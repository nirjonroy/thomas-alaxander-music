<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanionPageSettingRequest extends FormRequest
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
            'is_active' => ['sometimes', 'boolean'],
            'entrance_section_label' => ['nullable', 'string', 'max:255'],
            'entrance_title' => ['nullable', 'string', 'max:255'],
            'entrance_subtitle' => ['nullable', 'string', 'max:255'],
            'entrance_crest_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'entrance_crest_image_alt' => ['nullable', 'string', 'max:255'],
            'opening_teaching' => ['nullable', 'string'],
            'regeneration_title' => ['nullable', 'string', 'max:255'],
            'regeneration_subtitle' => ['nullable', 'string', 'max:255'],
            'regeneration_body' => ['nullable', 'string'],
            'regeneration_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'regeneration_image_alt' => ['nullable', 'string', 'max:255'],
            'witnesses_title' => ['nullable', 'string', 'max:255'],
            'witnesses_intro' => ['nullable', 'string'],
            'witness_wind_title' => ['nullable', 'string', 'max:255'],
            'witness_wind_body' => ['nullable', 'string'],
            'witness_earth_title' => ['nullable', 'string', 'max:255'],
            'witness_earth_body' => ['nullable', 'string'],
            'witness_water_title' => ['nullable', 'string', 'max:255'],
            'witness_water_body' => ['nullable', 'string'],
            'witness_sunlight_title' => ['nullable', 'string', 'max:255'],
            'witness_sunlight_body' => ['nullable', 'string'],
            'witness_night_title' => ['nullable', 'string', 'max:255'],
            'witness_night_body' => ['nullable', 'string'],
            'witness_rest_title' => ['nullable', 'string', 'max:255'],
            'witness_rest_body' => ['nullable', 'string'],
            'human_responsibility_title' => ['nullable', 'string', 'max:255'],
            'human_responsibility_body' => ['nullable', 'string'],
            'continuance_title' => ['nullable', 'string', 'max:255'],
            'continuance_body' => ['nullable', 'string'],
            'new_beginning_title' => ['nullable', 'string', 'max:255'],
            'new_beginning_body' => ['nullable', 'string'],
            'closing_crest_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'closing_crest_image_alt' => ['nullable', 'string', 'max:255'],
            'closing_text' => ['nullable', 'string'],
            'final_refrain' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
            'remove_entrance_crest_image' => ['nullable', 'boolean'],
            'remove_regeneration_image' => ['nullable', 'boolean'],
            'remove_closing_crest_image' => ['nullable', 'boolean'],
            'remove_og_image' => ['nullable', 'boolean'],
        ];
    }
}
