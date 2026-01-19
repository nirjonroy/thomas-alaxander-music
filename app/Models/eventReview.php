<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class eventReview extends Model
{
     protected $fillable = ['event_id','user_id','name','email','rating','comment','status'];

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function user(): BelongsTo  { return $this->belongsTo(User::class); }
}
