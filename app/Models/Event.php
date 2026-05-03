<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'location',
        'date',
        'time',
        'ticket_price',
        'description',
        'status',
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRouteKey()
    {
        return $this->slug ?: $this->id;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(eventReview::class)->where('status','approved');
    }

    public function getStartsAtAttribute(): ?Carbon
    {
        $dt = trim(($this->date ?? '').' '.($this->time ?? ''));
        return $dt ? Carbon::parse($dt) : null;
    }
}
