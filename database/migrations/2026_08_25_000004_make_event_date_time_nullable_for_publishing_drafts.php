<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE events MODIFY date DATE NULL');
            DB::statement('ALTER TABLE events MODIFY time TIME NULL');

            return;
        }

        if ($driver === 'sqlite') {
            Schema::table('events', function ($table) {
                $table->date('date')->nullable()->change();
                $table->time('time')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        if (DB::table('events')->whereNull('date')->orWhereNull('time')->exists()) {
            throw new \RuntimeException('Cannot make events.date and events.time NOT NULL while draft events contain null values.');
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE events MODIFY date DATE NOT NULL');
            DB::statement('ALTER TABLE events MODIFY time TIME NOT NULL');

            return;
        }

        if ($driver === 'sqlite') {
            Schema::table('events', function ($table) {
                $table->date('date')->nullable(false)->change();
                $table->time('time')->nullable(false)->change();
            });
        }
    }
};
