<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_settings', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'meta_image')) {
                $table->string('meta_image')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'meta_copyright')) {
                $table->string('meta_copyright')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'site_name')) {
                $table->string('site_name')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('seo_settings', 'meta_title')) {
                $columns[] = 'meta_title';
            }
            if (Schema::hasColumn('seo_settings', 'meta_description')) {
                $columns[] = 'meta_description';
            }
            if (Schema::hasColumn('seo_settings', 'meta_image')) {
                $columns[] = 'meta_image';
            }
            if (Schema::hasColumn('seo_settings', 'meta_copyright')) {
                $columns[] = 'meta_copyright';
            }
            if (Schema::hasColumn('seo_settings', 'site_name')) {
                $columns[] = 'site_name';
            }

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
