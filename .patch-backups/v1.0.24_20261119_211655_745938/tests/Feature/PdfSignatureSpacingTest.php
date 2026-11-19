<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfSignatureSpacingTest extends TestCase
{
    public function test_tujuan_formulir_hanya_mencetak_jabatan_tujuan(): void
    {
        $view = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        preg_match('/<table class="form-addressee">(.*?)<\/table>/s', $view, $found);
        $this->assertNotEmpty($found[1] ?? null);
        $recipient = $found[1];
        $this->assertSame(1, substr_count($recipient, "{{ \$settings['authority_position'] }}"));
        $this->assertStringNotContainsString("{{ \$settings['institution_name'] }}", $recipient);
        $this->assertStringContainsString('Kepada', $recipient);
        $this->assertStringContainsString('TEMPAT', $recipient);
    }

    public function test_ruang_tanda_tangan_tiga_baris_di_seluruh_dokumen(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $permission = file_get_contents(resource_path('views/pdf/_permission.blade.php'));
        $application = file_get_contents(resource_path('views/pdf/_application.blade.php'));
        $this->assertSame(3, substr_count($form, '<div class="word-sign-space"></div>'));
        $this->assertStringContainsString('.word-applicant-sign .word-sign-space{', $css);
        $this->assertStringContainsString('height:3.18em!important;', $css);
        $this->assertStringContainsString('.word-decision td.word-decision-sign .word-sign-space{', $css);
        $this->assertStringContainsString('height:3.15em!important;', $css);
        $this->assertStringContainsString('.permission-page .signature-block .sig-height,', $css);
        $this->assertStringContainsString('.application-page .signature-block .sig-height{', $css);
        $this->assertStringContainsString('height:3.45em!important;', $css);
        $this->assertStringContainsString('<div class="sig-height"></div>', $permission);
        $this->assertStringContainsString('<div class="sig-height"></div>', $application);
    }
}
