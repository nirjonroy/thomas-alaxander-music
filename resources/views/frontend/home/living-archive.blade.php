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
<style>
    :root {
        --living-gold: #f0b428;
        --living-gold-soft: #c9871f;
        --living-ink: #0b0f17;
        --living-panel: #0f0f0f;
        --living-text: #f4efe3;
    }
    body {
        background: #0b0f17;
        color: var(--living-text);
        overflow-x: hidden;
    }
    .as-mainwrapper {
        background: radial-gradient(circle at 20% 20%, rgba(240, 180, 40, 0.08), transparent 40%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05), transparent 45%),
            #0b0f17;
    }
    .living-archive-page {
        position: relative;
        padding-bottom: 60px;
    }
    .living-archive-page::before {
        content: "";
        position: fixed;
        inset: 0;
        background: url('{{ asset('frontend/living-archive/transparent-pattren.png') }}') center / 340px repeat;
        opacity: 0.08;
        pointer-events: none;
        z-index: 0;
    }
    .living-section {
        padding: 64px 0;
        position: relative;
        z-index: 1;
    }
    .living-section-title {
        text-align: center;
        margin-bottom: 28px;
    }
    .living-section-title h2 {
        margin: 0 0 10px;
        font-size: clamp(26px, 3vw, 38px);
        color: var(--living-text);
        letter-spacing: 0.04em;
    }
    .living-section-title p {
        margin: 0 auto;
        max-width: 720px;
        color: rgba(244, 239, 227, 0.78);
        font-size: 15px;
        line-height: 1.7;
    }
    .living-card {
        background: rgba(12, 16, 24, 0.85);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 24px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
        height: 100%;
    }
    .living-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(240, 180, 40, 0.12);
        color: var(--living-gold);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.18em;
    }
    .living-crest-nav {
        position: sticky;
        top: 0;
        z-index: 10;
        background: rgba(7, 10, 15, 0.9);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(8px);
    }
    .living-crest-nav .nav-inner {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 18px;
        padding: 16px 0;
    }
    .living-crest-nav .nav-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .living-crest-nav .nav-brand img {
        max-height: 52px;
        width: auto;
        filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.4));
    }
    .living-crest-nav .nav-brand span {
        font-size: 13px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(244, 239, 227, 0.75);
    }
    .crest-nav-links {
        display: flex;
        flex-wrap: nowrap;
        gap: 14px;
        justify-content: flex-start;
        align-items: center;
        min-width: 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        flex: 1 1 auto;
        margin-left: 24px;
    }
    .crest-nav-links a {
        color: rgba(244, 239, 227, 0.78);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        transition: color 0.2s ease;
        padding: 6px 10px;
        border-radius: 10px;
        min-width: 44px;
        min-height: 44px;
        justify-content: center;
        white-space: nowrap;
        flex: 0 0 auto;
    }
    .crest-nav-links a i {
        font-size: 16px;
        color: var(--living-gold);
    }
    .crest-nav-links a:hover {
        color: var(--living-gold);
    }
    .living-card.soft {
        box-shadow: none;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        padding: 18px;
        border-radius: 16px;
    }
    .living-card.soft .living-pill {
        margin-bottom: 10px;
    }
    .living-hero {
        padding: 60px 0 40px;
    }
    .living-hero-card {
        position: relative;
        border-radius: 26px;
        padding: 48px 44px;
        background: linear-gradient(120deg, rgba(12, 16, 24, 0.96), rgba(12, 16, 24, 0.72));
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
        text-align: center;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
    }
    .living-hero-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: var(--hero-image);
        background-size: cover;
        background-position: center;
        opacity: 0.28;
    }
    .living-hero-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 0%, rgba(240, 180, 40, 0.18), transparent 48%),
            linear-gradient(140deg, rgba(5, 8, 14, 0.9), rgba(10, 14, 22, 0.6));
    }
    .living-hero-content {
        position: relative;
        z-index: 1;
        max-width: 720px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: center;
    }
    .living-hero-kicker {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: rgba(244, 239, 227, 0.7);
    }
    .living-hero-crest img {
        max-width: 260px;
        width: 100%;
        filter: drop-shadow(0 14px 30px rgba(0, 0, 0, 0.45));
    }
    .living-affirmation {
        font-size: clamp(24px, 3vw, 40px);
        font-weight: 700;
        color: var(--living-gold);
        letter-spacing: 0.02em;
        margin: 0;
    }
    .living-hero-intro {
        font-size: 16px;
        color: rgba(244, 239, 227, 0.8);
        line-height: 1.7;
        margin: 0;
    }
    .living-primary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 26px;
        border-radius: 999px;
        background: linear-gradient(120deg, var(--living-gold), var(--living-gold-soft));
        color: #1b1409;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        text-decoration: none;
        font-size: 12px;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.35);
    }
    .living-secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 22px;
        border-radius: 999px;
        background: rgba(240, 180, 40, 0.12);
        color: var(--living-gold);
        border: 1px solid rgba(240, 180, 40, 0.45);
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        text-decoration: none;
        font-size: 11px;
    }
    .living-cta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
    }
    .lineage-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
        margin-top: 24px;
    }
    .lineage-crest {
        text-align: center;
        margin-bottom: 18px;
    }
    .lineage-crest img {
        max-width: 170px;
        width: 100%;
    }
    .lineage-crest-meta {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 10px;
        text-align: center;
    }
    .lineage-crest-title {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: rgba(244, 239, 227, 0.75);
    }
    .lineage-crest-caption {
        font-size: 13px;
        color: rgba(244, 239, 227, 0.7);
    }
    .crest-notes {
        margin-top: 18px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
    }
    .crest-note {
        background: rgba(10, 14, 22, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        padding: 14px;
        font-size: 14px;
        color: rgba(244, 239, 227, 0.78);
        line-height: 1.6;
    }
    .lineage-endline {
        text-align: center;
        margin-top: 26px;
        font-style: italic;
        color: rgba(244, 239, 227, 0.75);
        font-size: 16px;
    }
    .crest-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 22px;
    }
    .crest-card img {
        width: 100%;
        border-radius: 16px;
        margin-bottom: 16px;
    }
    .crest-card h3 {
        margin: 0 0 8px;
        font-size: 20px;
        color: var(--living-gold);
    }
    .crest-declaration {
        padding: 12px 14px;
        border-left: 3px solid rgba(240, 180, 40, 0.65);
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        font-style: italic;
        color: rgba(244, 239, 227, 0.9);
        margin: 12px 0 14px;
    }
    .pathway-flow {
        position: relative;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-top: 24px;
    }
    .pathway-flow::before {
        content: "";
        position: absolute;
        top: 40px;
        left: 8%;
        right: 8%;
        height: 2px;
        background: linear-gradient(90deg, rgba(240, 180, 40, 0.2), rgba(240, 180, 40, 0.8), rgba(240, 180, 40, 0.2));
    }
    .pathway-step {
        text-align: center;
        padding: 22px 18px 18px;
        position: relative;
    }
    .pathway-step .step-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid rgba(240, 180, 40, 0.35);
        background: rgba(240, 180, 40, 0.1);
        color: var(--living-gold);
        font-size: 11px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .pathway-step::before {
        content: "";
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--living-gold);
        position: absolute;
        top: 34px;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0 0 0 6px rgba(240, 180, 40, 0.2);
    }
    .pathway-step i {
        font-size: 22px;
        color: var(--living-gold);
        margin-bottom: 10px;
    }
    .pathway-step h4 {
        margin: 0 0 10px;
        color: var(--living-gold);
    }
    .media-merch-grid,
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 22px;
    }
    .media-merch-grid img,
    .contact-grid img {
        width: 100%;
        border-radius: 16px;
        margin-bottom: 14px;
    }
    .subtle-link {
        color: var(--living-gold);
        text-decoration: none;
        font-weight: 600;
    }
    .section-cta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }
    .section-cta-row a {
        flex: 1 1 auto;
    }
    .qr-bullets {
        margin: 12px auto 0;
        padding: 0;
        list-style: none;
        max-width: 520px;
        text-align: left;
    }
    .qr-bullets li {
        position: relative;
        padding-left: 18px;
        margin: 8px 0;
        color: rgba(244, 239, 227, 0.82);
        font-size: 14px;
        line-height: 1.6;
    }
    .qr-bullets li::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--living-gold);
        position: absolute;
        left: 0;
        top: 10px;
    }
    .certification-block {
        background: #f6f1e7;
        border-radius: 18px;
        border: 1px solid rgba(179, 140, 66, 0.6);
        padding: 32px;
        line-height: 1.8;
        font-size: 15px;
        color: #20160a;
        box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.6);
    }
    .certification-block strong {
        color: #9b6a1f;
    }
    .certification-lines {
        margin-top: 16px;
        font-family: "Courier New", Courier, monospace;
        font-size: 14px;
        color: #2c1c0b;
    }
    .contact-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 14px;
    }
    .contact-actions a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 999px;
        background: rgba(240, 180, 40, 0.15);
        color: var(--living-gold);
        border: 1px solid rgba(240, 180, 40, 0.45);
        text-decoration: none;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        font-size: 11px;
        min-height: 42px;
    }
    .living-footer {
        padding: 24px 0 40px;
        text-align: center;
        color: rgba(244, 239, 227, 0.7);
        font-size: 13px;
        position: relative;
        z-index: 1;
    }
    .living-footer a {
        color: var(--living-gold);
        text-decoration: none;
        font-weight: 600;
    }
    .living-footer a:hover {
        color: var(--living-gold-soft);
    }
    @media print {
        body {
            background: #fff !important;
            color: #111 !important;
        }
        .living-crest-nav,
        .living-hero,
        #media-merch,
        #qr-access,
        #contact-invitations {
            display: none !important;
        }
        .living-section {
            padding: 0 !important;
        }
        .certification-block {
            box-shadow: none !important;
            border: 1px solid #999 !important;
        }
    }
    @media (max-width: 991px) {
        .living-crest-nav .nav-inner {
            flex-direction: column;
            align-items: flex-start;
        }
        .crest-nav-links {
            justify-content: flex-start;
        }
        .living-hero-card {
            padding: 32px 24px;
        }
        .pathway-flow::before {
            left: 18%;
            right: 18%;
        }
    }
    @media (max-width: 768px) {
        .living-section {
            padding: 44px 0;
        }
        .living-crest-nav .nav-inner {
            padding: 8px 0;
            gap: 10px;
        }
        .living-crest-nav .nav-brand img {
            max-height: 38px;
        }
        .living-crest-nav .nav-brand span {
            font-size: 10px;
            letter-spacing: 0.12em;
        }
        .crest-nav-links {
            width: 100%;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 6px;
            gap: 12px;
        }
        .crest-nav-links a {
            min-width: 56px;
        }
        .crest-nav-links a i {
            font-size: 14px;
        }
        .living-section .container {
            padding-left: 16px;
            padding-right: 16px;
        }
        .living-hero {
            padding: 36px 0 24px;
        }
        .living-hero-card {
            padding: 26px 18px;
        }
        .living-hero-crest img {
            max-width: 210px;
        }
        .living-affirmation {
            font-size: 22px;
        }
        .living-hero-intro {
            font-size: 14px;
        }
        .living-cta-row {
            flex-direction: column;
            width: 100%;
        }
        .living-primary-btn,
        .living-secondary-btn {
            width: 100%;
        }
        .lineage-grid,
        .crest-grid,
        .pathway-flow,
        .media-merch-grid,
        .contact-grid {
            grid-template-columns: 1fr;
        }
        .crest-card img {
            max-height: 220px;
            object-fit: cover;
        }
        .pathway-flow::before {
            display: none;
        }
        .pathway-step::before {
            top: 26px;
        }
        .certification-block {
            padding: 22px;
            font-size: 14px;
        }
    }
    @media (max-width: 640px) {
        .crest-nav-links {
            gap: 10px;
        }
        .crest-nav-links a span {
            display: none;
        }
        .living-section .container {
            padding-left: 14px;
            padding-right: 14px;
        }
        .pathway-flow::before {
            display: none;
        }
        .pathway-step::before {
            top: 26px;
        }
    }
