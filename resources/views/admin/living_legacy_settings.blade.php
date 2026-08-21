@extends('admin.master_layout')

@section('title')
<title>Living Legacy Page</title>
@endsection

@section('admin-content')
@php
    $defaults = [
        'living_legacy_meta_title' => 'Thomas Alexander — Chief & Elder | Living Legacy',
        'living_legacy_meta_description' => 'Thomas Alexander carries a unified Black Indigenous lineage rooted in Creek, Cherokee, Yamassee, and Copper-coloured skinned homesteader ancestry.',
        'living_legacy_eyebrow' => 'Five Feathers Lineage Society',
        'living_legacy_title' => 'Thomas Alexander — Chief & Elder',
        'living_legacy_subtitle' => 'Living Archive of the Creek, Cherokee, Yamassee, and Copper-coloured Skinned Homesteader Heritage',
        'living_legacy_hero_image' => 'uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg',
        'living_legacy_intro_heading' => 'Living Legacy Introduction',
        'living_legacy_intro_body' => "Thomas Alexander carries a unified Black Indigenous lineage rooted in Creek, Cherokee, Yamassee, and Copper-coloured skinned homesteader ancestry. His family's footsteps echo across ancient tribal lands and the prairie soil of Alberta, where Black Indigenous homesteaders built communities that shaped the province's early history.\n\nAs Chief & Elder of the Five Feathers Lineage Society, Thomas preserves and presents this heritage through ceremony, narrative, and cultural stewardship. His lineage was whispered in his ear by his mother, right up to his great-grandmothers - a breathline of resilience carried forward into the present day.",
        'living_legacy_governance_heading' => 'Clan Mother Governance',
        'living_legacy_governance_body' => "In Indigenous culture, the Clan Mothers are the true chiefs - the original holders of authority, memory, and connection to the land. Their leadership is rooted in lineage, responsibility, and ancestral continuity. Historically, colonial powers refused to negotiate with women, imposing their own patriarchal systems onto Indigenous nations.\n\nTo protect their sovereignty and ensure their voices were still heard, the Clan Mothers appointed men to stand as chiefs on their behalf. These men were not replacements - they were representatives chosen by the Clan Mothers to carry out leadership duties in a world shaped by colonial restrictions.\n\nThis is why the recognition of an Ancestral Clan Mother carries profound weight. Her acknowledgment is not symbolic; it is authoritative. It reflects the original governance structure, the lineage-based truth, and the cultural legitimacy that predates colonial interference.",
        'living_legacy_portrait_image' => 'uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg',
        'living_legacy_portrait_image_alt' => 'Thomas Alexander portrait and performance imagery',
        'living_legacy_portrait_heading' => 'Leather Chair Lineage Narrative',
        'living_legacy_portrait_body' => "The Ancestral Clan Mother recognized the truth immediately.\n\nIn this portrait, Thomas Alexander sits grounded in a leather chair - a symbol of lineage, authority, and ancestral continuity. The image carries the weight of history: the quiet strength of Creek, Cherokee, Yamassee, and Copper-coloured skinned homesteader heritage. It reflects the presence of a man whose identity is rooted in generations of resilience and cultural memory.\n\nShe saw what the photo reveals: lineage-based connectivity, an authoritative historical presence, and the breathline carried forward through him. Her words affirm the ancestral grounding visible in the image - a visual declaration of heritage, responsibility, and the living archive Thomas embodies.\n\nThis is not simply a portrait - it is a lineage statement.",
        'living_legacy_identity_heading' => 'Five Feathers Identity',
        'living_legacy_feather_items' => "Creek\nCherokee\nYamassee\nCopper-coloured skinned homesteader heritage\nOne feather left open for future ancestral confirmation",
        'living_legacy_identity_note' => 'It is a living crest - a cultural emblem carried forward through ceremony, narrative, and artistic expression.',
        'living_legacy_heritage_heading' => 'Blue Alberta Blue Heritage Statement',
        'living_legacy_heritage_body' => 'Blue Alberta Blue is rooted in the Black Indigenous homesteader history of Alberta - a legacy carried forward by Thomas Alexander, Chief & Elder of the Five Feathers Lineage Society, and performed through his artistic identity as The Voice.',
        'living_legacy_closing_text' => 'Every note Thomas sings, every crest he wears, every chart that memory stirs - is a thread in his ancestral tapestry. This is his offering. This is who he is. This is the Living Archive of The Voice.',
    ];
    $fieldValue = fn ($key) => old($key, optional($setting)->{$key} ?? $defaults[$key] ?? '');
