<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishingAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'token_id',
        'action',
        'resource_type',
        'resource_id',
        'ip_address',
        'user_agent',
        'request_id',
        'idempotency_key',
        'changed_fields',
        'context',
        'created_at',
    ];

    protected $casts = [
        'changed_fields' => 'array',
        'context' => 'array',
        'created_at' => 'datetime',
    ];
}
