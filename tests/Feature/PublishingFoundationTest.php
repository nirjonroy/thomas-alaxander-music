<?php

namespace Tests\Feature;

use App\Exceptions\Publishing\IdempotencyKeyConflictException;
use App\Http\Requests\Api\V1\Publishing\PublishBlogRequest;
use App\Http\Requests\Api\V1\Publishing\PublishEventRequest;
use App\Http\Requests\Api\V1\Publishing\StoreBlogRequest;
use App\Http\Requests\Api\V1\Publishing\StoreEventRequest;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\PublishingIdempotencyKey;
use App\Services\Publishing\PublishingAuditLogger;
use App\Services\Publishing\PublishingContentSanitizer;
use App\Services\Publishing\PublishingIdempotencyService;
use App\Services\Publishing\PublishingMediaService;
use App\Services\Publishing\PublishingSlugService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PublishingFoundationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_blog_draft_accepts_incomplete_safe_payload(): void
    {
        $validator = Validator::make([
            'title' => 'Draft title',
            'description' => '<p>Partial copy</p>',
            'status' => 0,
        ], (new StoreBlogRequest())->rules());

        $this->assertFalse($validator->fails(), $validator->errors()->toJson());
    }

    public function test_blog_publish_request_allows_empty_action_payload(): void
    {
        $validator = Validator::make([], (new PublishBlogRequest())->rules());

        $this->assertFalse($validator->fails(), $validator->errors()->toJson());
    }

    public function test_event_draft_accepts_incomplete_safe_payload(): void
    {
        $validator = Validator::make([
            'name' => 'Draft event',
            'description' => '<p>Working notes</p>',
        ], (new StoreEventRequest())->rules());

        $this->assertFalse($validator->fails(), $validator->errors()->toJson());
    }

    public function test_event_publish_request_allows_empty_action_payload(): void
    {
        $validator = Validator::make([], (new PublishEventRequest())->rules());

        $this->assertFalse($validator->fails(), $validator->errors()->toJson());
    }

    public function test_invalid_status_and_duplicate_slug_are_rejected(): void
    {
        $blog = $this->blog('taken-slug', 1);

        $validator = Validator::make([
            'title' => 'New title',
            'slug' => $blog->slug,
            'status' => 3,
        ], (new StoreBlogRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('slug', $validator->errors()->toArray());
        $this->assertArrayHasKey('status', $validator->errors()->toArray());
    }

    public function test_sanitizer_retains_safe_formatting_and_removes_dangerous_html(): void
    {
        $html = '<p onclick="bad()">Hello <strong>World</strong></p><a href="javascript:alert(1)">bad</a><script>alert(1)</script><iframe src="https://example.com"></iframe>';
        $clean = app(PublishingContentSanitizer::class)->sanitize($html);

        $this->assertStringContainsString('<p>Hello <strong>World</strong></p>', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringNotContainsString('<script', $clean);
        $this->assertStringNotContainsString('<iframe', $clean);
    }

    public function test_slug_service_generates_predictable_unique_slugs_and_ignores_current_record(): void
    {
        $first = $this->blog('my-new-blog', 1);
        $this->blog('my-new-blog-2', 1);

        $service = app(PublishingSlugService::class);

        $this->assertSame('my-new-blog-3', $service->uniqueSlug('blogs', 'My New Blog'));
        $this->assertSame('my-new-blog', $service->uniqueSlug('blogs', 'Changed title', 'my-new-blog', $first->id));
    }

    public function test_idempotency_accepts_new_duplicate_and_rejects_changed_payload(): void
    {
        $service = app(PublishingIdempotencyService::class);
        $request = Request::create('/api/v1/publishing/blogs', 'POST');

        $first = $service->begin($request, 'idem-key', ['title' => 'A']);
        $second = $service->begin($request, 'idem-key', ['title' => 'A']);

        $this->assertSame($first->id, $second->id);

        $this->expectException(IdempotencyKeyConflictException::class);
        $service->begin($request, 'idem-key', ['title' => 'B']);
    }

    public function test_idempotency_replays_completed_response_and_allows_expired_key_reuse(): void
    {
        $service = app(PublishingIdempotencyService::class);
        $request = Request::create('/api/v1/publishing/blogs', 'POST');

        $record = $service->begin($request, 'completed-key', ['title' => 'A']);
        $completed = $service->complete($record, 201, ['ok' => true]);

        $this->assertTrue($service->isReplayable($completed));
        $this->assertSame(201, $completed->response_status);
        $this->assertSame(['ok' => true], $completed->response_body);

        PublishingIdempotencyKey::query()->where('key', 'expired-key')->create([
            'key' => 'expired-key',
            'method' => 'POST',
            'path' => '/api/v1/publishing/blogs',
            'request_hash' => $service->hashPayload(['title' => 'Old']),
            'expires_at' => now()->subMinute(),
        ]);

        $reused = $service->begin($request, 'expired-key', ['title' => 'New']);

        $this->assertSame($service->hashPayload(['title' => 'New']), $reused->request_hash);
    }

    public function test_idempotency_key_is_unique_in_database(): void
    {
        PublishingIdempotencyKey::create([
            'key' => 'unique-key',
            'method' => 'POST',
            'path' => '/api/v1/publishing/blogs',
            'request_hash' => hash('sha256', 'one'),
            'expires_at' => now()->addHour(),
        ]);

        $this->expectException(QueryException::class);

        PublishingIdempotencyKey::create([
            'key' => 'unique-key',
            'method' => 'POST',
            'path' => '/api/v1/publishing/blogs',
            'request_hash' => hash('sha256', 'two'),
            'expires_at' => now()->addHour(),
        ]);
    }

    public function test_audit_logger_creates_safe_entry_without_token_secret(): void
    {
        $admin = Admin::create([
            'name' => 'Publishing User',
            'email' => 'publishing-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(32)),
            'status' => 1,
            'admin_type' => 0,
        ]);
        $request = Request::create('/api/v1/publishing/blogs', 'POST', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer should-not-persist',
            'HTTP_IDEMPOTENCY_KEY' => 'audit-key',
        ]);
        $request->setUserResolver(fn () => $admin);

        $log = app(PublishingAuditLogger::class)->log($request, 'blog.updated', [
            'resource_type' => Blog::class,
            'resource_id' => 10,
            'changed_fields' => [
                'title' => ['old' => 'A', 'new' => 'B'],
                'authorization' => 'Bearer should-not-persist',
                'api_token' => 'secret',
            ],
        ]);

        $this->assertSame('blog.updated', $log->action);
        $this->assertSame(['title' => ['old' => 'A', 'new' => 'B']], $log->changed_fields);
        $this->assertStringNotContainsString('should-not-persist', json_encode($log->toArray()));
        $this->assertStringNotContainsString('secret', json_encode($log->toArray()));
    }

    public function test_sitemap_includes_active_blog_and_excludes_inactive_blog(): void
    {
        $active = $this->blog('active-blog', 1);
        $inactive = $this->blog('inactive-blog', 0);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee(route('front.blog_details', $active->slug), false);
        $response->assertDontSee(route('front.blog_details', $inactive->slug), false);
    }

    public function test_media_service_accepts_valid_jpeg_and_rejects_unsafe_files(): void
    {
        $service = app(PublishingMediaService::class);

        $service->validateImage($this->uploadedJpeg('photo.jpg'));
        $this->assertTrue(true);

        foreach ([
            UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
            UploadedFile::fake()->create('vector.svg', 1, 'image/svg+xml'),
            $this->uploadedJpeg('huge.jpg', 4194305),
            UploadedFile::fake()->create('fake.jpg', 1, 'application/x-php'),
        ] as $file) {
            try {
                $service->validateImage($file);
                $this->fail('Unsafe media file was accepted: ' . $file->getClientOriginalName());
            } catch (ValidationException $exception) {
                $this->assertNotEmpty($exception->errors());
            }
        }
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

    private function blog(string $slug, int $status): Blog
    {
        $admin = Admin::create([
            'name' => 'Publishing Blog Admin',
            'email' => 'blog-admin-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(32)),
            'status' => 1,
            'admin_type' => 0,
        ]);

        $blog = new Blog();
        $blog->admin_id = $admin->id;
        $blog->title = Str::headline($slug);
        $blog->slug = $slug;
        $blog->blog_category_id = $this->blogCategory()->id;
        $blog->image = 'uploads/custom-images/test.jpg';
        $blog->description = '<p>Body</p>';
        $blog->status = $status;
        $blog->show_homepage = 0;
        $blog->save();

        return $blog;
    }

    private function uploadedJpeg(string $name, int $extraBytes = 0): UploadedFile
    {
        $directory = storage_path('framework/testing');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = tempnam($directory, 'publishing-jpeg-');
        file_put_contents($path, base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAH/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAEFAqf/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAEDAQE/ASP/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAECAQE/ASP/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAY/Aqf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAE/IX//2gAMAwEAAgADAAAAEP/EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8QH//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8QH//EABQQAQAAAAAAAAAAAAAAAAAAABD/2gAIAQEAAT8QH//Z'));

        if ($extraBytes > 0) {
            file_put_contents($path, str_repeat('0', $extraBytes), FILE_APPEND);
        }

        return new UploadedFile($path, $name, 'image/jpeg', null, true);
    }
}
