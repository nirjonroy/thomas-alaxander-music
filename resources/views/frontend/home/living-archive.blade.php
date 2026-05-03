@extends('frontend.layouts.living-archive')

@section('seos')
    @php
        $seoSettings = $seoSettings
            ?? \App\Models\SeoSetting::forPage([
                'Living Archive',
                'The Living Archive',
                'Living Archive Page',
            ], '%Living Archive%')
            ?? DB::table('seo_settings')->where('id', 1)->first();

        $siteName = $seoSettings->site_name ?? config('app.name', 'Living Archive');
        $title = $seoSettings?->meta_title ?: ($seoSettings?->seo_title ?: $siteName);
        $rawDesc = $seoSettings?->meta_description ?: ($seoSettings?->seo_description ?: data_get($page ?? [], 'intro'));
        $desc = \Illuminate\Support\Str::limit(strip_tags($rawDesc ?? ''), 180);
        $url = url()->current();
        $fallbackLogo = siteInfo()->logo ?? null;
        $defaultImage = $fallbackLogo ? asset($fallbackLogo) : asset('images/og-default.jpg');
        $metaImageValue = $seoSettings?->meta_image;
        $ogImage = $metaImageValue
            ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
            : $defaultImage;
        $updatedIso = optional($seoSettings?->updated_at)->toIso8601String() ?? now()->toIso8601String();
        $twitter = $seoSettings->twitter_site ?? '@livingarchive';
        $indexable = isset($seoSettings->indexable) ? (bool) $seoSettings->indexable : true;
        $author = $seoSettings->seo_author ?? ($seoSettings->author ?? $siteName);
        $publisher = $seoSettings->seo_publisher ?? ($seoSettings->publisher ?? $siteName);
        $copyright = $seoSettings->meta_copyright ?? ($seoSettings->copyright ?? null);
        $keywords = $seoSettings->seo_keywords ?? ($seoSettings->keywords ?? null);
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
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('muzex/assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('muzex/assets/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('muzex/assets/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('muzex/assets/images/favicon/site.webmanifest') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Work+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('muzex/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/hover-min.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('muzex/assets/css/responsive.css') }}">

    <style>
        :root {
            --living-bg: #0b0f17;
            --living-bg-soft: #121923;
            --living-text: #f4efe3;
            --living-text-soft: rgba(244, 239, 227, 0.76);
            --living-gold: #d4af37;
            --living-gold-soft: rgba(212, 175, 55, 0.2);
            --living-glass: rgba(20, 20, 22, 0.7);
            --living-shadow: 0 24px 60px rgba(0, 0, 0, 0.34);
            --living-radius: 1.5rem;
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Work Sans', sans-serif;
            background:
                radial-gradient(circle at top, rgba(212, 175, 55, 0.08), transparent 30%),
                linear-gradient(180deg, #0a0e15 0%, #101723 100%);
            color: var(--living-text);
        }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .block-title h3,
        .block-title-two h3,
        .footer-widget__title,
        .side-content__block__title,
        .living-section-heading__title,
        .living-card-title,
        .living-paper-title {
            font-family: 'Cinzel', serif;
            color: var(--living-text);
            letter-spacing: 0.04em;
        }
        section[id] {
            scroll-margin-top: 110px;
        }
        a {
            transition: all 0.25s ease;
        }
        .preloader {
            background-color: var(--living-bg);
        }
        .main-nav-one__home-three {
            background: rgba(9, 12, 18, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.12);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
        }
        .main-nav-one .logo-box img {
            width: auto;
            max-height: 62px;
        }
        .main-nav__navigation-box > li > a {
            font-family: 'Work Sans', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .banner-section,
        .collection-two,
        .about-three,
        .cta-two,
        .collection-three,
        .event-one,
        .blog-one,
        .site-footer,
        .living-dark-section {
            background: transparent;
        }
        .living-dark-section {
            position: relative;
            padding: 6rem 0;
        }
        .living-section-shell {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .living-section-heading {
            max-width: 820px;
            margin: 0 auto 3rem;
            text-align: center;
        }
        .living-section-eyebrow {
            display: inline-block;
            margin-bottom: 1rem;
            color: rgba(212, 175, 55, 0.9);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.28em;
            text-transform: uppercase;
        }
        .living-section-heading__title {
            font-size: clamp(2rem, 4vw, 3.4rem);
            margin-bottom: 1rem;
        }
        .living-section-heading__copy,
        .living-section-copy {
            color: var(--living-text-soft);
            line-height: 1.85;
            max-width: 760px;
            margin: 0 auto;
        }
        .living-glass-card,
        .collection-two__single,
        .collection-three__block,
        .event-one__single,
        .blog-one__single,
        .cta-two__box {
            background: var(--living-glass);
            border: 1px solid var(--living-gold-soft);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: var(--living-shadow);
            border-radius: var(--living-radius);
        }
        .living-glass-card.is-interactive,
        .living-crest-card {
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        }
        .living-glass-card.is-interactive:hover,
        .living-crest-card:hover {
            transform: translateY(-10px);
            border-color: rgba(212, 175, 55, 0.35);
            box-shadow: 0 32px 65px rgba(0, 0, 0, 0.4);
        }
        .living-card-media {
            overflow: hidden;
            margin-bottom: 1.5rem;
            border-radius: 1rem;
        }
        .living-card-media img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
        }
        .living-card-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.55rem 1rem;
            border-radius: 999px;
            background: rgba(212, 175, 55, 0.12);
            border: 1px solid rgba(212, 175, 55, 0.18);
            color: rgba(244, 239, 227, 0.92);
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }
        .living-card-title {
            font-size: clamp(1.35rem, 2vw, 1.75rem);
            margin: 1.25rem 0 0.9rem;
            line-height: 1.35;
        }
        .living-card-declaration {
            color: var(--living-gold);
            font-weight: 600;
            line-height: 1.8;
            margin-bottom: 0.85rem;
        }
        .living-card-text {
            color: var(--living-text-soft);
            line-height: 1.85;
            margin-bottom: 0.9rem;
        }
        .living-card-text:last-child {
            margin-bottom: 0;
        }
        .living-svg-icon {
            width: 1em;
            height: 1em;
            display: inline-block;
            vertical-align: middle;
            fill: currentColor;
            flex: 0 0 auto;
        }
        .living-nav-icon,
        .living-close-icon {
            width: 1.35rem;
            height: 1.35rem;
        }
        .living-side-icon,
        .living-scroll-icon {
            width: 1rem;
            height: 1rem;
        }
        .living-hero-section {
            position: relative;
            min-height: 100vh;
            background-position: center;
            background-size: cover;
            overflow: hidden;
        }
        .living-hero-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(7, 10, 14, 0.84), rgba(7, 10, 14, 0.7)),
                radial-gradient(circle at top, rgba(212, 175, 55, 0.12), transparent 42%);
        }
        .living-hero-section .container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 7rem;
            padding-bottom: 5rem;
        }
        .living-hero-panel {
            width: 100%;
            max-width: 920px;
            text-align: center;
        }
        .living-hero-crest img {
            max-width: 220px;
            width: 100%;
            border-radius: 1.25rem;
            box-shadow: 0 22px 48px rgba(0, 0, 0, 0.32);
        }
        .living-hero-title {
            font-size: clamp(2.8rem, 6vw, 5.75rem);
            line-height: 1.08;
            color: #f8f2e6;
        }
        .living-hero-intro {
            max-width: 700px;
            margin: 0 auto;
            color: rgba(244, 239, 227, 0.82);
            font-size: 1.08rem;
            line-height: 1.95;
        }
        .living-hero-subtitle {
            display: inline-block;
            margin-top: 1.75rem;
            padding: 0.75rem 1.25rem;
            border-radius: 999px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.16);
            color: rgba(244, 239, 227, 0.92);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }
        .btn.living-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.95rem 1.7rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            min-height: 56px;
        }
        .btn.living-btn-primary {
            background: linear-gradient(135deg, #d4af37, #f3d67b);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: #17120a;
            box-shadow: 0 16px 28px rgba(212, 175, 55, 0.18);
        }
        .btn.living-btn-primary:hover {
            color: #17120a;
            transform: translateY(-2px);
        }
        .btn.living-btn-outline {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 175, 55, 0.35);
            color: var(--living-text);
        }
        .btn.living-btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            transform: translateY(-2px);
        }
        .living-grid > [class*="col-"] {
            display: flex;
        }
        .living-grid > [class*="col-"] > * {
            width: 100%;
        }
        .living-outfit {
            font-family: 'Outfit', sans-serif;
        }
        .living-pathway-step {
            width: 3.25rem;
            height: 3.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.14);
            border: 1px solid rgba(212, 175, 55, 0.24);
            color: var(--living-gold);
            font-weight: 700;
            letter-spacing: 0.08em;
        }
        .living-pathway-icon {
            color: var(--living-gold);
            font-size: 1.25rem;
        }
        .living-pathway-icon .living-svg-icon {
            width: 1.3rem;
            height: 1.3rem;
        }
        .living-ceremonial-intro {
            background:
                linear-gradient(180deg, rgba(8, 11, 16, 0.98), rgba(10, 15, 22, 0.94)),
                radial-gradient(circle at top, rgba(212, 175, 55, 0.08), transparent 42%);
        }
        .living-ceremonial-intro__wrap {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        .living-ceremonial-intro__media {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(20, 20, 22, 0.7);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 1.5rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 24px 56px rgba(212, 175, 55, 0.08);
        }
        .living-ceremonial-intro__media img {
            max-width: 250px;
            width: 100%;
            border-radius: 1.25rem;
            box-shadow: 0 18px 40px rgba(212, 175, 55, 0.12);
        }
        .living-ceremonial-intro__title {
            color: var(--living-gold);
            font-size: clamp(2rem, 4vw, 3.25rem);
            line-height: 1.2;
        }
        .living-ceremonial-intro__copy {
            max-width: 800px;
            margin: 0 auto;
            color: rgba(244, 239, 227, 0.84);
            font-family: 'Outfit', sans-serif;
            font-size: 1.08rem;
            line-height: 1.95;
        }
        .living-ceremonial-intro__copy + .living-ceremonial-intro__copy {
            margin-top: 1.15rem;
        }
        .about-three__content {
            padding: 80px 9vw 80px 70px;
        }
        .about-three .about-three__content,
        .about-three .about-three__content > p {
            color: var(--living-text-soft);
        }
        .about-three .about-three__content .about-three__highlight {
            color: var(--living-gold);
        }
        .about-three .about-three__content .block-title p {
            color: rgba(212, 175, 55, 0.9);
        }
        .about-three .about-three__content .block-title h3 {
            color: var(--living-text);
        }
        .living-about-image {
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            background: radial-gradient(circle at center, rgba(212, 175, 55, 0.12), transparent 58%);
        }
        .living-about-image img {
            max-width: 440px;
            width: 100%;
            border-radius: 22px;
            box-shadow: 0 26px 60px rgba(0, 0, 0, 0.35);
        }
        .about-three__highlight {
            color: var(--living-gold);
            line-height: 1.9;
            font-weight: 500;
        }
        .living-note-list {
            list-style: none;
            padding: 0;
            margin: 26px 0 0;
        }
        .living-note-list li {
            position: relative;
            padding-left: 18px;
            margin-bottom: 14px;
            color: var(--living-text-soft);
            line-height: 1.85;
        }
        .living-note-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 12px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--living-gold);
        }
        .cta-two__home-two .inner-container {
            background: linear-gradient(135deg, rgba(17, 22, 30, 0.96), rgba(10, 14, 19, 0.96));
            border-radius: var(--living-radius);
            border: 1px solid rgba(212, 175, 55, 0.12);
            overflow: hidden;
        }
        .cta-two__icon,
        .living-endline {
            color: var(--living-gold);
        }
        .cta-two__icon .living-svg-icon {
            width: 1.5rem;
            height: 1.5rem;
        }
        .living-endline {
            padding: 28px 32px 0;
            text-align: center;
            font-family: 'Cinzel', serif;
            font-size: 1.25rem;
        }
        .event-one__content h3 a,
        .blog-one__content h3 a,
        .footer-widget__title,
        .side-content__block__title,
        .footer-widget__links-list a,
        .site-footer__bottom-links a {
            color: var(--living-text);
        }
        .event-one__content p,
        .blog-one__content p,
        .footer-widget p,
        .side-content__block-about__text,
        .side-content__block-contact__list-item,
        .site-footer__bottom p,
        .site-footer__copy-text {
            color: var(--living-text-soft);
        }
        .blog-one__link {
            color: var(--living-gold);
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .blog-one__link + .blog-one__link {
            margin-left: 20px;
        }
        .living-contact-card .blog-one__content {
            padding-top: 28px;
        }
        .side-content__block-contact__list-item .living-svg-icon,
        .side-menu__social .living-svg-icon,
        .scroll-to-top .living-svg-icon {
            margin-right: 0.5rem;
        }
        .side-menu__social .living-svg-icon,
        .scroll-to-top .living-svg-icon {
            margin-right: 0;
        }
        .living-certification-section {
            position: relative;
            background-position: center;
            background-size: cover;
            overflow: hidden;
        }
        .living-certification-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8, 10, 14, 0.9), rgba(12, 16, 22, 0.82));
        }
        .living-certification-section .container {
            position: relative;
            z-index: 1;
        }
        .living-certification-panel {
            max-width: 920px;
            margin: 0 auto;
        }
        .living-paper-card {
            position: relative;
            background: linear-gradient(180deg, rgba(252, 247, 235, 0.99), rgba(242, 233, 212, 0.96));
            color: #23170d;
            border-radius: 1.5rem;
            border: 1px solid rgba(122, 95, 43, 0.4);
            box-shadow:
                0 36px 70px rgba(0, 0, 0, 0.36),
                0 14px 28px rgba(0, 0, 0, 0.18);
        }
        .living-paper-card::before {
            content: "";
            position: absolute;
            inset: 12px;
            border: 1px solid rgba(122, 95, 43, 0.28);
            border-radius: calc(1.5rem - 10px);
            pointer-events: none;
        }
        .living-paper-title {
            color: #8a621e;
            font-size: 1.15rem;
            letter-spacing: 0.1em;
        }
        .living-paper-body {
            margin: 0;
            white-space: pre-line;
            line-height: 1.95;
            font-family: "Courier New", Courier, monospace;
            font-size: 0.98rem;
        }
        .site-footer {
            background: rgba(7, 10, 14, 0.92);
        }
        .site-footer__bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }
        .site-footer__bottom-logo img,
        .side-content__block-inner img,
        .side-menu__logo img {
            max-height: 56px;
            width: auto;
        }
        .side-content__block-inner,
        .side-menu__block-inner {
            background: #101316;
        }
        .scroll-to-top {
            background: var(--living-gold);
            color: #18140d;
        }
        @media (max-width: 991.98px) {
            .living-dark-section {
                padding: 4.75rem 0;
            }
            .about-three__content {
                padding: 56px 24px;
            }
        }
        @media (max-width: 767.98px) {
            .main-nav-one .logo-box img {
                max-height: 48px;
            }
            .living-section-shell {
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }
            .living-hero-section .container {
                padding-top: 6.5rem;
                padding-bottom: 4rem;
            }
            .living-hero-title {
                font-size: clamp(2.35rem, 11vw, 3.5rem);
            }
            .living-hero-intro {
                font-size: 1rem;
            }
            .living-ceremonial-intro__copy {
                font-size: 1rem;
            }
            .living-glass-card,
            .collection-two__single,
            .collection-three__block,
            .event-one__single,
            .blog-one__single,
            .cta-two__box,
            .living-paper-card {
                padding: 1.5rem !important;
            }
            .living-about-image {
                padding: 40px 24px;
            }
            .blog-one__link + .blog-one__link {
                display: inline-block;
                margin-left: 0;
                margin-top: 10px;
            }
        }
    </style>
