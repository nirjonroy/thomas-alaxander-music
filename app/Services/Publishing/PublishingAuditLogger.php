<?php

namespace App\Services\Publishing;

use App\Models\PublishingAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PublishingAuditLogger
{
    public function log(Request $request, string $action, array $data = []): PublishingAuditLog
    {
        $context = PublishingContext::fromRequest($request);

        return PublishingAuditLog::create([
            'admin_id' => $context->actorId,
            'token_id' => $context->tokenId,
            'action' => $action,
            'resource_type' => $data['resource_type'] ?? null,
            'resource_id' => $data['resource_id'] ?? null,
            'ip_address' => $context->ipAddress,
            'user_agent' => $context->userAgent,
            'request_id' => $request->attributes->get('publishing_request_id') ?: $request->headers->get('X-Request-ID'),
            'idempotency_key' => $request->headers->get('Idempotency-Key'),
            'changed_fields' => $this->scrub(Arr::wrap($data['changed_fields'] ?? [])),
            'context' => $this->scrub($data['context'] ?? $context->toSafeArray()),
            'created_at' => now(),
        ]);
    }

    private function scrub(array $data): array
    {
        $blocked = ['authorization', 'bearer', 'password', 'token', 'secret', 'credential', 'binary', 'file'];

        foreach ($data as $key => $value) {
            $normalized = strtolower((string) $key);

            foreach ($blocked as $blockedKey) {
                if (str_contains($normalized, $blockedKey)) {
                    unset($data[$key]);
                    continue 2;
                }
            }

            if (is_array($value)) {
                $data[$key] = $this->scrub($value);
            }
        }

        return $data;
    }
}
