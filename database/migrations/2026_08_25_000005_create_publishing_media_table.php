<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('publishing_media')) {
            return;
        }

        Schema::create('publishing_media', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('uploaded_by_admin_id')->nullable();
            $table->unsignedBigInteger('token_id')->nullable();
            $table->string('media_type', 40);
            $table->string('purpose', 80)->nullable();
            $table->string('relative_path');
            $table->string('stored_name');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 120);
            $table->string('extension', 12);
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('checksum', 64);
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();

            $table->index('status');
            $table->index('purpose');
            $table->index('checksum');
            $table->index('created_at');
            $table->index('uploaded_by_admin_id');
            $table->index('token_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishing_media');
    }
};
