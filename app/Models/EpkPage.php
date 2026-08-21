<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EpkPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'hero_image',
        'hero_image_alt',
        'gold_feather_image',
        'gold_feather_image_alt',
        'overview_content',
        'sections',
        'audio_url',
        'audio_title',
        'video_url',
        'video_title',
        'booking_email',
        'status',
        'sort_order',
        'seo_title',
        'seo_description',
        'og_image',
        'og_image_alt',
        'published_at',
    ];

    protected $casts = [
        'sections' => 'array',
        'status' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->where(function (Builder $query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public static function makeSlug(string $title): string
    {
        return Str::slug($title);
    }

    public function publicUrl(): string
    {
        return match ($this->slug) {
            'full-artist' => route('front.epk.full-artist'),
            'crooners' => route('front.epk.crooners'),
            default => route('front.epk.show', $this->slug),
        };
    }
}
