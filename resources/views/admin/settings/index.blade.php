@extends('layouts.app')
@section('title','Pengaturan Instansi')
@section('breadcrumb','Admin / Pengaturan Instansi')
@section('content')
<div class="page-header"><div><div class="eyebrow">CONFIGURATION / ORGANIZATION</div><h1>Pengaturan Instansi & Surat</h1><p class="muted">Digunakan sebagai identitas penerbit pada dokumen PDF A4.</p></div></div>
<form method="POST" action="{{ route('admin.settings.update') }}" class="stack">@csrf @method('PUT')<div class="panel form-section"><h2>Kop Surat Instansi</h2><div class="form-grid two">
@foreach(['government_name'=>'Pemerintah Daerah','institution_name'=>'Nama Dinas/Instansi','institution_address'=>'Alamat Instansi','institution_phone'=>'Telepon / Faks','institution_city'=>'Kota Penerbitan'] as $field=>$label)<label>{{ $label }}<input name="{{ $field }}" maxlength="250" value="{{ old($field,$settings[$field]) }}" {{ $field==='institution_phone'?'':'required' }}></label>@endforeach</div></div>
<div class="panel form-section"><h2>Pejabat Berwenang</h2><p class="muted small">Identitas disimpan per surat ketika diterbitkan, sehingga perubahan pejabat tidak mengubah dokumen historis.</p><div class="form-grid two">
@foreach(['authority_name'=>'Nama Pejabat','authority_nip'=>'NIP Pejabat','authority_position'=>'Jabatan Pejabat','authority_rank'=>'Pangkat / Golongan Pejabat'] as $field=>$label)<label>{{ $label }}<input name="{{ $field }}" maxlength="180" value="{{ old($field,$settings[$field]) }}" {{ $field==='authority_position'?'required':'' }}></label>@endforeach</div></div>
<div class="panel form-section"><h2>Penomoran Surat</h2><p class="muted small">Format: prefiks / 0001 / sufiks / tahun. Nomor diterbitkan otomatis setelah keputusan sah dicatat.</p><div class="form-grid two"><label>Prefiks Nomor<input name="letter_prefix" value="{{ old('letter_prefix',$settings['letter_prefix']) }}" required></label><label>Sufiks Nomor<input name="letter_suffix" value="{{ old('letter_suffix',$settings['letter_suffix']) }}" required></label></div></div>
<div class="form-actions"><p class="muted small">Logo berasal dari berkas template yang dilampirkan; file terpasang di public/assets/img/lambang-subang.jpeg.</p><button class="btn primary">Simpan Pengaturan</button></div></form>
@endsection
