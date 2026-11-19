<div class="page permission-page density-{{ $density['permission'] ?? 0 }}">
<div class="letterhead"><img src="data:image/jpeg;base64,{{ $logo }}" class="logo" alt="Lambang Kabupaten Subang"><div class="letterhead-text"><div class="gov">{{ $settings['government_name'] }}</div><div class="agency">{{ $settings['institution_name'] }}</div><div class="addr">{{ $settings['institution_address'] }}</div><div class="addr">{{ $settings['institution_phone'] }}</div></div></div>
<div class="permit-title-area"><div class="letter-title">SURAT IZIN {{ mb_strtoupper($leave->type->name) }}</div><div class="letter-number">Nomor : {{ $leave->letter_number }}</div></div>
<div class="body-letter">
<p>Diberikan izin <strong>{{ $leave->type->name }}</strong> kepada pegawai:</p>
<table class="letter-info">
<tr><td class="label">Nama</td><td class="sep">:</td><td class="value">{{ $employee['name'] }}</td></tr>
<tr><td class="label">NIP</td><td class="sep">:</td><td class="value">{{ $employee['nip'] }}</td></tr>
<tr><td class="label">Pangkat/Gol.</td><td class="sep">:</td><td class="value">{{ $employee['rank'] }} / {{ $employee['grade'] }}</td></tr>
<tr><td class="label">Jabatan</td><td class="sep">:</td><td class="value">{{ $employee['position'] }}</td></tr>
<tr><td class="label">Unit Kerja</td><td class="sep">:</td><td class="value">{{ $employee['unit'] }}</td></tr>
</table>
<div class="space4"></div>
<p>Terhitung mulai tanggal <strong>{{ $leave->start_date->translatedFormat('d F Y') }}</strong> sampai dengan tanggal <strong>{{ $leave->end_date->translatedFormat('d F Y') }}</strong>, selama <strong>{{ $leave->duration_days }} hari</strong> ({{ $leave->type->count_mode==='working'?'hari kerja':'hari kalender' }}), untuk melaksanakan {{ $leave->type->name }} dengan ketentuan sebagai berikut:</p>
<ol><li>Sebelum menjalankan cuti, pegawai wajib menyerahkan pekerjaan kepada atasan langsung atau pejabat yang ditunjuk.</li><li>Setelah selesai menjalankan cuti, pegawai wajib melapor kepada atasan langsung dan kembali bekerja sesuai ketentuan.</li></ol>
<p>Demikian surat izin {{ $leave->type->name }} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
</div>
<div class="space4"></div>
<div class="signature-block"><div>Dikeluarkan di : {{ $settings['institution_city'] }}</div><div>Pada Tanggal : {{ $leave->issued_at->translatedFormat('d F Y') }}</div><div style="margin-top:3mm" class="bold upper">{{ $settings['authority_position'] }}</div><div class="sig-height"></div><div class="signature-name upper">{{ $settings['authority_name'] }}</div><div>{{ $settings['authority_rank'] }}</div><div>NIP. {{ $settings['authority_nip'] }}</div></div>
<div class="space4"></div><div class="docid">Diterbitkan melalui sistem: {{ $leave->letter_number }} · Belum sah tanpa tanda tangan pejabat berwenang.</div>
</div>
