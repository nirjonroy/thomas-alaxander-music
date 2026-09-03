<?php

namespace App\Services\Publishing;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublishingSlugService
{
    public function uniqueSlug(string $table, string $title, ?string $requestedSlug = null, ?int $ignoreId = null, string $column = 'slug'): string
    {
        $base = Str::slug($requestedSlug ?: $title);
        $base = $base !== '' ? $base : 'item';
        $slug = $base;
        $suffix = 2;

        while ($this->exists($table, $column, $slug, $ignoreId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function exists(string $table, string $column, string $slug, ?int $ignoreId): bool
    {
        $query = DB::table($table)->where($column, $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
