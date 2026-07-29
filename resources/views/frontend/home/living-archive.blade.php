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
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('living-archieve-new-design/assets/css/style.css') }}">
    <style>
        body {
            --la-ink: #070706;
            --la-deep: #12100d;
            --la-brown: #2b1a10;
            --la-cream: #fff7e8;
            --la-paper: #f8eed9;
            --la-soft: #fdfbf4;
            --la-sand: #dfc89e;
            --la-gold: #d9a441;
            --la-gold-bright: #f1c76b;
            --la-copper: #b96f37;
            --la-teal: #0e6f66;
            --la-teal-bright: #18a398;
            --la-text: #211a14;
            --la-muted: #756b5c;
            --la-shadow: 0 28px 70px rgba(7, 7, 6, 0.16);
            --la-shadow-soft: 0 18px 45px rgba(7, 7, 6, 0.1);
            background: #f8eed9;
            color: #211a14;
            font-family: "Manrope", "Work Sans", Arial, sans-serif;
            overflow-x: hidden;
        }
        .living-archive-page {
            --la-ink: #070706;
            --la-deep: #12100d;
            --la-brown: #2b1a10;
            --la-cream: #fff7e8;
            --la-paper: #f8eed9;
            --la-soft: #fdfbf4;
            --la-sand: #dfc89e;
            --la-gold: #d9a441;
            --la-gold-bright: #f1c76b;
            --la-copper: #b96f37;
            --la-teal: #0e6f66;
            --la-teal-bright: #18a398;
            --la-text: #211a14;
            --la-muted: #756b5c;
            --la-shadow: 0 28px 70px rgba(7, 7, 6, 0.16);
            --la-shadow-soft: 0 18px 45px rgba(7, 7, 6, 0.1);
            background: var(--la-paper);
            color: var(--la-text);
            font-family: "Manrope", "Work Sans", Arial, sans-serif;
        }
        .living-archive-page h1,
        .living-archive-page h2,
        .living-archive-page h3,
        .living-archive-page h4,
        .living-archive-page h5,
        .living-archive-page h6,
        .living-archive-page .block-title h3,
        .living-archive-page .living-section-heading__title,
        .living-archive-page .living-card-title,
        .living-archive-page .dual-identity-title,
        .living-archive-page .dual-identity-name {
            font-family: "Cormorant Garamond", Georgia, serif;
            letter-spacing: -0.015em;
        }
        .living-archive-page .container {
            max-width: 1248px;
        }
        .living-archive-page section[id] {
            scroll-margin-top: 108px;
        }
        .living-archive-page img {
            max-width: 100%;
            height: auto;
        }
        .living-section-shell {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .living-grid > [class*="col-"] {
            display: flex;
        }
        .living-grid > [class*="col-"] > * {
            width: 100%;
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
        .living-archive-page .main-nav-one__home-three {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: 18px 0 0;
            background: transparent;
            border: 0;
            box-shadow: none;
        }
        .living-archive-page .main-nav-one__home-three .inner-container {
            min-height: 74px;
            padding: 10px 18px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 999px;
            background: rgba(18, 16, 13, 0.74);
            box-shadow: 0 18px 60px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }
        .living-archive-page .main-nav-one .logo-box img {
            max-height: 56px;
            width: auto;
        }
        .living-archive-page .main-nav__navigation-box > li {
            padding: 0;
        }
        .living-archive-page .main-nav__navigation-box > li > a {
            position: relative;
            padding: 0.8rem 0.7rem;
            color: rgba(255, 247, 232, 0.84);
            font-family: "Manrope", Arial, sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .living-archive-page .main-nav__navigation-box > li > a::after {
            content: "";
            position: absolute;
            left: 0.7rem;
            right: 0.7rem;
            bottom: 0.42rem;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--la-gold-bright), var(--la-copper));
            opacity: 0;
            transform: scaleX(0.45);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .living-archive-page .main-nav__navigation-box > li > a:hover,
        .living-archive-page .main-nav__navigation-box > li > a.active {
            color: var(--la-gold-bright);
            transform: translateY(-1px);
        }
        .living-archive-page .main-nav__navigation-box > li > a:hover::after,
        .living-archive-page .main-nav__navigation-box > li > a.active::after {
            opacity: 1;
            transform: scaleX(1);
        }
        .living-archive-page .sidemenu-icon,
        .living-archive-page .side-menu__toggler {
            color: var(--la-gold-bright);
        }
        .living-archive-page .main-nav-one__home-three .container {
            max-width: 1248px;
        }
        .living-archive-page .main-nav-one__home-three .inner-container,
        .living-archive-page .logo-box,
        .living-archive-page .main-nav__main-navigation,
        .living-archive-page .main-nav__right {
            display: flex;
            align-items: center;
        }
        .living-archive-page .main-nav-one__home-three .inner-container {
            justify-content: space-between;
            gap: 18px;
        }
        .living-archive-page .logo-box {
            flex: 0 0 auto;
            gap: 16px;
        }
        .living-archive-page .main-nav__main-navigation {
            flex: 1 1 auto;
            justify-content: center;
        }
        .living-archive-page .main-nav__navigation-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(12px, 2vw, 28px);
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .living-archive-page .main-nav__right {
            flex: 0 0 auto;
            justify-content: flex-end;
        }
        .living-archive-page .logo-box a,
        .living-archive-page .main-nav__right a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .living-archive-page .side-menu__toggler {
            text-decoration: none;
        }
        .living-archive-page .logo-box .side-menu__toggler {
            display: none;
        }
        .living-archive-page .preloader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: grid;
            place-items: center;
            background: var(--la-ink);
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .living-archive-page .preloader.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .living-archive-page .lds-ripple {
            position: relative;
            width: 76px;
            height: 76px;
        }
        .living-archive-page .lds-ripple div {
            position: absolute;
            border: 2px solid var(--la-gold-bright);
            border-radius: 50%;
            animation: livingRipple 1.2s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }
        .living-archive-page .lds-ripple div:nth-child(2) {
            animation-delay: -0.6s;
        }
        @keyframes livingRipple {
            0% {
                inset: 36px;
                opacity: 1;
            }
            100% {
                inset: 0;
                opacity: 0;
            }
        }
        .living-archive-page .living-hero-section {
            min-height: auto;
            padding: clamp(104px, 10vw, 126px) 0 clamp(62px, 7vw, 92px);
            background-position: center;
            background-size: cover;
            color: var(--la-cream);
        }
        .living-archive-page .living-hero-section::before {
            background:
                radial-gradient(circle at 76% 0%, rgba(241, 199, 107, 0.2), transparent 35%),
                radial-gradient(circle at 0% 82%, rgba(24, 163, 152, 0.18), transparent 33%),
                linear-gradient(135deg, rgba(7, 7, 6, 0.92), rgba(43, 26, 16, 0.86));
        }
        .living-archive-page .living-hero-section .container {
            min-height: auto;
            justify-content: flex-start;
        }
        .living-archive-page .living-hero-panel {
            max-width: 980px;
            margin: 0 auto;
            padding: clamp(28px, 5vw, 58px) !important;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 42px;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.24);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }
        .living-archive-page .living-section-eyebrow,
        .living-archive-page .living-card-badge,
        .living-archive-page .dual-identity-label,
        .living-archive-page .living-connected-pages__label,
        .living-archive-page .block-title p {
            color: var(--la-copper);
            font-family: "Manrope", Arial, sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .living-archive-page .living-hero-section .living-section-eyebrow {
            color: var(--la-gold-bright);
        }
        .living-archive-page .living-hero-crest {
            position: relative;
            display: inline-block;
        }
        .living-archive-page .living-hero-crest::before {
            content: "";
            position: absolute;
            inset: -28px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(241, 199, 107, 0.26), transparent 68%);
            filter: blur(3px);
        }
        .living-archive-page .living-hero-crest img {
            position: relative;
            max-width: 260px;
            border: 1px solid rgba(241, 199, 107, 0.45);
            border-radius: 28px;
            box-shadow: 0 24px 55px rgba(0, 0, 0, 0.24);
        }
        .living-archive-page .living-hero-title {
            color: var(--la-cream);
            font-size: clamp(3.4rem, 7vw, 6rem);
            line-height: 0.95;
        }
        .living-archive-page .living-hero-intro {
            max-width: 720px;
            color: rgba(255, 247, 232, 0.82);
            font-size: clamp(1.05rem, 1.7vw, 1.28rem);
            line-height: 1.6;
        }
        .living-archive-page .living-hero-subtitle {
            color: var(--la-gold-bright);
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(241, 199, 107, 0.35);
        }
        .living-archive-page .btn.living-btn,
        .living-archive-page .thm-btn,
        .living-archive-page .blog-one__link,
        .living-archive-page .living-connected-pages__link {
            border-radius: 999px;
            font-family: "Manrope", Arial, sans-serif;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: none;
            transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
        }
        .living-archive-page .btn.living-btn:hover,
        .living-archive-page .thm-btn:hover,
        .living-archive-page .living-connected-pages__link:hover {
            transform: translateY(-3px);
        }
        .living-archive-page .living-btn-primary,
        .living-archive-page .thm-btn {
            color: var(--la-ink);
            background: linear-gradient(135deg, var(--la-gold-bright), var(--la-copper));
            border: 0;
            box-shadow: 0 18px 38px rgba(185, 111, 55, 0.25);
        }
        .living-archive-page .living-btn-outline,
        .living-archive-page .blog-one__link,
        .living-archive-page .living-connected-pages__link {
            color: var(--la-cream);
            background: var(--la-deep);
            border: 0;
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.18);
        }
        .living-archive-page .living-ceremonial-intro {
            position: relative;
            z-index: 3;
            margin-top: -110px;
            padding: 0 0 80px;
            background: transparent;
        }
        .living-archive-page .living-ceremonial-intro__wrap,
        .living-archive-page .dual-identity-shell,
        .living-archive-page .living-connected-pages__card,
        .living-archive-page .about-three .row,
        .living-archive-page .cta-two__home-two .inner-container,
        .living-archive-page .contact-card-surface {
            border: 1px solid rgba(223, 200, 158, 0.7);
            border-radius: 34px;
            background: var(--la-soft);
            box-shadow: var(--la-shadow);
        }
        .living-archive-page .living-ceremonial-intro__wrap {
            max-width: 1120px;
            padding: clamp(28px, 5vw, 54px);
        }
        .living-archive-page .living-ceremonial-intro__title,
        .living-archive-page .living-section-heading__title,
        .living-archive-page .dual-identity-title {
            color: var(--la-text);
            font-size: clamp(2.5rem, 5vw, 4rem);
            line-height: 1.02;
        }
        .living-archive-page .living-ceremonial-intro__copy,
        .living-archive-page .living-section-heading__copy,
        .living-archive-page .living-section-copy,
        .living-archive-page .dual-identity-intro,
        .living-archive-page .dual-identity-text,
        .living-archive-page .living-connected-pages__copy,
        .living-archive-page .about-three .about-three__content,
        .living-archive-page .about-three .about-three__content > p,
        .living-archive-page .living-note-list li {
            color: var(--la-muted);
        }
        .living-archive-page .living-ceremonial-intro__media {
            border-color: rgba(223, 200, 158, 0.7);
            background: #fffaf0;
            box-shadow: var(--la-shadow-soft);
        }
        .living-archive-page .dual-identity-section,
        .living-archive-page .living-connected-pages,
        .living-archive-page .about-three,
        .living-archive-page .cta-two,
        .living-archive-page .blog-one {
            padding: 108px 0;
            background: var(--la-paper);
        }
        .living-archive-page .dual-identity-row {
            align-items: stretch;
        }
        .living-archive-page .dual-identity-portrait,
        .living-archive-page .living-card-media img,
        .living-archive-page .blog-one__image img,
        .living-archive-page .living-about-image img {
            border-radius: 28px;
            box-shadow: var(--la-shadow-soft);
        }
        .living-archive-page .dual-identity-name,
        .living-archive-page .living-card-title,
        .living-archive-page .living-connected-pages__title,
        .living-archive-page .cta-two__content h3,
        .living-archive-page .blog-one__content h3 a,
        .living-archive-page .block-title h3 {
            color: var(--la-text);
        }
        .living-archive-page .dual-identity-subtitle,
        .living-archive-page .living-card-declaration,
        .living-archive-page .about-three__highlight {
            color: var(--la-copper);
        }
        .living-archive-page .dual-identity-summary {
            color: var(--la-text);
            border-color: rgba(217, 164, 65, 0.35);
        }
        .living-archive-page .living-connected-pages__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 28px;
        }
        .living-archive-page .living-connected-pages {
            position: relative;
            overflow: hidden;
            padding: 96px 0;
            color: var(--la-cream);
            background:
                radial-gradient(circle at 14% 20%, rgba(217, 164, 65, 0.14), transparent 28%),
                radial-gradient(circle at 86% 18%, rgba(24, 163, 152, 0.12), transparent 32%),
                linear-gradient(135deg, #070706 0%, #11100d 52%, #07111b 100%);
        }
        .living-archive-page .living-connected-pages::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(180deg, rgba(255, 247, 232, 0.04), transparent 34%);
        }
        .living-archive-page .living-connected-pages__card,
        .living-archive-page .living-glass-card,
        .living-archive-page .cta-two__box,
        .living-archive-page .blog-one__single {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border: 1px solid rgba(223, 200, 158, 0.68);
            border-radius: 26px;
            background: var(--la-soft);
            box-shadow: var(--la-shadow-soft);
            transition: transform 0.26s ease, box-shadow 0.26s ease, border-color 0.26s ease;
        }
        .living-archive-page .living-connected-pages__grid {
            position: relative;
            z-index: 1;
        }
        .living-archive-page .living-connected-pages__card {
            min-height: 315px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: clamp(28px, 4vw, 42px);
            border-color: rgba(217, 164, 65, 0.32);
            background:
                linear-gradient(145deg, rgba(255, 247, 232, 0.07), rgba(255, 247, 232, 0.02)),
                rgba(10, 10, 8, 0.88);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.24);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }
        .living-archive-page .living-connected-pages__card:nth-child(2)::before {
            background: linear-gradient(90deg, var(--la-copper), var(--la-teal-bright));
        }
        .living-archive-page .living-connected-pages__label {
            color: var(--la-gold-bright);
        }
        .living-archive-page .living-connected-pages__title {
            color: var(--la-cream);
            font-size: clamp(2.25rem, 4vw, 3.2rem);
            line-height: 1;
        }
        .living-archive-page .living-connected-pages__copy {
            max-width: 620px;
            color: rgba(255, 247, 232, 0.78);
            font-size: 1.05rem;
            line-height: 1.7;
        }
        .living-archive-page .living-connected-pages__link {
            width: max-content;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            margin-top: 24px;
            padding: 13px 22px;
            color: var(--la-ink);
            background: linear-gradient(135deg, var(--la-gold-bright), var(--la-copper));
            box-shadow: 0 18px 38px rgba(185, 111, 55, 0.22);
        }
        .living-archive-page .living-connected-pages__card::before,
        .living-archive-page .living-glass-card::before,
        .living-archive-page .cta-two__box::before,
        .living-archive-page .blog-one__single::before {
            content: "";
            position: absolute;
            top: 0;
            left: 28px;
            right: 28px;
            height: 5px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--la-gold), var(--la-gold-bright));
        }
        .living-archive-page .living-connected-pages__card:hover,
        .living-archive-page .living-glass-card:hover,
        .living-archive-page .cta-two__box:hover,
        .living-archive-page .blog-one__single:hover {
            transform: translateY(-8px);
            box-shadow: 0 28px 70px rgba(7, 7, 6, 0.18);
        }
        .living-archive-page .living-hero-panel.living-glass-card {
            border-color: rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.1);
            color: var(--la-cream);
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.24);
        }
        .living-archive-page .living-hero-panel.living-glass-card::before {
            display: none;
        }
        .living-archive-page .living-hero-panel.living-glass-card:hover {
            transform: none;
        }
        .living-archive-page .living-dark-section,
        .living-archive-page .living-certification-section,
        .living-archive-page #media-merch {
            position: relative;
            overflow: hidden;
            padding: 110px 0;
            color: var(--la-cream);
            background: radial-gradient(circle at 90% 20%, rgba(217, 164, 65, 0.18), transparent 30%), linear-gradient(135deg, var(--la-ink), var(--la-brown));
        }
        .living-archive-page .living-dark-section .living-section-heading__title,
        .living-archive-page .living-dark-section .living-card-title,
        .living-archive-page .living-dark-section .living-section-heading__copy,
        .living-archive-page .living-dark-section .living-card-text,
        .living-archive-page .living-dark-section .living-card-declaration {
            color: var(--la-cream);
        }
        .living-archive-page .living-dark-section .living-glass-card,
        .living-archive-page #media-merch .living-glass-card {
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.08);
            color: var(--la-cream);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }
        .living-archive-page .living-dark-section .living-card-text,
        .living-archive-page #media-merch .living-card-text {
            color: rgba(255, 247, 232, 0.76);
        }
        .living-archive-page #carrier-pathway {
            color: var(--la-text);
            background: linear-gradient(180deg, var(--la-paper), #fff9ee);
        }
        .living-archive-page #carrier-pathway .living-section-heading__title,
        .living-archive-page #carrier-pathway .living-section-heading__copy,
        .living-archive-page #carrier-pathway .living-card-title,
        .living-archive-page #carrier-pathway .living-card-text {
            color: var(--la-text);
        }
        .living-archive-page .living-pathway-step {
            width: 86px;
            height: 86px;
            color: var(--la-cream);
            background: linear-gradient(135deg, var(--la-gold), var(--la-gold-bright));
            border: 0;
            box-shadow: 0 18px 35px rgba(217, 164, 65, 0.25);
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: 30px;
        }
        .living-archive-page .about-three .row {
            overflow: hidden;
            margin: 0;
        }
        .living-archive-page .living-about-image {
            background: linear-gradient(135deg, var(--la-brown), var(--la-teal));
        }
        .living-archive-page .cta-two__icon,
        .living-archive-page .living-pathway-icon,
        .living-archive-page .living-endline {
            color: var(--la-copper);
        }
        .living-archive-page .blog-one__top {
            margin-bottom: 14px;
        }
        .living-archive-page .blog-one .block-title {
            max-width: 720px;
            margin-bottom: 18px;
        }
        .living-archive-page .blog-one .living-section-copy {
            max-width: 720px;
            margin: 0 0 36px;
            color: var(--la-muted);
            line-height: 1.75;
        }
        .living-archive-page .blog-one__content p,
        .living-archive-page .cta-two__content p {
            color: var(--la-muted);
        }
        .living-archive-page .blog-one__image {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            margin-bottom: 22px;
        }
        .living-archive-page .blog-one__image img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
        }
        .living-archive-page .blog-one__date {
            background: linear-gradient(135deg, var(--la-gold-bright), var(--la-copper));
            color: var(--la-ink);
        }
        .living-archive-page .living-paper-card {
            border-color: rgba(223, 200, 158, 0.68);
            border-radius: 34px;
            background: var(--la-soft);
            box-shadow: var(--la-shadow);
        }
        .living-archive-page .site-footer {
            background: var(--la-ink);
        }
        .living-archive-page .site-footer__upper,
        .living-archive-page .site-footer__bottom {
            background: transparent;
        }
        .living-archive-page .site-footer p,
        .living-archive-page .site-footer a,
        .living-archive-page .footer-widget__title {
            color: rgba(255, 247, 232, 0.78);
        }
        .living-archive-page .site-footer {
            padding: 0;
        }
        .living-archive-page .site-footer__upper {
            padding: 72px 0 42px;
        }
        .living-archive-page .site-footer__bottom {
            border-top: 1px solid rgba(255, 247, 232, 0.1);
            padding: 24px 0;
        }
        .living-archive-page .site-footer__bottom .inner-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        .living-archive-page .footer-widget__links-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .living-archive-page .footer-widget__links-list li + li {
            margin-top: 10px;
        }
        .living-archive-page .site-footer__bottom-links {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }
        .side-content__block,
        .side-menu__block {
            position: fixed;
            inset: 0;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .side-content__block.is-open,
        .side-menu__block.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .side-content__block-overlay,
        .side-menu__block-overlay {
            position: absolute;
            inset: 0;
            background: rgba(7, 7, 6, 0.72);
        }
        .side-content__block-inner,
        .side-menu__block-inner {
            position: absolute;
            top: 0;
            right: 0;
            width: min(92vw, 420px);
            height: 100%;
            overflow-y: auto;
            padding: 34px;
            color: var(--la-cream);
            background: linear-gradient(180deg, #12100d, #070706);
            box-shadow: -24px 0 70px rgba(0, 0, 0, 0.34);
            transform: translateX(100%);
            transition: transform 0.25s ease;
        }
        .side-menu__block-inner {
            left: 0;
            right: auto;
            transform: translateX(-100%);
        }
        .side-content__block.is-open .side-content__block-inner,
        .side-menu__block.is-open .side-menu__block-inner {
            transform: translateX(0);
        }
        .side-menu__close-btn {
            position: absolute;
            top: 22px;
            right: 22px;
            z-index: 1;
            color: var(--la-gold-bright);
        }
        .side-content__block a,
        .side-menu__block a {
            color: var(--la-gold-bright);
            text-decoration: none;
        }
        .side-content__block__title,
        .side-menu__block__copy {
            color: var(--la-cream);
        }
        .side-content__block-contact__list {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .side-content__block-contact__list-item {
            display: flex;
            gap: 10px;
            margin: 12px 0;
            color: rgba(255, 247, 232, 0.78);
        }
        .side-menu__social {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .mobile-nav__container {
            margin-top: 24px;
        }
        .mobile-nav__container ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .mobile-nav__container li + li {
            margin-top: 10px;
        }
        .mobile-nav__container a {
            display: flex;
            align-items: center;
            min-height: 46px;
            padding: 10px 0;
            color: var(--la-cream);
            border-bottom: 1px solid rgba(255, 247, 232, 0.12);
            font-weight: 800;
        }
        .scroll-to-top {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 99;
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--la-ink);
            background: linear-gradient(135deg, var(--la-gold-bright), var(--la-copper));
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.24);
        }
        @media (max-width: 991.98px) {
            .living-archive-page .main-nav-one__home-three {
                padding-top: 10px;
            }
            .living-archive-page .main-nav-one__home-three .inner-container {
                border-radius: 28px;
                background: rgba(18, 16, 13, 0.84);
            }
            .living-archive-page .main-nav__main-navigation {
                display: none;
            }
            .living-archive-page .main-nav__right {
                display: none;
            }
            .living-archive-page .logo-box .side-menu__toggler {
                display: inline-flex;
            }
            .living-archive-page .living-hero-section {
                min-height: auto;
                padding-top: 94px;
                padding-bottom: 72px;
            }
            .living-archive-page .living-hero-section .container {
                min-height: auto;
            }
            .living-archive-page .living-connected-pages__grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 767.98px) {
            .living-archive-page .main-nav-one__home-three .inner-container {
                min-height: 64px;
                padding: 8px 14px;
            }
            .living-archive-page .main-nav-one .logo-box img {
                max-height: 46px;
            }
            .living-archive-page .living-hero-section {
                padding-top: 86px;
                padding-bottom: 58px;
            }
            .living-archive-page .living-hero-panel {
                border-radius: 32px;
                padding: 28px 18px !important;
            }
            .living-archive-page .living-hero-title {
                font-size: clamp(3rem, 14vw, 4.2rem);
                line-height: 0.96;
            }
            .living-archive-page .living-hero-crest img {
                max-width: min(72vw, 240px);
            }
            .living-archive-page .btn.living-btn,
            .living-archive-page .thm-btn,
            .living-archive-page .living-connected-pages__link {
                width: 100%;
                justify-content: center;
                text-align: center;
            }
            .living-archive-page .living-ceremonial-intro {
                margin-top: -86px;
                padding-bottom: 52px;
            }
            .living-archive-page .living-dark-section,
            .living-archive-page .living-certification-section,
            .living-archive-page #media-merch,
            .living-archive-page .dual-identity-section,
            .living-archive-page .living-connected-pages,
            .living-archive-page .about-three,
            .living-archive-page .cta-two,
            .living-archive-page .blog-one {
                padding: 72px 0;
            }
            .living-archive-page .living-pathway-step {
                width: 70px;
                height: 70px;
                font-size: 26px;
            }
            .living-archive-page .living-connected-pages__card {
                min-height: auto;
            }
            .living-archive-page .living-connected-pages__link {
                width: 100%;
            }
            .living-archive-page .site-footer__bottom .inner-container {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Final page QC polish: section-specific layout corrections. */
        .living-archive-page .living-ceremonial-intro {
            margin-top: 0;
            padding: clamp(82px, 8vw, 112px) 0;
        }
        .living-archive-page .living-ceremonial-intro__wrap {
            width: min(100%, 1120px);
            text-align: left;
        }
        .living-archive-page .living-ceremonial-intro__media {
            width: 100%;
            overflow: hidden;
        }
        .living-archive-page .living-ceremonial-intro__media img {
            width: 100%;
            max-height: 520px;
            object-fit: contain;
            object-position: center;
            background: #000;
        }
        .living-archive-page .dual-identity-section {
            color: var(--la-cream);
            background:
                radial-gradient(circle at 80% 18%, rgba(217, 164, 65, 0.12), transparent 30%),
                linear-gradient(135deg, var(--la-ink), #11100d 52%, #07111b);
        }
        .living-archive-page .dual-identity-shell {
            padding: clamp(30px, 5vw, 58px);
            border-color: rgba(217, 164, 65, 0.28);
            background:
                linear-gradient(145deg, rgba(255, 247, 232, 0.07), rgba(255, 247, 232, 0.02)),
                rgba(10, 10, 8, 0.9);
            color: var(--la-cream);
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.28);
        }
        .living-archive-page .dual-identity-heading {
            max-width: 880px;
            margin: 0 auto 44px;
            text-align: center;
        }
        .living-archive-page .dual-identity-row {
            display: grid;
            grid-template-columns: minmax(220px, 340px) minmax(0, 1fr);
            gap: clamp(28px, 5vw, 56px);
            align-items: center;
        }
        .living-archive-page .dual-identity-row--executive {
            grid-template-columns: minmax(0, 1fr) minmax(220px, 340px);
        }
        .living-archive-page .dual-identity-media:empty {
            display: none;
        }
        .living-archive-page .dual-identity-row:has(.dual-identity-media:empty) {
            grid-template-columns: 1fr;
        }
        .living-archive-page .dual-identity-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin: clamp(34px, 5vw, 54px) 0;
        }
        .living-archive-page .dual-identity-divider::before,
        .living-archive-page .dual-identity-divider::after {
            content: "";
            width: min(28vw, 220px);
            height: 1px;
            background: rgba(217, 164, 65, 0.42);
        }
        .living-archive-page .dual-identity-divider-mark {
            width: 11px;
            height: 11px;
            border: 1px solid rgba(217, 164, 65, 0.8);
            transform: rotate(45deg);
            background: rgba(217, 164, 65, 0.18);
        }
        .living-archive-page .dual-identity-section .dual-identity-title,
        .living-archive-page .dual-identity-section .dual-identity-name {
            color: var(--la-cream);
        }
        .living-archive-page .dual-identity-section .dual-identity-intro,
        .living-archive-page .dual-identity-section .dual-identity-text {
            color: rgba(255, 247, 232, 0.74);
            line-height: 1.75;
        }
        .living-archive-page .dual-identity-summary {
            margin-top: 42px;
            padding: 18px 20px;
            color: var(--la-cream);
            text-align: center;
            border-top: 1px solid rgba(217, 164, 65, 0.35);
            border-bottom: 1px solid rgba(217, 164, 65, 0.35);
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(1.25rem, 2.4vw, 1.65rem);
            letter-spacing: 0.04em;
        }
        .living-archive-page .about-three .row {
            align-items: stretch;
        }
        .living-archive-page .living-about-image {
            min-height: 560px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(28px, 5vw, 56px);
        }
        .living-archive-page .living-about-image img {
            width: min(100%, 560px);
            max-height: 560px;
            object-fit: contain;
            background: #000;
        }
        .living-archive-page .about-three__content {
            padding: clamp(36px, 5vw, 68px);
        }
        .living-archive-page .living-note-list {
            margin: 24px 0 0;
            padding-left: 1.2rem;
        }
        .living-archive-page .living-note-list li + li {
            margin-top: 10px;
        }
        .living-archive-page .cta-two__home-two .inner-container {
            padding: clamp(24px, 3vw, 34px);
        }
        .living-archive-page .cta-two__box {
            height: 100%;
        }
        .living-archive-page .living-endline {
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px solid rgba(217, 164, 65, 0.24);
            text-align: center;
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(1.25rem, 2vw, 1.55rem);
            line-height: 1.45;
        }
        .living-archive-page #carrier-pathway .living-section-heading {
            max-width: 920px;
            margin-bottom: 44px;
        }
        .living-archive-page .living-pathway-step {
            width: 72px;
            height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 28px;
        }
        .living-archive-page #carrier-pathway .living-card-media img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            background: #130802;
        }
        .living-archive-page .blog-one__date {
            position: absolute;
            left: 18px;
            bottom: 18px;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            min-height: 36px;
            padding: 8px 14px;
            border-radius: 999px;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.2);
        }
        .living-archive-page .blog-one__content {
            padding: 0;
        }
        .living-archive-page .blog-one__content h3 {
            margin-bottom: 14px;
        }
        .living-archive-page .blog-one__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            margin-top: 10px;
            padding: 10px 16px;
            color: var(--la-ink);
            background: linear-gradient(135deg, var(--la-gold-bright), var(--la-copper));
            box-shadow: 0 14px 30px rgba(185, 111, 55, 0.18);
        }
        .living-archive-page .blog-one__link + .blog-one__link {
            margin-left: 8px;
        }
        .living-archive-page .living-certification-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(7, 7, 6, 0.76), rgba(7, 7, 6, 0.64)),
                radial-gradient(circle at 18% 20%, rgba(217, 164, 65, 0.18), transparent 30%);
        }
        .living-archive-page .living-certification-section .container {
            position: relative;
            z-index: 1;
        }
        .living-archive-page .living-paper-card {
            color: #23170d;
        }
        .living-archive-page .living-paper-title {
            color: #8a621e;
        }
        .living-archive-page .living-paper-body {
            margin: 0;
            color: #2a2118;
            white-space: pre-line;
            line-height: 1.8;
            font-family: "Courier New", Courier, monospace;
            font-size: 0.98rem;
        }
        @media (max-width: 991.98px) {
            .living-archive-page .dual-identity-row,
            .living-archive-page .dual-identity-row--executive {
                grid-template-columns: 1fr;
            }
            .living-archive-page .dual-identity-row--executive .dual-identity-media {
                order: -1;
            }
            .living-archive-page .living-about-image {
                min-height: 360px;
            }
        }
        @media (max-width: 767.98px) {
            .living-archive-page .living-ceremonial-intro {
                margin-top: 0;
            }
            .living-archive-page .living-pathway-step {
                width: 64px;
                height: 64px;
                font-size: 24px;
            }
            .living-archive-page .blog-one__link,
            .living-archive-page .blog-one__link + .blog-one__link {
                width: 100%;
                margin-left: 0;
            }
        }
        @media (max-width: 575.98px) {
            .living-archive-page .living-hero-section {
                padding-top: 82px;
                padding-bottom: 48px;
            }
            .living-archive-page .living-ceremonial-intro__title,
            .living-archive-page .dual-identity-title,
            .living-archive-page .living-section-heading__title {
                font-size: clamp(2.35rem, 12vw, 3.25rem);
            }
            .living-archive-page .living-ceremonial-intro__media img {
                max-height: 360px;
            }
            .side-content__block-inner,
            .side-menu__block-inner {
                width: 100vw;
                padding: 28px 22px;
            }
        }

        /* Three-color premium finish: dark base, gold/copper accents, teal depth. */
        .living-archive-page,
        .living-archive-page .living-ceremonial-intro,
        .living-archive-page .dual-identity-section,
        .living-archive-page .about-three,
        .living-archive-page .cta-two,
        .living-archive-page #carrier-pathway,
        .living-archive-page .blog-one {
            background:
                radial-gradient(circle at 82% 12%, rgba(14, 111, 102, 0.16), transparent 34%),
                radial-gradient(circle at 10% 30%, rgba(217, 164, 65, 0.12), transparent 28%),
                linear-gradient(135deg, #070706 0%, #12100d 56%, #07111b 100%);
            color: var(--la-cream);
        }
        .living-archive-page .living-ceremonial-intro__wrap,
        .living-archive-page .dual-identity-shell,
        .living-archive-page .about-three .row,
        .living-archive-page .cta-two__home-two .inner-container,
        .living-archive-page .living-glass-card,
        .living-archive-page .cta-two__box,
        .living-archive-page .blog-one__single {
            border-color: rgba(217, 164, 65, 0.26);
            background:
                linear-gradient(145deg, rgba(255, 247, 232, 0.07), rgba(255, 247, 232, 0.02)),
                rgba(10, 10, 8, 0.9);
            color: var(--la-cream);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.28);
        }
        .living-archive-page .living-ceremonial-intro__title,
        .living-archive-page .living-section-heading__title,
        .living-archive-page .dual-identity-title,
        .living-archive-page .dual-identity-name,
        .living-archive-page .living-card-title,
        .living-archive-page .cta-two__content h3,
        .living-archive-page .blog-one__content h3 a,
        .living-archive-page .block-title h3 {
            color: var(--la-cream);
        }
        .living-archive-page .living-ceremonial-intro__copy,
        .living-archive-page .living-section-heading__copy,
        .living-archive-page .living-section-copy,
        .living-archive-page .dual-identity-intro,
        .living-archive-page .dual-identity-text,
        .living-archive-page .living-card-text,
        .living-archive-page .about-three .about-three__content,
        .living-archive-page .about-three .about-three__content > p,
        .living-archive-page .living-note-list li,
        .living-archive-page .blog-one__content p,
        .living-archive-page .cta-two__content p {
            color: rgba(255, 247, 232, 0.76);
        }
        .living-archive-page .living-ceremonial-intro__media {
            background: rgba(0, 0, 0, 0.88);
            border-color: rgba(217, 164, 65, 0.24);
        }
        .living-archive-page .living-certification-section .living-paper-card {
            background: var(--la-soft);
            color: #23170d;
        }
        .living-archive-page .living-certification-section .living-paper-title {
            color: #8a621e;
        }
        .living-archive-page .living-certification-section .living-paper-body {
            color: #2a2118;
        }
        .living-archive-page .site-footer__bottom-logo img {
            max-height: 92px;
            width: auto;
            opacity: 0.9;
        }
        .living-archive-page .site-footer__bottom .inner-container {
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
        }
        @media (max-width: 767.98px) {
            .living-archive-page .site-footer__bottom-logo img {
                max-height: 70px;
            }
        }

        /* Contrast and interaction QC for dark three-color finish. */
        .living-archive-page .thm-btn,
        .living-archive-page .blog-one__link,
        .living-archive-page .living-connected-pages__link,
        .living-archive-page .btn.living-btn {
            border: 1px solid rgba(217, 164, 65, 0.46);
            color: var(--la-gold-bright);
            background: rgba(7, 7, 6, 0.58);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.22);
        }
        .living-archive-page .thm-btn:hover,
        .living-archive-page .blog-one__link:hover,
        .living-archive-page .living-connected-pages__link:hover,
        .living-archive-page .btn.living-btn:hover {
            color: var(--la-ink);
            background: linear-gradient(135deg, var(--la-gold-bright), var(--la-copper));
            border-color: transparent;
        }
        .living-archive-page .cta-two__box .thm-btn {
            width: max-content;
            min-height: 40px;
            padding: 9px 16px;
            margin-top: 18px;
            font-size: 0.86rem;
        }
        .living-archive-page #carrier-pathway .living-section-heading__title,
        .living-archive-page #carrier-pathway .living-section-heading__copy,
        .living-archive-page #carrier-pathway .living-card-title,
        .living-archive-page #carrier-pathway .living-card-text {
            color: var(--la-cream);
        }
        .living-archive-page #carrier-pathway .living-section-heading__copy,
        .living-archive-page #carrier-pathway .living-card-text {
            color: rgba(255, 247, 232, 0.78);
        }
        .living-archive-page #carrier-pathway .living-card-badge,
        .living-archive-page .cta-two__box .living-svg-icon {
            color: var(--la-copper);
        }
        .living-archive-page #carrier-pathway .living-pathway-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--la-copper);
            background: rgba(217, 164, 65, 0.1);
            border: 1px solid rgba(217, 164, 65, 0.22);
        }
        .living-archive-page #carrier-pathway .living-glass-card {
            border-color: rgba(217, 164, 65, 0.28);
            background:
                linear-gradient(145deg, rgba(255, 247, 232, 0.08), rgba(14, 111, 102, 0.08)),
                rgba(10, 10, 8, 0.9);
        }
        .living-archive-page #carrier-pathway .living-card-media {
            overflow: hidden;
            border-radius: 18px;
            margin-bottom: 24px;
        }
        .living-archive-page .living-section-heading {
            max-width: 920px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        .living-archive-page .cta-two__box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        @media (max-width: 767.98px) {
            .living-archive-page .cta-two__box .thm-btn,
            .living-archive-page .thm-btn,
            .living-archive-page .blog-one__link {
                width: 100%;
                justify-content: center;
            }
        }

        .living-archive-page .living-cta-band {
            position: relative;
            z-index: 2;
            width: 100%;
            min-height: clamp(70px, 7vw, 100px);
            display: flex;
            align-items: center;
            overflow: hidden;
            color: var(--la-cream);
            background:
                linear-gradient(90deg, rgba(7, 7, 6, 0.96), rgba(43, 26, 16, 0.94), rgba(7, 17, 27, 0.96));
            border-top: 1px solid rgba(217, 164, 65, 0.22);
            border-bottom: 1px solid rgba(217, 164, 65, 0.22);
        }
        .living-archive-page .living-cta-band::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 18% 50%, rgba(217, 164, 65, 0.18), transparent 28%),
                radial-gradient(circle at 82% 50%, rgba(14, 111, 102, 0.2), transparent 30%);
            pointer-events: none;
        }
        .living-archive-page .living-cta-band__inner {
            position: relative;
            z-index: 1;
            width: min(100%, 1248px);
            min-height: clamp(70px, 7vw, 100px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin: 0 auto;
            padding: 12px clamp(18px, 5vw, 64px);
        }
        .living-archive-page .living-cta-band__copy {
            min-width: 0;
        }
        .living-archive-page .living-cta-band__kicker {
            display: block;
            margin-bottom: 4px;
            color: var(--la-gold-bright);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }
        .living-archive-page .living-cta-band__title {
            margin: 0;
            color: var(--la-cream);
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(1.35rem, 2.4vw, 2rem);
            line-height: 1.08;
        }
        .living-archive-page .living-cta-band__actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex: 0 0 auto;
        }
        .living-archive-page .living-cta-band__btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border: 1px solid rgba(217, 164, 65, 0.48);
            border-radius: 999px;
            color: var(--la-gold-bright);
            background: rgba(7, 7, 6, 0.62);
            font-size: 0.82rem;
            font-weight: 800;
            line-height: 1;
            text-decoration: none;
            white-space: nowrap;
            transition: transform 0.22s ease, background 0.22s ease, color 0.22s ease;
        }
        .living-archive-page .living-cta-band__btn--primary,
        .living-archive-page .living-cta-band__btn:hover {
            color: var(--la-ink);
            background: linear-gradient(135deg, var(--la-gold-bright), var(--la-copper));
            border-color: transparent;
        }
        .living-archive-page .living-cta-band__btn:hover {
            transform: translateY(-2px);
        }
        .living-archive-page .living-animate {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity 0.7s ease, transform 0.7s ease;
            will-change: opacity, transform;
        }
        .living-archive-page .living-animate.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .living-archive-page .living-float-soft {
            animation: livingFloatSoft 5.5s ease-in-out infinite;
        }
        @keyframes livingFloatSoft {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }
        @media (max-width: 767.98px) {
            .living-archive-page .living-cta-band__inner {
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 8px 12px;
                padding-top: 10px;
                padding-bottom: 10px;
            }
            .living-archive-page .living-cta-band__copy {
                flex: 1 1 180px;
            }
            .living-archive-page .living-cta-band__kicker {
                font-size: 0.62rem;
                letter-spacing: 0.12em;
            }
            .living-archive-page .living-cta-band__title {
                font-size: 1.12rem;
            }
            .living-archive-page .living-cta-band__actions {
                flex: 1 1 180px;
                gap: 8px;
            }
            .living-archive-page .living-cta-band__btn {
                flex: 1 1 0;
                min-height: 38px;
                padding: 8px 10px;
                font-size: 0.68rem;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .living-archive-page .living-animate {
                opacity: 1;
                transform: none;
                transition: none;
            }
            .living-archive-page .living-float-soft {
                animation: none;
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
    $dualIdentity = data_get($page, 'dual_identity', []);

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

    $dualCeremonialText = $splitParagraphs(data_get($dualIdentity, 'ceremonial.text'));
    $dualExecutiveText = $splitParagraphs(data_get($dualIdentity, 'executive.text'));
    $dualDividerColor = trim((string) data_get($dualIdentity, 'divider_color', '#C9A227'));
    if (!preg_match('/^(#[0-9a-fA-F]{3,8}|rgba?\([0-9.,\s%]+\)|[a-zA-Z]+)$/', $dualDividerColor)) {
        $dualDividerColor = '#C9A227';
    }

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

    $supportUrl = data_get($contactSection, 'general.support_url', route('living-archive.donate'));
    $supportLabel = data_get($contactSection, 'general.support_label', 'Offer Support');
    $qrUrl = data_get($qr, 'cta_url', route('living-archive.donate'));
    $qrLabel = data_get($qr, 'cta_label', 'Open the QR Gateway');
@endphp

<div class="preloader">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
</div>

<div class="page-wrapper living-archive-page">
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
                    <img src="{{ $primaryCrestImage }}" alt="Living Archive Ceremonial Crest" class="img-fluid rounded-4" loading="lazy">
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

    <section class="living-cta-band" aria-label="Support the Living Archive">
        <div class="living-cta-band__inner">
            <div class="living-cta-band__copy">
                <span class="living-cta-band__kicker">Support the Archive</span>
                <p class="living-cta-band__title">Help carry the Living Archive forward.</p>
            </div>
            <div class="living-cta-band__actions">
                <a class="living-cta-band__btn living-cta-band__btn--primary" href="{{ $supportUrl }}">{{ $supportLabel }}</a>
                <a class="living-cta-band__btn" href="{{ $qrUrl }}">{{ $qrLabel }}</a>
            </div>
        </div>
    </section>

    @if (data_get($dualIdentity, 'enabled', true))
        <section id="dual-identity" class="dual-identity-section">
            <div class="container living-section-shell">
                <div class="dual-identity-shell">
                    <div class="dual-identity-heading">
                        <span class="living-section-eyebrow">{{ data_get($dualIdentity, 'kicker', 'Dual Identity') }}</span>
                        <h2 class="dual-identity-title">{{ data_get($dualIdentity, 'title', 'Chief, Elder, Executive Artist') }}</h2>
                        <p class="dual-identity-intro">{{ data_get($dualIdentity, 'intro', 'A unified presentation of ceremonial stewardship and executive creative leadership.') }}</p>
                    </div>

                    <div class="dual-identity-row dual-identity-row--ceremonial">
                        <div class="dual-identity-media">
                            @if (data_get($dualIdentity, 'ceremonial.image'))
                                <img class="dual-identity-portrait" src="{{ data_get($dualIdentity, 'ceremonial.image') }}" alt="Ceremonial portrait of Thomas Alexander" loading="lazy">
                            @endif
                        </div>
                        <div class="dual-identity-content">
                            <span class="dual-identity-label">{{ data_get($dualIdentity, 'ceremonial.label', 'Ceremonial Identity') }}</span>
                            <h3 class="dual-identity-name">{{ data_get($dualIdentity, 'ceremonial.title', 'Chief & Elder - The Five Feathers Lineage Society') }}</h3>
                            <p class="dual-identity-subtitle">{{ data_get($dualIdentity, 'ceremonial.subtitle', 'Lineage Stewardship | Cultural Continuity | Living Archive Leadership') }}</p>
                            @foreach ($dualCeremonialText as $paragraph)
                                <p class="dual-identity-text">{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </div>

                    <div class="dual-identity-divider" style="color: {{ $dualDividerColor }};">
                        <span class="dual-identity-divider-mark"></span>
                    </div>

                    <div class="dual-identity-row dual-identity-row--executive">
                        <div class="dual-identity-content">
                            <span class="dual-identity-label">{{ data_get($dualIdentity, 'executive.label', 'Executive Identity') }}</span>
                            <h3 class="dual-identity-name">{{ data_get($dualIdentity, 'executive.title', 'Business & Executive Profile') }}</h3>
                            <p class="dual-identity-subtitle">{{ data_get($dualIdentity, 'executive.subtitle', 'Founder & Executive Director, The Five Feathers Publishing Company') }}</p>
                            @foreach ($dualExecutiveText as $paragraph)
                                <p class="dual-identity-text">{{ $paragraph }}</p>
                            @endforeach
                        </div>
                        <div class="dual-identity-media">
                            @if (data_get($dualIdentity, 'executive.image'))
                                <img class="dual-identity-portrait" src="{{ data_get($dualIdentity, 'executive.image') }}" alt="Executive portrait of Thomas Alexander" loading="lazy">
                            @endif
                        </div>
                    </div>

                    <div class="dual-identity-summary">
                        {{ data_get($dualIdentity, 'summary_bar', 'Chief & Elder • Executive Artist • Founder & Director • The Voice') }}
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="living-connected-pages" aria-label="Connected Living Archive pages">
        <div class="container living-section-shell">
            <div class="living-connected-pages__grid">
                <article class="living-connected-pages__card">
                    <span class="living-connected-pages__label">Connected Page</span>
                    <h2 class="living-connected-pages__title">Identity</h2>
                    <p class="living-connected-pages__copy">
                        Explore the ceremonial and executive identities carried through Thomas Alexander’s public and cultural work.
                    </p>
                    <a class="living-connected-pages__link" href="{{ url('/identity') }}">Open Identity</a>
                </article>
                <article class="living-connected-pages__card">
                    <span class="living-connected-pages__label">Connected Page</span>
                    <h2 class="living-connected-pages__title">The Five Feathers Lineage Society</h2>
                    <p class="living-connected-pages__copy">
                        Learn about the lineage-rooted cultural society preserving ancestral memory, stewardship, and continuity.
                    </p>
                    <a class="living-connected-pages__link" href="{{ url('/five-feathers-lineage-society') }}">Open Lineage Society</a>
                </article>
            </div>
        </div>
    </section>

    <section class="living-cta-band" aria-label="Join the Living Archive circle">
        <div class="living-cta-band__inner">
            <div class="living-cta-band__copy">
                <span class="living-cta-band__kicker">Enter the Circle</span>
                <p class="living-cta-band__title">Connect with the lineage, the archive, and the work.</p>
            </div>
            <div class="living-cta-band__actions">
                <a class="living-cta-band__btn living-cta-band__btn--primary" href="#contact-invitations">Contact</a>
                <a class="living-cta-band__btn" href="{{ $supportUrl }}">{{ $supportLabel }}</a>
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
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="img-fluid rounded-3" loading="lazy">
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
                        <img src="{{ $primaryCrestImage }}" alt="{{ $crestTitle ?: 'Ceremonial Crest' }}" loading="lazy">
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

    <section class="living-cta-band" aria-label="Explore the lineage">
        <div class="living-cta-band__inner">
            <div class="living-cta-band__copy">
                <span class="living-cta-band__kicker">Lineage Access</span>
                <p class="living-cta-band__title">Move from story into pathway and stewardship.</p>
            </div>
            <div class="living-cta-band__actions">
                <a class="living-cta-band__btn living-cta-band__btn--primary" href="#carrier-pathway">Carrier Pathway</a>
                <a class="living-cta-band__btn" href="{{ $qrUrl }}">{{ $qrLabel }}</a>
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
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="img-fluid rounded-3" loading="lazy">
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

    <section class="living-cta-band" aria-label="Support ceremonial artifacts">
        <div class="living-cta-band__inner">
            <div class="living-cta-band__copy">
                <span class="living-cta-band__kicker">Artifacts & Support</span>
                <p class="living-cta-band__title">Support the archive through ceremony, media, and access.</p>
            </div>
            <div class="living-cta-band__actions">
                <a class="living-cta-band__btn living-cta-band__btn--primary" href="#media-merch">Media & Merch</a>
                <a class="living-cta-band__btn" href="{{ $supportUrl }}">{{ $supportLabel }}</a>
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
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="img-fluid rounded-3" loading="lazy">
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
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" loading="lazy">
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

    <section class="living-cta-band" aria-label="Complete the ceremonial record">
        <div class="living-cta-band__inner">
            <div class="living-cta-band__copy">
                <span class="living-cta-band__kicker">Ceremonial Record</span>
                <p class="living-cta-band__title">Complete the journey with certification and support.</p>
            </div>
            <div class="living-cta-band__actions">
                <a class="living-cta-band__btn living-cta-band__btn--primary" href="#certification">Certification</a>
                <a class="living-cta-band__btn" href="{{ $supportUrl }}">{{ $supportLabel }}</a>
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
                    <a href="{{ $supportUrl }}" class="btn living-btn living-btn-primary">
                        {{ $supportLabel }}
                    </a>
                    <a href="{{ $qrUrl }}" class="btn living-btn living-btn-outline">
                        {{ $qrLabel }}
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
    <script>
        (function () {
            'use strict';

            var page = document.querySelector('.living-archive-page');
            if (!page) {
                return;
            }

            var navLinks = page.querySelectorAll('.main-nav__navigation-box a[href^="#"]');
            var sections = Array.prototype.slice.call(page.querySelectorAll('section[id]'));
            var preloader = document.querySelector('.preloader');
            var sideContent = document.querySelector('.side-content__block');
            var sideMenu = document.querySelector('.side-menu__block');
            var mobileNav = document.querySelector('.mobile-nav__container');
            var scrollTop = document.querySelector('.scroll-to-top');

            function openPanel(panel) {
                if (!panel) {
                    return;
                }

                panel.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }

            function closePanels() {
                if (sideContent) {
                    sideContent.classList.remove('is-open');
                }

                if (sideMenu) {
                    sideMenu.classList.remove('is-open');
                }

                document.body.style.overflow = '';
            }

            function setActiveNav() {
                var current = sections.length ? sections[0].id : '';

                sections.forEach(function (section) {
                    if (section.getBoundingClientRect().top <= 130) {
                        current = section.id;
                    }
                });

                navLinks.forEach(function (link) {
                    var target = link.getAttribute('href').replace('#', '');
                    link.classList.toggle('active', target === current);
                });
            }

            if (preloader) {
                window.addEventListener('load', function () {
                    preloader.classList.add('is-hidden');
                });

                setTimeout(function () {
                    preloader.classList.add('is-hidden');
                }, 900);
            }

            if (mobileNav && navLinks.length) {
                var list = document.createElement('ul');

                navLinks.forEach(function (link) {
                    var item = document.createElement('li');
                    var clone = link.cloneNode(true);

                    clone.addEventListener('click', closePanels);
                    item.appendChild(clone);
                    list.appendChild(item);
                });

                mobileNav.innerHTML = '';
                mobileNav.appendChild(list);
            }

            page.querySelectorAll('.side-content__toggler').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    openPanel(sideContent);
                });
            });

            page.querySelectorAll('.side-menu__toggler').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();

                    openPanel(sideMenu);
                });
            });

            document.querySelectorAll('.side-menu__close-btn').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    closePanels();
                });
            });

            document.querySelectorAll('.side-content__block-overlay, .side-menu__block-overlay').forEach(function (overlay) {
                overlay.addEventListener('click', closePanels);
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closePanels();
                }
            });

            if (scrollTop) {
                scrollTop.addEventListener('click', function (event) {
                    event.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            var animatedItems = Array.prototype.slice.call(page.querySelectorAll(
                'section:not(#crest-home), .living-glass-card, .living-connected-pages__card, .cta-two__box, .blog-one__single, .living-paper-card'
            ));

            animatedItems.forEach(function (item, index) {
                item.classList.add('living-animate');
                item.style.transitionDelay = Math.min(index % 3, 2) * 90 + 'ms';
            });

            page.querySelectorAll('.living-hero-crest, .living-ceremonial-intro__media').forEach(function (item) {
                item.classList.add('living-float-soft');
            });

            if ('IntersectionObserver' in window) {
                var revealObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    rootMargin: '0px 0px -10% 0px',
                    threshold: 0.12
                });

                animatedItems.forEach(function (item) {
                    revealObserver.observe(item);
                });
            } else {
                animatedItems.forEach(function (item) {
                    item.classList.add('is-visible');
                });
            }

            setActiveNav();
            window.addEventListener('scroll', setActiveNav, { passive: true });
        })();
    </script>
@endpush
