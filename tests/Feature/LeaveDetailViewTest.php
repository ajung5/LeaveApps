<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveDetailViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function fixture(): array
    {
        $owner = User::create([
            'name' => 'Pegawai Contoh',
            'email' => 'pegawai-detail@example.test',
            'password' => 'password-sangat-panjang',
            'role' => 'user',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'user_id' => $owner->id,
            'nip' => '199001012020011001',
            'full_name' => 'Pegawai Contoh',
            'position' => 'Pranata Komputer',
            'supervisor_name' => 'Atasan Contoh',
            'supervisor_position' => 'Kepala Bidang',
        ]);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin-detail@example.test',
            'password' => 'password-sangat-panjang',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $type = LeaveType::where('code', 'TAHUNAN')->firstOrFail();

        $leave = LeaveRequest::create([
            'request_number' => 'CT-VIEW-TEST-001',
            'employee_id' => $employee->id,
            'leave_type_id' => $type->id,
            'start_date' => '2026-11-25',
            'end_date' => '2026-11-27',
            'duration_days' => 3,
            'reason' => 'Cuti keluarga',
            'leave_address' => 'Subang',
            'contact_phone' => '081234567890',
            'status' => 'submitted',
        ]);

        return [$owner, $admin, $leave];
    }

    public function test_owner_can_open_submitted_leave_detail_with_attachment(): void
    {
        [$owner, , $leave] = $this->fixture();
        $leave->attachments()->create([
            'path' => 'leave-attachments/contoh.pdf',
            'original_name' => 'Lampiran contoh.pdf',
        ]);

        $this->actingAs($owner)
            ->get(route('leaves.show', $leave))
            ->assertOk()
            ->assertSee('Detail Permohonan Cuti')
            ->assertSee($leave->request_number)
            ->assertSee('Lampiran contoh.pdf');
    }

    public function test_admin_can_open_leave_detail_with_decision_history(): void
    {
        [, $admin, $leave] = $this->fixture();
        $leave->update(['status' => 'awaiting_authority']);
        $leave->decisions()->create([
            'recorded_by' => $admin->id,
            'stage' => 'supervisor',
            'decision' => 'approved',
            'official_name' => 'Atasan Contoh',
            'official_position' => 'Kepala Bidang',
            'reference' => 'REF-SUP-001',
            'notes' => 'Pengajuan memenuhi persyaratan',
            'decision_date' => '2026-11-19',
        ]);

        $this->actingAs($admin)
            ->get(route('leaves.show', $leave))
            ->assertOk()
            ->assertSee('Riwayat Keputusan')
            ->assertSee('REF-SUP-001')
            ->assertSee('Simpan Keputusan');
    }

    public function test_detail_renders_for_all_workflow_statuses_and_both_roles(): void
    {
        [$owner, $admin, $leave] = $this->fixture();

        foreach ([
            'submitted', 'revision_required', 'awaiting_supervisor',
            'awaiting_authority', 'approved', 'issued', 'rejected',
            'deferred', 'cancelled',
        ] as $status) {
            $leave->update([
                'status' => $status,
                'revision_notes' => $status === 'revision_required' ? 'Perbaiki alasan cuti' : null,
            ]);

            $this->actingAs($owner)
                ->get(route('leaves.show', $leave))
                ->assertOk();

            $this->actingAs($admin)
                ->get(route('leaves.show', $leave))
                ->assertOk();
        }
    }
}
