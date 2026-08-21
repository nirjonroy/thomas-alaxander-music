@extends('frontend.app')

@php
    $siteName = siteInfo()->site_name ?? siteInfo()->website_name ?? config('app.name', 'Thomas Alexander');
    $pageTitle = $epkPage->seo_title ?: $epkPage->title . ' | Thomas Alexander — The Voice';
    $descSource = $epkPage->seo_description ?: ($epkPage->subtitle ?: $epkPage->overview_content);
    $pageDesc = \Illuminate\Support\Str::limit(strip_tags($descSource ?: 'Professional EPK for Thomas Alexander — The Voice.'), 180);
    $canonical = url()->current();
    $image = $epkPage->og_image ?: $epkPage->hero_image;
    $fallbackLogo = siteInfo()->logo ?? null;
    $metaImage = $image ? (str_starts_with($image, 'http') ? $image : asset($image)) : ($fallbackLogo ? asset($fallbackLogo) : asset('images/og-default.jpg'));
    $metaImageAlt = $epkPage->og_image_alt ?: ($epkPage->hero_image_alt ?: $epkPage->title);
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => url('/'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'EPK',
                'item' => route('front.epk.full-artist'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $epkPage->slug === 'full-artist' ? 'Full Artist' : $epkPage->title,
                'item' => $canonical,
            ],
        ],
    ];
@endphp

@section('title', $pageTitle)

