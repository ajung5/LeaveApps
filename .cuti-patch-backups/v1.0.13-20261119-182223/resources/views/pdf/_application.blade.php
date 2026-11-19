<div class="page application-page density-{{ $density['application'] ?? 0 }}">
<div class="letter-title application-title">SURAT PERMOHONAN {{ mb_strtoupper($leave->type->name) }}</div>
<div class="body-letter application-body">
<p>Saya yang bertanda tangan di bawah ini:</p>
<table class="letter-info">
<tr><td class="label">Nama</td><td class="sep">:</td><td class="value">{{ $employee['name'] }}</td></tr>
<tr><td class="label">NIP</td><td class="sep">:</td><td class="value">{{ $employee['nip'] }}</td></tr>
<tr><td class="label">Pangkat/Gol.</td><td class="sep">:</td><td class="value">{{ $employee['rank'] }} / {{ $employee['grade'] }}</td></tr>
<tr><td class="label">Jabatan</td><td class="sep">:</td><td class="value">{{ $employee['position'] }}</td></tr>
<tr><td class="label">Satuan Organisasi</td><td class="sep">:</td><td class="value">{{ $employee['unit'] }}</td></tr>
</table>
<div class="space4"></div><p class="text-indent">Dengan ini mengajukan <strong>{{ $leave->type->name }}</strong> selama <strong>{{ $leave->duration_days }} hari</strong> ({{ $leave->type->count_mode==='working'?'hari kerja':'hari kalender' }}), terhitung mulai tanggal <strong>{{ $leave->start_date->translatedFormat('d F Y') }}</strong> sampai dengan tanggal <strong>{{ $leave->end_date->translatedFormat('d F Y') }}</strong>.</p>
<p class="text-indent">Alasan permohonan: {{ $leave->reason }}</p>
<p class="text-indent">Demikian surat permohonan {{ $leave->type->name }} ini saya buat untuk digunakan sebagaimana mestinya.</p>
</div>
<div class="signature-block application-signature"><div>{{ $settings['institution_city'] }}, {{ $writtenDate->translatedFormat('d F Y') }}</div><div>Hormat saya,</div><div class="sig-height"></div><div class="signature-name">{{ $employee['name'] }}</div><div>NIP. {{ $employee['nip'] }}</div></div>
<div class="docid application-docid">{{ $leave->request_number }} @if(!$isIssued) · DRAF — BELUM DITERBITKAN @endif</div>
</div>
