<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Event;
use App\Models\PublishingMedia;
use App\Services\Publishing\PublishingMediaService;
use Illuminate\Console\Command;

class PublishingMediaOrphansCommand extends Command
{
    protected $signature = 'publishing:media-orphans {--cleanup : Remove provably safe API-owned orphan canonical media after confirmation} {--force : Skip cleanup confirmation}';

    protected $description = 'Report publishing media records and files that are unattached or missing.';

    public function handle(PublishingMediaService $mediaService): int
    {
        $unattached = PublishingMedia::query()->doesntHave('attachments')->orderBy('id')->get();
        $missingCanonical = PublishingMedia::query()->orderBy('id')->get()->filter(
            fn (PublishingMedia $media) => ! is_file(public_path($media->relative_path))
        );
        $orphanDerived = $this->orphanDerivedFiles($mediaService);

        $this->line('Publishing media orphan report');
        $this->line('Unattached canonical media records: ' . $unattached->count());
        $this->line('Media records pointing to missing canonical files: ' . $missingCanonical->count());
        $this->line('API-owned derived files not referenced by Blog/Event records: ' . count($orphanDerived));

        if ($unattached->isNotEmpty()) {
            $this->table(['ID', 'UUID', 'Path', 'Created'], $unattached->map(fn (PublishingMedia $media) => [
                $media->id,
                $media->uuid,
                $media->relative_path,
                optional($media->created_at)->toDateTimeString(),
            ])->all());
        }

        if ($missingCanonical->isNotEmpty()) {
            $this->warn('Missing canonical files:');
            foreach ($missingCanonical as $media) {
                $this->line(" - media {$media->id}: {$media->relative_path}");
            }
        }

        if ($orphanDerived) {
            $this->warn('Unreferenced API-owned derived files:');
            foreach ($orphanDerived as $file) {
                $this->line(' - ' . $file);
            }
        }

        if (! $this->option('cleanup')) {
            $this->info('Report only. No files or records were deleted.');

            return self::SUCCESS;
        }

        if ($unattached->isEmpty()) {
            $this->info('No unattached canonical media records to clean up.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Delete unattached canonical API-owned media files and their records?')) {
            $this->warn('Cleanup cancelled.');

            return self::SUCCESS;
        }

        $deleted = 0;
        foreach ($unattached as $media) {
            $mediaService->deleteCanonicalMedia($media);
            $media->delete();
            $deleted++;
        }

        $this->info("Deleted {$deleted} unattached canonical media record(s).");

        return self::SUCCESS;
    }

    private function orphanDerivedFiles(PublishingMediaService $mediaService): array
    {
        $referenced = [];

        foreach (Blog::query()->whereNotNull('image')->pluck('image')->all() as $path) {
            $referenced[] = trim((string) $path, '/');
        }

        foreach (Blog::query()->whereNotNull('meta_image')->pluck('meta_image')->all() as $path) {
            $referenced[] = trim((string) $path, '/');
        }

        foreach (Event::query()->whereNotNull('image')->pluck('image')->all() as $filename) {
            foreach (['uploads/custom-images', 'uploads/custom-images2', 'uploads/main-image'] as $dir) {
                $referenced[] = $dir . '/' . ltrim((string) $filename, '/');
            }
        }

        foreach (Event::query()->whereNotNull('meta_image')->pluck('meta_image')->all() as $path) {
            $referenced[] = trim((string) $path, '/');
        }

        $referenced = array_unique($referenced);
        $orphans = [];

        foreach (['uploads/custom-images', 'uploads/custom-images2', 'uploads/website-images'] as $dir) {
            foreach (glob(public_path($dir . '/publishing-*')) ?: [] as $absolutePath) {
                $relativePath = str_replace('\\', '/', str_replace(public_path() . DIRECTORY_SEPARATOR, '', $absolutePath));

                if ($mediaService->isApiManagedPath($relativePath) && ! in_array($relativePath, $referenced, true)) {
                    $orphans[] = $relativePath;
                }
            }
        }

        sort($orphans);

        return $orphans;
    }
}
