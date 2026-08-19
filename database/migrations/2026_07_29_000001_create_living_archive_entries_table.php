<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('living_archive_entries')) {
            return;
        }

        Schema::create('living_archive_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('living_archive_entries')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('section_label')->nullable();
            $table->text('teaser')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('document_image')->nullable();
            $table->string('document_caption')->nullable();
            $table->string('page_type', 80)->default('archive_page');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['parent_id', 'sort_order']);
            $table->index(['status', 'published_at']);
            $table->index('page_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('living_archive_entries');
    }
};
