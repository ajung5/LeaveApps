<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfTataNaskahTest extends TestCase
{
    public function test_a4_dan_margin_resmi_diterapkan(): void
    {
        $view = file_get_contents(resource_path('views/pdf/document.blade.php'));

        $this->assertMatchesRegularExpression(
            '/@page\s*\{\s*size\s*:\s*A4\s+portrait\s*;\s*margin\s*:\s*20mm\s+20mm\s+20mm\s+30mm\s*;?\s*\}/',
            $view
        );
        $this->assertMatchesRegularExpression(
            '/body\s*\{[^}]*font-size\s*:\s*12pt\s*;[^}]*line-height\s*:\s*1\.0/',
            $view
        );
        // Justify ditambahkan pada v1.0.9. Bukan berarti ukuran atau spasi berubah.
        $this->assertMatchesRegularExpression(
            '/\.body-letter\s*\{[^}]*font-size\s*:\s*12pt\s*;[^}]*line-height\s*:\s*1\.15\s*;[^}]*text-align\s*:\s*justify/',
            $view
        );
        $this->assertStringContainsString('margin-bottom:8.5mm', $view);
    }

    public function test_semua_surat_mendukung_font_bookman_dan_tata_letak_satu_halaman(): void
    {
        $view = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $controller = file_get_contents(app_path('Http/Controllers/DocumentController.php'));
        $this->assertStringContainsString('pdfBookmanRegular', $view);
        $this->assertStringContainsString('pdfBookmanBold', $view);
        $this->assertStringContainsString('localBookmanFonts()', $controller);
        $this->assertStringContainsString('$lastCount === 1', $controller);
        $this->assertStringContainsString('$bundleCount !== 3', $controller);
    }
}
