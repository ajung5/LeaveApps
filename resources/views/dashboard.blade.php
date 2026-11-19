@extends('layouts.app')
@section('title','Dashboard')
@section('breadcrumb','Dashboard')
@section('content')
<div class="page-header"><div><div class="eyebrow">OVERVIEW / {{ now()->translatedFormat('F Y') }}</div><h1>Dashboard {{ $admin?'Administrasi':'Pegawai' }}</h1><p class="muted">Ringkasan permohonan dan status cuti.</p></div>@if($employee)<a class="btn primary" href="{{ route('leaves.create') }}">＋ Ajukan Cuti</a>@endif</div>
<div class="stat-grid">
<div class="stat-card"><span>Total Permohonan</span><strong>{{ $stats['total'] }}</strong><small>Semua periode</small></div>
<div class="stat-card"><span>Menunggu Tindak Lanjut</span><strong>{{ $stats['pending'] }}</strong><small>Memerlukan proses</small></div>
<div class="stat-card"><span>Surat Diterbitkan</span><strong>{{ $stats['issued'] }}</strong><small>Tercatat dalam sistem</small></div>
@if(!$admin)<div class="stat-card accent"><span>Saldo Cuti Tersedia*</span><strong>{{ $balance }} <em>hari</em></strong><small>Saldo terverifikasi N−2 s.d. N</small></div>@endif
</div>
@if(!$employee && !$admin)<div class="alert danger">Akun belum terhubung profil pegawai. Hubungi Admin.</div>@endif
<div class="panel"><div class="panel-head"><div><h2>{{ $admin?'Seluruh Permohonan':'Riwayat Permohonan Saya' }}</h2><p class="muted small">Klik nomor permohonan untuk melihat detail.</p></div></div>
<div class="table-scroll"><table class="data-table"><thead><tr><th>Nomor</th>@if($admin)<th>Pegawai</th>@endif<th>Jenis Cuti</th><th>Periode</th><th>Durasi</th><th>Status</th></tr></thead><tbody>
@forelse($items as $leave)<tr><td><a class="text-link" href="{{ route('leaves.show',$leave) }}">{{ $leave->request_number }}</a></td>@if($admin)<td><strong>{{ $leave->employee->full_name }}</strong><br><span class="muted small">{{ $leave->employee->nip }}</span></td>@endif<td>{{ $leave->type->name }}</td><td>{{ $leave->start_date->format('d/m/Y') }} – {{ $leave->end_date->format('d/m/Y') }}</td><td>{{ $leave->duration_days }} hari</td><td><span class="status status-{{ $leave->status }}">{{ str_replace('_',' ',ucwords($leave->status,'_')) }}</span></td></tr>
@empty<tr><td colspan="{{ $admin?6:5 }}" class="empty">Belum ada permohonan cuti.</td></tr>@endforelse
</tbody></table></div><div class="paginate">{{ $items->links() }}</div></div>
@if(!$admin)<p class="muted small">*Saldo merupakan rekap data administrasi yang telah dimasukkan Admin, bukan penetapan hak cuti secara otomatis.</p>@endif
@endsection
