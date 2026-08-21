<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEpkPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check() || auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('epk_pages', 'slug')],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'overview_content' => ['nullable', 'string'],
            'lane_titles' => ['nullable', 'array'],
            'lane_titles.*' => ['nullable', 'string', 'max:255'],
            'lane_bodies' => ['nullable', 'array'],
            'lane_bodies.*' => ['nullable', 'string', 'max:255'],
            'engagement_items' => ['nullable', 'array'],
            'engagement_items.*' => ['nullable', 'string', 'max:255'],
            'repertoire_items' => ['nullable', 'array'],
            'repertoire_items.*' => ['nullable', 'string', 'max:255'],
            'testimonial_sources' => ['nullable', 'array'],
            'testimonial_sources.*' => ['nullable', 'string', 'max:255'],
            'testimonial_credentials' => ['nullable', 'array'],
            'testimonial_credentials.*' => ['nullable', 'string', 'max:255'],
            'testimonial_quotes' => ['nullable', 'array'],
            'testimonial_quotes.*' => ['nullable', 'string', 'max:1000'],
            'audio_caption' => ['nullable', 'string', 'max:1000'],
            'video_intro' => ['nullable', 'string', 'max:1000'],
            'booking_body' => ['nullable', 'string'],
            'section_titles' => ['nullable', 'array'],
            'section_titles.*' => ['nullable', 'string', 'max:255'],
            'section_bodies' => ['nullable', 'array'],
            'section_bodies.*' => ['nullable', 'string'],
            'audio_url' => ['nullable', 'url', 'max:255'],
            'audio_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac,webm', 'max:20480'],
            'audio_title' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/)[A-Za-z0-9_-]+/'],
            'video_title' => ['nullable', 'string', 'max:255'],
            'booking_email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'hero_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'hero_image_alt' => ['nullable', 'string', 'max:255'],
            'gold_feather_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'gold_feather_image_alt' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
        ];
    }
}
