<?php

namespace App\Services\Publishing;

use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PublishingEventService
{
    private const FIELDS = [
        'name',
        'slug',
        'date',
        'time',
        'location',
        'image',
        'ticket_price',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_author',
        'seo_publisher',
        'canonical_url',
        'meta_title',
        'meta_description',
        'meta_image',
        'meta_copyright',
        'site_name',
    ];

    private PublishingSlugService $slugService;
    private PublishingContentSanitizer $sanitizer;

    public function __construct(PublishingSlugService $slugService, PublishingContentSanitizer $sanitizer)
    {
        $this->slugService = $slugService;
        $this->sanitizer = $sanitizer;
    }

    public function prepareDraftData(array $payload, ?Event $event = null): array
    {
        $data = array_intersect_key($payload, array_flip(self::FIELDS));

        if (array_key_exists('slug', $data) || array_key_exists('name', $data)) {
            $data['slug'] = $this->slugService->uniqueSlug(
                'events',
                $data['name'] ?? $event?->name ?? 'event',
                $data['slug'] ?? null,
                $event?->id
            );
        }

        if (array_key_exists('description', $data)) {
            $data['description'] = $this->sanitizer->sanitize($data['description']);
        }

        if (array_key_exists('status', $data)) {
            unset($data['status']);
        }

        return $data;
    }

    public function createDraft(array $payload): Event
    {
        $event = new Event();
        $event->status = 0;
        $event->fill($this->prepareDraftData($payload));
        $event->save();

        return $event;
    }

    public function updateDraft(Event $event, array $payload): Event
    {
        $event->fill($this->prepareDraftData($payload, $event));
        $event->save();

        return $event;
    }

    public function validateForPublish(Event $event): void
    {
        $validator = Validator::make($event->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($event->id)],
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/'],
            'location' => ['required', 'string', 'max:255'],
        ], [
            'date.required' => 'The event date is required before publishing.',
            'time.required' => 'The event time is required before publishing.',
            'location.required' => 'The event location is required before publishing.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function publish(Event $event): Event
    {
        $this->validateForPublish($event);
        $event->status = 1;
        $event->save();

        return $event;
    }

    public function unpublish(Event $event): Event
    {
        $event->status = 0;
        $event->save();

        return $event;
    }

    public function publicUrl(Event $event): ?string
    {
        return $event->slug ? route('front.events.show', $event->slug) : null;
    }

    public function changedFields(Event $event, array $data): array
    {
        $changes = [];

        foreach ($data as $field => $value) {
            $old = $event->{$field};

            if ((string) $old !== (string) $value) {
                $changes[$field] = [
                    'old' => $field === 'description' ? 'set' : $old,
                    'new' => $field === 'description' ? 'set' : $value,
                ];
            }
        }

        return $changes;
    }

    public function exactDuplicate(Event $event): ?Event
    {
        if (! $event->name || ! $event->date || ! $event->time || ! $event->location) {
            return null;
        }

        return Event::query()
            ->where('id', '!=', $event->id ?: 0)
            ->where('status', 1)
            ->where('name', $event->name)
            ->where('date', $event->date)
            ->where('time', $event->time)
            ->where('location', $event->location)
            ->first();
    }

    public function toApiArray(Event $event): array
    {
        return [
            'id' => $event->id,
            'name' => $event->name,
            'slug' => $event->slug,
            'date' => $event->date,
            'time' => $event->time,
            'location' => $event->location,
            'ticket_price' => $event->ticket_price,
            'description' => $this->sanitizer->sanitize($event->description),
            'status' => (int) $event->status,
            'published' => (int) $event->status === 1,
            'seo_title' => $event->seo_title,
            'seo_description' => $event->seo_description,
            'seo_keywords' => $event->seo_keywords,
            'seo_author' => $event->seo_author,
            'seo_publisher' => $event->seo_publisher,
            'canonical_url' => $event->canonical_url,
            'meta_title' => $event->meta_title,
            'meta_description' => $event->meta_description,
            'meta_copyright' => $event->meta_copyright,
            'site_name' => $event->site_name,
            'image_url' => $this->imageUrl($event->image),
            'meta_image_url' => $this->metaImageUrl($event->meta_image),
            'url' => $this->publicUrl($event),
            'created_at' => optional($event->created_at)->toIso8601String(),
            'updated_at' => optional($event->updated_at)->toIso8601String(),
        ];
    }

    private function imageUrl(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        if (preg_match('#^(https?:)?//#', $image)) {
            return $image;
        }

        return str_contains($image, '/')
            ? asset($image)
            : asset('uploads/custom-images/' . $image);
    }

    private function metaImageUrl(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        return preg_match('#^(https?:)?//#', $image) ? $image : asset($image);
    }
}
