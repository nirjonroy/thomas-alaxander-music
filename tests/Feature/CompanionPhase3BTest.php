<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use Database\Seeders\ThomasCompanionContentSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CompanionPhase3BTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => ThomasCompanionContentSeeder::class]);
    }

    private function actingAsAdmin(): Admin
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'phase3b-admin@example.com'],
            [
                'name' => 'Phase 3B Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    public function test_01_exactly_11_companions_remain(): void
    {
        $this->assertSame(11, JourneyCompanion::count());
    }

    public function test_02_original_sequence_and_names_are_preserved(): void
    {
        $expected = [
            1 => ['Wolf', 'wolf'],
            2 => ['Hawk', 'hawk'],
            3 => ['Raven', 'raven'],
            4 => ['Owl', 'owl'],
            5 => ['Eagle', 'eagle'],
            6 => ['Panther', 'panther'],
            7 => ['Bear', 'bear'],
            8 => ['Lynx', 'lynx'],
            9 => ['Deer', 'deer'],
            10 => ['Fox', 'fox'],
            11 => ['Otter', 'otter'],
        ];

        $companions = JourneyCompanion::ordered()->get();

        $this->assertCount(11, $companions);

        foreach ($companions as $companion) {
            $seq = $companion->sequence;
            $this->assertArrayHasKey($seq, $expected);
            $this->assertSame($expected[$seq][0], $companion->name);
            $this->assertSame($expected[$seq][1], $companion->slug);
        }
    }

    public function test_03_companion_themes_match_thomas_source(): void
    {
        $expectedThemes = [
            1 => 'Leadership • Service • Protection • Responsibility',
            2 => 'Vision • Discernment • Foresight • Perspective',
            3 => 'Memory • Voice • Intelligence • Problem Solving • Carrying Forward',
            4 => 'Listening • Reflection • Discernment • Truth',
            5 => 'Majesty • Reverence • Perspective • Sacred Responsibility',
            6 => 'Patience • Discernment • Presence • Right Action',
            7 => 'Strength • Protection • Stewardship • Presence',
            8 => 'Observation • Solitude • Inner Knowing • Recognition',
            9 => 'Generations • Stewardship • Continuity • Legacy',
            10 => 'Awareness • Adaptability • Another Way • Respectful Witnessing',
            11 => 'Family • Resilience • Protection • Renewal • Joy',
        ];

        foreach ($expectedThemes as $seq => $theme) {
            $companion = JourneyCompanion::where('sequence', $seq)->firstOrFail();
            $this->assertSame($theme, $companion->themes);
        }
    }

    public function test_04_missing_expanded_teachings_and_remembrance_remain_null(): void
    {
        $companions = JourneyCompanion::all();

        foreach ($companions as $companion) {
            $this->assertNull(
                $companion->expanded_teaching,
                "Expanded teaching for {$companion->name} must remain null until complete approved text is provided."
            );
            $this->assertNull(
                $companion->remember_teaching,
                "Remember teaching for {$companion->name} must remain null until complete approved text is provided."
            );
        }
    }

    public function test_05_branding_block_is_preserved_exactly_for_all_companions(): void
    {
        $expectedBranding = "THOMAS ALEXANDER\nTHE VOICE\n\nFIVE FEATHERS PUBLISHING\n\nTHE LIVING ARCHIVE\nCOMPANIONS ALONG THE JOURNEY\n\nMUSIC | HERITAGE | STORY | EDUCATION | A LIVING ARCHIVE\n\nHonouring the Past • Inspiring the Present • Leaving a Path for the Future";

        $companions = JourneyCompanion::all();

        foreach ($companions as $companion) {
            $this->assertSame($expectedBranding, $companion->branding_info);
        }
    }

    public function test_06_all_records_remain_inactive(): void
    {
        foreach (JourneyCompanion::all() as $companion) {
            $this->assertFalse((bool) $companion->is_active, "Companion {$companion->name} must remain inactive.");
        }

        $setting = CompanionPageSetting::find(1);
        $this->assertFalse((bool) $setting->is_active, "Page settings must remain inactive.");
    }

    public function test_07_page_settings_text_matches_thomas_source(): void
    {
        $setting = CompanionPageSetting::find(1);

        $this->assertSame('THE LIVING ARCHIVE', $setting->entrance_section_label);
        $this->assertSame('COMPANIONS ALONG THE JOURNEY', $setting->entrance_title);
        $this->assertSame('Chief & Elder Wisdom', $setting->entrance_subtitle);

        $this->assertStringContainsString('The animals do not tell me who I am.', $setting->opening_teaching);
        $this->assertStringContainsString('The companion simply helps us recognize what may already be there', $setting->opening_teaching);

        $this->assertSame('Regeneration', $setting->regeneration_title);
        $this->assertSame('The Journey Deepens', $setting->regeneration_subtitle);

        $this->assertSame('The Witnesses', $setting->witnesses_title);
        $this->assertStringContainsString('Hawk. Raven. Owl. Eagle.', $setting->witnesses_intro);

        $this->assertSame('Wind — The Carrier', $setting->witness_wind_title);
        $this->assertSame('The wind carries the seed beyond the place of destruction.', $setting->witness_wind_body);

        $this->assertSame('Earth — The Receiver', $setting->witness_earth_title);
        $this->assertStringContainsString('The earth receives what has been carried.', $setting->witness_earth_body);

        $this->assertSame('Water — Memory and Life', $setting->witness_water_title);
        $this->assertStringContainsString('Water carries memory onward.', $setting->witness_water_body);

        $this->assertSame('Sunlight — Calling Life Forward', $setting->witness_sunlight_title);
        $this->assertStringContainsString('The seed survives.', $setting->witness_sunlight_body);

        $this->assertSame('Night — The Teaching of Unseen Movement', $setting->witness_night_title);
        $this->assertStringContainsString('Night is a time for rest, and rest we must.', $setting->witness_night_body);

        $this->assertSame('Rest', $setting->witness_rest_title);
        $this->assertStringContainsString('Rest itself has a purpose.', $setting->witness_rest_body);

        $this->assertSame('The Human Responsibility', $setting->human_responsibility_title);
        $this->assertStringContainsString('Heal the wounded.', $setting->human_responsibility_body);
        $this->assertStringContainsString('Once we recognize the lesson, we become responsible', $setting->human_responsibility_body);

        $this->assertSame('Final Rest and Continuance', $setting->continuance_title);
        $this->assertStringContainsString('The final rest.', $setting->continuance_body);

        $this->assertSame('New Beginning', $setting->new_beginning_title);
        $this->assertSame("What may appear to us as an ending is not.\nIt is just a new beginning.", $setting->new_beginning_body);

        $this->assertSame("We were never erased.\nWe were replanted.", $setting->closing_text);
        $this->assertSame('It is remembrance made living.', $setting->final_refrain);
    }

    public function test_08_importer_can_safely_run_twice_without_duplicates_or_drift(): void
    {
        Artisan::call('db:seed', ['--class' => ThomasCompanionContentSeeder::class]);
        Artisan::call('db:seed', ['--class' => ThomasCompanionContentSeeder::class]);

        $this->assertSame(11, JourneyCompanion::count());
        $this->assertSame(1, CompanionPageSetting::count());
        $this->assertFalse((bool) CompanionPageSetting::find(1)->is_active);
    }

    public function test_09_no_image_paths_are_overwritten_by_content_import(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $wolf->update(['artwork_image' => 'uploads/companions/custom-wolf.png']);

        $setting = CompanionPageSetting::find(1);
        $setting->update([
            'entrance_crest_image' => 'uploads/companions/entrance.png',
            'closing_crest_image' => 'uploads/companions/closing.png',
        ]);

        Artisan::call('db:seed', ['--class' => ThomasCompanionContentSeeder::class]);

        $wolf->refresh();
        $setting->refresh();

        $this->assertSame('uploads/companions/custom-wolf.png', $wolf->artwork_image);
        $this->assertSame('uploads/companions/entrance.png', $setting->entrance_crest_image);
        $this->assertSame('uploads/companions/closing.png', $setting->closing_crest_image);
    }

    public function test_10_admin_artwork_upload_and_preview_remains_functional(): void
    {
        $this->actingAsAdmin();

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $originalWolfPath = $wolf->artwork_image;
        $originalWolfBytes = ($originalWolfPath && file_exists(public_path($originalWolfPath)))
            ? file_get_contents(public_path($originalWolfPath))
            : null;

        try {
            $validPng = UploadedFile::fake()->createWithContent(
                'wolf.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==')
            );

            $response = $this->put(route('admin.companions-journey.update', $wolf->id), [
                'artwork_image' => $validPng,
                'artwork_image_alt' => 'Wolf Alt Test',
                'themes' => $wolf->themes,
            ]);

            $response->assertRedirect(route('admin.companions-journey.index'));

            $wolf->refresh();
            $this->assertNotNull($wolf->artwork_image);
            $this->assertFileExists(public_path($wolf->artwork_image));
            $this->assertSame('Wolf Alt Test', $wolf->artwork_image_alt);

            // Verify edit view renders image preview correctly
            $viewResponse = $this->get(route('admin.companions-journey.edit', $wolf->id));
            $viewResponse->assertOk();
            $viewResponse->assertSee($wolf->artwork_image);

            if ($wolf->artwork_image && $wolf->artwork_image !== $originalWolfPath && file_exists(public_path($wolf->artwork_image))) {
                unlink(public_path($wolf->artwork_image));
            }
        } finally {
            if ($originalWolfPath && $originalWolfBytes !== null) {
                file_put_contents(public_path($originalWolfPath), $originalWolfBytes);
            }
        }
    }
}
