@extends('admin.master_layout')
@section('title')
<title>Edit Living Archive Page</title>
@endsection
@section('admin-content')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Edit Living Archive Page</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.living-archive-entry.index') }}">Living Archive Pages</a></div>
              <div class="breadcrumb-item">Edit</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.living-archive-entry.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Living Archive Pages</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.living-archive-entry.update', $entry->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.living_archive_entries.form', ['entry' => $entry, 'parentOptions' => $parentOptions, 'isEdit' => true])
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>
@endsection