@endpush

@section('content')
@php
    $crest = data_get($page, 'crest', []);
    $hero = data_get($page, 'hero', []);
    $lineage = data_get($page, 'lineage', []);
    $crests = data_get($page, 'crests', []);
    $pathway = data_get($page, 'pathway', []);
    $mediaMerch = data_get($page, 'media_merch', []);
    $qr = data_get($page, 'qr', []);
    $contactSection = data_get($page, 'contact_section', []);
    $contact = data_get($page, 'contact', []);
    $certification = data_get($page, 'certification', []);

    $primaryCrestImage = $crest['primary_image'] ?? asset('frontend/living-archive/Dreamcatcher-style crest.jpeg');
    $secondaryCrestImage = $crest['secondary_image'] ?? asset('frontend/living-archive/crest represents the Five Civilized Tribes.jpeg');
    $heroImage = data_get($page, 'media.hero', asset('frontend/living-archive/banner3.jpg'));
    $logoImage = data_get($page, 'media.logo', asset('frontend/living-archive/images/logo.png'));

    $youthCrestImage = data_get($crests, 'youth.image', $secondaryCrestImage);
    $keeperCrestImage = data_get($crests, 'keeper.image', $secondaryCrestImage);
    $witnessCrestImage = data_get($crests, 'witness.image', $secondaryCrestImage);
    $qrCrestImage = data_get($qr, 'image', $secondaryCrestImage);

    $introText = data_get($page, 'intro')
        ?? 'Thomas Alexander - The Voice - carries the Living Crest of the Breath-line, a ceremonial archive of memory, music, and lineage.';

    $splitParagraphs = function ($text) {
        $lines = preg_split('/\r\n|\r|\n/', (string) $text);
        $lines = array_map('trim', $lines);
        return array_values(array_filter($lines, fn ($line) => $line !== ''));
    };

    $iconSvg = function ($name, $classes = '') {
        $normalized = trim((string) $name);
        $normalized = str_replace(['muzex-icon-', 'fa ', 'fa-'], '', $normalized);
        $normalized = trim(preg_replace('/\s+/', ' ', $normalized));
        $classAttr = htmlspecialchars($classes, ENT_QUOTES, 'UTF-8');

        return match ($normalized) {
            'menu' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v2H4zm0 6h16v2H4zm0 6h16v2H4z"/></svg>',
            'tree' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l4 5h-2l3 4h-2l3 4h-6v7h-2v-7H4l3-4H5l3-4H6l4-5h2z"/></svg>',
            'paw' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 11c-1.1 0-2-.9-2-2.2C5 7.3 5.9 6 7 6s2 .9 2 2.2C9 9.7 8.1 11 7 11zm10 0c-1.1 0-2-.9-2-2.2C15 7.3 15.9 6 17 6s2 .9 2 2.2c0 1.5-.9 2.8-2 2.8zM10 8c-1.1 0-2-.9-2-2.2C8 4.3 8.9 3 10 3s2 .9 2 2.2C12 6.7 11.1 8 10 8zm4 0c-1.1 0-2-.9-2-2.2C12 4.3 12.9 3 14 3s2 .9 2 2.2C16 6.7 15.1 8 14 8zm-2 4c3.2 0 6 2.2 6 5 0 2-1.6 4-4 4-1.1 0-1.8-.4-2-.8-.2.4-.9.8-2 .8-2.4 0-4-2-4-4 0-2.8 2.8-5 6-5z"/></svg>',
            'shieldalt', 'shield' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l7 3v6c0 5-3.4 9.7-7 11-3.6-1.3-7-6-7-11V5l7-3zm0 2.2L7 6.2v4.7c0 3.9 2.5 7.8 5 9 2.5-1.2 5-5.1 5-9V6.2l-5-2z"/></svg>',
            'owl' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3c4.4 0 8 3.6 8 8v8l-4-2-4 2-4-2-4 2v-8c0-4.4 3.6-8 8-8zm-3 8a1.75 1.75 0 100 3.5A1.75 1.75 0 009 11zm6 0a1.75 1.75 0 100 3.5 1.75 1.75 0 000-3.5zM12 7c-1.6 0-3 .7-4 1.8L10 10h4l2-1.2A5.3 5.3 0 0012 7z"/></svg>',
            'feather', 'featheralt' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.7 3.3c-4.7-.7-8.5.6-11.4 3.5-2.3 2.3-3.9 5.5-4.8 9.5l-1.2 4.4 4.4-1.2c4-.9 7.2-2.5 9.5-4.8 2.9-2.9 4.2-6.7 3.5-11.4zM8 17l-1 .3.3-1c.6-2.1 1.4-3.9 2.5-5.5l2.7 2.7c-1.6 1.1-3.4 1.9-5.5 2.5zm6.8-4.6l-3.2-3.2c1.5-1.6 3.5-2.7 5.9-3.2-.5 2.4-1.6 4.4-3.2 5.9z"/></svg>',
            'envelope' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1zm0 2v.5l9 6 9-6V7H3zm18 11V9.8l-8.4 5.6a1 1 0 01-1.2 0L3 9.8V18h18z"/></svg>',
            'phone' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8c1.5 2.9 3.8 5.2 6.7 6.7l2.2-2.2a1 1 0 011-.24c1.1.36 2.3.56 3.5.56a1 1 0 011 1V21a1 1 0 01-1 1C10.1 22 2 13.9 2 3a1 1 0 011-1h4.4a1 1 0 011 1c0 1.2.2 2.4.56 3.5a1 1 0 01-.24 1l-2.16 2.3z"/></svg>',
            'qrcode' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10-2h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm2 2h2v4h-2v-2h-2v-2h2zm-6 2h2v2h-2v-2zm2 2h4v2h-4v-2z"/></svg>',
            'copyright' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 2a8 8 0 110 16 8 8 0 010-16zm3.5 5.2l-1.4 1A3 3 0 0012 9a3 3 0 000 6 3 3 0 002.1-.8l1.4 1A5 5 0 1112 7c1.3 0 2.5.5 3.5 1.2z"/></svg>',
            'times' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.3 5.7L12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7l1.4-1.4 6.3 6.3 6.3-6.3 1.4 1.4z"/></svg>',
            'angleup' => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><path d="M7.4 14.6L12 10l4.6 4.6 1.4-1.4-6-6-6 6 1.4 1.4z"/></svg>',
            default => '<svg class="' . $classAttr . '" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8"/></svg>',
        };
    };

    $generalContactBody = trim((string) data_get($contactSection, 'general.body'));
    if ($generalContactBody === '') {
        $generalContactBody = 'Email: ' . data_get($contact, 'email', 'info@thomasalexanderthevoice.com')
            . "\nPhone: " . data_get($contact, 'phone', '(to be added)');
    }

    $crestTitle = trim((string) data_get($crest, 'title', ''));
    $crestCaption = trim((string) data_get($crest, 'secondary_caption', ''));
    $crestNotes = array_values(array_filter([
        data_get($crest, 'body_two'),
        data_get($crest, 'body_three'),
        data_get($crest, 'mission'),
    ], fn ($note) => trim((string) $note) !== ''));

    $certTextRaw = trim((string) data_get($certification, 'text', ''));
    $certLines = preg_split('/\r\n|\r|\n/', $certTextRaw);
    $certHeading = $certLines ? array_shift($certLines) : '';
    $certBody = trim(implode("\n", array_filter($certLines, fn ($line) => trim($line) !== '')));

    $crestCards = [
        [
            'id' => 'youth-crest',
            'label' => 'Youth Crest',
            'title' => data_get($crests, 'youth.title', 'Youth Crest - The Listener'),
            'declaration' => data_get($crests, 'youth.declaration', 'We perch where the roof gave way.'),
            'body_lines' => $splitParagraphs(data_get($crests, 'youth.body')),
            'image' => $youthCrestImage,
        ],
        [
            'id' => 'keeper-crest',
            'label' => 'Keeper Crest',
            'title' => data_get($crests, 'keeper.title', 'Keeper Crest - The Bearer'),
            'declaration' => data_get($crests, 'keeper.declaration', 'As the eagle, I did not blink, for I saw and see it all.'),
            'body_lines' => $splitParagraphs(data_get($crests, 'keeper.body')),
            'image' => $keeperCrestImage,
        ],
        [
            'id' => 'witness-crest',
            'label' => 'Witness Crest',
            'title' => data_get($crests, 'witness.title', 'Witness Crest - The Elder'),
            'declaration' => data_get($crests, 'witness.declaration', 'We kept the fire when the world went dark.'),
            'body_lines' => $splitParagraphs(data_get($crests, 'witness.body')),
            'image' => $witnessCrestImage,
        ],
    ];

    $pathwayCards = [];
    foreach (array_values(data_get($pathway, 'steps', [])) as $index => $step) {
        $bodyLines = $splitParagraphs(data_get($step, 'body'));
        $pathwayCards[] = [
            'id' => 'pathway-step-' . ($index + 1),
            'label' => 'Step ' . ($index + 1),
            'title' => data_get($step, 'title', 'Carrier Pathway'),
            'summary' => $bodyLines[0] ?? data_get($pathway, 'intro', 'The lineage moves with intention.'),
            'body_lines' => $bodyLines,
            'icon' => data_get($step, 'icon', 'fa-circle'),
            'image' => [$youthCrestImage, $keeperCrestImage, $witnessCrestImage][$index] ?? $primaryCrestImage,
        ];
    }

    $lineageHighlights = [
        [
            'icon' => 'tree',
            'title' => 'Tree of Life',
            'body' => data_get($lineage, 'tree', 'Root and canopy unite the Breath-line, keeping the living memory in motion.'),
        ],
        [
            'icon' => 'paw',
            'title' => 'Clan Animals',
            'body' => data_get($lineage, 'clan', 'Guardians of medicine, each one marking protection, vow, and teaching.'),
        ],
        [
            'icon' => 'shield-alt',
            'title' => 'Ancestral Shields',
            'body' => data_get($lineage, 'shields', 'Three shields hold sovereignty, continuity, and ceremonial protection.'),
        ],
    ];

    $experienceCards = [
        [
            'tag_day' => '01',
            'tag_month' => 'Hall',
            'title' => data_get($mediaMerch, 'merch.title', 'Merch Crest'),
            'body' => data_get($mediaMerch, 'merch.body', 'Apparel, scores, and ceremonial items are lineage extensions -- worn and shared to keep the crest visible.'),
            'cta_label' => data_get($mediaMerch, 'merch.cta_label', 'Enter the Artifact Hall'),
            'cta_url' => data_get($mediaMerch, 'merch.cta_url', route('front.shop')),
            'image' => data_get($mediaMerch, 'merch.image', $secondaryCrestImage),
        ],
        [
            'tag_day' => '02',
            'tag_month' => 'QR',
            'title' => data_get($qr, 'title', data_get($mediaMerch, 'qr.title', 'QR Access')),
            'body' => data_get($qr, 'intro', data_get($mediaMerch, 'qr.body', "The QR Crest is a digital gateway -- a quiet entry into the archive's living record.")),
            'cta_label' => data_get($qr, 'cta_label', data_get($mediaMerch, 'qr.cta_label', 'Open the QR Gateway')),
            'cta_url' => data_get($qr, 'cta_url', data_get($mediaMerch, 'qr.cta_url', route('living-archive.donate'))),
            'image' => data_get($qr, 'image', data_get($mediaMerch, 'qr.image', $qrCrestImage)),
        ],
        [
            'tag_day' => '03',
            'tag_month' => 'Seal',
            'title' => data_get($certification, 'title', 'Printable Certification'),
            'body' => data_get($certification, 'intro', 'Static ceremonial document for carriers within the Five Feathers lineage.'),
            'cta_label' => 'View Certification',
            'cta_url' => '#certification',
            'image' => $primaryCrestImage,
        ],
    ];

    $contactCards = [
        [
            'label' => 'Training',
            'title' => data_get($contactSection, 'training.title', 'Training Invitation'),
            'body_lines' => $splitParagraphs(data_get($contactSection, 'training.body')),
            'cta_label' => data_get($contactSection, 'training.cta_label', 'Request Training'),
            'cta_url' => data_get($contactSection, 'training.cta_url', 'mailto:' . data_get($contact, 'email', 'info@thomasalexanderthevoice.com')),
            'secondary_cta_label' => null,
            'secondary_cta_url' => null,
            'image' => $primaryCrestImage,
        ],
        [
            'label' => 'Ceremony',
            'title' => data_get($contactSection, 'events.title', 'Ceremonial Events'),
            'body_lines' => $splitParagraphs(data_get($contactSection, 'events.body')),
            'cta_label' => data_get($contactSection, 'events.cta_label', 'See Ceremonial Calendar'),
            'cta_url' => data_get($contactSection, 'events.cta_url', route('living-archive.donate')),
            'secondary_cta_label' => null,
            'secondary_cta_url' => null,
            'image' => $secondaryCrestImage,
        ],
        [
            'label' => 'Contact',
            'title' => data_get($contactSection, 'general.title', 'Contact'),
            'body_lines' => $splitParagraphs($generalContactBody),
            'cta_label' => data_get($contactSection, 'general.cta_label', 'Email the Archive'),
            'cta_url' => data_get($contactSection, 'general.cta_url', 'mailto:' . data_get($contact, 'email', 'info@thomasalexanderthevoice.com')),
            'secondary_cta_label' => data_get($contactSection, 'general.support_label', 'Offer Support'),
            'secondary_cta_url' => data_get($contactSection, 'general.support_url', route('living-archive.donate')),
            'image' => $qrCrestImage,
        ],
    ];

    $phoneText = data_get($contact, 'phone', '(to be added)');
    $phoneHref = preg_replace('/[^0-9+]/', '', $phoneText);

    $quickLinks = [
        ['label' => 'Home', 'url' => '#crest-home'],
        ['label' => 'Lineage Story', 'url' => '#lineage-story'],
        ['label' => 'Three Crests', 'url' => '#three-crests'],
        ['label' => 'Carrier Pathway', 'url' => '#carrier-pathway'],
        ['label' => 'Contact', 'url' => '#contact-invitations'],
    ];
