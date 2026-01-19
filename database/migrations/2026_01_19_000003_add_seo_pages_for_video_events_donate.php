<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();
        $pages = [
            'Video' => [
                'seo_title' => 'Thomas Alexander | Video Gallery',
                'seo_description' => 'Watch official videos and performances from Thomas Alexander.',
            ],
            'Events' => [
                'seo_title' => 'Thomas Alexander | Events',
                'seo_description' => 'Upcoming and past events from Thomas Alexander.',
            ],
            'Living Archive Donate' => [
                'seo_title' => 'Support the Living Archive',
                'seo_description' => 'Secure, one-time donation to support the Living Archive.',
            ],
        ];

        foreach ($pages as $name => $data) {
            DB::table('seo_settings')->updateOrInsert(
                ['page_name' => $name],
                array_merge($data, [
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );
        }
    }

    public function down(): void
    {
        DB::table('seo_settings')->whereIn('page_name', [
            'Video',
            'Events',
            'Living Archive Donate',
        ])->delete();
    }
};
