<?php

namespace App\Services;

use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use Illuminate\Support\Collection;

class CompanionPublicationReadinessService
{
    /**
     * Check if all publication conditions are met.
     */
    public function isReadyForPublication(?CompanionPageSetting $setting = null, ?Collection $companions = null): bool
    {
        $report = $this->getReadinessReport($setting, $companions);

        return $report['is_ready'];
    }

    /**
     * Build an exhaustive, read-only readiness report.
     */
    public function getReadinessReport(?CompanionPageSetting $setting = null, ?Collection $companions = null): array
    {
        $setting = $setting ?? CompanionPageSetting::first();
        $companions = $companions ?? JourneyCompanion::ordered()->get();

        $pageActive = (bool) ($setting && $setting->is_active);

        // 1. Validate Canonical Sequence & Identity (Exactly 11)
        $canonicalOrder = JourneyCompanion::CANONICAL_ORDER;
        $totalCompanions = $companions->count();
        $canonicalValid = ($totalCompanions === 11);

        if ($canonicalValid) {
            foreach ($companions as $index => $companion) {
                $expectedSeq = $index + 1;
                $expectedName = $canonicalOrder[$expectedSeq] ?? null;

                if ((int) $companion->sequence !== $expectedSeq || strcasecmp(trim($companion->name), $expectedName) !== 0) {
                    $canonicalValid = false;
                    break;
                }
            }
        }

        // 2. Validate Crests
        $openingCrestAssigned = ! empty($setting?->entrance_crest_image);
        $openingCrestFileExists = $openingCrestAssigned && $this->fileExists($setting->entrance_crest_image);
        $missingOpeningCrest = ! ($openingCrestAssigned && $openingCrestFileExists);

        $closingCrestAssigned = ! empty($setting?->closing_crest_image);
        $closingCrestFileExists = $closingCrestAssigned && $this->fileExists($setting->closing_crest_image);
        $missingClosingCrest = ! ($closingCrestAssigned && $closingCrestFileExists);

        // 3. Validate Required Narrative Fields
        $missingNarrativeFields = [];
        $requiredNarrativeFields = [
            'entrance_title' => 'Entrance Title',
            'opening_teaching' => 'Opening Teaching',
            'regeneration_title' => 'Regeneration Title',
            'witnesses_title' => 'The Witnesses Title',
            'witnesses_intro' => 'The Witnesses Introduction',
            'witness_wind_title' => 'Witness: Wind Title',
            'witness_wind_body' => 'Witness: Wind Body',
            'witness_earth_title' => 'Witness: Earth Title',
            'witness_earth_body' => 'Witness: Earth Body',
            'witness_water_title' => 'Witness: Water Title',
            'witness_water_body' => 'Witness: Water Body',
            'witness_sunlight_title' => 'Witness: Sunlight Title',
            'witness_sunlight_body' => 'Witness: Sunlight Body',
            'witness_night_title' => 'Witness: Night Title',
            'witness_night_body' => 'Witness: Night Body',
            'witness_rest_title' => 'Witness: Rest Title',
            'witness_rest_body' => 'Witness: Rest Body',
            'human_responsibility_title' => 'The Human Responsibility Title',
            'human_responsibility_body' => 'The Human Responsibility Body',
            'continuance_title' => 'Final Rest and Continuance Title',
            'continuance_body' => 'Final Rest and Continuance Body',
            'new_beginning_body' => 'New Beginning Body',
            'closing_text' => 'Closing Passage ("We were never erased...")',
            'final_refrain' => 'Final Refrain ("It is remembrance made living.")',
        ];

        foreach ($requiredNarrativeFields as $field => $label) {
            $value = $setting ? trim((string) ($setting->{$field} ?? '')) : '';
            if ($value === '') {
                $missingNarrativeFields[] = $label;
            }
        }

        // 4. Validate Companions (Artwork, Teachings, Remembrance, Branding, Active)
        $companionChecklists = [];
        $missingArtworksCount = 0;
        $missingTeachingsCount = 0;
        $missingRememberCount = 0;
        $missingBrandingCount = 0;
        $inactiveCompanionsCount = 0;

        foreach ($companions as $companion) {
            $issues = [];

            // Artwork
            $artworkAssigned = ! empty($companion->artwork_image);
            $artworkExists = $artworkAssigned && $this->fileExists($companion->artwork_image);
            if (! $artworkAssigned) {
                $issues[] = 'Artwork missing';
                $missingArtworksCount++;
            } elseif (! $artworkExists) {
                $issues[] = 'Artwork file not found on disk (' . $companion->artwork_image . ')';
                $missingArtworksCount++;
            }

            // Expanded Teaching
            if (trim((string) ($companion->expanded_teaching ?? '')) === '') {
                $issues[] = 'Expanded Teaching missing';
                $missingTeachingsCount++;
            }

            // Remembrance
            if (trim((string) ($companion->remember_teaching ?? '')) === '') {
                $issues[] = 'Remembrance missing';
                $missingRememberCount++;
            }

            // Branding
            if (trim((string) ($companion->branding_info ?? '')) === '') {
                $issues[] = 'Branding Block missing';
                $missingBrandingCount++;
            }

            // Active
            if (! $companion->is_active) {
                $issues[] = 'Inactive status';
                $inactiveCompanionsCount++;
            }

            $companionChecklists[] = [
                'id' => $companion->id,
                'sequence' => $companion->sequence,
                'name' => $companion->name,
                'slug' => $companion->slug,
                'is_active' => (bool) $companion->is_active,
                'issues' => $issues,
                'has_issues' => count($issues) > 0,
                'summary' => count($issues) > 0
                    ? $companion->name . ' — ' . implode('; ', $issues)
                    : $companion->name . ' — Ready',
            ];
        }

        // Global Missing Items
        $globalMissing = [];
        if (! $pageActive) {
            $globalMissing[] = 'Page Setting status is Inactive';
        }
        if (! $canonicalValid) {
            $globalMissing[] = 'Canonical 11 Companion sequence or identity is invalid or incomplete (Count: ' . $totalCompanions . ')';
        }
        if ($missingOpeningCrest) {
            $globalMissing[] = $openingCrestAssigned
                ? 'Full Ceremonial Living Crest (Entrance) file not found on disk'
                : 'Full Ceremonial Living Crest (Entrance) not assigned';
        }
        if ($missingClosingCrest) {
            $globalMissing[] = $closingCrestAssigned
                ? 'Full Ceremonial Living Crest (Closing) file not found on disk'
                : 'Full Ceremonial Living Crest (Closing) not assigned';
        }
        foreach ($missingNarrativeFields as $missingNarrativeField) {
            $globalMissing[] = 'Missing Narrative Text: ' . $missingNarrativeField;
        }

        // Overall Ready
        $isReady = $pageActive
            && $canonicalValid
            && ! $missingOpeningCrest
            && ! $missingClosingCrest
            && empty($missingNarrativeFields)
            && $missingArtworksCount === 0
            && $missingTeachingsCount === 0
            && $missingRememberCount === 0
            && $missingBrandingCount === 0
            && $inactiveCompanionsCount === 0;

        $contentComplete = $canonicalValid
            && ! $missingOpeningCrest
            && ! $missingClosingCrest
            && empty($missingNarrativeFields)
            && $missingArtworksCount === 0
            && $missingTeachingsCount === 0
            && $missingRememberCount === 0
            && $missingBrandingCount === 0;

        $allCompanionsActive = $inactiveCompanionsCount === 0;
        $publicationState = $isReady
            ? 'Published'
            : ($contentComplete && $allCompanionsActive
                ? 'Ready for Publication'
                : ($contentComplete ? 'Content Complete but Inactive' : 'Draft / Incomplete'));

        $missingItems = collect($globalMissing)
            ->merge(collect($companionChecklists)
                ->filter(fn (array $item) => $item['has_issues'])
                ->flatMap(fn (array $item) => collect($item['issues'])->map(fn (string $issue) => [
                    'type' => 'companion',
                    'companion_id' => $item['id'],
                    'sequence' => $item['sequence'],
                    'name' => $item['name'],
                    'issue' => $issue,
                ])))
            ->map(fn ($item) => is_array($item) ? $item : [
                'type' => 'settings',
                'companion_id' => null,
                'sequence' => null,
                'name' => 'Page settings',
                'issue' => $item,
            ])
            ->values()
            ->all();

        return [
            'is_ready' => $isReady,
            'content_complete' => $contentComplete,
            'all_companions_active' => $allCompanionsActive,
            'publication_state' => $publicationState,
            'page_active' => $pageActive,
            'total_companions' => $totalCompanions,
            'canonical_sequence_valid' => $canonicalValid,
            'missing_opening_crest' => $missingOpeningCrest,
            'missing_opening_crest_file' => $openingCrestAssigned && ! $openingCrestFileExists,
            'missing_closing_crest' => $missingClosingCrest,
            'missing_closing_crest_file' => $closingCrestAssigned && ! $closingCrestFileExists,
            'missing_artworks_count' => $missingArtworksCount,
            'missing_teachings_count' => $missingTeachingsCount,
            'missing_remember_count' => $missingRememberCount,
            'missing_branding_count' => $missingBrandingCount,
            'inactive_companions_count' => $inactiveCompanionsCount,
            'missing_narrative_fields' => $missingNarrativeFields,
            'missing_narrative_count' => count($missingNarrativeFields),
            'narrative_complete' => empty($missingNarrativeFields),
            'artworks_complete_count' => max(0, $totalCompanions - $missingArtworksCount),
            'teachings_complete_count' => max(0, $totalCompanions - $missingTeachingsCount),
            'remembrance_complete_count' => max(0, $totalCompanions - $missingRememberCount),
            'branding_complete_count' => max(0, $totalCompanions - $missingBrandingCount),
            'crests_complete_count' => 2 - (int) $missingOpeningCrest - (int) $missingClosingCrest,
            'missing_items' => $missingItems,
            'missing_item_count' => count($missingItems),
            'companion_checklists' => $companionChecklists,
            'global_missing' => $globalMissing,
        ];
    }

    /**
     * Check if a relative public file exists on disk.
     */
    protected function fileExists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $cleanPath = ltrim($path, '/\\');

        return file_exists(public_path($cleanPath));
    }
}
