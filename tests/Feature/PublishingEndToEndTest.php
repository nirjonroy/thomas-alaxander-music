<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Event;
use App\Models\PublishingAuditLog;
use App\Models\PublishingMedia;
use App\Models\PublishingMediaAttachment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublishingEndToEndTest extends TestCase
{
    use DatabaseTransactions;

    private array $uploadedFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->uploadedFiles as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }

    public function test_complete_blog_and_event_workflow_is_draft_first_and_idempotent(): void
    {
        $token = $this->publishingToken();
        $requestId = 'publishing-e2e-' . Str::uuid();

        $this->withToken($token)
            ->withHeader('X-Request-ID', $requestId)
            ->getJson('/api/v1/publishing/me')
            ->assertOk()
            ->assertHeader('X-Request-ID', $requestId)
            ->assertJsonPath('request_id', $requestId);

        $blogMedia = $this->uploadMedia($token, 'e2e-blog-media', 'blog_featured');
        $eventMedia = $this->uploadMedia($token, 'e2e-event-media', 'event_featured');

        $category = $this->blogCategory();
        $blogPayload = [
            'title' => 'Publishing API End To End Blog ' . Str::random(6),
            'blog_category_id' => $category->id,
            'description' => '<p onclick="bad()">Ready <strong>blog</strong></p><script>alert(1)</script>',
            'featured_media_id' => $blogMedia->id,
            'seo_title' => 'Publishing E2E Blog',
            'seo_description' => 'Publishing API end to end blog test.',
        ];

        $blogCount = Blog::count();
        $this->withToken($token)
            ->postJson('/api/v1/publishing/preview/blog', $blogPayload)
            ->assertOk()
            ->assertJsonPath('data.publishable', true)
            ->assertJsonPath('data.proposed_media.featured.id', $blogMedia->id);
        $this->assertSame($blogCount, Blog::count());

        $firstBlogCreate = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'e2e-blog-create')
            ->postJson('/api/v1/publishing/blogs', $blogPayload)
            ->assertCreated()
            ->assertJsonPath('data.status', 0);

        $secondBlogCreate = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'e2e-blog-create')
            ->postJson('/api/v1/publishing/blogs', $blogPayload)
            ->assertCreated();

        $this->assertSame($firstBlogCreate->json('data.id'), $secondBlogCreate->json('data.id'));
        $blog = Blog::findOrFail($firstBlogCreate->json('data.id'));
        $this->uploadedFiles[] = public_path($blog->image);

        $this->withToken($token)
            ->getJson("/api/v1/publishing/blogs/{$blog->id}")
            ->assertOk()
            ->assertJsonPath('data.published', false);

        $this->get(route('front.blog_details', $blog->slug))->assertNotFound();

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'e2e-blog-publish')
            ->postJson("/api/v1/publishing/blogs/{$blog->id}/publish")
            ->assertOk()
            ->assertJsonPath('data.status', 1)
            ->assertJsonPath('data.url', route('front.blog_details', $blog->slug));

        $this->assertSame(1, (int) $blog->fresh()->status);
        $this->assertDatabaseHas('publishing_media_attachments', [
            'publishing_media_id' => $blogMedia->id,
            'attachable_type' => Blog::class,
            'attachable_id' => $blog->id,
            'role' => 'blog_featured',
        ]);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'e2e-blog-unpublish')
            ->postJson("/api/v1/publishing/blogs/{$blog->id}/unpublish")
            ->assertOk()
            ->assertJsonPath('data.status', 0);

        $eventPayload = [
            'name' => 'Publishing API End To End Event ' . Str::random(6),
            'date' => '2026-12-28',
            'time' => '19:00',
            'location' => 'Edmonton, Alberta',
            'description' => '<p>Ready event.</p>',
            'featured_media_id' => $eventMedia->id,
        ];

        $eventCount = Event::count();
        $this->withToken($token)
            ->postJson('/api/v1/publishing/preview/event', $eventPayload)
            ->assertOk()
            ->assertJsonPath('data.publishable', true)
            ->assertJsonPath('data.proposed_media.featured.id', $eventMedia->id);
        $this->assertSame($eventCount, Event::count());

        $eventCreate = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'e2e-event-create')
            ->postJson('/api/v1/publishing/events', $eventPayload)
            ->assertCreated()
            ->assertJsonPath('data.status', 0);

        $event = Event::findOrFail($eventCreate->json('data.id'));
        $this->trackEventImage($event->image);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'e2e-event-publish')
            ->postJson("/api/v1/publishing/events/{$event->id}/publish")
            ->assertOk()
            ->assertJsonPath('data.status', 1)
            ->assertJsonPath('data.url', route('front.events.show', $event->slug));

        $this->assertSame(1, (int) $event->fresh()->status);
        $this->assertDatabaseHas('publishing_media_attachments', [
            'publishing_media_id' => $eventMedia->id,
            'attachable_type' => Event::class,
            'attachable_id' => $event->id,
            'role' => 'event_featured',
        ]);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'e2e-event-unpublish')
            ->postJson("/api/v1/publishing/events/{$event->id}/unpublish")
            ->assertOk()
            ->assertJsonPath('data.status', 0);

        $this->assertGreaterThanOrEqual(2, PublishingMediaAttachment::count());
        $this->assertTrue(PublishingAuditLog::query()->where('action', 'blog.published')->where('resource_id', $blog->id)->exists());
        $this->assertTrue(PublishingAuditLog::query()->where('action', 'event.published')->where('resource_id', $event->id)->exists());
        $this->assertSame(1, Blog::where('title', $blogPayload['title'])->count());
        $this->assertSame(1, Event::where('name', $eventPayload['name'])->count());
    }

    private function uploadMedia(string $token, string $key, string $purpose): PublishingMedia
    {
        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', $key)
            ->post('/api/v1/publishing/media', [
                'file' => $this->uploadedJpeg($key . '.jpg'),
                'purpose' => $purpose,
                'alt_text' => 'Alt ' . $key,
            ], ['Accept' => 'application/json'])
            ->assertCreated();

        $media = PublishingMedia::findOrFail($response->json('data.id'));
        $this->uploadedFiles[] = public_path($media->relative_path);

        return $media;
    }

    private function publishingToken(): string
    {
        $admin = Admin::create([
            'name' => 'Publishing Automation',
            'email' => 'e2e-publishing-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(32)),
            'status' => 1,
            'admin_type' => 0,
        ]);

        return $admin->createToken('Publishing API E2E Token', [
            'publishing:blogs.read',
            'publishing:blogs.write',
            'publishing:blogs.publish',
            'publishing:events.read',
            'publishing:events.write',
            'publishing:events.publish',
            'publishing:media.upload',
        ])->plainTextToken;
    }

    private function blogCategory(): BlogCategory
    {
        $category = new BlogCategory();
        $category->name = 'Publishing E2E ' . Str::random(8);
        $category->slug = Str::slug($category->name);
        $category->status = 1;
        $category->save();

        return $category;
    }

    private function uploadedJpeg(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'publishing-e2e-');
        $image = imagecreatetruecolor(1, 1);
        $color = imagecolorallocate($image, 217, 164, 65);
        imagefilledrectangle($image, 0, 0, 0, 0, $color);
        imagejpeg($image, $path, 90);
        imagedestroy($image);
        $this->uploadedFiles[] = $path;

        return new UploadedFile($path, $name, 'image/jpeg', null, true);
    }

    private function trackEventImage(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $this->uploadedFiles[] = public_path('uploads/custom-images/' . $filename);
        $this->uploadedFiles[] = public_path('uploads/custom-images2/' . $filename);
    }
}
