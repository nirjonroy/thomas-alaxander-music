@extends('admin.master_layout')
@section('title')
<title>Create EPK Page</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Create EPK Page</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('admin.epk-page.index') }}">EPK Pages</a></div>
                <div class="breadcrumb-item">Create</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.epk-page.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> EPK Pages</a>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.epk-page.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @include('admin.epk_pages.form', ['epkPage' => $epkPage, 'isEdit' => false])
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
