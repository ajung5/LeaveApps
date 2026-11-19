<?php

namespace Tests\Feature;

use App\Support\IndonesianNumberWords;
use Tests\TestCase;

class PdfDurationColumnsV120Test extends TestCase
{
    public function test_terbilang_berada_pada_kolom_selama_dan_angka_di_kolom_hari_bulan_tahun(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString(
            '<td>Selama : {{ \App\Support\IndonesianNumberWords::spell((int) $leave->duration_days) }} hari</td>',
            $form
        );
        $this->assertStringContainsString(
            '<span class="word-subline">(hari/bulan/tahun)</span><br><strong>{{ $leave->duration_days }} Hari</strong>',
            $form
        );
        // Tidak boleh tertukar kembali pada kolom kedua.
        $this->assertStringNotContainsString(
            '(hari/bulan/tahun)</span><br><strong>{{ \App\Support\IndonesianNumberWords::spell',
            $form
        );
    }

    public function test_terbilang_mengikuti_lama_cuti_sebenarnya(): void
    {
        $this->assertSame('Lima', IndonesianNumberWords::spell(5));
        $this->assertSame('Lima Belas', IndonesianNumberWords::spell(15));
    }
}
