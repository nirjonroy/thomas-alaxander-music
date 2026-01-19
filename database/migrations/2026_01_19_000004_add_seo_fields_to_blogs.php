<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'seo_keywords')) {
                $table->text('seo_keywords')->nullable()->after('seo_description');
            }
            if (!Schema::hasColumn('blogs', 'seo_author')) {
                $table->string('seo_author')->nullable()->after('seo_keywords');
            }
            if (!Schema::hasColumn('blogs', 'seo_publisher')) {
                $table->string('seo_publisher')->nullable()->after('seo_author');
            }
            if (!Schema::hasColumn('blogs', 'canonical_url')) {
                $table->string('canonical_url')->nullable()->after('seo_publisher');
            }
            if (!Schema::hasColumn('blogs', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('canonical_url');
            }
            if (!Schema::hasColumn('blogs', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('blogs', 'meta_image')) {
                $table->string('meta_image')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('blogs', 'meta_copyright')) {
                $table->string('meta_copyright')->nullable()->after('meta_image');
            }
            if (!Schema::hasColumn('blogs', 'site_name')) {
                $table->string('site_name')->nullable()->after('meta_copyright');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('blogs', 'seo_keywords')) {
                $columns[] = 'seo_keywords';
            }
            if (Schema::hasColumn('blogs', 'seo_author')) {
                $columns[] = 'seo_author';
            }
            if (Schema::hasColumn('blogs', 'seo_publisher')) {
                $columns[] = 'seo_publisher';
            }
            if (Schema::hasColumn('blogs', 'canonical_url')) {
                $columns[] = 'canonical_url';
            }
            if (Schema::hasColumn('blogs', 'meta_title')) {
                $columns[] = 'meta_title';
            }
            if (Schema::hasColumn('blogs', 'meta_description')) {
                $columns[] = 'meta_description';
            }
            if (Schema::hasColumn('blogs', 'meta_image')) {
                $columns[] = 'meta_image';
            }
            if (Schema::hasColumn('blogs', 'meta_copyright')) {
                $columns[] = 'meta_copyright';
            }
            if (Schema::hasColumn('blogs', 'site_name')) {
                $columns[] = 'site_name';
            }

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
