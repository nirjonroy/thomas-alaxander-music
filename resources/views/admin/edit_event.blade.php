@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Product Category')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Product Category')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.event.index') }}">Events</a></div>
              <div class="breadcrumb-item">Edit Event</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.event.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Product Category')}}</a>
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
                                        <img src="{{ asset('uploads/custom-images2/'.$event->image) }}" alt="" width="100px">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="image">
                                </div>

                             
                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $event->name }}">
                                </div>
                                <div class="form-group col-12">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" id="date" class="form-control"  name="date" value="{{ $event->date }}">
                                </div>
                              	<div class="form-group col-12">
                                    <label>Time <span class="text-danger">*</span></label>
                                    <input type="time" id="" class="form-control"  name="time" value="{{ $event->time }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Location <span class="text-danger">*</span></label>
                                    <input type="text" id="" class="form-control"  name="location" value="{{ $event->location }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Ticket Price <span class="text-danger">*</span></label>
                                    <input type="text" id="" class="form-control"  name="ticket_price" value="{{ $event->ticket_price }}">
                                </div>
                              
                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $event->status==1 ? 'selected': '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $event->status==0 ? 'selected': '' }}  value="0">{{__('admin.InActive')}}</option>
                                    </select>
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
            $("#name").on("focusout",function(e){
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
