<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Services\CompanionPublicationReadinessService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CompanionPhase4BTest extends TestCase
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
            ['email' => 'phase4b-admin@example.com'],
            [
                'name' => 'Phase 4B Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    /**
     * Seeds complete, valid temporary fixtures to satisfy all 12 publication conditions.
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
                'regeneration_body' => 'Regeneration narrative body.',
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

    /** 1. Inactive page returns 404 */
    public function test_inactive_page_returns_404(): void
    {
        CompanionPageSetting::updateOrCreate(['id' => 1], ['is_active' => false]);

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    /** 2. Active page with incomplete content still returns 404 */
    public function test_active_page_with_incomplete_content_still_returns_404(): void
    {
        // Activating settings while Companions lack artworks, teachings, etc.
        CompanionPageSetting::updateOrCreate(['id' => 1], ['is_active' => true]);

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    /** 3. Missing one required artwork blocks publication */
    public function test_missing_one_required_artwork_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        // Remove artwork for Wolf
        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $wolf->update(['artwork_image' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    /** 4. Missing one expanded teaching blocks publication */
    public function test_missing_one_expanded_teaching_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        // Clear expanded teaching for Eagle
        $eagle = JourneyCompanion::where('slug', 'eagle')->first();
        $eagle->update(['expanded_teaching' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    /** 5. Missing one remembrance passage blocks publication */
    public function test_missing_one_remembrance_passage_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        // Clear remembrance for Bear
        $bear = JourneyCompanion::where('slug', 'bear')->first();
        $bear->update(['remember_teaching' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    /** 6. Missing an entrance or closing Crest blocks publication */
    public function test_missing_an_entrance_or_closing_crest_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        // Remove entrance crest
        $setting = CompanionPageSetting::first();
        $setting->update(['entrance_crest_image' => null]);

        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();

        // Restore entrance crest, remove closing crest
        $setting->update([
            'entrance_crest_image' => 'uploads/companions/test-fixtures/crest.png',
            'closing_crest_image' => null,
        ]);

        $response2 = $this->get('/living-archive/companions-along-the-journey');
        $response2->assertNotFound();
    }

    /** 7. An inactive Companion blocks publication */
    public function test_an_inactive_companion_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        // Set Otter to inactive
        $otter = JourneyCompanion::where('slug', 'otter')->first();
        $otter->update(['is_active' => false]);

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    /** 8. Incorrect canonical sequence blocks publication */
    public function test_incorrect_canonical_sequence_blocks_publication(): void
    {
        $this->satisfyPublicationRequirements();

        // Alter sequence of Wolf to 99
        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $wolf->update(['sequence' => 99]);

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    /** 9. A complete valid dataset permits public rendering when explicitly active */
    public function test_complete_valid_dataset_permits_public_rendering_when_explicitly_active(): void
    {
        $this->satisfyPublicationRequirements();

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertOk();
        $response->assertSee('COMPANIONS ALONG THE JOURNEY');
        $response->assertSee('Wolf');
        $response->assertSee('Otter');
    }

    /** 10. Admin Preview works with incomplete content */
    public function test_admin_preview_works_with_incomplete_content(): void
    {
        $this->actingAsAdmin();

        // With default development database (inactive, missing artworks/teachings)
        CompanionPageSetting::updateOrCreate(['id' => 1], ['is_active' => false]);

        $response = $this->get('/admin/companions-journey/preview');

        $response->assertOk();
        $response->assertSee('Protected Admin Preview');
        $response->assertSee('COMPANIONS ALONG THE JOURNEY');
        $response->assertSee('Wolf');
        $response->assertSee('Otter');
    }

    /** 11. Readiness diagnostics are visible to admins only */
    public function test_readiness_diagnostics_are_visible_to_admins_only(): void
    {
        // 1. Guest preview is blocked
        $guestResponse = $this->get('/admin/companions-journey/preview');
        $guestResponse->assertRedirect(route('admin.login'));

        // 2. Admin preview shows diagnostics and checklists
        $this->actingAsAdmin();
        JourneyCompanion::where('slug', 'wolf')->update(['artwork_image' => null]);
        $adminResponse = $this->get('/admin/companions-journey/preview');
        $adminResponse->assertOk();
        $adminResponse->assertSee('Protected Admin Preview');
        $adminResponse->assertSee('Publication Blocked (Incomplete Content)');
        $adminResponse->assertSee('View Mandatory Publication Checklist');
        $adminResponse->assertSee('Wolf');
        $adminResponse->assertSee('Artwork missing');

        // 3. When public page is published, public users never see preview bar or diagnostics
        $this->satisfyPublicationRequirements();
        $publicResponse = $this->get('/living-archive/companions-along-the-journey');
        $publicResponse->assertOk();
        $publicResponse->assertDontSee('Protected Admin Preview');
        $publicResponse->assertDontSee('<aside class="preview-readiness-bar"', false);
        $publicResponse->assertDontSee('View Mandatory Publication Checklist');
    }

    /** 12. No unpublished content leaks through the public route */
    public function test_no_unpublished_content_leaks_through_the_public_route(): void
    {
        // When setting is inactive, public returns 404
        CompanionPageSetting::updateOrCreate(['id' => 1], ['is_active' => false]);
        $response = $this->get('/living-archive/companions-along-the-journey');
        $response->assertNotFound();

        // Even if setting is active, if content is incomplete, public returns 404
        CompanionPageSetting::updateOrCreate(['id' => 1], ['is_active' => true]);
        $responseActive = $this->get('/living-archive/companions-along-the-journey');
        $responseActive->assertNotFound();
    }

    /** 13. No prohibited promotional block follows the final refrain */
    public function test_no_prohibited_promotional_block_follows_the_final_refrain(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/companions-journey/preview');
        $content = $response->getContent();

        $posFinalRefrain = strpos($content, 'ceremonial-closing__refrain');
        $this->assertNotFalse($posFinalRefrain);

        $afterRefrain = substr($content, $posFinalRefrain);

        $this->assertStringNotContainsString('book-now', strtolower($afterRefrain));
        $this->assertStringNotContainsString('add-to-cart', strtolower($afterRefrain));
        $this->assertStringNotContainsString('epk-card', strtolower($afterRefrain));
        $this->assertStringNotContainsString('buy-ticket', strtolower($afterRefrain));
        $this->assertStringNotContainsString('merch', strtolower($afterRefrain));
        $this->assertStringNotContainsString('album-sale', strtolower($afterRefrain));
    }

    /** 14. Original client text is unchanged */
    public function test_original_client_text_is_unchanged(): void
    {
        $setting = CompanionPageSetting::find(1);

        $this->assertNotNull($setting);
        $this->assertStringContainsString('The animals do not tell me who I am.', $setting->opening_teaching);
        $this->assertStringContainsString('My life has done that.', $setting->opening_teaching);
        $this->assertEquals('We were never erased.', explode("\n", $setting->closing_text)[0]);
        $this->assertEquals('We were replanted.', explode("\n", $setting->closing_text)[1]);
        $this->assertEquals('It is remembrance made living.', $setting->final_refrain);

        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $this->assertEquals('Leadership • Service • Protection • Responsibility', $wolf->themes);
        $this->assertStringContainsString('FIVE FEATHERS PUBLISHING', $wolf->branding_info);
    }

    /** 15. Service correctly reports readiness status */
    public function test_service_correctly_reports_readiness_status(): void
    {
        $service = app(CompanionPublicationReadinessService::class);

        // In incomplete state
        CompanionPageSetting::updateOrCreate(['id' => 1], ['is_active' => false]);
        JourneyCompanion::where('slug', 'wolf')->update([
            'artwork_image' => null,
            'expanded_teaching' => null,
            'remember_teaching' => null,
        ]);
        $this->assertFalse($service->isReadyForPublication());

        $report = $service->getReadinessReport();
        $this->assertFalse($report['is_ready']);
        $this->assertGreaterThan(0, $report['missing_artworks_count']);
        $this->assertGreaterThan(0, $report['missing_teachings_count']);
        $this->assertGreaterThan(0, $report['missing_remember_count']);

        // In complete state
        $this->satisfyPublicationRequirements();
        $this->assertTrue($service->isReadyForPublication());
    }
}
