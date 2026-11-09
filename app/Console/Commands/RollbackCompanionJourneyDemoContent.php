<?php

namespace App\Console\Commands;

use App\Services\CompanionJourneyDemoService;
use Illuminate\Console\Command;
use LogicException;

class RollbackCompanionJourneyDemoContent extends Command
{
    protected $signature = 'companions:rollback-demo-content';

    protected $description = 'Restore local Companion Journey teaching fields from the demo snapshot.';

    public function handle(CompanionJourneyDemoService $demoService): int
    {
        try {
            $result = $demoService->rollback();
        } catch (LogicException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        // Deactivate page and companions back to draft state
        \App\Models\CompanionPageSetting::where('id', 1)->update([
            'is_active' => false,
            'entrance_crest_image' => null,
            'closing_crest_image' => null,
        ]);
        \App\Models\JourneyCompanion::query()->update(['is_active' => false]);

        $this->info('Companion Journey demo rollback complete: ' . $result['restored'] . ' fields restored.');
        $this->info('Page and companions reverted to draft mode.');

        if ($result['skipped'] > 0) {
            $this->warn(
                $result['skipped']
                . ' field(s) were changed after demo seeding and were left untouched. '
                . 'The snapshot remains at storage/app/' . $result['snapshot'] . '.'
            );
        }

        return self::SUCCESS;
    }
}
