@extends('frontend.layouts.living-archive')

@section('seos')
    @php
        $seoSettings = $seoSettings
            ?? \App\Models\SeoSetting::where('page_name', 'Living Archive')->first()
            ?? DB::table('seo_settings')->where('id', 1)->first();

        $siteName = $seoSettings->site_name ?? config('app.name', 'Living Archive');
        $title = $seoSettings?->meta_title ?: ($seoSettings?->seo_title ?: $siteName);
        $rawDesc = $seoSettings?->meta_description ?: ($seoSettings?->seo_description ?: data_get($page ?? [], 'intro'));
        $desc = \Illuminate\Support\Str::limit(strip_tags($rawDesc ?? ''), 180);
        $url = url()->current();
        $fallbackLogo = siteInfo()->logo ?? null;
        $defaultImage = $fallbackLogo ? asset($fallbackLogo) : asset('images/og-default.jpg');
        $ogImage = $seoSettings?->meta_image ? asset($seoSettings->meta_image) : $defaultImage;
        $updatedIso = optional($seoSettings?->updated_at)->toIso8601String() ?? now()->toIso8601String();
        $twitter = $seoSettings->twitter_site ?? '@livingarchive';
        $indexable = isset($seoSettings->indexable) ? (bool) $seoSettings->indexable : true;
        $author = $seoSettings->author ?? $siteName;
        $publisher = $seoSettings->publisher ?? $siteName;
        $copyright = $seoSettings->copyright ?? null;
        $keywords = $seoSettings->keywords ?? ($seoSettings->seo_keywords ?? null);
    @endphp
    @section('title', $title)
    <meta name="title" content="{{ $title }}">
    <meta name="description" content="{{ $desc }}">
    <meta name="author" content="{{ $author }}">
    @if ($publisher)
        <meta name="publisher" content="{{ $publisher }}">
    @endif
    @if ($copyright)
        <meta name="copyright" content="{{ $copyright }}">
    @endif
    @if ($keywords)
        <meta name="keywords" content="{{ $keywords }}">
    @endif
    <link rel="canonical" href="{{ $url }}">
    <meta name="robots" content="{{ $indexable ? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' : 'noindex, nofollow' }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $desc }}">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="{{ $siteName }}">
    <meta property="og:updated_time" content="{{ $updatedIso }}">
    <meta property="og:locale" content="en_US">
    @if ($publisher)
        <meta property="article:publisher" content="{{ $publisher }}">
    @endif
    @if ($author)
        <meta property="article:author" content="{{ $author }}">
    @endif
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="{{ $twitter }}">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $desc }}">
    <meta name="twitter:url" content="{{ $url }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('css')
