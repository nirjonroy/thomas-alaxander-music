<?php

namespace App\Http\Requests\Admin;

use App\Models\LivingArchiveEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLivingArchiveEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check() || auth()->check();
    }

    public function rules(): array
    {
        $entry = $this->route('living_archive_entry');
        $entryId = $entry instanceof LivingArchiveEntry ? $entry->id : $entry;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('living_archive_entries', 'slug')->ignore($entryId)],
            'section_label' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', Rule::exists('living_archive_entries', 'id')],
            'page_type' => ['required', 'string', 'max:80'],
            'teaser' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'document_caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
            'document_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'document_image_alt' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
            'remove_featured_image' => ['nullable', 'boolean'],
            'remove_document_image' => ['nullable', 'boolean'],
            'remove_og_image' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $entry = $this->route('living_archive_entry');

            if (! $entry instanceof LivingArchiveEntry) {
                return;
            }

            $parentId = $this->input('parent_id');

            if (! $parentId) {
                return;
            }

            if ((int) $parentId === (int) $entry->id) {
                $validator->errors()->add('parent_id', 'A Living Archive page cannot be its own parent.');

                return;
            }

            while ($parentId) {
                $parent = LivingArchiveEntry::find($parentId);

                if (! $parent) {
                    return;
                }

                if ((int) $parent->parent_id === (int) $entry->id) {
                    $validator->errors()->add('parent_id', 'This parent would create a circular archive hierarchy.');

                    return;
                }

                $parentId = $parent->parent_id;
            }
        });
    }
}
