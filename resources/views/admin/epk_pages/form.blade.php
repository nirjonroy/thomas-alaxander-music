@php
    $pageSlug = \Illuminate\Support\Str::slug((string) old('slug', $epkPage->slug));
    $isFullArtist = $pageSlug === 'full-artist';
    $isCrooners = $pageSlug === 'crooners';
    $sections = collect($epkPage->sections ?: []);
    $sectionOfType = fn ($type) => $sections->first(fn ($section) => data_get($section, 'type') === $type) ?: [];
    $performanceSection = $sectionOfType('performance_lanes');
    $engagementSection = $sectionOfType('engagements');
    $repertoireSection = $sections->first(fn ($section) => data_get($section, 'type') === 'tags') ?: [];
    $testimonialSection = $sectionOfType('testimonials');
    $medleySection = $sectionOfType('medley');
    $videoSection = $sectionOfType('video');
    $bookingSection = $sectionOfType('booking');
    $genericSections = old('section_titles')
        ? collect(old('section_titles'))->map(fn ($title, $index) => [
            'title' => $title,
            'body' => old('section_bodies.' . $index),
        ])->all()
        : ($epkPage->sections ?: []);
    $sectionCount = max(count($genericSections) + 1, 4);

    $pairedRows = function ($titlesInput, $bodiesInput, $existingItems, $minimum = 3) {
        if (old($titlesInput) !== null || old($bodiesInput) !== null) {
            $titles = old($titlesInput, []);
            $bodies = old($bodiesInput, []);
            $rows = [];
            foreach ($titles as $index => $title) {
                $rows[] = ['title' => $title, 'body' => $bodies[$index] ?? ''];
            }
            return array_pad($rows, max(count($rows), $minimum), ['title' => '', 'body' => '']);
        }

        $rows = collect($existingItems ?: [])->map(fn ($item) => [
            'title' => data_get($item, 'title', ''),
            'body' => data_get($item, 'body', ''),
        ])->all();

        return array_pad($rows, max(count($rows) + 1, $minimum), ['title' => '', 'body' => '']);
    };

    $listRows = function ($input, $existingItems, $minimum = 4) {
        $rows = old($input) !== null ? old($input, []) : ($existingItems ?: []);
        $rows = array_values($rows);
        return array_pad($rows, max(count($rows) + 1, $minimum), '');
    };

    $testimonialRows = function () use ($testimonialSection) {
        if (old('testimonial_sources') !== null || old('testimonial_quotes') !== null) {
            $sources = old('testimonial_sources', []);
            $credentials = old('testimonial_credentials', []);
            $quotes = old('testimonial_quotes', []);
            $rows = [];
            foreach ($sources as $index => $source) {
                $rows[] = [
                    'source' => $source,
                    'credential' => $credentials[$index] ?? '',
                    'quote' => $quotes[$index] ?? '',
                ];
            }
            return array_pad($rows, max(count($rows), 2), ['source' => '', 'credential' => '', 'quote' => '']);
        }

        $rows = collect(data_get($testimonialSection, 'items', []))->map(fn ($item) => [
            'source' => data_get($item, 'source', ''),
            'credential' => data_get($item, 'credential', ''),
            'quote' => data_get($item, 'quote', ''),
        ])->all();

        return array_pad($rows, max(count($rows), 2), ['source' => '', 'credential' => '', 'quote' => '']);
    };

    $laneRows = $pairedRows('lane_titles', 'lane_bodies', data_get($performanceSection, 'items', []), 5);
    $engagementRows = $listRows('engagement_items', data_get($engagementSection, 'items', []), 8);
    $repertoireRows = $listRows('repertoire_items', data_get($repertoireSection, 'items', []), $isCrooners ? 16 : 8);
    $testimonials = $testimonialRows();
    $audioCaption = old('audio_caption', data_get($medleySection, 'body'));
    $videoIntro = old('video_intro', data_get($videoSection, 'body'));
    $bookingBody = old('booking_body', data_get($bookingSection, 'body'));
    $externalAudioUrl = $epkPage->audio_url && str_starts_with($epkPage->audio_url, 'http') ? $epkPage->audio_url : '';
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($isEdit)
    <div class="mb-3">
        <a class="btn btn-info" href="{{ $epkPage->publicUrl() }}" target="_blank" rel="noopener">
            <i class="fa fa-eye"></i> Preview Public EPK
        </a>
    </div>
@endif

