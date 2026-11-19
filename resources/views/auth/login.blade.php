@extends('layouts.app')
@section('title','Masuk')
@section('content')
<div class="login-page"><div class="login-card">
<div class="login-logo">CT</div><p class="eyebrow">SISTEM INFORMASI KEPEGAWAIAN</p>
<h1>Permohonan Cuti</h1><p class="muted">Masuk menggunakan akun yang telah diberikan oleh administrator.</p>
<form method="POST" action="{{ route('login.store') }}" class="stack">@csrf
<label>Email Pegawai<input name="email" type="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autofocus autocomplete="username"></label>
<label>Kata Sandi<input name="password" type="password" placeholder="Masukkan kata sandi" required autocomplete="current-password"></label>
<button class="btn primary full" type="submit">Masuk ke Aplikasi</button></form>
<div class="login-foot">Akses terbatas untuk pegawai terdaftar.</div></div></div>
@endsection
