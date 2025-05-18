@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

@endpush
@section('seos')
 <meta charset="UTF-8">

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <meta name="title" content="{{$blog->seo_title}}">

    <meta name="description" content="{{ $blog->seo_description }}">
    <link rel="canonical" href="">
    <meta property="og:title" content="{{$blog->seo_title}}">
    <meta property="og:description" content="{!! $blog->seo_description !!}">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:site_name" content="{{$blog->seo_title}}">

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta property="article:modified_time" content="2023-03-01T12:33:34+00:00">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="">
    <meta name="twitter:image" content="">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
@endsection
@section('content')

<div class="ms_content_wrapper padder_top8">
<style>
  .ms_main_wrapper {
    background: #102034 !important;
  }
  .ms_mainindex_wrapper{
    background: #102034 !important;
  }
</style>
  <d class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">
  <!--Single Blog Section-->
  <section >
    <!-- Title -->
    <div class="text-center pt-5 pt-lg-5">
      <p class="text-warning fw-bold small mb-2">
      <h3>{{ date('m/d/Y', strtotime($blog->created_at)) }}</h3> 
      </p>
      <h1 class="fw-bold fs-2 fs-md-1">
       <h1>{{ $blog->title }}</h1> 
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
