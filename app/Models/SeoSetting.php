<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    public static function forPage(array $names, ?string $fallbackLike = null, ?string $fallbackNotLike = null): ?self
    {
        $query = static::query();
        if (!empty($names)) {
            $query->whereIn('page_name', $names);
        }
        $setting = $query->first();

        if (!$setting && $fallbackLike) {
            $fallbackQuery = static::where('page_name', 'like', $fallbackLike);
            if ($fallbackNotLike) {
                $fallbackQuery->where('page_name', 'not like', $fallbackNotLike);
            }
            $setting = $fallbackQuery->first();
        }

        return $setting;
    }
}
