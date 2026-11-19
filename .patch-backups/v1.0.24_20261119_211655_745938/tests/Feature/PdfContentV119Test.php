<?php

namespace Tests\Feature;

use App\Support\IndonesianNumberWords;
use Tests\TestCase;

class PdfContentV119Test extends TestCase
{
    public function test_angka_cuti_diubah_ke_huruf_sesuai_nilai(): void
    {
        foreach ([
            0 => 'Nol', 1 => 'Satu', 3 => 'Tiga', 5 => 'Lima',
            11 => 'Sebelas', 15 => 'Lima Belas', 21 => 'Dua Puluh Satu',
            100 => 'Seratus', 101 => 'Seratus Satu',
            365 => 'Tiga Ratus Enam Puluh Lima',
        ] as $number => $expected) {
            $this->assertSame($expected, IndonesianNumberWords::spell($number));
        }
    }

    public function test_form_menampilkan_jenis_cuti_dan_keputusan_manual(): void
    {
        $view = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('word-reason-text">{{ $leave->type->name }}', $view);
        $this->assertStringNotContainsString('{{ $leave->reason }}', $view);
        $this->assertStringContainsString('IndonesianNumberWords::spell((int) $leave->duration_days)', $view);
        $this->assertStringContainsString('<strong>{{ $leave->start_date->translatedFormat(', $view);
        $this->assertStringContainsString('<strong>{{ $leave->end_date->translatedFormat(', $view);
        $this->assertStringNotContainsString("' (X)'", $view);
        // Opsi X untuk jenis cuti pada bagian II tetap tersedia.
        $this->assertStringContainsString("? 'X' : ''", $view);
    }

    public function test_surat_izin_tanpa_nomor_tercetak_dan_tanpa_footer_sistem(): void
    {
        $view = file_get_contents(resource_path('views/pdf/_permission.blade.php'));
        $this->assertStringContainsString('<div class="letter-number">Nomor :</div>', $view);
        $this->assertStringNotContainsString('Nomor : {{ $leave->letter_number }}', $view);
        $this->assertStringNotContainsString('Diterbitkan melalui sistem:', $view);
        $this->assertStringContainsString('Sebelum menjalankan {{ mb_strtolower($leave->type->name) }}', $view);
        $this->assertStringContainsString('Setelah selesai menjalankan {{ mb_strtolower($leave->type->name) }}', $view);
    }

    public function test_surat_permohonan_tanpa_keterangan_hari_kalender_atau_hari_kerja(): void
    {
        $view = file_get_contents(resource_path('views/pdf/_application.blade.php'));
        $this->assertStringNotContainsString('hari kalender', $view);
        $this->assertStringNotContainsString('hari kerja', $view);
        $this->assertStringContainsString('<strong>{{ $leave->duration_days }} hari</strong>', $view);
    }

    public function test_margin_formulir_dapat_lebih_lapang_dari_naskah_surat_dinas(): void
    {
        $view = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $this->assertStringContainsString('@page :first{margin-top:12mm;margin-bottom:12mm}', $view);
        $this->assertStringContainsString('@page{size:A4 portrait;margin:12mm 20mm 12mm 30mm}', $view);
        $this->assertStringContainsString('font-size:10pt', $view);
    }
}
