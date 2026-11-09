<?php

namespace Database\Seeders;

use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThomasCompanionContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->importPageContent();
            $this->importCompanionContent();
        });
    }

    private function importPageContent(): void
    {
        $setting = CompanionPageSetting::firstOrCreate(['id' => 1], ['is_active' => false]);

        $settingData = [
            'entrance_section_label' => 'THE LIVING ARCHIVE',
            'entrance_title' => 'COMPANIONS ALONG THE JOURNEY',
            'entrance_subtitle' => 'Chief & Elder Wisdom',
            'opening_teaching' => "The animals do not tell me who I am.\nMy life has done that.\n\nBut sometimes an image gives us another visual language through which to understand what the journey has already taught us.\n\nThe animal does not create the lesson.\n\nThe journey has already begun teaching it.\n\nThe companion simply helps us recognize what may already be there and reminds us of what we are to remember.",
            'regeneration_title' => 'Regeneration',
            'regeneration_subtitle' => 'The Journey Deepens',
            'witnesses_title' => 'The Witnesses',
            'witnesses_intro' => "Hawk. Raven. Owl. Eagle.\n\nThey have seen.\n\nBut witnessing is only the beginning.",
            'witness_wind_title' => 'Wind — The Carrier',
            'witness_wind_body' => 'The wind carries the seed beyond the place of destruction.',
            'witness_earth_title' => 'Earth — The Receiver',
            'witness_earth_body' => "The earth receives what has been carried.\n\nVisible growth may not yet exist, but something may already have begun beneath the surface.",
            'witness_water_title' => 'Water — Memory and Life',
            'witness_water_body' => "Water carries memory onward.\n\nBut water also serves life.\n\nIt nourishes the seed, the roots, the forest, the animals and us.\n\nWater carries memory, but water also carries life.",
            'witness_sunlight_title' => 'Sunlight — Calling Life Forward',
            'witness_sunlight_body' => "The seed survives.\nThe wind carries.\nThe earth receives.\nThe water nourishes.\nThe light calls growth upward.\n\nLife rises.",
            'witness_night_title' => 'Night — The Teaching of Unseen Movement',
            'witness_night_body' => "Night is a time for rest, and rest we must.\n\nBut darkness does not mean that life has stopped.\n\nWhat is unseen is not necessarily still.",
            'witness_rest_title' => 'Rest',
            'witness_rest_body' => "Rest itself has a purpose.\nIt prepares us for what comes next.\nAnd we awaken refreshed to begin where we left off.",
            'human_responsibility_title' => 'The Human Responsibility',
            'human_responsibility_body' => "Heal the wounded.\n\nProtect the innocent.\n\nRemember what caused harm.\n\nCarry forward what has been learned.\n\nRespect what we have been permitted to witness.\n\nDo not destroy what we have merely been privileged to behold.\n\nDo not assume that everything beautiful must belong to us.\n\nPrepare a path for those who follow, even when we ourselves may never walk that path.\n\nOnce we recognize the lesson, we become responsible for what we do with what we have learned.",
            'continuance_title' => 'Final Rest and Continuance',
            'continuance_body' => "Rest itself has purpose.\n\nEventually there comes another kind of rest.\n\nThe final rest.\n\nYet the greater movement of life continues.\n\nThe seed continues.\n\nWater continues flowing.\n\nWind continues carrying.\n\nSunlight reaches another generation.\n\nNight returns.\n\nMorning returns.\n\nAnother generation awakens.\n\nThe purpose continues.",
            'new_beginning_title' => 'New Beginning',
            'new_beginning_body' => "What may appear to us as an ending is not.\nIt is just a new beginning.",
            'closing_text' => "We were never erased.\nWe were replanted.",
            'final_refrain' => 'It is remembrance made living.',
            'is_active' => false,
        ];

        // Explicitly preserve existing image paths if present, do not overwrite with null
        $setting->update($settingData);
    }

    private function importCompanionContent(): void
    {
        $brandingBlock = "THOMAS ALEXANDER\nTHE VOICE\n\nFIVE FEATHERS PUBLISHING\n\nTHE LIVING ARCHIVE\nCOMPANIONS ALONG THE JOURNEY\n\nMUSIC | HERITAGE | STORY | EDUCATION | A LIVING ARCHIVE\n\nHonouring the Past • Inspiring the Present • Leaving a Path for the Future";

        $companionThemes = [
            1 => [
                'name' => 'Wolf',
                'slug' => 'wolf',
                'themes' => 'Leadership • Service • Protection • Responsibility',
            ],
            2 => [
                'name' => 'Hawk',
                'slug' => 'hawk',
                'themes' => 'Vision • Discernment • Foresight • Perspective',
            ],
            3 => [
                'name' => 'Raven',
                'slug' => 'raven',
                'themes' => 'Memory • Voice • Intelligence • Problem Solving • Carrying Forward',
            ],
            4 => [
                'name' => 'Owl',
                'slug' => 'owl',
                'themes' => 'Listening • Reflection • Discernment • Truth',
            ],
            5 => [
                'name' => 'Eagle',
                'slug' => 'eagle',
                'themes' => 'Majesty • Reverence • Perspective • Sacred Responsibility',
            ],
            6 => [
                'name' => 'Panther',
                'slug' => 'panther',
                'themes' => 'Patience • Discernment • Presence • Right Action',
            ],
            7 => [
                'name' => 'Bear',
                'slug' => 'bear',
                'themes' => 'Strength • Protection • Stewardship • Presence',
            ],
            8 => [
                'name' => 'Lynx',
                'slug' => 'lynx',
                'themes' => 'Observation • Solitude • Inner Knowing • Recognition',
            ],
            9 => [
                'name' => 'Deer',
                'slug' => 'deer',
                'themes' => 'Generations • Stewardship • Continuity • Legacy',
            ],
            10 => [
                'name' => 'Fox',
                'slug' => 'fox',
                'themes' => 'Awareness • Adaptability • Another Way • Respectful Witnessing',
            ],
            11 => [
                'name' => 'Otter',
                'slug' => 'otter',
                'themes' => 'Family • Resilience • Protection • Renewal • Joy',
            ],
        ];

        foreach ($companionThemes as $seq => $data) {
            $companion = JourneyCompanion::where('sequence', $seq)->first();

            if (! $companion) {
                continue;
            }

            $companion->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'themes' => $data['themes'],
                'branding_info' => $brandingBlock,
                'is_active' => false,
                // Expanded teaching & remembrance remain NULL until complete approved text is delivered
                'expanded_teaching' => null,
                'remember_teaching' => null,
            ]);
        }
    }
}
