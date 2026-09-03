<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\PublishingAuditLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublishingEventApiTest extends TestCase
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

    public function test_event_show_requires_authentication(): void
    {
        $event = $this->event('private-event', 0);

        $this->getJson("/api/v1/publishing/events/{$event->id}")
            ->assertStatus(401);
    }

    public function test_read_token_can_show_inactive_event_but_cannot_create(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.read']);
        $event = $this->event('readable-event', 0);

        $this->withToken($token)
            ->getJson("/api/v1/publishing/events/{$event->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $event->id)
            ->assertJsonPath('data.published', false);

        $this->withToken($token)
            ->postJson('/api/v1/publishing/events', ['name' => 'Nope'])
            ->assertStatus(403);
    }

    public function test_write_token_creates_draft_with_null_date_and_time(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-draft-only-name')
            ->postJson('/api/v1/publishing/events', [
                'name' => 'Thomas Alexander Live at Alvin\'s Jazz Club',
                'status' => 1,
            ]);

        $response->assertStatus(422);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-draft-only-name-2')
            ->postJson('/api/v1/publishing/events', [
                'name' => 'Thomas Alexander Live at Alvin\'s Jazz Club',
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.slug', 'thomas-alexander-live-at-alvins-jazz-club')
            ->assertJsonPath('data.date', null)
            ->assertJsonPath('data.time', null)
            ->assertJsonPath('data.status', 0)
            ->assertJsonPath('data.published', false);

        $this->assertDatabaseHas('events', [
            'id' => $response->json('data.id'),
            'date' => null,
            'time' => null,
            'status' => 0,
        ]);
        $this->assertDatabaseHas('publishing_audit_logs', [
            'action' => 'event.created',
            'resource_id' => $response->json('data.id'),
        ]);
    }

    public function test_update_adds_publish_fields_and_does_not_unpublish_existing_event(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);
        $event = $this->event('published-event', 1);

        $this->withToken($token)
            ->patchJson("/api/v1/publishing/events/{$event->id}", [
                'date' => '2026-11-12',
                'time' => '19:30',
                'location' => 'Edmonton, Alberta',
            ])
            ->assertOk()
            ->assertJsonPath('data.date', '2026-11-12')
            ->assertJsonPath('data.time', '19:30')
            ->assertJsonPath('data.status', 1)
            ->assertJsonPath('data.published', true);

        $this->assertSame(1, (int) $event->fresh()->status);
        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'event.updated', 'resource_id' => $event->id]);
    }

    public function test_slug_generation_duplicate_suffix_custom_slug_and_own_slug_retained(): void
    {
        $this->event('example-event', 1);
        [, $token] = $this->publishingToken(['publishing:events.write']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-slug-1')
            ->postJson('/api/v1/publishing/events', ['name' => 'Example Event']);

        $response->assertCreated()->assertJsonPath('data.slug', 'example-event-2');
        $event = Event::findOrFail($response->json('data.id'));

        $this->withToken($token)
            ->patchJson("/api/v1/publishing/events/{$event->id}", ['slug' => 'My Custom Event!'])
            ->assertOk()
            ->assertJsonPath('data.slug', 'my-custom-event');

        $this->withToken($token)
            ->patchJson("/api/v1/publishing/events/{$event->id}", ['slug' => 'my-custom-event'])
            ->assertOk()
            ->assertJsonPath('data.slug', 'my-custom-event');
    }

    public function test_description_is_sanitized(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-sanitize')
            ->postJson('/api/v1/publishing/events', [
                'name' => 'Sanitized Event',
                'description' => '<p onclick="bad()">Safe <strong>copy</strong></p><script>alert(1)</script>',
            ]);

        $response->assertCreated();
        $event = Event::findOrFail($response->json('data.id'));

        $this->assertStringContainsString('<strong>copy</strong>', $event->description);
        $this->assertStringNotContainsString('onclick', $event->description);
        $this->assertStringNotContainsString('<script', $event->description);
    }

    public function test_valid_event_image_upload_and_safe_replacement(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);

        $response = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-image-1')
            ->post('/api/v1/publishing/events', [
                'name' => 'Image Event',
                'image' => $this->uploadedJpeg('cover.jpg'),
                'meta_image' => $this->uploadedJpeg('meta.jpg'),
            ], ['Accept' => 'application/json']);

        $response->assertCreated();
        $event = Event::findOrFail($response->json('data.id'));
        $oldImage = $event->image;

        $this->trackEventImage($event->image);
        $this->uploadedFiles[] = public_path($event->meta_image);

        $this->assertMatchesRegularExpression('/^publishing-[0-9a-f-]+\.jpg$/', $event->image);
        $this->assertStringStartsWith('uploads/website-images/publishing-', $event->meta_image);
        $this->assertFileExists(public_path('uploads/custom-images/' . $event->image));
        $this->assertFileExists(public_path('uploads/custom-images2/' . $event->image));

        $this->withToken($token)
            ->patch("/api/v1/publishing/events/{$event->id}", [
                'image' => $this->uploadedJpeg('replacement.jpg'),
            ], ['Accept' => 'application/json'])
            ->assertOk();

        $event->refresh();
        $this->trackEventImage($event->image);

        $this->assertNotSame($oldImage, $event->image);
        $this->assertFileDoesNotExist(public_path('uploads/custom-images/' . $oldImage));
        $this->assertFileDoesNotExist(public_path('uploads/custom-images2/' . $oldImage));
        $this->assertFileExists(public_path('uploads/custom-images/' . $event->image));
    }

    public function test_event_image_replacement_preserves_legacy_non_publishing_files(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);
        $legacyImage = 'legacy-event-image.jpg';

        $this->writePublicFile('uploads/custom-images/' . $legacyImage);
        $this->writePublicFile('uploads/custom-images2/' . $legacyImage);

        $event = $this->event('legacy-image-event', 0);
        $event->image = $legacyImage;
        $event->save();

        $this->withToken($token)
            ->patch("/api/v1/publishing/events/{$event->id}", [
                'image' => $this->uploadedJpeg('replacement.jpg'),
            ], ['Accept' => 'application/json'])
            ->assertOk();

        $event->refresh();
        $this->trackEventImage($event->image);

        $this->assertFileExists(public_path('uploads/custom-images/' . $legacyImage));
        $this->assertFileExists(public_path('uploads/custom-images2/' . $legacyImage));
        $this->assertMatchesRegularExpression('/^publishing-[0-9a-f-]+\.jpg$/', $event->image);
    }

    public function test_event_image_replacement_tolerates_missing_secondary_variant(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);
        $oldImage = 'publishing-' . Str::uuid() . '.jpg';

        $this->writePublicFile('uploads/custom-images/' . $oldImage);

        $event = $this->event('missing-secondary-image-event', 0);
        $event->image = $oldImage;
        $event->save();

        $this->withToken($token)
            ->patch("/api/v1/publishing/events/{$event->id}", [
                'image' => $this->uploadedJpeg('replacement.jpg'),
            ], ['Accept' => 'application/json'])
            ->assertOk();

        $event->refresh();
        $this->trackEventImage($event->image);

        $this->assertNotSame($oldImage, $event->image);
        $this->assertFileDoesNotExist(public_path('uploads/custom-images/' . $oldImage));
        $this->assertFileDoesNotExist(public_path('uploads/custom-images2/' . $oldImage));
        $this->assertFileExists(public_path('uploads/custom-images/' . $event->image));
        $this->assertFileExists(public_path('uploads/custom-images2/' . $event->image));
    }

    public function test_unsafe_event_images_and_multiday_fields_are_rejected(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);

        foreach ([
            UploadedFile::fake()->create('bad.svg', 1, 'image/svg+xml'),
            UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
            UploadedFile::fake()->create('fake.jpg', 1, 'application/x-php'),
        ] as $index => $file) {
            $this->withToken($token)
                ->withHeader('Idempotency-Key', 'unsafe-event-image-' . $index)
                ->post('/api/v1/publishing/events', [
                    'name' => 'Unsafe Event ' . $index,
                    'image' => $file,
                ], ['Accept' => 'application/json'])
                ->assertStatus(422);
        }

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-date-range')
            ->postJson('/api/v1/publishing/events', [
                'name' => 'Unsupported Multi Day Event',
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-02',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['start_date', 'end_date']);
    }

    public function test_preview_reports_missing_publish_fields_and_does_not_persist(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);
        $count = Event::count();

        $this->withToken($token)
            ->postJson('/api/v1/publishing/preview/event', [
                'name' => 'Preview Event',
                'description' => '<p>Preview</p>',
            ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'preview-event')
            ->assertJsonPath('data.publishable', false)
            ->assertJsonPath('data.publish_errors.date.0', 'The event date is required before publishing.')
            ->assertJsonPath('data.publish_errors.time.0', 'The event time is required before publishing.')
            ->assertJsonPath('data.publish_errors.location.0', 'The event location is required before publishing.');

        $this->assertSame($count, Event::count());
    }

    public function test_write_token_cannot_publish_event_without_publish_ability(): void
    {
        $event = $this->event('incomplete-event', 0, null, null, null);
        [, $writeToken] = $this->publishingToken(['publishing:events.write']);

        $this->withToken($writeToken)
            ->withHeader('Idempotency-Key', 'event-publish-no-ability')
            ->postJson("/api/v1/publishing/events/{$event->id}/publish")
            ->assertStatus(403);
    }

    public function test_publish_token_reaches_validation_for_incomplete_event(): void
    {
        $event = $this->event('incomplete-event-validation', 0, null, null, null);

        [, $publishToken] = $this->publishingToken(['publishing:events.publish']);

        $response = $this->withToken($publishToken)
            ->withHeader('Idempotency-Key', 'event-publish-invalid')
            ->postJson("/api/v1/publishing/events/{$event->id}/publish");

        $this->assertNotSame(403, $response->status());
        $response->assertStatus(422)
            ->assertJsonPath('message', 'Event is not ready to publish.');
    }

    public function test_publish_token_can_publish_valid_event(): void
    {
        $event = $this->event('publishable-event', 0, null, null, null);
        [, $publishToken] = $this->publishingToken(['publishing:events.publish']);

        $this->withToken($publishToken)
            ->withHeader('Idempotency-Key', 'event-publish-valid')
            ->postJson("/api/v1/publishing/events/{$event->id}/publish", [
                'date' => '2026-12-15',
                'time' => '20:00',
                'location' => 'Edmonton, Alberta',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 1)
            ->assertJsonPath('data.published', true)
            ->assertJsonPath('data.url', route('front.events.show', $event->slug));

        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'event.published', 'resource_id' => $event->id]);
    }

    public function test_unpublish_sets_status_zero_without_deleting_event(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.publish']);
        $event = $this->event('published-to-unpublish', 1);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-unpublish-valid')
            ->postJson("/api/v1/publishing/events/{$event->id}/unpublish")
            ->assertOk()
            ->assertJsonPath('data.status', 0)
            ->assertJsonPath('data.published', false);

        $this->assertDatabaseHas('events', ['id' => $event->id, 'status' => 0]);
        $this->assertDatabaseHas('publishing_audit_logs', ['action' => 'event.unpublished', 'resource_id' => $event->id]);
    }

    public function test_create_idempotency_replays_response_and_prevents_duplicate_event(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);

        $payload = ['name' => 'Idempotent Event'];
        $first = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'idem-create-event')
            ->postJson('/api/v1/publishing/events', $payload);

        $first->assertCreated();

        $second = $this->withToken($token)
            ->withHeader('Idempotency-Key', 'idem-create-event')
            ->postJson('/api/v1/publishing/events', $payload);

        $second->assertCreated();
        $this->assertSame($first->json('data.id'), $second->json('data.id'));
        $this->assertSame(1, Event::where('name', 'Idempotent Event')->count());

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'idem-create-event')
            ->postJson('/api/v1/publishing/events', ['name' => 'Different Event'])
            ->assertStatus(409);
    }

    public function test_audit_does_not_store_bearer_token(): void
    {
        [, $token] = $this->publishingToken(['publishing:events.write']);

        $this->withToken($token)
            ->withHeader('Idempotency-Key', 'event-audit-token-check')
            ->postJson('/api/v1/publishing/events', ['name' => 'Audit Event'])
            ->assertCreated();

        $logs = PublishingAuditLog::query()->latest('id')->firstOrFail()->toArray();

        $this->assertStringNotContainsString($token, json_encode($logs));
        $this->assertStringNotContainsString('Authorization', json_encode($logs));
    }

    private function publishingToken(array $abilities): array
    {
        $admin = Admin::create([
            'name' => 'Publishing Automation',
            'email' => 'event-publishing-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(32)),
            'status' => 1,
            'admin_type' => 0,
        ]);

        $token = $admin->createToken('Publishing API Test Token', $abilities);

        return [$admin, $token->plainTextToken];
    }

    private function event(string $slug, int $status, ?string $date = '2026-10-20', ?string $time = '19:00', ?string $location = 'Edmonton, Alberta'): Event
    {
        $event = new Event();
        $event->name = Str::headline($slug);
        $event->slug = $slug;
        $event->date = $date;
        $event->time = $time;
        $event->location = $location;
        $event->ticket_price = '25';
        $event->description = '<p>Event body</p>';
        $event->status = $status;
        $event->save();

        return $event;
    }

    private function uploadedJpeg(string $name): UploadedFile
    {
        $directory = storage_path('framework/testing');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = tempnam($directory, 'publishing-event-jpeg-');
        file_put_contents($path, base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAH/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAEFAqf/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAEDAQE/ASP/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAECAQE/ASP/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAY/Aqf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAE/IX//2gAMAwEAAgADAAAAEP/EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8QH//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8QH//EABQQAQAAAAAAAAAAAAAAAAAAABD/2gAIAQEAAT8QH//Z'));

        return new UploadedFile($path, $name, 'image/jpeg', null, true);
    }

    private function trackEventImage(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $this->uploadedFiles[] = public_path('uploads/custom-images/' . $filename);
        $this->uploadedFiles[] = public_path('uploads/custom-images2/' . $filename);
        $this->uploadedFiles[] = public_path('uploads/main-image/' . $filename);
    }

    private function writePublicFile(string $path): void
    {
        $absolutePath = public_path($path);
        $directory = dirname($absolutePath);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($absolutePath, 'test');
        $this->uploadedFiles[] = $absolutePath;
    }
}