@endphp
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Living Legacy Page</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                <div class="breadcrumb-item">Living Legacy</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Living Legacy Content</h4>
                    <div class="card-header-action">
                        <a href="{{ route('front.living-legacy') }}" class="btn btn-info" target="_blank">Preview Page</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.living-legacy.page.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">SEO</h5>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Meta Title</label>
                                <input type="text" class="form-control" name="living_legacy_meta_title" value="{{ $fieldValue('living_legacy_meta_title') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Meta Description</label>
                                <input type="text" class="form-control" name="living_legacy_meta_description" value="{{ $fieldValue('living_legacy_meta_description') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Open Graph Image URL</label>
                                <input type="text" class="form-control" name="living_legacy_og_image" value="{{ $fieldValue('living_legacy_og_image') }}">
                                @if($fieldValue('living_legacy_og_image'))
                                    <small class="text-muted d-block mt-1">Current: {{ $fieldValue('living_legacy_og_image') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label>Upload Open Graph Image</label>
                                <input type="file" class="form-control" name="living_legacy_og_image_file" accept=".jpg,.jpeg,.png,.webp,.svg">
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Hero</h5>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Eyebrow</label>
                                <input type="text" class="form-control" name="living_legacy_eyebrow" value="{{ $fieldValue('living_legacy_eyebrow') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Title</label>
                                <input type="text" class="form-control" name="living_legacy_title" value="{{ $fieldValue('living_legacy_title') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Subtitle</label>
                                <input type="text" class="form-control" name="living_legacy_subtitle" value="{{ $fieldValue('living_legacy_subtitle') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Hero Image URL</label>
                                <input type="text" class="form-control" name="living_legacy_hero_image" value="{{ $fieldValue('living_legacy_hero_image') }}">
                                @if($fieldValue('living_legacy_hero_image'))
                                    <small class="text-muted d-block mt-1">Current: {{ $fieldValue('living_legacy_hero_image') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label>Upload Hero Image</label>
                                <input type="file" class="form-control" name="living_legacy_hero_image_file" accept=".jpg,.jpeg,.png,.webp,.svg">
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Page Sections</h5>
                        <div class="form-group">
                            <label>Introduction Heading</label>
                            <input type="text" class="form-control" name="living_legacy_intro_heading" value="{{ $fieldValue('living_legacy_intro_heading') }}">
                        </div>
                        <div class="form-group">
                            <label>Introduction Body</label>
                            <textarea class="form-control" name="living_legacy_intro_body" rows="5">{{ $fieldValue('living_legacy_intro_body') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Clan Mother Governance Heading</label>
                            <input type="text" class="form-control" name="living_legacy_governance_heading" value="{{ $fieldValue('living_legacy_governance_heading') }}">
                        </div>
                        <div class="form-group">
                            <label>Clan Mother Governance Body</label>
                            <textarea class="form-control" name="living_legacy_governance_body" rows="6">{{ $fieldValue('living_legacy_governance_body') }}</textarea>
                        </div>

                        <h5 class="mb-3 mt-4">Portrait Section</h5>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Portrait Image URL</label>
                                <input type="text" class="form-control" name="living_legacy_portrait_image" value="{{ $fieldValue('living_legacy_portrait_image') }}">
                                @if($fieldValue('living_legacy_portrait_image'))
                                    <small class="text-muted d-block mt-1">Current: {{ $fieldValue('living_legacy_portrait_image') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label>Upload Portrait Image</label>
                                <input type="file" class="form-control" name="living_legacy_portrait_image_file" accept=".jpg,.jpeg,.png,.webp,.svg">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Portrait Image Alt Text</label>
                                <input type="text" class="form-control" name="living_legacy_portrait_image_alt" value="{{ $fieldValue('living_legacy_portrait_image_alt') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Portrait Heading</label>
                                <input type="text" class="form-control" name="living_legacy_portrait_heading" value="{{ $fieldValue('living_legacy_portrait_heading') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Portrait Body</label>
                            <textarea class="form-control" name="living_legacy_portrait_body" rows="7">{{ $fieldValue('living_legacy_portrait_body') }}</textarea>
                        </div>

                        <h5 class="mb-3 mt-4">Identity & Closing</h5>
                        <div class="form-group">
                            <label>Identity Heading</label>
                            <input type="text" class="form-control" name="living_legacy_identity_heading" value="{{ $fieldValue('living_legacy_identity_heading') }}">
                        </div>
                        <div class="form-group">
                            <label>Feather Items</label>
                            <textarea class="form-control" name="living_legacy_feather_items" rows="5">{{ $fieldValue('living_legacy_feather_items') }}</textarea>
                            <small class="text-muted">One item per line.</small>
                        </div>
                        <div class="form-group">
                            <label>Identity Note</label>
                            <textarea class="form-control" name="living_legacy_identity_note" rows="2">{{ $fieldValue('living_legacy_identity_note') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Heritage Statement Heading</label>
                            <input type="text" class="form-control" name="living_legacy_heritage_heading" value="{{ $fieldValue('living_legacy_heritage_heading') }}">
                        </div>
                        <div class="form-group">
                            <label>Heritage Statement Body</label>
                            <textarea class="form-control" name="living_legacy_heritage_body" rows="3">{{ $fieldValue('living_legacy_heritage_body') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Closing Text</label>
                            <textarea class="form-control" name="living_legacy_closing_text" rows="3">{{ $fieldValue('living_legacy_closing_text') }}</textarea>
                        </div>

                        <button class="btn btn-primary" type="submit">{{ __('admin.Update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
