<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class ListPublishingTokens extends Command
{
    protected $signature = 'publishing:token-list';

    protected $description = 'List publishing Sanctum tokens without displaying plaintext secrets.';

    public function handle(): int
    {
        $tokens = PersonalAccessToken::query()
            ->whereJsonContains('abilities', 'publishing:blogs.read')
            ->orWhereJsonContains('abilities', 'publishing:blogs.write')
            ->orWhereJsonContains('abilities', 'publishing:blogs.publish')
            ->orWhereJsonContains('abilities', 'publishing:events.read')
            ->orWhereJsonContains('abilities', 'publishing:events.write')
            ->orWhereJsonContains('abilities', 'publishing:events.publish')
            ->orWhereJsonContains('abilities', 'publishing:media.upload')
            ->with('tokenable')
            ->orderByDesc('id')
            ->get();

        $this->table([
            'Token ID',
            'Token Name',
            'Service Account',
            'Abilities',
            'Created',
            'Last Used',
            'Expiration',
            'Status',
        ], $tokens->map(function (PersonalAccessToken $token) {
            return [
                $token->id,
                $token->name,
                trim(($token->tokenable?->name ? $token->tokenable->name . ' ' : '') . '<' . ($token->tokenable?->email ?? 'unknown') . '>'),
                implode(', ', (array) $token->abilities),
                optional($token->created_at)->toDateTimeString() ?: '-',
                optional($token->last_used_at)->toDateTimeString() ?: '-',
                optional($token->expires_at)->toDateTimeString() ?: '-',
                $token->expires_at && $token->expires_at->isPast() ? 'expired' : 'active',
            ];
        })->all());

        if ($tokens->isEmpty()) {
            $this->info('No publishing tokens found.');
        }

        return self::SUCCESS;
    }
}
