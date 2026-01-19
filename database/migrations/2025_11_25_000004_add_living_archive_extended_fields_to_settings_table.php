<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $textColumns = [
                'living_crest_body_one',
                'living_crest_body_two',
                'living_crest_body_three',
                'living_crest_mission',
                'living_phases_intro',
            ];

            foreach ($textColumns as $column) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->text($column)->nullable();
                }
            }

            $stringColumns = [
                'living_crest_title',
                'living_crest_secondary_caption',
                'living_crest_primary_image',
                'living_crest_secondary_image',
                'living_contact_phone',
                'living_contact_email',
            ];

            foreach ($stringColumns as $column) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->string($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'living_crest_body_one',
                'living_crest_body_two',
                'living_crest_body_three',
                'living_crest_mission',
                'living_phases_intro',
                'living_crest_title',
                'living_crest_secondary_caption',
                'living_crest_primary_image',
                'living_crest_secondary_image',
                'living_contact_phone',
                'living_contact_email',
            ];

            $table->dropColumn($columns);
        });
    }
};
