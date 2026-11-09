<?php

namespace Tests\Feature;

use App\Models\CompanionPageSetting;
use App\Models\JourneyCompanion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CompanionFoundationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_tables_exist_with_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('companion_page_settings'));
        $this->assertTrue(Schema::hasTable('journey_companions'));

        $pageCols = [
            'id',
            'is_active',
            'entrance_section_label',
            'entrance_title',
            'entrance_subtitle',
            'entrance_crest_image',
            'entrance_crest_image_alt',
            'opening_teaching',
            'regeneration_title',
            'regeneration_subtitle',
            'regeneration_body',
            'regeneration_image',
            'regeneration_image_alt',
            'witnesses_title',
            'witnesses_intro',
            'witness_wind_title',
            'witness_wind_body',
            'witness_earth_title',
            'witness_earth_body',
            'witness_water_title',
            'witness_water_body',
            'witness_sunlight_title',
            'witness_sunlight_body',
            'witness_night_title',
            'witness_night_body',
            'witness_rest_title',
            'witness_rest_body',
            'human_responsibility_title',
            'human_responsibility_body',
            'continuance_title',
            'continuance_body',
            'new_beginning_title',
            'new_beginning_body',
            'closing_crest_image',
            'closing_crest_image_alt',
            'closing_text',
            'final_refrain',
            'meta_title',
            'meta_description',
            'og_image',
            'og_image_alt',
            'created_at',
            'updated_at',
        ];

        foreach ($pageCols as $col) {
            $this->assertTrue(
                Schema::hasColumn('companion_page_settings', $col),
                "Missing column [{$col}] in companion_page_settings"
            );
        }

        $companionCols = [
            'id',
            'sequence',
            'name',
            'slug',
            'themes',
            'artwork_image',
            'artwork_image_alt',
            'expanded_teaching',
            'remember_teaching',
            'branding_info',
            'is_active',
            'created_at',
            'updated_at',
        ];

        foreach ($companionCols as $col) {
            $this->assertTrue(
                Schema::hasColumn('journey_companions', $col),
                "Missing column [{$col}] in journey_companions"
            );
        }
    }

    public function test_companion_page_setting_instantiation_and_casts(): void
    {
        $setting = CompanionPageSetting::create([
            'is_active' => false,
            'entrance_section_label' => 'Living Archive',
            'entrance_title' => 'Companions Along the Journey',
            'entrance_subtitle' => 'The Sacred Circle',
            'entrance_crest_image' => 'uploads/companions/entrance-crest.png',
            'entrance_crest_image_alt' => 'Ceremonial Entrance Crest',
            'opening_teaching' => 'Test opening narrative.',
            'regeneration_title' => 'Regeneration',
            'witnesses_title' => 'The Witnesses',
            'closing_crest_image' => 'uploads/companions/closing-crest.png',
            'closing_crest_image_alt' => 'Ceremonial Closing Crest',
            'final_refrain' => 'Test final refrain.',
            'meta_title' => 'Companions Along the Journey | Living Archive',
        ]);

        $this->assertNotNull($setting->id);
        $this->assertFalse($setting->is_active);
        $this->assertIsBool($setting->is_active);

        $setting->update(['is_active' => true]);
        $this->assertTrue($setting->fresh()->is_active);
    }

    public function test_journey_companion_instantiation_and_casts(): void
    {
        $companion = JourneyCompanion::updateOrCreate(
            ['slug' => 'wolf'],
            [
                'sequence' => 1,
                'name' => 'Wolf',
                'themes' => 'Pathfinder, Loyalty, Community',
                'artwork_image' => 'uploads/companions/wolf.png',
                'artwork_image_alt' => 'Wolf companion artwork',
                'expanded_teaching' => 'Test teaching content.',
                'remember_teaching' => 'Test remembrance text.',
                'branding_info' => 'Five Feathers Publishing',
                'is_active' => true,
            ]
        );

        $this->assertNotNull($companion->id);
        $this->assertSame(1, $companion->sequence);
        $this->assertIsInt($companion->sequence);
        $this->assertTrue($companion->is_active);
        $this->assertIsBool($companion->is_active);
    }

    public function test_canonical_companion_order_is_immutable_and_exact(): void
    {
        $canonical = JourneyCompanion::canonicalOrder();

        $this->assertCount(11, $canonical);

        $expected = [
            1 => 'Wolf',
            2 => 'Hawk',
            3 => 'Raven',
            4 => 'Owl',
            5 => 'Eagle',
            6 => 'Panther',
            7 => 'Bear',
            8 => 'Lynx',
            9 => 'Deer',
            10 => 'Fox',
            11 => 'Otter',
        ];

        $this->assertSame($expected, $canonical);
        $this->assertSame('Wolf', JourneyCompanion::canonicalNameForSequence(1));
        $this->assertSame('Otter', JourneyCompanion::canonicalNameForSequence(11));
        $this->assertNull(JourneyCompanion::canonicalNameForSequence(12));
    }

    public function test_journey_companion_scopes(): void
    {
        JourneyCompanion::where('slug', 'wolf')->update(['is_active' => true]);
        JourneyCompanion::where('slug', 'hawk')->update(['is_active' => false]);

        $active = JourneyCompanion::active()->get();
        $this->assertTrue($active->contains('slug', 'wolf'));
        $this->assertFalse($active->contains('slug', 'hawk'));

        $ordered = JourneyCompanion::ordered()->pluck('sequence')->all();
        $this->assertSame([1, 2], array_values(array_intersect([1, 2], $ordered)));
    }

    public function test_existing_living_archive_tables_remain_untouched(): void
    {
        $this->assertTrue(Schema::hasTable('settings'));
        $this->assertTrue(Schema::hasTable('living_archive_entries'));
        $this->assertTrue(Schema::hasTable('custom_pages'));
        $this->assertTrue(Schema::hasTable('seo_settings'));

        // Verify key existing columns in settings still exist
        $this->assertTrue(Schema::hasColumn('settings', 'living_archive_title'));
        $this->assertTrue(Schema::hasColumn('settings', 'living_legacy_title'));
        $this->assertTrue(Schema::hasColumn('settings', 'dual_identity_enabled'));

        // Verify key existing columns in living_archive_entries still exist
        $this->assertTrue(Schema::hasColumn('living_archive_entries', 'parent_id'));
        $this->assertTrue(Schema::hasColumn('living_archive_entries', 'slug'));
        $this->assertTrue(Schema::hasColumn('living_archive_entries', 'content'));
    }
}
