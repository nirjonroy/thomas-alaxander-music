<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyCompanion extends Model
{
    use HasFactory;

    protected $table = 'journey_companions';

    public const CANONICAL_ORDER = [
        1 => 'Wolf',
        2 => 'Hawk',
        3 => 'Raven',
        4 => 'Owl',
        5 => 'Eagle',
        6 => 'Panther',
        7 => 'Bear',
        8 => 'Lynx',
        9 => 'Deer',
        10 => 'Fox',
        11 => 'Otter',
    ];

    protected $fillable = [
        'sequence',
        'name',
        'slug',
        'themes',
        'artwork_image',
        'artwork_image_alt',
        'expanded_teaching',
        'remember_teaching',
        'branding_info',
        'is_active',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sequence', 'asc');
    }

    public static function canonicalOrder(): array
    {
        return self::CANONICAL_ORDER;
    }

    public static function canonicalNameForSequence(int $sequence): ?string
    {
        return self::CANONICAL_ORDER[$sequence] ?? null;
    }
}
