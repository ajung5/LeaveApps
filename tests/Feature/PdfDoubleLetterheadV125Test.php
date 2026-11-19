<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfDoubleLetterheadV125Test extends TestCase
{
    public function test_kop_surat_izin_menggunakan_dua_garis_dan_tetap_aman_di_dompdf(): void
    {
        $permission = file_get_contents(resource_path('views/pdf/_permission.blade.php'));
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));

        $this->assertSame(1, substr_count($permission, 'class="letterhead-rules"'));
        $this->assertSame(1, substr_count($permission, 'class="letterhead-rule-primary"'));
        $this->assertSame(1, substr_count($permission, 'class="letterhead-rule-secondary"'));
        $this->assertStringContainsString('border-bottom:0;', $css);
        $this->assertStringContainsString('.permission-page .letterhead-rules{clear:both;padding-top:2.3mm}', $css);
        $this->assertStringContainsString('.permission-page .letterhead-rule-primary{border-top:3pt solid #111;', $css);
        $this->assertStringContainsString('.permission-page .letterhead-rule-secondary{border-top:.85pt solid #111;', $css);
        $this->assertStringContainsString('margin-top:1.2mm;', $css);
    }

    public function test_nomor_surat_dan_jarak_tanda_tangan_tidak_berubah(): void
    {
        $permission = file_get_contents(resource_path('views/pdf/_permission.blade.php'));
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));

        $this->assertStringContainsString('class="letter-number-placeholder"', $permission);
        $this->assertStringNotContainsString('Nomor : {{ $leave->letter_number }}', $permission);
        $this->assertStringContainsString('<div class="sig-height"></div>', $permission);
        $this->assertStringContainsString('height:20mm!important;', $css);
        $this->assertStringContainsString('font-size:12pt!important;', $css);
        $this->assertStringContainsString('@page{size:A4 portrait;margin:20mm 20mm 20mm 30mm}', $css);
    }
}
