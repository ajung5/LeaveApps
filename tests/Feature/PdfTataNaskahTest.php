<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfTataNaskahTest extends TestCase
{
    public function test_a4_dan_margin_resmi_diterapkan(): void
    {
        $view = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $this->assertStringContainsString('@page{size:A4 portrait;margin:20mm 20mm 20mm 30mm}', $view);
        $this->assertStringContainsString("font-size:12pt;line-height:1.0", $view);
        $this->assertStringContainsString('.body-letter{font-size:12pt;line-height:1.15}', $view);
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
