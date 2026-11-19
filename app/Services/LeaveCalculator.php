<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Validation\ValidationException;

class LeaveCalculator
{
    public function count(LeaveType $type, string $start, string $end): int
    {
        $from=CarbonImmutable::parse($start); $to=CarbonImmutable::parse($end);
        if ($to->lessThan($from) || $from->diffInDays($to)>3660) {
            throw ValidationException::withMessages(['end_date'=>'Rentang tanggal tidak valid (maksimum 10 tahun).']);
        }
        // Tahun penjatahan yang berbeda harus diproses terpisah untuk cuti tahunan.
        if ($type->uses_balance && $from->year !== $to->year) {
            throw ValidationException::withMessages(['end_date'=>'Cuti tahunan lintas tahun harus diajukan terpisah per tahun.']);
        }
        if ($type->count_mode === 'calendar') return (int)$from->diffInDays($to)+1;
        $holidayDates=Holiday::query()->whereBetween('date',[$start,$end])->pluck('date')->all();
        $holidayMap=array_fill_keys($holidayDates,true);
        $days=0;
        foreach (CarbonPeriod::create($from,$to) as $day) {
            if (! $day->isWeekend() && ! isset($holidayMap[$day->toDateString()])) $days++;
        }
        if ($days===0) throw ValidationException::withMessages(['start_date'=>'Tidak ada hari kerja di rentang ini.']);
        return $days;
    }

    public function assertNotOverlapping(int $employeeId,string $start,string $end,?int $exceptId=null): void
    {
        $q=LeaveRequest::query()->where('employee_id',$employeeId)
            ->whereNotIn('status',['rejected','deferred','cancelled'])
            ->whereDate('start_date','<=',$end)->whereDate('end_date','>=',$start);
        if ($exceptId) $q->whereKeyNot($exceptId);
        if ($q->exists()) throw ValidationException::withMessages(['start_date'=>'Tanggal berbenturan dengan permohonan lain yang masih aktif.']);
    }
}
