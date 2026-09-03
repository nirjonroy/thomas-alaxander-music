<?php

namespace App\Console\Commands;

use App\Models\PublishingIdempotencyKey;
use Illuminate\Console\Command;

class CleanupPublishingIdempotencyKeys extends Command
{
    protected $signature = 'publishing:idempotency-cleanup
        {--dry-run : Report expired records without deleting them}
        {--force : Delete expired records}';

    protected $description = 'Delete only expired publishing idempotency records.';

    public function handle(): int
    {
        $query = PublishingIdempotencyKey::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());

        $count = (clone $query)->count();

        if ($this->option('dry-run') || ! $this->option('force')) {
            $this->info("Dry run: {$count} expired idempotency record(s) would be deleted. Re-run with --force to delete them.");

            return self::SUCCESS;
        }

        if ($count === 0) {
            $this->info('No expired idempotency records found.');

            return self::SUCCESS;
        }

        $deleted = $query->delete();
        $this->info("Deleted {$deleted} expired idempotency record(s).");

        return self::SUCCESS;
    }
}
