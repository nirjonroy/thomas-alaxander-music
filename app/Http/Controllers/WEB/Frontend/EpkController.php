<?php

namespace App\Http\Controllers\WEB\Frontend;

use App\Http\Controllers\Controller;
use App\Models\EpkPage;

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
}