@section('seos')
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:alt" content="{{ $metaImageAlt }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .epk-page {
            --epk-ink: #070706;
            --epk-deep: #12100d;
            --epk-brown: #2b1a10;
            --epk-cream: #fff7e8;
            --epk-muted: rgba(255, 247, 232, 0.76);
            --epk-gold: #d9a441;
            --epk-gold-bright: #f1c76b;
            --epk-copper: #b96f37;
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--epk-cream);
            background:
                radial-gradient(circle at 12% 18%, rgba(217, 164, 65, 0.13), transparent 30%),
                radial-gradient(circle at 88% 30%, rgba(185, 111, 55, 0.12), transparent 34%),
                linear-gradient(135deg, var(--epk-ink), var(--epk-deep) 55%, #080d13);
            font-family: "Manrope", Arial, sans-serif;
        }
        .epk-page.common_pages_space {
            padding: 0 !important;
        }
        .epk-page *,
        .epk-page *::before,
        .epk-page *::after {
            box-sizing: border-box;
        }
        .epk-page a {
            color: var(--epk-gold-bright);
            text-decoration: none;
        }
        .epk-page a:focus-visible,
        .epk-page button:focus-visible {
            outline: 3px solid rgba(241, 199, 107, 0.74);
            outline-offset: 4px;
        }
        .epk-shell {
            width: min(100%, 1180px);
            margin: 0 auto;
            padding: clamp(18px, 3vw, 34px) 18px clamp(34px, 5vw, 72px);
        }
        .epk-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 22px;
        }
        .epk-breadcrumb {
            margin-bottom: 16px;
            color: rgba(255, 247, 232, 0.68);
            font-size: 0.85rem;
        }
        .epk-breadcrumb ol {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .epk-breadcrumb li {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }
        .epk-breadcrumb li + li::before {
            content: "/";
            color: rgba(241, 199, 107, 0.58);
        }
        .epk-breadcrumb a {
            color: rgba(255, 247, 232, 0.82);
            font-weight: 800;
        }
        .epk-breadcrumb a:hover,
        .epk-breadcrumb a:focus {
            color: var(--epk-gold-bright);
        }
        .epk-nav a {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            padding: 9px 14px;
            border: 1px solid rgba(217, 164, 65, 0.28);
            border-radius: 999px;
            color: rgba(255, 247, 232, 0.84);
            font-size: 13px;
            font-weight: 800;
        }
        .epk-nav a[aria-current="page"],
        .epk-nav a:hover {
            color: var(--epk-ink);
            background: linear-gradient(135deg, var(--epk-gold-bright), var(--epk-copper));
            border-color: transparent;
        }
        .epk-hero {
            position: relative;
            overflow: hidden;
            min-height: clamp(380px, 44vw, 560px);
            display: grid;
            align-items: end;
            padding: clamp(30px, 6vw, 72px);
            border: 1px solid rgba(217, 164, 65, 0.28);
            border-radius: 18px;
            background:
                linear-gradient(90deg, rgba(7, 7, 6, 0.9), rgba(7, 7, 6, 0.62) 44%, rgba(7, 7, 6, 0.18)),
                linear-gradient(180deg, rgba(7, 7, 6, 0.2), rgba(7, 7, 6, 0.94)),
                var(--epk-hero-image, linear-gradient(135deg, #120d08, #25150c));
            background-position: center;
            background-size: cover;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
        }
        .epk-hero__logo {
            position: absolute;
            right: clamp(18px, 4vw, 42px);
            bottom: clamp(18px, 4vw, 42px);
            z-index: 1;
            width: min(22vw, 150px);
            max-height: 66px;
            object-fit: contain;
            padding: 8px 10px;
            border: 1px solid rgba(241, 199, 107, 0.3);
            border-radius: 999px;
            background: rgba(7, 7, 6, 0.42);
            opacity: 0.68;
            filter: sepia(1) saturate(1.6) hue-rotate(350deg) brightness(1.05);
        }
        .epk-hero__content {
            max-width: 760px;
            position: relative;
            z-index: 2;
        }
        .epk-kicker {
            display: inline-block;
            margin-bottom: 14px;
            color: var(--epk-gold-bright);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }
        .epk-title,
        .epk-section h2,
        .epk-card h3 {
            color: var(--epk-cream);
            font-family: "Cormorant Garamond", Georgia, serif;
            letter-spacing: 0;
        }
        .epk-title {
            margin: 0 0 14px;
            font-size: clamp(4rem, 8vw, 7.2rem);
            line-height: 0.95;
        }
        .epk-title__sub {
            margin: -4px 0 14px;
            color: var(--epk-cream);
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: 13px;
            line-height: 1.05;
        }
        .epk-agency-line {
            margin: 14px 0 0;
            color: var(--epk-gold-bright);
            font-weight: 900;
        }
        .epk-subtitle,
        .epk-hero__booking,
        .epk-copy,
        .epk-card,
        .epk-card p {
            color: var(--epk-muted);
            line-height: 1.75;
        }
        .epk-hero__booking {
            margin: 18px 0 24px;
            font-weight: 700;
        }
        .epk-copy img {
            display: none;
        }
        .epk-hero__booking span {
            display: block;
            color: var(--epk-cream);
        }
        .epk-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, 0.65fr);
            gap: 24px;
            margin-top: 24px;
        }
        .epk-section,
        .epk-card {
            border: 1px solid rgba(217, 164, 65, 0.24);
            border-radius: 24px;
            background: linear-gradient(145deg, rgba(255, 247, 232, 0.07), rgba(255, 247, 232, 0.02)), rgba(10, 10, 8, 0.9);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.23);
        }
        .epk-section {
            padding: clamp(24px, 4vw, 42px);
        }
        .epk-section h2 {
            margin: 0 0 16px;
            font-size: clamp(2.8rem, 4.8vw, 4.4rem);
        }
        .epk-copy h1,
        .epk-copy h2,
        .epk-copy h3,
        .epk-copy h4,
        .epk-copy h5,
        .epk-copy h6 {
            margin: 0 0 16px;
            color: var(--epk-cream);
            font-family: "Cormorant Garamond", Georgia, serif;
            font-weight: 700;
            line-height: 1.05;
        }
        .epk-copy h1 {
            font-size: clamp(3.6rem, 7vw, 6.2rem);
        }
        .epk-copy h2 {
            font-size: clamp(2.8rem, 5.2vw, 4.8rem);
        }
        .epk-copy h3 {
            font-size: clamp(2.35rem, 4.3vw, 3.7rem);
        }
        .epk-copy h4 {
            font-size: clamp(2rem, 3.4vw, 3rem);
        }
        .epk-copy h5 {
            font-size: clamp(1.65rem, 2.8vw, 2.35rem);
        }
        .epk-copy h6 {
            font-size: clamp(1.35rem, 2.2vw, 1.85rem);
        }
        .epk-copy > *:last-child {
            margin-bottom: 0;
        }
        .epk-card {
            padding: 24px;
        }
        .epk-card + .epk-card {
            margin-top: 18px;
        }
        .epk-card h3 {
            margin: 0 0 10px;
            font-size: clamp(2.35rem, 4vw, 3.5rem);
        }
        .epk-card--performance-lanes,
        .epk-card--engagements,
        .epk-card--tags,
        .epk-card--testimonials,
        .epk-card--medley,
        .epk-card--video,
        .epk-card--booking,
        .epk-card--cross-link {
            margin-top: 24px;
        }
        .epk-card--cross-link .epk-kicker {
            margin-bottom: 8px;
        }
        .epk-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }
        .epk-lane-grid,
        .epk-quote-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .epk-lane {
            min-height: 118px;
            padding: 18px;
            border: 1px solid rgba(217, 164, 65, 0.22);
            border-radius: 16px;
            background: rgba(255, 247, 232, 0.045);
        }
        .epk-lane strong,
        .epk-lane span {
            display: block;
        }
        .epk-lane strong {
            margin-bottom: 8px;
            color: var(--epk-gold-bright);
            font-weight: 900;
        }
        .epk-engagement-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px 18px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .epk-engagement-list li {
            position: relative;
            padding-left: 18px;
            color: rgba(255, 247, 232, 0.82);
        }
        .epk-engagement-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.72em;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--epk-gold-bright);
        }
        .epk-tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .epk-tag-list span {
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            padding: 8px 13px;
            border: 1px solid rgba(217, 164, 65, 0.28);
            border-radius: 999px;
            color: rgba(255, 247, 232, 0.86);
            background: rgba(7, 7, 6, 0.42);
            font-weight: 800;
        }
        .epk-quote {
            margin: 0;
            padding: 20px;
            border-left: 3px solid var(--epk-gold-bright);
            border-radius: 14px;
            background: rgba(255, 247, 232, 0.045);
        }
        .epk-quote blockquote {
            margin: 0 0 14px;
            color: var(--epk-cream);
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: 1.45rem;
            line-height: 1.28;
        }
        .epk-quote figcaption {
            color: rgba(255, 247, 232, 0.68);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .epk-medley {
            position: relative;
            min-height: 320px;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
            margin: 0;
            padding: 22px;
            border-radius: 18px;
            background:
                linear-gradient(180deg, rgba(7, 7, 6, 0.1), rgba(7, 7, 6, 0.88)),
                var(--epk-medley-image, linear-gradient(135deg, #120d08, #25150c));
            background-position: center;
            background-size: cover;
        }
        .epk-medley img {
            display: none;
        }
        .epk-medley figcaption {
            position: relative;
            z-index: 1;
            max-width: 62%;
            color: var(--epk-cream);
            font-weight: 900;
            line-height: 1.5;
        }
        .epk-logo {
            display: none;
        }
        .living-float-group {
            right: 14px;
            bottom: 14px;
            z-index: 900;
            transform: scale(0.82);
            transform-origin: right bottom;
        }
        .epk-button {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border: 1px solid rgba(217, 164, 65, 0.46);
            border-radius: 999px;
            color: var(--epk-gold-bright);
            background: rgba(7, 7, 6, 0.58);
            font-weight: 900;
            text-decoration: none;
        }
        .epk-button--primary {
            color: var(--epk-ink);
            background: linear-gradient(135deg, var(--epk-gold-bright), var(--epk-copper));
            border-color: transparent;
        }
        .epk-button:hover {
            color: var(--epk-ink);
            background: linear-gradient(135deg, var(--epk-gold-bright), var(--epk-copper));
        }
        .epk-media iframe,
        .epk-video-frame iframe,
        .epk-media video,
        .epk-media audio {
            width: 100%;
        }
        .epk-media iframe,
        .epk-video-frame iframe {
            aspect-ratio: 16 / 9;
            height: auto;
            border: 0;
            border-radius: 18px;
            background: #000;
        }
        .epk-video-frame {
            overflow: hidden;
            margin-top: 18px;
            border: 1px solid rgba(217, 164, 65, 0.24);
            border-radius: 20px;
            background: #000;
            box-shadow: 0 24px 54px rgba(0, 0, 0, 0.24);
        }
        @media (max-width: 991.98px) {
            .epk-grid {
                grid-template-columns: 1fr;
            }
            .living-float-group {
                display: none;
            }
            .epk-hero__logo {
                top: 20px;
                right: 20px;
                bottom: auto;
                width: min(34vw, 132px);
            }
        }
        @media (max-width: 575.98px) {
            .epk-shell {
                padding-left: 12px;
                padding-right: 12px;
                padding-top: 14px;
            }
            .epk-hero,
            .epk-section,
            .epk-card {
                border-radius: 18px;
            }
            .epk-title {
                font-size: clamp(2.75rem, 13vw, 4rem);
                line-height: 1;
                overflow-wrap: anywhere;
            }
            .epk-title__sub {
                font-size: 13px;
            }
            .epk-section h2 {
                font-size: 2.65rem;
            }
            .epk-card h3 {
                font-size: 2.35rem;
            }
            .epk-copy h1 {
                font-size: 3rem;
            }
            .epk-copy h2 {
                font-size: 2.65rem;
            }
            .epk-copy h3 {
                font-size: 2.25rem;
            }
            .epk-copy h4 {
                font-size: 1.95rem;
            }
            .epk-copy h5 {
                font-size: 1.65rem;
            }
            .epk-copy h6 {
                font-size: 1.35rem;
            }
            .epk-lane-grid,
            .epk-quote-grid,
            .epk-engagement-list {
                grid-template-columns: 1fr;
            }
            .epk-lane {
                min-height: auto;
            }
            .epk-medley {
                min-height: 260px;
            }
            .epk-medley figcaption {
                max-width: 100%;
                padding-right: 0;
            }
            .epk-hero {
                min-height: 0;
                padding-top: 150px;
            }
            .epk-hero__logo {
                display: none;
            }
            .epk-nav a,
            .epk-button {
                width: 100%;
                text-align: center;
            }
            .epk-breadcrumb ol {
                gap: 6px;
            }
            .epk-card__actions {
                display: grid;
            }
        }
        .epk-page .epk-breadcrumb ol,
        .epk-page .epk-quote blockquote,
        .epk-page span,
        .epk-page h1,
        .epk-page h2,
        .epk-page h3,
        .epk-page h4,
        .epk-page h5,
        .epk-page h6 {
            font-size: 14px;
        }
        @media (prefers-reduced-motion: reduce) {
            .epk-page *,
            .epk-page *::before,
            .epk-page *::after {
                scroll-behavior: auto !important;
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <main class="ms_index_wrapper common_pages_space epk-page">
        <div class="epk-shell">
            <nav class="epk-breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('front.home') }}">Home</a></li>
                    <li><a href="{{ route('front.epk.full-artist') }}">EPK</a></li>
                    <li aria-current="page">{{ $epkPage->slug === 'full-artist' ? 'Full Artist' : 'Crooners' }}</li>
                </ol>
            </nav>

            @include('frontend.epk.partials.navigation', ['epkPages' => $epkPages, 'currentPage' => $epkPage])
            @include('frontend.epk.partials.hero', ['epkPage' => $epkPage])

            <div class="epk-grid">
                <article class="epk-section">
                    <span class="epk-kicker">Electronic Press Kit</span>
                    <h2>Artist Overview</h2>
                    <div class="epk-copy">
                        {!! clean($epkPage->overview_content ?: 'Professional EPK content will be added from the client-provided material.') !!}
                    </div>

                    @foreach(($epkPage->sections ?? []) as $section)
                        @include('frontend.epk.partials.section-card', ['section' => $section, 'epkPage' => $epkPage])
                    @endforeach
                </article>

                <aside aria-label="EPK media and booking">
                    @include('frontend.epk.partials.media-panel', ['epkPage' => $epkPage])

                    @if($alternateEpkPage)
                        <section class="epk-card epk-card--cross-link">
                            <span class="epk-kicker">Also Available</span>
                            <h3>{{ $alternateEpkPage->slug === 'crooners' ? 'Crooners EPK' : 'Full Artist EPK' }}</h3>
                            <p>
                                {{ $alternateEpkPage->slug === 'crooners'
                                    ? 'Explore the Crooners-focused EPK for repertoire, testimonials, live performance and booking details.'
                                    : 'Explore the full artist EPK for biography, performance lanes, repertoire, audio and booking details.' }}
                            </p>
                            <div class="epk-card__actions">
                                <a class="epk-button" href="{{ $alternateEpkPage->publicUrl() }}">
                                    {{ $alternateEpkPage->slug === 'crooners' ? 'Explore Crooners EPK' : 'Explore Full Artist EPK' }}
                                </a>
                            </div>
                        </section>
                    @endif
                </aside>
            </div>
        </div>
    </main>
@endsection
