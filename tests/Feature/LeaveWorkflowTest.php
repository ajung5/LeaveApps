<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Services\LeaveCalculator;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

class LeaveWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // CSRF hanya dikecualikan pada feature test alur cuti ini,
        // tidak pada aplikasi yang digunakan pengguna.
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->seed(DatabaseSeeder::class);
    }

    private function staff(string $email='pegawai@example.test',string $role='user'): User
    {
        $user=User::create(['name'=>'Pegawai Contoh','email'=>$email,'password'=>'password-sangat-panjang',
            'role'=>$role,'is_active'=>true]);
        Employee::create(['user_id'=>$user->id,'nip'=>'199001012020011001',
            'full_name'=>'Pegawai Contoh','position'=>'Analis Sistem',
            'supervisor_name'=>'Atasan Contoh','supervisor_position'=>'Kepala Bidang']);
        return $user;
    }

    public function test_guest_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_user_cannot_visit_admin_area(): void
    {
        $user=$this->staff();
        $this->actingAs($user)->get(route('admin.employees'))->assertForbidden();
    }

    public function test_annual_duration_counts_workdays(): void
    {
        $type=LeaveType::where('code','TAHUNAN')->firstOrFail();
        $days=app(LeaveCalculator::class)->count($type,'2026-11-25','2026-11-27');
        $this->assertSame(3,$days);
    }

    public function test_user_can_submit_and_cannot_download_unissued_permission(): void
    {
        $user=$this->staff();
        $type=LeaveType::where('code','TAHUNAN')->firstOrFail();
        $data=['leave_type_id'=>$type->id,'start_date'=>'2026-11-25','end_date'=>'2026-11-27',
            'reason'=>'Cuti tahunan keluarga','leave_address'=>'Alamat contoh',
            'contact_phone'=>'081234567890'];
        $this->actingAs($user)->post(route('leaves.store'),$data)->assertRedirect();
        $leave=LeaveRequest::firstOrFail();
        $this->assertSame('submitted',$leave->status);
        $this->assertSame(3,$leave->duration_days);
        $this->get(route('leaves.pdf',[$leave,'permission']))->assertForbidden();
    }

    public function test_new_user_is_active_in_memory_but_disabled_user_is_rejected(): void
    {
        // Nilai default model harus sama dengan nilai default kolom database.
        $user=User::create(['name'=>'Pegawai Baru','email'=>'new@example.test',
            'password'=>'password-sangat-panjang','role'=>'user']);
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->fresh()->is_active);

        // Jangan mengendurkan kontrol akses untuk akun yang benar-benar dinonaktifkan.
        $user->update(['is_active'=>false]);
        $this->actingAs($user)->get(route('dashboard'))
            ->assertRedirect(route('login'))->assertSessionHasErrors('email');
    }

    public function test_user_cannot_view_other_users_request(): void
    {
        $owner=$this->staff();
        $type=LeaveType::where('code','TAHUNAN')->firstOrFail();
        $leave=LeaveRequest::create(['request_number'=>'CT-TEST-001','employee_id'=>$owner->employee->id,
            'leave_type_id'=>$type->id,'start_date'=>'2026-11-25','end_date'=>'2026-11-27',
            'duration_days'=>3,'reason'=>'Contoh cuti','leave_address'=>'Alamat contoh','contact_phone'=>'0812345',
            'status'=>'submitted']);
        $other=User::create(['name'=>'Pegawai Lain','email'=>'other@example.test',
            'password'=>'password-sangat-panjang','role'=>'user']);
        $this->assertTrue($other->is_active);
        $this->actingAs($other)->get(route('leaves.show',$leave))->assertForbidden();
    }

    public function test_issued_leave_debits_ledger_once(): void
    {
        $user=$this->staff();
        $admin=User::create(['name'=>'Admin','email'=>'admin@example.test','password'=>'password-sangat-panjang','role'=>'admin']);
        $type=LeaveType::where('code','TAHUNAN')->firstOrFail();
        $balance=LeaveBalance::create(['employee_id'=>$user->employee->id,'year'=>2026,'remaining_days'=>12]);
        $leave=LeaveRequest::create(['request_number'=>'CT-TEST-002','employee_id'=>$user->employee->id,
            'leave_type_id'=>$type->id,'start_date'=>'2026-11-25','end_date'=>'2026-11-27',
            'duration_days'=>3,'reason'=>'Cuti keluarga','leave_address'=>'Alamat contoh','contact_phone'=>'0812345',
            'status'=>'approved']);
        $leave->decisions()->create(['recorded_by'=>$admin->id,'stage'=>'authority','decision'=>'approved',
            'official_name'=>'Pejabat Contoh','official_position'=>'Kepala Dinas','reference'=>'REF-001',
            'decision_date'=>'2026-11-19']);
        $this->actingAs($admin)->post(route('admin.leaves.issue',$leave))
            ->assertRedirect()->assertSessionHas('ok')->assertSessionHasNoErrors();
        $this->assertSame(9,$balance->fresh()->remaining_days);
        $this->assertSame('issued',$leave->fresh()->status);
        $this->actingAs($admin)->post(route('admin.leaves.issue',$leave))->assertSessionHasErrors('status');
        $this->assertSame(9,$balance->fresh()->remaining_days);
        $this->assertSame(1,\App\Models\LeaveBalanceTransaction::where('leave_request_id',$leave->id)
            ->where('type','usage')->count());
    }
}
