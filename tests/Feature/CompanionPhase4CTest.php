<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Services\CompanionPublicationReadinessService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompanionPhase4CTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        $testDir = public_path('uploads/companions/test-fixtures');
        if (file_exists($testDir)) {
            $files = glob($testDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($testDir);
        }

        parent::tearDown();
    }

    private function actingAsAdmin(): Admin
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'phase4c-admin@example.com'],
            [
                'name' => 'Phase 4C Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    /**
     * Seeds complete, valid temporary fixtures to satisfy all publication conditions.
     * Note: regeneration_body is intentionally NULL to verify that it is optional.
     */
    private function satisfyPublicationRequirements(): array
    {
        $testDir = public_path('uploads/companions/test-fixtures');
        if (! file_exists($testDir)) {
            mkdir($testDir, 0755, true);
        }
        $crestPath = 'uploads/companions/test-fixtures/crest.png';
        $artworkPath = 'uploads/companions/test-fixtures/art.png';
        file_put_contents(public_path($crestPath), 'dummy-crest-binary');
        file_put_contents(public_path($artworkPath), 'dummy-art-binary');

        CompanionPageSetting::updateOrCreate(
            ['id' => 1],
            [
                'is_active' => true,
                'entrance_section_label' => 'THE LIVING ARCHIVE',
                'entrance_title' => 'COMPANIONS ALONG THE JOURNEY',
                'entrance_subtitle' => 'Chief & Elder Wisdom',
                'entrance_crest_image' => $crestPath,
                'closing_crest_image' => $crestPath,
                'opening_teaching' => "The animals do not tell me who I am.\nMy life has done that.",
                'regeneration_title' => 'Regeneration',
                'regeneration_subtitle' => 'The Journey Deepens',
                'regeneration_body' => null, // Intentionally NULL: Regeneration body is optional
                'witnesses_title' => 'The Witnesses',
                'witnesses_intro' => 'Witnesses introductory text.',
                'witness_wind_title' => 'Wind — The Carrier',
                'witness_wind_body' => 'The wind carries the seed.',
                'witness_earth_title' => 'Earth — The Receiver',
                'witness_earth_body' => 'The earth receives what has been carried.',
                'witness_water_title' => 'Water — Memory and Life',
                'witness_water_body' => 'Water carries memory onward.',
                'witness_sunlight_title' => 'Sunlight — Calling Life Forward',
                'witness_sunlight_body' => 'The light calls growth upward.',
                'witness_night_title' => 'Night — The Teaching of Unseen Movement',
                'witness_night_body' => 'What is unseen is not necessarily still.',
                'witness_rest_title' => 'Rest',
                'witness_rest_body' => 'Rest itself has a purpose.',
                'human_responsibility_title' => 'The Human Responsibility',
                'human_responsibility_body' => 'Heal the wounded. Protect the innocent.',
                'continuance_title' => 'Final Rest and Continuance',
                'continuance_body' => 'Yet the greater movement of life continues.',
                'new_beginning_title' => 'New Beginning',
                'new_beginning_body' => "What may appear to us as an ending is not.\nIt is just a new beginning.",
                'closing_text' => "We were never erased.\nWe were replanted.",
                'final_refrain' => 'It is remembrance made living.',
            ]
        );

        foreach (JourneyCompanion::all() as $companion) {
            $companion->update([
                'artwork_image' => $artworkPath,
                'expanded_teaching' => "Expanded teaching for {$companion->name}",
                'remember_teaching' => "What We Are Asked to Remember for {$companion->name}",
                'branding_info' => "Branding block for {$companion->name}",
                'is_active' => true,
            ]);
        }

        return [$crestPath, $artworkPath];
    }

    /** 1. Regeneration body may remain empty when not required by the handoff */
    public function test_regeneration_body_may_remain_empty_when_not_required(): void
    {
        $this->satisfyPublicationRequirements();

        // Ensure regeneration_body is null
        $setting = CompanionPageSetting::first();
        $setting->update(['regeneration_body' => null]);

        $service = app(CompanionPublicationReadinessService::class);
        $this->assertTrue($service->isReadyForPublication($setting), 'Service must permit publication with null regeneration_body');

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertOk();
        $response->assertSee('Regeneration');
        $response->assertSee('The Witnesses');
    }

    /** 2. Empty required Witnesses content blocks publication */
    public function test_empty_required_witnesses_content_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        // Null out witnesses_intro
        $setting = CompanionPageSetting::first();
        $setting->update(['witnesses_intro' => null]);

        $service = app(CompanionPublicationReadinessService::class);
        $this->assertFalse($service->isReadyForPublication($setting));

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();

        // Restore witnesses_intro and null out witness_wind_body
        $setting->update([
            'witnesses_intro' => 'Witnesses intro restored',
            'witness_wind_body' => null,
        ]);
        $this->assertFalse($service->isReadyForPublication($setting));

        $response2 = $this->get('/living-archive/companions-along-the-journey');
        $response2->assertNotFound();
    }

    /** 3. Missing Companion artwork blocks publication */
    public function test_missing_companion_artwork_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $wolf->update(['artwork_image' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();
    }

    /** 4. Missing expanded teaching blocks publication */
    public function test_missing_expanded_teaching_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        $hawk = JourneyCompanion::where('slug', 'hawk')->first();
        $hawk->update(['expanded_teaching' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();
    }

    /** 5. Missing remembrance passage blocks publication */
    public function test_missing_remembrance_passage_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        $raven = JourneyCompanion::where('slug', 'raven')->first();
        $raven->update(['remember_teaching' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();
    }

    /** 6. Missing Crest blocks publication */
    public function test_missing_crest_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        $setting = CompanionPageSetting::first();
        $setting->update(['entrance_crest_image' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();
    }

    /** 7. Inactive page blocks publication */
    public function test_inactive_page_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        $setting = CompanionPageSetting::first();
        $setting->update(['is_active' => false]);

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();
    }

    /** 8. Inactive Companion blocks publication */
    public function test_inactive_companion_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        $owl = JourneyCompanion::where('slug', 'owl')->first();
        $owl->update(['is_active' => false]);

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();
    }

    /** 9. Exact canonical sequence is enforced */
    public function test_exact_canonical_sequence_is_enforced(): void
    {
        $this->satisfyPublicationRequirements();

        // Swap sequence of Wolf and Hawk
        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $hawk = JourneyCompanion::where('slug', 'hawk')->first();

        // Bypass unique constraint by temporary negative
        $wolf->update(['sequence' => 999]);
        $hawk->update(['sequence' => 1]);
        $wolf->update(['sequence' => 2]);

        $service = app(CompanionPublicationReadinessService::class);
        $this->assertFalse($service->isReadyForPublication());

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();
    }

    /** 10. Complete approved content passes readiness without placeholders */
    public function test_complete_approved_content_passes_readiness_without_placeholders(): void
    {
        $this->satisfyPublicationRequirements();

        $service = app(CompanionPublicationReadinessService::class);
        $this->assertTrue($service->isReadyForPublication());

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertOk();
        $response->assertSee('COMPANIONS ALONG THE JOURNEY');
        $response->assertSee('Wolf');
        $response->assertSee('Otter');
    }

    /** 11. Admin image upload and update workflow remains functional */
    public function test_admin_image_upload_and_update_workflow_remains_functional(): void
    {
        $this->actingAsAdmin();

        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $originalWolfPath = $wolf->artwork_image;
        $originalWolfBytes = ($originalWolfPath && file_exists(public_path($originalWolfPath)))
            ? file_get_contents(public_path($originalWolfPath))
            : null;

        $fakeUpload = UploadedFile::fake()->create('wolf_new_art.jpg', 500, 'image/jpeg');

        $response = $this->put(route('admin.companions-journey.update', $wolf->id), [
            'artwork_image' => $fakeUpload,
            'artwork_image_alt' => 'Updated Wolf Alt Text',
            'themes' => 'Updated Themes',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.companions-journey.index'));

        $wolf->refresh();
        $this->assertNotNull($wolf->artwork_image);
        $this->assertStringContainsString('uploads/companions/companion-wolf-', $wolf->artwork_image);
        $this->assertEquals('Updated Wolf Alt Text', $wolf->artwork_image_alt);

        // Clean up created test file
        if ($wolf->artwork_image && $wolf->artwork_image !== $originalWolfPath && file_exists(public_path($wolf->artwork_image))) {
            unlink(public_path($wolf->artwork_image));
        }

        // Restore original client artwork
        if ($originalWolfPath && $originalWolfBytes !== null) {
            file_put_contents(public_path($originalWolfPath), $originalWolfBytes);
        }
    }

    /** 12. Rich-text rendering is safe */
    public function test_rich_text_rendering_is_safe(): void
    {
        $this->actingAsAdmin();

        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $wolf->update([
            'expanded_teaching' => '<p>The wolf walks ahead with <strong>service</strong> and courage.</p><script>alert("xss")</script>',
        ]);

        $response = $this->get('/admin/companions-journey/preview');
        $response->assertOk();

        $content = $response->getContent();

        // Must render clean HTML elements, NOT literal escaped &lt;p&gt;
        $this->assertStringContainsString('<p>The wolf walks ahead with <strong>service</strong> and courage.</p>', $content);
        $this->assertStringNotContainsString('&lt;p&gt;', $content);
        $this->assertStringNotContainsString('&lt;strong&gt;', $content);

        // Must strip malicious script tags completely
        $this->assertStringNotContainsString('<script>', $content);
        $this->assertStringNotContainsString('alert("xss")', $content);
    }

    /** 13. Original Thomas content remains unchanged */
    public function test_original_thomas_content_remains_unchanged(): void
    {
        $setting = CompanionPageSetting::find(1);
        $this->assertNotNull($setting);

        $this->assertStringContainsString('The animals do not tell me who I am.', $setting->opening_teaching);
        $this->assertStringContainsString('My life has done that.', $setting->opening_teaching);
        $this->assertStringContainsString('We were never erased.', $setting->closing_text);
        $this->assertStringContainsString('We were replanted.', $setting->closing_text);
        $this->assertEquals('It is remembrance made living.', $setting->final_refrain);

        $panther = JourneyCompanion::where('slug', 'panther')->first();
        $this->assertEquals('Patience • Discernment • Presence • Right Action', $panther->themes);
        $this->assertStringContainsString('FIVE FEATHERS PUBLISHING', $panther->branding_info);
    }

    /** 14. Existing routes and pages remain unaffected */
    public function test_existing_routes_and_pages_remain_unaffected(): void
    {
        $livingArchiveResponse = $this->get('/living-archive');
        $livingArchiveResponse->assertOk();

        $identityResponse = $this->get('/identity');
        $identityResponse->assertOk();

        $legacyResponse = $this->get('/living-legacy');
        $legacyResponse->assertOk();
    }
}
