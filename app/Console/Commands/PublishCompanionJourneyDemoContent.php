<?php

namespace App\Console\Commands;

use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Services\CompanionJourneyDemoService;
use App\Services\CompanionPublicationReadinessService;
use Illuminate\Console\Command;
use LogicException;

class PublishCompanionJourneyDemoContent extends Command
{
    protected $signature = 'companions:publish-demo-content';

    protected $description = 'Populate approved demonstration content and crests, and publish the Companion Journey locally.';

    public function handle(
        CompanionJourneyDemoService $demoService,
        CompanionPublicationReadinessService $readinessService
    ): int {
        $this->info('Starting Companion Journey demo publication...');

        // 1. Seed demo teachings & remembrances
        try {
            $result = $demoService->seed();
            $this->info("Demo teachings: {$result['seeded']} seeded, {$result['preserved']} preserved.");
        } catch (LogicException $e) {
            $this->warn("Demo seeder note: {$e->getMessage()}");
        }

        // 2. Ensure ceremonial crests exist
        $sourceCrest = public_path('uploads/living-archive/living-crest-primary-image-1769364407-69765bb7f2d64.png');
        $entranceCrest = public_path('uploads/companions/living-archive-entrance-crest.png');
        $closingCrest = public_path('uploads/companions/living-archive-closing-crest.png');

        if (file_exists($sourceCrest)) {
            if (! file_exists($entranceCrest)) {
                copy($sourceCrest, $entranceCrest);
            }
            if (! file_exists($closingCrest)) {
                copy($sourceCrest, $closingCrest);
            }
        }

        // 3. Activate all 11 companions
        JourneyCompanion::query()->update(['is_active' => true]);

        // 4. Update page settings with crests and activate
        $setting = CompanionPageSetting::firstOrCreate(['id' => 1]);
        $setting->update([
            'entrance_crest_image' => 'uploads/companions/living-archive-entrance-crest.png',
            'closing_crest_image' => 'uploads/companions/living-archive-closing-crest.png',
            'is_active' => true,
        ]);

        // 5. Check readiness
        $report = $readinessService->getReadinessReport($setting);

        if ($report['is_ready']) {
            $this->info('====================================================');
            $this->info('🎉 COMPANION JOURNEY IS NOW PUBLISHED AND LIVE!');
            $this->info('====================================================');
            $this->line('Public URL:  http://127.0.0.1:8000/living-archive/companions-along-the-journey');
            $this->line('Admin Preview: http://127.0.0.1:8000/admin/companions-journey/preview');
            $this->line('Publication State: ' . $report['publication_state']);
            $this->info('To rollback demo content at any time, run:');
            $this->line('php artisan companions:rollback-demo-content');

            return self::SUCCESS;
        }

        $this->error('Publication blocked: ' . $report['missing_item_count'] . ' item(s) remaining.');
        foreach ($report['missing_items'] as $item) {
            $this->warn(' - ' . ($item['issue'] ?? json_encode($item)));
        }

        return self::FAILURE;
    }
}

