<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\LivingArchiveEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LivingArchiveAdminTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_edit_publish_unpublish_and_delete_archive_page(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.living-archive-entry.store'), [
            'title' => 'Admin Archive Page',
            'slug' => 'admin-archive-page',
            'section_label' => 'Test Section',
            'parent_id' => null,
            'page_type' => 'historical_article',
            'teaser' => 'A short archive teaser.',
            'content' => '<p>Editor content.</p>',
            'document_caption' => 'Source note.',
            'sort_order' => 12,
            'status' => 1,
            'meta_title' => 'Admin Archive Page Meta',
            'meta_description' => 'Meta description.',
            'published_at' => now()->format('Y-m-d H:i'),
            'featured_image' => UploadedFile::fake()->image('featured.jpg', 40, 40),
            'featured_image_alt' => 'Featured archive alt text.',
            'document_image' => UploadedFile::fake()->image('document.jpg', 40, 40),
            'document_image_alt' => 'Document archive alt text.',
            'og_image' => UploadedFile::fake()->image('og.jpg', 40, 40),
            'og_image_alt' => 'OG archive alt text.',
        ])->assertRedirect(route('admin.living-archive-entry.index'));

        $entry = LivingArchiveEntry::where('slug', 'admin-archive-page')->firstOrFail();

        $this->assertSame('historical_article', $entry->page_type);
        $this->assertSame('Featured archive alt text.', $entry->featured_image_alt);
        $this->assertSame('Document archive alt text.', $entry->document_image_alt);
        $this->assertSame('OG archive alt text.', $entry->og_image_alt);
        $this->assertFileExists(public_path($entry->featured_image));
        $this->assertFileExists(public_path($entry->document_image));
        $this->assertFileExists(public_path($entry->og_image));

        $oldFeaturedImage = $entry->featured_image;

        $this->put(route('admin.living-archive-entry.update', $entry->id), [
            'title' => 'Updated Admin Archive Page',
            'slug' => 'admin-archive-page',
            'section_label' => 'Updated Section',
            'parent_id' => null,
            'page_type' => 'archive_page',
            'teaser' => 'Updated teaser.',
            'content' => '<p>Updated editor content.</p>',
            'document_caption' => 'Updated source note.',
            'sort_order' => 2,
            'status' => 1,
            'meta_title' => 'Updated Meta',
            'meta_description' => 'Updated meta description.',
            'published_at' => now()->format('Y-m-d H:i'),
            'featured_image_alt' => 'Updated featured archive alt text.',
            'document_image_alt' => 'Updated document archive alt text.',
            'og_image_alt' => 'Updated OG archive alt text.',
            'remove_featured_image' => 1,
        ])->assertRedirect(route('admin.living-archive-entry.index'));

        $entry->refresh();
        $this->assertSame('Updated Admin Archive Page', $entry->title);
        $this->assertSame('Updated document archive alt text.', $entry->document_image_alt);
        $this->assertNull($entry->featured_image);
        $this->assertFileDoesNotExist(public_path($oldFeaturedImage));

        $this->put(route('admin.living-archive-entry.status', $entry->id))
            ->assertOk();

        $this->assertFalse($entry->fresh()->status);

        $documentImage = $entry->document_image;
        $ogImage = $entry->og_image;

        $this->delete(route('admin.living-archive-entry.destroy', $entry->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('living_archive_entries', ['id' => $entry->id]);
        $this->assertFileDoesNotExist(public_path($documentImage));
        $this->assertFileDoesNotExist(public_path($ogImage));
    }

    public function test_admin_duplicate_slug_validation_fails(): void
    {
        $this->actingAsAdmin();

        $this->archiveEntry(['title' => 'Existing Page', 'slug' => 'existing-page']);

        $this->from(route('admin.living-archive-entry.create'))
            ->post(route('admin.living-archive-entry.store'), [
                'title' => 'New Page',
                'slug' => 'existing-page',
                'page_type' => 'archive_page',
                'sort_order' => 1,
                'status' => 1,
            ])
            ->assertRedirect(route('admin.living-archive-entry.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_prevents_self_and_circular_parent_relationships(): void
    {
        $this->actingAsAdmin();

        $parent = $this->archiveEntry(['title' => 'Parent Page', 'slug' => 'parent-page']);
        $child = $this->archiveEntry(['title' => 'Child Page', 'slug' => 'child-page', 'parent_id' => $parent->id]);
        $grandchild = $this->archiveEntry(['title' => 'Grandchild Page', 'slug' => 'grandchild-page', 'parent_id' => $child->id]);

        $payload = [
            'title' => $parent->title,
            'slug' => $parent->slug,
            'page_type' => $parent->page_type,
            'sort_order' => $parent->sort_order,
            'status' => 1,
        ];

        $this->put(route('admin.living-archive-entry.update', $parent->id), array_merge($payload, [
            'parent_id' => $parent->id,
        ]))->assertSessionHasErrors('parent_id');

        $this->put(route('admin.living-archive-entry.update', $parent->id), array_merge($payload, [
            'parent_id' => $grandchild->id,
        ]))->assertSessionHasErrors('parent_id');
    }

    public function test_admin_cannot_delete_parent_with_children(): void
    {
        $this->actingAsAdmin();

        $parent = $this->archiveEntry(['title' => 'Parent With Children', 'slug' => 'parent-with-children']);
        $this->archiveEntry(['title' => 'Child Record', 'slug' => 'child-record', 'parent_id' => $parent->id]);

        $this->delete(route('admin.living-archive-entry.destroy', $parent->id))
            ->assertRedirect()
            ->assertSessionHas('alert-type', 'warning');

        $this->assertDatabaseHas('living_archive_entries', ['id' => $parent->id]);
    }

    public function test_admin_index_lists_pages_in_hierarchy_order(): void
    {
        $this->actingAsAdmin();

        $memoir = $this->archiveEntry(['title' => 'Memoir', 'slug' => 'memoir-admin', 'sort_order' => 10]);
        $this->archiveEntry(['title' => 'The Road North', 'slug' => 'the-road-north-admin', 'parent_id' => $memoir->id, 'sort_order' => 10]);

        $this->get(route('admin.living-archive-entry.index'))
            ->assertOk()
            ->assertSeeInOrder(['Memoir', 'The Road North']);
    }

    private function actingAsAdmin(): Admin
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'archive-admin@example.com'],
            [
                'name' => 'Archive Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'admin_type' => 1,
            ]
        );

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    private function archiveEntry(array $attributes): LivingArchiveEntry
    {
        return LivingArchiveEntry::updateOrCreate(
            ['slug' => $attributes['slug']],
            array_merge([
                'parent_id' => null,
                'title' => 'Archive Page',
                'section_label' => 'Archive',
                'teaser' => 'Test teaser.',
                'content' => '<p>Test content.</p>',
                'page_type' => 'archive_page',
                'sort_order' => 0,
                'status' => true,
                'published_at' => now(),
            ], $attributes)
        );
    }
}
