<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CreatePublishingToken extends Command
{
    protected $signature = 'publishing:token
        {--email= : Email address for the dedicated publishing Admin identity}
        {--name=Publishing Automation : Name for the publishing Admin identity}
        {--token-name=Publishing API Token : Sanctum token name}
        {--abilities=* : Token abilities to grant. Repeat the option for multiple abilities}
        {--preset= : Ability preset. Supported: full-publishing}
        {--expires-in-days= : Optional token expiration in days. Recommended production value: 90}
        {--create-admin : Create the Admin identity if it does not exist}';

    protected $description = 'Create a scoped Sanctum token for the Publishing API without storing the plaintext token.';

    private const ALLOWED_ABILITIES = [
        'publishing:blogs.read',
        'publishing:blogs.write',
        'publishing:blogs.publish',
        'publishing:events.read',
        'publishing:events.write',
        'publishing:events.publish',
        'publishing:media.upload',
    ];

    public const FULL_PUBLISHING_ABILITIES = [
        'publishing:blogs.read',
        'publishing:blogs.write',
        'publishing:blogs.publish',
        'publishing:events.read',
        'publishing:events.write',
        'publishing:events.publish',
        'publishing:media.upload',
    ];

    public function handle(): int
    {
        $email = (string) ($this->option('email') ?: $this->ask('Publishing Admin email'));
        $name = (string) $this->option('name');
        $tokenName = (string) $this->option('token-name');
        $abilities = $this->resolveAbilities();
        $expiresAt = $this->resolveExpiration();

        if ($expiresAt === false) {
            return self::FAILURE;
        }

        try {
            Validator::make([
                'email' => $email,
                'name' => $name,
                'token_name' => $tokenName,
                'abilities' => $abilities,
            ], [
                'email' => ['required', 'email'],
                'name' => ['required', 'string', 'max:255'],
                'token_name' => ['required', 'string', 'max:255'],
                'abilities' => ['required', 'array', 'min:1'],
                'abilities.*' => ['required', Rule::in(self::ALLOWED_ABILITIES)],
            ])->validate();
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $messages) {
                foreach ($messages as $message) {
                    $this->error($message);
                }
            }

            return self::FAILURE;
        }

        $admin = Admin::where('email', $email)->first();

        if (!$admin && !$this->option('create-admin')) {
            $this->error('No Admin identity exists for that email. Re-run with --create-admin if this should be the dedicated service identity.');

            return self::FAILURE;
        }

        if (!$admin) {
            $admin = Admin::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(48)),
                'status' => 1,
                'admin_type' => 0,
            ]);
        }

        $token = $admin->createToken($tokenName, $abilities, $expiresAt);

        $this->info('Publishing token created. Copy it now; it will not be shown again.');
        $this->line($token->plainTextToken);
        $this->newLine();
        $this->line('Granted abilities: '.implode(', ', $abilities));
        $this->line('Expires at: '.($expiresAt ? $expiresAt->toDateTimeString() : 'No explicit expiration set. Recommended production value: 90 days.'));

        return self::SUCCESS;
    }

    private function resolveAbilities(): array
    {
        $preset = trim((string) $this->option('preset'));

        if ($preset !== '') {
            if ($preset !== 'full-publishing') {
                $this->error('Unsupported preset. Supported preset: full-publishing.');

                return [];
            }

            return self::FULL_PUBLISHING_ABILITIES;
        }

        return $this->normalizeAbilities((array) $this->option('abilities'));
    }

    private function resolveExpiration(): Carbon|false|null
    {
        $days = $this->option('expires-in-days');

        if ($days === null || $days === '') {
            return null;
        }

        if (! ctype_digit((string) $days) || (int) $days < 1 || (int) $days > 3660) {
            $this->error('The --expires-in-days value must be a whole number between 1 and 3660.');

            return false;
        }

        return now()->addDays((int) $days);
    }

    private function normalizeAbilities(array $abilities): array
    {
        $abilities = array_values(array_filter(array_map('trim', $abilities)));

        if ($abilities === []) {
            return [
                'publishing:blogs.read',
                'publishing:events.read',
            ];
        }

        return array_values(array_unique($abilities));
    }
}
