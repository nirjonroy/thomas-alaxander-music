@extends('frontend.app')
@section('title', 'About Thomas Alexander')

@push('css')
@section('seos')
    @php
        $SeoSettings = DB::table('seo_settings')->where('id', 2)->first();
    @endphp

    <meta charset="UTF-8">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="title" content="{{ $SeoSettings->seo_title }}">
    <meta name="description" content="{{ $SeoSettings->seo_description }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $SeoSettings->seo_title }}">
    <meta property="og:description" content="{{ $SeoSettings->seo_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $SeoSettings->seo_title }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset($about->video_background) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta property="article:modified_time" content="{{ now()->toIso8601String() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $SeoSettings->seo_title }}">
    <meta name="twitter:description" content="{{ $SeoSettings->seo_description }}">
    <meta name="twitter:image" content="{{ asset($about->video_background) }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
@endsection

<style>
    /* Main About Section */
    .ms_about_wrapper {
        background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        margin-bottom: 40px;
    }

    /* About Image */
    .ms_about_img {
        position: relative;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        overflow: hidden;
    }

    .ms_about_img img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .ms_about_img:hover img {
        transform: scale(1.03);
    }

    /* About Content */
    .ms_about_content {
        padding: 30px;
        color: #fff;
    }

    .ms_about_content h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #fff;
        text-align: center;
        position: relative;
        padding-bottom: 15px;
    }

    .ms_about_content h1:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #ff4d00, #ff9500);
    }

    .ms_about_content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #e0e0e0;
        margin-bottom: 20px;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .ms_about_content {
            padding: 25px;
        }
        
        .ms_about_content h1 {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        .ms_about_wrapper {
            border-radius: 8px;
        }
        
        .ms_about_content {
            padding: 20px;
        }
        
        .ms_about_content h1 {
            font-size: 1.6rem;
        }
        
        .ms_about_content p {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .ms_about_content {
            padding: 15px;
        }
        
        .ms_about_content h1 {
            font-size: 1.4rem;
            padding-bottom: 10px;
        }
        
        .ms_about_content h1:after {
            width: 60px;
            height: 2px;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .ms_about_content {
        animation: fadeIn 0.8s ease-out;
    }
</style>
@endpush

@section('content')
<div class="ms_content_wrapper padder_top8">
    <div class="ms_index_wrapper common_pages_space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="ms_about_wrapper">
                        <div class="ms_about_img">
                            <img src="{{ asset($about->video_background) }}" alt="Thomas Alexander" class="img-fluid">
                        </div>
                        <div class="ms_about_content">
                            <h1>{{ $about->description_three }}</h1>
                            <div class="about-text">
                                {!! $about->about_us !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection