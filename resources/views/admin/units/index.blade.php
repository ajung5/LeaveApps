@extends('layouts.app')
@section('title','Unit Kerja')
@section('breadcrumb','Admin / Unit Kerja')
@section('content')
<div class="page-header"><div><div class="eyebrow">MASTER DATA / ORGANISASI</div><h1>Manajemen Unit Kerja</h1><p class="muted">Kelola unit organisasi yang dapat dipilih saat mendaftarkan pegawai.</p></div></div>
<div class="panel form-section"><h2>Tambah Unit Kerja</h2><form method="POST" action="{{ route('admin.units.store') }}" class="search-row">@csrf<input name="name" required maxlength="180" placeholder="Contoh: Bidang Teknologi Informasi dan Komunikasi"><button class="btn primary">Tambah</button></form></div>
<div class="panel"><h2>Unit Terdaftar</h2><div class="stack">@forelse($units as $unit)<form method="POST" action="{{ route('admin.units.update',$unit) }}" class="search-row" style="max-width:700px">@csrf @method('PUT')<input name="name" maxlength="180" value="{{ $unit->name }}" required><button class="btn outline">Simpan</button></form>@empty<p class="muted">Belum ada unit kerja.</p>@endforelse</div></div>
@endsection
