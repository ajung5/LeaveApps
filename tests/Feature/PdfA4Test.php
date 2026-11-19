<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfA4Test extends TestCase
{
    use RefreshDatabase;

    public function test_all_final_pdfs_render_with_expected_one_page_per_document(): void
    {
        $this->seed(DatabaseSeeder::class);
        $user=User::create(['name'=>'Pemohon Contoh','email'=>'sample@example.test',
            'password'=>'password-kuat-panjang','role'=>'user']);
        $employee=Employee::create([
            'user_id'=>$user->id,'nip'=>'199001012020011001','full_name'=>'PEMOHON CONTOH, S.Kom.',
            'position'=>'Pranata Komputer Ahli Pertama','rank'=>'Penata Muda Tk. I','grade'=>'III/b',
            'supervisor_name'=>'ATASAN CONTOH','supervisor_nip'=>'198001012001011001',
            'supervisor_position'=>'Kepala Bidang',
        ]);
        $type=LeaveType::where('code','TAHUNAN')->firstOrFail();
        $leave=LeaveRequest::create([
            'request_number'=>'CT-PDF-TEST-001','employee_id'=>$employee->id,'leave_type_id'=>$type->id,
            'start_date'=>'2026-11-25','end_date'=>'2026-11-27','duration_days'=>3,
            'reason'=>'Cuti untuk keperluan keluarga','leave_address'=>'Subang, Jawa Barat',
            'contact_phone'=>'081234567890','status'=>'issued','issued_at'=>'2026-11-19 09:00:00',
            'letter_number'=>'KP.06.03/0001/Sekre/2026',
        ]);
        $admin=User::create(['name'=>'Admin Contoh','email'=>'admin2@example.test',
            'password'=>'password-kuat-panjang','role'=>'admin']);
        $leave->decisions()->create([
            'recorded_by'=>$admin->id,'stage'=>'supervisor','decision'=>'approved',
            'official_name'=>'ATASAN CONTOH','official_position'=>'Kepala Bidang',
            'decision_date'=>'2026-11-18',
        ]);
        $leave->decisions()->create([
            'recorded_by'=>$admin->id,'stage'=>'authority','decision'=>'approved',
            'official_name'=>'PEJABAT CONTOH','official_position'=>'Kepala Dinas',
            'decision_date'=>'2026-11-19',
        ]);
        $this->actingAs($user);
        foreach (['form','application','permission','bundle'] as $kind) {
            $response = $this->get(route('leaves.pdf', [$leave, $kind]));
            $response->assertOk()
                ->assertHeader('content-type', 'application/pdf')
                ->assertHeader('X-Cuti-Pdf-Pages', $kind === 'bundle' ? '3' : '1');
            $this->assertStringStartsWith('%PDF-', $response->getContent(), "Output {$kind} bukan PDF valid.");
        }
    }
}
