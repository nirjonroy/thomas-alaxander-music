<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('publishing_audit_logs')) {
            return;
        }

        Schema::create('publishing_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('token_id')->nullable();
            $table->string('action');
            $table->string('resource_type')->nullable();
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_id')->nullable();
            $table->string('idempotency_key')->nullable();
            $table->json('changed_fields')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['resource_type', 'resource_id']);
            $table->index('admin_id');
            $table->index('token_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishing_audit_logs');
    }
};
