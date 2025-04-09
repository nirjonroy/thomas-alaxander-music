@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="ms_content_wrapper padder_top8">

  <div class="ms_index_wrapper common_pages_space">
  <!--Single Blog Section-->
  <section class="py-5">
    <!-- Title -->
    <div class="text-center pt-5 pt-lg-5">
      <p class="text-warning fw-bold small mb-2">
        {{ date('m/d/Y', strtotime($blog->created_at)) }}
      </p>
      <h1 class="fw-bold fs-2 fs-md-1">
        {{ $blog->title }}
      </h1>
    </div>
  
    <!-- Image -->
    <div class="container my-4">
      <div
        class="w-100 rounded mx-auto bg-white mb-5"
        style="background-image: url('{{ asset($blog->image) }}'); background-size: cover; background-position: center; height: 400px; max-height: 650px;"
      >
      </div>
    </div>
  <style>
    p{
      font-size: 14px !important;
    }
  </style>
    <!-- Container -->
    <div class="container mt-n5">
      <div class="bg-white p-4 p-md-5 shadow-sm rounded text-dark" style="font-family: Georgia, serif; font-size: 1.25rem; line-height: 1.8;">
        <p class="small text-muted mb-4 pb-2 song_name small" style="font-size: 14px">
          {!! $blog->description !!}
        </p>
      </div>
    </div>
  </section>
  </div>
</div>
        <!--End Single Blog Section-->
@endsection
