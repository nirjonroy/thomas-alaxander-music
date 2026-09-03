<?php

namespace App\Http\Requests\Api\V1\Publishing;

use App\Services\Publishing\PublishingMediaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublishingMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'purpose' => ['nullable', Rule::in(PublishingMediaService::PURPOSES)],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'A media file is required.',
            'file.image' => 'The file must be a valid JPEG, PNG, or WEBP image.',
            'file.mimes' => 'The file must be a JPEG, PNG, or WEBP image.',
            'file.max' => 'The file may not be greater than 10240 kilobytes.',
            'purpose.in' => 'The selected media purpose is not supported.',
        ];
    }
}
