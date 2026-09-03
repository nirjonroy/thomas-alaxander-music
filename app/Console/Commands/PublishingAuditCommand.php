<?php

namespace App\Console\Commands;

use App\Models\PublishingAuditLog;
use Illuminate\Console\Command;

class PublishingAuditCommand extends Command
{
    protected $signature = 'publishing:audit
        {--type= : Filter by resource type: blog, event, media}
        {--action= : Filter by exact audit action}
        {--limit=50 : Maximum rows to display}';

    protected $description = 'Review safe publishing audit log entries.';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        if ($limit < 1 || $limit > 500) {
            $this->error('The --limit value must be between 1 and 500.');

            return self::FAILURE;
        }

        $query = PublishingAuditLog::query()->latest('created_at')->latest('id');

        if ($action = $this->option('action')) {
            $query->where('action', $action);
        }

        if ($type = $this->option('type')) {
            $map = [
                'blog' => 'App\\Models\\Blog',
                'event' => 'App\\Models\\Event',
                'media' => 'App\\Models\\PublishingMedia',
            ];

            if (! isset($map[$type])) {
                $this->error('Unsupported --type. Use blog, event, or media.');

                return self::FAILURE;
            }

            $query->where('resource_type', $map[$type]);
        }

        $logs = $query->limit($limit)->get();

        $this->table([
            'Timestamp',
            'Actor',
            'Action',
            'Resource',
            'Request ID',
            'Context',
        ], $logs->map(function (PublishingAuditLog $log) {
            $context = $this->safeJson($log->context ?? []);

            return [
                optional($log->created_at)->toDateTimeString() ?: '-',
                $log->admin_id ? 'admin:' . $log->admin_id : '-',
                $log->action,
                ($log->resource_type ? class_basename($log->resource_type) : '-') . ':' . ($log->resource_id ?: '-'),
                $log->request_id ?: '-',
                $context,
            ];
        })->all());

        if ($logs->isEmpty()) {
            $this->info('No publishing audit entries found.');
        }

        return self::SUCCESS;
    }

    private function safeJson(array $context): string
    {
        foreach (['authorization', 'token', 'secret', 'password', 'bearer'] as $blocked) {
            unset($context[$blocked]);
        }

        return json_encode($context, JSON_UNESCAPED_SLASHES) ?: '{}';
    }
}
