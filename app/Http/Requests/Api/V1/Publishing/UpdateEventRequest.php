<?php

namespace App\Http\Requests\Api\V1\Publishing;

use App\Http\Requests\Api\V1\Publishing\Concerns\ResolvesPublishingRecordId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    use ResolvesPublishingRecordId;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'time' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'featured_media_id' => ['nullable', 'integer', 'exists:publishing_media,id'],
            'ticket_price' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'seo_author' => ['nullable', 'string', 'max:255'],
            'seo_publisher' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'meta_media_id' => ['nullable', 'integer', 'exists:publishing_media,id'],
            'meta_copyright' => ['nullable', 'string', 'max:255'],
            'site_name' => ['nullable', 'string', 'max:255'],
            'id' => ['prohibited'],
            'status' => ['prohibited'],
            'created_at' => ['prohibited'],
            'updated_at' => ['prohibited'],
            'start_date' => ['prohibited'],
            'end_date' => ['prohibited'],
            'date_range' => ['prohibited'],
        ];
    }
}
