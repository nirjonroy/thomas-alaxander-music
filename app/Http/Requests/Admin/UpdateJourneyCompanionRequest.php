<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJourneyCompanionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $admin = auth('admin')->user();
        if ($admin && (int) $admin->status === 1) {
            return true;
        }

        $user = auth('web')->user();
        if ($user && (int) $user->status === 2 && (int) $user->active_status === 1 && $user->can('admin.dashboard')) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'themes' => ['nullable', 'string'],
            'artwork_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'artwork_image_alt' => ['nullable', 'string', 'max:255'],
            'expanded_teaching' => ['nullable', 'string'],
            'remember_teaching' => ['nullable', 'string'],
            'branding_info' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'remove_artwork_image' => ['nullable', 'boolean'],
        ];
    }
}
