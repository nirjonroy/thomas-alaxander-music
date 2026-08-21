<?php

namespace Tests\Feature;

use App\Models\EpkPage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EpkRouteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_full_artist_epk_loads(): void
    {
        $this->epkPage([
            'title' => 'Thomas Alexander — The Voice',
            'slug' => 'full-artist',
            'subtitle' => 'Exclusively Presented & Represented by Five Feathers Music Agency',
            'overview_content' => '<p>Thomas Alexander — The Voice — is a heritage-rooted vocalist whose artistry is shaped by his Copper-colored skin lineage, unified Black Indigenous ancestry, and deep Alberta homesteader roots. His performances blend classic jazz, soul, crooner repertoire, big band features, tribute show mastery, and narrative storytelling with a vocal warmth carried through generations. His career spans intimate jazz clubs, orchestral halls, cultural showcases, and touring productions across Western Canada.</p>',
            'sections' => [
                [
                    'type' => 'performance_lanes',
                    'title' => 'Performance Lanes',
                    'items' => [
                        ['title' => 'Jazz & Soul', 'body' => 'Alvin’s Jazz Club, Yardbird Suite'],
                    ],
                ],
                [
                    'type' => 'engagements',
                    'title' => 'Notable Engagements',
                    'items' => ['Twilight Time — Lead Male Vocalist'],
                ],
                [
                    'type' => 'tags',
                    'title' => 'Featured Repertoire',
                    'items' => ['Jazz Standards'],
                ],
                [
                    'type' => 'testimonials',
                    'title' => 'Testimonials',
                    'items' => [
                        [
                            'source' => 'Charles Austin, ARCT, BMus, ME — Professor Emeritus, MacEwan University',
                            'quote' => 'Thomas is a gifted performer who is professional and engaging on stage... worthy of serious consideration and merit.',
                        ],
                    ],
                ],
                [
                    'type' => 'medley',
                    'title' => 'Live Performance Medley',
                    'body' => 'Audio Medley — Thomas Alexander (The Voice)<br>Presented by Five Feathers Music Agency',
                ],
                [
                    'type' => 'booking',
                    'title' => 'Booking & Representation',
                    'body' => '<p>Five Feathers Music Agency</p><p>Exclusive Representatives for<br>Thomas Alexander — The Voice</p><p><a href="mailto:info@thomasalexanderthevoice.com">info@thomasalexanderthevoice.com</a></p><p>Edmonton, Alberta, Canada</p>',
                ],
            ],
            'booking_email' => 'info@thomasalexanderthevoice.com',
            'seo_title' => 'Thomas Alexander — The Voice | Full Artist EPK',
            'seo_description' => 'Explore the official Thomas Alexander — The Voice electronic press kit, featuring artist biography, repertoire, notable engagements, testimonials and booking information through Five Feathers Music Agency.',
        ]);
        $this->epkPage([
            'title' => 'CROONERS',
            'slug' => 'crooners',
            'sort_order' => 2,
        ]);

        $this->get('/epk/full-artist')
            ->assertOk()
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSee('Full Artist')
            ->assertSee('Thomas Alexander')
            ->assertSee('Artist Overview')
            ->assertSee('Performance Lanes')
            ->assertSee('Notable Engagements')
            ->assertSee('Featured Repertoire')
            ->assertSee('Testimonials')
            ->assertSee('Live Performance Medley')
            ->assertSee('Booking &amp; Representation', false)
            ->assertSee('Request EPK')
            ->assertSee('/contact-us?inquiry=epk', false)
            ->assertSee('Explore Crooners EPK')
            ->assertSee('EPK%20Request', false)
            ->assertSee('Thomas Alexander — The Voice | Full Artist EPK', false);
    }

    public function test_crooners_epk_loads(): void
    {
        $this->epkPage([
            'title' => 'Thomas Alexander',
            'slug' => 'full-artist',
            'sort_order' => 1,
        ]);
        $this->epkPage([
            'title' => 'CROONERS',
            'slug' => 'crooners',
            'sort_order' => 2,
            'subtitle' => 'A Legacy of Elegance, Heritage, and Timeless Musical Artistry',
            'overview_content' => '<p>A celebration of timeless vocal artistry, blending classic jazz, swing, and beloved standards with Thomas Alexander’s signature warmth and stage presence.</p>',
            'sections' => [
                [
                    'type' => 'tags',
                    'title' => 'Featured Selections',
                    'items' => ['Fly Me to the Moon', 'Twilight Time', 'Smoke Gets in Your Eyes'],
                ],
                [
                    'type' => 'testimonials',
                    'title' => 'Testimonials',
                    'items' => [
                        [
                            'source' => 'Charles Austin, ARCT, BMus, ME',
                            'credential' => 'Professor Emeritus, MacEwan University',
                            'quote' => 'Thomas is a gifted performer who is professional and engaging on stage... worthy of serious consideration and merit.',
                        ],
                        [
                            'source' => 'Lonnie H.',
                            'quote' => 'Thomas Alexander brings a level of class and vocal mastery rarely heard today.',
                        ],
                    ],
                ],
                [
                    'type' => 'video',
                    'title' => 'Live Performance',
                    'url' => 'https://www.youtube.com/watch?v=stRFQS7qAiU',
                ],
                [
                    'type' => 'booking',
                    'title' => 'Booking & Contact',
                    'body' => '<p>Full Crooners EPK available upon request through Five Feathers Music Agency.</p>',
                ],
            ],
            'video_url' => 'https://www.youtube.com/watch?v=stRFQS7qAiU',
            'booking_email' => 'info@thomasalexanderthevoice.com',
            'seo_title' => 'Thomas Alexander — The Voice | Crooners EPK',
            'seo_description' => 'Explore the Crooners EPK for Thomas Alexander — The Voice, featuring timeless jazz standards, repertoire, testimonials, live performance and booking information.',
        ]);

        $this->get('/epk/crooners')
            ->assertOk()
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSee('CROONERS')
            ->assertSee('Thomas Alexander — The Voice', false)
            ->assertSee('A Legacy of Elegance, Heritage, and Timeless Musical Artistry')
            ->assertSee('Presented &amp; Represented by Five Feathers Music Agency', false)
            ->assertSee('Featured Selections')
            ->assertSee('Fly Me to the Moon')
            ->assertSee('Smoke Gets in Your Eyes')
            ->assertSee('Testimonials')
            ->assertSee('Charles Austin')
            ->assertSee('Lonnie H.')
            ->assertSee('Live Performance')
            ->assertSee('youtube-nocookie.com/embed/stRFQS7qAiU', false)
            ->assertSee('Request EPK')
            ->assertSee('/contact-us?inquiry=epk', false)
            ->assertSee('Explore Full Artist EPK')
            ->assertSee('Crooners%20EPK%20Request', false)
            ->assertSee('Full Crooners EPK available upon request through Five Feathers Music Agency.')
            ->assertDontSee('.pdf');
    }

    public function test_inactive_epk_page_is_hidden(): void
    {
        $this->epkPage([
            'title' => 'Draft EPK',
            'slug' => 'full-artist',
            'status' => false,
        ]);

        $this->get('/epk/full-artist')->assertNotFound();
    }

    public function test_unknown_epk_slug_is_not_found(): void
    {
        $this->get('/epk/unknown')->assertNotFound();
    }

    public function test_sitemap_includes_published_epk_pages_only(): void
    {
        $this->epkPage(['title' => 'EPK - Full Artist', 'slug' => 'full-artist']);
        $this->epkPage(['title' => 'EPK - Crooners', 'slug' => 'crooners']);
        $this->epkPage(['title' => 'Hidden EPK', 'slug' => 'hidden-epk', 'status' => false]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/epk/full-artist')
            ->assertSee('/epk/crooners')
            ->assertDontSee('/epk/hidden-epk');
    }

    private function epkPage(array $attributes): EpkPage
    {
        return EpkPage::updateOrCreate(
            ['slug' => $attributes['slug']],
            array_merge([
                'title' => 'EPK Page',
                'subtitle' => 'Thomas Alexander - The Voice',
                'overview_content' => '<p>EPK overview content.</p>',
                'sections' => [
                    ['title' => 'Press Notes', 'body' => '<p>Press-ready content.</p>'],
                ],
                'status' => true,
                'sort_order' => 1,
                'published_at' => now(),
            ], $attributes)
        );
    }
}
