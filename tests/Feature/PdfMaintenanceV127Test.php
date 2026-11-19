<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdfMaintenanceV127Test extends TestCase
{
    public function test_jarak_kop_surat_izin_enam_per_sepuluh_milimeter(): void
    {
        $css = file_get_contents(resource_path('views/pdf/document.blade.php'));
        $this->assertStringContainsString(
            '.permission-page .letterhead-rule-secondary{border-top:.85pt solid #111;height:0;margin-top:.6mm;',
            $css
        );
        $this->assertStringContainsString(
            '.permission-page .letterhead-rule-primary{border-top:3pt solid #111;',
            $css
        );
    }

    public function test_backup_residual_diabaikan_git(): void
    {
        $ignore = file_get_contents(base_path('.gitignore'));
        $this->assertStringContainsString('*.bak_v*', $ignore);
        $this->assertStringContainsString('*.orig', $ignore);
        $this->assertStringContainsString('*.rej', $ignore);
    }
}
