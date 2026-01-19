<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('seo_settings', 'seo_author')) {
            Schema::table('seo_settings', function (Blueprint $table) {
                $table->string('seo_author')->nullable()->after('seo_description');
                $table->text('seo_keywords')->nullable()->after('seo_author');
                $table->string('seo_publisher')->nullable()->after('seo_keywords');
                $table->string('canonical_url')->nullable()->after('seo_publisher');
            });
        }

        $now = Carbon::now();
        $pages = [
            'Living Archive' => [
                'seo_title'       => 'The Living Archive | Thomas Alexander',
                'seo_description' => 'Step into the Living Archive ceremonial shop by Thomas Alexander. Follow the phased releases, affirmations, and heritage artefacts that keep the lineage alive.',
            ],
            'Blog Details' => [
                'seo_title'       => 'Thomas Alexander | Blog Insights',
                'seo_description' => 'Detailed stories, ceremony notes, and archival entries from Thomas Alexander.',
            ],
            'Product Details' => [
                'seo_title'       => 'Thomas Alexander | Featured Product',
                'seo_description' => 'Explore ceremonial merchandise and physical releases from Thomas Alexander.',
            ],
        ];

        foreach ($pages as $name => $data) {
            DB::table('seo_settings')->updateOrInsert(
                ['page_name' => $name],
                array_merge($data, [
                    'seo_author'   => 'Thomas Alexander',
                    'seo_keywords' => 'Thomas Alexander, music, living archive, ceremony',
                    'seo_publisher'=> 'Thomas Alexander Official',
                    'canonical_url'=> null,
                    'updated_at'   => $now,
                    'created_at'   => $now,
                ])
            );
        }
    }

    public function down(): void
    {
        DB::table('seo_settings')->whereIn('page_name', [
            'Living Archive','Blog Details','Product Details'
        ])->delete();

        Schema::table('seo_settings', function (Blueprint $table) {
            if (Schema::hasColumn('seo_settings', 'seo_author')) {
                $table->dropColumn(['seo_author','seo_keywords','seo_publisher','canonical_url']);
            }
        });
    }
};
