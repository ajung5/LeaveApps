<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfLayoutTemplateTest extends TestCase
{
    public function test_kop_surat_menggunakan_proporsi_3_4_dan_bookman(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));

        foreach (['gov' => ['12pt', 'normal'], 'agency' => ['16pt', 'bold']] as $selector => [$size, $weight]) {
            $found = preg_match_all('/\.letterhead-text\s+\.'.preg_quote($selector, '/').'\s*\{([^}]+)\}/', $css, $matches);
            $this->assertGreaterThan(0, $found, "Kop {$selector} harus memiliki deklarasi CSS.");
            $rule = end($matches[1]);
            $this->assertMatchesRegularExpression('/font-size\s*:\s*'.preg_quote($size, '/').'\s*;?/', $rule);
            $weights = $weight === 'normal' ? '(?:normal|400)' : '(?:bold|700)';
            $this->assertMatchesRegularExpression('/font-weight\s*:\s*'.$weights.'\s*;?/', $rule);
        }

        $this->assertStringContainsString("font-family:'CutiBookman','Bookman Old Style','URW Bookman'", $css);
        $this->assertStringContainsString("@font-face{font-family:'CutiBookman'", $css);
    }

    public function test_persetujuan_selebar_form_dan_penandatangan_di_kolom_kanan_sesuai_word(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));

        // File Word terbaru memiliki heading & empat opsi keputusan selebar
        // formulir. Kolom tanda tangan pejabat terletak di 52% paling kanan,
        // sejajar dengan kolom tanda tangan pemohon (52%).
        $this->assertSame(2, substr_count($form, 'class="form-table tight approval-table word-table word-decision"'));
        $this->assertSame(2, substr_count($form, 'class="word-approval-empty"'));
        $this->assertSame(2, substr_count($form, 'class="word-decision-sign center"'));
        $this->assertSame(2, substr_count($form, 'style="width:52%"'));
        $this->assertStringContainsString('class="center word-applicant-sign"', $form);
        $this->assertStringContainsString('VII. PERTIMBANGAN ATASAN LANGSUNG', $form);
        $this->assertStringContainsString('VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI', $form);
        $this->assertStringContainsString('.form-page.word-cuti-form .approval-table{width:100%;margin-left:0;', $css);
    }
}
