<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Event;
use App\Models\PublishingMedia;
use App\Services\Publishing\PublishingBlogService;
use App\Services\Publishing\PublishingEventService;
use App\Services\Publishing\PublishingMediaService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class PublishingSmokeTestCommand extends Command
{
    protected $signature = 'publishing:smoke-test {--run-content-test : Create, publish, unpublish, and clean up temporary test content}';

    protected $description = 'Safely verify Publishing API configuration and optionally run a temporary content workflow.';

    private array $cleanupFiles = [];
    private array $cleanupCategoryIds = [];

    public function handle(
        PublishingBlogService $blogs,
        PublishingEventService $events,
        PublishingMediaService $mediaService
    ): int {
        $ok = $this->configurationChecks();

        if (! $this->option('run-content-test')) {
            $this->info($ok ? 'Smoke test configuration checks passed. No content was created.' : 'Smoke test found configuration issues.');

            return $ok ? self::SUCCESS : self::FAILURE;
        }

        if (! $ok) {
            $this->error('Content smoke test skipped because configuration checks failed.');

            return self::FAILURE;
        }

        try {
            $this->runBlogWorkflow($blogs, $mediaService);
            $this->runEventWorkflow($events, $mediaService);
        } finally {
            $this->cleanupFiles();
            $this->cleanupCategories();
        }

        $this->info('Publishing content smoke test completed and temporary API-owned files were cleaned up.');

        return self::SUCCESS;
    }

    private function configurationChecks(): bool
    {
        $ok = true;

        foreach (['blogs', 'events', 'publishing_media', 'publishing_media_attachments', 'publishing_idempotency_keys', 'publishing_audit_logs'] as $table) {
            if (! Schema::hasTable($table)) {
                $this->error("Missing required table: {$table}");
                $ok = false;
            }
        }

        foreach ([
            'api.v1.publishing.health',
            'api.v1.publishing.me',
            'api.v1.publishing.media.store',
            'api.v1.publishing.blogs.store',
            'api.v1.publishing.blogs.publish',
            'api.v1.publishing.events.store',
            'api.v1.publishing.events.publish',
        ] as $routeName) {
            if (! Route::has($routeName)) {
                $this->error("Missing required route: {$routeName}");
                $ok = false;
            }
        }

        $mediaDirectory = public_path(PublishingMediaService::CANONICAL_DIRECTORY);
        if (! is_dir($mediaDirectory)) {
            mkdir($mediaDirectory, 0755, true);
        }

        if (! is_writable($mediaDirectory)) {
            $this->error('Publishing media directory is not writable: ' . PublishingMediaService::CANONICAL_DIRECTORY);
            $ok = false;
        }

        if ($ok) {
            $this->info('Publishing configuration checks passed.');
        }

        return $ok;
    }

    private function runBlogWorkflow(PublishingBlogService $blogs, PublishingMediaService $mediaService): void
    {
        $timestamp = now()->format('YmdHis');
        $title = 'Publishing API Smoke Test Blog - ' . $timestamp;
        $media = $this->temporaryMedia($mediaService, 'blog_featured');
        $category = $this->blogCategory();

        $blog = $blogs->createDraft([
            'title' => $title,
            'blog_category_id' => $category->id,
            'description' => '<p>Temporary publishing smoke test blog.</p>',
            'image' => $mediaService->deriveBlogFeaturedImage($media),
        ]);

        $this->cleanupFiles[] = public_path($blog->image);
        $blogs->validateForPublish($blog);
        $blogs->publish($blog);
        $this->line('Blog public URL: ' . $blogs->publicUrl($blog));
        $blogs->unpublish($blog);
        $blog->delete();
        $mediaService->deleteCanonicalMedia($media);
        $media->delete();
    }

    private function runEventWorkflow(PublishingEventService $events, PublishingMediaService $mediaService): void
    {
        $timestamp = now()->format('YmdHis');
        $title = 'Publishing API Smoke Test Event - ' . $timestamp;
        $media = $this->temporaryMedia($mediaService, 'event_featured');

        $event = $events->createDraft([
            'name' => $title,
            'date' => now()->addDays(14)->format('Y-m-d'),
            'time' => '19:00',
            'location' => 'Publishing API Smoke Test Location',
            'description' => '<p>Temporary publishing smoke test event.</p>',
            'image' => $mediaService->deriveEventFeaturedImage($media),
        ]);

        $this->cleanupFiles[] = public_path('uploads/custom-images/' . $event->image);
        $this->cleanupFiles[] = public_path('uploads/custom-images2/' . $event->image);
        $events->validateForPublish($event);
        $events->publish($event);
        $this->line('Event public URL: ' . $events->publicUrl($event));
        $events->unpublish($event);
        $event->delete();
        $mediaService->deleteCanonicalMedia($media);
        $media->delete();
    }

    private function temporaryMedia(PublishingMediaService $mediaService, string $purpose): PublishingMedia
    {
        $result = $mediaService->storeCanonicalImage($this->uploadedJpeg('publishing-smoke-test.jpg'), [
            'purpose' => $purpose,
            'alt_text' => 'Publishing API smoke test image',
        ]);

        $media = $result['media'];
        $this->cleanupFiles[] = public_path($media->relative_path);

        return $media;
    }

    private function blogCategory(): BlogCategory
    {
        $category = BlogCategory::query()->where('status', 1)->first();

        if ($category) {
            return $category;
        }

        $category = new BlogCategory();
        $category->name = 'Publishing Smoke Test';
        $category->slug = 'publishing-smoke-test';
        $category->status = 1;
        $category->save();
        $this->cleanupCategoryIds[] = $category->id;

        return $category;
    }

    private function uploadedJpeg(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'publishing-smoke-');
        $image = imagecreatetruecolor(1, 1);
        $color = imagecolorallocate($image, 217, 164, 65);
        imagefilledrectangle($image, 0, 0, 0, 0, $color);
        imagejpeg($image, $path, 90);
        imagedestroy($image);
        $this->cleanupFiles[] = $path;

        return new UploadedFile($path, $name, 'image/jpeg', null, true);
    }

    private function cleanupFiles(): void
    {
        foreach (array_unique($this->cleanupFiles) as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private function cleanupCategories(): void
    {
        foreach (array_unique($this->cleanupCategoryIds) as $id) {
            BlogCategory::query()->where('id', $id)->delete();
        }
    }
}
