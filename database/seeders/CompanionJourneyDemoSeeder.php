<?php

namespace Database\Seeders;

use App\Services\CompanionJourneyDemoService;
use Illuminate\Database\Seeder;

class CompanionJourneyDemoSeeder extends Seeder
{
    public function run(): void
    {
        $result = app(CompanionJourneyDemoService::class)->seed();

        $this->command?->info(
            'Companion Journey demo seeding complete: '
            . $result['seeded'] . ' fields populated, '
            . $result['preserved'] . ' existing fields preserved.'
        );
    }
}
