<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfWordFidelityTest extends TestCase
{
    public function test_form_penerima_mengikuti_pdf_pratinjau_yang_dipilih_pengguna(): void
    {
        $form = file_get_contents(resource_path('views/pdf/_form.blade.php'));
        $this->assertStringContainsString('data-reference-layout="Pratinjau_Formulir_Cuti_v1.0.11_A4"', $form);
        $this->assertStringContainsString('Yth. {{ $settings[\'authority_position\'] }}', $form);
        // v1.0.17: tujuan hanya satu jabatan; nama OPD tidak boleh terulang.
        preg_match('/<table class="form-addressee">(.*?)<\/table>/s', $form, $found);
        $this->assertNotEmpty($found[1] ?? null, 'Blok tujuan surat tidak ditemukan.');
        $recipient = $found[1];
        $this->assertSame(1, substr_count($recipient, "{{ \$settings['authority_position'] }}"));
        $this->assertStringNotContainsString("{{ \$settings['institution_name'] }}", $recipient);
        $this->assertStringContainsString('Kepada', $recipient);
        $this->assertStringContainsString('TEMPAT', $recipient);
        $this->assertStringContainsString('III. ALASAN CUTI', $form);
        $this->assertStringContainsString('{{ $leave->type->name }}', $form);
        $this->assertStringNotContainsString('{{ $leave->reason }}', $form);
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
