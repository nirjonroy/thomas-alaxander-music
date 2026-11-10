<?php

namespace App\Http\Controllers\WEB\Frontend;

use App\Http\Controllers\Controller;
use App\Models\EpkPage;
use File;

class EpkController extends Controller
{
    public function show(string $slug)
    {
        $epkPage = EpkPage::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $epkPages = EpkPage::published()
            ->ordered()
            ->get(['id', 'title', 'slug', 'subtitle']);

        $alternateEpkPage = $epkPages->firstWhere('id', '!=', $epkPage->id);

        return view('frontend.epk.show', compact('epkPage', 'epkPages', 'alternateEpkPage'));
    }

    public function viewPdf(string $slug)
    {
        $epkPage = EpkPage::published()
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless($epkPage->pdf_file, 404);

        $filePath = public_path($epkPage->pdf_file);
        abort_unless(File::exists($filePath) && File::isFile($filePath), 404);

        $displayName = $this->getPdfDisplayName($epkPage);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $displayName . '"',
        ]);
    }

    public function downloadPdf(string $slug)
    {
        $epkPage = EpkPage::published()
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless($epkPage->pdf_file, 404);

        $filePath = public_path($epkPage->pdf_file);
        abort_unless(File::exists($filePath) && File::isFile($filePath), 404);

        $displayName = $this->getPdfDisplayName($epkPage);

        return response()->download($filePath, $displayName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function getPdfDisplayName(EpkPage $epkPage): string
    {
        if ($epkPage->slug === 'crooners') {
            return 'Thomas-Alexander-Crooners-EPK.pdf';
        }

        return 'Thomas-Alexander-Full-Artist-EPK.pdf';
    }
}
