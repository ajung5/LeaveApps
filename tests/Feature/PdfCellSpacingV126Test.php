<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfCellSpacingV126Test extends TestCase
{
    public function test_jarak_antar_garis_kop_adalah_delapan_per_sepuluh_milimeter(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $this->assertStringContainsString(
            '.permission-page .letterhead-rule-secondary{border-top:.85pt solid #111;height:0;margin-top:.8mm;',
            $css
        );
        $this->assertStringNotContainsString('margin-top:1.2mm;', $css);
        $this->assertStringContainsString('.permission-page .letterhead-rule-primary{border-top:3pt solid #111;', $css);
    }

    public function test_padding_isi_sel_formulir_sekitar_satu_spasi_tetap_satu_halaman_a4(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));

        $this->assertMatchesRegularExpression(
            '/\.form-page\.word-cuti-form\.reference-v111\.density-2 \.word-table td\s*\{\s*padding:\.6mm 1mm;\s*\}/',
            $css
        );
        // Baris tersembunyi yang menjaga lebar kolom tetap bebas padding.
        $this->assertStringContainsString('.word-column-guide td,', $css);
        $this->assertStringContainsString('padding:0!important;border:0!important;', $css);
        // Mode density untuk kasus isi panjang tidak dihapus.
        foreach ([3, 4, 5, 6, 7] as $level) {
            $this->assertStringContainsString("reference-v111.density-{$level} .word-table td{", $css);
        }
        $this->assertStringContainsString('@page{size:A4 portrait;margin:20mm 20mm 20mm 30mm}', $css);
    }
}
