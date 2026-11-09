<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CompanionPhase4ATest extends TestCase
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

    private function satisfyPublicationRequirements(): void
    {
        $testDir = public_path('uploads/companions/test-fixtures');
        if (! file_exists($testDir)) {
            mkdir($testDir, 0755, true);
        }
        $crestPath = 'uploads/companions/test-fixtures/test-crest.png';
        $artworkPath = 'uploads/companions/test-fixtures/test-art.png';
        file_put_contents(public_path($crestPath), 'dummy-crest');
        file_put_contents(public_path($artworkPath), 'dummy-artwork');

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
                'regeneration_body' => 'Test regeneration body',
                'witnesses_title' => 'The Witnesses',
                'witnesses_intro' => 'Test witnesses intro',
                'witness_wind_title' => 'Wind',
                'witness_wind_body' => 'Wind body',
                'witness_earth_title' => 'Earth',
                'witness_earth_body' => 'Earth body',
                'witness_water_title' => 'Water',
                'witness_water_body' => 'Water body',
                'witness_sunlight_title' => 'Sunlight',
                'witness_sunlight_body' => 'Sunlight body',
                'witness_night_title' => 'Night',
                'witness_night_body' => 'Night body',
                'witness_rest_title' => 'Rest',
                'witness_rest_body' => 'Rest body',
                'human_responsibility_title' => 'Human Responsibility',
                'human_responsibility_body' => 'Human responsibility body',
                'continuance_title' => 'Continuance',
                'continuance_body' => 'Continuance body',
                'new_beginning_title' => 'New Beginning',
                'new_beginning_body' => 'New beginning body',
                'closing_text' => "We were never erased.\nWe were replanted.",
                'final_refrain' => 'It is remembrance made living.',
            ]
        );

        foreach (JourneyCompanion::all() as $companion) {
            $companion->update([
                'artwork_image' => $artworkPath,
                'expanded_teaching' => "Teaching for {$companion->name}",
                'remember_teaching' => "Remember for {$companion->name}",
                'branding_info' => "Branding for {$companion->name}",
                'is_active' => true,
            ]);
        }
    }

    private function actingAsAdmin(): Admin
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'phase4a-admin@example.com'],
            [
                'name' => 'Phase 4A Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    public function test_public_route_returns_404_while_setting_is_inactive(): void
    {
        CompanionPageSetting::updateOrCreate(
            ['id' => 1],
            ['is_active' => false]
        );

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertNotFound();
    }

    public function test_public_route_returns_200_when_setting_is_active(): void
    {
        $this->satisfyPublicationRequirements();

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertOk();
        $response->assertSee('COMPANIONS ALONG THE JOURNEY');
    }

    public function test_guest_cannot_access_admin_preview_and_is_redirected(): void
    {
        $response = $this->get('/admin/companions-journey/preview');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authorized_admin_can_access_preview_even_when_setting_is_inactive(): void
    {
        $this->actingAsAdmin();

        CompanionPageSetting::updateOrCreate(
            ['id' => 1],
            ['is_active' => false]
        );

        $response = $this->get('/admin/companions-journey/preview');

        $response->assertOk();
        $response->assertSee('COMPANIONS ALONG THE JOURNEY');
    }

    public function test_preview_contains_noindex_nofollow_meta_tag(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/companions-journey/preview');

        $response->assertOk();
        $response->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_public_page_when_active_does_not_contain_noindex_nofollow(): void
    {
        $this->satisfyPublicationRequirements();

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertOk();
        $response->assertDontSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_admin_preview_contains_readiness_summary(): void
    {
        $this->actingAsAdmin();

        CompanionPageSetting::updateOrCreate(
            ['id' => 1],
            [
                'is_active' => false,
                'entrance_crest_image' => null,
                'closing_crest_image' => null,
            ]
        );

        $response = $this->get('/admin/companions-journey/preview');

        $response->assertOk();
        $response->assertSee('Protected Admin Preview');
        $response->assertSee('Page Status:');
        $response->assertSee('Opening Crest:');
        $response->assertSee('Closing Crest:');
        $response->assertSee('Missing Artworks:');
        $response->assertSee('Missing Teachings:');
        $response->assertSee('Missing Remembrance:');
    }

    public function test_public_page_when_active_does_not_contain_readiness_summary_or_admin_badges(): void
    {
        $this->satisfyPublicationRequirements();

        $response = $this->get('/living-archive/companions-along-the-journey');

        $response->assertOk();
        $response->assertDontSee('Protected Admin Preview');
        $response->assertDontSee('<aside class="preview-readiness-bar"', false);
        $response->assertDontSee('Artwork pending upload');
        $response->assertDontSee('Expanded teaching pending');
        $response->assertDontSee('Remembrance passage pending');
    }

    public function test_companions_render_in_exact_canonical_sequence(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/companions-journey/preview');
        $content = $response->getContent();

        $canonicalCompanions = [
            'Wolf',
            'Hawk',
            'Raven',
            'Owl',
            'Eagle',
            'Panther',
            'Bear',
            'Lynx',
            'Deer',
            'Fox',
            'Otter',
        ];

        $lastPos = 0;
        foreach ($canonicalCompanions as $companionName) {
            $pos = strpos($content, '<h2 class="companion-name">' . $companionName . '</h2>');
            $this->assertNotFalse($pos, "Companion {$companionName} not found in content");
            $this->assertGreaterThan($lastPos, $pos, "Companion {$companionName} is not in canonical sequence");
            $lastPos = $pos;
        }
    }

    public function test_no_companion_appears_more_than_once(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/companions-journey/preview');
        $content = $response->getContent();

        $canonicalCompanions = [
            'Wolf', 'Hawk', 'Raven', 'Owl', 'Eagle', 'Panther',
            'Bear', 'Lynx', 'Deer', 'Fox', 'Otter',
        ];

        foreach ($canonicalCompanions as $companionName) {
            $count = substr_count($content, '<h2 class="companion-name">' . $companionName . '</h2>');
            $this->assertEquals(1, $count, "Companion {$companionName} appeared {$count} times instead of 1");
        }
    }

    public function test_empty_artwork_fields_do_not_break_rendering(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/companions-journey/preview');
        $response->assertOk();
        $response->assertSee('Wolf');
        $response->assertSee('Otter');
    }

    public function test_missing_expanded_teachings_are_not_replaced_with_invented_content(): void
    {
        $this->actingAsAdmin();

        // Under preview mode with null teachings, teaching blocks are not rendered
        JourneyCompanion::query()->update(['expanded_teaching' => null]);
        $response = $this->get('/admin/companions-journey/preview');
        $response->assertOk();

        // The teaching block with actual content is not present
        $response->assertDontSee('<div class="companion-teaching">', false);
    }

    public function test_missing_remembrance_passages_are_not_replaced_with_invented_content(): void
    {
        $this->actingAsAdmin();

        // Under preview mode with null remembrance, remember blocks are not rendered
        JourneyCompanion::query()->update(['remember_teaching' => null]);
        $response = $this->get('/admin/companions-journey/preview');
        $response->assertOk();

        // The remember block with actual content is not present
        $response->assertDontSee('<div class="companion-remember">', false);
    }

    public function test_database_image_paths_are_used_dynamically(): void
    {
        $this->actingAsAdmin();

        $wolf = JourneyCompanion::where('slug', 'wolf')->first();
        $wolf->update(['artwork_image' => 'uploads/companions/custom-wolf-painting.jpg']);

        $response = $this->get('/admin/companions-journey/preview');

        $response->assertOk();
        $response->assertSee('uploads/companions/custom-wolf-painting.jpg');
    }

    public function test_entrance_crest_is_rendered_only_at_the_entrance(): void
    {
        $this->actingAsAdmin();

        CompanionPageSetting::updateOrCreate(
            ['id' => 1],
            [
                'entrance_crest_image' => 'uploads/companions/test-entrance-crest.png',
                'closing_crest_image' => null,
            ]
        );

        $response = $this->get('/admin/companions-journey/preview');
        $content = $response->getContent();

        $this->assertStringContainsString('uploads/companions/test-entrance-crest.png', $content);
        $this->assertStringContainsString('class="ceremonial-crest ceremonial-crest--entrance"', $content);
        $this->assertStringNotContainsString('class="ceremonial-crest ceremonial-crest--closing"', $content);
        $this->assertEquals(1, substr_count($content, 'uploads/companions/test-entrance-crest.png'));
    }

    public function test_closing_crest_is_rendered_only_at_the_conclusion(): void
    {
        $this->actingAsAdmin();

        CompanionPageSetting::updateOrCreate(
            ['id' => 1],
            [
                'entrance_crest_image' => null,
                'closing_crest_image' => 'uploads/companions/test-closing-crest.png',
            ]
        );

        $response = $this->get('/admin/companions-journey/preview');
        $content = $response->getContent();

        $this->assertStringContainsString('uploads/companions/test-closing-crest.png', $content);
        $this->assertStringContainsString('class="ceremonial-crest ceremonial-crest--closing"', $content);
        $this->assertStringNotContainsString('class="ceremonial-crest ceremonial-crest--entrance"', $content);
        $this->assertEquals(1, substr_count($content, 'uploads/companions/test-closing-crest.png'));
    }

    public function test_closing_passages_render_in_exact_required_sequence(): void
    {
        $this->actingAsAdmin();

        CompanionPageSetting::updateOrCreate(
            ['id' => 1],
            [
                'new_beginning_body' => "What may appear to us as an ending is not.\nIt is just a new beginning.",
                'closing_crest_image' => 'uploads/companions/test-closing-crest.png',
                'closing_text' => "We were never erased.\nWe were replanted.",
                'final_refrain' => 'It is remembrance made living.',
            ]
        );

        $response = $this->get('/admin/companions-journey/preview');
        $content = $response->getContent();

        $posClosingFooter = strpos($content, '<footer class="ceremonial-closing-flow"');
        $this->assertNotFalse($posClosingFooter, 'Closing footer section not found');
        $closingSection = substr($content, $posClosingFooter);

        $posNewBeginning = strpos($closingSection, 'ceremonial-closing__new-beginning');
        $posClosingCrest = strpos($closingSection, 'ceremonial-crest--closing');
        $posClosingText = strpos($closingSection, 'ceremonial-closing__statement');
        $posFinalRefrain = strpos($closingSection, 'ceremonial-closing__refrain');

        $this->assertNotFalse($posNewBeginning, 'new_beginning_body not found');
        $this->assertNotFalse($posClosingCrest, 'closing_crest_image not found');
        $this->assertNotFalse($posClosingText, 'closing_text not found');
        $this->assertNotFalse($posFinalRefrain, 'final_refrain not found');

        $this->assertLessThan($posClosingCrest, $posNewBeginning, 'New beginning must come before closing crest');
        $this->assertLessThan($posClosingText, $posClosingCrest, 'Closing crest must come before closing text');
        $this->assertLessThan($posFinalRefrain, $posClosingText, 'Closing text must come before final refrain');
    }

    public function test_no_promotional_components_are_inserted_after_the_final_refrain(): void
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
    }

    public function test_admin_index_and_settings_contain_preview_button(): void
    {
        $this->actingAsAdmin();

        $indexResponse = $this->get(route('admin.companions-journey.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee(route('admin.companions-journey.preview'));
        $indexResponse->assertSee('Preview Page');

        $settingsResponse = $this->get(route('admin.companions-journey.settings'));
        $settingsResponse->assertOk();
        $settingsResponse->assertSee(route('admin.companions-journey.preview'));
        $settingsResponse->assertSee('Preview Page');
    }
}