@endphp

<div class="preloader">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
</div>

<div class="page-wrapper">
    <nav class="main-nav-one stricky main-nav-one__home-three">
        <div class="container">
            <div class="inner-container">
                <div class="logo-box">
                    <a href="#crest-home">
                        <img src="{{ $logoImage }}" alt="Living Archive" width="143">
                    </a>
                    <a href="#" class="side-menu__toggler">{!! $iconSvg('menu', 'living-svg-icon living-nav-icon') !!}</a>
                </div>
                <div class="main-nav__main-navigation">
                    <ul class="main-nav__navigation-box">
                        <li><a href="#crest-home">Home</a></li>
                        <li><a href="#lineage-story">Lineage Story</a></li>
                        <li><a href="#three-crests">Three Crests</a></li>
                        <li><a href="#carrier-pathway">Carrier Pathway</a></li>
                        <li><a href="#media-merch">Media &amp; Merch</a></li>
                        <li><a href="#contact-invitations">Contact</a></li>
                        <li><a href="#certification">Certification</a></li>
                    </ul>
                </div>
                <div class="main-nav__right">
                    <a class="sidemenu-icon side-content__toggler" href="#">{!! $iconSvg('menu', 'living-svg-icon living-nav-icon') !!}</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="living-hero-section" id="crest-home" style="background-image: url('{{ $heroImage }}');">
        <div class="container px-4 px-lg-3">
            <div class="living-hero-panel living-glass-card p-4 p-lg-5">
                <span class="living-section-eyebrow">{{ data_get($page, 'header.title', 'Living Archive') }}</span>
                <div class="living-hero-crest mb-4">
                    <img src="{{ $primaryCrestImage }}" alt="Main Ceremonial Crest" class="img-fluid">
                </div>
                <h1 class="living-hero-title mb-4">{{ data_get($hero, 'affirmation', 'We Were Never Erased. We Were Replanted.') }}</h1>
                <p class="living-hero-intro">{{ $introText }}</p>
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-stretch align-items-md-center gap-3 mt-4">
                    <a href="{{ data_get($hero, 'primary_cta_url', '#lineage-story') }}" class="btn living-btn living-btn-primary">
                        {{ data_get($hero, 'primary_cta_label', 'Explore the Five Feathers Lineage') }}
                    </a>
                    <a href="{{ data_get($hero, 'secondary_cta_url', '#carrier-pathway') }}" class="btn living-btn living-btn-outline">
                        {{ data_get($hero, 'secondary_cta_label', 'Begin the Carrier Pathway') }}
                    </a>
                </div>
                <span class="living-hero-subtitle">{{ data_get($page, 'header.subtitle', 'This is not a store. This is ceremony.') }}</span>
            </div>
        </div>
    </section>

    <section class="living-dark-section living-ceremonial-intro" id="ceremonial-introduction">
        <div class="container living-section-shell py-5">
            <div class="living-ceremonial-intro__wrap">
                <span class="living-section-eyebrow">Step 2 • Homepage Ceremonial Introduction</span>
                <h2 class="living-ceremonial-intro__title mb-4">Ceremonial Introduction</h2>
                <div class="living-ceremonial-intro__media mb-4">
                    <img src="{{ $primaryCrestImage }}" alt="Living Archive Ceremonial Crest" class="img-fluid rounded-4">
                </div>
                <p class="living-ceremonial-intro__copy">
                    {{ $introText }}
                </p>
                <p class="living-ceremonial-intro__copy">
                    {{ data_get($lineage, 'intro', data_get($crest, 'body_one', 'The Living Archive is a ceremonial record where memory, symbol, and song return to their rightful lineage.')) }}
                </p>
            </div>
        </div>
    </section>

    <section class="living-dark-section" id="three-crests">
        <div class="container living-section-shell">
            <div class="living-section-heading">
                <span class="living-section-eyebrow">Living Archive</span>
                <h2 class="living-section-heading__title">{{ data_get($crests, 'title', 'The Three Crests') }}</h2>
                <p class="living-section-heading__copy">{{ data_get($crests, 'intro', 'These are sacred displays -- static and enduring, held as testimony for the youth, the keepers, and the elders of the lineage.') }}</p>
            </div>
            <div class="row g-4 living-grid">
                @foreach ($crestCards as $card)
                    <div class="col-12 col-md-12 col-lg-4" id="{{ $card['id'] }}">
                        <article class="living-glass-card living-crest-card is-interactive h-100 d-flex flex-column p-4 p-lg-5">
                            <div class="living-card-media">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="img-fluid rounded-3">
                            </div>
                            <span class="living-card-badge">{{ $card['label'] }}</span>
                            <h3 class="living-card-title">{{ $card['title'] }}</h3>
                            <p class="living-card-declaration">{{ $card['declaration'] }}</p>
                            <div class="mt-auto">
                                @foreach ($card['body_lines'] as $line)
                                    <p class="living-card-text">{{ $line }}</p>
                                @endforeach
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about-three" id="lineage-story">
        <div class="container-fluid">
            <div class="row no-gutters">
                <div class="col-lg-6">
                    <div class="about-three__image clearfix living-about-image">
                        <img src="{{ $primaryCrestImage }}" alt="{{ $crestTitle ?: 'Ceremonial Crest' }}">
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="my-auto">
                        <div class="about-three__content">
                            <div class="block-title">
                                <p>{{ $crestCaption ?: 'Ceremonial Crest' }}</p>
                                <h3>{{ data_get($lineage, 'title', 'About the Lineage') }}</h3>
                            </div>
                            <p class="about-three__highlight">{{ $introText }}</p>
                            <p>{{ data_get($crest, 'body_one', 'The Tree of Life stands at the center of the crest, holding the Breath-line across generations and returning each name to ceremony.') }}</p>
                            @if (!empty($crestNotes))
                                <ul class="living-note-list">
                                    @foreach ($crestNotes as $note)
                                        <li>{!! nl2br(e($note)) !!}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-two cta-two__home-two">
        <div class="container">
            <div class="inner-container">
                <div class="row no-gutters">
                    @foreach ($lineageHighlights as $highlight)
                        <div class="col-lg-4">
                            <div class="cta-two__box">
                                <div class="cta-two__icon">
                                    {!! $iconSvg($highlight['icon'], 'living-svg-icon') !!}
                                </div>
                                <div class="cta-two__content">
                                    <h3>{{ $highlight['title'] }}</h3>
                                    <p>{{ $highlight['body'] }}</p>
                                    <a href="#lineage-story" class="thm-btn">Read Lineage</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="living-endline">
                    {{ data_get($lineage, 'feathers', 'The five tribes honored; the Ghost Feather holds the ancestor still returning.') }}
                    <br>
                    {{ data_get($lineage, 'endline', 'We Were Never Erased. We Were Replanted.') }}
                </div>
            </div>
        </div>
    </section>

    <section class="living-dark-section pt-0" id="carrier-pathway">
        <div class="container living-section-shell">
            <div class="living-section-heading">
                <span class="living-section-eyebrow">Carrier Pathway</span>
                <h2 class="living-section-heading__title">{{ data_get($pathway, 'title', 'Carrier Pathway') }}</h2>
                <p class="living-section-heading__copy">{{ data_get($pathway, 'intro', 'The lineage moves with intention -- Youth to Keeper to Witness -- each step recognized through ceremony, accountability, and protection.') }}</p>
            </div>
            <div class="row g-4 living-grid">
                @foreach ($pathwayCards as $card)
                    <div class="col-12 col-lg-4" id="{{ $card['id'] }}">
                        <article class="living-glass-card h-100 d-flex flex-column p-4 p-lg-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="living-pathway-step">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="living-pathway-icon">{!! $iconSvg($card['icon'], 'living-svg-icon') !!}</span>
                            </div>
                            <div class="living-card-media">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="img-fluid rounded-3">
                            </div>
                            <span class="living-card-badge">{{ $card['label'] }}</span>
                            <h3 class="living-card-title">{{ $card['title'] }}</h3>
                            @foreach ($card['body_lines'] as $line)
                                <p class="living-card-text">{{ $line }}</p>
                            @endforeach
                        </article>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                <a href="{{ data_get($hero, 'secondary_cta_url', '#contact-invitations') }}" class="btn living-btn living-btn-outline">
                    {{ data_get($hero, 'secondary_cta_label', 'Begin the Carrier Pathway') }}
                </a>
            </div>
        </div>
    </section>

    <section class="living-dark-section" id="media-merch">
        <div class="container living-section-shell">
            <div class="living-section-heading">
                <span class="living-section-eyebrow">Artifacts &amp; Access</span>
                <h2 class="living-section-heading__title">{{ data_get($mediaMerch, 'title', 'Media & Merch as Ceremonial Artifacts') }}</h2>
                <p class="living-section-heading__copy">{{ data_get($mediaMerch, 'intro', 'Music scores, apparel, and recordings are extensions of the Breath-line -- artifacts that carry ceremony into the everyday.') }}</p>
            </div>
            <div class="row g-4 living-grid">
                @foreach ($experienceCards as $card)
                    <div class="col-12 col-lg-4">
                        <article class="living-glass-card h-100 d-flex flex-column p-4 p-lg-5">
                            <div class="living-card-media">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="img-fluid rounded-3">
                            </div>
                            <span class="living-card-badge">{{ $card['tag_month'] }}</span>
                            <h3 class="living-card-title">{{ $card['title'] }}</h3>
                            <p class="living-card-text flex-grow-1">{{ $card['body'] }}</p>
                            <a href="{{ $card['cta_url'] }}" class="btn living-btn living-btn-primary mt-4 align-self-start">
                                {{ $card['cta_label'] }}
                            </a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="blog-one" id="contact-invitations">
        <div class="container">
            <div class="blog-one__top">
                <div class="block-title">
                    <p>Enter the Circle</p>
                    <h3>{{ data_get($contactSection, 'title', 'Contact & Invitations') }}</h3>
                </div>
            </div>
            <p class="living-section-copy">{{ data_get($contactSection, 'intro', 'Enter the circle through training, ceremony, and direct invitation.') }}</p>
            <div class="row">
                @foreach ($contactCards as $card)
                    <div class="col-lg-4">
                        <div class="blog-one__single living-contact-card">
                            <div class="blog-one__image">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}">
                                <div class="blog-one__date">{{ $card['label'] }}</div>
                            </div>
                            <div class="blog-one__content">
                                <h3><a href="{{ $card['cta_url'] }}">{{ $card['title'] }}</a></h3>
                                @foreach ($card['body_lines'] as $line)
                                    <p>{{ $line }}</p>
                                @endforeach
                                <a href="{{ $card['cta_url'] }}" class="blog-one__link">{{ $card['cta_label'] }}</a>
                                @if ($card['secondary_cta_label'] && $card['secondary_cta_url'])
                                    <a href="{{ $card['secondary_cta_url'] }}" class="blog-one__link">{{ $card['secondary_cta_label'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="living-dark-section living-certification-section" id="certification" style="background-image: url('{{ $heroImage }}');">
        <div class="container living-section-shell">
            <div class="living-section-heading">
                <span class="living-section-eyebrow">Ceremonial Record</span>
                <h2 class="living-section-heading__title">{{ data_get($certification, 'title', 'Printable Certification') }}</h2>
                <p class="living-section-heading__copy">{{ data_get($certification, 'intro', 'Static ceremonial document for carriers within the Five Feathers lineage.') }}</p>
            </div>
            <div class="living-certification-panel">
                <div class="living-paper-card p-4 p-lg-5">
                    @if ($certHeading)
                        <strong class="living-paper-title mb-3 d-block">{{ $certHeading }}</strong>
                    @endif
                    <p class="living-paper-body">{{ $certBody }}</p>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
                    <a href="{{ data_get($contactSection, 'general.support_url', route('living-archive.donate')) }}" class="btn living-btn living-btn-primary">
                        {{ data_get($contactSection, 'general.support_label', 'Offer Support') }}
                    </a>
                    <a href="{{ data_get($qr, 'cta_url', route('living-archive.donate')) }}" class="btn living-btn living-btn-outline">
                        {{ data_get($qr, 'cta_label', 'Open the QR Gateway') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="site-footer__upper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="footer-widget footer-widget__about">
                            <p>{{ $introText }}</p>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="footer-widget footer-widget__links">
                            <h3 class="footer-widget__title">Quick Link</h3>
                            <ul class="footer-widget__links-list list-unstyled">
                                @foreach ($quickLinks as $link)
                                    <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="footer-widget footer-widget__contact">
                            <h3 class="footer-widget__title">Contact</h3>
                            <p>{{ data_get($contactSection, 'general.title', 'Contact') }}</p>
                            <p>
                                <a href="{{ $phoneHref ? 'tel:' . $phoneHref : '#' }}">{{ $phoneText }}</a>
                            </p>
                            <p>
                                <a href="mailto:{{ data_get($contact, 'email', 'info@thomasalexanderthevoice.com') }}">
                                    {{ data_get($contact, 'email', 'info@thomasalexanderthevoice.com') }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="footer-widget footer-widget__open-hrs">
                            <h3 class="footer-widget__title">Ceremony</h3>
                            <p>
                                {{ data_get($page, 'header.subtitle', 'This is not a store. This is ceremony.') }}<br>
                                {{ data_get($lineage, 'endline', 'We Were Never Erased. We Were Replanted.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-footer__bottom">
            <div class="container">
                <div class="inner-container">
                    <p>&copy; Copyright {{ now()->year }} Thomas Alexander. All Rights Reserved</p>
                    <a href="#crest-home" class="site-footer__bottom-logo">
                        <img src="{{ $logoImage }}" alt="Living Archive">
                    </a>
                    <div class="site-footer__bottom-links">
                        <a href="{{ data_get($contactSection, 'general.support_url', route('living-archive.donate')) }}">
                            {{ data_get($contactSection, 'general.support_label', 'Offer Support') }}
                        </a>
                        <a href="{{ data_get($qr, 'cta_url', route('living-archive.donate')) }}">
                            {{ data_get($qr, 'cta_label', 'QR Access') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<div class="side-content__block">
    <div class="side-content__block-overlay custom-cursor__overlay">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>
    <div class="side-content__block-inner">
        <a href="#crest-home">
            <img src="{{ $logoImage }}" alt="Living Archive" width="143">
        </a>
        <div class="side-content__block-about">
            <h3 class="side-content__block__title">{{ data_get($lineage, 'title', 'About the Lineage') }}</h3>
            <p class="side-content__block-about__text">{{ \Illuminate\Support\Str::limit(strip_tags($introText), 220) }}</p>
            <a href="{{ data_get($hero, 'primary_cta_url', '#lineage-story') }}" class="thm-btn side-content__block-about__btn">
                {{ data_get($hero, 'primary_cta_label', 'Explore the Five Feathers Lineage') }}
            </a>
        </div>
        <hr class="side-content__block-line" />
        <div class="side-content__block-contact">
            <h3 class="side-content__block__title">{{ data_get($contactSection, 'title', 'Contact & Invitations') }}</h3>
            <ul class="side-content__block-contact__list">
                <li class="side-content__block-contact__list-item">
                    {!! $iconSvg('envelope', 'living-svg-icon living-side-icon') !!}
                    <a href="mailto:{{ data_get($contact, 'email', 'info@thomasalexanderthevoice.com') }}">
                        {{ data_get($contact, 'email', 'info@thomasalexanderthevoice.com') }}
                    </a>
                </li>
                <li class="side-content__block-contact__list-item">
                    {!! $iconSvg('phone', 'living-svg-icon living-side-icon') !!}
                    <a href="{{ $phoneHref ? 'tel:' . $phoneHref : '#' }}">{{ $phoneText }}</a>
                </li>
                <li class="side-content__block-contact__list-item">
                    {!! $iconSvg('qrcode', 'living-svg-icon living-side-icon') !!}
                    <a href="{{ data_get($qr, 'cta_url', route('living-archive.donate')) }}">{{ data_get($qr, 'cta_label', 'Open the QR Gateway') }}</a>
                </li>
            </ul>
        </div>
        <p class="side-content__block__text site-footer__copy-text">
            <a href="#crest-home">Living Archive</a> {!! $iconSvg('copyright', 'living-svg-icon living-side-icon') !!} {{ now()->year }} All Right Reserved
        </p>
    </div>
</div>

<div class="side-menu__block">
    <a href="#" class="side-menu__toggler side-menu__close-btn">{!! $iconSvg('times', 'living-svg-icon living-close-icon') !!}</a>
    <div class="side-menu__block-overlay custom-cursor__overlay">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>
    <div class="side-menu__block-inner">
        <a href="#crest-home" class="side-menu__logo">
            <img src="{{ $logoImage }}" alt="Living Archive" width="143">
        </a>
        <nav class="mobile-nav__container"></nav>
        <p class="side-menu__block__copy">(c) {{ now()->year }} <a href="#crest-home">Living Archive</a> - All rights reserved.</p>
        <div class="side-menu__social">
            <a href="#lineage-story" aria-label="Lineage Story">{!! $iconSvg('tree', 'living-svg-icon living-side-icon') !!}</a>
            <a href="#three-crests" aria-label="Three Crests">{!! $iconSvg('shield-alt', 'living-svg-icon living-side-icon') !!}</a>
            <a href="#carrier-pathway" aria-label="Carrier Pathway">{!! $iconSvg('feather-alt', 'living-svg-icon living-side-icon') !!}</a>
            <a href="#contact-invitations" aria-label="Contact">{!! $iconSvg('envelope', 'living-svg-icon living-side-icon') !!}</a>
        </div>
    </div>
</div>

<a href="#" data-target="html" class="scroll-to-target scroll-to-top" aria-label="Scroll to top">{!! $iconSvg('angle-up', 'living-svg-icon living-scroll-icon') !!}</a>
@endsection

@push('js')
    <script src="{{ asset('muzex/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/isotope.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/TweenMax.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/jquery.lettering.min.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/jquery.circleType.js') }}"></script>
    <script src="{{ asset('muzex/assets/js/theme.js') }}"></script>
@endpush
