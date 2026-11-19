<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfDompdfColumnWidthsTest extends TestCase
{
    public function test_ukuran_kolom_formulir_memiliki_panduan_eksplisit_bukan_hanya_colgroup(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));

        foreach (['word-employee'=>1,'word-types'=>1,'word-duration'=>1,
                  'word-history'=>1,'word-address'=>1,'word-decision'=>2] as $table=>$expected) {
            $pattern = '/<table class="[^"]*\\b'.preg_quote($table,'/').'\\b[^"]*"[^>]*>\\s*'
                .'<colgroup>.*?<\\/colgroup>\\s*<tr class="word-column-guide"/s';
            $this->assertSame($expected, preg_match_all($pattern, $form),
                "Lebar {$table} harus didefinisikan sebelum heading colspan.");
        }
        $this->assertStringContainsString('.word-column-guide td', $css);
        $this->assertStringContainsString('table-layout:auto!important', $css);
        $this->assertStringContainsString('padding:0!important;border:0!important', $css);
    }

    public function test_proporsi_lebar_tabel_seperti_pdf_acuan(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        foreach ([
            ['word-employee',['25.6mm','57.6mm','27.2mm','49.6mm']],
            ['word-types',['56mm','22.4mm','57.6mm','24mm']],
            ['word-address',['76.8mm','28.8mm','54.4mm']],
            ['word-decision',['23.2mm','23.2mm','30.4mm','83.2mm']],
        ] as [$table,$widths]) {
            preg_match('/<table class="[^"]*\\b'.preg_quote($table,'/').'\\b[^"]*"[^>]*>\\s*'
                .'<colgroup>.*?<\\/colgroup>\\s*<tr class="word-column-guide"[^>]*>(.*?)<\\/tr>/s', $form, $match);
            $this->assertNotEmpty($match[1] ?? null, "Grid width {$table} tidak ada");
            preg_match_all('/style="width:([0-9.]+mm)"/', $match[1], $found);
            $this->assertSame($widths, $found[1], "Grid {$table} tidak sesuai acuan");
        }
        $this->assertStringContainsString('data-word-section="VII"', $form);
        $this->assertStringContainsString('data-word-section="VIII"', $form);
        $this->assertStringContainsString('data-reference-layout="Pratinjau_Formulir_Cuti_v1.0.11_A4"', $form);
    }
}
