@extends('admin.master_layout')
@section('title')
<title>Video</title>
@endsection

@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Video</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                <div class="breadcrumb-item">Video</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.video.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.SN')}}</th>
                                            <th>{{__('admin.Title')}}</th>
                                            <th>{{__('admin.Thumbnail')}}</th>
                                            <th>{{__('admin.URL')}}</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th>{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($videos as $index => $video)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $video->title }}</td>
                                            <td>
                                                @if($video->thumbnail)
                                                <img src="{{ asset('uploads/video-thumbnails/'.$video->thumbnail) }}" alt="" class="thumbnail_image" style="max-width:100px;">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ $video->url }}" target="_blank">{{ $video->url }}</a>
                                            </td>
                                            <td>
                                                @if($video->status == 1)
                                                <a href="javascript:;" onclick="changeVideoStatus({{ $video->id }})">
                                                    <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                                @else
                                                <a href="javascript:;" onclick="changeVideoStatus({{ $video->id }})">
                                                    <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.video.edit', $video->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                  <form action="{{ route('admin.video.destroy', $video->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this video?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
</form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                {{__('admin.You can not delete this video.')}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/video-delete/") }}'+"/"+id)
    }
    function changeVideoStatus(id){
        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 'DEMO'){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/video-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){
                console.log(err);
            }
        })
    }
</script>
@endsection
