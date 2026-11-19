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

    public function test_form_keputusan_memiliki_empat_kolom_dan_signature_kanan(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('data-word-section="VII"', $form);
        $this->assertStringContainsString('data-word-section="VIII"', $form);
        $this->assertSame(2, substr_count($form, 'colspan="3" rowspan="2" class="word-approval-empty"'));
        $this->assertStringContainsString('class="center word-applicant-sign"', $form);
    }
}
