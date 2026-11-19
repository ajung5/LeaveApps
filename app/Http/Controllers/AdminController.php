<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\LeaveDecision;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\OrganizationalUnit;
use App\Models\User;
use App\Services\LeaveIssuer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminController extends Controller
{

    public function units(): View
    {
        return view('admin.units.index',['units'=>OrganizationalUnit::orderBy('name')->get()]);
    }

    public function unitStore(Request $request): RedirectResponse
    {
        $data=$request->validate(['name'=>['required','string','max:180','unique:organizational_units,name']]);
        $unit=OrganizationalUnit::create($data);
        AuditLog::record('unit.created',$unit);
        return back()->with('ok','Unit kerja ditambahkan.');
    }

    public function unitUpdate(Request $request,OrganizationalUnit $unit): RedirectResponse
    {
        $data=$request->validate(['name'=>['required','string','max:180',Rule::unique('organizational_units','name')->ignore($unit->id)]]);
        $unit->update($data);
        AuditLog::record('unit.updated',$unit);
        return back()->with('ok','Nama unit kerja diperbarui.');
    }

    public function employees(Request $request): View
    {
        $q=Employee::with('user','unit');
        if ($request->filled('q')) $q->where(function ($query) use ($request) {
            $term='%'.addcslashes($request->query('q'),'%_\\').'%';
            $query->where('full_name','like',$term)->orWhere('nip','like',$term);
        });
        return view('admin.employees.index',['employees'=>$q->orderBy('full_name')->paginate(20)]);
    }

    public function employeeCreate(): View
    {
        return view('admin.employees.form',['employee'=>null,'units'=>OrganizationalUnit::orderBy('name')->get()]);
    }

    public function employeeEdit(Employee $employee): View
    {
        $employee->load('user','balances');
        return view('admin.employees.form',['employee'=>$employee,'units'=>OrganizationalUnit::orderBy('name')->get()]);
    }

    public function employeeStore(Request $request): RedirectResponse
    {
        $data=$this->employeeData($request);
        DB::transaction(function () use ($data) {
            $user=User::create(['name'=>$data['full_name'],'email'=>$data['email'],
                'password'=>$data['password'],'role'=>'user','is_active'=>true]);
            $employee=$user->employee()->create($this->employeeFields($data));
            AuditLog::record('employee.created',$employee);
        });
        return redirect()->route('admin.employees')->with('ok','Pegawai dan akun pengguna dibuat.');
    }

    public function employeeUpdate(Request $request,Employee $employee): RedirectResponse
    {
        $data=$this->employeeData($request,$employee);
        DB::transaction(function () use ($data,$employee) {
            $user=$employee->user;
            $changes=['name'=>$data['full_name'],'email'=>$data['email'],'is_active'=>$data['is_active']];
            if (! empty($data['password'])) $changes['password']=$data['password'];
            $user->update($changes);
            $employee->update($this->employeeFields($data));
            AuditLog::record('employee.updated',$employee);
        });
        return back()->with('ok','Data pegawai diperbarui.');
    }

    private function employeeData(Request $request,?Employee $employee=null): array
    {
        return $request->validate([
            'full_name'=>['required','string','max:150'],
            'nip'=>['required','string','max:30',Rule::unique('employees','nip')->ignore($employee?->id)],
            'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($employee?->user_id)],
            'password'=>$employee ? ['nullable','string','min:12','max:128'] : ['required','string','min:12','max:128'],
            'is_active'=>['sometimes','boolean'],
            'employment_type'=>['required',Rule::in(['PNS','PPPK'])],
            'organizational_unit_id'=>['nullable','exists:organizational_units,id'],
            'position'=>['required','string','max:180'],
            'rank'=>['nullable','string','max:130'],'grade'=>['nullable','string','max:30'],
            'service_start_date'=>['nullable','date_format:Y-m-d','before_or_equal:today'],
            'phone'=>['nullable','string','max:25'],'leave_address'=>['nullable','string','max:1000'],
            'supervisor_name'=>['nullable','string','max:150'],
            'supervisor_nip'=>['nullable','string','max:30'],
            'supervisor_position'=>['nullable','string','max:180'],
        ]);
    }

    private function employeeFields(array $data): array
    {
        $keys=['full_name','nip','employment_type','organizational_unit_id','position','rank','grade',
            'service_start_date','phone','leave_address','supervisor_name','supervisor_nip','supervisor_position'];
        return array_intersect_key($data,array_flip($keys));
    }

    public function balanceUpdate(Request $request,Employee $employee): RedirectResponse
    {
        $data=$request->validate([
            'year'=>['required','integer','between:2000,2100'],
            'delta'=>['required','integer','between:-365,365','not_in:0'],
            'note'=>['required','string','min:5','max:500'],
        ]);
        DB::transaction(function () use ($request,$employee,$data) {
            $balance=LeaveBalance::firstOrCreate(['employee_id'=>$employee->id,'year'=>$data['year']],['remaining_days'=>0]);
            $balance=LeaveBalance::whereKey($balance->id)->lockForUpdate()->firstOrFail();
            if ($balance->remaining_days+$data['delta'] < 0) {
                throw ValidationException::withMessages(['delta'=>'Koreksi menghasilkan saldo negatif.']);
            }
            $balance->increment('remaining_days',$data['delta']);
            LeaveBalanceTransaction::create(['leave_balance_id'=>$balance->id,'actor_id'=>$request->user()->id,
                'delta'=>$data['delta'],'type'=>'admin_adjustment','note'=>$data['note']]);
            AuditLog::record('balance.adjusted',$balance,$data);
        });
        return back()->with('ok','Penyesuaian saldo tercatat dalam ledger.');
    }

    public function types(): View {return view('admin.types.index',['types'=>LeaveType::orderBy('id')->get()]);}

    public function typeUpdate(Request $request,LeaveType $type): RedirectResponse
    {
        $data=$request->validate([
            'name'=>['required','string','max:120'],
            'count_mode'=>['required',Rule::in(['working','calendar'])],
            'requires_attachment'=>['required','boolean'], 'is_active'=>['required','boolean'],
        ]);
        $type->update($data); AuditLog::record('leave_type.updated',$type,$data);
        return back()->with('ok','Pengaturan jenis cuti tersimpan.');
    }

    public function holidays(): View
    {
        return view('admin.holidays.index',['holidays'=>Holiday::orderByDesc('date')->paginate(40)]);
    }
    public function holidayStore(Request $request): RedirectResponse
    {
        $data=$request->validate(['date'=>['required','date_format:Y-m-d','unique:holidays,date'],
            'name'=>['required','string','max:150']]);
        $holiday=Holiday::create($data); AuditLog::record('holiday.created',$holiday);
        return back()->with('ok','Hari libur ditambahkan.');
    }
    public function holidayDestroy(Holiday $holiday): RedirectResponse
    {
        AuditLog::record('holiday.deleted',$holiday,['date'=>$holiday->date->toDateString()]);
        $holiday->delete(); return back()->with('ok','Hari libur dihapus.');
    }

    public function settings(): View
    {
        $keys=['institution_name','government_name','institution_address','institution_phone',
            'institution_city','authority_name','authority_nip','authority_position','authority_rank',
            'letter_prefix','letter_suffix'];
        return view('admin.settings.index',['settings'=>collect($keys)->mapWithKeys(fn($k)=>[$k=>AppSetting::read($k)])]);
    }
    public function settingsUpdate(Request $request): RedirectResponse
    {
        $data=$request->validate([
            'government_name'=>['required','string','max:180'],
            'institution_name'=>['required','string','max:180'],
            'institution_address'=>['required','string','max:250'],
            'institution_phone'=>['nullable','string','max:180'],
            'institution_city'=>['required','string','max:80'],
            'authority_name'=>['nullable','string','max:150'],
            'authority_nip'=>['nullable','string','max:30'],
            'authority_position'=>['required','string','max:180'],
            'authority_rank'=>['nullable','string','max:120'],
            'letter_prefix'=>['required','string','max:40','regex:/^[A-Za-z0-9.\-]+$/'],
            'letter_suffix'=>['required','string','max:40','regex:/^[A-Za-z0-9.\-]+$/'],
        ]);
        DB::transaction(function () use ($data) {
            foreach ($data as $key=>$val) AppSetting::updateOrCreate(['key'=>$key],['value'=>$val]);
        });
        return back()->with('ok','Pengaturan tersimpan.');
    }

    public function verify(Request $request,LeaveRequest $leave): RedirectResponse
    {
        $this->otherPerson($request,$leave);
        DB::transaction(function () use ($leave) {
            $locked=LeaveRequest::lockForUpdate()->findOrFail($leave->id);
            abort_unless($locked->status==='submitted',409,'Status sudah berubah.');
            $locked->update(['status'=>'awaiting_supervisor']); AuditLog::record('leave.verified',$locked);
        });
        return back()->with('ok','Verifikasi selesai. Menunggu pertimbangan atasan langsung.');
    }

    public function decision(Request $request,LeaveRequest $leave): RedirectResponse
    {
        $this->otherPerson($request,$leave);
        $data=$request->validate([
            'stage'=>['required',Rule::in(['supervisor','authority'])],
            'decision'=>['required',Rule::in(['approved','change','deferred','rejected'])],
            'decision_date'=>['required','date_format:Y-m-d'],
            'reference'=>['required_if:decision,approved','nullable','string','max:180'],
            'notes'=>['nullable','string','max:1500'],
        ]);
        DB::transaction(function () use ($request,$leave,$data) {
            $locked=LeaveRequest::lockForUpdate()->with('employee')->findOrFail($leave->id);
            $expected=$data['stage']==='supervisor'?'awaiting_supervisor':'awaiting_authority';
            abort_unless($locked->status===$expected,409,'Tahapan keputusan tidak sesuai.');
            $employee=$locked->employee;
            if ($data['stage']==='supervisor') {
                $name=$employee->supervisor_name;
                $nip=$employee->supervisor_nip;
                $position=$employee->supervisor_position;
            } else {
                $name=AppSetting::read('authority_name');
                $nip=AppSetting::read('authority_nip');
                $position=AppSetting::read('authority_position');
            }
            if (! $name || ! $position) {
                throw ValidationException::withMessages(['stage'=>'Nama/jabatan pejabat belum diatur pada profil pegawai atau pengaturan instansi.']);
            }
            LeaveDecision::updateOrCreate(['leave_request_id'=>$locked->id,'stage'=>$data['stage']],[
                'recorded_by'=>$request->user()->id,'decision'=>$data['decision'],'decision_date'=>$data['decision_date'],
                'reference'=>$data['reference']??null,'notes'=>$data['notes']??null,
                'official_name'=>$name,'official_nip'=>$nip,'official_position'=>$position,
            ]);
            $next=match ($data['decision']) {
                'change'=>'revision_required','deferred'=>'deferred','rejected'=>'rejected',
                'approved'=>$data['stage']==='supervisor'?'awaiting_authority':'approved',
            };
            $locked->update(['status'=>$next,'revision_notes'=>$data['decision']==='change'?($data['notes']??'Perbaiki pengajuan.'):null]);
            AuditLog::record('leave.decision',$locked,['stage'=>$data['stage'],'decision'=>$data['decision']]);
        });
        return back()->with('ok','Keputusan dicatat.');
    }

    public function issue(Request $request,LeaveRequest $leave,LeaveIssuer $issuer): RedirectResponse
    {
        $this->otherPerson($request,$leave);
        $issuer->issue($leave,$request->user()->id);
        return back()->with('ok','Nomor surat diterbitkan dan saldo cuti tahunan (jika berlaku) telah dibukukan.');
    }

    public function signedUpload(Request $request,LeaveRequest $leave): RedirectResponse
    {
        $this->otherPerson($request,$leave);
        abort_unless($leave->status==='issued',403);
        $data=$request->validate(['signed_scan'=>['required','file','mimes:pdf','max:10240']]);
        $newPath=$data['signed_scan']->store('signed-leave-documents','local');
        $old=$leave->signed_document_path;
        $leave->update(['signed_document_path'=>$newPath]);
        AuditLog::record('leave.signed_scan_uploaded',$leave);
        if ($old) Storage::disk('local')->delete($old);
        return back()->with('ok','Scan dokumen bertanda tangan tersimpan secara privat.');
    }

    private function otherPerson(Request $request,LeaveRequest $leave): void
    {
        abort_if($leave->employee->user_id===$request->user()->id,403,'Admin tidak boleh memproses cutinya sendiri.');
    }
}
