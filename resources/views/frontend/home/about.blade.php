@extends('frontend.app')
@section('title', 'About Thomas Alexander')
@push('css')
@section('seos')
@php
        $SeoSettings = DB::table('seo_settings')->where('id', 2)->first();
    @endphp

    <meta charset="UTF-8">

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <meta name="title" content="{{$SeoSettings->seo_title}}">

    <meta name="description" content="{{$SeoSettings->seo_description}}">
    <link rel="canonical" href="">
    <meta property="og:title" content="{{$SeoSettings->seo_title}}">
    <meta property="og:description" content="{{$SeoSettings->seo_description}}">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:site_name" content="{{$SeoSettings->seo_title}}">

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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')

<div class="ms_content_wrapper padder_top8">
   
    <div class="ms_index_wrapper common_pages_space">
      
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ms_about_wrapper">
                        <div class="ms_about_img">
                            <img src="{{ asset($about->video_background) }}" alt="About Thomas Alexander" width="200px" height="60px" style="background:white">
                            </div>
                            <div class="ms_about_content" style="margin-top:10px; background: black; padding: 5px;">

                                <h1 style="font-size:14pt">{{$about->description_three}}</h1>

                                <p style="background: white !important">
                                    {!!$about->about_us!!}
                                </p>
                                
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>                          
                                
                                    

    </div>
</div>

@endsection
