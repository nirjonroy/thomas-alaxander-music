<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'dual_identity_enabled')) {
                $table->boolean('dual_identity_enabled')->nullable();
            }

            $stringColumns = [
                'dual_identity_kicker',
                'dual_identity_title',
                'ceremonial_identity_image',
                'ceremonial_identity_label',
                'ceremonial_identity_title',
                'ceremonial_identity_subtitle',
                'dual_identity_divider_color',
                'executive_identity_image',
                'executive_identity_label',
                'executive_identity_title',
                'executive_identity_subtitle',
                'dual_identity_summary_bar',
            ];

            foreach ($stringColumns as $column) {
                if (!Schema::hasColumn('settings', $column)) {
                    $table->text($column)->nullable();
                }
            }

            $textColumns = [
                'dual_identity_intro',
                'ceremonial_identity_text',
                'executive_identity_text',
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
                'dual_identity_enabled',
                'dual_identity_kicker',
                'dual_identity_title',
                'dual_identity_intro',
                'ceremonial_identity_image',
                'ceremonial_identity_label',
                'ceremonial_identity_title',
                'ceremonial_identity_subtitle',
                'ceremonial_identity_text',
                'dual_identity_divider_color',
                'executive_identity_image',
                'executive_identity_label',
                'executive_identity_title',
                'executive_identity_subtitle',
                'executive_identity_text',
                'dual_identity_summary_bar',
            ];

            $table->dropColumn($columns);
        });
    }
};
