<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $admin=$request->user()->isAdmin();
        $q=LeaveRequest::query()->with('employee','type');
        if (! $admin) $q->where('employee_id',$request->user()->employee?->id ?? 0);
        $items=(clone $q)->latest()->paginate(15);
        $stats=[
            'total'=>(clone $q)->count(),
            'pending'=>(clone $q)->whereIn('status',['submitted','awaiting_supervisor','awaiting_authority','approved'])->count(),
            'issued'=>(clone $q)->where('status','issued')->count(),
        ];
        $employee=$request->user()->employee;
        $balance=$employee ? LeaveBalance::where('employee_id',$employee->id)
            ->whereBetween('year',[now()->year-2,now()->year])->sum('remaining_days') : 0;
        return view('dashboard',compact('items','stats','balance','admin','employee'));
    }
}
