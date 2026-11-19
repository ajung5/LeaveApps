@extends('layouts.app')
@section('title','Detail Permohonan')
@section('breadcrumb','Pengajuan / Detail')
@section('content')
<div class="page-header"><div><div class="eyebrow">LEAVE REQUEST / {{ $leave->request_number }}</div><h1>Detail Permohonan Cuti</h1><p class="muted">{{ $leave->employee->full_name }} · {{ $leave->type->name }}</p></div><a class="btn outline" href="{{ route('dashboard') }}">Kembali</a></div>
<div class="detail-grid"><section class="panel"><div class="panel-head"><h2>Informasi Permohonan</h2><span class="status status-{{ $leave->status }}">{{ str_replace('_',' ',ucwords($leave->status,'_')) }}</span></div>
<dl class="details"><dt>Nomor Permohonan</dt><dd>{{ $leave->request_number }}</dd><dt>Nama / NIP</dt><dd>{{ $leave->employee->full_name }} / {{ $leave->employee->nip }}</dd><dt>Jenis Cuti</dt><dd>{{ $leave->type->name }}</dd><dt>Periode</dt><dd>{{ $leave->start_date->translatedFormat('d F Y') }} – {{ $leave->end_date->translatedFormat('d F Y') }}</dd><dt>Durasi</dt><dd><strong>{{ $leave->duration_days }} hari ({{ $leave->type->count_mode==='working'?'kerja':'kalender' }})</strong></dd><dt>Alasan</dt><dd>{{ $leave->reason }}</dd><dt>Alamat Cuti</dt><dd>{{ $leave->leave_address }}</dd><dt>Kontak</dt><dd>{{ $leave->contact_phone }}</dd><dt>Nomor Surat</dt><dd>{{ $leave->letter_number?:'Belum diterbitkan' }}</dd></dl>
@if($leave->revision_notes)<div class="alert danger"><strong>Catatan perbaikan</strong><p>{{ $leave->revision_notes }}</p></div>@endif
@if($leave->attachments->isNotEmpty())<h3>Lampiran</h3>@foreach($leave->attachments as $file)<p><a class="text-link" href="{{ route('leaves.attachment',[$leave,$file->id]) }}">Unduh: {{ $file->original_name }}</a></p>@endforeach@endif
</section>
<section class="panel"><div class="panel-head"><h2>Dokumen PDF A4</h2></div><p class="muted small">Setiap format dirancang satu lembar A4. Dokumen yang belum diterbitkan akan menampilkan penanda DRAF.</p><div class="document-actions"><a class="btn outline" target="_blank" href="{{ route('leaves.pdf',[$leave,'form']) }}">Formulir Cuti · A4</a><a class="btn outline" target="_blank" href="{{ route('leaves.pdf',[$leave,'application']) }}">Surat Permohonan · A4</a>
@if($leave->status==='issued')<a class="btn outline" target="_blank" href="{{ route('leaves.pdf',[$leave,'permission']) }}">Surat Izin · A4</a><a class="btn primary" target="_blank" href="{{ route('leaves.pdf',[$leave,'bundle']) }}">PDF Gabungan (3 Halaman)</a>@else <p class="muted small">Surat izin dan PDF gabungan tersedia setelah penerbitan.</p>@endif
@if($leave->signed_document_path)<a class="text-link" href="{{ route('leaves.signed',$leave) }}">Unduh scan surat bertanda tangan</a>@endif</div>
<div class="subtle-warning">PDF yang belum ditandatangani pejabat berwenang bukan bukti izin cuti yang sah.</div>
@if($leave->decisions->isNotEmpty())<div class="divider"></div><h3>Riwayat Keputusan</h3>@foreach($leave->decisions as $d)<div class="decision-card"><strong>{{ $d->stage==='supervisor'?'Atasan Langsung':'Pejabat Berwenang' }}</strong><span class="status">{{ strtoupper($d->decision) }}</span><p>{{ $d->official_name }} · {{ $d->decision_date->format('d/m/Y') }}</p><p class="muted small">Ref: {{ $d->reference?:'—' }} @if($d->notes) | {{ $d->notes }} @endif</p></div>@endforeach@endif
</section></div>
@if($leave->employee->user_id===auth()->id() && $leave->status==='revision_required')<div class="panel"><h2>Revisi Diperlukan</h2><p>Perbaiki formulir sesuai catatan Admin.</p><a class="btn primary" href="{{ route('leaves.edit',$leave) }}">Perbaiki Permohonan</a></div>@endif
@if($leave->employee->user_id===auth()->id() && in_array($leave->status,['submitted','revision_required','awaiting_supervisor','awaiting_authority']))
<form method="POST" action="{{ route('leaves.cancel',$leave) }}" onsubmit="return confirm('Yakin ingin membatalkan permohonan ini?')">@csrf<button class="btn danger" type="submit">Batalkan Permohonan</button></form>@endif
@if(auth()->user()->isAdmin() && $leave->employee->user_id!==auth()->id())
<div class="panel admin-action"><div class="panel-head"><h2>Proses Administrasi</h2><span class="role-chip">KHUSUS ADMIN</span></div>
@if($leave->status==='submitted')<form action="{{ route('admin.leaves.verify',$leave) }}" method="POST">@csrf<p>Periksa identitas, persyaratan, benturan tanggal, dan hak cuti sebelum verifikasi.</p><button class="btn primary" type="submit">Verifikasi dan Teruskan</button></form>@endif
@if(in_array($leave->status,['awaiting_supervisor','awaiting_authority']))
<form action="{{ route('admin.leaves.decision',$leave) }}" method="POST" class="stack">@csrf
<input type="hidden" name="stage" value="{{ $leave->status==='awaiting_supervisor'?'supervisor':'authority' }}">
<p class="muted">Catat keputusan resmi {{ $leave->status==='awaiting_supervisor'?'atasan langsung':'pejabat berwenang' }} yang diperoleh di luar aplikasi. Admin tidak otomatis berwenang menyetujuinya.</p>
<div class="form-grid three"><label>Keputusan<select name="decision" required><option value="approved">Disetujui</option><option value="change">Perubahan</option><option value="deferred">Ditangguhkan</option><option value="rejected">Tidak Disetujui</option></select></label><label>Tanggal Keputusan<input type="date" name="decision_date" required value="{{ now()->toDateString() }}"></label><label>Nomor Referensi Keputusan<input type="text" name="reference" maxlength="180" placeholder="Wajib jika disetujui"></label></div>
<label>Catatan<textarea name="notes" rows="2" maxlength="1500" placeholder="Alasan perubahan/penangguhan/penolakan"></textarea></label><button class="btn primary" type="submit">Simpan Keputusan</button></form>
@endif
@if($leave->status==='approved')<form action="{{ route('admin.leaves.issue',$leave) }}" method="POST" onsubmit="return confirm('Terbitkan nomor surat dan bukukan pemakaian saldo?')">@csrf<p>Pastikan keputusan sah, data pejabat, serta kecukupan saldo cuti telah diverifikasi.</p><button class="btn primary" type="submit">Terbitkan Surat Izin</button></form>@endif
@if($leave->status==='issued')<form method="POST" enctype="multipart/form-data" action="{{ route('admin.leaves.signed.upload',$leave) }}" class="stack">@csrf<label>Arsip Scan Surat yang Sudah Ditandatangani (PDF, maks 10 MB)<input type="file" name="signed_scan" accept=".pdf" required></label><button class="btn primary" type="submit">Simpan Arsip Bertanda Tangan</button></form>@endif
</div>@endif
@endsection
