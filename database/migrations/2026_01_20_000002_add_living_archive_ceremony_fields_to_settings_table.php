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
                'living_archive_affirmation',
                'living_archive_primary_cta_label',
                'living_archive_primary_cta_url',
                'living_archive_secondary_cta_label',
                'living_archive_secondary_cta_url',
                'living_lineage_title',
                'living_lineage_endline',
                'living_crests_title',
                'living_youth_crest_title',
                'living_youth_crest_declaration',
                'living_youth_crest_image',
                'living_keeper_crest_title',
                'living_keeper_crest_declaration',
                'living_keeper_crest_image',
                'living_witness_crest_title',
                'living_witness_crest_declaration',
                'living_witness_crest_image',
                'living_qr_crest_image',
                'living_pathway_title',
                'living_pathway_step1_title',
                'living_pathway_step2_title',
                'living_pathway_step3_title',
                'living_media_merch_title',
                'living_media_merch_card1_title',
                'living_media_merch_card1_cta_label',
                'living_media_merch_card1_cta_url',
                'living_media_merch_card2_title',
                'living_media_merch_card2_cta_label',
                'living_media_merch_card2_cta_url',
                'living_qr_title',
                'living_qr_cta_label',
                'living_qr_cta_url',
                'living_contact_title',
                'living_contact_training_title',
                'living_contact_training_cta_label',
                'living_contact_training_cta_url',
                'living_contact_events_title',
                'living_contact_events_cta_label',
                'living_contact_events_cta_url',
                'living_contact_general_title',
                'living_contact_general_cta_label',
                'living_contact_general_cta_url',
                'living_contact_support_cta_label',
                'living_contact_support_cta_url',
                'living_certification_title',
            ];

            foreach ($stringColumns as $column) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->text($column)->nullable();
                }
            }

            $textColumns = [
                'living_lineage_intro',
                'living_lineage_tree_text',
                'living_lineage_clan_text',
                'living_lineage_shields_text',
                'living_lineage_feathers_text',
                'living_crests_intro',
                'living_youth_crest_body',
                'living_keeper_crest_body',
                'living_witness_crest_body',
                'living_pathway_intro',
                'living_pathway_step1_body',
                'living_pathway_step2_body',
                'living_pathway_step3_body',
                'living_media_merch_intro',
                'living_media_merch_card1_body',
                'living_media_merch_card2_body',
                'living_qr_intro',
                'living_contact_intro',
                'living_contact_training_body',
                'living_contact_events_body',
                'living_contact_general_body',
                'living_certification_intro',
                'living_certification_text',
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
                'living_archive_affirmation',
                'living_archive_primary_cta_label',
                'living_archive_primary_cta_url',
                'living_archive_secondary_cta_label',
                'living_archive_secondary_cta_url',
                'living_lineage_title',
                'living_lineage_intro',
                'living_lineage_tree_text',
                'living_lineage_clan_text',
                'living_lineage_shields_text',
                'living_lineage_feathers_text',
                'living_lineage_endline',
                'living_crests_title',
                'living_crests_intro',
                'living_youth_crest_title',
                'living_youth_crest_declaration',
                'living_youth_crest_body',
                'living_youth_crest_image',
                'living_keeper_crest_title',
                'living_keeper_crest_declaration',
                'living_keeper_crest_body',
                'living_keeper_crest_image',
                'living_witness_crest_title',
                'living_witness_crest_declaration',
                'living_witness_crest_body',
                'living_witness_crest_image',
                'living_qr_crest_image',
                'living_pathway_title',
                'living_pathway_intro',
                'living_pathway_step1_title',
                'living_pathway_step1_body',
                'living_pathway_step2_title',
                'living_pathway_step2_body',
                'living_pathway_step3_title',
                'living_pathway_step3_body',
                'living_media_merch_title',
                'living_media_merch_intro',
                'living_media_merch_card1_title',
                'living_media_merch_card1_body',
                'living_media_merch_card1_cta_label',
                'living_media_merch_card1_cta_url',
                'living_media_merch_card2_title',
                'living_media_merch_card2_body',
                'living_media_merch_card2_cta_label',
                'living_media_merch_card2_cta_url',
                'living_qr_title',
                'living_qr_intro',
                'living_qr_cta_label',
                'living_qr_cta_url',
                'living_contact_title',
                'living_contact_intro',
                'living_contact_training_title',
                'living_contact_training_body',
                'living_contact_training_cta_label',
                'living_contact_training_cta_url',
                'living_contact_events_title',
                'living_contact_events_body',
                'living_contact_events_cta_label',
                'living_contact_events_cta_url',
                'living_contact_general_title',
                'living_contact_general_body',
                'living_contact_general_cta_label',
                'living_contact_general_cta_url',
                'living_contact_support_cta_label',
                'living_contact_support_cta_url',
                'living_certification_title',
                'living_certification_intro',
                'living_certification_text',
            ];

            $table->dropColumn($columns);
        });
    }
};
