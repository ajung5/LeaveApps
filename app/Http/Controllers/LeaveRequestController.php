<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\LeaveCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LeaveRequestController extends Controller {
    public function create(Request $request): View {
        abort_unless($request->user()->employee, 403, 'Admin harus memiliki profil pegawai untuk mengajukan cuti.');
        $types = LeaveType::where('is_active', true)->orderBy('id')->get();
        return view('leaves.form', ['leave' => null, 'types' => $types, 'employee' => $request->user()->employee]);
    }

    public function store(Request $request, LeaveCalculator $calculator): RedirectResponse {
        $employee = $request->user()->employee;
        abort_unless($employee, 403);
        $data = $this->validated($request);
        unset($data['attachment']);
        $type = LeaveType::whereKey($data['leave_type_id'])->where('is_active', true)->firstOrFail();
        $this->checkAttachment($request, $type);
        $days = $calculator->count($type, $data['start_date'], $data['end_date']);
        $calculator->assertNotOverlapping($employee->id, $data['start_date'], $data['end_date']);
        $leave = DB::transaction(function () use ($request, $employee, $data, $days, $calculator) {
            Employee::whereKey($employee->id)->lockForUpdate()->firstOrFail();
            $calculator->assertNotOverlapping($employee->id, $data['start_date'], $data['end_date']);
            $leave = LeaveRequest::create([
                ...$data,
                'employee_id' => $employee->id,
                'duration_days' => $days,
                'status' => 'submitted',
                'submitted_at' => now(),
                'request_number' => 'CT-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)))
            ]);
            $this->storeAttachment($request, $leave);
            AuditLog::record('leave.submitted', $leave);
            return $leave;
        });
        return redirect()->route('leaves.show', $leave)->with('ok', 'Permohonan berhasil dikirim.');
    }

    public function show(Request $request, LeaveRequest $leave): View {
        $this->authorizeView($request, $leave);
        $leave->load('employee.unit', 'type', 'decisions', 'attachments');
        return view('leaves.show', compact('leave'));
    }

    public function edit(Request $request, LeaveRequest $leave): View {
        $this->authorizeEdit($request, $leave);
        return view('leaves.form', [
            'leave' => $leave,
            'types' => LeaveType::where('is_active', true)->get(),
            'employee' => $leave->employee
        ]);
    }

    public function update(Request $request, LeaveRequest $leave, LeaveCalculator $calculator): RedirectResponse {
        $this->authorizeEdit($request, $leave);
        $data = $this->validated($request);
        unset($data['attachment']);
        $type = LeaveType::whereKey($data['leave_type_id'])->where('is_active', true)->firstOrFail();
        $this->checkAttachment($request, $type, $leave);
        $days = $calculator->count($type, $data['start_date'], $data['end_date']);
        $calculator->assertNotOverlapping($leave->employee_id, $data['start_date'], $data['end_date'], $leave->id);
        DB::transaction(function () use ($leave, $data, $days, $request, $calculator) {
            Employee::whereKey($leave->employee_id)->lockForUpdate()->firstOrFail();
            $calculator->assertNotOverlapping($leave->employee_id, $data['start_date'], $data['end_date'], $leave->id);
            $locked = LeaveRequest::query()->lockForUpdate()->findOrFail($leave->id);
            abort_unless($locked->status === 'revision_required', 403);
            $locked->update([
                ...$data,
                'duration_days' => $days,
                'revision_notes' => null,
                'status' => 'submitted',
                'submitted_at' => now()
            ]);
            AuditLog::record('leave.previous_decisions_archived', $locked, [
                'decisions' => $locked->decisions()->get()->toArray()
            ]);
            $locked->decisions()->delete();
            $this->storeAttachment($request, $locked);
            AuditLog::record('leave.resubmitted', $locked);
        });
        return redirect()->route('leaves.show', $leave)->with('ok', 'Revisi permohonan telah dikirim.');
    }

    public function cancel(Request $request, LeaveRequest $leave): RedirectResponse {
        $this->authorizeView($request, $leave);
        abort_unless($leave->employee->user_id === $request->user()->id, 403);
        DB::transaction(function () use ($leave) {
            $locked = LeaveRequest::lockForUpdate()->findOrFail($leave->id);
            abort_unless(
                in_array(
                    $locked->status,
                    ['submitted', 'revision_required', 'awaiting_supervisor', 'awaiting_authority'],
                    true
                ),
                403
            );
            $locked->update(['status' => 'cancelled']);
            AuditLog::record('leave.cancelled', $locked);
        });
        return redirect()->route('dashboard')->with('ok', 'Permohonan dibatalkan.');
    }

    private function validated(Request $request): array {
        return $request->validate(
            [
                'leave_type_id' => ['required', 'integer', 'exists:leave_types,id'],
                'start_date' => ['required', 'date_format:Y-m-d'],
                'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
                'reason' => ['required', 'string', 'min:4', 'max:350'],
                'leave_address' => ['required', 'string', 'max:300'],
                'contact_phone' => ['required', 'string', 'max:25', 'regex:/^[0-9+()\s-]+$/'],
                'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']
            ],
            ['contact_phone.regex' => 'Format nomor telepon tidak valid.']
        );
    }

    private function checkAttachment(Request $request, LeaveType $type, ?LeaveRequest $leave = null): void {
        if (
            $type->requires_attachment &&
            !$request->hasFile('attachment') &&
            !($leave && $leave->leave_type_id === $type->id && $leave->attachments()->exists())
        ) {
            throw ValidationException::withMessages(['attachment' => 'Jenis cuti ini mewajibkan lampiran.']);
        }
    }

    private function storeAttachment(Request $request, LeaveRequest $leave): void {
        if (!$request->hasFile('attachment')) {
            return;
        }
        $file = $request->file('attachment');
        $leave->attachments()->create([
            'path' => $file->store('leave-attachments', 'local'),
            'original_name' => basename($file->getClientOriginalName())
        ]);
    }

    private function authorizeView(Request $request, LeaveRequest $leave): void {
        abort_unless($request->user()->isAdmin() || $leave->employee->user_id === $request->user()->id, 403);
    }

    private function authorizeEdit(Request $request, LeaveRequest $leave): void {
        abort_unless($leave->employee->user_id === $request->user()->id && $leave->status === 'revision_required', 403);
    }
}
