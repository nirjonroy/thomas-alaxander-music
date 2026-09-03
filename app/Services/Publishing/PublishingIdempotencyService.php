<?php

namespace App\Services\Publishing;

use App\Exceptions\Publishing\IdempotencyKeyConflictException;
use App\Models\PublishingIdempotencyKey;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublishingIdempotencyService
{
    public function begin(Request $request, string $key, array $payload = [], int $ttlMinutes = 1440): PublishingIdempotencyKey
    {
        $context = PublishingContext::fromRequest($request);
        $hash = $this->hashPayload($payload);

        return DB::transaction(function () use ($request, $key, $payload, $ttlMinutes, $context, $hash) {
            $existing = PublishingIdempotencyKey::query()
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            if ($existing && $existing->expires_at && $existing->expires_at->isPast()) {
                $existing->delete();
                $existing = null;
            }

            if ($existing) {
                if (! hash_equals($existing->request_hash, $hash)) {
                    throw new IdempotencyKeyConflictException('The idempotency key was reused with a different request payload.');
                }

                return $existing;
            }

            try {
                return PublishingIdempotencyKey::create([
                    'key' => $key,
                    'actor_type' => $context->actorType,
                    'actor_id' => $context->actorId,
                    'token_id' => $context->tokenId,
                    'method' => strtoupper($request->method()),
                    'path' => '/' . ltrim($request->path(), '/'),
                    'request_hash' => $hash,
                    'expires_at' => now()->addMinutes($ttlMinutes),
                ]);
            } catch (QueryException $exception) {
                $record = PublishingIdempotencyKey::query()->where('key', $key)->first();

                if ($record && hash_equals($record->request_hash, $hash)) {
                    return $record;
                }

                throw $exception;
            }
        });
    }

    public function complete(PublishingIdempotencyKey $record, int $status, array $body): PublishingIdempotencyKey
    {
        $record->forceFill([
            'response_status' => $status,
            'response_body' => $body,
        ])->save();

        return $record->refresh();
    }

    public function isReplayable(PublishingIdempotencyKey $record): bool
    {
        return $record->response_status !== null && $record->response_body !== null;
    }

    public function hashPayload(array $payload): string
    {
        return hash('sha256', json_encode($this->canonicalize($payload), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    private function canonicalize(array $payload): array
    {
        ksort($payload);

        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->canonicalize($value);
            }
        }

        return $payload;
    }
}
