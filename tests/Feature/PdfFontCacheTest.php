<?php

namespace Tests\Feature;

use App\Http\Controllers\DocumentController;
use ReflectionMethod;
use RuntimeException;
use Tests\TestCase;

class PdfFontCacheTest extends TestCase
{
    public function test_direktori_cache_font_dibuat_dan_dapat_dipakai_ulang(): void
    {
        $directory = sys_get_temp_dir().'/cuti_pdf_font_'.bin2hex(random_bytes(8));
        $method = new ReflectionMethod(DocumentController::class, 'ensurePdfFontCacheDirectory');
        $controller = app(DocumentController::class);

        try {
            $this->assertDirectoryDoesNotExist($directory);
            $method->invoke($controller, $directory);
            $this->assertDirectoryExists($directory);
            $this->assertTrue(is_writable($directory));
            $method->invoke($controller, $directory);
            $this->assertDirectoryExists($directory);
        } finally {
            if (is_dir($directory)) {
                rmdir($directory);
            }
        }
    }

    public function test_direktori_cache_font_menolak_path_yang_berupa_file(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'cuti_pdf_cache_');
        $this->assertNotFalse($path);
        $method = new ReflectionMethod(DocumentController::class, 'ensurePdfFontCacheDirectory');

        try {
            $this->expectException(RuntimeException::class);
            $method->invoke(app(DocumentController::class), $path);
        } finally {
            unlink($path);
        }
    }
}
