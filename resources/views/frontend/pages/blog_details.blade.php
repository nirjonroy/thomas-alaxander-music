@extends('frontend.app')
@section('title', $blog->meta_title ?? $blog->seo_title ?? $blog->title)
@push('css')
<style>
  .blog-detail-page {
    padding: 36px 24px 40px;
  }
  .blog-detail-shell {
    position: relative;
    width: 100%;
    border-radius: 26px;
    padding: 28px;
    background: radial-gradient(circle at 12% 18%, rgba(255, 255, 255, 0.06), transparent 55%),
      linear-gradient(140deg, #0c1424 0%, #0b1626 52%, #0a111f 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 30px 70px rgba(6, 10, 18, 0.55);
    overflow: hidden;
  }
  .blog-detail-shell::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: var(--detail-bg);
    background-size: cover;
    background-position: center;
    opacity: 0.12;
  }
  .blog-detail-shell::after {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 10%, rgba(255, 120, 50, 0.18), transparent 45%),
      radial-gradient(circle at 10% 80%, rgba(255, 255, 255, 0.12), transparent 40%);
  }
  .blog-detail-content {
    position: relative;
    z-index: 1;
    color: #f7f1e6;
  }
  .blog-detail-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
    border-radius: 22px;
    overflow: hidden;
    background: rgba(12, 18, 30, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
  }
  .blog-detail-hero-content {
    padding: 26px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .blog-share {
    display: flex;
    gap: 12px;
    font-size: 14px;
  }
  .blog-share a {
    color: rgba(245, 235, 220, 0.8);
    text-decoration: none;
  }
  .blog-share a:hover {
    color: #ff9b60;
  }
  .blog-detail-date {
    font-size: 13px;
    color: rgba(245, 235, 220, 0.7);
  }
  .blog-detail-title {
    margin: 0;
    font-size: 30px;
    line-height: 1.2;
  }
  .blog-detail-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    font-size: 13px;
    color: rgba(245, 235, 220, 0.75);
  }
  .blog-detail-hero-media {
    background-image: var(--hero-image);
    background-size: cover;
    background-position: center;
    position: relative;
    min-height: 240px;
  }
  .blog-detail-hero-media::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(10, 16, 28, 0.9), rgba(10, 16, 28, 0.1));
  }
  .blog-detail-body {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 280px;
    gap: 20px;
    margin-top: 22px;
  }
  .blog-article {
    background: rgba(12, 18, 30, 0.75);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 24px;
  }
  .blog-article h2 {
    margin: 0 0 8px;
    font-size: 22px;
  }
  .blog-article-meta {
    font-size: 13px;
    color: rgba(245, 235, 220, 0.7);
    margin-bottom: 16px;
  }
  .blog-article-content {
    line-height: 1.7;
    color: rgba(245, 235, 220, 0.86);
  }
  .blog-article-content p,
  .blog-article-content li {
    font-size: 15px;
  }
  .blog-article-content h3 {
    font-size: 18px;
    margin: 18px 0 8px;
  }
  .blog-article-content h4 {
    font-size: 16px;
    margin: 16px 0 6px;
  }
  .blog-article-content h3,
  .blog-article-content h4 {
    color: #f7f1e6;
  }
  .blog-side {
    display: grid;
    gap: 16px;
    align-content: start;
  }
  .blog-side-card {
    background: rgba(12, 18, 30, 0.75);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 18px;
    color: rgba(245, 235, 220, 0.86);
  }
  .blog-side-kicker {
    display: inline-flex;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(245, 235, 220, 0.6);
    margin-bottom: 8px;
  }
  .blog-side-title {
    margin: 0 0 8px;
    font-size: 18px;
  }
  .blog-side-text {
    margin: 0 0 12px;
    font-size: 14px;
    color: rgba(245, 235, 220, 0.75);
  }
  .blog-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    border-radius: 999px;
    background: linear-gradient(120deg, #ff7a2c, #ff4b2b);
    color: #1b0d05;
    font-weight: 700;
    font-size: 12px;
    text-decoration: none;
    width: fit-content;
  }
  .blog-subscribe input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(10, 16, 28, 0.6);
    color: #f7f1e6;
    margin-bottom: 10px;
  }
  .blog-subscribe button {
    width: 100%;
    padding: 10px 12px;
    border-radius: 999px;
    border: none;
    background: linear-gradient(120deg, #ff7a2c, #ff4b2b);
    color: #1b0d05;
    font-weight: 700;
  }
  .blog-detail-nav {
    margin-top: 18px;
    display: flex;
    justify-content: space-between;
    font-size: 13px;
  }
  .blog-detail-nav a {
    color: rgba(245, 235, 220, 0.75);
    text-decoration: none;
  }
  .blog-detail-nav a:hover {
    color: #ff9b60;
  }
  @media (max-width: 991px) {
    .blog-detail-page {
      padding: 28px 18px 36px;
    }
    .blog-detail-hero {
      grid-template-columns: 1fr;
    }
    .blog-detail-body {
      grid-template-columns: 1fr;
    }
  }
  @media (max-width: 576px) {
    .blog-detail-page {
      padding: 22px 14px 32px;
    }
    .blog-detail-shell {
      padding: 20px;
    }
  }
</style>
@endpush
@section('seos')
    {{-- Basic --}}
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    {{-- Page title & canonical --}}
    @php
        use Illuminate\Support\Str;
        $pageTitle = $blog->meta_title ?? $blog->seo_title ?? $blog->title;
        $pageUrl   = url()->current();
        $imageUrl  = $blog->image ? asset($blog->image) : null;
        $seoDefaults = \App\Models\SeoSetting::where('page_name', 'Blog Details')->first();
        $siteName = $blog->site_name ?: (optional($seoDefaults)->site_name ?? config('app.name', 'Thomas Alexander'));
        $pageTitle = $pageTitle ?: (optional($seoDefaults)->meta_title ?? $siteName);
        $rawDesc = $blog->meta_description ?: ($blog->seo_description ?: $blog->description);
        $rawDesc = $rawDesc ?: (optional($seoDefaults)->meta_description ?: optional($seoDefaults)->seo_description);
        $pageDesc = Str::limit(strip_tags($rawDesc ?? ''), 180);
        $canonical = $blog->canonical_url ?: (optional($seoDefaults)->canonical_url ?: $pageUrl);
        $keywords = $blog->seo_keywords ?: (optional($seoDefaults)->seo_keywords ?? $blog->title);
        $authorMeta = $blog->seo_author ?: ($blog->author ?? (optional($seoDefaults)->seo_author ?? $siteName));
        $publisher = $blog->seo_publisher ?: (optional($seoDefaults)->seo_publisher ?? $siteName);
        $metaImageValue = $blog->meta_image ?: optional($seoDefaults)->meta_image;
        $metaImage = $metaImageValue
            ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
            : ($imageUrl ?: asset(siteInfo()->logo));
        $copyright = $blog->meta_copyright ?: optional($seoDefaults)->meta_copyright;
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
@php
    $heroImage = $blog->image ? asset($blog->image) : $metaImage;
    $detailBg = $metaImage ?? $heroImage;
    $publishDate = optional($blog->created_at)->format('F j, Y');
    $authorName = $blog->author ?? $authorMeta ?? 'Thomas Alexander';
    $shareUrl = urlencode($canonical ?? url()->current());
    $shareTitle = urlencode($pageTitle);
@endphp
<div class="ms_index_wrapper common_pages_space blog-detail-page">
    <div class="blog-detail-shell" style="--detail-bg: url('{{ $detailBg }}');">
        <div class="blog-detail-content">
            <div class="blog-detail-hero">
                <div class="blog-detail-hero-content">
                    <div class="blog-share">
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" target="_blank" rel="noopener">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    </div>
                    <span class="blog-detail-date">{{ $publishDate }}</span>
                    <h1 class="blog-detail-title">{{ $blog->title }}</h1>
                    <div class="blog-detail-meta">
                        <span>{{ $publishDate }}</span>
                        <span>By {{ $authorName }}</span>
                    </div>
                </div>
                <div class="blog-detail-hero-media" style="--hero-image: url('{{ $heroImage }}');"></div>
            </div>

            <div class="blog-detail-body">
                <article class="blog-article">
                    <h2>{{ $blog->title }}</h2>
                    <div class="blog-article-meta">{{ $publishDate }} - By {{ $authorName }}</div>
                    <div class="blog-article-content">
                        {!! $blog->description !!}
                    </div>
                </article>

                <aside class="blog-side">
                    <div class="blog-side-card">
                        <span class="blog-side-kicker">Donate</span>
                        <p class="blog-side-title">Support My Work</p>
                        <p class="blog-side-text">If you enjoy this content, consider supporting me with a donation.</p>
                        <a class="blog-action-btn" href="{{ route('living-archive.donate') }}">Donate</a>
                    </div>

                    <div class="blog-side-card">
                        <span class="blog-side-kicker">Subscribe</span>
                        <p class="blog-side-title">Subscribe to My Blog</p>
                        <div class="blog-subscribe">
                            <input type="text" placeholder="Name">
                            <input type="email" placeholder="Email address">
                            <button type="button">Subscribe</button>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="blog-detail-nav">
                <a href="{{ route('front.blog') }}">Back to Blog</a>
                <a href="{{ route('living-archive.donate') }}">Support the Living Archive</a>
            </div>
        </div>
    </div>
</div>
@endsection
