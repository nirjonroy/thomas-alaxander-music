@extends('admin.master_layout')
@section('title')
<title>Companions Along the Journey &mdash; Page Settings</title>
@endsection
@section('admin-content')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Companions Along the Journey &mdash; Page Settings</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.companions-journey.index') }}">Companions Along the Journey</a></div>
              <div class="breadcrumb-item">Settings</div>
            </div>
          </div>

          <div class="section-body">
            @include('admin.companions.partials.readiness', ['readiness' => $readiness])

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('admin.companions-journey.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> View 11 Canonical Companions</a>
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.companions-journey.preview') }}" target="_blank" class="btn btn-warning mr-3"><i class="fas fa-eye"></i> Preview Page</a>
                    <span class="badge {{ $setting->is_active ? 'badge-success' : 'badge-warning' }} p-2">
                        Page Status: {{ $setting->is_active ? 'Active' : 'Inactive (Draft / Unpublished)' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('admin.companions-journey.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- 1. Ceremonial Entrance -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>1. Ceremonial Entrance</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Page Active Status <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-control">
                                    <option value="0" {{ old('is_active', (int) $setting->is_active) === 0 ? 'selected' : '' }}>Inactive / Unpublished</option>
                                    <option value="1" {{ old('is_active', (int) $setting->is_active) === 1 ? 'selected' : '' }}>Active / Published</option>
                                </select>
                                <small class="form-text text-muted">Page remains unpublished until ceremonial launch.</small>
                            </div>

                            <div class="form-group col-md-8">
                                <label>Section Label</label>
                                <input type="text" class="form-control" name="entrance_section_label" value="{{ old('entrance_section_label', $setting->entrance_section_label) }}" placeholder="e.g. Living Archive &mdash; Ceremonial Teachings">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Entrance Title</label>
                                <input type="text" class="form-control" name="entrance_title" value="{{ old('entrance_title', $setting->entrance_title) }}" placeholder="e.g. Companions Along the Journey">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Entrance Subtitle</label>
                                <input type="text" class="form-control" name="entrance_subtitle" value="{{ old('entrance_subtitle', $setting->entrance_subtitle) }}" placeholder="e.g. The Sacred Circle and the Eleven Guides">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Full Ceremonial Entrance Crest</label>
                                @if($setting->entrance_crest_image)
                                    <div class="mb-2 p-2 border rounded bg-light">
                                        <img src="{{ asset($setting->entrance_crest_image) }}" alt="{{ $setting->entrance_crest_image_alt }}" style="max-height: 100px;">
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" id="remove_entrance_crest_image" name="remove_entrance_crest_image" value="1">
                                            <label class="custom-control-label text-danger" for="remove_entrance_crest_image">Remove entrance crest</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="entrance_crest_image" accept=".jpg,.jpeg,.png,.webp">
                                <small class="form-text text-muted">Full Ceremonial Living Crest for page entrance (JPG, PNG, WebP only).</small>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Entrance Crest Alt Text</label>
                                <input type="text" class="form-control" name="entrance_crest_image_alt" value="{{ old('entrance_crest_image_alt', $setting->entrance_crest_image_alt) }}" placeholder="Alt text for the ceremonial crest">
                            </div>

                            <div class="form-group col-12">
                                <label>Opening Teaching / Narrative</label>
                                <textarea name="opening_teaching" class="summernote">{{ old('opening_teaching', $setting->opening_teaching) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Regeneration -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>2. Regeneration</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Regeneration Title</label>
                                <input type="text" class="form-control" name="regeneration_title" value="{{ old('regeneration_title', $setting->regeneration_title) }}" placeholder="Title for Regeneration section">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Regeneration Subtitle</label>
                                <input type="text" class="form-control" name="regeneration_subtitle" value="{{ old('regeneration_subtitle', $setting->regeneration_subtitle) }}">
                            </div>

                            <div class="form-group col-12">
                                <label>Regeneration Body</label>
                                <textarea name="regeneration_body" class="summernote">{{ old('regeneration_body', $setting->regeneration_body) }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Regeneration Image</label>
                                @if($setting->regeneration_image)
                                    <div class="mb-2 p-2 border rounded bg-light">
                                        <img src="{{ asset($setting->regeneration_image) }}" alt="{{ $setting->regeneration_image_alt }}" style="max-height: 100px;">
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" id="remove_regeneration_image" name="remove_regeneration_image" value="1">
                                            <label class="custom-control-label text-danger" for="remove_regeneration_image">Remove regeneration image</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="regeneration_image" accept=".jpg,.jpeg,.png,.webp">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Regeneration Image Alt Text</label>
                                <input type="text" class="form-control" name="regeneration_image_alt" value="{{ old('regeneration_image_alt', $setting->regeneration_image_alt) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. The Witnesses -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>3. The Witnesses</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Witnesses Section Title</label>
                                <input type="text" class="form-control" name="witnesses_title" value="{{ old('witnesses_title', $setting->witnesses_title) }}" placeholder="e.g. The Witnesses">
                            </div>

                            <div class="form-group col-12">
                                <label>Witnesses Intro</label>
                                <textarea name="witnesses_intro" class="summernote">{{ old('witnesses_intro', $setting->witnesses_intro) }}</textarea>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-feather-alt"></i> The Individual Witnesses</h6>

                        <!-- Wind -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Witness: Wind &mdash; Title</label>
                                    <input type="text" class="form-control" name="witness_wind_title" value="{{ old('witness_wind_title', $setting->witness_wind_title) }}" placeholder="Wind">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Witness: Wind &mdash; Body</label>
                                    <textarea name="witness_wind_body" class="summernote">{{ old('witness_wind_body', $setting->witness_wind_body) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Earth -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Witness: Earth &mdash; Title</label>
                                    <input type="text" class="form-control" name="witness_earth_title" value="{{ old('witness_earth_title', $setting->witness_earth_title) }}" placeholder="Earth">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Witness: Earth &mdash; Body</label>
                                    <textarea name="witness_earth_body" class="summernote">{{ old('witness_earth_body', $setting->witness_earth_body) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Water -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Witness: Water &mdash; Title</label>
                                    <input type="text" class="form-control" name="witness_water_title" value="{{ old('witness_water_title', $setting->witness_water_title) }}" placeholder="Water">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Witness: Water &mdash; Body</label>
                                    <textarea name="witness_water_body" class="summernote">{{ old('witness_water_body', $setting->witness_water_body) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sunlight -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Witness: Sunlight &mdash; Title</label>
                                    <input type="text" class="form-control" name="witness_sunlight_title" value="{{ old('witness_sunlight_title', $setting->witness_sunlight_title) }}" placeholder="Sunlight">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Witness: Sunlight &mdash; Body</label>
                                    <textarea name="witness_sunlight_body" class="summernote">{{ old('witness_sunlight_body', $setting->witness_sunlight_body) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Night -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Witness: Night &mdash; Title</label>
                                    <input type="text" class="form-control" name="witness_night_title" value="{{ old('witness_night_title', $setting->witness_night_title) }}" placeholder="Night">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Witness: Night &mdash; Body</label>
                                    <textarea name="witness_night_body" class="summernote">{{ old('witness_night_body', $setting->witness_night_body) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Rest -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Witness: Rest &mdash; Title</label>
                                    <input type="text" class="form-control" name="witness_rest_title" value="{{ old('witness_rest_title', $setting->witness_rest_title) }}" placeholder="Rest">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Witness: Rest &mdash; Body</label>
                                    <textarea name="witness_rest_body" class="summernote">{{ old('witness_rest_body', $setting->witness_rest_body) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Human Responsibility -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>4. Human Responsibility</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Human Responsibility Title</label>
                                <input type="text" class="form-control" name="human_responsibility_title" value="{{ old('human_responsibility_title', $setting->human_responsibility_title) }}">
                            </div>

                            <div class="form-group col-12">
                                <label>Human Responsibility Body</label>
                                <textarea name="human_responsibility_body" class="summernote">{{ old('human_responsibility_body', $setting->human_responsibility_body) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Final Rest / Continuance -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>5. Final Rest / Continuance</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Continuance Title</label>
                                <input type="text" class="form-control" name="continuance_title" value="{{ old('continuance_title', $setting->continuance_title) }}">
                            </div>

                            <div class="form-group col-12">
                                <label>Continuance Body</label>
                                <textarea name="continuance_body" class="summernote">{{ old('continuance_body', $setting->continuance_body) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. New Beginning -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>6. New Beginning</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>New Beginning Title</label>
                                <input type="text" class="form-control" name="new_beginning_title" value="{{ old('new_beginning_title', $setting->new_beginning_title) }}">
                            </div>

                            <div class="form-group col-12">
                                <label>New Beginning Body</label>
                                <textarea name="new_beginning_body" class="summernote">{{ old('new_beginning_body', $setting->new_beginning_body) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. Closing -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>7. Closing</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Full Ceremonial Closing Crest</label>
                                @if($setting->closing_crest_image)
                                    <div class="mb-2 p-2 border rounded bg-light">
                                        <img src="{{ asset($setting->closing_crest_image) }}" alt="{{ $setting->closing_crest_image_alt }}" style="max-height: 100px;">
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" id="remove_closing_crest_image" name="remove_closing_crest_image" value="1">
                                            <label class="custom-control-label text-danger" for="remove_closing_crest_image">Remove closing crest</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="closing_crest_image" accept=".jpg,.jpeg,.png,.webp">
                                <small class="form-text text-muted">Full Ceremonial Living Crest for page closing (JPG, PNG, WebP only).</small>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Closing Crest Alt Text</label>
                                <input type="text" class="form-control" name="closing_crest_image_alt" value="{{ old('closing_crest_image_alt', $setting->closing_crest_image_alt) }}">
                            </div>

                            <div class="form-group col-12">
                                <label>Closing Text</label>
                                <textarea name="closing_text" class="summernote">{{ old('closing_text', $setting->closing_text) }}</textarea>
                            </div>

                            <div class="form-group col-12">
                                <label>Final Refrain</label>
                                <textarea name="final_refrain" class="summernote">{{ old('final_refrain', $setting->final_refrain) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 8. SEO -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4>8. Search Engine Optimization (SEO)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}" placeholder="Companions Along the Journey | Living Archive">
                            </div>

                            <div class="form-group col-12">
                                <label>Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $setting->meta_description) }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label>OG Image</label>
                                @if($setting->og_image)
                                    <div class="mb-2 p-2 border rounded bg-light">
                                        <img src="{{ asset($setting->og_image) }}" alt="{{ $setting->og_image_alt }}" style="max-height: 100px;">
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" id="remove_og_image" name="remove_og_image" value="1">
                                            <label class="custom-control-label text-danger" for="remove_og_image">Remove OG image</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="og_image" accept=".jpg,.jpeg,.png,.webp">
                            </div>

                            <div class="form-group col-md-6">
                                <label>OG Image Alt Text</label>
                                <input type="text" class="form-control" name="og_image_alt" value="{{ old('og_image_alt', $setting->og_image_alt) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-lg px-4"><i class="fas fa-save"></i> Save Page Settings</button>
                    </div>
                </div>
            </form>
          </div>
        </section>
      </div>
@endsection
