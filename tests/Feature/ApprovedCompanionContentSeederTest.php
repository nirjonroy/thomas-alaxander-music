<?php

namespace Tests\Feature;

use App\Models\CompanionPageSetting;
use App\Models\Admin;
use App\Models\JourneyCompanion;
use Database\Seeders\ApprovedCompanionContentSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApprovedCompanionContentSeederTest extends TestCase
{
    use DatabaseTransactions;

    public function test_final_content_is_exactly_applied_by_slug_without_changing_artwork_or_ids(): void
    {
        $before = JourneyCompanion::query()->get()->keyBy('slug')->map(fn (JourneyCompanion $companion) => [
            'id' => $companion->id,
            'artwork_image' => $companion->artwork_image,
            'artwork_hash' => $this->artworkHash($companion->artwork_image),
        ]);
        $setting = CompanionPageSetting::firstOrFail();

        $seeder = app(ApprovedCompanionContentSeeder::class);
        $expected = $seeder->approvedCompanions();
        $seeder->run();
        $seeder->run();

        $companions = JourneyCompanion::ordered()->get();
        $this->assertSame(array_keys($expected), $companions->pluck('slug')->all());

        foreach ($companions as $companion) {
            $approved = $expected[$companion->slug];
            $this->assertSame($approved['sequence'], $companion->sequence);
            $this->assertSame($approved['name'], $companion->name);
            $this->assertSame($approved['themes'], $companion->themes);
            $this->assertSame($approved['expanded_teaching'], $companion->expanded_teaching);
            $this->assertSame($approved['remember_teaching'], $companion->remember_teaching);
            $this->assertSame($before[$companion->slug]['id'], $companion->id);
            $this->assertSame($before[$companion->slug]['artwork_image'], $companion->artwork_image);
            $this->assertSame($before[$companion->slug]['artwork_hash'], $this->artworkHash($companion->artwork_image));
            $this->assertStringContainsString("\n\n", $companion->expanded_teaching);
            $this->assertStringNotContainsString('[DEMO CONTENT', $companion->expanded_teaching);
            $this->assertStringNotContainsString('[DEMO CONTENT', $companion->remember_teaching);
        }

        $this->assertFalse($setting->fresh()->is_active);
    }

    public function test_authenticated_preview_renders_final_copyright_without_duplicate_witnesses_or_closing_promotions(): void
    {
        app(ApprovedCompanionContentSeeder::class)->run();

        $admin = Admin::create([
            'name' => 'Approved Content Preview Admin',
            'email' => 'approved-content-preview-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'admin_type' => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.companions-journey.preview-as-public'));

        $response->assertOk()
            ->assertSee(JourneyCompanion::COMPANION_COPYRIGHT)
            ->assertSee('No reproduction or republication without permission.')
            ->assertDontSee('Back to Living Archive')
            ->assertDontSee('Offer Support');
        $this->assertSame(1, substr_count($response->getContent(), '>The Witnesses<'));
        $this->assertSame(11, substr_count($response->getContent(), '<p class="companion-copyright">'));
    }

    private function artworkHash(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $absolutePath = public_path($path);

        return is_file($absolutePath) ? hash_file('sha256', $absolutePath) : null;
    }
}
