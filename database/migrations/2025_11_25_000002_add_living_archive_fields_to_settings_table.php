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
                'living_archive_title' => 'string',
                'living_archive_subtitle' => 'string',
                'living_archive_intro' => 'text',
                'living_archive_qr_text' => 'text',
                'living_ritual_before' => 'text',
                'living_ritual_during' => 'text',
                'living_ritual_after' => 'text',
            ];

            foreach ($columns as $column => $type) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->{$type}($column)->nullable();
                }
            }

            for ($i = 1; $i <= 4; $i++) {
                $titleColumn = "living_phase{$i}_title";
                $affirmationColumn = "living_phase{$i}_affirmation";

                if (!Schema::hasColumn('settings', $titleColumn)) {
                    $table->string($titleColumn)->nullable();
                }

                if (!Schema::hasColumn('settings', $affirmationColumn)) {
                    $table->text($affirmationColumn)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'living_archive_title',
                'living_archive_subtitle',
                'living_archive_intro',
                'living_archive_qr_text',
                'living_ritual_before',
                'living_ritual_during',
                'living_ritual_after',
            ];

            for ($i = 1; $i <= 4; $i++) {
                $columns[] = "living_phase{$i}_title";
                $columns[] = "living_phase{$i}_affirmation";
            }

            $table->dropColumn($columns);
        });
    }
};
