@extends('frontend.app')

@section('seos')
    @php
        $SeoSettings = \App\Models\SeoSetting::where('page_name', 'About Us')->first();
        $pageTitle = optional($SeoSettings)->seo_title ?? 'About Thomas Alexander';
        $pageDesc = optional($SeoSettings)->seo_description ?? "Learn about Thomas Alexander's musical legacy.";
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
        $fallbackImage = $about->video_background ? asset($about->video_background) : asset(siteInfo()->logo);
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

@push('css')
<style>
    .about-page {
        padding: 36px 24px 40px;
    }
    .about-shell {
        position: relative;
        width: 100%;
        border-radius: 26px;
        padding: 28px;
        background: radial-gradient(circle at 14% 18%, rgba(255, 255, 255, 0.06), transparent 56%),
            linear-gradient(140deg, #0b1729 0%, #0a1525 52%, #08101e 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 30px 70px rgba(6, 10, 18, 0.55);
        overflow: hidden;
    }
    .about-shell::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: var(--about-image);
        background-size: cover;
        background-position: center;
        opacity: 0.12;
    }
    .about-shell::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 75% 20%, rgba(255, 120, 50, 0.18), transparent 45%),
            radial-gradient(circle at 12% 80%, rgba(255, 255, 255, 0.1), transparent 40%);
    }
    .about-content {
        position: relative;
        z-index: 1;
        color: #f7f1e6;
    }
    .about-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
        border-radius: 22px;
        overflow: hidden;
        background: rgba(12, 18, 30, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        gap: 12px;
    }
    .about-hero-content {
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .about-kicker {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.24em;
        color: rgba(245, 235, 220, 0.7);
    }
    .about-title {
        margin: 0;
        font-size: clamp(26px, 3.2vw, 40px);
        line-height: 1.2;
        font-weight: 700;
    }
    .about-intro {
        margin: 0;
        font-size: 15px;
        line-height: 1.6;
        color: rgba(245, 235, 220, 0.82);
    }
    .about-hero-media {
        background-image: var(--about-image);
        background-size: cover;
        background-position: center;
        position: relative;
        min-height: 260px;
    }
    .about-hero-media::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(10, 16, 28, 0.95), rgba(10, 16, 28, 0.2));
    }
    .about-body {
        margin-top: 22px;
        background: rgba(12, 18, 30, 0.75);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 24px;
        line-height: 1.8;
        color: rgba(245, 235, 220, 0.86);
    }
    .about-body p,
    .about-body li {
        font-size: 15px;
    }
    .about-body h1 {
        margin: 0 0 12px;
        font-size: 20px;
        line-height: 1.3;
        font-weight: 600;
        color: #f7f1e6;
        text-align: left;
    }
    .about-body h2 {
        margin: 0 0 10px;
        font-size: 22px;
        color: #f7f1e6;
    }
    .about-body h3 {
        margin: 18px 0 8px;
        font-size: 18px;
        color: #f7f1e6;
    }
    .about-body h4 {
        margin: 16px 0 6px;
        font-size: 16px;
        color: #f7f1e6;
    }
    .about-body a {
        color: #ff9b60;
    }
    @media (max-width: 991px) {
        .about-page {
            padding: 28px 18px 36px;
        }
        .about-hero {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 576px) {
        .about-page {
            padding: 22px 14px 32px;
        }
        .about-shell {
            padding: 20px;
        }
        .about-hero-content {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
@php
    $aboutImage = $about->video_background ? asset($about->video_background) : asset(siteInfo()->logo);
    $aboutTitle = $about->description_three ?: 'About Thomas Alexander';
    $aboutBodyHtml = html_entity_decode($about->about_us ?? '', ENT_QUOTES, 'UTF-8');
    $aboutBodyHtml = str_replace("\xC2\xA0", ' ', $aboutBodyHtml);
    $aboutBodyHtml = preg_replace('/<p>(\s|&nbsp;)*<\/p>/i', '', $aboutBodyHtml);
    $aboutBodyPlain = trim(preg_replace('/\s+/', ' ', strip_tags($aboutBodyHtml)));
    $aboutIntro = \Illuminate\Support\Str::limit($aboutBodyPlain, 180);
@endphp
<div class="ms_index_wrapper common_pages_space about-page">
    <div class="about-shell" style="--about-image: url('{{ $aboutImage }}');">
        <div class="about-content">
            <div class="about-hero">
                <div class="about-hero-content">
                    <span class="about-kicker">About</span>
                    <h1 class="about-title">{{ $aboutTitle }}</h1>
                    @if(!empty($aboutIntro))
                        <p class="about-intro">{{ $aboutIntro }}</p>
                    @endif
                </div>
                <div class="about-hero-media" role="img" aria-label="Thomas Alexander"></div>
            </div>

            <div class="about-body">
                {!! $aboutBodyHtml !!}
            </div>
        </div>
    </div>
</div>
@endsection
