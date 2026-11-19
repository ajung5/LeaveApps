<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfTypographyV124Test extends TestCase
{
    public function test_isi_surat_izin_dan_permohonan_berukuran_12pt(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));

        // Jangan hanya menguji ukuran default; pastikan override terakhir
        // mempertahankan 12pt ketika density dinaikkan oleh generator.
        foreach ([
            '.permission-page .body-letter',
            '.application-page .body-letter',
            '.permission-page .letter-info td',
            '.application-page .letter-info td',
            '.permission-page .letter-number',
            '.permission-page .signature-block',
            '.application-page .signature-block',
        ] as $selector) {
            $this->assertStringContainsString($selector, $css);
        }
        $this->assertMatchesRegularExpression(
            '/\/\* v1\.0\.24:.*?font-size:12pt!important;\s*\}/s',
            $css
        );
        // Kop memakai aturan ukuran tersendiri: 12pt (pemerintah) dan 16pt (OPD).
        $this->assertStringContainsString('.letterhead-text .gov{font-size:12pt', $css);
        $this->assertStringContainsString('.letterhead-text .agency{font-size:16pt', $css);
    }

    public function test_ruang_tanda_tangan_setara_minimal_empat_baris(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $permission = file_get_contents(resource_path('views/pdf/_permission.blade.php'));
        $application = file_get_contents(resource_path('views/pdf/_application.blade.php'));

        $this->assertMatchesRegularExpression(
            '/\.permission-page \.signature-block \.sig-height,\s*'
            .'\.application-page \.signature-block \.sig-height\s*\{'
            .'[^}]*height:20mm!important;[^}]*min-height:20mm!important;/s',
            $css
        );
        // 4 baris * 12pt * line-height 1.15 = 19.47mm; 20mm > 19.47mm.
        $height = 20.0;
        $minimum = 4 * 12 * 1.15 * 25.4 / 72;
        $this->assertGreaterThanOrEqual($minimum, $height);
        $this->assertStringContainsString('<div class="sig-height"></div>', $permission);
        $this->assertStringContainsString('<div class="sig-height"></div>', $application);
        $this->assertStringContainsString('page-break-inside:avoid;', $css);
    }

    public function test_nomor_surat_izin_tetap_kosong_dan_tes_regresi_lama_sudah_diperbaiki(): void
    {
        $permission = file_get_contents(resource_path('views/pdf/_permission.blade.php'));
        $legacyTest = file_get_contents(base_path('tests/Feature/PdfContentV119Test.php'));
        $this->assertStringContainsString('class="letter-number-placeholder"', $permission);
        $this->assertStringNotContainsString('{{ $leave->letter_number }}', $permission);
        $this->assertStringContainsString('letter-number-placeholder', $legacyTest);
        $this->assertStringNotContainsString('Nomor :</div>', $legacyTest);
    }
}
