<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Services\CompanionJourneyDemoService;
use Database\Seeders\CompanionJourneyDemoSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LogicException;
use Tests\TestCase;

class CompanionJourneyDemoSeederTest extends TestCase
{
    use DatabaseTransactions;

    private string $snapshotPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->snapshotPath = 'companion-journey-demo-tests/' . uniqid('snapshot-', true) . '.json';

        config()->set('companion_demo.enabled', true);
        config()->set('companion_demo.approved_database', DB::connection()->getDatabaseName());
        config()->set('companion_demo.snapshot_path', $this->snapshotPath);

        Storage::disk('local')->delete($this->snapshotPath);
    }

    protected function tearDown(): void
    {
        Storage::disk('local')->delete($this->snapshotPath);

        parent::tearDown();
    }

    public function test_all_empty_companion_teaching_fields_receive_marked_demo_content(): void
    {
        JourneyCompanion::query()->update([
            'expanded_teaching' => null,
            'remember_teaching' => null,
        ]);

        app(CompanionJourneyDemoSeeder::class)->run();

        $companions = JourneyCompanion::ordered()->get();

        $this->assertCount(11, $companions);
        $this->assertSame(11, $companions->filter(
            fn (JourneyCompanion $companion) => str_starts_with($companion->expanded_teaching, CompanionJourneyDemoService::PREFIX)
        )->count());
        $this->assertSame(11, $companions->filter(
            fn (JourneyCompanion $companion) => str_starts_with($companion->remember_teaching, CompanionJourneyDemoService::PREFIX)
        )->count());
        $this->assertTrue(Storage::disk('local')->exists($this->snapshotPath));
    }

    public function test_existing_teaching_content_is_never_overwritten(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $wolf->update([
            'expanded_teaching' => 'Approved Wolf teaching.',
            'remember_teaching' => 'Approved Wolf remembrance.',
        ]);

        app(CompanionJourneyDemoService::class)->seed();

        $wolf->refresh();
        $this->assertSame('Approved Wolf teaching.', $wolf->expanded_teaching);
        $this->assertSame('Approved Wolf remembrance.', $wolf->remember_teaching);
    }

    public function test_seeder_is_idempotent_and_never_changes_artwork(): void
    {
        JourneyCompanion::query()->update([
            'expanded_teaching' => null,
            'remember_teaching' => null,
        ]);

        $beforeArtwork = JourneyCompanion::ordered()->get()->mapWithKeys(function (JourneyCompanion $companion) {
            $path = public_path($companion->artwork_image);

            return [$companion->id => [
                'path' => $companion->artwork_image,
                'hash' => is_file($path) ? hash_file('sha256', $path) : null,
            ]];
        });

        $service = app(CompanionJourneyDemoService::class);
        $first = $service->seed();
        $second = $service->seed();

        $this->assertSame(22, $first['seeded']);
        $this->assertSame(0, $second['seeded']);

        JourneyCompanion::ordered()->get()->each(function (JourneyCompanion $companion) use ($beforeArtwork): void {
            $path = public_path($companion->artwork_image);

            $this->assertSame($beforeArtwork[$companion->id]['path'], $companion->artwork_image);
            $this->assertSame($beforeArtwork[$companion->id]['hash'], is_file($path) ? hash_file('sha256', $path) : null);
        });
    }

    public function test_rollback_restores_demo_fields_without_overwriting_later_admin_edits(): void
    {
        JourneyCompanion::query()->update([
            'expanded_teaching' => null,
            'remember_teaching' => null,
        ]);

        app(CompanionJourneyDemoService::class)->seed();

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $wolf->update(['expanded_teaching' => 'Administrator edited this after the demo seed.']);

        $this->artisan('companions:rollback-demo-content')
            ->assertExitCode(0);

        $wolf->refresh();
        $this->assertSame('Administrator edited this after the demo seed.', $wolf->expanded_teaching);
        $this->assertNull($wolf->remember_teaching);
        $this->assertTrue(Storage::disk('local')->exists($this->snapshotPath));
    }

    public function test_production_environment_is_rejected(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        try {
            app(CompanionJourneyDemoService::class)->seed();
            $this->fail('Production execution should have been rejected.');
        } catch (LogicException $exception) {
            $this->assertStringContainsString('local or testing', $exception->getMessage());
        } finally {
            $this->app->detectEnvironment(fn () => 'testing');
        }
    }

    public function test_demo_content_does_not_publish_the_public_page_and_is_visible_to_authenticated_preview(): void
    {
        JourneyCompanion::query()->update([
            'expanded_teaching' => null,
            'remember_teaching' => null,
            'is_active' => false,
        ]);
        CompanionPageSetting::firstOrFail()->update(['is_active' => false]);

        app(CompanionJourneyDemoService::class)->seed();

        $this->get(route('front.living-archive.companions'))->assertNotFound();

        $admin = Admin::create([
            'name' => 'Demo Preview Admin',
            'email' => 'demo-preview-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'admin_type' => 1,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.companions-journey.preview'))
            ->assertOk()
            ->assertSee(CompanionJourneyDemoService::PREFIX);
    }

    public function test_local_authenticated_view_as_public_preview_renders_demo_content_without_admin_toolbar(): void
    {
        JourneyCompanion::query()->update([
            'expanded_teaching' => null,
            'remember_teaching' => null,
            'is_active' => false,
        ]);
        CompanionPageSetting::firstOrFail()->update(['is_active' => false]);

        app(CompanionJourneyDemoService::class)->seed();

        $admin = Admin::create([
            'name' => 'Public Style Preview Admin',
            'email' => 'public-style-preview-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'admin_type' => 1,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.companions-journey.preview-as-public'))
            ->assertOk()
            ->assertSee(CompanionJourneyDemoService::PREFIX)
            ->assertDontSee('Protected Admin Preview')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_view_as_public_preview_is_not_available_outside_local_or_testing(): void
    {
        $admin = Admin::create([
            'name' => 'Production Preview Admin',
            'email' => 'production-preview-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'admin_type' => 1,
        ]);

        $this->app->detectEnvironment(fn () => 'production');

        try {
            $this->actingAs($admin, 'admin')
                ->get(route('admin.companions-journey.preview-as-public'))
                ->assertNotFound();
        } finally {
            $this->app->detectEnvironment(fn () => 'testing');
        }
    }
}
