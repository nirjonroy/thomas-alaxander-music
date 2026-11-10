<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('epk_pages') && !Schema::hasColumn('epk_pages', 'pdf_file')) {
            Schema::table('epk_pages', function (Blueprint $table) {
                $table->string('pdf_file')->nullable()->after('sections');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('epk_pages') && Schema::hasColumn('epk_pages', 'pdf_file')) {
            Schema::table('epk_pages', function (Blueprint $table) {
                $table->dropColumn('pdf_file');
            });
        }
    }
};
