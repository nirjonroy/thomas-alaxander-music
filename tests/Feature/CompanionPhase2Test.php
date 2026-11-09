<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use Database\Seeders\CompanionPageSettingStructureSeeder;
use Database\Seeders\JourneyCompanionStructureSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CompanionPhase2Test extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => JourneyCompanionStructureSeeder::class]);
        Artisan::call('db:seed', ['--class' => CompanionPageSettingStructureSeeder::class]);
    }

    private function actingAsAdmin(): Admin
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'phase2-admin@example.com'],
            [
                'name' => 'Phase 2 Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    public function test_01_unique_sequence_database_protection_works(): void
    {
        $this->expectException(QueryException::class);

        // Attempt to insert duplicate sequence 1
        DB::table('journey_companions')->insert([
            'sequence' => 1,
            'name' => 'Duplicate Wolf',
            'slug' => 'duplicate-wolf',
            'is_active' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_02_exactly_11_structural_companion_records_can_be_seeded(): void
    {
        $this->assertSame(11, JourneyCompanion::count());
    }

    public function test_03_seeder_is_idempotent(): void
    {
        Artisan::call('db:seed', ['--class' => JourneyCompanionStructureSeeder::class]);
        Artisan::call('db:seed', ['--class' => JourneyCompanionStructureSeeder::class]);

        $this->assertSame(11, JourneyCompanion::count());
    }

    public function test_04_canonical_order_remains_exact(): void
    {
        $expected = [
            1 => ['name' => 'Wolf', 'slug' => 'wolf'],
            2 => ['name' => 'Hawk', 'slug' => 'hawk'],
            3 => ['name' => 'Raven', 'slug' => 'raven'],
            4 => ['name' => 'Owl', 'slug' => 'owl'],
            5 => ['name' => 'Eagle', 'slug' => 'eagle'],
            6 => ['name' => 'Panther', 'slug' => 'panther'],
            7 => ['name' => 'Bear', 'slug' => 'bear'],
            8 => ['name' => 'Lynx', 'slug' => 'lynx'],
            9 => ['name' => 'Deer', 'slug' => 'deer'],
            10 => ['name' => 'Fox', 'slug' => 'fox'],
            11 => ['name' => 'Otter', 'slug' => 'otter'],
        ];

        $companions = JourneyCompanion::ordered()->get();

        $this->assertCount(11, $companions);

        foreach ($companions as $companion) {
            $seq = $companion->sequence;
            $this->assertArrayHasKey($seq, $expected);
            $this->assertSame($expected[$seq]['name'], $companion->name);
            $this->assertSame($expected[$seq]['slug'], $companion->slug);
        }
    }

    public function test_05_all_companions_start_inactive(): void
    {
        $companions = JourneyCompanion::all();

        $this->assertCount(11, $companions);

        foreach ($companions as $companion) {
            $this->assertFalse(
                (bool) $companion->is_active,
                "Companion [{$companion->name}] must start inactive."
            );
        }
    }

    public function test_06_structural_seeder_does_not_populate_teaching_fields(): void
    {
        JourneyCompanion::query()->update([
            'themes' => null,
            'expanded_teaching' => null,
            'remember_teaching' => null,
            'branding_info' => null,
            'artwork_image' => null,
        ]);
        Artisan::call('db:seed', ['--class' => JourneyCompanionStructureSeeder::class]);

        $companions = JourneyCompanion::all();

        foreach ($companions as $companion) {
            $this->assertNull($companion->themes, "Themes for [{$companion->name}] must be null.");
            $this->assertNull($companion->artwork_image, "Artwork image for [{$companion->name}] must be null.");
            $this->assertNull($companion->artwork_image_alt, "Artwork alt for [{$companion->name}] must be null.");
            $this->assertNull($companion->expanded_teaching, "Expanded teaching for [{$companion->name}] must be null.");
            $this->assertNull($companion->remember_teaching, "Remember teaching for [{$companion->name}] must be null.");
            $this->assertNull($companion->branding_info, "Branding info for [{$companion->name}] must be null.");
        }
    }

    public function test_07_structural_seeder_does_not_overwrite_existing_teaching_content(): void
    {
        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $wolf->update([
            'themes' => 'Guidance, Instinct',
            'expanded_teaching' => 'Original sacred wolf teachings.',
            'remember_teaching' => 'Remember the pathfinder.',
            'branding_info' => 'Five Feathers Archives',
        ]);

        Artisan::call('db:seed', ['--class' => JourneyCompanionStructureSeeder::class]);

        $wolf->refresh();

        $this->assertSame('Guidance, Instinct', $wolf->themes);
        $this->assertSame('Original sacred wolf teachings.', $wolf->expanded_teaching);
        $this->assertSame('Remember the pathfinder.', $wolf->remember_teaching);
        $this->assertSame('Five Feathers Archives', $wolf->branding_info);
    }

    public function test_08_arbitrary_companion_creation_is_unavailable(): void
    {
        $this->assertNull(Route::getRoutes()->getByName('admin.companions-journey.create'));
        $this->assertNull(Route::getRoutes()->getByName('admin.companions-journey.store'));

        $this->actingAsAdmin();

        $response = $this->post('/admin/companions-journey', [
            'name' => 'Coyote',
            'slug' => 'coyote',
            'sequence' => 12,
        ]);

        $this->assertSame(405, $response->getStatusCode()); // MethodNotAllowedHttpException
    }

    public function test_09_companion_deletion_is_unavailable(): void
    {
        $this->assertNull(Route::getRoutes()->getByName('admin.companions-journey.destroy'));

        $this->actingAsAdmin();

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $response = $this->delete("/admin/companions-journey/{$wolf->id}");

        $this->assertSame(405, $response->getStatusCode()); // MethodNotAllowedHttpException
    }

    public function test_10_sequence_name_slug_cannot_be_changed_through_normal_admin_update(): void
    {
        $this->actingAsAdmin();

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $response = $this->put(route('admin.companions-journey.update', $wolf->id), [
            'sequence' => 99,
            'name' => 'Golden Lion',
            'slug' => 'golden-lion',
            'themes' => 'Updated themes',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.companions-journey.index'));

        $wolf->refresh();

        $this->assertSame(1, $wolf->sequence);
        $this->assertSame('Wolf', $wolf->name);
        $this->assertSame('wolf', $wolf->slug);
        $this->assertSame('Updated themes', $wolf->themes);
        $this->assertTrue($wolf->is_active);
    }

    public function test_11_page_setting_remains_inactive_by_default(): void
    {
        $setting = CompanionPageSetting::find(1);

        $this->assertNotNull($setting);
        $this->assertFalse((bool) $setting->is_active);
    }

    public function test_12_authorized_admin_can_access_settings_and_edit_routes(): void
    {
        // Unauthenticated access must redirect to admin login
        $this->get(route('admin.companions-journey.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.companions-journey.settings'))->assertRedirect(route('admin.login'));

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $this->get(route('admin.companions-journey.edit', $wolf->id))->assertRedirect(route('admin.login'));

        // Authorized admin access
        $this->actingAsAdmin();

        $this->get(route('admin.companions-journey.index'))
            ->assertOk()
            ->assertSee('Companions Along the Journey')
            ->assertSee('Wolf')
            ->assertSee('Otter');

        $this->get(route('admin.companions-journey.settings'))
            ->assertOk()
            ->assertSee('Companions Along the Journey &mdash; Page Settings', false)
            ->assertSee('Ceremonial Entrance');

        $this->get(route('admin.companions-journey.edit', $wolf->id))
            ->assertOk()
            ->assertSee('Edit Companion: Wolf')
            ->assertSee('Expanded Teaching');
    }

    public function test_13_invalid_image_types_are_rejected(): void
    {
        $this->actingAsAdmin();

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $originalWolfPath = $wolf->artwork_image;
        $originalWolfBytes = ($originalWolfPath && file_exists(public_path($originalWolfPath)))
            ? file_get_contents(public_path($originalWolfPath))
            : null;

        try {
            // 1. Reject SVG or TXT on Companion artwork
            $svgFile = UploadedFile::fake()->create('artwork.svg', 10, 'image/svg+xml');
            $this->put(route('admin.companions-journey.update', $wolf->id), [
                'artwork_image' => $svgFile,
            ])->assertSessionHasErrors(['artwork_image']);

            $txtFile = UploadedFile::fake()->create('document.txt', 10, 'text/plain');
            $this->put(route('admin.companions-journey.update', $wolf->id), [
                'artwork_image' => $txtFile,
            ])->assertSessionHasErrors(['artwork_image']);

            // 2. Reject SVG or TXT on Page Settings crest
            $this->put(route('admin.companions-journey.settings.update'), [
                'entrance_crest_image' => $svgFile,
            ])->assertSessionHasErrors(['entrance_crest_image']);

            // 3. Accept valid PNG image
            $validPng = UploadedFile::fake()->createWithContent(
                'wolf.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==')
            );

            $response = $this->put(route('admin.companions-journey.update', $wolf->id), [
                'artwork_image' => $validPng,
                'artwork_image_alt' => 'Wolf artwork description',
            ]);

            $response->assertRedirect(route('admin.companions-journey.index'));

            $wolf->refresh();
            $this->assertNotNull($wolf->artwork_image);
            $this->assertFileExists(public_path($wolf->artwork_image));
            $this->assertStringStartsWith('uploads/companions/', $wolf->artwork_image);
            $this->assertSame('Wolf artwork description', $wolf->artwork_image_alt);

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
