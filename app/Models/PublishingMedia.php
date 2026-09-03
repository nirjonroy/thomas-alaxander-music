<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublishingMedia extends Model
{
    protected $table = 'publishing_media';

    protected $fillable = [
        'uuid',
        'uploaded_by_admin_id',
        'token_id',
        'media_type',
        'purpose',
        'relative_path',
        'stored_name',
        'original_name',
        'mime_type',
        'extension',
        'size',
        'width',
        'height',
        'checksum',
        'alt_text',
        'caption',
        'status',
    ];

    protected $casts = [
        'uploaded_by_admin_id' => 'integer',
        'token_id' => 'integer',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'status' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PublishingMediaAttachment::class, 'publishing_media_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function isActive(): bool
    {
        return (int) $this->status === 1;
    }
}
