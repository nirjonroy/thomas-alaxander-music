<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('events', 'description')) {
                $table->longText('description')->nullable()->after('ticket_price');
            }
            if (!Schema::hasColumn('events', 'seo_title')) {
                $table->string('seo_title')->nullable()->after('status');
            }
            if (!Schema::hasColumn('events', 'seo_description')) {
                $table->text('seo_description')->nullable()->after('seo_title');
            }
            if (!Schema::hasColumn('events', 'seo_keywords')) {
                $table->text('seo_keywords')->nullable()->after('seo_description');
            }
            if (!Schema::hasColumn('events', 'seo_author')) {
                $table->string('seo_author')->nullable()->after('seo_keywords');
            }
            if (!Schema::hasColumn('events', 'seo_publisher')) {
                $table->string('seo_publisher')->nullable()->after('seo_author');
            }
            if (!Schema::hasColumn('events', 'canonical_url')) {
                $table->string('canonical_url')->nullable()->after('seo_publisher');
            }
            if (!Schema::hasColumn('events', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('canonical_url');
            }
            if (!Schema::hasColumn('events', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('events', 'meta_image')) {
                $table->string('meta_image')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('events', 'meta_copyright')) {
                $table->string('meta_copyright')->nullable()->after('meta_image');
            }
            if (!Schema::hasColumn('events', 'site_name')) {
                $table->string('site_name')->nullable()->after('meta_copyright');
            }
        });

        $usedSlugs = [];
        $events = DB::table('events')->select('id', 'name', 'slug')->orderBy('id')->get();

        foreach ($events as $event) {
            if (!empty($event->slug)) {
                $usedSlugs[$event->slug] = true;
                continue;
            }

            $baseSlug = Str::slug($event->name ?: 'event-'.$event->id) ?: 'event-'.$event->id;
            $slug = $baseSlug;
            $suffix = 2;

            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }

            $usedSlugs[$slug] = true;
            DB::table('events')->where('id', $event->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columns = [];

            foreach ([
                'slug',
                'description',
                'seo_title',
                'seo_description',
                'seo_keywords',
                'seo_author',
                'seo_publisher',
                'canonical_url',
                'meta_title',
                'meta_description',
                'meta_image',
                'meta_copyright',
                'site_name',
            ] as $column) {
                if (Schema::hasColumn('events', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
