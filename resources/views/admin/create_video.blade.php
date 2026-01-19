@extends('admin.master_layout')
@section('title')
<title>Video</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Videos</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.video.index') }}">Video</a></div>
              <div class="breadcrumb-item">Videos</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.video.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.All Videos')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail')}}</label>
                                    <input type="file" class="form-control-file" name="thumbnail">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="title" class="form-control" name="title" required>
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.Description')}}</label>
                                    <textarea class="form-control" name="description"></textarea>
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.URL')}} <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" name="url" required>
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{__('admin.Active')}}</option>
                                        <option value="0">{{__('admin.InActive')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Save')}}</button>
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
            $("#title").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })
        });
    })(jQuery);

    function convertToSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-');
    }
</script>
@endsection
