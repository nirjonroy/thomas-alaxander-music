<?php

namespace Tests\Feature;

use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Services\CompanionPublicationReadinessService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CompanionNavigationReadinessTest extends TestCase
{
    use DatabaseTransactions;

    protected CompanionPublicationReadinessService $readinessService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->readinessService = app(CompanionPublicationReadinessService::class);
    }

    /**
     * 1. Draft state (is_active = false):
     * Companion navigation, card, showcase, and CTAs must be hidden on Living Archive,
     * and the public route must return HTTP 404.
     */
    public function test_draft_state_hides_companion_navigation_and_returns_404(): void
    {
        $setting = CompanionPageSetting::firstOrFail();
        $setting->update(['is_active' => false]);

        $this->assertFalse(
            $this->readinessService->isReadyForPublication($setting),
            'Readiness service must reject draft state when is_active is false.'
        );

        $response = $this->get('/living-archive');
        $response->assertStatus(200);

        // Companion navigation, cards, showcase, and CTAs must be hidden
        $response->assertDontSee('#companions-overview');
        $response->assertDontSee(route('front.living-archive.companions'));
        $response->assertDontSee('Enter Companions Along the Journey');
        $response->assertDontSee('Explore Companions');

        // Unrelated Living Archive sections must remain intact
        $response->assertSee('Lineage Story');
        $response->assertSee('Three Crests');
        $response->assertSee('Carrier Pathway');

        // Public route must return 404
        $publicResponse = $this->get(route('front.living-archive.companions'));
        $publicResponse->assertStatus(404);
    }

    /**
     * 2. Incomplete-active state:
     * When page setting is_active is true, but required fields (e.g., entrance_title or crest)
     * are missing/empty, the readiness service fails, companion links remain hidden, and public route is 404.
     */
    public function test_incomplete_active_state_hides_companion_navigation_and_returns_404(): void
    {
        $setting = CompanionPageSetting::firstOrFail();
        $setting->update([
            'is_active' => true,
            'entrance_title' => '', // Cleared required narrative field
        ]);

        $this->assertFalse(
            $this->readinessService->isReadyForPublication($setting),
            'Readiness service must reject incomplete content even when is_active is true.'
        );

        $response = $this->get('/living-archive');
        $response->assertStatus(200);

        $response->assertDontSee('#companions-overview');
        $response->assertDontSee(route('front.living-archive.companions'));
        $response->assertDontSee('Enter Companions Along the Journey');
        $response->assertDontSee('Explore Companions');

        $publicResponse = $this->get(route('front.living-archive.companions'));
        $publicResponse->assertStatus(404);
    }

    /**
     * 3. Demo-content / partial state:
     * When one or more companions are inactive or have missing required fields,
     * the publication gate blocks public exposure.
     */
    public function test_demo_content_or_inactive_companion_state_remains_blocked(): void
    {
        $setting = CompanionPageSetting::firstOrFail();
        $setting->update([
            'is_active' => true,
            'entrance_crest_image' => 'uploads/companions/living-archive-closing-crest.png',
        ]);

        // Mark one companion inactive
        $otter = JourneyCompanion::where('slug', 'otter')->firstOrFail();
        $otter->update(['is_active' => false]);

        $this->assertFalse(
            $this->readinessService->isReadyForPublication($setting),
            'Readiness service must fail when any companion is inactive.'
        );

        $response = $this->get('/living-archive');
        $response->assertStatus(200);

        $response->assertDontSee('#companions-overview');
        $response->assertDontSee(route('front.living-archive.companions'));
        $response->assertDontSee('Enter Companions Along the Journey');
        $response->assertDontSee('Explore Companions');

        $publicResponse = $this->get(route('front.living-archive.companions'));
        $publicResponse->assertStatus(404);
    }

    /**
     * 4. Fully-approved and active state:
     * When all requirements pass, the Living Archive homepage shows Companion navbar links,
     * card, showcase, and CTAs, and public route serves HTTP 200.
     */
    public function test_fully_approved_and_active_state_exposes_companion_navigation_and_allows_public_access(): void
    {
        $setting = CompanionPageSetting::firstOrFail();
        $setting->update([
            'is_active' => true,
            'entrance_crest_image' => 'uploads/companions/living-archive-closing-crest.png',
        ]);

        // Ensure all 11 companions are active and complete
        JourneyCompanion::query()->update(['is_active' => true]);

        $this->assertTrue(
            $this->readinessService->isReadyForPublication($setting),
            'Readiness service must approve fully completed and active state.'
        );

        $response = $this->get('/living-archive');
        $response->assertStatus(200);

        // Companion navigation, cards, showcase, and CTAs must be visible
        $response->assertSee('#companions-overview');
        $response->assertSee(route('front.living-archive.companions'));
        $response->assertSee('Enter Companions Along the Journey');
        $response->assertSee('Explore Companions');

        // Public route must return 200 OK
        $publicResponse = $this->get(route('front.living-archive.companions'));
        $publicResponse->assertStatus(200);
    }
}

