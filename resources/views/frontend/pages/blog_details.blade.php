@extends('frontend.app')
@php($detailTitle = $blog->seo_title ?? $blog->title)
@section('title', $detailTitle)
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

@endpush
@section('seos')
    {{-- Basic --}}
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    {{-- Page title & canonical --}}
    @php
        use Illuminate\Support\Str;
        $pageTitle = $blog->seo_title ?? $blog->title;
        $pageDesc  = Str::limit(strip_tags($blog->seo_description ?? $blog->description), 180);
        $pageUrl   = url()->current();
        $imageUrl  = $blog->image ? asset($blog->image) : null;
        $seoDefaults = \App\Models\SeoSetting::where('page_name', 'Blog Details')->first();
        $canonical = optional($seoDefaults)->canonical_url ?: $pageUrl;
        $keywords = optional($seoDefaults)->seo_keywords ?? ($blog->seo_keywords ?? $blog->title);
        $authorMeta = optional($seoDefaults)->seo_author ?? ($blog->author ?? 'Thomas Alexander');
        $siteName = optional($seoDefaults)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = $pageTitle ?: (optional($seoDefaults)->meta_title ?? $siteName);
        $rawDesc = $blog->seo_description ?? $blog->description;
        $rawDesc = $rawDesc ?: optional($seoDefaults)->meta_description;
        $pageDesc = Str::limit(strip_tags($rawDesc ?? ''), 180);
        $metaImageValue = optional($seoDefaults)->meta_image;
        $metaImage = $imageUrl
            ?: ($metaImageValue
                ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
                : asset(siteInfo()->logo));
        $publisher = optional($seoDefaults)->seo_publisher ?? $siteName;
        $copyright = optional($seoDefaults)->meta_copyright;
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $authorMeta }}">
    <meta name="publisher" content="{{ $publisher }}">
    @if ($copyright)
        <meta name="copyright" content="{{ $copyright }}">
    @endif
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph (Facebook, etc.) --}}
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $pageTitle }}">

    {{-- Optional article data --}}
    <meta property="article:published_time" content="{{ optional($blog->created_at)->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ optional($blog->updated_at ?? $blog->created_at)->toIso8601String() }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
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
      <h3 style="font-size: 16px;
    font-weight: bold;">{{ date('m/d/Y', strtotime($blog->created_at)) }}</h3> 
      </p>
      <h1 class="fw-bold fs-2 fs-md-1" >
       <h1 style="font-size: 27px;
    font-weight: bold;">{{ $blog->title }}</h1> 
      </h1>
    </div>
  
    <!-- Image -->
    <div class="container my-4">
      <div
        class="w-100 rounded mx-auto bg-white mb-5"
        style="background-image: url('{{ asset($blog->image) }}'); background-size: cover; background-position: center; height: 400px; max-height: 500px;"
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
