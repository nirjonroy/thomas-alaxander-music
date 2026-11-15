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
        3 => 'Bear',
        4 => 'Raven',
        5 => 'Owl',
        6 => 'Panther',
        7 => 'Deer',
        8 => 'Eagle',
        9 => 'Fox',
        10 => 'Lynx',
        11 => 'Otter',
    ];

    public const COMPANION_COPYRIGHT = '© 2026 Thomas Alexander. All Rights Reserved.';

    public const CLOSING_COPYRIGHT = "© 2026 Thomas Alexander. All Rights Reserved.\nThe Living Archive • Companions Along the Journey • Chief & Elder Wisdom\nNo reproduction or republication without permission.";

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
