<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompanionPageSettingRequest;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Services\CompanionPublicationReadinessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanionPageSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    public function edit(CompanionPublicationReadinessService $readinessService): View
    {
        $setting = CompanionPageSetting::firstOrCreate(['id' => 1], ['is_active' => false]);
        $readiness = $readinessService->getReadinessReport($setting, JourneyCompanion::ordered()->get());

        return view('admin.companions.settings', compact('setting', 'readiness'));
    }

    public function update(UpdateCompanionPageSettingRequest $request): RedirectResponse
    {
        $setting = CompanionPageSetting::firstOrCreate(['id' => 1], ['is_active' => false]);
        $validated = $request->validated();

        $imageFields = [
            'entrance_crest_image',
            'regeneration_image',
            'closing_crest_image',
            'og_image',
        ];

        $oldImagesToDelete = [];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $oldImagesToDelete[] = $setting->{$field};
                $validated[$field] = $this->storeUpload($request->file($field), 'page-' . Str::slug($field));
            } elseif ($request->boolean('remove_' . $field)) {
                $oldImagesToDelete[] = $setting->{$field};
                $validated[$field] = null;
            } else {
                unset($validated[$field]);
            }
            unset($validated['remove_' . $field]);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $setting->update($validated);

        foreach ($oldImagesToDelete as $oldImage) {
            $this->deleteUpload($oldImage);
        }

        $notification = [
            'messege' => trans('admin_validation.Updated Successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
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
