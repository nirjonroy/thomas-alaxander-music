<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('companion_page_settings')) {
            return;
        }

        Schema::create('companion_page_settings', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_active')->default(false);
            $table->string('entrance_section_label')->nullable();
            $table->string('entrance_title')->nullable();
            $table->string('entrance_subtitle')->nullable();
            $table->string('entrance_crest_image')->nullable();
            $table->string('entrance_crest_image_alt')->nullable();
            $table->longText('opening_teaching')->nullable();

            $table->string('regeneration_title')->nullable();
            $table->string('regeneration_subtitle')->nullable();
            $table->longText('regeneration_body')->nullable();
            $table->string('regeneration_image')->nullable();
            $table->string('regeneration_image_alt')->nullable();

            $table->string('witnesses_title')->nullable();
            $table->longText('witnesses_intro')->nullable();

            $table->string('witness_wind_title')->nullable();
            $table->longText('witness_wind_body')->nullable();

            $table->string('witness_earth_title')->nullable();
            $table->longText('witness_earth_body')->nullable();

            $table->string('witness_water_title')->nullable();
            $table->longText('witness_water_body')->nullable();

            $table->string('witness_sunlight_title')->nullable();
            $table->longText('witness_sunlight_body')->nullable();

            $table->string('witness_night_title')->nullable();
            $table->longText('witness_night_body')->nullable();

            $table->string('witness_rest_title')->nullable();
            $table->longText('witness_rest_body')->nullable();

            $table->string('human_responsibility_title')->nullable();
            $table->longText('human_responsibility_body')->nullable();

            $table->string('continuance_title')->nullable();
            $table->longText('continuance_body')->nullable();

            $table->string('new_beginning_title')->nullable();
            $table->longText('new_beginning_body')->nullable();

            $table->string('closing_crest_image')->nullable();
            $table->string('closing_crest_image_alt')->nullable();
            $table->longText('closing_text')->nullable();
            $table->text('final_refrain')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_image_alt')->nullable();

            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companion_page_settings');
    }
};
