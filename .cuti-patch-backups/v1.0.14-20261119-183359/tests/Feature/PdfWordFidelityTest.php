<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfWordFidelityTest extends TestCase
{
    public function test_form_penerima_menggunakan_jabatan_dan_wilayah_tanpa_mengulang_nama_dinas(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));

        $this->assertStringContainsString('Yth. {{ $recipientPosition }}', $form);
        $this->assertStringContainsString('{{ $recipientRegion }}', $form);
        $this->assertStringNotContainsString('Yth. {{ $settings[\'authority_position\'] }}', $form);
        $this->assertStringContainsString('III. ALASAN CUTI', $form);
        $this->assertStringContainsString('{{ $leave->reason }}', $form);
    }

    public function test_surat_permohonan_tidak_mencetak_baris_alasan_tersendiri(): void
    {
        $application = file_get_contents(resource_path('views/pdf/_application.blade.php'));

        $this->assertStringNotContainsString('Alasan permohonan:', $application);
        $this->assertStringNotContainsString('{{ $leave->reason }}', $application);
        $this->assertStringContainsString('SURAT PERMOHONAN', $application);
        $this->assertStringContainsString('Demikian surat permohonan', $application);
    }
}
