@extends('frontend.app')

@section('seos')
    @php
        use Illuminate\Support\Str;

        $seoSetting = \App\Models\SeoSetting::where('page_name', 'Video')->first();
        $siteName = optional($seoSetting)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = optional($seoSetting)->meta_title ?: (optional($seoSetting)->seo_title ?: 'Video Gallery');
        $rawDesc = optional($seoSetting)->meta_description
            ?: (optional($seoSetting)->seo_description ?: 'Watch official videos and performances from Thomas Alexander.');
        $pageDesc = Str::limit(strip_tags($rawDesc), 180);
        $heroTitle = optional($seoSetting)->seo_title ?: $pageTitle;
        $heroDescRaw = optional($seoSetting)->seo_description ?: $rawDesc;
        $heroDesc = Str::limit(strip_tags($heroDescRaw ?? ''), 200);
        $pageUrl = url()->current();
        $canonical = optional($seoSetting)->canonical_url ?: $pageUrl;
        $keywords = optional($seoSetting)->seo_keywords ?? 'Thomas Alexander videos, performances, gallery';
        $author = optional($seoSetting)->seo_author ?? $siteName;
        $publisher = optional($seoSetting)->seo_publisher ?? $siteName;
        $metaImageValue = optional($seoSetting)->meta_image;
        $metaImage = $metaImageValue
            ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
            : asset(siteInfo()->logo);
        $heroImage = $metaImage;
        $copyright = optional($seoSetting)->meta_copyright;
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
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
@endsection

@push('css')
<style>
    .video-page {
        padding: 36px 24px 40px;
    }
    .video-shell {
        position: relative;
        background: radial-gradient(circle at 14% 18%, rgba(255, 255, 255, 0.06), transparent 56%),
            linear-gradient(140deg, #0b1729 0%, #0a1525 52%, #08101e 100%);
        border-radius: 28px;
        padding: 28px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 35px 80px rgba(5, 10, 18, 0.55);
        margin: 0 auto 36px;
        color: #f7f1e6;
        max-width: 1220px;
    }
    .video-shell::after {
        content: "";
        position: absolute;
        inset: 12px;
        border-radius: 22px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }
    .video-hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 40px 48px;
        background-image: var(--hero-image);
        background-size: cover;
        background-position: right center;
        min-height: 260px;
        margin-bottom: 26px;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
    }
    .video-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(100deg, rgba(7, 12, 20, 0.94) 0%, rgba(7, 12, 20, 0.72) 55%, rgba(7, 12, 20, 0.18) 100%);
    }
    .video-hero-content {
        position: relative;
        z-index: 1;
        max-width: 540px;
        width: min(60%, 540px);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .video-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.28em;
        color: rgba(245, 235, 220, 0.7);
    }
    .video-hero-title {
        font-size: clamp(28px, 3.2vw, 44px);
        margin: 0;
        color: #fdf7ed;
        font-weight: 700;
        line-height: 1.18;
    }
    .video-hero-desc {
        margin: 0;
        color: rgba(245, 235, 220, 0.82);
        font-size: 15px;
        line-height: 1.6;
    }
    .video-hero-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        padding: 10px 22px;
        border-radius: 999px;
        background: linear-gradient(120deg, #ff4b4b, #d61d1d);
        color: #fff;
        font-weight: 700;
        text-transform: none;
        letter-spacing: 0.02em;
        font-size: 13px;
        box-shadow: 0 12px 24px rgba(214, 29, 29, 0.35);
        text-decoration: none;
    }
    .video-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }
    .video-card {
        background: rgba(21, 30, 46, 0.94);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 18px 30px rgba(6, 10, 18, 0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .video-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 38px rgba(6, 10, 18, 0.45);
    }
    .video-card-media {
        position: relative;
        background: #0b1523;
    }
    .video-card-media img,
    .video-card-media video {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
    }
    .video-card-media iframe {
        width: 100%;
        height: 190px;
        border: 0;
        display: block;
    }
    .video-card-body {
        padding: 14px 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }
    .video-card-title {
        margin: 0;
        font-size: 14px;
        color: #f7f1e6;
        line-height: 1.4;
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .video-card-title a {
        color: inherit;
        text-decoration: none;
    }
    .video-card-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 9px 16px;
        border-radius: 9px;
        background: linear-gradient(120deg, #ff4b4b, #d61d1d);
        color: #fff;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: none;
        font-size: 12px;
        border: none;
        box-shadow: 0 10px 20px rgba(214, 29, 29, 0.35);
        text-decoration: none;
    }
    .video-card-cta i {
        margin-right: 6px;
    }
    .video-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 28px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.04);
        color: rgba(245, 235, 220, 0.8);
    }
    @media (max-width: 1200px) {
        .video-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 991px) {
        .video-page {
            padding: 28px 18px 36px;
        }
        .video-hero {
            padding: 32px 36px;
        }
    }
    @media (max-width: 900px) {
        .video-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 768px) {
        .video-hero {
            padding: 26px 22px;
            min-height: 220px;
        }
        .video-hero-content {
            max-width: 100%;
            width: 100%;
        }
        .video-card-media img,
        .video-card-media video,
        .video-card-media iframe {
            height: 170px;
        }
    }
    @media (max-width: 576px) {
        .video-page {
            padding: 22px 14px 32px;
        }
        .video-shell {
            padding: 20px;
        }
        .video-hero {
            padding: 22px 18px;
        }
        .video-hero-title {
            font-size: 24px;
        }
        .video-grid {
            grid-template-columns: 1fr;
        }
        .video-card-media img,
        .video-card-media video,
        .video-card-media iframe {
            height: 160px;
        }
    }
