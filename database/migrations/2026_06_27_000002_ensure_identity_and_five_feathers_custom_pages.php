<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $pages = [
            [
                'page_name' => 'Identity',
                'slug' => 'identity',
                'description' => '<p>This page is reserved for Thomas Alexander\'s ceremonial and executive identity. Full client-approved content will be added here from the admin panel.</p>',
            ],
            [
                'page_name' => 'Five Feathers Lineage Society',
                'slug' => 'five-feathers-lineage-society',
                'description' => '<p>This page is reserved for the Five Feathers Lineage Society. Full client-approved content will be added here from the admin panel after the complete copy is provided.</p>',
            ],
        ];

        foreach ($pages as $page) {
            $existing = DB::table('custom_pages')
                ->where('slug', $page['slug'])
                ->orWhere('page_name', $page['page_name'])
                ->first();

            if ($existing) {
                continue;
            }

            DB::table('custom_pages')->insert([
                'page_name' => $page['page_name'],
                'slug' => $page['slug'],
                'description' => $page['description'],
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $slugs = [
            'identity',
            'five-feathers-lineage-society',
        ];

        foreach ($slugs as $slug) {
            $page = DB::table('custom_pages')->where('slug', $slug)->first();

            if (!$page) {
                continue;
            }

            $plain = trim(strip_tags((string) $page->description));
            if (Str::contains($plain, 'Full client-approved content will be added here')) {
                DB::table('custom_pages')->where('id', $page->id)->delete();
            }
        }
    }
};
