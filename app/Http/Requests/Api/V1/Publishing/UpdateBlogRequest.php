<?php

namespace App\Http\Requests\Api\V1\Publishing;

use App\Http\Requests\Api\V1\Publishing\Concerns\ResolvesPublishingRecordId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
{
    use ResolvesPublishingRecordId;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')->ignore($this->routeRecordId('blog'))],
            'blog_category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'featured_media_id' => ['nullable', 'integer', 'exists:publishing_media,id'],
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
            'status' => ['nullable', Rule::in([0, 1, '0', '1'])],
            'show_homepage' => ['nullable', Rule::in([0, 1, '0', '1'])],
            'id' => ['prohibited'],
            'admin_id' => ['prohibited'],
            'views' => ['prohibited'],
            'created_at' => ['prohibited'],
            'updated_at' => ['prohibited'],
        ];
    }
}