</style>
@endpush

@section('content')
@php
    $handoff = $handoff ?? [];
    $contact = data_get($page, 'contact', []);
    $crest = data_get($page, 'crest', []);
    $hero = data_get($page, 'hero', []);
    $lineage = data_get($page, 'lineage', []);
    $crests = data_get($page, 'crests', []);
    $pathway = data_get($page, 'pathway', []);
    $mediaMerch = data_get($page, 'media_merch', []);
    $qr = data_get($page, 'qr', []);
    $contactSection = data_get($page, 'contact_section', []);
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
@endphp

<div class="as-mainwrapper living-archive-page">
    <nav class="living-crest-nav">
        <div class="container nav-inner">
            <div class="nav-brand">
                <img src="{{ $logoImage }}" alt="Living Archive">
                <span>Living Archive</span>
            </div>
            <div class="crest-nav-links">
                <a href="#crest-home"><i class="fa fa-dharmachakra"></i><span>Home</span></a>
                <a href="#youth-crest"><i class="fa fa-owl"></i><span>Youth Crest</span></a>
                <a href="#keeper-crest"><i class="fa fa-feather"></i><span>Keeper Crest</span></a>
                <a href="#witness-crest"><i class="fa fa-shield-alt"></i><span>Witness Crest</span></a>
                <a href="#lineage-story"><i class="fa fa-tree"></i><span>Lineage Story</span></a>
                <a href="#carrier-pathway"><i class="fa fa-stream"></i><span>Carrier Pathway</span></a>
                <a href="#media-merch"><i class="fa fa-music"></i><span>Media & Merch</span></a>
                <a href="#qr-access"><i class="fa fa-qrcode"></i><span>QR Access</span></a>
                <a href="#contact-invitations"><i class="fa fa-envelope-open-text"></i><span>Contact</span></a>
            </div>
        </div>
    </nav>

    <section class="living-hero living-section" id="crest-home">
        <div class="container">
            <div class="living-hero-card" style="--hero-image: url('{{ $heroImage }}');">
                <div class="living-hero-content">
                    <span class="living-hero-kicker">{{ data_get($page, 'header.title', 'Living Archive') }}</span>
                    <div class="living-hero-crest">
                        <img src="{{ $primaryCrestImage }}" alt="Main Ceremonial Crest">
                    </div>
                    <p class="living-affirmation">{{ data_get($hero, 'affirmation', 'We Were Never Erased. We Were Replanted.') }}</p>
                    <span class="living-pill">{{ data_get($page, 'header.subtitle', 'This is not a store. This is ceremony.') }}</span>
                    <p class="living-hero-intro">{{ $introText }}</p>
                    <div class="living-cta-row">
                        <a href="{{ data_get($hero, 'primary_cta_url', '#lineage-story') }}" class="living-primary-btn">
                            {{ data_get($hero, 'primary_cta_label', 'Explore the Five Feathers Lineage') }}
                        </a>
                        <a href="{{ data_get($hero, 'secondary_cta_url', '#carrier-pathway') }}" class="living-secondary-btn">
                            {{ data_get($hero, 'secondary_cta_label', 'Begin the Carrier Pathway') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="lineage-story">
        <div class="container">
            <div class="living-section-title">
                <h2>{{ data_get($lineage, 'title', 'About the Lineage') }}</h2>
                <p>{{ data_get($lineage, 'intro', 'The Living Archive is a ceremonial record -- an ancestral ledger where memory, symbol, and song return to their rightful lineage.') }}</p>
            </div>
            <div class="living-card">
                <div class="lineage-crest">
                    <img src="{{ $primaryCrestImage }}" alt="Ceremonial Crest">
                    @if($crestTitle || $crestCaption)
                        <div class="lineage-crest-meta">
                            @if($crestTitle)
                                <span class="lineage-crest-title">{{ $crestTitle }}</span>
                            @endif
                            @if($crestCaption)
                                <span class="lineage-crest-caption">{{ $crestCaption }}</span>
                            @endif
                        </div>
                    @endif
                </div>
                <p>{{ data_get($crest, 'body_one', 'The Tree of Life stands at the center of the crest, holding the Breath-line across generations and returning each name to ceremony.') }}</p>
                @if(!empty($crestNotes))
                    <div class="crest-notes">
                        @foreach($crestNotes as $note)
                            <div class="crest-note">{!! nl2br(e($note)) !!}</div>
                        @endforeach
                    </div>
                @endif
                <div class="lineage-grid">
                    <div class="living-card soft">
                        <span class="living-pill">Tree of Life</span>
                        <p>{{ data_get($lineage, 'tree', 'Root and canopy unite the Breath-line, keeping the living memory in motion.') }}</p>
                    </div>
                    <div class="living-card soft">
                        <span class="living-pill">Ten Yamassee Clan Animals</span>
                        <p>{{ data_get($lineage, 'clan', 'Guardians of medicine, each one marking protection, vow, and teaching.') }}</p>
                    </div>
                    <div class="living-card soft">
                        <span class="living-pill">Three Ancestral Shields</span>
                        <p>{{ data_get($lineage, 'shields', 'Three shields hold sovereignty, continuity, and ceremonial protection.') }}</p>
                    </div>
                    <div class="living-card soft">
                        <span class="living-pill">Five Feathers + Ghost Feather</span>
                        <p>{{ data_get($lineage, 'feathers', 'The five tribes honored; the Ghost Feather holds the ancestor still returning.') }}</p>
                    </div>
                </div>
                <div class="lineage-endline">{{ data_get($lineage, 'endline', 'We Were Never Erased. We Were Replanted.') }}</div>
            </div>
        </div>
    </section>

    <section class="living-section" id="three-crests">
        <div class="container">
            <div class="living-section-title">
                <h2>{{ data_get($crests, 'title', 'The Three Crests') }}</h2>
                <p>{{ data_get($crests, 'intro', 'These are sacred displays -- static and enduring, held as testimony for the youth, the keepers, and the elders of the lineage.') }}</p>
            </div>
            <div class="crest-grid">
                <div class="living-card crest-card" id="youth-crest">
                    <img src="{{ $youthCrestImage }}" alt="{{ data_get($crests, 'youth.title', 'Youth Crest - The Listener') }}">
                    <h3>{{ data_get($crests, 'youth.title', 'Youth Crest - The Listener') }}</h3>
                    <p class="crest-declaration">{{ data_get($crests, 'youth.declaration', 'We perch where the roof gave way.') }}</p>
                    @foreach($splitParagraphs(data_get($crests, 'youth.body')) as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
                <div class="living-card crest-card" id="keeper-crest">
                    <img src="{{ $keeperCrestImage }}" alt="{{ data_get($crests, 'keeper.title', 'Keeper Crest - The Bearer') }}">
                    <h3>{{ data_get($crests, 'keeper.title', 'Keeper Crest - The Bearer') }}</h3>
                    <p class="crest-declaration">{{ data_get($crests, 'keeper.declaration', 'As the eagle, I did not blink, for I saw and see it all.') }}</p>
                    @foreach($splitParagraphs(data_get($crests, 'keeper.body')) as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
                <div class="living-card crest-card" id="witness-crest">
                    <img src="{{ $witnessCrestImage }}" alt="{{ data_get($crests, 'witness.title', 'Witness Crest - The Elder') }}">
                    <h3>{{ data_get($crests, 'witness.title', 'Witness Crest - The Elder') }}</h3>
                    <p class="crest-declaration">{{ data_get($crests, 'witness.declaration', 'We kept the fire when the world went dark.') }}</p>
                    @foreach($splitParagraphs(data_get($crests, 'witness.body')) as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="carrier-pathway">
        <div class="container">
            <div class="living-section-title">
                <h2>{{ data_get($pathway, 'title', 'Carrier Pathway') }}</h2>
                <p>{{ data_get($pathway, 'intro', 'The lineage moves with intention -- Youth to Keeper to Witness -- each step recognized through ceremony, accountability, and protection.') }}</p>
            </div>
            <div class="pathway-flow">
                @foreach(data_get($pathway, 'steps', []) as $index => $step)
                    <div class="living-card pathway-step">
                        <div class="step-badge">Step {{ $index + 1 }}</div>
                        <i class="fa {{ data_get($step, 'icon', 'fa-circle') }}"></i>
                        <h4>{{ data_get($step, 'title') }}</h4>
                        @foreach($splitParagraphs(data_get($step, 'body')) as $line)
                            <p>{{ $line }}</p>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="living-section" id="media-merch">
        <div class="container">
            <div class="living-section-title">
                <h2>{{ data_get($mediaMerch, 'title', 'Media & Merch as Ceremonial Artifacts') }}</h2>
                <p>{{ data_get($mediaMerch, 'intro', 'Music scores, apparel, and recordings are extensions of the Breath-line -- artifacts that carry ceremony into the everyday.') }}</p>
            </div>
            <div class="media-merch-grid">
                <div class="living-card">
                    <span class="living-pill">{{ data_get($mediaMerch, 'merch.title', 'Merch Crest') }}</span>
                    <img src="{{ data_get($mediaMerch, 'merch.image', $secondaryCrestImage) }}" alt="{{ data_get($mediaMerch, 'merch.title', 'Merch Crest') }}">
                    <h3>{{ data_get($mediaMerch, 'merch.title', 'Merch Crest') }}</h3>
                    <p>{{ data_get($mediaMerch, 'merch.body', 'Apparel, scores, and ceremonial items are lineage extensions -- worn and shared to keep the crest visible.') }}</p>
                    <div class="section-cta-row">
                        <a href="{{ data_get($mediaMerch, 'merch.cta_url', route('front.shop')) }}" class="living-primary-btn">
                            {{ data_get($mediaMerch, 'merch.cta_label', 'Shop Ceremonial Artifacts') }}
                        </a>
                        <a href="{{ data_get($mediaMerch, 'merch.secondary_cta_url', data_get($mediaMerch, 'merch.cta_url', route('front.shop'))) }}" class="living-secondary-btn">
                            {{ data_get($mediaMerch, 'merch.secondary_cta_label', 'View Scores & Items') }}
                        </a>
                    </div>
                </div>
                <div class="living-card">
                    <span class="living-pill">{{ data_get($mediaMerch, 'qr.title', 'QR Crest') }}</span>
                    <img src="{{ data_get($mediaMerch, 'qr.image', $qrCrestImage) }}" alt="{{ data_get($mediaMerch, 'qr.title', 'QR Crest') }}">
                    <h3>{{ data_get($mediaMerch, 'qr.title', 'QR Crest') }}</h3>
                    <p>{{ data_get($mediaMerch, 'qr.body', "The QR Crest is a digital gateway -- a quiet entry into the archive's living record.") }}</p>
                    <div class="section-cta-row">
                        <a href="{{ data_get($mediaMerch, 'qr.cta_url', '#qr-access') }}" class="living-primary-btn">
                            {{ data_get($mediaMerch, 'qr.cta_label', 'Open the QR Gateway') }}
                        </a>
                        <a href="{{ data_get($mediaMerch, 'qr.secondary_cta_url', route('living-archive.donate')) }}" class="living-secondary-btn">
                            {{ data_get($mediaMerch, 'qr.secondary_cta_label', 'Donate') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="qr-access">
        <div class="container">
            <div class="living-section-title">
                <h2>{{ data_get($qr, 'title', 'QR Access') }}</h2>
                <p>{{ data_get($qr, 'intro', 'The QR Crest offers a direct ceremonial passage -- a digital doorway into the lineage archive.') }}</p>
            </div>
            <div class="living-card text-center">
                <img src="{{ data_get($qr, 'image', $qrCrestImage) }}" alt="QR Crest" style="max-width: 320px; margin: 0 auto 18px;">
                <p style="font-size: 16px;">{{ data_get($page, 'header.qr_intro', 'Scan to enter the Living Archive.') }}</p>
                <ul class="qr-bullets">
                    <li>Direct entry to ceremonial materials and lineage references.</li>
                    <li>Mobile-friendly access point for supporters and carriers.</li>
                    <li>Gateway experience that preserves the Breath-line in digital form.</li>
                </ul>
                <a href="{{ data_get($qr, 'cta_url', route('living-archive.donate')) }}" class="living-primary-btn" style="margin-top: 10px;">
                    {{ data_get($qr, 'cta_label', 'Open the QR Gateway') }}
                </a>
            </div>
        </div>
    </section>

    <section class="living-section" id="contact-invitations">
        <div class="container">
            <div class="living-section-title">
                <h2>{{ data_get($contactSection, 'title', 'Contact & Invitations') }}</h2>
                <p>{{ data_get($contactSection, 'intro', 'Enter the circle through training, ceremony, and direct invitation.') }}</p>
            </div>
            <div class="contact-grid">
                <div class="living-card">
                    <span class="living-pill">{{ data_get($contactSection, 'training.title', 'Training Invitation') }}</span>
                    <p>{{ data_get($contactSection, 'training.body', 'Receive training in the Five Feathers lineage and learn the responsibilities of ceremonial care.') }}</p>
                    <div class="contact-actions">
                        <a href="{{ data_get($contactSection, 'training.cta_url', 'mailto:' . data_get($contact, 'email', 'info@thomasalexanderthevoice.com')) }}">
                            {{ data_get($contactSection, 'training.cta_label', 'Request Training') }}
                        </a>
                    </div>
                </div>
                <div class="living-card">
                    <span class="living-pill">{{ data_get($contactSection, 'events.title', 'Ceremonial Events') }}</span>
                    <p>{{ data_get($contactSection, 'events.body', 'Join ceremonial gatherings that affirm the Breath-line and honor the crest as living memory.') }}</p>
                    <div class="contact-actions">
                        <a href="{{ data_get($contactSection, 'events.cta_url', route('living-archive.donate')) }}">
                            {{ data_get($contactSection, 'events.cta_label', 'See Ceremonial Calendar') }}
                        </a>
                    </div>
                </div>
                <div class="living-card">
                    <span class="living-pill">{{ data_get($contactSection, 'general.title', 'Contact') }}</span>
                    @foreach($splitParagraphs($generalContactBody) as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                    <div class="contact-actions">
                        <a href="{{ data_get($contactSection, 'general.cta_url', 'mailto:' . data_get($contact, 'email', 'info@thomasalexanderthevoice.com')) }}">
                            {{ data_get($contactSection, 'general.cta_label', 'Email the Archive') }}
                        </a>
                        <a href="{{ data_get($contactSection, 'general.support_url', route('living-archive.donate')) }}">
                            {{ data_get($contactSection, 'general.support_label', 'Offer Support') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="certification">
        <div class="container">
            <div class="living-section-title">
                <h2>{{ data_get($certification, 'title', 'Printable Certification') }}</h2>
                <p>{{ data_get($certification, 'intro', 'Static ceremonial document for carriers within the Five Feathers lineage.') }}</p>
            </div>
            <div class="certification-block">
                @if($certHeading)
                    <strong>{{ $certHeading }}</strong><br>
                @endif
                {!! nl2br(e($certBody)) !!}
            </div>
        </div>
    </section>

    <footer class="living-footer">
        @copyright Thomas Alexander. Develop by
        <a href="https://nirjonroy.com" target="_blank" rel="noopener">Nirjon roy</a>.
    </footer>
</div>
@endsection

@push('js')
<script>
    (function ($) {
        'use strict';
        $('.crest-nav-links a').on('click', function (e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                var navHeight = $('.living-crest-nav').outerHeight() || 80;
                $('html, body').stop().animate({ scrollTop: target.offset().top - navHeight - 10 }, 650);
            }
        });
    })(jQuery);
</script>
@endpush
