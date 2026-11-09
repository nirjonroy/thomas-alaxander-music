<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanionPageSetting extends Model
{
    use HasFactory;

    protected $table = 'companion_page_settings';

    protected $fillable = [
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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
