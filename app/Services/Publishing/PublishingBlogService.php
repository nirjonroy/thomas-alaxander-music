<?php

namespace App\Services\Publishing;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PublishingBlogService
{
    private const FIELDS = [
        'title',
        'slug',
        'blog_category_id',
        'image',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_author',
        'seo_publisher',
        'canonical_url',
        'meta_title',
        'meta_description',
        'meta_image',
        'meta_copyright',
        'site_name',
        'status',
        'show_homepage',
    ];

    private PublishingSlugService $slugService;
    private PublishingContentSanitizer $sanitizer;

    public function __construct(PublishingSlugService $slugService, PublishingContentSanitizer $sanitizer)
    {
        $this->slugService = $slugService;
        $this->sanitizer = $sanitizer;
    }

    public function prepareDraftData(array $payload, ?Blog $blog = null): array
    {
        $data = array_intersect_key($payload, array_flip(self::FIELDS));
        unset($data['status']);

        if (array_key_exists('slug', $data) || array_key_exists('title', $data)) {
            $data['slug'] = $this->slugService->uniqueSlug(
                'blogs',
                $data['title'] ?? $blog?->title ?? 'blog',
                $data['slug'] ?? null,
                $blog?->id
            );
        }

        if (array_key_exists('description', $data)) {
            $data['description'] = $this->sanitizer->sanitize($data['description']);
        }

        if (array_key_exists('status', $data)) {
            $data['status'] = (int) $data['status'];
        }

        if (array_key_exists('show_homepage', $data)) {
            $data['show_homepage'] = (int) $data['show_homepage'];
        }

        return $data;
    }

    public function createDraft(array $payload, ?int $adminId = null): Blog
    {
        $blog = new Blog();
        $blog->admin_id = $adminId;
        $blog->status = 0;

        return $this->assignAndSave($blog, $this->prepareDraftData($payload));
    }

    public function updateDraft(Blog $blog, array $payload): Blog
    {
        return $this->assignAndSave($blog, $this->prepareDraftData($payload, $blog));
    }

    public function validateForPublish(Blog $blog): void
    {
        $validator = Validator::make($blog->getAttributes(), [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'blog_category_id' => ['required', 'integer'],
            'image' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:1'],
        ]);

        $validator->after(function ($validator) use ($blog) {
            if ($blog->blog_category_id && ! BlogCategory::query()->where('id', $blog->blog_category_id)->where('status', 1)->exists()) {
                $validator->errors()->add('blog_category_id', 'An active blog category is required before publishing.');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function publish(Blog $blog): Blog
    {
        $this->validateForPublish($blog);
        $blog->status = 1;
        $blog->save();

        return $blog;
    }

    public function unpublish(Blog $blog): Blog
    {
        $blog->status = 0;
        $blog->save();

        return $blog;
    }

    public function publicUrl(Blog $blog): ?string
    {
        return $blog->slug ? route('front.blog_details', $blog->slug) : null;
    }

    public function changedFields(Blog $blog, array $data): array
    {
        $changes = [];

        foreach ($data as $key => $value) {
            $old = $blog->getAttribute($key);

            if ((string) $old !== (string) $value) {
                $changes[$key] = [
                    'old' => $old,
                    'new' => $value,
                ];
            }
        }

        return $changes;
    }

    public function toApiArray(Blog $blog): array
    {
        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'blog_category_id' => $blog->blog_category_id,
            'description' => $blog->description,
            'status' => (int) $blog->status,
            'published' => (int) $blog->status === 1,
            'show_homepage' => (int) ($blog->show_homepage ?? 0),
            'seo_title' => $blog->seo_title,
            'seo_description' => $blog->seo_description,
            'seo_keywords' => $blog->seo_keywords,
            'seo_author' => $blog->seo_author,
            'seo_publisher' => $blog->seo_publisher,
            'canonical_url' => $blog->canonical_url,
            'meta_title' => $blog->meta_title,
            'meta_description' => $blog->meta_description,
            'meta_copyright' => $blog->meta_copyright,
            'site_name' => $blog->site_name,
            'image_url' => $blog->image ? asset($blog->image) : null,
            'meta_image_url' => $blog->meta_image ? asset($blog->meta_image) : null,
            'url' => $this->publicUrl($blog),
            'created_at' => optional($blog->created_at)->toIso8601String(),
            'updated_at' => optional($blog->updated_at)->toIso8601String(),
        ];
    }

    private function assignAndSave(Blog $blog, array $data): Blog
    {
        foreach ($data as $key => $value) {
            $blog->{$key} = $value;
        }

        if ($blog->status === null) {
            $blog->status = 0;
        }

        $blog->save();

        return $blog;
    }
}
