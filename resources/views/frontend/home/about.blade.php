@extends('frontend.app')
@push('css')
@section('seos')
    @php
        $SeoSettings = \App\Models\SeoSetting::where('page_name', 'About Us')->first();
        $pageTitle = optional($SeoSettings)->seo_title ?? 'About Thomas Alexander';
        $pageDesc = optional($SeoSettings)->seo_description ?? 'Learn about Thomas Alexander’s musical legacy.';
        $pageUrl = url()->current();
        $canonical = optional($SeoSettings)->canonical_url ?: $pageUrl;
        $keywords = optional($SeoSettings)->seo_keywords ?? 'Thomas Alexander biography, About Thomas Alexander';
        $siteName = optional($SeoSettings)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = optional($SeoSettings)->meta_title ?: $pageTitle;
        $rawDesc = optional($SeoSettings)->meta_description ?: $pageDesc;
        $pageDesc = \Illuminate\Support\Str::limit(strip_tags($rawDesc), 180);
        $author = optional($SeoSettings)->seo_author ?? $siteName;
        $publisher = optional($SeoSettings)->seo_publisher ?? $siteName;
        $metaImageValue = optional($SeoSettings)->meta_image;
        $fallbackImage = asset($about->video_background);
        $metaImage = $metaImageValue
            ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
            : $fallbackImage;
        $copyright = optional($SeoSettings)->meta_copyright;
    @endphp
    @section('title', $pageTitle)

    <meta charset="UTF-8">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $author }}">
    <meta name="publisher" content="{{ $publisher }}">
    @if ($copyright)
        <meta name="copyright" content="{{ $copyright }}">
    @endif
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta property="article:modified_time" content="{{ now()->toIso8601String() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
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
    font-size: 2.5rem; /* Larger base size */
    font-weight: 700;
    margin: 0 0 25px 0;
    color: #fff;
    text-align: center;
    text-transform: uppercase; /* Makes it more prominent */
    letter-spacing: 1px; /* Improves readability */
    line-height: 1.2;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.3); /* Subtle shadow for depth */
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
             font-size: 2.2rem;
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
            font-size: 1.8rem;
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
            font-size: 1.5rem;
        margin-bottom: 15px;
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
                            <img src="{{ asset($about->video_background) }}" alt="Thomas Alexander" class="img-fluid" style="background:#FF4D4F !important;">
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
