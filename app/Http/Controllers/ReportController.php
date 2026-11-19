<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\OrganizationalUnit;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    private function query(Request $request)
    {
        $q=LeaveRequest::with('employee.unit','type');
        if ($request->filled('from')) $q->whereDate('start_date','>=',$request->query('from'));
        if ($request->filled('until')) $q->whereDate('end_date','<=',$request->query('until'));
        if ($request->filled('type')) $q->where('leave_type_id',$request->query('type'));
        if ($request->filled('status')) $q->where('status',$request->query('status'));
        if ($request->filled('unit')) $q->whereHas('employee',fn($e)=>$e->where('organizational_unit_id',$request->query('unit')));
        return $q->orderByDesc('id');
    }
    private function validateQuery(Request $request): void
    {
        $request->validate(['from'=>['nullable','date_format:Y-m-d'],'until'=>['nullable','date_format:Y-m-d'],
            'type'=>['nullable','integer','exists:leave_types,id'], 'unit'=>['nullable','integer','exists:organizational_units,id'],
            'status'=>['nullable','in:submitted,revision_required,awaiting_supervisor,awaiting_authority,approved,rejected,deferred,issued,cancelled']]);
    }
    public function index(Request $request): View
    {
        $this->validateQuery($request);
        return view('admin.reports.index',['items'=>$this->query($request)->paginate(30)->withQueryString(),
            'types'=>LeaveType::orderBy('id')->get(),'units'=>OrganizationalUnit::orderBy('name')->get()]);
    }
    public function csv(Request $request): StreamedResponse
    {
        $this->validateQuery($request);
        $query=$this->query($request);
        // Prefix quoted text with apostrophe when it could become an Excel formula.
        $safe=fn($v)=>is_string($v) && preg_match('/^\s*[=+\-@]/u',$v)?"'".$v:$v;
        return response()->streamDownload(function () use ($query,$safe) {
            $out=fopen('php://output','w');
            fwrite($out,"\xEF\xBB\xBF");
            fputcsv($out,['Nomor','Nama','NIP','Unit','Jenis Cuti','Mulai','Selesai','Durasi','Status','Nomor Surat']);
            $query->chunk(200,function ($items) use ($out,$safe) {
                foreach ($items as $l) fputcsv($out,array_map($safe,[$l->request_number,$l->employee->full_name,
                    $l->employee->nip,$l->employee->unit?->name,$l->type->name,$l->start_date->format('Y-m-d'),
                    $l->end_date->format('Y-m-d'),$l->duration_days,$l->status,$l->letter_number]));
            });
            fclose($out);
        },'laporan-cuti-'.now()->format('Ymd-His').'.csv',['Content-Type'=>'text/csv; charset=UTF-8']);
    }
}