<div class="row">
    <div class="form-group col-md-8">
        <label>{{ $isCrooners ? 'Title' : 'Hero Title' }} <span class="text-danger">*</span></label>
        <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $epkPage->title) }}">
    </div>
    <div class="form-group col-md-4">
        <label>Slug</label>
        <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $epkPage->slug) }}">
    </div>

    <div class="form-group col-md-8">
        <label>{{ $isCrooners ? 'Tagline' : 'Agency Line / Subtitle' }}</label>
        <input type="text" class="form-control" name="subtitle" value="{{ old('subtitle', $epkPage->subtitle) }}">
    </div>
    <div class="form-group col-md-2">
        <label>Sort Order</label>
        <input type="number" min="0" class="form-control" name="sort_order" value="{{ old('sort_order', $epkPage->sort_order ?? 0) }}">
    </div>
    <div class="form-group col-md-2">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control">
            <option value="1" {{ (string) old('status', (int) $epkPage->status) === '1' ? 'selected' : '' }}>{{__('admin.Active')}}</option>
            <option value="0" {{ (string) old('status', (int) $epkPage->status) === '0' ? 'selected' : '' }}>{{__('admin.Inactive')}}</option>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>Published Date</label>
        <input type="text" class="form-control datetimepicker_mask" name="published_at" value="{{ old('published_at', optional($epkPage->published_at)->format('Y-m-d H:i')) }}">
    </div>
    <div class="form-group col-md-4">
        <label>Booking Email</label>
        <input type="email" class="form-control" name="booking_email" value="{{ old('booking_email', $epkPage->booking_email) }}">
    </div>
    <div class="form-group col-md-4">
        <label>Video Title</label>
        <input type="text" class="form-control" name="video_title" value="{{ old('video_title', $epkPage->video_title) }}">
    </div>

    <div class="form-group col-md-6">
        <label>Audio Caption / Title</label>
        <input type="text" class="form-control" name="audio_title" value="{{ old('audio_title', $epkPage->audio_title) }}">
    </div>
    <div class="form-group col-md-6">
        <label>YouTube URL</label>
        <input type="url" class="form-control" name="video_url" value="{{ old('video_url', $epkPage->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
    </div>

    <div class="form-group col-12">
        <label>{{ $isCrooners ? 'Crooners Overview' : 'Artist Overview' }}</label>
        <textarea name="overview_content" cols="30" rows="10" class="summernote">{{ old('overview_content', $epkPage->overview_content) }}</textarea>
    </div>
</div>

@if($isFullArtist || $isCrooners)
    @if($isFullArtist)
        <div class="card border mb-4">
            <div class="card-header"><h4>Performance Lanes</h4></div>
            <div class="card-body">
                @foreach($laneRows as $row)
                    <div class="row mb-2">
                        <div class="col-md-5"><input type="text" class="form-control" name="lane_titles[]" value="{{ $row['title'] }}" placeholder="Lane title"></div>
                        <div class="col-md-7"><input type="text" class="form-control" name="lane_bodies[]" value="{{ $row['body'] }}" placeholder="Venues / description"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card border mb-4">
            <div class="card-header"><h4>Notable Engagements</h4></div>
            <div class="card-body">
                @foreach($engagementRows as $item)
                    <input type="text" class="form-control mb-2" name="engagement_items[]" value="{{ $item }}" placeholder="Engagement">
                @endforeach
            </div>
        </div>
    @endif

    <div class="card border mb-4">
        <div class="card-header"><h4>{{ $isCrooners ? 'Featured Selections' : 'Repertoire' }}</h4></div>
        <div class="card-body">
            @foreach($repertoireRows as $item)
                <input type="text" class="form-control mb-2" name="repertoire_items[]" value="{{ $item }}" placeholder="{{ $isCrooners ? 'Song title' : 'Repertoire item' }}">
            @endforeach
        </div>
    </div>

    <div class="card border mb-4">
        <div class="card-header"><h4>Testimonials</h4></div>
        <div class="card-body">
            @foreach($testimonials as $testimonial)
                <div class="border rounded p-3 mb-3">
                    <input type="text" class="form-control mb-2" name="testimonial_sources[]" value="{{ $testimonial['source'] }}" placeholder="Name / source">
                    <input type="text" class="form-control mb-2" name="testimonial_credentials[]" value="{{ $testimonial['credential'] }}" placeholder="Credential">
                    <textarea class="form-control" rows="3" name="testimonial_quotes[]" placeholder="Quote">{{ $testimonial['quote'] }}</textarea>
                </div>
            @endforeach
        </div>
    </div>

    @if($isFullArtist)
        <div class="card border mb-4">
            <div class="card-header"><h4>Audio Medley</h4></div>
            <div class="card-body">
                <div class="form-group">
                    <label>Audio Caption</label>
                    <textarea class="form-control" rows="3" name="audio_caption">{{ $audioCaption }}</textarea>
                </div>
                @if($isEdit && $epkPage->audio_url)
                    <div class="mb-2">
                        <audio controls controlsList="nodownload" preload="none" class="w-100">
                            <source src="{{ str_starts_with($epkPage->audio_url, 'http') ? $epkPage->audio_url : asset($epkPage->audio_url) }}">
                        </audio>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="remove_audio_file" name="remove_audio_file" value="1">
                            <label class="custom-control-label" for="remove_audio_file">Remove current audio</label>
                        </div>
                    </div>
                @endif
                <input type="file" class="form-control" name="audio_file" accept="audio/mpeg,audio/wav,audio/ogg,audio/mp4,audio/aac,audio/webm">
                <input type="url" class="form-control mt-2" name="audio_url" value="{{ old('audio_url', $externalAudioUrl) }}" placeholder="Optional external audio URL">
                <small class="text-muted">Audio is streamed in the browser with no download CTA. Browser-accessible files cannot be treated as DRM-protected.</small>
            </div>
        </div>
    @endif

    @if($isCrooners)
        <div class="card border mb-4">
            <div class="card-header"><h4>Live Performance</h4></div>
            <div class="card-body">
                <label>Intro Text</label>
                <textarea class="form-control mb-2" rows="3" name="video_intro">{{ $videoIntro }}</textarea>
                <small class="text-muted">Use a public YouTube watch, embed, or youtu.be URL. The public page embeds with youtube-nocookie.com and no autoplay.</small>
            </div>
        </div>
    @endif

    <div class="card border mb-4">
        <div class="card-header"><h4>{{ $isCrooners ? 'Booking Information' : 'Booking & Representation' }}</h4></div>
        <div class="card-body">
            <textarea name="booking_body" cols="30" rows="8" class="summernote">{{ $bookingBody }}</textarea>
        </div>
    </div>
