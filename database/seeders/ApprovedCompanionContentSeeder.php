<?php

namespace Database\Seeders;

use App\Models\JourneyCompanion;
use App\Models\CompanionPageSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use LogicException;

class ApprovedCompanionContentSeeder extends Seeder
{
    public function run(): void
    {
        $companions = $this->approvedCompanions();

        DB::transaction(function () use ($companions): void {
            $existing = JourneyCompanion::whereIn('slug', array_keys($companions))
                ->get()
                ->keyBy('slug');

            if ($existing->count() !== count($companions)) {
                throw new LogicException('The approved Companion update requires all eleven canonical slug records.');
            }

            // Move existing records above the active range before setting the
            // new unique sequence values. Records are always resolved by slug.
            $offset = ((int) JourneyCompanion::max('sequence')) + 100;
            JourneyCompanion::whereIn('slug', array_keys($companions))->update([
                'sequence' => DB::raw('sequence + ' . $offset),
            ]);

            foreach ($companions as $slug => $content) {
                JourneyCompanion::where('slug', $slug)->update([
                    'sequence' => $content['sequence'],
                    'name' => $content['name'],
                    'themes' => $content['themes'],
                    'expanded_teaching' => $content['expanded_teaching'],
                    'remember_teaching' => $content['remember_teaching'],
                    'is_active' => false,
                ]);
            }

            // Content installation must not publish the Companion journey.
            CompanionPageSetting::query()->update(['is_active' => false]);
        });
    }

    /**
     * Parses Thomas's approved source directly to retain its exact paragraphs.
     */
    public function approvedCompanions(): array
    {
        $path = base_path('docs/client-handoff/Thomas_Final_Companion_Teachings.txt');
        $source = file_get_contents($path);

        if ($source === false) {
            throw new LogicException('Approved Companion teachings source file is missing.');
        }

        $order = [
            'wolf' => ['sequence' => 1, 'name' => 'Wolf'],
            'hawk' => ['sequence' => 2, 'name' => 'Hawk'],
            'bear' => ['sequence' => 3, 'name' => 'Bear'],
            'raven' => ['sequence' => 4, 'name' => 'Raven'],
            'owl' => ['sequence' => 5, 'name' => 'Owl'],
            'panther' => ['sequence' => 6, 'name' => 'Panther'],
            'deer' => ['sequence' => 7, 'name' => 'Deer'],
            'eagle' => ['sequence' => 8, 'name' => 'Eagle'],
            'fox' => ['sequence' => 9, 'name' => 'Fox'],
            'lynx' => ['sequence' => 10, 'name' => 'Lynx'],
            'otter' => ['sequence' => 11, 'name' => 'Otter'],
        ];
        $headings = implode('|', array_map('strtoupper', array_column($order, 'name')));
        $pattern = '/^THE (' . $headings . ')\R\R(.*?)\R\RExpanded Teaching\R\R(.*?)\R\RWhat We Are Asked to Remember\R\R(.*?)(?=\R\RTHE (?:' . $headings . ')\R|\R\RCOPYRIGHT AND CREST INSTRUCTIONS\R|\z)/ms';

        preg_match_all($pattern, $source, $matches, PREG_SET_ORDER);

        if (count($matches) !== count($order)) {
            throw new LogicException('Unable to parse all eleven approved Companion teachings.');
        }

        $approved = [];
        foreach ($matches as $match) {
            $slug = strtolower($match[1]);
            if (! isset($order[$slug])) {
                throw new LogicException('An unexpected Companion heading was found in the approved source.');
            }

            $approved[$slug] = $order[$slug] + [
                'themes' => trim($match[2]),
                'expanded_teaching' => trim($match[3]),
                'remember_teaching' => trim($match[4]),
            ];
        }

        if (array_keys($approved) !== array_keys($order)) {
            throw new LogicException('Approved Companion headings are not in the required canonical order.');
        }

        return $approved;
    }
}
