@php
    $pageTypes = [
        'archive_section' => 'Archive Section',
        'archive_page' => 'Archive Page',
        'memoir' => 'Memoir',
        'ceremonial_lineage' => 'Ceremonial Lineage',
        'heritage' => 'Heritage',
        'historical_article' => 'Historical Article',
    ];
@endphp

<div class="row">
    <div class="form-group col-md-8">
        <label>Title <span class="text-danger">*</span></label>
        <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $entry->title) }}">
    </div>
    <div class="form-group col-md-4">
        <label>Slug</label>
        <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $entry->slug) }}">
    </div>

    <div class="form-group col-md-4">
        <label>Section Label</label>
        <input type="text" class="form-control" name="section_label" value="{{ old('section_label', $entry->section_label) }}">
    </div>
    <div class="form-group col-md-4">
        <label>Parent Page</label>
        <select name="parent_id" class="form-control select2">
            <option value="">Root Page</option>
            @foreach($parentOptions as $row)
                @php
                    $parentEntry = $row['entry'];
                    $selectedParent = (string) old('parent_id', $entry->parent_id) === (string) $parentEntry->id;
                @endphp
                <option value="{{ $parentEntry->id }}" {{ $selectedParent ? 'selected' : '' }}>
                    {{ str_repeat('-- ', $row['depth']) }}{{ $parentEntry->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label>Page Type <span class="text-danger">*</span></label>
        <select name="page_type" class="form-control">
            @foreach($pageTypes as $value => $label)
                <option value="{{ $value }}" {{ old('page_type', $entry->page_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>Sort Order</label>
        <input type="number" min="0" class="form-control" name="sort_order" value="{{ old('sort_order', $entry->sort_order ?? 0) }}">
    </div>
    <div class="form-group col-md-4">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control">
            <option value="1" {{ (string) old('status', (int) $entry->status) === '1' ? 'selected' : '' }}>{{__('admin.Active')}}</option>
            <option value="0" {{ (string) old('status', (int) $entry->status) === '0' ? 'selected' : '' }}>{{__('admin.Inactive')}}</option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label>Published Date</label>
        <input type="text" class="form-control datetimepicker_mask" name="published_at" value="{{ old('published_at', optional($entry->published_at)->format('Y-m-d H:i')) }}">
    </div>

    <div class="form-group col-12">
        <label>Teaser</label>
        <textarea name="teaser" class="form-control" rows="4">{{ old('teaser', $entry->teaser) }}</textarea>
    </div>

    <div class="form-group col-12">
        <label>Full Rich-Text Content</label>
        <textarea name="content" cols="30" rows="12" class="summernote">{{ old('content', $entry->content) }}</textarea>
    </div>

    <div class="form-group col-md-4">
        <label>Featured Image</label>
        @if($isEdit && $entry->featured_image)
            <div class="mb-2">
                <img src="{{ asset($entry->featured_image) }}" alt="" width="120">
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="remove_featured_image" name="remove_featured_image" value="1">
                    <label class="custom-control-label" for="remove_featured_image">Remove current image</label>
                </div>
            </div>
        @endif
        <input type="file" class="form-control" name="featured_image">
        <input type="text" class="form-control mt-2" name="featured_image_alt" value="{{ old('featured_image_alt', $entry->featured_image_alt) }}" placeholder="Featured image alt text">
    </div>

    <div class="form-group col-md-4">
        <label>Historical Document Image</label>
        @if($isEdit && $entry->document_image)
            <div class="mb-2">
                <img src="{{ asset($entry->document_image) }}" alt="" width="120">
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="remove_document_image" name="remove_document_image" value="1">
                    <label class="custom-control-label" for="remove_document_image">Remove current document</label>
                </div>
            </div>
        @endif
        <input type="file" class="form-control" name="document_image">
        <input type="text" class="form-control mt-2" name="document_image_alt" value="{{ old('document_image_alt', $entry->document_image_alt) }}" placeholder="Document image alt text">
    </div>

    <div class="form-group col-md-4">
        <label>Open Graph Image</label>
        @if($isEdit && $entry->og_image)
            <div class="mb-2">
                <img src="{{ asset($entry->og_image) }}" alt="" width="120">
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="remove_og_image" name="remove_og_image" value="1">
                    <label class="custom-control-label" for="remove_og_image">Remove current OG image</label>
                </div>
            </div>
        @endif
        <input type="file" class="form-control" name="og_image">
        <input type="text" class="form-control mt-2" name="og_image_alt" value="{{ old('og_image_alt', $entry->og_image_alt) }}" placeholder="Open Graph image alt text">
    </div>

    <div class="form-group col-12">
        <label>Document Caption / Source Note</label>
        <textarea name="document_caption" class="form-control" rows="3">{{ old('document_caption', $entry->document_caption) }}</textarea>
    </div>

    <div class="form-group col-md-6">
        <label>Meta Title</label>
        <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $entry->meta_title) }}">
    </div>
    <div class="form-group col-md-6">
        <label>Meta Description</label>
        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $entry->meta_description) }}</textarea>
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

                $("#slug").val(convertToArchiveSlug($(this).val()));
            });
        });
    })(jQuery);

    function convertToArchiveSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-');
    }
</script>
