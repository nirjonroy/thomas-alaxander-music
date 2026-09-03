<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishingIdempotencyKey extends Model
{
    protected $fillable = [
        'key',
        'actor_type',
        'actor_id',
        'token_id',
        'method',
        'path',
        'request_hash',
        'response_status',
        'response_body',
        'expires_at',
    ];

    protected $casts = [
        'response_body' => 'array',
        'expires_at' => 'datetime',
    ];
}
