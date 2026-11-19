<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfReferencePreviewTest extends TestCase
{
    public function test_form_uses_reference_layout_instead_of_old_default(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $controller = file_get_contents(app_path('Http/Controllers/DocumentController.php'));

        $this->assertStringContainsString('data-reference-layout="Pratinjau_Formulir_Cuti_v1.0.11_A4"', $form);
        $this->assertStringContainsString('reference-v111 density-', $form);
        $this->assertStringContainsString("'form' => 2", $controller);
        $this->assertStringContainsString('X-Cuti-Form-Density', $controller);
        $this->assertStringContainsString('reference-v111.density-2 .word-table td{padding:.5mm .55mm;font-size:9.25pt', $css);
        $this->assertStringContainsString('@page{size:A4 portrait;margin:20mm 20mm 20mm 30mm}', $css);
    }

    public function test_form_contains_eight_sections_and_signatures_right_aligned(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        foreach (['I. DATA PEGAWAI', 'II. JENIS CUTI', 'III. ALASAN CUTI', 'IV. LAMANYA CUTI',
                  'V. CATATAN CUTI', 'VI. ALAMAT SELAMA', 'VII. PERTIMBANGAN', 'VIII. KEPUTUSAN'] as $heading) {
            $this->assertStringContainsString($heading, $form);
        }
        $this->assertSame(2, substr_count($form, 'data-word-section='));
        $this->assertSame(2, substr_count($form, 'colspan="3" class="word-approval-empty word-approval-empty-tail"'));
        $this->assertSame(2, substr_count($form, '<col style="width:52%">'));
    }
}
