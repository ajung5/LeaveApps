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

    public function test_persetujuan_sejajar_area_tanda_tangan_pemohon(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));

        // Layout v1.0.9: kolom kosong 53% + blok persetujuan 47%.
        // Lebar tabel persetujuan sendiri 100% dari kolom kanannya.
        $this->assertMatchesRegularExpression(
            '~<table\s+class="approval-layout"\s*>\s*<tr>\s*<td\s+width="53%"\s*>\s*</td>\s*<td\s+width="47%"\s*>~',
            $form
        );
        $this->assertMatchesRegularExpression(
            '/\.form-page\s+\.approval-layout\s*\{[^}]*width\s*:\s*100%\s*;[^}]*table-layout\s*:\s*fixed/',
            $css
        );
        $this->assertMatchesRegularExpression(
            '/\.form-page\s+\.approval-table\s*\{[^}]*width\s*:\s*100%/',
            $css
        );
        $this->assertSame(2, substr_count($form, 'class="form-table tight approval-table"'));
        $this->assertStringContainsString('VII. PERTIMBANGAN ATASAN LANGSUNG', $form);
        $this->assertStringContainsString('VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI', $form);

        // Pastikan formulir bagian VI dan bagian VII/VIII sejajar pada pembagian 53:47.
        $this->assertStringContainsString('<td width="53%"><strong>Alamat</strong>', $form);
    }
}
