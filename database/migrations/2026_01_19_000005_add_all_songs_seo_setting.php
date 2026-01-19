<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();
        DB::table('seo_settings')->updateOrInsert(
            ['page_name' => 'All Songs'],
            [
                'seo_title' => 'All Songs | Thomas Alexander',
                'seo_description' => 'Browse the full catalogue of songs and releases.',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        DB::table('seo_settings')->where('page_name', 'All Songs')->delete();
    }
};
