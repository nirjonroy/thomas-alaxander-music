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
                'living_legacy_meta_title',
                'living_legacy_meta_description',
                'living_legacy_og_image',
                'living_legacy_eyebrow',
                'living_legacy_title',
                'living_legacy_subtitle',
                'living_legacy_hero_image',
                'living_legacy_intro_heading',
                'living_legacy_governance_heading',
                'living_legacy_portrait_image',
                'living_legacy_portrait_image_alt',
                'living_legacy_portrait_heading',
                'living_legacy_identity_heading',
                'living_legacy_heritage_heading',
                'living_legacy_intro_body',
                'living_legacy_governance_body',
                'living_legacy_portrait_body',
                'living_legacy_feather_items',
                'living_legacy_identity_note',
                'living_legacy_heritage_body',
                'living_legacy_closing_text',
            ];

            foreach ($columns as $column) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->text($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'living_legacy_meta_title',
                'living_legacy_meta_description',
                'living_legacy_og_image',
                'living_legacy_eyebrow',
                'living_legacy_title',
                'living_legacy_subtitle',
                'living_legacy_hero_image',
                'living_legacy_intro_heading',
                'living_legacy_intro_body',
                'living_legacy_governance_heading',
                'living_legacy_governance_body',
                'living_legacy_portrait_image',
                'living_legacy_portrait_image_alt',
                'living_legacy_portrait_heading',
                'living_legacy_portrait_body',
                'living_legacy_identity_heading',
                'living_legacy_feather_items',
                'living_legacy_identity_note',
                'living_legacy_heritage_heading',
                'living_legacy_heritage_body',
                'living_legacy_closing_text',
            ];

            $existing = array_filter($columns, fn ($column) => Schema::hasColumn('settings', $column));

            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};
