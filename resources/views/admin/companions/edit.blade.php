@extends('admin.master_layout')
@section('title')
<title>Edit Companion: {{ $companion->name }}</title>
@endsection
@section('admin-content')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Edit Companion: {{ $companion->name }} ({{ sprintf('%02d', $companion->sequence) }})</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.companions-journey.index') }}">Companions Along the Journey</a></div>
              <div class="breadcrumb-item">Edit: {{ $companion->name }}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('admin.companions-journey.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Back to Companions List</a>
                <span class="badge badge-secondary p-2">Canonical Sequence #{{ sprintf('%02d', $companion->sequence) }} &mdash; {{ $companion->name }}</span>
            </div>

            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-header bg-light">
                        <h4>Companion Content &amp; Artwork</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.companions-journey.update', $companion->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="alert alert-info py-2 mb-4">
                                <i class="fas fa-info-circle"></i>
                                Sequence, Name, and Slug are canonical structural identifiers and cannot be altered. Teaching and artwork fields are populated as provided by Thomas Alexander.
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Sequence (Read-only)</label>
                                    <input type="text" class="form-control" value="{{ sprintf('%02d', $companion->sequence) }}" disabled readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Companion Name (Read-only)</label>
                                    <input type="text" class="form-control" value="{{ $companion->name }}" disabled readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Canonical Slug (Read-only)</label>
                                    <input type="text" class="form-control" value="{{ $companion->slug }}" disabled readonly>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="is_active" class="form-control">
                                        <option value="0" {{ old('is_active', (int) $companion->is_active) === 0 ? 'selected' : '' }}>{{__('admin.Inactive')}}</option>
                                        <option value="1" {{ old('is_active', (int) $companion->is_active) === 1 ? 'selected' : '' }}>{{__('admin.Active')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>Themes</label>
                                    <input type="text" class="form-control" name="themes" value="{{ old('themes', $companion->themes) }}" placeholder="e.g. Pathfinder, Loyalty, Community">
                                    <small class="form-text text-muted">Primary themes and spiritual qualities associated with this Companion.</small>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Companion Artwork Image</label>
                                    @if($companion->artwork_image)
                                        <div class="mb-2 p-2 border rounded bg-light">
                                            <img src="{{ asset($companion->artwork_image) }}" alt="{{ $companion->artwork_image_alt ?? $companion->name }}" style="max-height: 140px; max-width: 100%; object-fit: contain;">
                                            <div class="custom-control custom-checkbox mt-2">
                                                <input type="checkbox" class="custom-control-input" id="remove_artwork_image" name="remove_artwork_image" value="1">
                                                <label class="custom-control-label text-danger" for="remove_artwork_image">Remove current artwork</label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="artwork_image" accept=".jpg,.jpeg,.png,.webp">
                                    <small class="form-text text-muted">Accepted formats: JPG, PNG, WebP (max 10MB). SVG is not accepted.</small>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Artwork Image Alt Text</label>
                                    <input type="text" class="form-control" name="artwork_image_alt" value="{{ old('artwork_image_alt', $companion->artwork_image_alt) }}" placeholder="Descriptive alt text for accessibility">
                                    <small class="form-text text-muted">Detailed description of the artwork image for screen readers and SEO.</small>
                                </div>

                                <div class="form-group col-12">
                                    <label>Expanded Teaching</label>
                                    <textarea name="expanded_teaching" class="summernote">{{ old('expanded_teaching', $companion->expanded_teaching) }}</textarea>
                                    <small class="form-text text-muted">The complete ceremonial teaching text for this Companion.</small>
                                </div>

                                <div class="form-group col-12">
                                    <label>What We Are Asked to Remember</label>
                                    <textarea name="remember_teaching" class="summernote">{{ old('remember_teaching', $companion->remember_teaching) }}</textarea>
                                    <small class="form-text text-muted">Specific remembrance and contemplative prompt for this Companion.</small>
                                </div>

                                <div class="form-group col-12">
                                    <label>Branding Information</label>
                                    <textarea name="branding_info" class="summernote">{{ old('branding_info', $companion->branding_info) }}</textarea>
                                    <small class="form-text text-muted">Exact Companion branding block supplied by Thomas Alexander (do not invent logo or crest).</small>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Save Companion</button>
                                <a href="{{ route('admin.companions-journey.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </section>
      </div>
@endsection
