<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfLayoutV121Test extends TestCase
{
    public function test_nomor_surat_izin_rata_kiri_dan_tetap_kosong(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $permission = file_get_contents(resource_path('views/pdf/_permission.blade.php'));
        $this->assertStringContainsString('<div class="letter-number">Nomor :</div>', $permission);
        $this->assertMatchesRegularExpression('/\.permission-page\s+\.letter-number\s*\{[^}]*text-align:left!important/s', $css);
    }

    public function test_sisa_cuti_N_rata_tengah_dan_tahun_lain_tidak_berubah(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('<td>N</td><td class="center word-current-balance">', $form);
        $this->assertStringContainsString('<td>N-1</td><td>', $form);
        $this->assertStringContainsString('<td>N-2</td><td>', $form);
    }

    public function test_nomor_internal_tidak_dicetak_pada_footer_form_dan_permohonan(): void
    {
        foreach (['pdf/_form.blade.php', 'pdf/_application.blade.php'] as $template) {
            $html = file_get_contents(resource_path('views/'.$template));
            $this->assertStringNotContainsString('{{ $leave->request_number }}', $html);
            $this->assertStringContainsString('DRAF — BELUM DITERBITKAN', $html);
        }
    }

    public function test_lima_baris_tanda_tangan_dengan_fallback_kepadatan_a4(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $this->assertStringContainsString('word-applicant-sign .word-sign-space', $css);
        $this->assertStringContainsString('word-decision td.word-decision-sign .word-sign-space', $css);
        $this->assertStringContainsString('height:5.2em!important', $css);
        $this->assertStringContainsString('density-7', $css);
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertSame(3, substr_count($form, '<div class="word-sign-space"></div>'));
    }
}
