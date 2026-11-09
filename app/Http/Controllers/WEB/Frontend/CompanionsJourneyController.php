<?php

namespace App\Http\Controllers\WEB\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Services\CompanionPublicationReadinessService;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CompanionsJourneyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth')->only(['preview', 'previewAsPublic']);
    }

    public function show(CompanionPublicationReadinessService $readinessService): View
    {
        $setting = CompanionPageSetting::first();
        $companions = JourneyCompanion::ordered()->get();

        if (! $readinessService->isReadyForPublication($setting, $companions)) {
            abort(404);
        }

        return view('frontend.living-archive.companions', [
            'setting' => $setting,
            'companions' => $companions,
            'isPreview' => false,
            'readiness' => null,
        ]);
    }

    public function preview(CompanionPublicationReadinessService $readinessService): Response
    {
        $setting = CompanionPageSetting::first() ?? new CompanionPageSetting([
            'is_active' => false,
            'entrance_title' => 'COMPANIONS ALONG THE JOURNEY',
        ]);

        $companions = JourneyCompanion::ordered()->get();
        $readiness = $readinessService->getReadinessReport($setting, $companions);

        return response()
            ->view('frontend.living-archive.companions', [
                'setting' => $setting,
                'companions' => $companions,
                'isPreview' => true,
                'readiness' => $readiness,
            ])
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, private');
    }

    /**
     * Local design review only. This intentionally does not alter public
     * publication readiness or expose an unauthenticated preview route.
     */
    public function previewAsPublic(): Response
    {
        abort_unless(app()->environment(['local', 'testing']), 404);

        $setting = CompanionPageSetting::first() ?? new CompanionPageSetting([
            'is_active' => false,
            'entrance_title' => 'COMPANIONS ALONG THE JOURNEY',
        ]);

        return response()
            ->view('frontend.living-archive.companions', [
                'setting' => $setting,
                'companions' => JourneyCompanion::ordered()->get(),
                'isPreview' => false,
                'readiness' => null,
            ])
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, private');
    }
}
