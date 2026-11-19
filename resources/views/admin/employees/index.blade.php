@extends('layouts.app')
@section('title','Manajemen Pegawai')
@section('breadcrumb','Admin / Data Pegawai')
@section('content')
<div class="page-header"><div><div class="eyebrow">MASTER DATA / PEGAWAI</div><h1>Data Pegawai</h1><p class="muted">Kelola akun dan identitas yang digunakan dalam surat.</p></div><a href="{{ route('admin.employees.create') }}" class="btn primary">＋ Tambah Pegawai</a></div>
<div class="panel"><form action="{{ route('admin.employees') }}" method="GET" class="search-row"><input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIP"><button class="btn outline">Cari</button></form>
<div class="table-scroll"><table class="data-table"><thead><tr><th>Pegawai</th><th>NIP</th><th>Unit Kerja</th><th>Jabatan</th><th>Akun</th><th></th></tr></thead><tbody>@forelse($employees as $e)<tr><td><strong>{{ $e->full_name }}</strong><br><span class="muted small">{{ $e->user->email }}</span></td><td>{{ $e->nip }}</td><td>{{ $e->unit?->name ?: '—' }}</td><td>{{ $e->position }}</td><td><span class="status">{{ $e->user->is_active?'Aktif':'Nonaktif' }}</span></td><td><a href="{{ route('admin.employees.edit',$e) }}" class="text-link">Kelola</a></td></tr>@empty<tr><td colspan="6" class="empty">Belum ada data pegawai.</td></tr>@endforelse</tbody></table></div><div class="paginate">{{ $employees->links() }}</div></div>
@endsection
