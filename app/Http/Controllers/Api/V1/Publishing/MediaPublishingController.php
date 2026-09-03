<?php

namespace App\Http\Controllers\Api\V1\Publishing;

use App\Exceptions\Publishing\IdempotencyKeyConflictException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Publishing\StorePublishingMediaRequest;
use App\Models\PublishingMedia;
use App\Services\Publishing\PublishingAuditLogger;
use App\Services\Publishing\PublishingContext;
use App\Services\Publishing\PublishingIdempotencyService;
use App\Services\Publishing\PublishingMediaService;
use App\Support\PublishingApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MediaPublishingController extends Controller
{
    private PublishingMediaService $media;
    private PublishingIdempotencyService $idempotency;
    private PublishingAuditLogger $audit;

    public function __construct(
        PublishingMediaService $media,
        PublishingIdempotencyService $idempotency,
        PublishingAuditLogger $audit
    ) {
        $this->media = $media;
        $this->idempotency = $idempotency;
        $this->audit = $audit;
    }

    public function store(StorePublishingMediaRequest $request): JsonResponse
    {
        $this->media->validateCanonicalImage($request->file('file'));
        $idempotency = $this->beginIdempotency($request);

        if ($this->idempotency->isReplayable($idempotency)) {
            return response()->json($idempotency->response_body, $idempotency->response_status);
        }

        $result = DB::transaction(function () use ($request) {
            $context = PublishingContext::fromRequest($request);
            $result = $this->media->storeCanonicalImage(
                $request->file('file'),
                [
                    'purpose' => $request->validated('purpose') ?: 'general',
                    'alt_text' => $request->validated('alt_text'),
                    'caption' => $request->validated('caption'),
                ],
                $context->actorId,
                $context->tokenId
            );

            $media = $result['media'];

            $this->audit->log($request, 'media.uploaded', [
                'resource_type' => PublishingMedia::class,
                'resource_id' => $media->id,
                'context' => [
                    'media_id' => $media->id,
                    'uuid' => $media->uuid,
                    'purpose' => $media->purpose,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'duplicate_of_media_id' => $result['duplicate_of_media_id'],
                ],
            ]);

            return $result;
        });

        $body = $this->successBody('Media uploaded.', $this->media->toApiArray($result['media'], $result['duplicate_of_media_id']));
        $this->idempotency->complete($idempotency, 201, $body);

        return response()->json($body, 201);
    }

    public function show(PublishingMedia $media): JsonResponse
    {
        $media->load('attachments');

        return PublishingApiResponse::success('Media retrieved.', $this->media->toApiArray($media, null, true));
    }

    public function destroy(Request $request, PublishingMedia $media): JsonResponse
    {
        $media->loadCount('attachments');

        if ($media->attachments_count > 0) {
            return PublishingApiResponse::error('Media is currently attached to content and cannot be deleted.', [], 409);
        }

        DB::transaction(function () use ($request, $media) {
            $this->media->deleteCanonicalMedia($media);
            $media->delete();

            $this->audit->log($request, 'media.deleted', [
                'resource_type' => PublishingMedia::class,
                'resource_id' => $media->id,
                'context' => [
                    'media_id' => $media->id,
                    'uuid' => $media->uuid,
                    'purpose' => $media->purpose,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                ],
            ]);
        });

        return PublishingApiResponse::success('Media deleted.');
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
        $payload = $request->except(['file']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $payload['file'] = [
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'hash' => hash_file('sha256', $file->getRealPath()),
            ];
        }

        return $payload;
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
