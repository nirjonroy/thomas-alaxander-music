<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\PublishingAuditLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublishingBlogApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_blog_show_requires_authentication(): void
    {
        $blog = $this->blog('private-draft', 0);

        $this->getJson("/api/v1/publishing/blogs/{$blog->id}")
            ->assertStatus(401);
    }

    public function test_read_token_can_show_inactive_blog_but_cannot_create(): void
    {
        [$admin, $token] = $this->publishingToken(['publishing:blogs.read']);
        $blog = $this->blog('readable-draft', 0, $admin->id);

        $this->withToken($token)
            ->getJson("/api/v1/publishing/blogs/{$blog->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $blog->id)
            ->assertJsonPath('data.published', false)
            ->assertJsonMissingPath('data.admin.password');

        $this->withToken($token)
            ->postJson('/api/v1/publishing/blogs', ['title' => 'Nope'])
            ->assertStatus(403);
    }

    public function test_write_token_creates_draft_for_authenticated_admin_and_sanitizes_content(): void
    {
        [$admin, $token] = $this->publishingToken(['publishing:blogs.write']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'create-draft-1')
            ->postJson('/api/v1/publishing/blogs', [
                'title' => 'My New Blog',
                'slug' => 'My Custom Slug!',
                'status' => 1,
                'description' => '<p onclick="bad()">Safe <strong>copy</strong></p><script>alert(1)</script>',
                'show_homepage' => 1,
                'admin_id' => 999,
            ]);

        $response->assertStatus(422);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'create-draft-2')
            ->postJson('/api/v1/publishing/blogs', [
                'title' => 'My New Blog',
                'slug' => 'My Custom Slug!',
                'status' => 1,
                'description' => '<p onclick="bad()">Safe <strong>copy</strong></p><script>alert(1)</script>',
                'show_homepage' => 1,
            ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Blog draft created.')
            ->assertJsonPath('data.slug', 'my-custom-slug')
            ->assertJsonPath('data.status', 0)
            ->assertJsonPath('data.published', false);

        $blog = Blog::findOrFail($response->json('data.id'));

        $this->assertSame($admin->id, (int) $blog->admin_id);
        $this->assertSame(0, (int) $blog->status);
        $this->assertStringContainsString('<strong>copy</strong>', $blog->description);
        $this->assertStringNotContainsString('onclick', $blog->description);
        $this->assertStringNotContainsString('<script', $blog->description);
        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'blog.created', 'resource_id' => $blog->id]);
    }

    public function test_slug_generation_duplicate_suffix_and_update_keeps_own_slug(): void
    {
        $this->blog('example-blog', 1);
        [$admin, $token] = $this->publishingToken(['publishing:blogs.write']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'slug-draft-1')
            ->postJson('/api/v1/publishing/blogs', ['title' => 'Example Blog']);

        $response->assertCreated()->assertJsonPath('data.slug', 'example-blog-2');
        $blog = Blog::findOrFail($response->json('data.id'));

        $this->withToken($token)
            ->patchJson("/api/v1/publishing/blogs/{$blog->id}", ['title' => 'Retitled Blog'])
            ->assertOk()
            ->assertJsonPath('data.slug', 'retitled-blog');

        $this->withToken($token)
            ->patchJson("/api/v1/publishing/blogs/{$blog->id}", ['slug' => 'example-blog-2'])
            ->assertOk()
            ->assertJsonPath('data.slug', 'example-blog-2');
    }

    public function test_valid_image_upload_and_safe_replacement(): void
    {
        [$admin, $token] = $this->publishingToken(['publishing:blogs.write']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'image-draft-1')
            ->post('/api/v1/publishing/blogs', [
                'title' => 'Image Blog',
                'image' => $this->uploadedJpeg('cover.jpg'),
                'meta_image' => $this->uploadedJpeg('meta.jpg'),
            ], ['Accept' => 'application/json']);

        $response->assertCreated();
        $blog = Blog::findOrFail($response->json('data.id'));
        $oldImage = $blog->image;

        $this->assertStringStartsWith('uploads/custom-images/publishing-', $blog->image);
        $this->assertStringStartsWith('uploads/website-images/publishing-', $blog->meta_image);
        $this->assertFileExists(public_path($blog->image));

        $this->withToken($token)
            ->patch("/api/v1/publishing/blogs/{$blog->id}", [
                'image' => $this->uploadedJpeg('replacement.jpg'),
            ], ['Accept' => 'application/json'])
            ->assertOk();

        $blog->refresh();
        $this->assertNotSame($oldImage, $blog->image);
        $this->assertFileDoesNotExist(public_path($oldImage));
        $this->assertFileExists(public_path($blog->image));
    }

    public function test_unsafe_image_uploads_are_rejected(): void
    {
        [, $token] = $this->publishingToken(['publishing:blogs.write']);

        foreach ([
            UploadedFile::fake()->create('bad.svg', 1, 'image/svg+xml'),
            UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
            UploadedFile::fake()->create('fake.jpg', 1, 'application/x-php'),
        ] as $index => $file) {
            $this->withToken($token)
                ->withHeader('Idempotency-Key', 'unsafe-image-' . $index)
                ->post('/api/v1/publishing/blogs', [
                    'title' => 'Unsafe Image ' . $index,
                    'image' => $file,
                ], ['Accept' => 'application/json'])
                ->assertStatus(422);
        }
    }

    public function test_preview_returns_proposed_slug_and_does_not_create_blog(): void
    {
        [, $token] = $this->publishingToken(['publishing:blogs.write']);
        $count = Blog::count();

        $this->withToken($token)
            ->postJson('/api/v1/publishing/preview/blog', [
                'title' => 'Preview Blog',
                'description' => '<p>Preview</p>',
            ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'preview-blog')
            ->assertJsonPath('data.publishable', false)
            ->assertJsonPath('data.publish_errors.image.0', 'The image field is required.');

        $this->assertSame($count, Blog::count());
    }

    public function test_write_token_cannot_publish_without_publish_ability(): void
    {
        [$admin, $writeToken] = $this->publishingToken(['publishing:blogs.write']);
        $blog = $this->blog('incomplete-blog', 0, $admin->id);

        $this->withToken($writeToken)
            ->withHeader('Idempotency-Key', 'publish-no-ability')
            ->postJson("/api/v1/publishing/blogs/{$blog->id}/publish")
            ->assertStatus(403);
    }

    public function test_publish_requires_valid_persisted_blog(): void
    {
        [$admin] = $this->publishingToken(['publishing:blogs.read']);
        $blog = $this->blog('incomplete-blog', 0, $admin->id);
        $blog->image = null;
        $blog->save();

        [, $publishToken] = $this->publishingToken(['publishing:blogs.publish']);

        $this->withToken($publishToken)
            ->withHeader('Idempotency-Key', 'publish-invalid')
            ->postJson("/api/v1/publishing/blogs/{$blog->id}/publish")
            ->assertStatus(422)
            ->assertJsonPath('message', 'Blog is not ready to publish.');

        $blog->image = 'uploads/custom-images/existing.jpg';
        $blog->save();

        $this->withToken($publishToken)
            ->withHeader('Idempotency-Key', 'publish-valid')
            ->postJson("/api/v1/publishing/blogs/{$blog->id}/publish")
            ->assertOk()
            ->assertJsonPath('data.status', 1)
            ->assertJsonPath('data.published', true)
            ->assertJsonPath('data.url', route('front.blog_details', $blog->slug));

        $this->assertSame(1, (int) $blog->fresh()->status);
        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'blog.published', 'resource_id' => $blog->id]);
    }

    public function test_unpublish_sets_status_zero_without_deleting_blog(): void
    {
        [, $token] = $this->publishingToken(['publishing:blogs.publish']);
        $blog = $this->blog('published-blog', 1);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'unpublish-valid')
            ->postJson("/api/v1/publishing/blogs/{$blog->id}/unpublish")
            ->assertOk()
            ->assertJsonPath('data.status', 0)
            ->assertJsonPath('data.published', false);

        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'status' => 0]);
        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'blog.unpublished', 'resource_id' => $blog->id]);
    }

    public function test_create_idempotency_replays_response_and_prevents_duplicate_blog(): void
    {
        [, $token] = $this->publishingToken(['publishing:blogs.write']);

        $payload = ['title' => 'Idempotent Blog'];
        $first = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'idem-create-blog')
            ->postJson('/api/v1/publishing/blogs', $payload);

        $first->assertCreated();

        $second = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'idem-create-blog')
            ->postJson('/api/v1/publishing/blogs', $payload);

        $second->assertCreated();
        $this->assertSame($first->json('data.id'), $second->json('data.id'));
        $this->assertSame(1, Blog::where('title', 'Idempotent Blog')->count());

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'idem-create-blog')
            ->postJson('/api/v1/publishing/blogs', ['title' => 'Different Payload'])
            ->assertStatus(409);
    }

    public function test_audit_does_not_store_bearer_token(): void
    {
        [, $token] = $this->publishingToken(['publishing:blogs.write']);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'audit-token-check')
            ->postJson('/api/v1/publishing/blogs', ['title' => 'Audit Blog'])
            ->assertCreated();

        $logs = PublishingAuditLog::query()->latest('id')->firstOrFail()->toArray();

        $this->assertStringNotContainsString($token, json_encode($logs));
        $this->assertStringNotContainsString('Authorization', json_encode($logs));
    }

    private function publishingToken(array $abilities): array
    {
        $admin = Admin::create([
            'name' => 'Publishing Automation',
            'email' => 'publishing-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(32)),
            'status' => 1,
            'admin_type' => 0,
        ]);

        $token = $admin->createToken('Publishing API Test Token', $abilities);

        return [$admin, $token->plainTextToken];
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

    private function blog(string $slug, int $status, ?int $adminId = null): Blog
    {
        if (! $adminId) {
            [$admin] = $this->publishingToken(['publishing:blogs.read']);
            $adminId = $admin->id;
        }

        $blog = new Blog();
        $blog->admin_id = $adminId;
        $blog->title = Str::headline($slug);
        $blog->slug = $slug;
        $blog->blog_category_id = $this->blogCategory()->id;
        $blog->image = 'uploads/custom-images/existing.jpg';
        $blog->description = '<p>Body</p>';
        $blog->status = $status;
        $blog->show_homepage = 0;
        $blog->save();

        return $blog;
    }

    private function uploadedJpeg(string $name): UploadedFile
    {
        $directory = storage_path('framework/testing');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = tempnam($directory, 'publishing-jpeg-');
        file_put_contents($path, base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAH/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAEFAqf/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAEDAQE/ASP/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAECAQE/ASP/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAY/Aqf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAE/IX//2gAMAwEAAgADAAAAEP/EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8QH//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8QH//EABQQAQAAAAAAAAAAAAAAAAAAABD/2gAIAQEAAT8QH//Z'));

        return new UploadedFile($path, $name, 'image/jpeg', null, true);
    }
}
