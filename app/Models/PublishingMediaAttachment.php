<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PublishingMediaAttachment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'publishing_media_id',
        'attachable_type',
        'attachable_id',
        'role',
        'created_at',
    ];

    protected $casts = [
        'publishing_media_id' => 'integer',
        'attachable_id' => 'integer',
        'created_at' => 'datetime',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(PublishingMedia::class, 'publishing_media_id');
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
