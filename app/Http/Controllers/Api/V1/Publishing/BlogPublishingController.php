<?php

namespace App\Http\Controllers\Api\V1\Publishing;

use App\Exceptions\Publishing\IdempotencyKeyConflictException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Publishing\PublishBlogRequest;
use App\Http\Requests\Api\V1\Publishing\StoreBlogRequest;
use App\Http\Requests\Api\V1\Publishing\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\PublishingMedia;
use App\Services\Publishing\PublishingAuditLogger;
use App\Services\Publishing\PublishingBlogService;
use App\Services\Publishing\PublishingIdempotencyService;
use App\Services\Publishing\PublishingMediaService;
use App\Support\PublishingApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BlogPublishingController extends Controller
{
    private PublishingBlogService $blogs;
    private PublishingMediaService $media;
    private PublishingIdempotencyService $idempotency;
    private PublishingAuditLogger $audit;

    public function __construct(
        PublishingBlogService $blogs,
        PublishingMediaService $media,
        PublishingIdempotencyService $idempotency,
        PublishingAuditLogger $audit
    ) {
        $this->blogs = $blogs;
        $this->media = $media;
        $this->idempotency = $idempotency;
        $this->audit = $audit;
    }

    public function show(Blog $blog): JsonResponse
    {
        return PublishingApiResponse::success('Blog retrieved.', $this->blogs->toApiArray($blog));
    }

    public function store(StoreBlogRequest $request): JsonResponse
    {
        $idempotency = $this->beginIdempotency($request);

        if ($this->idempotency->isReplayable($idempotency)) {
            return response()->json($idempotency->response_body, $idempotency->response_status);
        }

        $blog = DB::transaction(function () use ($request) {
            $data = $this->validatedBlogData($request);
            $data = $this->storeUploadedImages($request, $data);
            $data['status'] = 0;

            $blog = $this->blogs->createDraft($data, (int) $request->user()->id);
            $this->syncMediaAttachments($request, $blog, $data);

            $this->audit->log($request, 'blog.created', [
                'resource_type' => Blog::class,
                'resource_id' => $blog->id,
                'changed_fields' => array_fill_keys(array_keys($data), ['old' => null, 'new' => 'set']),
            ]);

            return $blog;
        });

        $body = $this->successBody('Blog draft created.', $this->blogs->toApiArray($blog));
        $this->idempotency->complete($idempotency, 201, $body);

        return response()->json($body, 201);
    }

    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        $data = $this->validatedBlogData($request);
        $data = $this->storeUploadedImages($request, $data, $blog);
        unset($data['status']);

        $changes = $this->blogs->changedFields($blog, $data);
        $blog = $this->blogs->updateDraft($blog, $data);
        $this->syncMediaAttachments($request, $blog, $data);

        if (! empty($changes)) {
            $this->audit->log($request, 'blog.updated', [
                'resource_type' => Blog::class,
                'resource_id' => $blog->id,
                'changed_fields' => $changes,
            ]);
        }

        return PublishingApiResponse::success('Blog updated.', $this->blogs->toApiArray($blog));
    }

    public function publish(PublishBlogRequest $request, Blog $blog): JsonResponse
    {
        $idempotency = $this->beginIdempotency($request);

        if ($this->idempotency->isReplayable($idempotency)) {
            return response()->json($idempotency->response_body, $idempotency->response_status);
        }

        try {
            $blog = DB::transaction(function () use ($request, $blog) {
                $data = $this->validatedBlogData($request);
                $data = $this->storeUploadedImages($request, $data, $blog);
                unset($data['status']);

                if (! empty($data)) {
                    $blog = $this->blogs->updateDraft($blog, $data);
                    $this->syncMediaAttachments($request, $blog, $data);
                }

                $blog = $this->blogs->publish($blog);

                $this->audit->log($request, 'blog.published', [
                    'resource_type' => Blog::class,
                    'resource_id' => $blog->id,
                    'changed_fields' => ['status' => ['old' => 0, 'new' => 1]],
                ]);

                return $blog;
            });
        } catch (ValidationException $exception) {
            return PublishingApiResponse::error('Blog is not ready to publish.', $exception->errors(), 422);
        }

        $body = $this->successBody('Blog published.', $this->blogs->toApiArray($blog));
        $this->idempotency->complete($idempotency, 200, $body);

        return response()->json($body);
    }

    public function unpublish(Request $request, Blog $blog): JsonResponse
    {
        $idempotency = $this->beginIdempotency($request);

        if ($this->idempotency->isReplayable($idempotency)) {
            return response()->json($idempotency->response_body, $idempotency->response_status);
        }

        $oldStatus = (int) $blog->status;
        $blog = $this->blogs->unpublish($blog);

        $this->audit->log($request, 'blog.unpublished', [
            'resource_type' => Blog::class,
            'resource_id' => $blog->id,
            'changed_fields' => ['status' => ['old' => $oldStatus, 'new' => 0]],
        ]);

        $body = $this->successBody('Blog unpublished.', $this->blogs->toApiArray($blog));
        $this->idempotency->complete($idempotency, 200, $body);

        return response()->json($body);
    }

    public function preview(StoreBlogRequest $request): JsonResponse
    {
        $data = $this->validatedBlogData($request);
        unset($data['image'], $data['meta_image'], $data['status']);
        $proposedMedia = $this->proposedMedia($data);

        if (isset($proposedMedia['featured'])) {
            $data['image'] = 'pending-derived-media:' . $proposedMedia['featured']['id'];
        } elseif ($request->hasFile('image')) {
            $data['image'] = 'pending-upload:' . $request->file('image')->getClientOriginalName();
        }

        $normalized = $this->blogs->prepareDraftData($data);
        $blog = new Blog();

        foreach ($normalized as $field => $value) {
            $blog->{$field} = $value;
        }

        $publishErrors = [];
        try {
            $this->blogs->validateForPublish($blog);
        } catch (ValidationException $exception) {
            $publishErrors = $exception->errors();
        }

        return PublishingApiResponse::success('Blog preview validated.', [
            'valid' => true,
            'publishable' => empty($publishErrors),
            'slug' => $normalized['slug'] ?? null,
            'proposed_url' => $blog->slug ? route('front.blog_details', $blog->slug) : null,
            'normalized' => $normalized,
            'proposed_media' => $proposedMedia,
            'publish_errors' => $publishErrors,
        ]);
    }

    private function validatedBlogData(Request $request): array
    {
        $data = $request->validated();

        unset($data['id'], $data['admin_id'], $data['views'], $data['created_at'], $data['updated_at']);

        return $data;
    }

    private function storeUploadedImages(Request $request, array $data, ?Blog $blog = null): array
    {
        if (isset($data['featured_media_id']) && ! $request->hasFile('image')) {
            $media = $this->media->findUsableMedia((int) $data['featured_media_id'], 'blog_featured');
            $data['image'] = $this->media->deriveBlogFeaturedImage($media, $blog?->image);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->replaceImage($request->file('image'), 'uploads/custom-images', $blog?->image);
        }

        if (isset($data['meta_media_id']) && ! $request->hasFile('meta_image')) {
            $media = $this->media->findUsableMedia((int) $data['meta_media_id'], 'blog_meta');
            $data['meta_image'] = $this->media->deriveMetaImage($media, $blog?->meta_image);
        }

        if ($request->hasFile('meta_image')) {
            $data['meta_image'] = $this->replaceImage($request->file('meta_image'), 'uploads/website-images', $blog?->meta_image);
        }

        return $data;
    }

    private function replaceImage(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        $stored = $this->media->storeImage($file, $directory);
        $this->media->deleteApiManagedFile($oldPath);

        return $stored['path'];
    }

    private function beginIdempotency(Request $request)
    {
        $key = trim((string) $request->headers->get('Idempotency-Key'));

        if ($key === '') {
            throw ValidationException::withMessages(['Idempotency-Key' => 'The Idempotency-Key header is required for this operation.']);
        }

        try {
            return $this->idempotency->begin($request, $key, $this->idempotencyPayload($request));
        } catch (IdempotencyKeyConflictException $exception) {
            abort(PublishingApiResponse::error('The idempotency key was reused with a different request payload.', [
                'Idempotency-Key' => [$exception->getMessage()],
            ], 409));
        }
    }

    private function idempotencyPayload(Request $request): array
    {
        $payload = $request->except(['image', 'meta_image']);

        foreach (['image', 'meta_image'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $payload[$field] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'hash' => hash_file('sha256', $file->getRealPath()),
                ];
            }
        }

        return $payload;
    }

    private function syncMediaAttachments(Request $request, Blog $blog, array $data): void
    {
        if ($request->hasFile('image')) {
            $this->detachMediaRole($request, $blog, 'blog_featured');
        } elseif (isset($data['featured_media_id'])) {
            $this->attachMedia($request, $blog, (int) $data['featured_media_id'], 'blog_featured');
        }

        if ($request->hasFile('meta_image')) {
            $this->detachMediaRole($request, $blog, 'blog_meta');
        } elseif (isset($data['meta_media_id'])) {
            $this->attachMedia($request, $blog, (int) $data['meta_media_id'], 'blog_meta');
        }
    }

    private function attachMedia(Request $request, Blog $blog, int $mediaId, string $role): void
    {
        $media = $this->media->findUsableMedia($mediaId, $role);
        $result = $this->media->syncAttachment($blog, $media, $role);

        foreach ($result['detached'] as $detached) {
            $this->logDetached($request, $detached->publishing_media_id, $blog, $role);
        }

        $this->audit->log($request, 'media.attached', [
            'resource_type' => PublishingMedia::class,
            'resource_id' => $media->id,
            'context' => [
                'media_id' => $media->id,
                'uuid' => $media->uuid,
                'attachment_resource_type' => Blog::class,
                'attachment_resource_id' => $blog->id,
                'role' => $role,
            ],
        ]);
    }

    private function detachMediaRole(Request $request, Blog $blog, string $role): void
    {
        foreach ($this->media->detachRole($blog, $role) as $detached) {
            $this->logDetached($request, $detached->publishing_media_id, $blog, $role);
        }
    }

    private function logDetached(Request $request, int $mediaId, Blog $blog, string $role): void
    {
        $this->audit->log($request, 'media.detached', [
            'resource_type' => PublishingMedia::class,
            'resource_id' => $mediaId,
            'context' => [
                'media_id' => $mediaId,
                'attachment_resource_type' => Blog::class,
                'attachment_resource_id' => $blog->id,
                'role' => $role,
            ],
        ]);
    }

    private function proposedMedia(array $data): array
    {
        $proposed = [];

        if (isset($data['featured_media_id'])) {
            $media = $this->media->findUsableMedia((int) $data['featured_media_id'], 'blog_featured');
            $proposed['featured'] = $this->media->toApiArray($media);
        }

        if (isset($data['meta_media_id'])) {
            $media = $this->media->findUsableMedia((int) $data['meta_media_id'], 'blog_meta');
            $proposed['meta'] = $this->media->toApiArray($media);
        }

        return $proposed;
    }

    private function successBody(string $message, array $data): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'request_id' => PublishingApiResponse::requestId(),
        ];
    }
}
