@extends('frontend.app')
@push('css')
<style>
  .blog-page {
    padding: 36px 24px 40px;
  }
  .blog-shell {
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
  .blog-shell::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: var(--blog-bg);
    background-size: cover;
    background-position: center;
    opacity: 0.12;
  }
  .blog-shell::after {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 65% 0%, rgba(255, 120, 50, 0.18), transparent 45%),
      radial-gradient(circle at 10% 80%, rgba(255, 255, 255, 0.12), transparent 40%);
  }
  .blog-content {
    position: relative;
    z-index: 1;
    color: #f7f1e6;
  }
  .blog-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    flex-wrap: wrap;
  }
  .blog-title {
    font-size: 30px;
    margin: 0 0 4px;
  }
  .blog-subtitle {
    margin: 0;
    color: rgba(245, 235, 220, 0.8);
    font-size: 14px;
  }
  .blog-sort {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: rgba(245, 235, 220, 0.7);
    font-size: 12px;
  }
  .blog-sort select {
    background: rgba(12, 18, 30, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #f7f1e6;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
  }
  .blog-feature {
    margin-top: 24px;
    border-radius: 22px;
    overflow: hidden;
    background: rgba(12, 18, 30, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
    min-height: 280px;
  }
  .blog-feature-content {
    padding: 26px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .blog-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 999px;
    background: rgba(255, 120, 50, 0.15);
    border: 1px solid rgba(255, 120, 50, 0.5);
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    width: fit-content;
  }
  .blog-feature-title {
    font-size: 28px;
    margin: 0;
  }
  .blog-feature-excerpt {
    margin: 0;
    color: rgba(245, 235, 220, 0.8);
    font-size: 14px;
    line-height: 1.6;
  }
  .blog-feature-meta {
    font-size: 12px;
    color: rgba(245, 235, 220, 0.7);
  }
  .blog-feature-cta {
    margin-top: 6px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 999px;
    background: linear-gradient(120deg, #ff7a2c, #ff4b2b);
    color: #1b0d05;
    font-weight: 700;
    font-size: 12px;
    text-decoration: none;
    width: fit-content;
  }
  .blog-feature-media {
    background-image: var(--feature-image);
    background-size: cover;
    background-position: center;
    position: relative;
  }
  .blog-feature-media::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(10, 16, 28, 0.85), rgba(10, 16, 28, 0.1));
  }
  .blog-section-title {
    margin: 26px 0 12px;
    font-size: 20px;
  }
  .blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 18px;
  }
  .blog-card {
    background: rgba(12, 18, 30, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 16px 28px rgba(6, 10, 18, 0.4);
  }
  .blog-card-media {
    position: relative;
    background-image: var(--card-image);
    background-size: cover;
    background-position: center;
    padding-top: 60%;
  }
  .blog-tag {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 4px 10px;
    border-radius: 999px;
    background: rgba(12, 18, 30, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 11px;
    color: #f7f1e6;
  }
  .blog-card-body {
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
  }
  .blog-card-title {
    margin: 0;
    font-size: 15px;
  }
  .blog-card-excerpt {
    margin: 0;
    font-size: 13px;
    color: rgba(245, 235, 220, 0.75);
    line-height: 1.5;
  }
  .blog-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    font-size: 12px;
    color: rgba(245, 235, 220, 0.7);
  }
  .blog-card-link {
    color: #ff9b60;
    text-decoration: none;
    font-weight: 600;
  }
  .blog-empty {
    padding: 24px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.04);
    text-align: center;
    color: rgba(245, 235, 220, 0.75);
  }
  @media (max-width: 991px) {
    .blog-page {
      padding: 28px 18px 36px;
    }
    .blog-feature {
      grid-template-columns: 1fr;
    }
  }
  @media (max-width: 576px) {
    .blog-page {
      padding: 22px 14px 32px;
    }
    .blog-shell {
      padding: 20px;
    }
  }
