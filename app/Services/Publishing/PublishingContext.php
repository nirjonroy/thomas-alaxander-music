<?php

namespace App\Services\Publishing;

use Illuminate\Http\Request;

class PublishingContext
{
    public ?string $actorType;
    public ?int $actorId;
    public ?int $tokenId;
    public ?string $tokenName;
    public array $tokenAbilities;
    public ?string $ipAddress;
    public ?string $userAgent;
    public ?string $requestId;

    public function __construct(
        ?string $actorType,
        ?int $actorId,
        ?int $tokenId,
        ?string $tokenName,
        array $tokenAbilities,
        ?string $ipAddress,
        ?string $userAgent,
        ?string $requestId = null
    ) {
        $this->actorType = $actorType;
        $this->actorId = $actorId;
        $this->tokenId = $tokenId;
        $this->tokenName = $tokenName;
        $this->tokenAbilities = $tokenAbilities;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->requestId = $requestId;
    }

    public static function fromRequest(Request $request): self
    {
        $user = $request->user();
        $token = $user && method_exists($user, 'currentAccessToken') ? $user->currentAccessToken() : null;

        return new self(
            $user ? class_basename($user) : null,
            $user ? (int) $user->getKey() : null,
            $token ? (int) $token->id : null,
            $token?->name,
            $token ? (array) $token->abilities : [],
            $request->ip(),
            $request->userAgent(),
            $request->attributes->get('publishing_request_id') ?: $request->headers->get('X-Request-ID'),
        );
    }

    public function toSafeArray(): array
    {
        return [
            'actor_type' => $this->actorType,
            'actor_id' => $this->actorId,
            'token_id' => $this->tokenId,
            'token_name' => $this->tokenName,
            'token_abilities' => $this->tokenAbilities,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'request_id' => $this->requestId,
        ];
    }
}
