<?php

namespace Database\Seeders;

use App\Models\LivingArchiveEntry;
use Illuminate\Database\Seeder;

class LivingArchiveEntrySeeder extends Seeder
{
    private const PLACEHOLDER_CONTENT = 'Full archive content will be added from the client-provided material.';

    public function run(): void
    {
        $now = now();

        $memoir = $this->createEntry([
            'title' => 'Memoir',
            'slug' => 'memoir',
            'section_label' => 'Living Archive',
            'page_type' => 'archive_section',
            'sort_order' => 10,
            'published_at' => $now,
        ]);

        $ceremonialLineage = $this->createEntry([
            'title' => 'Ceremonial Lineage',
            'slug' => 'ceremonial-lineage',
            'section_label' => 'Living Archive',
            'page_type' => 'archive_section',
            'sort_order' => 20,
            'published_at' => $now,
        ]);

        $heritage = $this->createEntry([
            'title' => 'Heritage',
            'slug' => 'heritage',
            'section_label' => 'Living Archive',
            'page_type' => 'archive_section',
            'sort_order' => 30,
            'published_at' => $now,
        ]);

        $this->createEntry([
            'parent_id' => $memoir->id,
            'title' => 'The Road North',
            'slug' => 'the-road-north',
            'section_label' => 'Memoir',
            'teaser' => 'A personal account of the Prairie Migration and the families — including the Alexanders — whose courage shaped Thomas’s lineage.',
            'page_type' => 'memoir',
            'sort_order' => 10,
            'published_at' => $now,
        ]);

        $this->createEntry([
            'parent_id' => $ceremonialLineage->id,
            'title' => 'The Crossing',
            'slug' => 'the-crossing',
            'section_label' => 'Ceremonial Lineage',
            'teaser' => 'A ceremonial retelling of the northward journey, honouring the ancestors who carried hope, identity, and survival across borders.',
            'page_type' => 'ceremonial_lineage',
            'sort_order' => 10,
            'published_at' => $now,
        ]);

        $prairieMigration = $this->createEntry([
            'parent_id' => $heritage->id,
            'title' => 'Prairie Migration',
            'slug' => 'prairie-migration',
            'section_label' => 'Heritage',
            'page_type' => 'heritage_section',
            'sort_order' => 10,
            'published_at' => $now,
        ]);

        $this->createEntry([
            'parent_id' => $prairieMigration->id,
            'title' => 'The Alexander Thread',
            'slug' => 'the-alexander-thread',
            'section_label' => 'Prairie Migration',
            'teaser' => 'A historical overview of the Prairie homesteader movement and the Alexander family’s place within it.',
            'page_type' => 'heritage',
            'sort_order' => 10,
            'published_at' => $now,
        ]);

        $this->createEntry([
            'parent_id' => $prairieMigration->id,
            'title' => 'Breton Registry',
            'slug' => 'breton-registry',
            'section_label' => 'Prairie Migration',
            'teaser' => 'Original 1918 tax and land assessment documents showing where the Alexander family lived and registered in Keystone/Breton.',
            'page_type' => 'heritage_document',
            'sort_order' => 20,
            'published_at' => $now,
        ]);

        $this->createEntry([
            'parent_id' => $heritage->id,
            'title' => 'Black Loyalists',
            'slug' => 'black-loyalists',
            'section_label' => 'Heritage',
            'teaser' => 'The earliest large-scale Black migration into Canada, recorded in the Book of Negroes.',
            'page_type' => 'heritage',
            'sort_order' => 20,
            'published_at' => $now,
        ]);

        $this->createEntry([
            'parent_id' => $heritage->id,
            'title' => 'Underground Railroad',
            'slug' => 'underground-railroad',
            'section_label' => 'Heritage',
            'teaser' => 'The courageous journey of families who escaped slavery and built new lives in Ontario.',
            'page_type' => 'heritage',
            'sort_order' => 30,
            'published_at' => $now,
        ]);

        $railwayPorters = $this->createEntry([
            'parent_id' => $heritage->id,
            'title' => 'Railway Porters',
            'slug' => 'railway-porters',
            'section_label' => 'Heritage',
            'teaser' => 'The story of Black railway workers who shaped Canada’s labour movement and urban Black communities.',
            'page_type' => 'heritage',
            'sort_order' => 40,
            'published_at' => $now,
        ]);

        $this->createEntry([
            'parent_id' => $railwayPorters->id,
            'title' => 'Roy Williams',
            'slug' => 'roy-williams',
            'section_label' => 'Railway Porters',
            'teaser' => 'The life and leadership of Roy Williams, a cornerstone of Black labour history in Canada.',
            'page_type' => 'heritage_profile',
            'sort_order' => 10,
            'published_at' => $now,
        ]);
    }

    private function createEntry(array $data): LivingArchiveEntry
    {
        $teaser = $data['teaser'] ?? self::PLACEHOLDER_CONTENT;

        return LivingArchiveEntry::firstOrCreate(
            ['slug' => $data['slug']],
            array_merge([
                'parent_id' => null,
                'teaser' => $teaser,
                'content' => self::PLACEHOLDER_CONTENT,
                'status' => true,
                'meta_title' => $data['title'],
                'meta_description' => $teaser,
            ], $data)
        );
    }
}
