<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $stringColumns = [
                'living_handoff_page_name',
                'living_handoff_logo_url',
                'living_handoff_email',
                'living_handoff_phone',
                'living_handoff_social_instagram',
                'living_handoff_social_facebook',
                'living_handoff_social_youtube',
                'living_handoff_address',
                'living_handoff_tagline1',
                'living_handoff_tagline2',
                'living_handoff_tagline3',
                'living_handoff_tagline4',
                'living_handoff_merch_apparel',
                'living_handoff_merch_posters',
                'living_handoff_merch_music',
                'living_handoff_merch_donor',
                'living_handoff_merch_digital',
                'living_handoff_footer_line',
                'living_handoff_palette_primary',
                'living_handoff_palette_secondary',
                'living_handoff_palette_accent',
            ];

            foreach ($stringColumns as $column) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->string($column)->nullable();
                }
            }

            $textColumns = [
                'living_handoff_intro',
                'living_handoff_mission',
                'living_handoff_supporter',
                'living_handoff_coming_soon',
                'living_handoff_visual_hierarchy',
                'living_handoff_background_guide',
            ];

            foreach ($textColumns as $column) {
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
                'living_handoff_page_name',
                'living_handoff_logo_url',
                'living_handoff_email',
                'living_handoff_phone',
                'living_handoff_social_instagram',
                'living_handoff_social_facebook',
                'living_handoff_social_youtube',
                'living_handoff_address',
                'living_handoff_tagline1',
                'living_handoff_tagline2',
                'living_handoff_tagline3',
                'living_handoff_tagline4',
                'living_handoff_merch_apparel',
                'living_handoff_merch_posters',
                'living_handoff_merch_music',
                'living_handoff_merch_donor',
                'living_handoff_merch_digital',
                'living_handoff_footer_line',
                'living_handoff_palette_primary',
                'living_handoff_palette_secondary',
                'living_handoff_palette_accent',
                'living_handoff_intro',
                'living_handoff_mission',
                'living_handoff_supporter',
                'living_handoff_coming_soon',
                'living_handoff_visual_hierarchy',
                'living_handoff_background_guide',
            ];

            $table->dropColumn($columns);
        });
    }
};
