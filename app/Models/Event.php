<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = ['name','image','location','date','time','ticket_price','description','status'];

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

