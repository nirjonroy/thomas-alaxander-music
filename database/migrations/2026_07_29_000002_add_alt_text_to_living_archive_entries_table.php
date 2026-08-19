<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('living_archive_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('living_archive_entries', 'featured_image_alt')) {
                $table->string('featured_image_alt')->nullable()->after('featured_image');
            }

            if (!Schema::hasColumn('living_archive_entries', 'document_image_alt')) {
                $table->string('document_image_alt')->nullable()->after('document_image');
            }

            if (!Schema::hasColumn('living_archive_entries', 'og_image_alt')) {
                $table->string('og_image_alt')->nullable()->after('og_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('living_archive_entries', function (Blueprint $table) {
            if (Schema::hasColumn('living_archive_entries', 'featured_image_alt')) {
                $table->dropColumn('featured_image_alt');
            }

            if (Schema::hasColumn('living_archive_entries', 'document_image_alt')) {
                $table->dropColumn('document_image_alt');
            }

            if (Schema::hasColumn('living_archive_entries', 'og_image_alt')) {
                $table->dropColumn('og_image_alt');
            }
        });
    }
};