</style>
@endpush

@section('content')
<div class="ms_index_wrapper common_pages_space video-page">
    <div class="video-shell">
        <div class="video-hero" style="--hero-image: url('{{ $heroImage }}');">
            <div class="video-hero-content">
                <span class="video-kicker">Video</span>
                <h1 class="video-hero-title">{{ $heroTitle }}</h1>
                <p class="video-hero-desc">{{ $heroDesc }}</p>
                <a class="video-hero-cta" href="#video-grid">Browse All Videos</a>
            </div>
        </div>

        <div class="video-grid" id="video-grid">
            @forelse($videos as $video)
                @php
                    $url = trim($video->url ?? '');
                    $isYoutube = Str::contains($url, ['youtube.com', 'youtu.be']);
                    $ytId = null;
                    if ($isYoutube) {
                        if (preg_match('~youtu\.be/([^\?\&]+)~', $url, $m)) {
                            $ytId = $m[1];
                        } elseif (preg_match('~v=([^&]+)~', $url, $m)) {
                            $ytId = $m[1];
                        } elseif (preg_match('~embed/([^\?\&]+)~', $url, $m)) {
                            $ytId = $m[1];
                        }
                    }
                    $ytThumb = $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : null;
                @endphp
                <div class="video-card">
                    <div class="video-card-media">
                        @if(!empty($video->thumbnail))
                            <a href="{{ $video->url }}" target="_blank" rel="noopener">
                                <img src="{{ asset('uploads/video-thumbnails/'.$video->thumbnail) }}"
                                     alt="{{ $video->title }}">
                            </a>
                        @elseif($ytThumb)
                            <a href="{{ $video->url }}" target="_blank" rel="noopener">
                                <img src="{{ $ytThumb }}" alt="{{ $video->title }}">
                            </a>
                        @else
                            <video controls>
                                <source src="{{ $video->url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                    <div class="video-card-body">
                        <p class="video-card-title">
                            <a href="{{ $video->url }}" target="_blank" rel="noopener">
                                {{ Str::limit($video->title, 40) }}
                            </a>
                        </p>
                        <a href="{{ $video->url }}" target="_blank" rel="noopener" class="video-card-cta">
                            <i class="fa fa-play"></i> Watch Now
                        </a>
                    </div>
                </div>
            @empty
                <div class="video-empty">
                    <strong>No videos are available</strong>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
