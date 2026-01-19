<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_settings', 'seo_author')) {
                $table->string('seo_author')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'seo_keywords')) {
                $table->text('seo_keywords')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'seo_publisher')) {
                $table->string('seo_publisher')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'canonical_url')) {
                $table->string('canonical_url')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('seo_settings', 'seo_author')) {
                $columns[] = 'seo_author';
            }
            if (Schema::hasColumn('seo_settings', 'seo_keywords')) {
                $columns[] = 'seo_keywords';
            }
            if (Schema::hasColumn('seo_settings', 'seo_publisher')) {
                $columns[] = 'seo_publisher';
            }
            if (Schema::hasColumn('seo_settings', 'canonical_url')) {
                $columns[] = 'canonical_url';
            }

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
