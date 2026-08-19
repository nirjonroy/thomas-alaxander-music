<?php

namespace Tests\Feature;

use App\Models\LivingArchiveEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LivingArchiveRouteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_valid_root_page_loads(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive/memoir')
            ->assertOk()
            ->assertSee('Memoir');
    }

    public function test_valid_nested_page_loads(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive/heritage/prairie-migration/breton-registry')
            ->assertOk()
            ->assertSee('Breton Registry')
            ->assertSee('Prairie Migration');
    }

    public function test_invalid_nested_path_returns_not_found(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive/memoir/the-crossing')
            ->assertNotFound();
    }

    public function test_inactive_page_returns_not_found(): void
    {
        $entries = $this->seedArchiveHierarchy();

        $this->archiveEntry([
            'parent_id' => $entries['memoir']->id,
            'title' => 'Inactive Archive Page',
            'slug' => 'inactive-archive-page',
            'section_label' => 'Memoir',
            'status' => false,
            'sort_order' => 99,
        ]);

        $this->get('/living-archive/memoir/inactive-archive-page')
            ->assertNotFound();
    }

    public function test_previous_and_next_navigation_uses_sibling_order(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive/heritage/underground-railroad')
            ->assertOk()
            ->assertSee('Previous')
            ->assertSee('Black Loyalists')
            ->assertSee('Next')
            ->assertSee('Railway Porters');
    }

    public function test_landing_page_renders_archive_gateways(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive')
            ->assertOk()
            ->assertSee('Explore the Living Archive')
            ->assertSee('Memoir')
            ->assertSee('/living-archive/memoir/the-road-north', false)
            ->assertSee('Ceremonial Lineage')
            ->assertSee('/living-archive/ceremonial-lineage/the-crossing', false)
            ->assertSee('Heritage')
            ->assertSee('/living-archive/heritage/railway-porters', false)
            ->assertSee('View the Complete Archive');
    }

    public function test_landing_page_preserves_existing_living_archive_sections(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive')
            ->assertOk()
            ->assertSee('Ceremonial Introduction')
            ->assertSee('Dual Identity')
            ->assertSee('The Three Crests')
            ->assertSee('About the Lineage')
            ->assertSee('Carrier Pathway')
            ->assertSee('Media &amp; Merch', false)
            ->assertSee('Contact &amp; Invitations', false)
            ->assertSee('Printable Certification');
    }

    public function test_all_seeded_archive_urls_load(): void
    {
        $this->seedArchiveHierarchy();

        $urls = [
            '/living-archive/memoir',
            '/living-archive/memoir/the-road-north',
            '/living-archive/ceremonial-lineage',
            '/living-archive/ceremonial-lineage/the-crossing',
            '/living-archive/heritage',
            '/living-archive/heritage/prairie-migration',
            '/living-archive/heritage/prairie-migration/the-alexander-thread',
            '/living-archive/heritage/prairie-migration/breton-registry',
            '/living-archive/heritage/black-loyalists',
            '/living-archive/heritage/underground-railroad',
            '/living-archive/heritage/railway-porters',
            '/living-archive/heritage/railway-porters/roy-williams',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_category_page_uses_child_grid_and_back_cta(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive/heritage')
            ->assertOk()
            ->assertSee('Archive Records')
            ->assertSee('Prairie Migration')
            ->assertSee('Black Loyalists')
            ->assertSee('Back to Living Archive');
    }

    public function test_article_page_uses_article_layout_and_parent_cta(): void
    {
        $this->seedArchiveHierarchy();

        $this->get('/living-archive/heritage/prairie-migration/the-alexander-thread')
            ->assertOk()
            ->assertSee('<article class="archive-article">', false)
            ->assertSee('Back to Prairie Migration')
            ->assertSee('Back to Living Archive');
    }

    public function test_article_document_block_renders_accessible_dialog(): void
    {
        $entries = $this->seedArchiveHierarchy();

        $entries['bretonRegistry']->update([
            'document_image' => 'uploads/living-archive/breton-registry.jpg',
            'document_image_alt' => '1918 Breton registry document for the Alexander family.',
            'document_caption' => 'Breton registry source document.',
        ]);

        $this->get('/living-archive/heritage/prairie-migration/breton-registry')
            ->assertOk()
            ->assertSee('Open document larger')
            ->assertSee('<dialog class="archive-dialog"', false)
            ->assertSee('alt="1918 Breton registry document for the Alexander family."', false)
            ->assertSee('Breton registry source document.');
    }

    public function test_archive_page_outputs_unique_metadata_and_structured_data(): void
    {
        $entries = $this->seedArchiveHierarchy();

        $entries['alexanderThread']->update([
            'meta_title' => 'The Alexander Thread SEO Title',
            'meta_description' => 'The Alexander Thread SEO description.',
            'og_image' => 'uploads/living-archive/alexander-thread-og.jpg',
            'og_image_alt' => 'Alexander Thread archive image.',
        ]);
        $canonical = url('/living-archive/heritage/prairie-migration/the-alexander-thread');
        $ogImage = asset('uploads/living-archive/alexander-thread-og.jpg');

        $response = $this->get('/living-archive/heritage/prairie-migration/the-alexander-thread');

        $response
            ->assertOk()
            ->assertSee('<title>The Alexander Thread SEO Title</title>', false)
            ->assertSee('<meta name="description" content="The Alexander Thread SEO description.">', false)
            ->assertSee('<link rel="canonical" href="'.$canonical.'">', false)
            ->assertSee('<meta property="og:image" content="'.$ogImage.'">', false)
            ->assertSee('<meta property="og:image:alt" content="Alexander Thread archive image.">', false)
            ->assertSee('<meta name="twitter:url" content="'.$canonical.'">', false)
            ->assertSee('BreadcrumbList')
            ->assertSee('CreativeWork')
            ->assertSee('Living Archive Navigation');

        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
    }

    public function test_sitemap_includes_only_published_archive_pages(): void
    {
        $entries = $this->seedArchiveHierarchy();

        $this->archiveEntry([
            'parent_id' => $entries['heritage']->id,
            'title' => 'Draft Heritage Page',
            'slug' => 'draft-heritage-page',
            'status' => false,
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/living-archive/heritage/prairie-migration/the-alexander-thread')
            ->assertSee('/living-archive/heritage/railway-porters/roy-williams')
            ->assertDontSee('/living-archive/heritage/draft-heritage-page');
    }

    private function seedArchiveHierarchy(): array
    {
        $memoir = $this->archiveEntry([
            'title' => 'Memoir',
            'slug' => 'memoir',
            'section_label' => 'Living Archive',
            'page_type' => 'archive_section',
            'sort_order' => 10,
        ]);

        $ceremonialLineage = $this->archiveEntry([
            'title' => 'Ceremonial Lineage',
            'slug' => 'ceremonial-lineage',
            'section_label' => 'Living Archive',
            'page_type' => 'archive_section',
            'sort_order' => 20,
        ]);

        $heritage = $this->archiveEntry([
            'title' => 'Heritage',
            'slug' => 'heritage',
            'section_label' => 'Living Archive',
            'page_type' => 'archive_section',
            'sort_order' => 30,
        ]);

        $roadNorth = $this->archiveEntry([
            'parent_id' => $memoir->id,
            'title' => 'The Road North',
            'slug' => 'the-road-north',
            'section_label' => 'Memoir',
            'sort_order' => 10,
        ]);

        $crossing = $this->archiveEntry([
            'parent_id' => $ceremonialLineage->id,
            'title' => 'The Crossing',
            'slug' => 'the-crossing',
            'section_label' => 'Ceremonial Lineage',
            'sort_order' => 10,
        ]);

        $prairieMigration = $this->archiveEntry([
            'parent_id' => $heritage->id,
            'title' => 'Prairie Migration',
            'slug' => 'prairie-migration',
            'section_label' => 'Heritage',
            'sort_order' => 10,
        ]);

        $alexanderThread = $this->archiveEntry([
            'parent_id' => $prairieMigration->id,
            'title' => 'The Alexander Thread',
            'slug' => 'the-alexander-thread',
            'section_label' => 'Prairie Migration',
            'sort_order' => 10,
        ]);

        $bretonRegistry = $this->archiveEntry([
            'parent_id' => $prairieMigration->id,
            'title' => 'Breton Registry',
            'slug' => 'breton-registry',
            'section_label' => 'Prairie Migration',
            'sort_order' => 20,
        ]);

        $blackLoyalists = $this->archiveEntry([
            'parent_id' => $heritage->id,
            'title' => 'Black Loyalists',
            'slug' => 'black-loyalists',
            'section_label' => 'Heritage',
            'sort_order' => 20,
        ]);

        $undergroundRailroad = $this->archiveEntry([
            'parent_id' => $heritage->id,
            'title' => 'Underground Railroad',
            'slug' => 'underground-railroad',
            'section_label' => 'Heritage',
            'sort_order' => 30,
        ]);

        $railwayPorters = $this->archiveEntry([
            'parent_id' => $heritage->id,
            'title' => 'Railway Porters',
            'slug' => 'railway-porters',
            'section_label' => 'Heritage',
            'sort_order' => 40,
        ]);

        $royWilliams = $this->archiveEntry([
            'parent_id' => $railwayPorters->id,
            'title' => 'Roy Williams',
            'slug' => 'roy-williams',
            'section_label' => 'Railway Porters',
            'sort_order' => 10,
        ]);

        return compact(
            'memoir',
            'ceremonialLineage',
            'heritage',
            'roadNorth',
            'crossing',
            'prairieMigration',
            'alexanderThread',
            'bretonRegistry',
            'blackLoyalists',
            'undergroundRailroad',
            'railwayPorters',
            'royWilliams'
        );
    }

    private function archiveEntry(array $attributes): LivingArchiveEntry
    {
        return LivingArchiveEntry::updateOrCreate(
            ['slug' => $attributes['slug']],
            array_merge([
                'parent_id' => null,
                'teaser' => 'Test archive teaser.',
                'content' => 'Full archive content will be added from the client-provided material.',
                'page_type' => 'archive_page',
                'status' => true,
                'meta_title' => $attributes['title'],
                'meta_description' => 'Test archive teaser.',
                'published_at' => now(),
            ], $attributes)
        );
    }
}
