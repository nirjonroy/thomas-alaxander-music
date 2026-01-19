@extends('admin.master_layout')
@section('title')
<title>Living Archive Page</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Living Archive Page</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                <div class="breadcrumb-item">Living Archive Page</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Update ceremonial copy</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.living-archive.page.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Header Title</label>
                                <input type="text" class="form-control" name="living_archive_title" value="{{ old('living_archive_title', optional($setting)->living_archive_title) }}" placeholder="Welcome to the Living Archive">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Header Subtitle</label>
                                <input type="text" class="form-control" name="living_archive_subtitle" value="{{ old('living_archive_subtitle', optional($setting)->living_archive_subtitle) }}" placeholder="This is not a store. This is ceremony.">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Ceremonial Welcome</label>
                                <textarea name="living_archive_intro" class="form-control" rows="3">{{ old('living_archive_intro', optional($setting)->living_archive_intro) }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">QR Landing Text</label>
                                <textarea name="living_archive_qr_text" class="form-control" rows="2">{{ old('living_archive_qr_text', optional($setting)->living_archive_qr_text) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Header Logo Upload</label>
                                <input type="file" class="form-control" name="living_archive_logo_image_file" accept=".jpg,.jpeg,.png,.webp,.svg">
                                @if(optional($setting)->living_archive_logo_image)
                                    <small class="text-muted d-block mt-1">Current: {{ optional($setting)->living_archive_logo_image }}</small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Header Logo Path / URL</label>
                                <input type="text" class="form-control" name="living_archive_logo_image" value="{{ old('living_archive_logo_image', optional($setting)->living_archive_logo_image) }}" placeholder="uploads/living-archive/logo.png or https://...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hero Banner Upload</label>
                                <input type="file" class="form-control" name="living_archive_hero_image_file" accept=".jpg,.jpeg,.png,.webp,.svg">
                                @if(optional($setting)->living_archive_hero_image)
                                    <small class="text-muted d-block mt-1">Current: {{ optional($setting)->living_archive_hero_image }}</small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hero Banner Path / URL</label>
                                <input type="text" class="form-control" name="living_archive_hero_image" value="{{ old('living_archive_hero_image', optional($setting)->living_archive_hero_image) }}" placeholder="uploads/living-archive/hero.jpg or https://...">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3">SEO (Living Archive)</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SEO Title</label>
                                <input type="text" class="form-control" name="living_seo_title" value="{{ old('living_seo_title', optional($seo)->seo_title) }}" placeholder="Living Archive | Thomas Alexander">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Canonical URL</label>
                                <input type="text" class="form-control" name="living_seo_canonical" value="{{ old('living_seo_canonical', optional($seo)->canonical_url) }}" placeholder="https://example.com/living-archive">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">SEO Description</label>
                                <textarea name="living_seo_description" class="form-control" rows="2" placeholder="Enter page description">{{ old('living_seo_description', optional($seo)->seo_description) }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">SEO Keywords</label>
                                <input type="text" class="form-control" name="living_seo_keywords" value="{{ old('living_seo_keywords', optional($seo)->seo_keywords) }}" placeholder="living archive, ceremony, crest">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">SEO Author</label>
                                <input type="text" class="form-control" name="living_seo_author" value="{{ old('living_seo_author', optional($seo)->seo_author) }}" placeholder="Thomas Alexander">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">SEO Publisher</label>
                                <input type="text" class="form-control" name="living_seo_publisher" value="{{ old('living_seo_publisher', optional($seo)->seo_publisher) }}" placeholder="Thomas Alexander">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">OpenGraph / Twitter Image Path or URL</label>
                                <input type="text" class="form-control" name="living_seo_meta_image" value="{{ old('living_seo_meta_image', optional($seo)->meta_image) }}" placeholder="uploads/living-archive/og.jpg or https://...">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3">Ceremonial Crest</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Crest Title</label>
                                <input type="text" class="form-control" name="living_crest_title" value="{{ old('living_crest_title', optional($setting)->living_crest_title) }}" placeholder="Ceremonial Crest">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Crest Caption</label>
                                <input type="text" class="form-control" name="living_crest_secondary_caption" value="{{ old('living_crest_secondary_caption', optional($setting)->living_crest_secondary_caption) }}" placeholder="Five Feather Lineage">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Crest Image URL</label>
                                <input type="text" class="form-control" name="living_crest_primary_image" value="{{ old('living_crest_primary_image', optional($setting)->living_crest_primary_image) }}" placeholder="https://example.com/primary-crest.jpg">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Crest Upload</label>
                                <input type="file" class="form-control" name="living_crest_primary_image_file" accept=".jpg,.jpeg,.png,.webp,.svg">
                                @if(optional($setting)->living_crest_primary_image)
                                    <small class="text-muted d-block mt-1">Current: {{ optional($setting)->living_crest_primary_image }}</small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Crest Image URL</label>
                                <input type="text" class="form-control" name="living_crest_secondary_image" value="{{ old('living_crest_secondary_image', optional($setting)->living_crest_secondary_image) }}" placeholder="https://example.com/secondary-crest.jpg">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Crest Upload</label>
                                <input type="file" class="form-control" name="living_crest_secondary_image_file" accept=".jpg,.jpeg,.png,.webp,.svg">
                                @if(optional($setting)->living_crest_secondary_image)
                                    <small class="text-muted d-block mt-1">Current: {{ optional($setting)->living_crest_secondary_image }}</small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Crest Body (line 1)</label>
                                <textarea name="living_crest_body_one" class="form-control" rows="3" placeholder="Dreamcatcher-style crest with twelve animal segments, central tree, and five feathers.">{{ old('living_crest_body_one', optional($setting)->living_crest_body_one) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Crest Body (line 2)</label>
                                <textarea name="living_crest_body_two" class="form-control" rows="3" placeholder="Five feathers honour the Five Civilized Tribes, with one feather reserved for the unknown yet to be recognized.">{{ old('living_crest_body_two', optional($setting)->living_crest_body_two) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Crest Body (line 3)</label>
                                <textarea name="living_crest_body_three" class="form-control" rows="2" placeholder="Full breathline: Mali, Black Indigenous, Alexander Black Indigenous/Gaelic, and Williams.">{{ old('living_crest_body_three', optional($setting)->living_crest_body_three) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Crest Mission / Texas Yamassee Line</label>
                                <textarea name="living_crest_mission" class="form-control" rows="3" placeholder="Mission and Texas Yamassee line">{{ old('living_crest_mission', optional($setting)->living_crest_mission) }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Phone (Living Archive)</label>
                                <input type="text" class="form-control" name="living_contact_phone" value="{{ old('living_contact_phone', optional($setting)->living_contact_phone) }}" placeholder="+000 123 45678">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Email (Living Archive)</label>
                                <input type="text" class="form-control" name="living_contact_email" value="{{ old('living_contact_email', optional($setting)->living_contact_email) }}" placeholder="info@example.com">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3">Living Archive Team Handoff Summary</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Page Name</label>
                                <input type="text" class="form-control" name="living_handoff_page_name" value="{{ old('living_handoff_page_name', optional($setting)->living_handoff_page_name) }}" placeholder="The Yamassee Rising - Living Archive">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo URL (Living Crest)</label>
                                <input type="text" class="form-control" name="living_handoff_logo_url" value="{{ old('living_handoff_logo_url', optional($setting)->living_handoff_logo_url) }}" placeholder="https://example.com/living-crest.png">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Email</label>
                                <input type="text" class="form-control" name="living_handoff_email" value="{{ old('living_handoff_email', optional($setting)->living_handoff_email) }}" placeholder="info@thomasalexanderthevoice.com">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" name="living_handoff_phone" value="{{ old('living_handoff_phone', optional($setting)->living_handoff_phone) }}" placeholder="(to be added)">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="living_handoff_address" value="{{ old('living_handoff_address', optional($setting)->living_handoff_address) }}" placeholder="P.O. Box or publishing company">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Instagram URL</label>
                                <input type="text" class="form-control" name="living_handoff_social_instagram" value="{{ old('living_handoff_social_instagram', optional($setting)->living_handoff_social_instagram) }}" placeholder="https://instagram.com/...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Facebook URL</label>
                                <input type="text" class="form-control" name="living_handoff_social_facebook" value="{{ old('living_handoff_social_facebook', optional($setting)->living_handoff_social_facebook) }}" placeholder="https://facebook.com/...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">YouTube URL</label>
                                <input type="text" class="form-control" name="living_handoff_social_youtube" value="{{ old('living_handoff_social_youtube', optional($setting)->living_handoff_social_youtube) }}" placeholder="https://youtube.com/...">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Intro Paragraph</label>
                                <textarea name="living_handoff_intro" class="form-control" rows="3" placeholder="Intro paragraph">{{ old('living_handoff_intro', optional($setting)->living_handoff_intro) }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mission Statement</label>
                                <textarea name="living_handoff_mission" class="form-control" rows="3" placeholder="Mission statement">{{ old('living_handoff_mission', optional($setting)->living_handoff_mission) }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Supporter Acknowledgment</label>
                                <textarea name="living_handoff_supporter" class="form-control" rows="3" placeholder="Supporter acknowledgment">{{ old('living_handoff_supporter', optional($setting)->living_handoff_supporter) }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Coming Soon Banner</label>
                                <textarea name="living_handoff_coming_soon" class="form-control" rows="2" placeholder="Coming soon text">{{ old('living_handoff_coming_soon', optional($setting)->living_handoff_coming_soon) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tagline 1</label>
                                <input type="text" class="form-control" name="living_handoff_tagline1" value="{{ old('living_handoff_tagline1', optional($setting)->living_handoff_tagline1) }}" placeholder="Carrying the Breath-line, Restoring the Living Memory.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tagline 2</label>
                                <input type="text" class="form-control" name="living_handoff_tagline2" value="{{ old('living_handoff_tagline2', optional($setting)->living_handoff_tagline2) }}" placeholder="Where the Crest breathes, the lineage lives.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tagline 3</label>
                                <input type="text" class="form-control" name="living_handoff_tagline3" value="{{ old('living_handoff_tagline3', optional($setting)->living_handoff_tagline3) }}" placeholder="The Breath-line carried forward, the memory restored.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tagline 4</label>
                                <input type="text" class="form-control" name="living_handoff_tagline4" value="{{ old('living_handoff_tagline4', optional($setting)->living_handoff_tagline4) }}" placeholder="Every supporter a carrier of the Living Crest.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Merch: Apparel</label>
                                <input type="text" class="form-control" name="living_handoff_merch_apparel" value="{{ old('living_handoff_merch_apparel', optional($setting)->living_handoff_merch_apparel) }}" placeholder="Where the Crest breathes, the lineage lives.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Merch: Posters & Cards</label>
                                <input type="text" class="form-control" name="living_handoff_merch_posters" value="{{ old('living_handoff_merch_posters', optional($setting)->living_handoff_merch_posters) }}" placeholder="Carrying the Breath-line, Restoring the Living Memory.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Merch: Music Scores & Charts</label>
                                <input type="text" class="form-control" name="living_handoff_merch_music" value="{{ old('living_handoff_merch_music', optional($setting)->living_handoff_merch_music) }}" placeholder="The Breath-line carried forward, the memory restored.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Merch: Donor Items</label>
                                <input type="text" class="form-control" name="living_handoff_merch_donor" value="{{ old('living_handoff_merch_donor', optional($setting)->living_handoff_merch_donor) }}" placeholder="Every supporter a carrier of the Living Crest.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Merch: Digital Products</label>
                                <input type="text" class="form-control" name="living_handoff_merch_digital" value="{{ old('living_handoff_merch_digital', optional($setting)->living_handoff_merch_digital) }}" placeholder="Carrying the Breath-line, Restoring the Living Memory.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Visual Hierarchy Notes</label>
                                <textarea name="living_handoff_visual_hierarchy" class="form-control" rows="2" placeholder="Primary crest, secondary tagline, supporting glyphs/feathers, informational copy">{{ old('living_handoff_visual_hierarchy', optional($setting)->living_handoff_visual_hierarchy) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Background Pairing Guide</label>
                                <textarea name="living_handoff_background_guide" class="form-control" rows="2" placeholder="Posters: parchment beige... Apparel: charcoal black...">{{ old('living_handoff_background_guide', optional($setting)->living_handoff_background_guide) }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Palette: Primary</label>
                                <input type="text" class="form-control" name="living_handoff_palette_primary" value="{{ old('living_handoff_palette_primary', optional($setting)->living_handoff_palette_primary) }}" placeholder="Earth Ochre, Ceremonial White, Charcoal Black">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Palette: Secondary</label>
                                <input type="text" class="form-control" name="living_handoff_palette_secondary" value="{{ old('living_handoff_palette_secondary', optional($setting)->living_handoff_palette_secondary) }}" placeholder="Muted Gold, Forest Green, Crimson Red">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Palette: Accent</label>
                                <input type="text" class="form-control" name="living_handoff_palette_accent" value="{{ old('living_handoff_palette_accent', optional($setting)->living_handoff_palette_accent) }}" placeholder="Sky Silver, Deep Yamassee Blue">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Footer Line</label>
                                <input type="text" class="form-control" name="living_handoff_footer_line" value="{{ old('living_handoff_footer_line', optional($setting)->living_handoff_footer_line) }}" placeholder="The Yamassee Rising - A Living Archive of Ceremony and Song.">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Before Setup Instructions</label>
                                <textarea name="living_ritual_before" class="form-control" rows="4" placeholder="One instruction per line">{{ old('living_ritual_before', optional($setting)->living_ritual_before) }}</textarea>
                                <small class="text-muted">Each line becomes a bullet.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">During Setup Instructions</label>
                                <textarea name="living_ritual_during" class="form-control" rows="4" placeholder="One instruction per line">{{ old('living_ritual_during', optional($setting)->living_ritual_during) }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">After Setup Instructions</label>
                                <textarea name="living_ritual_after" class="form-control" rows="4" placeholder="One instruction per line">{{ old('living_ritual_after', optional($setting)->living_ritual_after) }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Phases Intro Text</label>
                                <textarea name="living_phases_intro" class="form-control" rows="2" placeholder="Move through each phase to reveal artefacts, apparel, and recordings inscribed with their own affirmation.">{{ old('living_phases_intro', optional($setting)->living_phases_intro) }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            @foreach(range(1,4) as $phase)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phase {{ $phase }} Title</label>
                                    @php
                                        $phaseTitleKey = "living_phase{$phase}_title";
                                        $phaseAffKey = "living_phase{$phase}_affirmation";
                                    @endphp
                                    <input type="text" class="form-control" name="{{ $phaseTitleKey }}" value="{{ old($phaseTitleKey, optional($setting)->{$phaseTitleKey}) }}" placeholder="Phase {{ $phase }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phase {{ $phase }} Affirmation</label>
                                    <textarea name="{{ $phaseAffKey }}" class="form-control" rows="3">{{ old($phaseAffKey, optional($setting)->{$phaseAffKey}) }}</textarea>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button class="btn btn-primary">{{ __('admin.Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
