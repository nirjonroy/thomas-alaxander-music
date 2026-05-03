@extends('admin.master_layout')
@section('title')
<title>Edit Event</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Edit Event</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.event.index') }}">Events</a></div>
              <div class="breadcrumb-item">Edit Event</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.event.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Event</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.event.update',$event->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Current Image')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ $event->image ? asset('uploads/custom-images2/'.$event->image) : asset('uploads/website-images/preview.png') }}" alt="">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image')}}</label>
                                    <input type="file" class="form-control-file" name="image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $event->name) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $event->slug) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" id="date" class="form-control" name="date" value="{{ old('date', $event->date) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="time" value="{{ old('time', $event->time) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="location" value="{{ old('location', $event->location) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Ticket Price</label>
                                    <input type="text" class="form-control" name="ticket_price" value="{{ old('ticket_price', $event->ticket_price) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Description')}}</label>
                                    <textarea name="description" cols="30" rows="10" class="summernote">{{ old('description', $event->description) }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="1" @selected(old('status', $event->status) == 1)>{{__('admin.Active')}}</option>
                                        <option value="0" @selected(old('status', $event->status) == 0)>{{__('admin.InActive')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Title')}}</label>
                                    <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title', $event->seo_title) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Description')}}</label>
                                    <textarea name="seo_description" cols="30" rows="10" class="form-control text-area-5">{{ old('seo_description', $event->seo_description) }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>SEO Keywords</label>
                                    <textarea name="seo_keywords" rows="3" class="form-control">{{ old('seo_keywords', $event->seo_keywords) }}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Author</label>
                                    <input type="text" class="form-control" name="seo_author" value="{{ old('seo_author', $event->seo_author) }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Publisher</label>
                                    <input type="text" class="form-control" name="seo_publisher" value="{{ old('seo_publisher', $event->seo_publisher) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Canonical URL</label>
                                    <input type="text" class="form-control" name="canonical_url" value="{{ old('canonical_url', $event->canonical_url) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $event->meta_title) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Meta Description</label>
                                    <textarea name="meta_description" cols="30" rows="4" class="form-control text-area-5">{{ old('meta_description', $event->meta_description) }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>Meta Image (upload)</label>
                                    <input type="file" class="form-control" name="meta_image" accept=".jpg,.jpeg,.png,.webp,.svg">
                                    @if ($event->meta_image)
                                        <div class="mt-2">
                                            <img src="{{ str_starts_with($event->meta_image, 'http') ? $event->meta_image : asset($event->meta_image) }}" alt="Meta image preview" style="max-height: 120px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Site Name</label>
                                    <input type="text" class="form-control" name="site_name" value="{{ old('site_name', $event->site_name) }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Meta Copyright</label>
                                    <input type="text" class="form-control" name="meta_copyright" value="{{ old('meta_copyright', $event->meta_copyright) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $("#name").on("focusout", function() {
                if (!$("#slug").val()) {
                    $("#slug").val(convertToSlug($(this).val()));
                }
            });
        });
    })(jQuery);

    function convertToSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-');
    }

    function previewThumnailImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-img');
            output.src = reader.result;
        }

        reader.readAsDataURL(event.target.files[0]);
    };
</script>
@endsection
