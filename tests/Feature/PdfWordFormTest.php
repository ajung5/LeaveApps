<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfWordFormTest extends TestCase
{
    public function test_struktur_delapan_bagian_sama_dengan_word_referensi(): void
    {
        $view = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $headings = [
            'I. DATA PEGAWAI',
            'II. JENIS CUTI YANG DIAMBIL',
            'III. ALASAN CUTI',
            'IV. LAMANYA CUTI',
            'V. CATATAN CUTI',
            'VI. ALAMAT SELAMA MENJALANKAN CUTI',
            'VII. PERTIMBANGAN ATASAN LANGSUNG',
            'VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI',
        ];
        $lastPos = -1;
        foreach ($headings as $heading) {
            $pos = strpos($view, $heading);
            $this->assertNotFalse($pos, "Bagian hilang: {$heading}");
            $this->assertGreaterThan($lastPos, $pos, "Urutan bagian berubah: {$heading}");
            $lastPos = $pos;
        }
    }

    public function test_tabel_saldo_dan_jenis_cuti_terpisah_kiri_kanan_seperti_word(): void
    {
        $view = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('class="form-table tight word-table word-history"', $view);
        $this->assertStringContainsString('<td colspan="3">1. CUTI TAHUNAN</td><td colspan="2">2. CUTI BESAR</td>', $view);
        $this->assertStringContainsString('3. CUTI SAKIT', $view);
        $this->assertStringContainsString('4. CUTI BERSALIN', $view);
        $this->assertStringContainsString('5. CUTI KARENA ALASAN PENTING', $view);
        $this->assertStringContainsString('6. CUTI DI LUAR TANGGUNGAN NEGARA', $view);
        foreach (['N-2', 'N-1', 'N'] as $year) {
            $this->assertStringContainsString('<td>'.$year.'</td>', $view);
        }
    }

    public function test_lamanya_cuti_6_kolom_dan_alamat_tiga_kolom_dengan_telp(): void
    {
        $view = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('class="form-table tight word-table word-duration"', $view);
        $this->assertStringContainsString('<tr><td colspan="6" class="section-title">IV. LAMANYA CUTI', $view);
        $this->assertStringContainsString('class="form-table tight word-table word-address"', $view);
        $this->assertStringContainsString('<td>TELP</td><td>{{ $leave->contact_phone }}</td>', $view);
        $this->assertStringContainsString('<td colspan="2" class="center word-applicant-sign">', $view);
    }
}
