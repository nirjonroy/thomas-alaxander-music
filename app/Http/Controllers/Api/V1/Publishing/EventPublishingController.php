<?php

namespace App\Http\Controllers\Api\V1\Publishing;

use App\Exceptions\Publishing\IdempotencyKeyConflictException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Publishing\PublishEventRequest;
use App\Http\Requests\Api\V1\Publishing\StoreEventRequest;
use App\Http\Requests\Api\V1\Publishing\UpdateEventRequest;
use App\Models\Event;
use App\Models\PublishingMedia;
use App\Services\Publishing\PublishingAuditLogger;
use App\Services\Publishing\PublishingEventService;
use App\Services\Publishing\PublishingIdempotencyService;
use App\Services\Publishing\PublishingMediaService;
use App\Support\PublishingApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventPublishingController extends Controller
{
    private PublishingEventService $events;
    private PublishingMediaService $media;
    private PublishingIdempotencyService $idempotency;
    private PublishingAuditLogger $audit;

    public function __construct(
        PublishingEventService $events,
        PublishingMediaService $media,
        PublishingIdempotencyService $idempotency,
        PublishingAuditLogger $audit
    ) {
        $this->events = $events;
        $this->media = $media;
        $this->idempotency = $idempotency;
        $this->audit = $audit;
    }

    public function show(Event $event): JsonResponse
    {
        return PublishingApiResponse::success('Event retrieved.', $this->events->toApiArray($event));
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $idempotency = $this->beginIdempotency($request);

        if ($this->idempotency->isReplayable($idempotency)) {
            return response()->json($idempotency->response_body, $idempotency->response_status);
        }

        $event = DB::transaction(function () use ($request) {
            $data = $this->validatedEventData($request);
            $data = $this->storeUploadedImages($request, $data);

            $event = $this->events->createDraft($data);
            $this->syncMediaAttachments($request, $event, $data);

            $this->audit->log($request, 'event.created', [
                'resource_type' => Event::class,
                'resource_id' => $event->id,
                'changed_fields' => array_fill_keys(array_keys($data), ['old' => null, 'new' => 'set']),
            ]);

            return $event;
        });

        $body = $this->successBody('Event draft created.', $this->events->toApiArray($event));
        $this->idempotency->complete($idempotency, 201, $body);

        return response()->json($body, 201);
    }

    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $data = $this->validatedEventData($request);
        $data = $this->storeUploadedImages($request, $data, $event);

        $changes = $this->events->changedFields($event, $data);
        $event = $this->events->updateDraft($event, $data);
        $this->syncMediaAttachments($request, $event, $data);

        if (! empty($changes)) {
            $this->audit->log($request, 'event.updated', [
                'resource_type' => Event::class,
                'resource_id' => $event->id,
                'changed_fields' => $changes,
            ]);
        }

        $payload = $this->events->toApiArray($event);
        if ($duplicate = $this->events->exactDuplicate($event)) {
            $payload['warnings'] = [
                'possible_duplicate' => 'An active Event already exists with the same name, date, time, and location.',
                'duplicate_event_id' => $duplicate->id,
            ];
        }

        return PublishingApiResponse::success('Event updated.', $payload);
    }

    public function publish(PublishEventRequest $request, Event $event): JsonResponse
    {
        $idempotency = $this->beginIdempotency($request);

        if ($this->idempotency->isReplayable($idempotency)) {
            return response()->json($idempotency->response_body, $idempotency->response_status);
        }

        try {
            $event = DB::transaction(function () use ($request, $event) {
                $data = $this->validatedEventData($request);
                $data = $this->storeUploadedImages($request, $data, $event);

                if (! empty($data)) {
                    $event = $this->events->updateDraft($event, $data);
                    $this->syncMediaAttachments($request, $event, $data);
                }

                $oldStatus = (int) $event->status;
                $event = $this->events->publish($event);

                $this->audit->log($request, 'event.published', [
                    'resource_type' => Event::class,
                    'resource_id' => $event->id,
                    'changed_fields' => ['status' => ['old' => $oldStatus, 'new' => 1]],
                ]);

                return $event;
            });
        } catch (ValidationException $exception) {
            return PublishingApiResponse::error('Event is not ready to publish.', $exception->errors(), 422);
        }

        $body = $this->successBody('Event published.', $this->events->toApiArray($event));
        $this->idempotency->complete($idempotency, 200, $body);

        return response()->json($body);
    }

    public function unpublish(Request $request, Event $event): JsonResponse
    {
        $idempotency = $this->beginIdempotency($request);

        if ($this->idempotency->isReplayable($idempotency)) {
            return response()->json($idempotency->response_body, $idempotency->response_status);
        }

        $oldStatus = (int) $event->status;
        $event = $this->events->unpublish($event);

        $this->audit->log($request, 'event.unpublished', [
            'resource_type' => Event::class,
            'resource_id' => $event->id,
            'changed_fields' => ['status' => ['old' => $oldStatus, 'new' => 0]],
        ]);

        $body = $this->successBody('Event unpublished.', $this->events->toApiArray($event));
        $this->idempotency->complete($idempotency, 200, $body);

        return response()->json($body);
    }

    public function preview(StoreEventRequest $request): JsonResponse
    {
        $data = $this->validatedEventData($request);
        unset($data['image'], $data['meta_image']);
        $proposedMedia = $this->proposedMedia($data);

        $normalized = $this->events->prepareDraftData($data);
        $event = new Event();

        foreach ($normalized as $field => $value) {
            $event->{$field} = $value;
        }

        $publishErrors = [];
        try {
            $this->events->validateForPublish($event);
        } catch (ValidationException $exception) {
            $publishErrors = $exception->errors();
        }

        return PublishingApiResponse::success('Event preview validated.', [
            'valid' => true,
            'publishable' => empty($publishErrors),
            'slug' => $normalized['slug'] ?? null,
            'proposed_url' => $event->slug ? $this->events->publicUrl($event) : null,
            'normalized' => $normalized,
            'proposed_media' => $proposedMedia,
            'publish_errors' => $publishErrors,
        ]);
    }

    private function validatedEventData(Request $request): array
    {
        $data = $request->validated();

        unset($data['id'], $data['status'], $data['created_at'], $data['updated_at']);
        unset($data['start_date'], $data['end_date'], $data['date_range']);

        return $data;
    }

    private function storeUploadedImages(Request $request, array $data, ?Event $event = null): array
    {
        if (isset($data['featured_media_id']) && ! $request->hasFile('image')) {
            $media = $this->media->findUsableMedia((int) $data['featured_media_id'], 'event_featured');
            $data['image'] = $this->media->deriveEventFeaturedImage($media, $event?->image);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->replaceEventImage($request->file('image'), $event?->image);
        }

        if (isset($data['meta_media_id']) && ! $request->hasFile('meta_image')) {
            $media = $this->media->findUsableMedia((int) $data['meta_media_id'], 'event_meta');
            $data['meta_image'] = $this->media->deriveMetaImage($media, $event?->meta_image);
        }

        if ($request->hasFile('meta_image')) {
            $data['meta_image'] = $this->replaceImage($request->file('meta_image'), 'uploads/website-images', $event?->meta_image);
        }

        return $data;
    }

    private function replaceEventImage(UploadedFile $file, ?string $oldFilename = null): string
    {
        $stored = $this->media->storeEventImage($file);
        $this->media->deleteApiManagedEventImages($oldFilename);

        return $stored['filename'];
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

    private function syncMediaAttachments(Request $request, Event $event, array $data): void
    {
        if ($request->hasFile('image')) {
            $this->detachMediaRole($request, $event, 'event_featured');
        } elseif (isset($data['featured_media_id'])) {
            $this->attachMedia($request, $event, (int) $data['featured_media_id'], 'event_featured');
        }

        if ($request->hasFile('meta_image')) {
            $this->detachMediaRole($request, $event, 'event_meta');
        } elseif (isset($data['meta_media_id'])) {
            $this->attachMedia($request, $event, (int) $data['meta_media_id'], 'event_meta');
        }
    }

    private function attachMedia(Request $request, Event $event, int $mediaId, string $role): void
    {
        $media = $this->media->findUsableMedia($mediaId, $role);
        $result = $this->media->syncAttachment($event, $media, $role);

        foreach ($result['detached'] as $detached) {
            $this->logDetached($request, $detached->publishing_media_id, $event, $role);
        }

        $this->audit->log($request, 'media.attached', [
            'resource_type' => PublishingMedia::class,
            'resource_id' => $media->id,
            'context' => [
                'media_id' => $media->id,
                'uuid' => $media->uuid,
                'attachment_resource_type' => Event::class,
                'attachment_resource_id' => $event->id,
                'role' => $role,
            ],
        ]);
    }

    private function detachMediaRole(Request $request, Event $event, string $role): void
    {
        foreach ($this->media->detachRole($event, $role) as $detached) {
            $this->logDetached($request, $detached->publishing_media_id, $event, $role);
        }
    }

    private function logDetached(Request $request, int $mediaId, Event $event, string $role): void
    {
        $this->audit->log($request, 'media.detached', [
            'resource_type' => PublishingMedia::class,
            'resource_id' => $mediaId,
            'context' => [
                'media_id' => $mediaId,
                'attachment_resource_type' => Event::class,
                'attachment_resource_id' => $event->id,
                'role' => $role,
            ],
        ]);
    }

    private function proposedMedia(array $data): array
    {
        $proposed = [];

        if (isset($data['featured_media_id'])) {
            $media = $this->media->findUsableMedia((int) $data['featured_media_id'], 'event_featured');
            $proposed['featured'] = $this->media->toApiArray($media);
        }

        if (isset($data['meta_media_id'])) {
            $media = $this->media->findUsableMedia((int) $data['meta_media_id'], 'event_meta');
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
