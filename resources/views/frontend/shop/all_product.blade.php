@extends('frontend.app')

@section('seos')
    @php
        use Illuminate\Support\Str;
        $seoSetting = \App\Models\SeoSetting::where('page_name', 'All Songs')->first();
        $siteName = optional($seoSetting)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = optional($seoSetting)->meta_title ?: (optional($seoSetting)->seo_title ?: 'All Songs');
        $rawDesc = optional($seoSetting)->meta_description
            ?: (optional($seoSetting)->seo_description ?: 'Discover and purchase the full song catalogue.');
        $pageDesc = Str::limit(strip_tags($rawDesc), 180);
        $heroTitle = optional($seoSetting)->seo_title ?: $pageTitle;
        $heroDescRaw = optional($seoSetting)->seo_description ?: $rawDesc;
        $heroDesc = Str::limit(strip_tags($heroDescRaw ?? ''), 200);
        $pageUrl = url()->current();
        $canonical = optional($seoSetting)->canonical_url ?: $pageUrl;
        $keywords = optional($seoSetting)->seo_keywords ?? 'Thomas Alexander, songs, music';
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
    .all-songs-shell {
        position: relative;
        background: radial-gradient(circle at 14% 18%, rgba(255, 255, 255, 0.06), transparent 56%),
            linear-gradient(140deg, #0b1729 0%, #0a1525 52%, #08101e 100%);
        border-radius: 28px;
        padding: 34px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 35px 80px rgba(5, 10, 18, 0.55);
        margin: 0 auto 36px;
        color: #f7f1e6;
        max-width: 1220px;
    }
    .all-songs-shell::after {
        content: "";
        position: absolute;
        inset: 12px;
        border-radius: 22px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }
    .all-songs-shell a {
        color: inherit;
        text-decoration: none;
    }
    .all-songs-hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 44px 52px;
        background-image: var(--hero-image);
        background-size: cover;
        background-position: right center;
        min-height: 300px;
        margin-bottom: 28px;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
    }
    .all-songs-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(100deg, rgba(7, 12, 20, 0.94) 0%, rgba(7, 12, 20, 0.72) 55%, rgba(7, 12, 20, 0.18) 100%);
    }
    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 540px;
        width: min(58%, 540px);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.28em;
        color: rgba(245, 235, 220, 0.7);
    }
    .hero-content h1 {
        font-size: clamp(28px, 3.2vw, 44px);
        margin: 0;
        color: #fdf7ed;
        font-weight: 700;
        line-height: 1.18;
    }
    .hero-content p {
        margin: 0;
        color: rgba(245, 235, 220, 0.82);
        font-size: 15px;
        line-height: 1.6;
    }
    .hero-cta {
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
    }
    .all-songs-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }
    .song-card {
        background: rgba(21, 30, 46, 0.94);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 18px 30px rgba(6, 10, 18, 0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .song-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 38px rgba(6, 10, 18, 0.45);
    }
    .song-card-media {
        display: block;
        background: #0b1523;
    }
    .song-card-media img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
    }
    .song-card-body {
        padding: 14px 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }
    .song-card-body h3 {
        font-size: 14px;
        margin: 0;
        color: #f7f1e6;
        line-height: 1.4;
    }
    .song-card-body h3 a {
        color: inherit;
    }
    .song-card-price {
        display: flex;
        align-items: baseline;
        gap: 8px;
        font-weight: 700;
        color: #f5f1e9;
        font-size: 13px;
    }
    .song-card-price .old_price {
        color: #ff7a7a;
        font-size: 12px;
    }
    .song-card-actions {
        margin-top: auto;
    }
    .song-card-cta {
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
    }
    .song-card-cta i {
        margin-right: 6px;
    }
    .all-songs-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 28px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.04);
        color: rgba(245, 235, 220, 0.8);
    }
    @media (max-width: 991px) {
        .all-songs-shell {
            padding: 24px;
        }
        .all-songs-hero {
            padding: 32px 36px;
        }
    }
    @media (max-width: 1200px) {
        .all-songs-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 900px) {
        .all-songs-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 768px) {
        .all-songs-hero {
            padding: 26px 22px;
            min-height: 240px;
        }
        .hero-content {
            max-width: 100%;
            width: 100%;
            text-align: left;
        }
        .song-card-media img {
            height: 170px;
        }
    }
    @media (max-width: 576px) {
        .all-songs-hero {
            padding: 22px 18px;
        }
        .hero-content h1 {
            font-size: 24px;
        }
        .all-songs-shell {
            padding: 20px;
        }
        .all-songs-grid {
            grid-template-columns: 1fr;
        }
        .song-card-media img {
            height: 150px;
        }
    }
</style>
@endpush

@section('content')
<div class="ms_index_wrapper common_pages_space">
    <div class="all-songs-shell">
        <div class="all-songs-hero" style="--hero-image: url('{{ $heroImage }}');">
            <div class="hero-content">
                <span class="hero-kicker">All Songs</span>
                <h1>{{ $heroTitle }}</h1>
                <p>{{ $heroDesc }}</p>
                <a class="hero-cta" href="#all-songs-grid">Browse All Products</a>
            </div>
        </div>

        <div class="all-songs-grid" id="all-songs-grid">
            @forelse($products as $product)
                <div class="song-card">
                    <a class="song-card-media" href="{{ route('front.product.show', $product->slug ) }}">
                        <img src="{{ asset('uploads/custom-images2/'.$product->thumb_image) }}" alt="{{ $product->name }}">
                    </a>
                    <div class="song-card-body">
                        <h3>
                            <a href="{{ route('front.product.show', $product->slug ) }}">
                                {{ \Illuminate\Support\Str::limit($product->name, 30) }}
                            </a>
                        </h3>
                        <div class="song-card-price">
                            @if(empty($product->offer_price))
                                <span class="current_price">${{ $product->price }}</span>
                            @else
                                <span class="current_price">${{ $product->offer_price }}</span>
                                <span class="old_price"><del>${{ $product->price }}</del></span>
                            @endif
                        </div>
                        <div class="song-card-actions">
                            @if($product->type == 'variable' || $product->prod_color == 'varcolor')
                                <a href="{{ route('front.product.show', $product->slug ) }}" class="song-card-cta">
                                    {{ BanglaText('order') }}
                                </a>
                            @else
                                <a href="{{ route('front.product.show', $product->slug ) }}"
                                   class="song-card-cta buy-now"
                                   data-url="{{ route('front.cart.store') }}">
                                    <i class="fas fa-shopping-cart"></i> {{ BanglaText('order') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="all-songs-empty">
                    <strong>No products are available</strong>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function () {
    $('.buy-now').on('click', function (e) {
        e.preventDefault();

        var productId = $(this).attr('href').split('/').pop();
        var proQty = 1;
        var addToCartUrl = $(this).data('url');
        var checkoutUrl = "{{ route('front.cart.index') }}";
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        $.post(addToCartUrl, { id: productId, quantity: proQty }, function () {
            window.location.href = "{{ route('front.checkout.index') }}";
        });
    });
});
</script>
@endpush
