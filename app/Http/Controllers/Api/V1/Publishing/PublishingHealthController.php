<?php

namespace App\Http\Controllers\Api\V1\Publishing;

use App\Http\Controllers\Controller;
use App\Support\PublishingApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublishingHealthController extends Controller
{
    public function health(): JsonResponse
    {
        return PublishingApiResponse::success('Publishing API is available.', [
            'service' => 'publishing-api',
            'version' => 'v1',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $identity = $request->user();
        $token = $identity?->currentAccessToken();

        return PublishingApiResponse::success('Authenticated publishing identity.', [
            'identity' => [
                'id' => $identity?->id,
                'type' => $identity ? class_basename($identity) : null,
                'name' => $identity?->name,
                'email' => $identity?->email,
            ],
            'token' => [
                'name' => $token?->name,
                'abilities' => $token?->abilities ?? [],
            ],
        ]);
    }
}
