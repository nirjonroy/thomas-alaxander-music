<?php

namespace App\Http\Requests\Api\V1\Publishing;

use App\Http\Requests\Api\V1\Publishing\Concerns\ResolvesPublishingRecordId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublishBlogRequest extends FormRequest
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
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('blogs', 'slug')->ignore($this->routeRecordId('blog'))],
            'blog_category_id' => [
                'sometimes',
                'integer',
                Rule::exists('blog_categories', 'id')->where(fn ($query) => $query->where('status', 1)),
            ],
            'image' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'description' => ['sometimes', 'string', 'min:1'],
        ];
    }
}
