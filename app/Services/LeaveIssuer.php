<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\AuditLog;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveIssuer
{
    public function issue(LeaveRequest $leave, int $actorId): LeaveRequest
    {
        return DB::transaction(function () use ($leave,$actorId) {
            $locked=LeaveRequest::query()->lockForUpdate()->findOrFail($leave->id);
            if ($locked->status !== 'approved') {
                throw ValidationException::withMessages(['status'=>'Hanya permohonan berstatus Disetujui yang dapat diterbitkan.']);
            }
            $locked->load('employee.unit','type','decisions');
            $authority=$locked->decisions->firstWhere('stage','authority');
            if (! $authority || $authority->decision !== 'approved') {
                throw ValidationException::withMessages(['status'=>'Keputusan pejabat berwenang belum lengkap.']);
            }
            if ($locked->type->uses_balance) $this->debitAnnualBalance($locked,$actorId);
            $year=now()->year;
            // INSERT OR IGNORE di sini aman untuk dua penerbitan pertama bersamaan.
            DB::table('document_sequences')->insertOrIgnore(['year'=>$year,'last_number'=>0]);
            $seq=DB::table('document_sequences')->where('year',$year)->lockForUpdate()->first();
            $number=$seq->last_number+1;
            DB::table('document_sequences')->where('year',$year)->update(['last_number'=>$number]);
            $locked->letter_number=AppSetting::read('letter_prefix','KP.06.03').'/'.str_pad($number,4,'0',STR_PAD_LEFT).'/'.AppSetting::read('letter_suffix','Sekre').'/'.$year;
            $locked->issued_at=now(); $locked->status='issued';
            $e=$locked->employee;
            $locked->employee_snapshot=[
                'name'=>$e->full_name,'nip'=>$e->nip,'employment_type'=>$e->employment_type,
                'rank'=>$e->rank,'grade'=>$e->grade,'position'=>$e->position,
                'unit'=>$e->unit?->name ?? '', 'service_start_date'=>$e->service_start_date?->toDateString(),
                'supervisor_name'=>$e->supervisor_name,'supervisor_nip'=>$e->supervisor_nip,
                'supervisor_position'=>$e->supervisor_position,
                'balance_snapshot'=>\App\Models\LeaveBalance::where('employee_id',$e->id)
                    ->whereBetween('year',[$locked->start_date->year-2,$locked->start_date->year])
                    ->pluck('remaining_days','year')->toArray(),
            ];
            $locked->official_snapshot=[
                'government_name'=>AppSetting::read('government_name'),
                'institution_name'=>AppSetting::read('institution_name'),
                'institution_address'=>AppSetting::read('institution_address'),
                'institution_phone'=>AppSetting::read('institution_phone'),
                'institution_city'=>AppSetting::read('institution_city','Subang'),
                'authority_name'=>$authority->official_name,
                'authority_nip'=>$authority->official_nip,
                'authority_position'=>$authority->official_position,
                'authority_rank'=>AppSetting::read('authority_rank'),
            ];
            $locked->save(); AuditLog::record('leave.issued',$locked,['number'=>$locked->letter_number]);
            return $locked;
        }, 3);
    }

    private function debitAnnualBalance(LeaveRequest $request,int $actorId): void
    {
        $year=$request->start_date->year;
        // Penentuan kelayakan sisa N-2/N-1 tetap wajib diverifikasi Admin sesuai aturan pegawai.
        $balances=LeaveBalance::query()->where('employee_id',$request->employee_id)
            ->whereBetween('year',[$year-2,$year])->orderBy('year')->lockForUpdate()->get();
        if ($balances->sum('remaining_days') < $request->duration_days) {
            throw ValidationException::withMessages(['balance'=>'Saldo cuti tidak cukup pada saat penerbitan.']);
        }
        $remaining=$request->duration_days;
        foreach ($balances as $balance) {
            $spent=min($balance->remaining_days,$remaining);
            if ($spent<=0) continue;
            $balance->decrement('remaining_days',$spent);
            LeaveBalanceTransaction::create(['leave_balance_id'=>$balance->id,'leave_request_id'=>$request->id,
                'actor_id'=>$actorId,'delta'=>-$spent,'type'=>'usage',
                'note'=>'Pemakaian melalui surat '.$request->request_number]);
            $remaining-=$spent;
            if ($remaining===0) break;
        }
    }
}
