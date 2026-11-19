<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfLayoutAlignmentTest extends TestCase
{
    public function test_identitas_pemohon_dirapatkan_dan_naskah_justify(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $this->assertStringContainsString('.letter-info .label{width:33mm', $css);
        $this->assertStringContainsString('.letter-info .sep{width:4.5mm', $css);
        $this->assertStringContainsString('.body-letter p{margin-bottom:4mm;text-align:justify}', $css);
    }

    public function test_form_persetujuan_dibungkus_dalam_layout_kanan(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('class="approval-layout"', $form);
        $this->assertStringContainsString('class="form-table tight approval-table"', $form);
    }
}
