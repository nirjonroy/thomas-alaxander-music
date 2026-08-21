<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('epk_pages')) {
            return;
        }

        Schema::create('epk_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_image_alt')->nullable();
            $table->string('gold_feather_image')->nullable();
            $table->string('gold_feather_image_alt')->nullable();
            $table->longText('overview_content')->nullable();
            $table->json('sections')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('audio_title')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_title')->nullable();
            $table->string('booking_email')->nullable();
            $table->boolean('status')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_image_alt')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('epk_pages');
    }
};
