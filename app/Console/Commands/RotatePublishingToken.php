<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class RotatePublishingToken extends Command
{
    protected $signature = 'publishing:token-rotate
        {token-id}
        {--token-name= : Replacement token name}
        {--expires-in-days=90 : Replacement token expiration in days}
        {--revoke-old : Revoke old token after creating replacement}
        {--force : Skip revoke confirmation when --revoke-old is set}';

    protected $description = 'Create a replacement publishing token with the same abilities and optionally revoke the old token.';

    public function handle(): int
    {
        $oldToken = PersonalAccessToken::with('tokenable')->find($this->argument('token-id'));

        if (! $oldToken || ! $oldToken->tokenable) {
            $this->error('Token not found.');

            return self::FAILURE;
        }

        $abilities = array_values(array_filter((array) $oldToken->abilities, fn ($ability) => str_starts_with((string) $ability, 'publishing:')));

        if ($abilities === []) {
            $this->error('The selected token does not have publishing API abilities.');

            return self::FAILURE;
        }

        $days = $this->option('expires-in-days');
        if (! ctype_digit((string) $days) || (int) $days < 1 || (int) $days > 3660) {
            $this->error('The --expires-in-days value must be a whole number between 1 and 3660.');

            return self::FAILURE;
        }

        $name = trim((string) ($this->option('token-name') ?: $oldToken->name . ' rotated ' . now()->format('Y-m-d')));
        $replacement = $oldToken->tokenable->createToken($name, $abilities, now()->addDays((int) $days));

        $this->info('Replacement publishing token created. Copy it now; it will not be shown again.');
        $this->line($replacement->plainTextToken);
        $this->newLine();
        $this->line('Granted abilities: ' . implode(', ', $abilities));
        $this->line('Expires at: ' . now()->addDays((int) $days)->toDateTimeString());
        $this->line('Update the automation platform secret store before revoking the old token.');

        if ($this->option('revoke-old')) {
            if ($this->option('force') || $this->confirm("Revoke old token {$oldToken->id} ({$oldToken->name}) now?")) {
                $oldToken->delete();
                $this->info('Old publishing token revoked.');
            } else {
                $this->warn('Old token was left active.');
            }
        }

        return self::SUCCESS;
    }
}
