<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class RevokePublishingToken extends Command
{
    protected $signature = 'publishing:token-revoke {token-id} {--force : Revoke without interactive confirmation}';

    protected $description = 'Revoke a publishing Sanctum token by deleting the stored token hash.';

    public function handle(): int
    {
        $token = PersonalAccessToken::find($this->argument('token-id'));

        if (! $token) {
            $this->error('Token not found.');

            return self::FAILURE;
        }

        if (! $this->isPublishingToken($token)) {
            $this->error('The selected token does not have publishing API abilities.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm("Revoke token {$token->id} ({$token->name})?")) {
            $this->warn('Token was not revoked.');

            return self::SUCCESS;
        }

        $token->delete();
        $this->info('Publishing token revoked.');

        return self::SUCCESS;
    }

    private function isPublishingToken(PersonalAccessToken $token): bool
    {
        foreach ((array) $token->abilities as $ability) {
            if (str_starts_with((string) $ability, 'publishing:')) {
                return true;
            }
        }

        return false;
    }
}
