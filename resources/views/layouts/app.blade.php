<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — {{ config('app.name') }}</title>
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
@if(auth()->check())
<div class="app-shell">
<aside class="sidebar">
  <div class="brand"><div class="brand-mark">CT</div><div><strong>CUTI / PEGAWAI</strong><small>Manajemen permohonan</small></div></div>
  <div class="sidebar-label">MENU UTAMA</div>
  <a class="nav-link {{ request()->routeIs('dashboard')?'active':'' }}" href="{{ route('dashboard') }}"><span>▤</span> Dashboard</a>
  @if(auth()->user()->employee)
  <a class="nav-link {{ request()->routeIs('leaves.create')?'active':'' }}" href="{{ route('leaves.create') }}"><span>＋</span> Ajukan Cuti</a>
  @endif
  @if(auth()->user()->isAdmin())
    <div class="sidebar-label">ADMINISTRASI</div>
    <a class="nav-link {{ request()->routeIs('admin.reports*')?'active':'' }}" href="{{ route('admin.reports') }}"><span>≡</span> Laporan</a>
    <a class="nav-link {{ request()->routeIs('admin.employees*')?'active':'' }}" href="{{ route('admin.employees') }}"><span>♙</span> Data Pegawai</a>
    <a class="nav-link {{ request()->routeIs('admin.units*')?'active':'' }}" href="{{ route('admin.units') }}"><span>▤</span> Unit Kerja</a>
    <a class="nav-link {{ request()->routeIs('admin.types*')?'active':'' }}" href="{{ route('admin.types') }}"><span>▦</span> Jenis Cuti</a>
    <a class="nav-link {{ request()->routeIs('admin.holidays*')?'active':'' }}" href="{{ route('admin.holidays') }}"><span>▣</span> Kalender Libur</a>
    <a class="nav-link {{ request()->routeIs('admin.settings*')?'active':'' }}" href="{{ route('admin.settings') }}"><span>⚙</span> Pengaturan Instansi</a>
  @endif
  <div class="sidebar-bottom"><span class="signal-dot"></span> Sistem Cuti · v1.0.0</div>
</aside>
<div class="main-area">
<header class="topbar"><div class="topbar-left">Administrasi Kepegawaian <span>/</span> @yield('breadcrumb','Dashboard')</div>
<div class="user-indicator"><span class="role-chip">{{ auth()->user()->isAdmin()?'ADMIN':'PEGAWAI' }}</span><span class="user-avatar">{{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}</span><span class="user-name">{{ auth()->user()->name }}</span>
<form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="logout" aria-label="Keluar">Keluar</button></form></div></header>
<main class="page-content">
@endif
@if(session('ok')) <div class="alert success">{{ session('ok') }}</div> @endif
@if($errors->any()) <div class="alert danger"><strong>Periksa kembali data:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
@yield('content')
@if(auth()->check())
</main><footer class="footer">Sistem Permohonan Cuti &nbsp;·&nbsp; Dokumen internal kepegawaian</footer></div></div>
@endif
</body></html>
