<?php

namespace Database\Seeders;

use App\Models\JourneyCompanion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JourneyCompanionStructureSeeder extends Seeder
{
    public function run(): void
    {
        $structuralCompanions = [
            ['sequence' => 1, 'name' => 'Wolf', 'slug' => 'wolf'],
            ['sequence' => 2, 'name' => 'Hawk', 'slug' => 'hawk'],
            ['sequence' => 3, 'name' => 'Bear', 'slug' => 'bear'],
            ['sequence' => 4, 'name' => 'Raven', 'slug' => 'raven'],
            ['sequence' => 5, 'name' => 'Owl', 'slug' => 'owl'],
            ['sequence' => 6, 'name' => 'Panther', 'slug' => 'panther'],
            ['sequence' => 7, 'name' => 'Deer', 'slug' => 'deer'],
            ['sequence' => 8, 'name' => 'Eagle', 'slug' => 'eagle'],
            ['sequence' => 9, 'name' => 'Fox', 'slug' => 'fox'],
            ['sequence' => 10, 'name' => 'Lynx', 'slug' => 'lynx'],
            ['sequence' => 11, 'name' => 'Otter', 'slug' => 'otter'],
        ];

        DB::transaction(function () use ($structuralCompanions): void {
            foreach ($structuralCompanions as $item) {
                $existing = JourneyCompanion::where('slug', $item['slug'])->first();

                if (! $existing) {
                    JourneyCompanion::create([
                        'sequence' => $item['sequence'] + 100,
                        'name' => $item['name'],
                        'slug' => $item['slug'],
                        'is_active' => false,
                    ]);
                }
            }

            $slugs = collect($structuralCompanions)->pluck('slug');
            $offset = ((int) JourneyCompanion::max('sequence')) + 100;
            JourneyCompanion::whereIn('slug', $slugs)->update([
                'sequence' => DB::raw('sequence + ' . $offset),
            ]);

            foreach ($structuralCompanions as $item) {
                JourneyCompanion::where('slug', $item['slug'])->update([
                    'sequence' => $item['sequence'],
                    'name' => $item['name'],
                ]);
            }
        });
    }
}