<style>
    body {
        background: #f6f6f6;
        overflow-x: hidden;
    }
    .as-mainwrapper.wrapper-boxed {
        max-width: 100%;
        width: 100%;
    }
    .living-crest-wrap {
        position: relative;
        padding: 40px 0 10px;
        overflow: hidden;
    }
    .living-crest-card {
        background: #0f0f0f;
        color: #ffffff;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 18px 40px rgba(0,0,0,0.25);
        position: relative;
        z-index: 2;
    }
    .living-crest-card img {
        max-width: 220px;
        width: 100%;
        filter: drop-shadow(0 8px 20px rgba(0,0,0,0.35));
    }
    .living-crest-card h3,
    .living-crest-card p,
    .living-crest-card strong,
    .living-crest-card .small {
        color: #ffffff;
    }
    .living-crest-card .crest-accent {
        color: #f0b428;
    }
    .living-crest-card .crest-secondary {
        max-width: 140px;
    }
    .living-crest-card .crest-copy {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .living-crest-card h3 {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 16px;
        margin-top: 12px;
    }
    .living-crest-card p {
        margin-bottom: 8px;
        line-height: 1.6;
    }
    .living-crest-watermark {
        position: absolute;
        inset: 0;
        background: url('{{ asset('frontend/images/svg/living-archive-crest.svg') }}') center / 380px no-repeat;
        opacity: 0.06;
        z-index: 1;
    }
    .living-header .as-topstrip {
        background: #f0b428;
        color: #1c1c1c;
        padding: 8px 0;
    }
    .living-header .as-donate-btn {
        background: #1c1c1c;
        border-radius: 50px;
        color: #fff;
        padding: 8px 16px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .living-header .as-header-bar {
        padding: 18px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }
    .living-header nav.main-navigation > ul > li > a {
        text-transform: uppercase;
        font-weight: 700;
    }
    .living-banner {
        position: relative;
    }
    .living-banner::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, rgba(0,0,0,0.55), rgba(0,0,0,0.35));
        pointer-events: none;
    }
    .living-banner .slides li { position: relative; }
    .living-banner .as-caption { color: #ffffff; }
    .living-banner .as-caption h1 span {
        background: rgba(240, 180, 40, 0.95);
        padding: 6px 14px;
        display: inline-block;
        color: #1c1c1c;
        letter-spacing: 0.08em;
    }
    .living-banner .as-captiontitle span {
        background: rgba(0, 0, 0, 0.75);
        display: inline-block;
        padding: 6px 10px;
        color: #fff;
        margin-top: 6px;
    }
    .living-banner .as-caption .cta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        margin-top: 18px;
    }
    .living-banner .as-caption a.as-donate-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 999px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: linear-gradient(120deg, #f0b428, #c9871f);
        color: #1c1c1c;
        border: none;
        box-shadow: 0 12px 24px rgba(0,0,0,0.22);
        transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
    }
    .living-banner .as-caption a.as-donate-btn:last-of-type {
        background: rgba(255,255,255,0.94) !important;
        color: #1c1c1c !important;
        box-shadow: 0 10px 18px rgba(0,0,0,0.18);
    }
    .living-banner .as-caption a.as-donate-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 26px rgba(0,0,0,0.28);
    }
    .as-main-section.living-intro .as-title-text {
        max-width: 900px;
        margin: 0 auto;
        font-size: 16px;
        line-height: 1.8;
        color: #111 !important;
        text-align: center;
        padding: 0 12px;
        display: block;
    }
    .as-main-section.living-intro p {
        color: #111 !important;
    }
    #living-phases p {
        color: #111 !important;
    }
    .living-tabs {
        border: none;
        margin-bottom: 20px;
    }
    .living-tabs > li > a {
        background: #f0f0f0;
        border-radius: 30px;
        margin: 4px 6px;
        color: #1c1c1c;
        font-weight: 700;
        letter-spacing: 0.04em;
        border: 1px solid #e3e3e3;
    }
    .living-tabs > li.active > a,
    .living-tabs > li > a:focus,
    .living-tabs > li > a:hover {
        background: #f0b428;
        color: #1c1c1c;
        border-color: #f0b428;
    }
    .living-phase-lede {
        background: #111;
        color: #f8f8f8;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .living-phase-lede strong {
        letter-spacing: 0.08em;
    }
    .living-phase-lede .living-affirmation {
        margin: 0;
        font-style: italic;
        color: #f0b428;
    }
    .as-causes-grid .living-card h3 a {
        color: #222;
        font-weight: 700;
    }
    .as-causes-grid .living-card p {
        color: #666;
    }
    .living-price-row {
        margin: 8px 0;
        font-weight: 700;
    }
    .living-price-current {
        color: #f0b428;
        margin-right: 8px;
    }
    .living-price-compare {
        color: #999;
        text-decoration: line-through;
    }
    .living-empty {
        background: #fff8e6;
        border: 1px dashed #f0b428;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        color: #1c1c1c;
    }
    .living-ritual h4 {
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .living-ritual ul {
        padding-left: 18px;
    }
    .living-summary {
        padding: 20px 0 10px;
    }
    .handoff-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        padding: 18px;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .handoff-card h4 {
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 14px;
        margin-bottom: 10px;
        color: #1c1c1c;
    }
    .handoff-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .handoff-list li {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 8px;
        color: #111;
    }
    .handoff-list strong {
        display: inline-block;
        min-width: 110px;
        color: #1c1c1c;
    }
    .living-tagline-chip {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 30px;
        border: 1px solid #f0b428;
        color: #1c1c1c;
        background: #fff8e6;
        margin: 4px 4px 0 0;
        font-weight: 700;
        letter-spacing: 0.03em;
    }
    .coming-soon-banner {
        background: linear-gradient(120deg, #10344d, #0b0f17);
        color: #f4efe3;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid rgba(240,180,40,0.4);
        box-shadow: 0 12px 26px rgba(0,0,0,0.24);
        margin-top: 12px;
        letter-spacing: 0.04em;
    }
    .living-text-cards .living-brief {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 10px 24px rgba(0,0,0,0.1);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        text-align: center;
    }
    .living-text-cards h5 {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 13px;
        margin-bottom: 8px;
        color: #1c1c1c;
    }
    .living-text-cards p {
        margin-bottom: 0;
        color: #333;
        line-height: 1.55;
        flex: 1;
    }
    .living-text-cards .row {
        justify-content: center;
    }
    .living-header .logo img {
        max-height: 75px;
        width: auto;
    }
    .living-crest-card .col-md-3.text-center img {
        max-width: 200px;
    }
    .merch-flow-card {
        background: #0f0f0f;
        color: #f6f6f6;
        border-radius: 12px;
        padding: 16px;
        height: 100%;
        box-shadow: 0 12px 24px rgba(0,0,0,0.25);
    }
    .merch-row {
        border-bottom: 1px solid rgba(255,255,255,0.08);
        padding: 8px 0;
        font-size: 14px;
    }
    .merch-row:last-child {
        border-bottom: none;
    }
    .palette-swatch {
        background: #fff;
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #eaeaea;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.02);
        margin-bottom: 10px;
        font-weight: 700;
        color: #1c1c1c;
    }
    .background-guide {
        background: #f7f2e8;
        border: 1px dashed #c9871f;
        border-radius: 12px;
        padding: 14px;
        color: #2a1f10;
    }
    .living-footer-line {
        text-align: center;
        padding: 18px 0;
        color: #5c4d35;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
    }
    .as-causes-grid .living-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .as-causes-grid .living-card .as-causes-info {
        flex: 1;
    }
    @media (max-width: 991px) {
        .as-mainwrapper.wrapper-boxed {
            padding: 0;
        }
        .living-header .as-topstrip {
            text-align: center;
        }
        .living-header .as-topstrip .as-section-right {
            justify-content: center;
            margin-top: 8px;
        }
        .living-header .logo {
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }
        .living-header .as-header-bar .as-section-right nav.main-navigation > ul {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }
        .living-banner .as-caption h1 span {
            font-size: 18px;
            line-height: 1.4;
        }
        .living-banner .as-caption .as-captiontitle span {
            display: block;
            margin-top: 6px;
        }
        .living-banner .as-caption .cta-row {
            justify-content: center;
        }
        .living-crest-card {
            margin: 0 8px;
        }
    }
    @media (max-width: 768px) {
        .living-crest-card {
            padding: 20px;
        }
        .living-crest-card .row > div {
            margin-bottom: 12px;
        }
        .living-crest-card img {
            max-width: 180px;
        }
        .living-crest-card .crest-secondary {
            max-width: 120px;
        }
        .living-crest-card .row {
            text-align: center;
        }
        .living-crest-card .col-md-6 {
            text-align: left;
        }
        .living-crest-card .col-md-6.crest-copy {
            text-align: left;
        }
        .living-crest-card .col-md-6,
        .living-crest-card .col-md-3 {
            width: 100%;
        }
        .living-summary .row > div,
        .living-intro .row > div,
        .living-visuals .row > div {
            width: 100%;
        }
        .living-banner .as-caption {
            padding: 20px 14px;
        }
        .living-banner .as-caption h1 span {
            font-size: 16px;
            padding: 6px 10px;
        }
        .living-banner .as-caption .cta-row {
            flex-direction: column;
            gap: 10px;
        }
        .living-banner .as-caption a.as-donate-btn {
            width: 100%;
            justify-content: center;
        }
        .as-main-section.living-intro .as-title-text {
            font-size: 15px;
            line-height: 1.6;
        }
        .living-text-cards .living-brief {
            text-align: left;
        }
        .living-phase-lede {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
        .living-tabs {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 6px;
        }
        .living-tabs > li {
            flex: 0 0 auto;
        }
        .living-tabs > li > a {
            width: 100%;
            text-align: center;
        }
        .as-causes-grid ul.row {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .as-causes-grid ul.row li {
            width: 100% !important;
            padding-left: 0;
            padding-right: 0;
        }
        .as-causes-grid figure img {
            width: 100%;
            height: auto;
        }
        .living-summary .row > div,
        .living-visuals .row > div,
        .living-intro .row > div {
            padding-left: 0;
            padding-right: 0;
        }
        .as-main-section {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
    }
    @media (max-width: 576px) {
        .living-banner .as-caption h1 span {
            font-size: 15px;
        }
        .living-banner .as-caption .as-captiontitle span {
            font-size: 13px;
        }
        .living-crest-card img {
            max-width: 160px;
        }
        .living-summary .handoff-card,
        .living-summary .merch-flow-card,
        .living-summary .palette-swatch,
        .living-summary .background-guide {
            margin-left: 0;
            margin-right: 0;
        }
        .living-crest-wrap {
            padding-left: 0;
            padding-right: 0;
        }
        .living-tabs::-webkit-scrollbar {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="as-mainwrapper">
    {{-- Style Switcher --}}
    <div class="ec-colorswitcher">
        <a class="ec-handle" href="#">
            <i class="fa fa-gear"></i>
        </a>
        <h3>Style Switcher</h3>
        <div class="ec-switcherarea">
            <h6>Select Layout</h6>
            <div class="layout-btn">
                <a href="#" class="ec-boxed"><span>Boxed</span></a>
                <a href="#" class="ec-wide"><span>Wide</span></a>
            </div>
            <h6>Chose Color</h6>
            <ul class="ec-switcher">
                <li><a href="#" data-rel="color-black" class="styleswitch" style=" background: #000; "></a></li>
                <li><a href="#" data-rel="color-two" class="styleswitch" style="background: #41c3ac;"></a></li>
                <li><a href="#" data-rel="color-three" class="styleswitch" style="background: #AF4D32;"></a></li>
                <li><a href="#" data-rel="color-four" class="styleswitch" style="background: #1abc9c;"></a></li>
                <li><a href="#" data-rel="color-five" class="styleswitch" style="background: #3498db;"></a></li>
                <li><a href="#" data-rel="color-six" class="styleswitch" style="background: #9b59b6;"></a></li>
                <li><a href="#" data-rel="color-seven" class="styleswitch" style="background: #34495e;"></a></li>
                <li><a href="#" data-rel="color-eight" class="styleswitch" style="background: #e67e22;"></a></li>
                <li><a href="#" data-rel="color-nine" class="styleswitch" style="background: #c0392b;"></a></li>
                <li><a href="#" data-rel="color-ten" class="styleswitch" style="background: #336E7B;"></a></li>
            </ul>

            <div class="ec-pattren">
                <h6>Chose Pattren</h6>
                <div class="pattren-wrap">
                    <a href="#" data-rel="pattren-black" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-pattren/pattren1.jpg') }}" alt=""></a>
                    <a href="#" data-rel="pattren1" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-pattren/pattren1.jpg') }}" alt=""></a>
                    <a href="#" data-rel="pattren2" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-pattren/pattren2.jpg') }}" alt=""></a>
                    <a href="#" data-rel="pattren3" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-pattren/pattren3.jpg') }}" alt=""></a>
                    <a href="#" data-rel="pattren4" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-pattren/pattren4.jpg') }}" alt=""></a>
                    <a href="#" data-rel="pattren5" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-pattren/pattren5.jpg') }}" alt=""></a>
                </div>
            </div>
            <div class="ec-background">
                <h6>Chose Background</h6>
                <div class="background-wrap">
                    <a href="#" data-rel="background1" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-background/bg-1.jpg') }}" alt=""></a>
                    <a href="#" data-rel="background2" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-background/bg-2.jpg') }}" alt=""></a>
                    <a href="#" data-rel="background3" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-background/bg-3.jpg') }}" alt=""></a>
                    <a href="#" data-rel="background4" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-background/bg-4.jpg') }}" alt=""></a>
                    <a href="#" data-rel="background5" class="styleswitch"><img src="{{ asset('frontend/living-archive/images/ec-background/bg-5.jpg') }}" alt=""></a>
                </div>
            </div>

        </div>
    </div>
    {{-- End Style Switcher --}}
    @php
        $handoff = $handoff ?? [];
        $contact = data_get($page, 'contact', []);
        $social = data_get($handoff, 'social', []);
        $crest = data_get($page, 'crest', []);
        $primaryCrestImage = $crest['primary_image'] ?? asset('frontend/living-archive/Dreamcatcher-style crest.jpeg');
        $secondaryCrestImage = $crest['secondary_image'] ?? asset('frontend/living-archive/crest represents the Five Civilized Tribes.jpeg');
        $heroImage = data_get($page, 'media.hero', asset('frontend/living-archive/banner3.jpg'));
        $socialLinks = [
            ['icon' => 'instagram', 'url' => data_get($social, 'instagram'), 'label' => 'Instagram'],
            ['icon' => 'facebook', 'url' => data_get($social, 'facebook'), 'label' => 'Facebook'],
            ['icon' => 'youtube', 'url' => data_get($social, 'youtube'), 'label' => 'YouTube'],
        ];
    @endphp
    <header id="as-header" class="as-absolute living-header">
        <div class="container">
            <div class="as-topstrip as-bgcolor">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="as-stripinfo">
                            @if(!empty($contact['phone']))
                                <li><i class="fa fa-phone"></i> {{ $contact['phone'] }}</li>
                            @endif
                            @if(!empty($contact['email']))
                                <li><i class="fa fa-envelope-o"></i> {{ $contact['email'] }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="as-section-right">
                            <ul class="as-social-media">
                                @foreach($socialLinks as $link)
                                    @php $href = $link['url'] ?: '#'; @endphp
                                    <li>
                                        <a href="{{ $href }}"
                                           class="fa fa-{{ $link['icon'] }}"
                                           @if(!empty($link['url'])) target="_blank" rel="noopener" @endif
                                           title="{{ $link['label'] }}{{ empty($link['url']) ? ' (pending)' : '' }}"></a>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('living-archive.donate') }}" class="as-donate-btn"><i class="fa fa-dollar"></i> Donate Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="as-header-bar">
                <div class="row">
                    <div class="col-md-3"><a href="#" class="logo"><img src="{{ data_get($page, 'media.logo', asset('frontend/living-archive/images/logo.png')) }}" alt="Living Archive"></a></div>
                    <div class="col-md-9">
                        <div class="as-section-right">
                            <nav class="main-navigation">
                                <ul>
                                    <li><a href="#living-intro">Home</a></li>
                                    <li><a href="#living-summary">Summary</a></li>
                                    <li><a href="#living-phases">Phases</a></li>
                                    <li><a href="#living-ritual">Ritual</a></li>
                                    <li><a href="{{ route('living-archive.donate') }}">Donate</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="as-mainbanner living-banner">
        <div class="flexslider">
            <ul class="slides">
                <li>
                    <img src="{{ $heroImage }}" alt="Living Archive">
                    <div class="as-caption living-caption">
                        <div class="container">
                            <h1><span>{{ data_get($page, 'header.title', 'Welcome to the Living Archive') }}</span></h1>
                            <div class="clearfix"></div>
                            <div class="as-captiontitle">
                                <span>{{ data_get($page, 'header.subtitle') }}</span>
                                @if(data_get($page, 'header.qr_intro'))
                                    <span>{{ data_get($page, 'header.qr_intro') }}</span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                            <div class="cta-row">
                                <a href="{{ route('living-archive.donate') }}" class="as-donate-btn">Donate Now</a>
                                <a href="#living-phases" class="as-donate-btn">Enter Phases</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="as-main-content">
        <div class="container living-crest-wrap" id="living-crest">
            <div class="living-crest-watermark"></div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="living-crest-card">
                            <div class="row align-items-center g-4">
                                <div class="col-md-3 text-center">
                                    <img src="{{ $primaryCrestImage }}" alt="Ceremonial Crest">
                                </div>
                                <div class="col-md-6 crest-copy">
                                    <h3 class="mb-2">{{ data_get($crest, "title", "Ceremonial Crest") }}</h3>
                                    @if(!empty($crest["body_one"]))
                                        <p class="mb-2">{!! nl2br(e($crest["body_one"])) !!}</p>
                                    @endif
                                @if(!empty($crest["body_two"]))
                                    <p class="mb-2">{!! nl2br(e($crest["body_two"])) !!}</p>
                                @endif
                                @if(!empty($crest["body_three"]))
                                    <p class="mb-2">{!! nl2br(e($crest["body_three"])) !!}</p>
                                @endif
                                @if(!empty($crest["mission"]))
                                    <p class="mb-0">{!! nl2br(e($crest["mission"])) !!}</p>
                                @endif
                            </div>
                            <div class="col-md-3 text-center">
                                <img class="crest-secondary" src="{{ $secondaryCrestImage }}" alt="Five Feather Lineage Crest">
                                <p class="small mb-0 mt-2">{{ data_get($crest, "secondary_caption", "Secondary crest: Five Feather Lineage") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="as-main-section living-summary" id="living-summary">
            <div class="container">
                <div class="row">
                    <div class="col-md-6" style="margin-bottom:20px;">
                        <div class="handoff-card">
                            <h4>Page Setup</h4>
                            <div class="row" style="margin-bottom:10px;">
                                <div class="col-sm-4 text-center">
                                    <img src="{{ data_get($handoff, 'logo_url', data_get($page, 'media.logo')) }}" alt="Living Crest" style="max-width:120px;width:100%;margin:0 auto;filter:drop-shadow(0 8px 16px rgba(0,0,0,0.18));">
                                </div>
                                <div class="col-sm-8">
                                    <p class="mb-0" style="font-weight:700;letter-spacing:0.06em;">{{ data_get($handoff, 'page_name', 'The Yamassee Rising - Living Archive') }}</p>
                                    <p class="mb-0" style="color:#555;">Living Archive Team Handoff Summary</p>
                                </div>
                            </div>
                            <ul class="handoff-list">
                                <li><strong>Page name</strong> {{ data_get($handoff, 'page_name') }}</li>
                                <li><strong>Email</strong> <a href="mailto:{{ data_get($handoff, 'email') }}">{{ data_get($handoff, 'email') }}</a></li>
                                <li><strong>Phone</strong> {{ data_get($handoff, 'phone') }}</li>
                                <li><strong>Social links</strong>
                                    @foreach($socialLinks as $link)
                                        <a href="{{ $link['url'] ?: '#' }}" class="fa fa-{{ $link['icon'] }}" style="margin-right:8px;" @if(!empty($link['url'])) target="_blank" rel="noopener" @endif title="{{ $link['label'] }}{{ empty($link['url']) ? ' (pending)' : '' }}"></a>
                                    @endforeach
                                </li>
                                <li><strong>Address</strong> {{ data_get($handoff, 'address') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom:20px;">
                        <div class="handoff-card">
                            <h4>Taglines & Coming Soon</h4>
                            <div>
                                @forelse(data_get($handoff, 'taglines', []) as $line)
                                    <span class="living-tagline-chip">{{ $line }}</span>
                                @empty
                                    <span class="living-tagline-chip">Carrying the Breath-line, Restoring the Living Memory.</span>
                                @endforelse
                            </div>
                            <div class="coming-soon-banner">
                                {{ data_get($handoff, 'coming_soon', 'The Yamassee Rising Suite - audio recording in progress. Soon, the Breath-line will be heard in a full ceremony.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="as-main-section living-intro" id="living-intro" style="padding:50px 0 30px 0;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="as-fancytitle">
                            <h2>Ceremonial Welcome</h2>
                        </div>
                        <div class="as-fancy-divider-wrap">
                            <div class="as-fancy-divider"> <span class="as-first-dote"></span> <span class="as-sec-dote as-bgcolor"></span> <span class="as-third-dote"></span> </div>
                        </div>
                        <div class="as-title-text">
                           <p style="text-align: center">{!! nl2br(e(data_get($page, 'intro'))) !!}</p>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success mt-3">{{ session('success') }}</div>
                        @endif
                        <div class="row living-text-cards" style="margin-top:20px;">
                            <div class="col-md-3 col-sm-6" style="margin-bottom:15px;">
                                <div class="living-brief">
                                    <h5>Intro Paragraph</h5>
                                    <p>{!! nl2br(e(data_get($handoff, 'intro', data_get($page, 'intro')))) !!}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6" style="margin-bottom:15px;">
                                <div class="living-brief">
                                    <h5>Mission Statement</h5>
                                    <p>{!! nl2br(e(data_get($handoff, 'mission'))) !!}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6" style="margin-bottom:15px;">
                                <div class="living-brief">
                                    <h5>Supporter Acknowledgment</h5>
                                    <p>{!! nl2br(e(data_get($handoff, 'supporter'))) !!}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6" style="margin-bottom:15px;">
                                <div class="living-brief">
                                    <h5>Coming Soon</h5>
                                    <p>{{ data_get($handoff, 'coming_soon') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="as-main-section living-summary" id="living-visuals" style="padding:20px 0 30px 0;">
            <div class="container">
                <div class="row">
                    <div class="col-md-6" style="margin-bottom:20px;">
                        <div class="merch-flow-card">
                            <h4 style="color:#f0b428;">Merch Catalogue Flow</h4>
                            <div class="merch-row"><strong>Apparel:</strong> "{{ data_get($handoff, 'merch.apparel') }}"</div>
                            <div class="merch-row"><strong>Posters & Cards:</strong> "{{ data_get($handoff, 'merch.posters') }}"</div>
                            <div class="merch-row"><strong>Music Scores & Charts:</strong> "{{ data_get($handoff, 'merch.music') }}"</div>
                            <div class="merch-row"><strong>Donor Items:</strong> "{{ data_get($handoff, 'merch.donor') }}"</div>
                            <div class="merch-row"><strong>Digital Products:</strong> "{{ data_get($handoff, 'merch.digital') }}"</div>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom:20px;">
                        <div class="handoff-card">
                            <h4>Visual Direction</h4>
                            <p style="color:#333;">{{ data_get($handoff, 'visual_hierarchy') }}</p>
                            <div class="palette-swatch" style="background: linear-gradient(120deg, #c9871f, #f4efe3);">Primary: {{ data_get($handoff, 'palette.primary') }}</div>
                            <div class="palette-swatch" style="background: linear-gradient(120deg, #b78a2d, #1f4b2c); color: #fff;">Secondary: {{ data_get($handoff, 'palette.secondary') }}</div>
                            <div class="palette-swatch" style="background: linear-gradient(120deg, #bcc6cf, #10344d); color: #0b0b0a;">Accent: {{ data_get($handoff, 'palette.accent') }}</div>
                            <div class="background-guide">
                                <strong>Background Pairing Guide</strong>
                                <div style="margin-top:6px;">{{ data_get($handoff, 'background_guide') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="as-main-section" id="living-phases" style="padding:40px 0 20px 0;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="as-fancytitle">
                            <h2>Living Archive Phases</h2>
                        </div>
                        <div class="as-fancy-divider-wrap">
                            <div class="as-fancy-divider"> <span class="as-first-dote"></span> <span class="as-sec-dote as-bgcolor"></span> <span class="as-third-dote"></span> </div>
                        </div>
                        <p>{{ data_get($page, 'phases_intro', 'Move through each phase to reveal artefacts, apparel, and recordings inscribed with their own affirmation.') }}</p>
                    </div>
                    <div class="col-md-12">
                        <ul class="nav nav-tabs living-tabs" role="tablist">
                            @foreach($phases as $phase)
                                <li class="{{ $loop->first ? 'active' : '' }}" role="presentation">
                                    <a href="#phase-{{ $loop->index }}" aria-controls="phase-{{ $loop->index }}" role="tab" data-toggle="tab">{{ $phase['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($phases as $phase)
                                <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}" id="phase-{{ $loop->index }}">
                                    <div class="living-phase-lede">
                                        <div>
                                            <strong>Phase {{ $phase['phase_index'] ?? ($loop->index + 1) }}</strong>
                                            <div>{{ $phase['title'] }}</div>
                                        </div>
                                        @if(!empty($phase['affirmation']))
                                            <p class="living-affirmation">&ldquo;{{ $phase['affirmation'] }}&rdquo;</p>
                                        @endif
                                    </div>
                                    <div class="as-causes as-causes-grid">
                                        <ul class="row">
                                            @forelse($phase['products'] as $product)
                                                @php
                                                    $productUrl = !empty($product['model'])
                                                        ? route('front.product.show', $product['model']->slug)
                                                        : '#';
                                                @endphp
                                                <li class="col-md-3 col-sm-6">
                                                    <figure><a href="{{ $productUrl }}"><img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}"></a></figure>
                                                    <div class="as-causes-text living-card">
                                                        <div class="as-causes-info">
                                                            <h3><a href="{{ $productUrl }}">{{ $product['name'] }}</a></h3>
                                                            <p>{!! nl2br(e($product['description'])) !!}</p>
                                                        </div>
                                                        @if(!empty($product['affirmation']))
                                                            <p class="living-affirmation">&ldquo;{{ $product['affirmation'] }}&rdquo;</p>
                                                        @endif
                                                        @if(!empty($product['price']))
                                                            <div class="living-price-row">
                                                                <span class="living-price-current">${{ number_format($product['price'], 2) }}</span>
                                                                @if(!empty($product['compare']))
                                                                    <span class="living-price-compare">${{ number_format($product['compare'], 2) }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if(!empty($product['model']))
                                                            <a href="{{ $productUrl }}" class="as-causes-btn">View Item</a>
                                                        @endif
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="col-md-12">
                                                    <div class="living-empty">
                                                        <strong>Keepers are preparing this phase.</strong>
                                                        Add products from the admin panel and assign them to {{ $phase['title'] }} to reveal them here.
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="as-main-section living-ritual" id="living-ritual" style="padding:40px 0 60px 0; background:#111; color:#fff;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="as-fancytitle">
                            <h2 style="color:#fff;">Ritual Flow for Setup</h2>
                        </div>
                        <div class="as-fancy-divider-wrap">
                            <div class="as-fancy-divider"> <span class="as-first-dote"></span> <span class="as-sec-dote as-bgcolor"></span> <span class="as-third-dote"></span> </div>
                        </div>
                        <p style="color:#e8e8e8;">Move through the rites before, during, and after setup to keep the archive ceremonial.</p>
                    </div>
                </div>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-4">
                        <h4 style="color:#f0b428;">Before Setup</h4>
                        <ul style="color:#f5f5f5;">
                            @foreach(data_get($page, 'ritual_flow.before', []) as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h4 style="color:#f0b428;">During Setup</h4>
                        <ul style="color:#f5f5f5;">
                            @foreach(data_get($page, 'ritual_flow.during', []) as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h4 style="color:#f0b428;">After Setup</h4>
                        <ul style="color:#f5f5f5;">
                            @foreach(data_get($page, 'ritual_flow.after', []) as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="living-footer-line">
            {{ data_get($handoff, 'footer', 'The Yamassee Rising - A Living Archive of Ceremony and Song.') }}
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    (function ($) {
        'use strict';
        $('a[href^="#living-"]').on('click', function (e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({ scrollTop: target.offset().top - 60 }, 800);
            }
        });

        // Set default style switcher: black theme + black pattern
        setTimeout(function () {
            createCookie('style', '', -1); // reset previous choice
            // layout default wide
            $('.ec-wide').trigger('click');
            // apply black color (first swatch)
            $('.ec-switcher a[data-rel="color-black"]').trigger('click');
            // apply black pattern
            $('.pattren-wrap a[data-rel="pattren-black"]').trigger('click');
        }, 400);
    })(jQuery);
</script>
@endpush
