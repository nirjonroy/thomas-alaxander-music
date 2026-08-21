<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\EpkPage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EpkAdminTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_manage_epk_page(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.epk-page.store'), [
            'title' => 'EPK Test Page',
            'slug' => 'epk-test-page',
            'subtitle' => 'Thomas Alexander - The Voice',
            'overview_content' => '<p>Overview content.</p>',
            'section_titles' => ['Highlights'],
            'section_bodies' => ['<p>Highlight content.</p>'],
            'audio_url' => 'https://example.com/audio.mp3',
            'audio_title' => 'Audio Sample',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_title' => 'Video Sample',
            'booking_email' => 'booking@example.com',
            'status' => 1,
            'sort_order' => 3,
            'seo_title' => 'EPK Test SEO',
            'seo_description' => 'EPK test SEO description.',
            'published_at' => now()->format('Y-m-d H:i'),
            'hero_image' => UploadedFile::fake()->create('hero.jpg', 12, 'image/jpeg'),
            'hero_image_alt' => 'Hero alt',
            'gold_feather_image' => UploadedFile::fake()->create('logo.jpg', 12, 'image/jpeg'),
            'gold_feather_image_alt' => 'Logo alt',
            'og_image' => UploadedFile::fake()->create('og.jpg', 12, 'image/jpeg'),
            'og_image_alt' => 'OG alt',
        ])->assertRedirect(route('admin.epk-page.index'));

        $page = EpkPage::where('slug', 'epk-test-page')->firstOrFail();

        $this->assertSame('booking@example.com', $page->booking_email);
        $this->assertSame('Highlights', $page->sections[0]['title']);
        $this->assertFileExists(public_path($page->hero_image));
        $this->assertFileExists(public_path($page->gold_feather_image));
        $this->assertFileExists(public_path($page->og_image));

        $oldHero = $page->hero_image;

        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Updated EPK Test Page',
            'slug' => 'epk-test-page',
            'subtitle' => 'Updated subtitle',
            'overview_content' => '<p>Updated overview.</p>',
            'section_titles' => ['Updated Highlights'],
            'section_bodies' => ['<p>Updated highlight content.</p>'],
            'booking_email' => 'updated-booking@example.com',
            'status' => 1,
            'sort_order' => 5,
            'remove_hero_image' => 1,
        ])->assertRedirect(route('admin.epk-page.index'));

        $page->refresh();
        $this->assertSame('Updated EPK Test Page', $page->title);
        $this->assertSame('updated-booking@example.com', $page->booking_email);
        $this->assertNull($page->hero_image);
        $this->assertFileDoesNotExist(public_path($oldHero));

        $this->put(route('admin.epk-page.status', $page->id))->assertOk();
        $this->assertFalse($page->fresh()->status);

        $logo = $page->gold_feather_image;
        $og = $page->og_image;

        $this->delete(route('admin.epk-page.destroy', $page->id))->assertRedirect();

        $this->assertDatabaseMissing('epk_pages', ['id' => $page->id]);
        $this->assertFileDoesNotExist(public_path($logo));
        $this->assertFileDoesNotExist(public_path($og));
    }

    public function test_admin_duplicate_slug_validation_fails(): void
    {
        $this->actingAsAdmin();

        EpkPage::create([
            'title' => 'Existing EPK',
            'slug' => 'existing-epk',
            'status' => true,
        ]);

        $this->from(route('admin.epk-page.create'))
            ->post(route('admin.epk-page.store'), [
                'title' => 'New EPK',
                'slug' => 'existing-epk',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.epk-page.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_full_artist_structured_content_and_upload_audio(): void
    {
        $this->actingAsAdmin();

        $page = EpkPage::updateOrCreate(
            ['slug' => 'full-artist'],
            [
                'title' => 'Full Artist EPK',
                'status' => true,
                'sections' => [],
                'audio_url' => null,
                'hero_image' => null,
            ]
        );

        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Thomas Alexander - The Voice',
            'slug' => 'full-artist',
            'subtitle' => 'Exclusively Presented by Five Feathers Music Agency',
            'overview_content' => '<script>alert("x")</script><p>Updated artist overview.</p>',
            'lane_titles' => ['Symphony & Pops', 'Crooners'],
            'lane_bodies' => ['Orchestral stages', 'Classic standards'],
            'engagement_items' => ['Winspear Centre', 'MacEwan University'],
            'repertoire_items' => ['Nessun Dorma', 'Misty'],
            'testimonial_sources' => ['Charles Austin'],
            'testimonial_credentials' => ['Professor Emeritus'],
            'testimonial_quotes' => ['A remarkable voice.'],
            'audio_caption' => 'Preview medley available in-browser.',
            'booking_body' => '<p>Five Feathers Music Agency handles booking.</p>',
            'booking_email' => 'info@thomasalexanderthevoice.com',
            'status' => 1,
            'sort_order' => 1,
            'hero_image' => UploadedFile::fake()->create('full-artist-hero.jpg', 12, 'image/jpeg'),
            'audio_file' => UploadedFile::fake()->create('medley.mp3', 64, 'audio/mpeg'),
        ])->assertRedirect(route('admin.epk-page.index'));

        $page->refresh();

        $this->assertStringNotContainsString('<script', $page->overview_content);
        $this->assertSame('performance_lanes', $page->sections[0]['type']);
        $this->assertSame('Symphony & Pops', $page->sections[0]['items'][0]['title']);
        $this->assertSame('medley', collect($page->sections)->firstWhere('type', 'medley')['type']);
        $this->assertFileExists(public_path($page->hero_image));
        $this->assertFileExists(public_path($page->audio_url));

        $oldHero = $page->hero_image;
        $oldAudio = $page->audio_url;

        $this->get(route('front.epk.full-artist'))
            ->assertOk()
            ->assertSee('Updated artist overview.', false)
            ->assertSee('Symphony &amp; Pops', false)
            ->assertSee('Preview medley available in-browser.', false)
            ->assertSee(asset($page->audio_url), false);

        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Thomas Alexander - The Voice',
            'slug' => 'full-artist',
            'subtitle' => 'Updated',
            'overview_content' => '<p>Updated again.</p>',
            'lane_titles' => ['Symphony & Pops'],
            'lane_bodies' => ['Orchestral stages'],
            'engagement_items' => ['Winspear Centre'],
            'repertoire_items' => ['Misty'],
            'testimonial_sources' => ['Lonnie H.'],
            'testimonial_quotes' => ['Wonderful.'],
            'audio_caption' => 'Updated medley.',
            'booking_body' => '<p>Booking body.</p>',
            'booking_email' => 'info@thomasalexanderthevoice.com',
            'status' => 1,
            'sort_order' => 1,
            'hero_image' => UploadedFile::fake()->create('full-artist-new.jpg', 12, 'image/jpeg'),
            'audio_file' => UploadedFile::fake()->create('new-medley.mp3', 64, 'audio/mpeg'),
        ])->assertRedirect(route('admin.epk-page.index'));

        $page->refresh();

        $this->assertFileDoesNotExist(public_path($oldHero));
        $this->assertFileDoesNotExist(public_path($oldAudio));
        $this->assertFileExists(public_path($page->hero_image));
        $this->assertFileExists(public_path($page->audio_url));

        $audioToRemove = $page->audio_url;

        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Thomas Alexander - The Voice',
            'slug' => 'full-artist',
            'overview_content' => '<p>Audio removed.</p>',
            'lane_titles' => ['Symphony & Pops'],
            'lane_bodies' => ['Orchestral stages'],
            'status' => 1,
            'sort_order' => 1,
            'remove_audio_file' => 1,
        ])->assertRedirect(route('admin.epk-page.index'));

        $page->refresh();

        $this->assertNull($page->audio_url);
        $this->assertFileDoesNotExist(public_path($audioToRemove));

        if ($page->hero_image && file_exists(public_path($page->hero_image))) {
            unlink(public_path($page->hero_image));
        }
    }

    public function test_admin_can_update_crooners_structured_content_and_public_video_renders(): void
    {
        $this->actingAsAdmin();

        $page = EpkPage::updateOrCreate(
            ['slug' => 'crooners'],
            [
                'title' => 'CROONERS',
                'status' => true,
                'sections' => [],
            ]
        );

        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'CROONERS',
            'slug' => 'crooners',
            'subtitle' => 'A Legacy of Elegance, Heritage, and Timeless Musical Artistry',
            'overview_content' => '<p>Crooners overview from client material.</p>',
            'repertoire_items' => ['Misty', 'Fly Me to the Moon', 'Misty'],
            'testimonial_sources' => ['Charles Austin, ARCT, BMus, ME', 'Lonnie H.'],
            'testimonial_credentials' => ['Professor Emeritus, MacEwan University', ''],
            'testimonial_quotes' => ['Client-approved Charles Austin testimonial.', 'Client-approved Lonnie H. testimonial.'],
            'video_intro' => 'Official Thomas Alexander live performance.',
            'video_url' => 'https://youtu.be/stRFQS7qAiU',
            'video_title' => 'Thomas Alexander Live Performance',
            'booking_body' => '<p>Full Crooners EPK available upon request through Five Feathers Music Agency.</p>',
            'booking_email' => 'info@thomasalexanderthevoice.com',
            'status' => 1,
            'sort_order' => 2,
        ])->assertRedirect(route('admin.epk-page.index'));

        $page->refresh();
        $repertoire = collect($page->sections)->firstWhere('type', 'tags')['items'];

        $this->assertSame(['Misty', 'Fly Me to the Moon'], $repertoire);

        $this->get(route('front.epk.crooners'))
            ->assertOk()
            ->assertSee('CROONERS')
            ->assertSee('youtube-nocookie.com/embed/stRFQS7qAiU', false)
            ->assertSee('Request EPK')
            ->assertSee('Full Crooners EPK available upon request through Five Feathers Music Agency.', false)
            ->assertDontSee('.pdf', false);
    }

    public function test_epk_validation_rejects_non_youtube_video_and_invalid_audio(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.epk-page.create'))
            ->post(route('admin.epk-page.store'), [
                'title' => 'Invalid Media EPK',
                'slug' => 'invalid-media-epk',
                'status' => 1,
                'video_url' => 'https://vimeo.com/123456',
                'audio_file' => UploadedFile::fake()->create('bad.pdf', 4, 'application/pdf'),
            ])
            ->assertRedirect(route('admin.epk-page.create'))
            ->assertSessionHasErrors(['video_url', 'audio_file']);
    }

    public function test_inactive_epk_is_hidden_publicly_after_admin_update(): void
    {
        $this->actingAsAdmin();

        $page = EpkPage::create([
            'title' => 'Inactive EPK',
            'slug' => 'inactive-epk',
            'status' => true,
        ]);

        $this->put(route('admin.epk-page.update', $page->id), [
            'title' => 'Inactive EPK',
            'slug' => 'inactive-epk',
            'overview_content' => '<p>Hidden content.</p>',
            'status' => 0,
            'sort_order' => 0,
        ])->assertRedirect(route('admin.epk-page.index'));

        $this->get(route('front.epk.show', 'inactive-epk'))->assertNotFound();
    }

    private function actingAsAdmin(): Admin
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'epk-admin@example.com'],
            [
                'name' => 'EPK Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }
}