@else
    <div class="form-group col-12">
        <label>Sections</label>
        @for($i = 0; $i < $sectionCount; $i++)
            @php $section = $genericSections[$i] ?? ['title' => '', 'body' => '']; @endphp
            <div class="border rounded p-3 mb-3">
                <input type="text" class="form-control mb-2" name="section_titles[]" value="{{ $section['title'] ?? '' }}" placeholder="Section title">
                <textarea name="section_bodies[]" class="summernote" rows="6">{{ $section['body'] ?? '' }}</textarea>
            </div>
        @endfor
    </div>
@endif

<div class="row">
    <div class="form-group col-md-4">
        <label>Hero Image</label>
        @if($isEdit && $epkPage->hero_image)
            <div class="mb-2">
                <img src="{{ asset($epkPage->hero_image) }}" alt="" width="120">
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="remove_hero_image" name="remove_hero_image" value="1">
                    <label class="custom-control-label" for="remove_hero_image">Remove current image</label>
                </div>
            </div>
        @endif
        <input type="file" class="form-control" name="hero_image">
        <input type="text" class="form-control mt-2" name="hero_image_alt" value="{{ old('hero_image_alt', $epkPage->hero_image_alt) }}" placeholder="Hero image alt text">
    </div>

    <div class="form-group col-md-4">
        <label>Gold Feather / Logo Image</label>
        @if($isEdit && $epkPage->gold_feather_image)
            <div class="mb-2">
                <img src="{{ asset($epkPage->gold_feather_image) }}" alt="" width="120">
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="remove_gold_feather_image" name="remove_gold_feather_image" value="1">
                    <label class="custom-control-label" for="remove_gold_feather_image">Remove current image</label>
                </div>
            </div>
        @endif
        <input type="file" class="form-control" name="gold_feather_image">
        <input type="text" class="form-control mt-2" name="gold_feather_image_alt" value="{{ old('gold_feather_image_alt', $epkPage->gold_feather_image_alt) }}" placeholder="Logo image alt text">
    </div>

    <div class="form-group col-md-4">
        <label>Open Graph Image</label>
        @if($isEdit && $epkPage->og_image)
            <div class="mb-2">
                <img src="{{ asset($epkPage->og_image) }}" alt="" width="120">
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="remove_og_image" name="remove_og_image" value="1">
                    <label class="custom-control-label" for="remove_og_image">Remove current image</label>
                </div>
            </div>
        @endif
        <input type="file" class="form-control" name="og_image">
        <input type="text" class="form-control mt-2" name="og_image_alt" value="{{ old('og_image_alt', $epkPage->og_image_alt) }}" placeholder="Open Graph image alt text">
    </div>

    <div class="form-group col-md-6">
        <label>SEO Title</label>
        <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title', $epkPage->seo_title) }}">
    </div>
    <div class="form-group col-md-6">
        <label>SEO Description</label>
        <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $epkPage->seo_description) }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <button class="btn btn-primary">{{ $isEdit ? __('admin.Update') : __('admin.Save') }}</button>
    </div>
</div>

<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $("#title").on("focusout",function(){
                if ($("#slug").val()) {
                    return;
                }

                $("#slug").val(convertToEpkSlug($(this).val()));
            });
        });
    })(jQuery);

    function convertToEpkSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-');
    }
</script>
