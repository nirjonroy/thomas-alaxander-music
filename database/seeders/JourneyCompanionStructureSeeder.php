<?php

namespace Database\Seeders;

use App\Models\JourneyCompanion;
use Illuminate\Database\Seeder;

class JourneyCompanionStructureSeeder extends Seeder
{
    public function run(): void
    {
        $structuralCompanions = [
            ['sequence' => 1, 'name' => 'Wolf', 'slug' => 'wolf'],
            ['sequence' => 2, 'name' => 'Hawk', 'slug' => 'hawk'],
            ['sequence' => 3, 'name' => 'Raven', 'slug' => 'raven'],
            ['sequence' => 4, 'name' => 'Owl', 'slug' => 'owl'],
            ['sequence' => 5, 'name' => 'Eagle', 'slug' => 'eagle'],
            ['sequence' => 6, 'name' => 'Panther', 'slug' => 'panther'],
            ['sequence' => 7, 'name' => 'Bear', 'slug' => 'bear'],
            ['sequence' => 8, 'name' => 'Lynx', 'slug' => 'lynx'],
            ['sequence' => 9, 'name' => 'Deer', 'slug' => 'deer'],
            ['sequence' => 10, 'name' => 'Fox', 'slug' => 'fox'],
            ['sequence' => 11, 'name' => 'Otter', 'slug' => 'otter'],
        ];

        foreach ($structuralCompanions as $item) {
            $existing = JourneyCompanion::where('sequence', $item['sequence'])
                ->orWhere('slug', $item['slug'])
                ->first();

            if ($existing) {
                $existing->update([
                    'sequence' => $item['sequence'],
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                ]);
            } else {
                JourneyCompanion::create([
                    'sequence' => $item['sequence'],
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'is_active' => false,
                ]);
            }
        }
    }
}
