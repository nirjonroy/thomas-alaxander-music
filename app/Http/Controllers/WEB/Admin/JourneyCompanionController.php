<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateJourneyCompanionRequest;
use App\Models\JourneyCompanion;
use App\Models\CompanionPageSetting;
use App\Services\CompanionPublicationReadinessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JourneyCompanionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    public function index(CompanionPublicationReadinessService $readinessService): View
    {
        $companions = JourneyCompanion::ordered()->get();
        $readiness = $readinessService->getReadinessReport(CompanionPageSetting::first(), $companions);

        return view('admin.companions.index', compact('companions', 'readiness'));
    }

    public function edit(JourneyCompanion $companion): View
    {
        return view('admin.companions.edit', compact('companion'));
    }

    public function update(UpdateJourneyCompanionRequest $request, JourneyCompanion $companion): RedirectResponse
    {
        $validated = $request->validated();

        $updateData = [
            'themes' => $validated['themes'] ?? null,
            'artwork_image_alt' => $validated['artwork_image_alt'] ?? null,
            'expanded_teaching' => $validated['expanded_teaching'] ?? null,
            'remember_teaching' => $validated['remember_teaching'] ?? null,
            'branding_info' => $validated['branding_info'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        // Explicit safeguard: Never accept sequence, name, or slug from request
        unset($updateData['sequence'], $updateData['name'], $updateData['slug']);

        $oldImage = $companion->artwork_image;

        if ($request->hasFile('artwork_image')) {
            $newPath = $this->storeUpload($request->file('artwork_image'), 'companion-' . $companion->slug);
            $updateData['artwork_image'] = $newPath;
        } elseif ($request->boolean('remove_artwork_image')) {
            $updateData['artwork_image'] = null;
        }

        $companion->update($updateData);

        // Delete old image only after successful DB update and if a new image was saved or removal requested
        if (($request->hasFile('artwork_image') || $request->boolean('remove_artwork_image')) && $oldImage) {
            $this->deleteUpload($oldImage);
        }

        $notification = [
            'messege' => trans('admin_validation.Updated Successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->route('admin.companions-journey.index')->with($notification);
    }

    private function storeUpload($file, string $prefix): string
    {
        $directory = public_path('uploads/companions');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
        $filename = $prefix . '-' . date('YmdHis') . '-' . Str::random(8) . '.' . $extension;
        $file->move($directory, $filename);

        return 'uploads/companions/' . $filename;
    }

    private function deleteUpload(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        $normalized = str_replace('\\', '/', $path);
        if (! Str::startsWith($normalized, 'uploads/companions/')) {
            return;
        }

        $fullPath = public_path($normalized);

        if (File::exists($fullPath) && File::isFile($fullPath)) {
            File::delete($fullPath);
        }
    }
}