</style>
@endpush
@section('seos')
    @php
        $seoSetting = \App\Models\SeoSetting::where('page_name', 'Blog')->first();
        $pageTitle = optional($seoSetting)->seo_title ?? 'Thomas Alexander | Blog';
        $pageDesc = optional($seoSetting)->seo_description ?? 'Stories, ceremonies, and updates from the Living Archive.';
        $pageUrl = url()->current();
        $canonical = optional($seoSetting)->canonical_url ?: $pageUrl;
        $keywords = optional($seoSetting)->seo_keywords ?? 'Thomas Alexander blog, Living Archive stories';
        $siteName = optional($seoSetting)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = optional($seoSetting)->meta_title ?: $pageTitle;
        $rawDesc = optional($seoSetting)->meta_description ?: $pageDesc;
        $pageDesc = \Illuminate\Support\Str::limit(strip_tags($rawDesc), 180);
        $author = optional($seoSetting)->seo_author ?? $siteName;
        $publisher = optional($seoSetting)->seo_publisher ?? $siteName;
        $metaImageValue = optional($seoSetting)->meta_image;
        $metaImage = $metaImageValue
            ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
            : asset(siteInfo()->logo);
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
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
@endsection
@section('content')
@php
    $featured = $blogs->first();
    $otherBlogs = $blogs->slice(1);
    $blogBg = isset($metaImage) ? $metaImage : asset(siteInfo()->logo);
@endphp
<div class="ms_index_wrapper common_pages_space blog-page">
    <div class="blog-shell" style="--blog-bg: url('{{ $blogBg }}');">
        <div class="blog-content">
            <div class="blog-header">
                <div>
                    <h1 class="blog-title">Blog</h1>
                    <p class="blog-subtitle">Latest posts, affirmations, and updates</p>
                </div>
                <div class="blog-sort">
                    <span>Sort by:</span>
                    <select>
                        <option selected>Newest</option>
                    </select>
                </div>
            </div>

            @if($featured)
                @php
                    $featuredImage = $featured->image ? asset($featured->image) : $blogBg;
                @endphp
                <article class="blog-feature">
                    <div class="blog-feature-content">
                        <span class="blog-badge">Featured</span>
                        <h2 class="blog-feature-title">{{ $featured->title }}</h2>
                        <p class="blog-feature-excerpt">
                            {{ \Illuminate\Support\Str::limit(strip_tags($featured->description), 150) }}
                        </p>
                        <a class="blog-feature-cta" href="{{ route('front.blog_details', [$featured->slug]) }}">
                            Read Article
                        </a>
                        <span class="blog-feature-meta">{{ date('F j, Y', strtotime($featured->created_at)) }}</span>
                    </div>
                    <div class="blog-feature-media" style="--feature-image: url('{{ $featuredImage }}');"></div>
                </article>
            @endif

            <h2 class="blog-section-title">Blog</h2>
            <div class="blog-grid">
                @forelse($otherBlogs as $blog)
                    @php
                        $cardImage = $blog->image ? asset($blog->image) : $blogBg;
                        $tag = optional($blog->category)->name ?? 'Blog';
                    @endphp
                    <article class="blog-card">
                        <a class="blog-card-media" href="{{ route('front.blog_details', [$blog->slug]) }}" style="--card-image: url('{{ $cardImage }}');">
                            <span class="blog-tag">{{ $tag }}</span>
                        </a>
                        <div class="blog-card-body">
                            <h3 class="blog-card-title">{{ \Illuminate\Support\Str::limit($blog->title, 60) }}</h3>
                            <p class="blog-card-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($blog->description), 90) }}</p>
                            <div class="blog-card-footer">
                                <span>{{ date('F j, Y', strtotime($blog->created_at)) }}</span>
                                <a class="blog-card-link" href="{{ route('front.blog_details', [$blog->slug]) }}">Read More</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="blog-empty">
                        No blog posts are available yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
