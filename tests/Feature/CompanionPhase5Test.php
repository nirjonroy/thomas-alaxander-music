<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompanionPhase5Test extends TestCase
{
    use DatabaseTransactions;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Phase 5 Admin',
            'email' => 'phase5-admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 2,
        ]);
        $this->adminUser->active_status = 1;
        $this->adminUser->save();

        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        if ($adminRole) {
            $this->adminUser->assignRole($adminRole);
        }
    }

    /**
     * 1. Saving one Companion never alters another Companion's data.
     */
    public function test_saving_one_companion_does_not_modify_another(): void
    {
        $this->actingAs($this->adminUser, 'web');

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();
        $hawk = JourneyCompanion::where('slug', 'hawk')->firstOrFail();

        $initialHawkThemes = $hawk->themes;
        $initialHawkTeaching = $hawk->expanded_teaching;

        $response = $this->put(route('admin.companions-journey.update', $wolf->id), [
            'themes' => 'Updated Wolf Themes Only',
            'expanded_teaching' => '<p>Updated Wolf Teaching</p>',
            'is_active' => false,
        ]);

        $response->assertRedirect(route('admin.companions-journey.index'));

        $wolf->refresh();
        $hawk->refresh();

        $this->assertEquals('Updated Wolf Themes Only', $wolf->themes);
        $this->assertEquals('<p>Updated Wolf Teaching</p>', $wolf->expanded_teaching);

        // Hawk must be completely unchanged
        $this->assertEquals($initialHawkThemes, $hawk->themes);
        $this->assertEquals($initialHawkTeaching, $hawk->expanded_teaching);
    }

    /**
     * 2. Page settings persistence and reload including the fixed witness_sunlight_title field.
     */
    public function test_page_settings_persist_and_sunlight_title_renders_correctly(): void
    {
        $this->actingAs($this->adminUser, 'web');

        $setting = CompanionPageSetting::firstOrCreate(['id' => 1]);

        $response = $this->put(route('admin.companions-journey.settings.update'), [
            'is_active' => false,
            'entrance_title' => 'COMPANIONS ALONG THE JOURNEY',
            'witnesses_title' => 'The Witnesses',
            'witness_sunlight_title' => 'Sunlight — Calling Life Forward',
            'witness_sunlight_body' => '<p>Sunlight body paragraph content</p>',
        ]);

        $response->assertRedirect();

        $setting->refresh();
        $this->assertEquals('Sunlight — Calling Life Forward', $setting->witness_sunlight_title);
        $this->assertEquals('<p>Sunlight body paragraph content</p>', $setting->witness_sunlight_body);

        // Verify that the settings edit view binds the title correctly, not the body
        $viewResponse = $this->get(route('admin.companions-journey.settings'));
        $viewResponse->assertOk();
        $viewResponse->assertSee('value="Sunlight — Calling Life Forward"', false);
    }

    /**
     * 3. Dynamic artwork upload, replacement, and removal behavior.
     */
    public function test_dynamic_artwork_upload_and_removal_workflow(): void
    {
        $this->actingAs($this->adminUser, 'web');

        $otter = JourneyCompanion::where('slug', 'otter')->firstOrFail();
        $originalOtterPath = $otter->artwork_image;
        $originalOtterBytes = ($originalOtterPath && file_exists(public_path($originalOtterPath)))
            ? file_get_contents(public_path($originalOtterPath))
            : null;

        try {
            // 1. Upload initial artwork image
            $initialImage = $this->createFakeImage('otter_art.png', 'image/png');

            $this->put(route('admin.companions-journey.update', $otter->id), [
                'artwork_image' => $initialImage,
                'artwork_image_alt' => 'Otter Painting',
                'is_active' => false,
            ])->assertRedirect(route('admin.companions-journey.index'));

            $otter->refresh();
            $this->assertNotNull($otter->artwork_image);
            $this->assertStringStartsWith('uploads/companions/', $otter->artwork_image);
            $this->assertFileExists(public_path($otter->artwork_image));
            $firstSavedPath = $otter->artwork_image;

            // 2. Replace with a new artwork image
            $replacementImage = $this->createFakeImage('otter_art_v2.jpg', 'image/jpeg');

            $this->put(route('admin.companions-journey.update', $otter->id), [
                'artwork_image' => $replacementImage,
                'artwork_image_alt' => 'Otter Painting V2',
                'is_active' => false,
            ])->assertRedirect(route('admin.companions-journey.index'));

            $otter->refresh();
            $this->assertNotNull($otter->artwork_image);
            $this->assertNotEquals($firstSavedPath, $otter->artwork_image);
            $this->assertFileExists(public_path($otter->artwork_image));
            // The old file must have been cleaned up
            $this->assertFileDoesNotExist(public_path($firstSavedPath));
            $secondSavedPath = $otter->artwork_image;

            // 3. Remove the artwork via the removal checkbox
            $this->put(route('admin.companions-journey.update', $otter->id), [
                'remove_artwork_image' => '1',
                'is_active' => false,
            ])->assertRedirect(route('admin.companions-journey.index'));

            $otter->refresh();
            $this->assertNull($otter->artwork_image);
            $this->assertFileDoesNotExist(public_path($secondSavedPath));
        } finally {
            if ($originalOtterPath && $originalOtterBytes !== null) {
                file_put_contents(public_path($originalOtterPath), $originalOtterBytes);
            }
        }
    }

    /**
     * 4. Independent entrance crest and closing crest uploads.
     */
    public function test_independent_crest_uploads(): void
    {
        $this->actingAs($this->adminUser, 'web');

        $setting = CompanionPageSetting::firstOrCreate(['id' => 1]);

        $entranceCrest = $this->createFakeImage('entrance_crest.png', 'image/png');
        $closingCrest = $this->createFakeImage('closing_crest.png', 'image/png');

        // Upload entrance crest only
        $this->put(route('admin.companions-journey.settings.update'), [
            'entrance_crest_image' => $entranceCrest,
            'entrance_crest_image_alt' => 'Entrance Crest Alt',
            'is_active' => false,
        ])->assertRedirect();

        $setting->refresh();
        $this->assertNotNull($setting->entrance_crest_image);
        $this->assertNull($setting->closing_crest_image);
        $entrancePath = $setting->entrance_crest_image;

        // Upload closing crest only
        $this->put(route('admin.companions-journey.settings.update'), [
            'closing_crest_image' => $closingCrest,
            'closing_crest_image_alt' => 'Closing Crest Alt',
            'is_active' => false,
        ])->assertRedirect();

        $setting->refresh();
        // Entrance crest is untouched
        $this->assertEquals($entrancePath, $setting->entrance_crest_image);
        $this->assertNotNull($setting->closing_crest_image);
        $closingPath = $setting->closing_crest_image;

        // Cleanup
        $this->put(route('admin.companions-journey.settings.update'), [
            'remove_entrance_crest_image' => '1',
            'remove_closing_crest_image' => '1',
            'is_active' => false,
        ]);
        $this->assertFileDoesNotExist(public_path($entrancePath));
        $this->assertFileDoesNotExist(public_path($closingPath));
    }

    /**
     * 5. Admin Preview returns noindex, nofollow, and private/no-store cache control headers.
     */
    public function test_admin_preview_security_and_cache_headers(): void
    {
        $this->actingAs($this->adminUser, 'web');

        $response = $this->get(route('admin.companions-journey.preview'));

        $response->assertOk();
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
        $response->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    /**
     * 6. Rich text renders safe HTML while rejecting malicious scripts and attributes.
     */
    public function test_rich_text_sanitizes_scripts_and_malicious_attributes(): void
    {
        $this->actingAs($this->adminUser, 'web');

        $wolf = JourneyCompanion::where('slug', 'wolf')->firstOrFail();

        $xssPayload = '<p>Good teaching <em>text</em></p><script>alert("xss")</script><img src="invalid.jpg" onerror="alert(1)">';

        $updateResp = $this->put(route('admin.companions-journey.update', $wolf->id), [
            'expanded_teaching' => $xssPayload,
            'is_active' => false,
        ]);
        $updateResp->assertSessionHasNoErrors();
        $updateResp->assertRedirect(route('admin.companions-journey.index'));

        $wolf->refresh();
        // The script tag must be eliminated
        $this->assertStringNotContainsString('<script>', $wolf->expanded_teaching);
        $this->assertStringNotContainsString('</script>', $wolf->expanded_teaching);

        $previewResponse = $this->get(route('admin.companions-journey.preview'));
        $previewResponse->assertOk();

        // Safe markup is preserved
        $previewResponse->assertSee('<p>Good teaching <em>text</em></p>', false);
        // Script tag and malicious event handlers are rejected/sanitized
        $previewResponse->assertDontSee('<script>', false);
        $previewResponse->assertDontSee('onerror=', false);
    }

    private function createFakeImage(string $name, string $mimeType = 'image/png'): UploadedFile
    {
        if (function_exists('imagecreatetruecolor')) {
            return UploadedFile::fake()->image($name, 400, 400);
        }

        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $temp = tempnam(sys_get_temp_dir(), 'test_img_');
        file_put_contents($temp, base64_decode($pngBase64));

        return new UploadedFile($temp, $name, $mimeType, null, true);
    }
}
