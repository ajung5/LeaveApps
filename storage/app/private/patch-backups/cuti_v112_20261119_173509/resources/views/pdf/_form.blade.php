<div class="page form-page word-cuti-form density-{{ $density['form'] ?? 0 }}">
    <div class="header-date">{{ $settings['institution_city'] }}, {{ $writtenDate->translatedFormat('d F Y') }}</div>
    <table class="form-addressee">
        <tr>
            <td width="54%"></td>
            <td width="46%">
                Kepada<br>
                Yth. {{ $settings['authority_position'] }}<br>
                {{ $settings['institution_name'] }}<br>
                di<br>
                TEMPAT
            </td>
        </tr>
    </table>
    <div class="form-caption">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</div>

    {{-- I. Mengikuti empat kolom dan sel gabungan pada dokumen Word. --}}
    <table class="form-table tight word-table word-employee">
        <colgroup><col style="width:16%"><col style="width:36%"><col style="width:17%"><col style="width:31%"></colgroup>
        <tr><td colspan="4" class="section-title">I. DATA PEGAWAI****</td></tr>
        <tr><td>Nama</td><td>{{ $employee['name'] }}</td><td>NIP</td><td>{{ $employee['nip'] }}</td></tr>
        <tr><td>Jabatan</td><td>{{ $employee['position'] }}</td><td>Masa Kerja</td><td>{{ $workAge ? $workAge->y.' TAHUN '.$workAge->m.' BULAN' : 'Belum tercatat' }}</td></tr>
        <tr><td>Unit Kerja</td><td colspan="3">{{ $employee['unit'] }}</td></tr>
    </table>

    <table class="form-table tight word-table word-types">
        <colgroup><col style="width:35%"><col style="width:14%"><col style="width:36%"><col style="width:15%"></colgroup>
        <tr><td colspan="4" class="section-title">II. JENIS CUTI YANG DIAMBIL****</td></tr>
        @php
            $choices = [
                ['TAHUNAN', '1. Cuti Tahunan', 'BESAR', '2. Cuti Besar'],
                ['SAKIT', '3. Cuti Sakit', 'BERSALIN', '4. Cuti Bersalin'],
                ['PENTING', '5. Cuti Karena Alasan Penting', 'CLTN', '6. Cuti di Luar Tanggungan Negara'],
            ];
        @endphp
        @foreach($choices as $choice)
            <tr>
                <td>{{ $choice[1] }}</td>
                <td class="center">{{ $leave->type->code === $choice[0] ? 'X' : '' }}</td>
                <td>{{ $choice[3] }}</td>
                <td class="center">{{ $leave->type->code === $choice[2] ? 'X' : '' }}</td>
            </tr>
        @endforeach
    </table>

    <table class="form-table tight word-table word-reason">
        <tr><td class="section-title">III. ALASAN CUTI****</td></tr>
        <tr><td class="word-reason-text">{{ $leave->reason }}</td></tr>
    </table>

    <table class="form-table tight word-table word-duration">
        <colgroup><col style="width:21%"><col style="width:19%"><col style="width:16%"><col style="width:19%"><col style="width:6%"><col style="width:19%"></colgroup>
        <tr><td colspan="6" class="section-title">IV. LAMANYA CUTI****</td></tr>
        <tr>
            <td>Selama :</td>
            <td class="center"><span class="word-subline">(hari/bulan/tahun)</span><br>{{ $leave->duration_days }} Hari</td>
            <td>Mulai tanggal</td>
            <td>{{ $leave->start_date->translatedFormat('d F Y') }}</td>
            <td class="center">s/d</td>
            <td>{{ $leave->end_date->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    {{-- V. Catatan saldo N-2/N-1/N kiri; daftar jenis cuti lainnya kanan. --}}
    <table class="form-table tight word-table word-history">
        <colgroup><col style="width:12%"><col style="width:10%"><col style="width:18%"><col style="width:50%"><col style="width:10%"></colgroup>
        <tr><td colspan="5" class="section-title">V. CATATAN CUTI***</td></tr>
        <tr><td colspan="3">1. CUTI TAHUNAN</td><td colspan="2">2. CUTI BESAR</td></tr>
        <tr><td>Tahun</td><td>Sisa</td><td>Keterangan</td><td colspan="2">3. CUTI SAKIT</td></tr>
        <tr><td>N-2</td><td>{{ $balanceYears[$leave->start_date->year-2] ?? '' }}</td><td></td><td colspan="2">4. CUTI BERSALIN</td></tr>
        <tr><td>N-1</td><td>{{ $balanceYears[$leave->start_date->year-1] ?? '' }}</td><td></td><td colspan="2">5. CUTI KARENA ALASAN PENTING</td></tr>
        <tr><td>N</td><td>{{ $balanceYears[$leave->start_date->year] ?? '' }}</td><td></td><td colspan="2">6. CUTI DI LUAR TANGGUNGAN NEGARA</td></tr>
    </table>

    {{-- VI. Kolom alamat 48%; 52% kanan adalah area tanda tangan pemohon. --}}
    <table class="form-table tight word-table word-address">
        <colgroup><col style="width:48%"><col style="width:18%"><col style="width:34%"></colgroup>
        <tr><td colspan="3" class="section-title">VI. ALAMAT SELAMA MENJALANKAN CUTI****</td></tr>
        <tr><td></td><td>TELP</td><td>{{ $leave->contact_phone }}</td></tr>
        <tr>
            <td class="word-address-text">{{ $leave->leave_address }}</td>
            <td colspan="2" class="center word-applicant-sign">
                Hormat saya,
                <div class="word-sign-space"></div>
                <span class="signature-name">{{ $employee['name'] }}</span><br>
                NIP. {{ $employee['nip'] }}
            </td>
        </tr>
    </table>

    {{-- VII & VIII. Judul/pilihan keputusan selebar tabel seperti Word;
         bagian tanda tangan pejabat berada di kanan, sejajar dengan pemohon. --}}
    <table class="form-table tight approval-table word-table word-decision" data-word-section="VII">
        <colgroup><col style="width:14.5%"><col style="width:14.5%"><col style="width:19%"><col style="width:52%"></colgroup>
        <tr><td colspan="4" class="section-title">VII. PERTIMBANGAN ATASAN LANGSUNG**</td></tr>
        <tr>
            <td class="word-decision-choice">DISETUJUI**{{ $sup?->decision === 'approved' ? ' (X)' : '' }}</td>
            <td class="word-decision-choice">PERUBAHAN**{{ $sup?->decision === 'change' ? ' (X)' : '' }}</td>
            <td class="word-decision-choice">DITANGGUHKAN*{{ $sup?->decision === 'deferred' ? ' (X)' : '' }}</td>
            <td class="word-decision-choice center">TIDAK DISETUJUI****{{ $sup?->decision === 'rejected' ? ' (X)' : '' }}</td>
        </tr>
        <tr><td colspan="3" rowspan="2" class="word-approval-empty"></td><td class="word-decision-position center">{{ $sup?->official_position ?: ($employee['supervisor_position'] ?: 'ATASAN LANGSUNG') }}</td></tr>
        <tr><td class="word-decision-sign center"><div class="word-sign-space"></div><span class="signature-name">{{ $sup?->official_name ?: ($employee['supervisor_name'] ?: '..................................') }}</span><br>NIP. {{ $sup?->official_nip ?: ($employee['supervisor_nip'] ?: '..................................') }}</td></tr>
    </table>

    <table class="form-table tight approval-table word-table word-decision" data-word-section="VIII">
        <colgroup><col style="width:14.5%"><col style="width:14.5%"><col style="width:19%"><col style="width:52%"></colgroup>
        <tr><td colspan="4" class="section-title">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI****</td></tr>
        <tr>
            <td class="word-decision-choice">DISETUJUI**{{ $authority?->decision === 'approved' ? ' (X)' : '' }}</td>
            <td class="word-decision-choice">PERUBAHAN**{{ $authority?->decision === 'change' ? ' (X)' : '' }}</td>
            <td class="word-decision-choice">DITANGGUHKAN**{{ $authority?->decision === 'deferred' ? ' (X)' : '' }}</td>
            <td class="word-decision-choice center">TIDAK DISETUJUI**{{ $authority?->decision === 'rejected' ? ' (X)' : '' }}</td>
        </tr>
        <tr><td colspan="3" rowspan="2" class="word-approval-empty"></td><td class="word-decision-position center">{{ $authority?->official_position ?: $settings['authority_position'] }}</td></tr>
        <tr><td class="word-decision-sign center"><div class="word-sign-space"></div><span class="signature-name">{{ $authority?->official_name ?: $settings['authority_name'] }}</span><br>NIP. {{ $authority?->official_nip ?: $settings['authority_nip'] }}</td></tr>
    </table>

    <div class="docid">{{ $leave->request_number }} @if(!$isIssued) · DRAF — BELUM DITERBITKAN @endif</div>
</div>
