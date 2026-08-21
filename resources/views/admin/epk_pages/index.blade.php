@extends('admin.master_layout')
@section('title')
<title>EPK Pages</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>EPK Pages</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                <div class="breadcrumb-item">EPK Pages</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.epk-page.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.SN')}}</th>
                                            <th>Title</th>
                                            <th>Slug</th>
                                            <th>Sort</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th>{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($epkPages as $index => $epkPage)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>
                                                    {{ $epkPage->title }}
                                                    @if($epkPage->subtitle)
                                                        <div class="small text-muted">{{ $epkPage->subtitle }}</div>
                                                    @endif
                                                </td>
                                                <td>{{ $epkPage->slug }}</td>
                                                <td>{{ $epkPage->sort_order }}</td>
                                                <td>
                                                    <a href="javascript:;" onclick="changeEpkStatus({{ $epkPage->id }})">
                                                        <input id="status_toggle_{{ $epkPage->id }}" type="checkbox" {{ $epkPage->status ? 'checked' : '' }} data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ $epkPage->publicUrl() }}" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    <a href="{{ route('admin.epk-page.edit', $epkPage->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $epkPage->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/epk-page") }}'+"/"+id)
    }
    function changeEpkStatus(id){
        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 'DEMO'){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/epk-page-status/')}}"+"/"+id,
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
