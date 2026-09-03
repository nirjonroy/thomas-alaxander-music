<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class PublishingApiResponse
{
    public static function success(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => (object) $data,
            'request_id' => self::requestId(),
        ], $status);
    }

    public static function error(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => (object) $errors,
            'request_id' => self::requestId(),
        ], $status);
    }

    public static function successPayload(string $message, array $data = []): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'request_id' => self::requestId(),
        ];
    }

    public static function errorPayload(string $message, array $errors = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => (object) $errors,
            'request_id' => self::requestId(),
        ];
    }

    public static function requestId(): ?string
    {
        $request = request();

        return $request?->attributes->get('publishing_request_id')
            ?: $request?->headers->get('X-Request-ID');
    }
}
