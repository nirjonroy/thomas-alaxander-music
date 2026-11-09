<?php

namespace Database\Seeders;

use App\Models\CompanionPageSetting;
use Illuminate\Database\Seeder;

class CompanionPageSettingStructureSeeder extends Seeder
{
    public function run(): void
    {
        if (! CompanionPageSetting::where('id', 1)->exists()) {
            CompanionPageSetting::create([
                'id' => 1,
                'is_active' => false,
            ]);
        }
    }
}
