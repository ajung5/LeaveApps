<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\LeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Setiap surat harus benar-benar satu halaman A4. Jika tidak muat, coba
     * template yang lebih rapat; jangan pernah mengabaikan jumlah halaman.
     */
    public function pdf(Request $request, LeaveRequest $leave, string $kind)
    {
        $this->permit($request, $leave);
        abort_unless(in_array($kind, ['form', 'application', 'permission', 'bundle'], true), 404);
        if (in_array($kind, ['permission', 'bundle'], true)) {
            abort_unless($leave->status === 'issued', 403);
        }

        $leave->load('employee.unit', 'type', 'decisions');
        $snapshot = $leave->employee_snapshot;
        $employee = $snapshot ?: [
            'name' => $leave->employee->full_name,
            'nip' => $leave->employee->nip,
            'rank' => $leave->employee->rank,
            'grade' => $leave->employee->grade,
            'position' => $leave->employee->position,
            'unit' => $leave->employee->unit?->name ?? '',
            'service_start_date' => $leave->employee->service_start_date?->toDateString(),
            'supervisor_name' => $leave->employee->supervisor_name,
            'supervisor_nip' => $leave->employee->supervisor_nip,
            'supervisor_position' => $leave->employee->supervisor_position,
        ];
        $settings = $leave->official_snapshot ?: [
            'government_name' => AppSetting::read('government_name'),
            'institution_name' => AppSetting::read('institution_name'),
            'institution_address' => AppSetting::read('institution_address'),
            'institution_phone' => AppSetting::read('institution_phone'),
            'institution_city' => AppSetting::read('institution_city'),
            'authority_name' => AppSetting::read('authority_name'),
            'authority_nip' => AppSetting::read('authority_nip'),
            'authority_position' => AppSetting::read('authority_position'),
            'authority_rank' => AppSetting::read('authority_rank'),
        ];
        $logo = base64_encode(file_get_contents(public_path('assets/img/lambang-subang.jpeg')));
        // Bookman Old Style wajib disediakan sendiri secara sah pada server.
        // File font tidak disertakan dalam source/patch. Digunakan untuk ketiga PDF.
        [$pdfBookmanRegular, $pdfBookmanBold] = $this->localBookmanFonts();
        if (! $pdfBookmanRegular || ! $pdfBookmanBold) {
            Log::warning('Bookman Old Style belum tersedia; PDF memakai fallback serif, tidak sepenuhnya sesuai tata naskah.', [
                'leave_id' => $leave->getKey(),
            ]);
        }
        $data = compact('leave', 'employee', 'settings', 'logo', 'pdfBookmanRegular', 'pdfBookmanBold');

        // Rendering tiap bagian secara independen memastikan bundle tidak
        // bisa dianggap benar bila salah satu surat meluber atau kosong.
        $parts = $kind === 'bundle' ? ['form', 'permission', 'application'] : [$kind];
        $density = ['form' => 0, 'permission' => 0, 'application' => 0];

        foreach ($parts as $part) {
            $fitted = false;
            $lastCount = null;
            foreach ([0, 1, 2] as $level) {
                $density[$part] = $level;
                $partPdf = $this->renderPdf($data + ['kind' => $part, 'density' => $density]);
                $lastCount = $partPdf->getDomPDF()->getCanvas()->get_page_count();
                if ($lastCount === 1) {
                    $fitted = true;
                    break;
                }
            }
            if (! $fitted) {
                Log::warning('Dokumen cuti melampaui satu halaman A4', [
                    'leave_id' => $leave->getKey(), 'kind' => $part,
                    'pages' => $lastCount, 'max_density' => 2,
                ]);
                abort(422, "Dokumen {$part} menghasilkan {$lastCount} halaman (harus 1). "
                    .'Periksa panjang isian alasan/alamat dan tata letak PDF. Tidak ada isi yang dipotong.');
            }
            if ($kind !== 'bundle') {
                return $this->pdfResponse($partPdf, $kind, $leave, 1);
            }
        }

        $bundlePdf = $this->renderPdf($data + ['kind' => 'bundle', 'density' => $density]);
        $bundleCount = $bundlePdf->getDomPDF()->getCanvas()->get_page_count();
        if ($bundleCount !== 3) {
            Log::warning('Gabungan PDF cuti tidak tepat tiga halaman', [
                'leave_id' => $leave->getKey(), 'pages' => $bundleCount, 'density' => $density,
            ]);
            abort(422, "Bundle menghasilkan {$bundleCount} halaman (harus 3). "
                .'Periksa template/pemisahan halaman; PDF tidak diterbitkan.');
        }

        return $this->pdfResponse($bundlePdf, $kind, $leave, 3);
    }

    /**
     * Memakai Bookman Old Style yang sudah tersedia secara sah pada server.
     * Tidak memaketkan/menyalin file font komersial dalam distribusi.
     *
     * @return array{0: ?string, 1: ?string}
     */
    private function localBookmanFonts(): array
    {
        $directories = [
            storage_path('app/private/pdf-fonts'),
            '/Library/Fonts',
            '/System/Library/Fonts/Supplemental',
        ];

        $pairs = [
            ['Bookman Old Style.ttf', 'Bookman Old Style Bold.ttf'],
            ['BookmanOldStyle.ttf', 'BookmanOldStyle-Bold.ttf'],
            ['bookos.ttf', 'bookosb.ttf'],
            ['BOOKOS.TTF', 'BOOKOSB.TTF'],
            ['BookmanOldStyle-Regular.ttf', 'BookmanOldStyle-Bold.ttf'],
        ];

        foreach ($directories as $directory) {
            foreach ($pairs as [$regularName, $boldName]) {
                $regular = $directory.DIRECTORY_SEPARATOR.$regularName;
                $bold = $directory.DIRECTORY_SEPARATOR.$boldName;
                if (is_file($regular) && is_readable($regular) && is_file($bold) && is_readable($bold)) {
                    return [base64_encode(file_get_contents($regular)), base64_encode(file_get_contents($bold))];
                }
            }
        }

        return [null, null];
    }

    /**
     * Dompdf membuat berkas metrik .ufm ketika font Bookman lokal diregistrasi.
     * Direktori cache harus ada dan dapat ditulis SEBELUM proses render.
     */
    private function ensurePdfFontCacheDirectory(string $directory): void
    {
        if (! is_dir($directory) && ! @mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new \RuntimeException('Direktori cache font PDF gagal dibuat: '.$directory);
        }

        if (! is_dir($directory) || ! is_writable($directory)) {
            throw new \RuntimeException(
                'Direktori cache font PDF tidak dapat ditulis: '.$directory
                .'. Periksa ownership dan izin akses pengguna PHP.'
            );
        }
    }

    private function renderPdf(array $data)
    {
        $fontCache = storage_path('fonts');
        $this->ensurePdfFontCacheDirectory($fontCache);

        $pdf = Pdf::loadView('pdf.document', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', false)
            ->setOption('fontDir', $fontCache)
            ->setOption('fontCache', $fontCache);
        $pdf->render();

        return $pdf;
    }

    private function pdfResponse($pdf, string $kind, LeaveRequest $leave, int $pages)
    {
        $response = $pdf->stream($kind.'-'.$leave->request_number.'.pdf');
        $response->headers->set('X-Cuti-Pdf-Pages', (string) $pages);

        return $response;
    }

    public function attachment(Request $request, LeaveRequest $leave, int $attachment)
    {
        $this->permit($request, $leave);
        $file = $leave->attachments()->findOrFail($attachment);
        abort_unless(Storage::disk('local')->exists($file->path), 404);

        return Storage::disk('local')->download($file->path, $file->original_name);
    }

    public function signed(Request $request, LeaveRequest $leave)
    {
        $this->permit($request, $leave);
        abort_unless($leave->status === 'issued' && $leave->signed_document_path, 404);
        abort_unless(Storage::disk('local')->exists($leave->signed_document_path), 404);

        return Storage::disk('local')->download($leave->signed_document_path, 'signed-'.$leave->request_number.'.pdf');
    }

    private function permit(Request $request, LeaveRequest $leave): void
    {
        abort_unless($request->user()->isAdmin() || $leave->employee->user_id === $request->user()->id, 403);
    }
}
