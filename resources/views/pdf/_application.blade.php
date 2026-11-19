<div class="page">
<div class="letter-title" style="margin:13mm 0 10mm;text-decoration:none">SURAT PERMOHONAN {{ mb_strtoupper($leave->type->name) }}</div>
<div class="body-letter" style="font-size:9.5pt">
<p>Saya yang bertanda tangan di bawah ini:</p>
<table class="letter-info" style="margin-left:7mm;width:95%"><tr><td width="26%">Nama</td><td width="3%">:</td><td>{{ $employee['name'] }}</td></tr><tr><td>NIP</td><td>:</td><td>{{ $employee['nip'] }}</td></tr><tr><td>Pangkat/Gol.</td><td>:</td><td>{{ $employee['rank'] }} / {{ $employee['grade'] }}</td></tr><tr><td>Jabatan</td><td>:</td><td>{{ $employee['position'] }}</td></tr><tr><td>Satuan Organisasi</td><td>:</td><td>{{ $employee['unit'] }}</td></tr></table>
<div class="space4"></div><p class="text-indent">Dengan ini mengajukan <strong>{{ $leave->type->name }}</strong> selama <strong>{{ $leave->duration_days }} hari</strong> ({{ $leave->type->count_mode==='working'?'hari kerja':'hari kalender' }}), terhitung mulai tanggal <strong>{{ $leave->start_date->translatedFormat('d F Y') }}</strong> sampai dengan tanggal <strong>{{ $leave->end_date->translatedFormat('d F Y') }}</strong>.</p>
<p class="text-indent">Alasan permohonan: {{ $leave->reason }}</p>
<p class="text-indent">Demikian surat permohonan {{ $leave->type->name }} ini saya buat untuk digunakan sebagaimana mestinya.</p>
</div>
<div style="margin-top:25mm" class="signature-block"><div>{{ $settings['institution_city'] }}, {{ $writtenDate->translatedFormat('d F Y') }}</div><div>Hormat saya,</div><div class="sig-height"></div><div class="signature-name">{{ $employee['name'] }}</div><div>NIP. {{ $employee['nip'] }}</div></div>
<div style="margin-top:20mm" class="docid">{{ $leave->request_number }} @if(!$isIssued) · DRAF — BELUM DITERBITKAN @endif</div>
</div>
