<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_living_archive')) {
                $table->boolean('is_living_archive')
                    ->default(false)
                    ->after('new_product');
            }

            if (!Schema::hasColumn('products', 'living_archive_phase')) {
                $table->string('living_archive_phase', 40)
                    ->nullable()
                    ->after('is_living_archive');
            }

            if (!Schema::hasColumn('products', 'living_archive_feather')) {
                $table->string('living_archive_feather', 120)
                    ->nullable()
                    ->after('living_archive_phase');
            }

            if (!Schema::hasColumn('products', 'living_archive_affirmation')) {
                $table->string('living_archive_affirmation', 255)
                    ->nullable()
                    ->after('living_archive_feather');
            }

            if (!Schema::hasColumn('products', 'living_archive_story')) {
                $table->text('living_archive_story')
                    ->nullable()
                    ->after('living_archive_affirmation');
            }

            if (!Schema::hasColumn('products', 'living_archive_qr_caption')) {
                $table->string('living_archive_qr_caption', 120)
                    ->nullable()
                    ->after('living_archive_story');
            }

            if (!Schema::hasColumn('products', 'living_archive_sort')) {
                $table->unsignedInteger('living_archive_sort')
                    ->nullable()
                    ->after('living_archive_qr_caption');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_living_archive',
                'living_archive_phase',
                'living_archive_feather',
                'living_archive_affirmation',
                'living_archive_story',
                'living_archive_qr_caption',
                'living_archive_sort',
            ]);
        });
    }
};
