@extends('layouts.app')
@section('title',$employee?'Ubah Pegawai':'Tambah Pegawai')
@section('breadcrumb','Admin / Pegawai / Formulir')
@section('content')
<div class="page-header"><div><div class="eyebrow">MASTER DATA / PEGAWAI</div><h1>{{ $employee?'Ubah Data Pegawai':'Tambah Pegawai' }}</h1><p class="muted">Identitas ini akan menjadi sumber otomatis formulir permohonan cuti.</p></div><a class="btn outline" href="{{ route('admin.employees') }}">Kembali</a></div>
<form method="POST" action="{{ $employee?route('admin.employees.update',$employee):route('admin.employees.store') }}" class="stack">@csrf @if($employee) @method('PUT') @endif
<div class="panel form-section"><div class="section-heading"><span class="section-number">01</span><h2>Identitas dan Akun</h2></div><div class="form-grid three">
<label>Nama Lengkap & Gelar*<input name="full_name" required maxlength="150" value="{{ old('full_name',$employee?->full_name) }}"></label>
<label>NIP*<input name="nip" required maxlength="30" value="{{ old('nip',$employee?->nip) }}"></label>
<label>Status Kepegawaian*<select name="employment_type"><option value="PNS" @selected(old('employment_type',$employee?->employment_type)==='PNS')>PNS</option><option value="PPPK" @selected(old('employment_type',$employee?->employment_type)==='PPPK')>PPPK</option></select></label>
<label>Email Login*<input name="email" type="email" required value="{{ old('email',$employee?->user?->email) }}"></label>
<label>Kata Sandi {{ $employee?'(kosongkan jika tetap)':'*' }}<input name="password" type="password" minlength="12" {{ $employee?'':'required' }} autocomplete="new-password" placeholder="Minimal 12 karakter"></label>
@if($employee)<label>Status Akun<select name="is_active"><option value="1" @selected(old('is_active',$employee->user->is_active)==1)>Aktif</option><option value="0" @selected(old('is_active',$employee->user->is_active)==0)>Nonaktif</option></select></label>@endif
</div></div>
<div class="panel form-section"><div class="section-heading"><span class="section-number">02</span><h2>Kepegawaian</h2></div><div class="form-grid three">
<label>Unit Kerja<select name="organizational_unit_id"><option value="">Pilih unit kerja</option>@foreach($units as $unit)<option value="{{ $unit->id }}" @selected(old('organizational_unit_id',$employee?->organizational_unit_id)==$unit->id)>{{ $unit->name }}</option>@endforeach</select></label>
<label>Jabatan*<input name="position" required maxlength="180" value="{{ old('position',$employee?->position) }}"></label>
<label>Tanggal Mulai Masa Kerja<input type="date" name="service_start_date" value="{{ old('service_start_date',$employee?->service_start_date?->format('Y-m-d')) }}"></label>
<label>Pangkat<input name="rank" maxlength="130" value="{{ old('rank',$employee?->rank) }}" placeholder="Penata Muda Tk. I"></label>
<label>Golongan<input name="grade" maxlength="30" value="{{ old('grade',$employee?->grade) }}" placeholder="III/b"></label>
<label>Telepon<input name="phone" maxlength="25" value="{{ old('phone',$employee?->phone) }}"></label>
<label>Nama Atasan Langsung<input name="supervisor_name" maxlength="150" value="{{ old('supervisor_name',$employee?->supervisor_name) }}"></label>
<label>NIP Atasan Langsung<input name="supervisor_nip" maxlength="30" value="{{ old('supervisor_nip',$employee?->supervisor_nip) }}"></label>
<label>Jabatan Atasan Langsung<input name="supervisor_position" maxlength="180" value="{{ old('supervisor_position',$employee?->supervisor_position) }}"></label>
</div><label>Alamat Selama Cuti (Default)<textarea rows="2" name="leave_address" maxlength="1000">{{ old('leave_address',$employee?->leave_address) }}</textarea></label></div>
<div class="form-actions"><p class="muted small">Perubahan identitas tidak akan mengubah surat yang sudah diterbitkan (snapshot).</p><button type="submit" class="btn primary">{{ $employee?'Simpan Perubahan':'Buat Pegawai & Akun' }}</button></div></form>
@if($employee)<div class="panel form-section"><div class="section-heading"><span class="section-number">03</span><div><h2>Saldo Cuti Tahunan</h2><p>Isi saldo yang telah diverifikasi administrasi. Nilai positif untuk penambahan dan negatif untuk koreksi.</p></div></div>
<div class="balance-list">@forelse($employee->balances->sortByDesc('year') as $balance)<div><span>Tahun {{ $balance->year }}</span><strong>{{ $balance->remaining_days }} hari</strong></div>@empty<span class="muted">Belum ada saldo tercatat.</span>@endforelse</div>
<form method="POST" action="{{ route('admin.balances.update',$employee) }}" class="stack">@csrf<div class="form-grid three"><label>Tahun Saldo<input type="number" name="year" min="2000" max="2100" value="{{ now()->year }}" required></label><label>Perubahan Saldo (hari)<input type="number" name="delta" min="-365" max="365" placeholder="Contoh: 12 atau -2" required></label><label>Alasan Koreksi<input name="note" minlength="5" maxlength="500" placeholder="Contoh: Saldo disahkan BKPSDM" required></label></div><button class="btn outline" type="submit">Catat Penyesuaian Saldo</button></form></div>@endif
@endsection
