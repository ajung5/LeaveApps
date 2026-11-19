<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\LeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    public function pdf(Request $request,LeaveRequest $leave,string $kind)
    {
        $this->permit($request,$leave);
        abort_unless(in_array($kind,['form','application','permission','bundle'],true),404);
        if (in_array($kind,['permission','bundle'],true)) abort_unless($leave->status==='issued',403);
        $leave->load('employee.unit','type','decisions');
        $snapshot=$leave->employee_snapshot;
        $employee=$snapshot ?: [
            'name'=>$leave->employee->full_name,'nip'=>$leave->employee->nip,
            'rank'=>$leave->employee->rank,'grade'=>$leave->employee->grade,
            'position'=>$leave->employee->position,'unit'=>$leave->employee->unit?->name ?? '',
            'service_start_date'=>$leave->employee->service_start_date?->toDateString(),
            'supervisor_name'=>$leave->employee->supervisor_name,
            'supervisor_nip'=>$leave->employee->supervisor_nip,
            'supervisor_position'=>$leave->employee->supervisor_position,
        ];
        $settings=$leave->official_snapshot ?: [
            'government_name'=>AppSetting::read('government_name'),
            'institution_name'=>AppSetting::read('institution_name'),
            'institution_address'=>AppSetting::read('institution_address'),
            'institution_phone'=>AppSetting::read('institution_phone'),
            'institution_city'=>AppSetting::read('institution_city'),
            'authority_name'=>AppSetting::read('authority_name'),
            'authority_nip'=>AppSetting::read('authority_nip'),
            'authority_position'=>AppSetting::read('authority_position'),
            'authority_rank'=>AppSetting::read('authority_rank'),
        ];
        $logo=base64_encode(file_get_contents(public_path('assets/img/lambang-subang.jpeg')));
        $data=compact('leave','employee','settings','logo','kind');
        $pdf=Pdf::loadView('pdf.document',$data)
            ->setPaper('a4','portrait')
            ->setOption('isRemoteEnabled',false);
        $pdf->render();
        $pageCount=$pdf->getDomPDF()->getCanvas()->get_page_count();
        $expected=$kind==='bundle'?3:1;
        abort_if($pageCount!==$expected,422,'Dokumen tidak muat pada format A4 satu halaman per surat. Ringkas alasan/alamat, atau sesuaikan template cetak.');
        return $pdf->stream($kind.'-'.$leave->request_number.'.pdf');
    }

    public function attachment(Request $request,LeaveRequest $leave,int $attachment)
    {
        $this->permit($request,$leave);
        $file=$leave->attachments()->findOrFail($attachment);
        abort_unless(Storage::disk('local')->exists($file->path),404);
        return Storage::disk('local')->download($file->path,$file->original_name);
    }

    public function signed(Request $request,LeaveRequest $leave)
    {
        $this->permit($request,$leave);
        abort_unless($leave->status==='issued' && $leave->signed_document_path,404);
        abort_unless(Storage::disk('local')->exists($leave->signed_document_path),404);
        return Storage::disk('local')->download($leave->signed_document_path,'signed-'.$leave->request_number.'.pdf');
    }

    private function permit(Request $request,LeaveRequest $leave): void
    {
        abort_unless($request->user()->isAdmin() || $leave->employee->user_id===$request->user()->id,403);
    }
}
