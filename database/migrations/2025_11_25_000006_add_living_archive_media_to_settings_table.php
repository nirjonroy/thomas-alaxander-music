<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'living_archive_logo_image',
                'living_archive_hero_image',
            ];

            foreach ($columns as $column) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->string($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'living_archive_logo_image',
                'living_archive_hero_image',
            ]);
        });
    }
};
