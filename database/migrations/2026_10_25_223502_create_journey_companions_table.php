<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('journey_companions')) {
            return;
        }

        Schema::create('journey_companions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('sequence');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('themes')->nullable();
            $table->string('artwork_image')->nullable();
            $table->string('artwork_image_alt')->nullable();
            $table->longText('expanded_teaching')->nullable();
            $table->longText('remember_teaching')->nullable();
            $table->longText('branding_info')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('sequence');
            $table->index('is_active');
            $table->index(['is_active', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journey_companions');
    }
};
