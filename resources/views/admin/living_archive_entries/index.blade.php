@extends('admin.master_layout')
@section('title')
<title>Living Archive Pages</title>
@endsection
@section('admin-content')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Living Archive Pages</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">Living Archive Pages</div>
            </div>
          </div>

          <div class="section-body">
            <div class="d-flex flex-wrap mb-3" style="gap: 10px;">
                <a href="{{ route('admin.living-archive-entry.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
                <a href="{{ route('admin.living-archive.page') }}" class="btn btn-info"><i class="fas fa-cog"></i> Living Archive Settings</a>
            </div>
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
                                    <th>Type</th>
                                    <th>Sort</th>
                                    <th>Children</th>
                                    <th>{{__('admin.Status')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($archiveRows as $index => $row)
                                    @php
                                        $entry = $row['entry'];
                                        $depth = $row['depth'];
                                    @endphp
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>
                                            <span style="display:inline-block; padding-left: {{ $depth * 24 }}px;">
                                                @if($depth > 0)
                                                    <span class="text-muted">&mdash;</span>
                                                @endif
                                                {{ $entry->title }}
                                            </span>
                                            @if($entry->section_label)
                                                <div class="small text-muted" style="padding-left: {{ $depth * 24 }}px;">{{ $entry->section_label }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $entry->slug }}</td>
                                        <td>{{ $entry->page_type }}</td>
                                        <td>{{ $entry->sort_order }}</td>
                                        <td>{{ $entry->children_count }}</td>
                                        <td>
                                            <a href="javascript:;" onclick="changeLivingArchiveStatus({{ $entry->id }})">
                                                <input id="status_toggle_{{ $entry->id }}" type="checkbox" {{ $entry->status ? 'checked' : '' }} data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.InActive')}}" data-onstyle="success" data-offstyle="danger">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.living-archive-entry.edit', $entry->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $entry->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
        </section>
      </div>

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/living-archive-entry") }}'+"/"+id)
    }
    function changeLivingArchiveStatus(id){
        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 'DEMO'){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/living-archive-entry-status/')}}"+"/"+id,
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
