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

class PublishingMediaApiTest extends TestCase
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

    public function test_media_upload_requires_authentication_and_upload_ability(): void
    {
        $this->post('/api/v1/publishing/media', [
            'file' => $this->uploadedJpeg('cover.jpg'),
        ], ['Accept' => 'application/json'])->assertStatus(401);

        [, $wrongToken] = $this->publishingToken(['publishing:blogs.write']);

        $this->withToken($wrongToken)
            ->withHeader('Idempotency-Key', 'media-wrong-ability')
            ->post('/api/v1/publishing/media', [
                'file' => $this->uploadedJpeg('cover.jpg'),
            ], ['Accept' => 'application/json'])
            ->assertStatus(403);
    }

    public function test_jpeg_upload_creates_safe_canonical_media_record(): void
    {
        [, $token] = $this->publishingToken(['publishing:media.upload']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-jpeg-upload')
            ->post('/api/v1/publishing/media', [
                'file' => $this->uploadedJpeg('portrait.jpg'),
                'purpose' => 'blog_featured',
                'alt_text' => 'Thomas Alexander portrait',
                'caption' => 'Promotional portrait',
            ], ['Accept' => 'application/json']);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Media uploaded.')
            ->assertJsonPath('data.media_type', 'image')
            ->assertJsonPath('data.purpose', 'blog_featured')
            ->assertJsonPath('data.mime_type', 'image/jpeg')
            ->assertJsonPath('data.extension', 'jpg')
            ->assertJsonPath('data.width', 1)
            ->assertJsonPath('data.height', 1)
            ->assertJsonPath('data.alt_text', 'Thomas Alexander portrait')
            ->assertJsonPath('data.duplicate_of_media_id', null);

        $media = PublishingMedia::findOrFail($response->json('data.id'));
        $this->trackMedia($media);

        $this->assertStringStartsWith('publishing-', $media->stored_name);
        $this->assertStringStartsWith('uploads/publishing-media/publishing-', $media->relative_path);
        $this->assertFileExists(public_path($media->relative_path));
        $this->assertSame(64, strlen($media->checksum));
        $response->assertDontSee(public_path(), false);
        $response->assertDontSee('Authorization', false);

        $this->assertDatabaseHas('publishing_audit_logs', [
            'action' => 'media.uploaded',
            'resource_id' => $media->id,
        ]);
    }

    public function test_png_and_webp_uploads_are_supported_when_image_driver_supports_them(): void
    {
        [, $token] = $this->publishingToken(['publishing:media.upload']);

        foreach (['png' => 'image/png', 'webp' => 'image/webp'] as $extension => $mime) {
            $file = $extension === 'png'
                ? $this->uploadedPng('asset.png')
                : $this->uploadedWebp('asset.webp');

            if (! @getimagesize($file->getRealPath())) {
                $this->addToAssertionCount(1);
                continue;
            }

            $response = $this->withToken($token)
                ->withHeader('Idempotency-Key', 'media-' . $extension . '-upload')
                ->post('/api/v1/publishing/media', [
                    'file' => $file,
                    'purpose' => 'general',
                ], ['Accept' => 'application/json']);

            $response->assertCreated()
                ->assertJsonPath('data.mime_type', $mime)
                ->assertJsonPath('data.extension', $extension);

            $this->trackMedia(PublishingMedia::findOrFail($response->json('data.id')));
        }
    }

    public function test_invalid_media_uploads_are_rejected(): void
    {
        [, $token] = $this->publishingToken(['publishing:media.upload']);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-missing-file')
            ->postJson('/api/v1/publishing/media', ['purpose' => 'general'])
            ->assertStatus(422);

        foreach ([
            'php' => UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
            'svg' => UploadedFile::fake()->create('vector.svg', 1, 'image/svg+xml'),
            'corrupt' => UploadedFile::fake()->createWithContent('broken.jpg', 'not an image'),
            'mismatch' => $this->mismatchedPngNameWithJpegContents(),
        ] as $key => $file) {
            $this->withToken($token)
                ->withHeader('Idempotency-Key', 'media-invalid-' . $key)
                ->post('/api/v1/publishing/media', [
                    'file' => $file,
                    'purpose' => 'general',
                ], ['Accept' => 'application/json'])
                ->assertStatus(422);
        }

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-oversized')
            ->post('/api/v1/publishing/media', [
                'file' => UploadedFile::fake()->create('huge.jpg', 10241, 'image/jpeg'),
                'purpose' => 'general',
            ], ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function test_media_upload_idempotency_and_duplicate_reporting(): void
    {
        [, $token] = $this->publishingToken(['publishing:media.upload']);
        $firstFile = $this->uploadedJpeg('duplicate.jpg');
        $firstPath = $firstFile->getRealPath();

        $first = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-idempotent')
            ->post('/api/v1/publishing/media', [
                'file' => $firstFile,
                'purpose' => 'general',
                'alt_text' => 'Duplicate image',
            ], ['Accept' => 'application/json']);

        $first->assertCreated();
        $media = PublishingMedia::findOrFail($first->json('data.id'));
        $this->trackMedia($media);

        $same = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-idempotent')
            ->post('/api/v1/publishing/media', [
                'file' => $this->fileFromPath($firstPath, 'duplicate.jpg'),
                'purpose' => 'general',
                'alt_text' => 'Duplicate image',
            ], ['Accept' => 'application/json']);

        $same->assertCreated()
            ->assertJsonPath('data.id', $media->id);

        $this->assertSame(1, PublishingMedia::where('checksum', $media->checksum)->count());
        $fileCountAfterReplay = count(glob(public_path('uploads/publishing-media/publishing-*')) ?: []);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-idempotent')
            ->post('/api/v1/publishing/media', [
                'file' => $this->uploadedPng('different.png'),
                'purpose' => 'general',
                'alt_text' => 'Different image',
            ], ['Accept' => 'application/json'])
            ->assertStatus(409);

        $this->assertSame($fileCountAfterReplay, count(glob(public_path('uploads/publishing-media/publishing-*')) ?: []));

        $duplicate = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-duplicate-new-key')
            ->post('/api/v1/publishing/media', [
                'file' => $this->fileFromPath($firstPath, 'duplicate.jpg'),
                'purpose' => 'general',
                'alt_text' => 'Duplicate image',
            ], ['Accept' => 'application/json']);

        $duplicate->assertCreated()
            ->assertJsonPath('data.duplicate_of_media_id', $media->id);

        $this->trackMedia(PublishingMedia::findOrFail($duplicate->json('data.id')));
    }

    public function test_media_show_returns_safe_metadata_and_delete_removes_unattached_media(): void
    {
        [, $token] = $this->publishingToken(['publishing:media.upload']);
        $media = $this->uploadMedia($token, 'media-show-delete', 'general');

        $this->withToken($token)
            ->getJson('/api/v1/publishing/media/' . $media->uuid)
            ->assertOk()
            ->assertJsonPath('data.id', $media->id)
            ->assertJsonPath('data.uuid', $media->uuid)
            ->assertJsonPath('data.attachments', [])
            ->assertDontSee(public_path(), false);

        $path = public_path($media->relative_path);

        $this->withToken($token)
            ->deleteJson('/api/v1/publishing/media/' . $media->uuid)
            ->assertOk()
            ->assertJsonPath('message', 'Media deleted.');

        $this->assertFileDoesNotExist($path);
        $this->assertDatabaseMissing('publishing_media', ['id' => $media->id]);
        $this->assertDatabaseHas('publishing_audit_logs', [
            'action' => 'media.deleted',
            'resource_id' => $media->id,
        ]);
    }

    public function test_blog_can_use_media_id_and_preview_does_not_attach(): void
    {
        [, $token] = $this->publishingToken([
            'publishing:media.upload',
            'publishing:blogs.write',
            'publishing:blogs.publish',
        ]);
        $media = $this->uploadMedia($token, 'blog-media', 'blog_featured');
        $category = $this->blogCategory();

        $this->withToken($token)
            ->postJson('/api/v1/publishing/preview/blog', [
                'title' => 'Preview Blog Media',
                'blog_category_id' => $category->id,
                'featured_media_id' => $media->id,
            ])
            ->assertOk()
            ->assertJsonPath('data.proposed_media.featured.id', $media->id);

        $this->assertSame(0, PublishingMediaAttachment::count());

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'blog-create-from-media')
            ->postJson('/api/v1/publishing/blogs', [
                'title' => 'Blog From Media',
                'blog_category_id' => $category->id,
                'description' => '<p>Ready</p>',
                'featured_media_id' => $media->id,
            ]);

        $response->assertCreated();
        $blog = Blog::findOrFail($response->json('data.id'));
        $this->uploadedFiles[] = public_path($blog->image);

        $this->assertFileExists(public_path($blog->image));
        $this->assertDatabaseHas('publishing_media_attachments', [
            'publishing_media_id' => $media->id,
            'attachable_type' => Blog::class,
            'attachable_id' => $blog->id,
            'role' => 'blog_featured',
        ]);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'blog-publish-from-media')
            ->postJson("/api/v1/publishing/blogs/{$blog->id}/publish")
            ->assertOk()
            ->assertJsonPath('data.published', true);
    }

    public function test_event_can_use_media_id_and_shared_media_delete_is_blocked(): void
    {
        [, $token] = $this->publishingToken([
            'publishing:media.upload',
            'publishing:blogs.write',
            'publishing:events.write',
            'publishing:events.publish',
        ]);
        $media = $this->uploadMedia($token, 'shared-media', 'general');

        $blog = $this->createBlogFromMedia($token, $media);

        $this->withToken($token)
            ->postJson('/api/v1/publishing/preview/event', [
                'name' => 'Preview Event Media',
                'featured_media_id' => $media->id,
            ])
            ->assertOk()
            ->assertJsonPath('data.proposed_media.featured.id', $media->id);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-create-from-media')
            ->postJson('/api/v1/publishing/events', [
                'name' => 'Event From Media',
                'date' => '2026-12-20',
                'time' => '19:30',
                'location' => 'Edmonton, Alberta',
                'featured_media_id' => $media->id,
            ]);

        $response->assertCreated();
        $event = Event::findOrFail($response->json('data.id'));
        $this->trackEventImage($event->image);

        $this->assertFileExists(public_path('uploads/custom-images/' . $event->image));
        $this->assertFileExists(public_path('uploads/custom-images2/' . $event->image));
        $this->assertDatabaseHas('publishing_media_attachments', [
            'publishing_media_id' => $media->id,
            'attachable_type' => Event::class,
            'attachable_id' => $event->id,
            'role' => 'event_featured',
        ]);

        $this->withToken($token)
            ->deleteJson('/api/v1/publishing/media/' . $media->uuid)
            ->assertStatus(409);

        $replacement = $this->uploadMedia($token, 'blog-replacement', 'blog_featured');
        $this->withToken($token)
            ->patchJson("/api/v1/publishing/blogs/{$blog->id}", [
                'featured_media_id' => $replacement->id,
            ])
            ->assertOk();

        $this->assertDatabaseHas('publishing_media_attachments', [
            'publishing_media_id' => $media->id,
            'attachable_type' => Event::class,
            'attachable_id' => $event->id,
            'role' => 'event_featured',
        ]);
        $this->assertFileExists(public_path($media->relative_path));

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-publish-from-media')
            ->postJson("/api/v1/publishing/events/{$event->id}/publish")
            ->assertOk()
            ->assertJsonPath('data.published', true);
    }

    public function test_attached_media_show_includes_safe_attachments_and_audit_has_no_token_secret(): void
    {
        [, $token, $hashedToken] = $this->publishingToken([
            'publishing:media.upload',
            'publishing:blogs.write',
        ]);
        $media = $this->uploadMedia($token, 'attachment-show', 'general');
        $blog = $this->createBlogFromMedia($token, $media);

        $this->withToken($token)
            ->getJson('/api/v1/publishing/media/' . $media->uuid)
            ->assertOk()
            ->assertJsonPath('data.attachments.0.resource_type', 'Blog')
            ->assertJsonPath('data.attachments.0.resource_id', $blog->id)
            ->assertJsonPath('data.attachments.0.role', 'blog_featured')
            ->assertDontSee(Admin::class, false);

        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'media.attached']);
        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'media.uploaded']);

        $logs = PublishingAuditLog::query()->latest('id')->take(20)->get()->toArray();
        $encoded = json_encode($logs);

        $this->assertStringNotContainsString($token, $encoded);
        $this->assertStringNotContainsString($hashedToken, $encoded);
        $this->assertStringNotContainsString('Authorization', $encoded);
    }

    private function uploadMedia(string $token, string $key, string $purpose): PublishingMedia
    {
        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'media-upload-' . $key)
            ->post('/api/v1/publishing/media', [
                'file' => $this->uploadedJpeg($key . '.jpg'),
                'purpose' => $purpose,
                'alt_text' => 'Alt ' . $key,
            ], ['Accept' => 'application/json']);

        $response->assertCreated();
        $media = PublishingMedia::findOrFail($response->json('data.id'));
        $this->trackMedia($media);

        return $media;
    }

    private function createBlogFromMedia(string $token, PublishingMedia $media): Blog
    {
        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'blog-from-media-' . Str::uuid())
            ->postJson('/api/v1/publishing/blogs', [
                'title' => 'Shared Media Blog ' . Str::random(5),
                'blog_category_id' => $this->blogCategory()->id,
                'description' => '<p>Ready</p>',
                'featured_media_id' => $media->id,
            ]);

        $response->assertCreated();
        $blog = Blog::findOrFail($response->json('data.id'));
        $this->uploadedFiles[] = public_path($blog->image);

        return $blog;
    }

    private function publishingToken(array $abilities): array
    {
        $admin = Admin::create([
            'name' => 'Publishing Automation',
            'email' => 'media-publishing-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(32)),
            'status' => 1,
            'admin_type' => 0,
        ]);

        $token = $admin->createToken('Publishing API Test Token', $abilities);

        return [$admin, $token->plainTextToken, $token->accessToken->token];
    }

    private function blogCategory(int $status = 1): BlogCategory
    {
        $category = new BlogCategory();
        $category->name = 'Publishing Category ' . Str::random(8);
        $category->slug = Str::slug($category->name);
        $category->status = $status;
        $category->save();

        return $category;
    }

    private function mismatchedPngNameWithJpegContents(): UploadedFile
    {
        $source = $this->uploadedJpeg('source.jpg');

        return new UploadedFile($source->getRealPath(), 'mismatch.png', 'image/png', null, true);
    }

    private function uploadedJpeg(string $name): UploadedFile
    {
        return $this->uploadedGdImage($name, 'image/jpeg', 'jpeg');
    }

    private function uploadedPng(string $name): UploadedFile
    {
        return $this->uploadedGdImage($name, 'image/png', 'png');
    }

    private function uploadedWebp(string $name): UploadedFile
    {
        if (function_exists('imagewebp')) {
            return $this->uploadedGdImage($name, 'image/webp', 'webp');
        }

        return $this->uploadedImage($name, 'image/webp', 'UklGRiIAAABXRUJQVlA4IBYAAAAwAQCdASoBAAEADsD+JaQAA3AAAAAA');
    }

    private function uploadedImage(string $name, string $mimeType, string $base64): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'publishing-media-test-');
        file_put_contents($path, base64_decode($base64));
        $this->uploadedFiles[] = $path;

        return new UploadedFile($path, $name, $mimeType, null, true);
    }

    private function uploadedGdImage(string $name, string $mimeType, string $format): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'publishing-media-test-');
        $image = imagecreatetruecolor(1, 1);
        $color = imagecolorallocate($image, 217, 164, 65);
        imagefilledrectangle($image, 0, 0, 0, 0, $color);

        match ($format) {
            'jpeg' => imagejpeg($image, $path, 90),
            'png' => imagepng($image, $path),
            'webp' => imagewebp($image, $path, 90),
        };

        imagedestroy($image);
        $this->uploadedFiles[] = $path;

        return new UploadedFile($path, $name, $mimeType, null, true);
    }

    private function fileFromPath(string $path, string $name): UploadedFile
    {
        return new UploadedFile($path, $name, null, null, true);
    }

    private function trackMedia(PublishingMedia $media): void
    {
        $this->uploadedFiles[] = public_path($media->relative_path);
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
