<div class="page">
<div class="header-date">{{ $settings['institution_city'] }}, {{ $writtenDate->translatedFormat('d F Y') }}</div>
<table style="margin-top:3mm"><tr><td width="51%"></td><td width="49%">Kepada Yth.<br><strong>{{ $settings['authority_position'] }}</strong><br>{{ $settings['institution_name'] }}<br>di TEMPAT</td></tr></table>
<div class="form-caption">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</div>
<table class="form-table tight"><tr><td class="section-title" colspan="4">I. DATA PEGAWAI</td></tr>
<tr><td width="15%">Nama</td><td width="40%">{{ $employee['name'] }}</td><td width="16%">NIP</td><td width="29%">{{ $employee['nip'] }}</td></tr>
<tr><td>Jabatan</td><td>{{ $employee['position'] }}</td><td>Masa Kerja</td><td>{{ $workAge?$workAge->y.' TAHUN '.$workAge->m.' BULAN':'Belum tercatat' }}</td></tr>
<tr><td>Unit Kerja</td><td colspan="3">{{ $employee['unit'] }}</td></tr></table>
<div class="space3"></div>
<table class="form-table tight"><tr><td class="section-title" colspan="4">II. JENIS CUTI YANG DIAMBIL</td></tr>
@php $choices=[['TAHUNAN','Cuti Tahunan','BESAR','Cuti Besar'],['SAKIT','Cuti Sakit','BERSALIN','Cuti Bersalin'],['PENTING','Cuti Karena Alasan Penting','CLTN','Cuti di Luar Tanggungan Negara']]; @endphp
@foreach($choices as $row)<tr><td width="43%">{{ $row[1] }}</td><td width="7%" class="center">{{ $leave->type->code===$row[0]?'X':'' }}</td><td width="43%">{{ $row[3] }}</td><td width="7%" class="center">{{ $leave->type->code===$row[2]?'X':'' }}</td></tr>@endforeach</table>
<div class="space3"></div>
<table class="form-table tight"><tr><td class="section-title">III. ALASAN CUTI</td></tr><tr><td style="height:12mm">{{ $leave->reason }}</td></tr></table>
<div class="space3"></div>
<table class="form-table tight"><tr><td class="section-title" colspan="4">IV. LAMANYA CUTI</td></tr><tr><td width="20%">Selama</td><td width="26%"><strong>{{ $leave->duration_days }} hari</strong> ({{ $leave->type->count_mode==='working'?'hari kerja':'hari kalender' }})</td><td width="24%">Mulai tanggal</td><td width="30%">{{ $leave->start_date->translatedFormat('d F Y') }}<br>s/d {{ $leave->end_date->translatedFormat('d F Y') }}</td></tr></table>
<div class="space3"></div>
<table class="form-table tight"><tr><td class="section-title" colspan="4">V. CATATAN CUTI</td></tr><tr><td width="12%"><strong>Tahun</strong></td><td width="17%"><strong>Sisa</strong></td><td width="30%"><strong>Keterangan</strong></td><td width="41%"><strong>Jenis Cuti Lain</strong></td></tr>
@foreach(['N-2','N-1','N'] as $i=>$label)<tr><td>{{ $label }}</td><td>{{ $balanceYears[$leave->start_date->year-(2-$i)]??'—' }}</td><td>{{ $leave->type->code==='TAHUNAN'?'Saldo administrasi':'—' }}</td><td>{{ ['Cuti Besar / Cuti Sakit','Cuti Bersalin / Alasan Penting','Cuti di Luar Tanggungan Negara'][$i] }}</td></tr>@endforeach</table>
<div class="space3"></div>
<table class="form-table tight"><tr><td class="section-title" colspan="2">VI. ALAMAT SELAMA MENJALANKAN CUTI</td></tr><tr><td width="53%"><strong>Alamat</strong><br>{{ $leave->leave_address }}<br><br>Telp: {{ $leave->contact_phone }}</td><td class="address-cell center">Hormat saya,<div class="sign-space"></div><span class="signature-name">{{ $employee['name'] }}</span><br>NIP. {{ $employee['nip'] }}</td></tr></table>
<div class="space3"></div>
<table class="form-table tight"><tr><td class="section-title" colspan="4">VII. PERTIMBANGAN ATASAN LANGSUNG</td></tr><tr>@foreach(['approved'=>'DISETUJUI','change'=>'PERUBAHAN','deferred'=>'DITANGGUHKAN','rejected'=>'TIDAK DISETUJUI'] as $k=>$v)<td width="25%" class="center">{{ $v }} <span class="checkbox">{{ $sup?->decision===$k?'X':'' }}</span></td>@endforeach</tr><tr><td colspan="4" class="signature-cell">{{ $employee['supervisor_position'] ?: 'ATASAN LANGSUNG' }}<div class="sign-space"></div><span class="signature-name">{{ $sup?->official_name ?: $employee['supervisor_name'] }}</span><br>NIP. {{ $sup?->official_nip ?: $employee['supervisor_nip'] }}</td></tr></table>
<div class="space3"></div>
<table class="form-table tight"><tr><td class="section-title" colspan="4">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</td></tr><tr>@foreach(['approved'=>'DISETUJUI','change'=>'PERUBAHAN','deferred'=>'DITANGGUHKAN','rejected'=>'TIDAK DISETUJUI'] as $k=>$v)<td width="25%" class="center">{{ $v }} <span class="checkbox">{{ $authority?->decision===$k?'X':'' }}</span></td>@endforeach</tr><tr><td colspan="4" class="signature-cell">{{ $settings['authority_position'] }}<div class="sign-space"></div><span class="signature-name">{{ $authority?->official_name ?: $settings['authority_name'] }}</span><br>NIP. {{ $authority?->official_nip ?: $settings['authority_nip'] }}</td></tr></table>
<div class="docid">{{ $leave->request_number }} @if(!$isIssued) · DRAF — BELUM DITERBITKAN @endif</div>
</div>
