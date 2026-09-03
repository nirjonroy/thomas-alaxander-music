<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('publishing_media_attachments')) {
            return;
        }

        Schema::create('publishing_media_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publishing_media_id');
            $table->string('attachable_type');
            $table->unsignedBigInteger('attachable_id');
            $table->string('role', 80);
            $table->timestamp('created_at')->nullable();

            $table->unique(['publishing_media_id', 'attachable_type', 'attachable_id', 'role'], 'publishing_media_attachment_unique');
            $table->index(['attachable_type', 'attachable_id', 'role'], 'publishing_media_attachment_attachable_idx');
            $table->index('role');
            $table->foreign('publishing_media_id', 'publishing_media_attachment_media_fk')
                ->references('id')
                ->on('publishing_media')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishing_media_attachments');
    }
};
