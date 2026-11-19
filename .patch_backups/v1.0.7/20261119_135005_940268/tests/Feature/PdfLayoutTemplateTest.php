<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfLayoutTemplateTest extends TestCase
{
    public function test_kop_surat_menggunakan_proporsi_3_4_dan_arial(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $this->assertStringContainsString('.letterhead-text .gov{font-size:12pt;font-weight:normal}', $css);
        $this->assertStringContainsString('.letterhead-text .agency{font-size:16pt;font-weight:bold}', $css);
        $this->assertStringContainsString("font-family:'CutiArial',Arial,Helvetica,sans-serif", $css);
        $this->assertStringContainsString("@font-face{font-family:'CutiArial'", $css);
    }

    public function test_persetujuan_sejajar_area_tanda_tangan_pemohon(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('class="form-table tight approval-table"', $form);
        $this->assertSame(2, substr_count($form, 'class="form-table tight approval-table"'));
        $this->assertStringContainsString('margin-left:53%', $css);
        $this->assertStringContainsString('width:47%', $css);
        $this->assertStringContainsString('VII. PERTIMBANGAN ATASAN LANGSUNG', $form);
        $this->assertStringContainsString('VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI', $form);
    }
}
