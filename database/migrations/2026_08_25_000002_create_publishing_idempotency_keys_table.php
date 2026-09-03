<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('publishing_idempotency_keys')) {
            return;
        }

        Schema::create('publishing_idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191);
            $table->string('actor_type')->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->unsignedBigInteger('token_id')->nullable();
            $table->string('method', 12);
            $table->string('path');
            $table->string('request_hash', 64);
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->json('response_body')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique('key');
            $table->index('expires_at');
            $table->index(['actor_type', 'actor_id']);
            $table->index('token_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishing_idempotency_keys');
    }
};
