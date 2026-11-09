@extends('frontend.layouts.living-archive')

@php
    $siteName = siteInfo()->site_name ?? siteInfo()->website_name ?? config('app.name', 'Thomas Alexander');
    $title = $setting->meta_title ?: 'Companions Along the Journey | Living Archive';
    $descSource = $setting->meta_description ?: ($setting->opening_teaching ?: 'Companions Along the Journey — Chief & Elder Wisdom.');
    $desc = \Illuminate\Support\Str::limit(strip_tags($descSource), 180);
    $canonical = route('front.living-archive.companions');
    $ogImage = $setting->og_image ? (str_starts_with($setting->og_image, 'http') ? $setting->og_image : asset($setting->og_image)) : null;
    $ogImageAlt = $setting->og_image_alt ?: $title;

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Living Archive',
                'item' => route('front.home.living-archive'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Companions Along the Journey',
                'item' => $canonical,
            ],
        ],
    ];

    $contentSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'headline' => $setting->entrance_title ?: 'Companions Along the Journey',
        'description' => $desc,
        'url' => $canonical,
        'image' => $ogImage,
        'publisher' => [
            '@type' => 'Organization',
            'name' => $siteName,
        ],
    ];

    $schemaGraph = array_values(array_filter([$breadcrumbSchema, $contentSchema]));

    $renderText = function (?string $content) {
        if ($content === null || trim($content) === '') {
            return '';
        }
        if ($content !== strip_tags($content)) {
            return clean($content);
        }
        return nl2br(e($content));
    };
@endphp

@section('title', $title)

@section('seos')
    @if ($isPreview)
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="title" content="{{ $title }}">
        <meta name="description" content="{{ $desc }}">
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:type" content="article">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $desc }}">
        <meta property="og:url" content="{{ $canonical }}">
        @if ($ogImage)
            <meta property="og:image" content="{{ $ogImage }}">
            <meta property="og:image:secure_url" content="{{ $ogImage }}">
            <meta property="og:image:alt" content="{{ $ogImageAlt }}">
        @endif
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $desc }}">
        <meta name="twitter:url" content="{{ $canonical }}">
        @if ($ogImage)
            <meta name="twitter:image" content="{{ $ogImage }}">
        @endif
        <script type="application/ld+json">{!! json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Palette: Deep Obsidian, Midnight Charcoal, Antique Gold, Warm Ivory */
            --archive-bg: #07090e;
            --archive-bg-alt: #0a0e16;
            --archive-surface: #0e131d;
            --archive-surface-soft: rgba(14, 19, 29, 0.65);
            --archive-cream: #faf5eb;
            --archive-ivory: #f2ece0;
            --archive-parchment: #e6dcce;
            --archive-muted: rgba(242, 236, 224, 0.70);
            --archive-faint: rgba(242, 236, 224, 0.40);
            --archive-hairline: rgba(242, 236, 224, 0.10);
            --archive-gold: #cfa851;
            --archive-gold-bright: #f4d37c;
            --archive-gold-faint: rgba(207, 168, 81, 0.20);
            --archive-gold-hairline: rgba(207, 168, 81, 0.24);
            --archive-copper: #b86e39;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--archive-bg);
            color: var(--archive-cream);
            font-family: "Manrope", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.85;
            letter-spacing: -0.005em;
        }

        /* Ambient Museum Atmosphere */
        .ceremonial-page {
            min-height: 100vh;
            background:
                radial-gradient(ellipse 80% 40% at 50% 0%, rgba(207, 168, 81, 0.07) 0%, transparent 60%),
                radial-gradient(circle 600px at 90% 25%, rgba(14, 111, 102, 0.05) 0%, transparent 50%),
                radial-gradient(circle 650px at 10% 60%, rgba(184, 110, 57, 0.05) 0%, transparent 50%),
                linear-gradient(180deg, #07090e 0%, #090d14 25%, #0a0e16 50%, #070a10 75%, #05070b 100%);
            color: var(--archive-cream);
            position: relative;
        }

        /* Editorial Shell Constraints */
        .editorial-shell {
            width: min(100%, 1240px);
            margin: 0 auto;
            padding: clamp(24px, 4.5vw, 60px) clamp(20px, 4vw, 44px) clamp(80px, 10vw, 140px);
        }

        /* ==========================================================================
           Admin Preview Bar & Publication Diagnostics (ADMIN ONLY)
           Sleek, Compact, Non-intrusive Toolbar
           ========================================================================== */
        .preview-readiness-bar {
            background: linear-gradient(180deg, #16130e 0%, #100d08 100%);
            border-bottom: 1px solid var(--archive-gold);
            color: var(--archive-cream);
            font-size: 13px;
            padding: 10px clamp(14px, 3vw, 28px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.85);
            position: sticky;
            top: 0;
            z-index: 1050;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .preview-readiness-bar__inner {
            width: min(100%, 1280px);
            margin: 0 auto;
        }
        .preview-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .preview-toolbar__left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .preview-toolbar__right {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .preview-badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(244, 211, 124, 0.12);
            border: 1px solid var(--archive-gold-bright);
            color: var(--archive-gold-bright);
        }
        .preview-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            letter-spacing: 0.04em;
        }
        .preview-pill--success {
            background: rgba(40, 167, 69, 0.18);
            border: 1px solid #28a745;
            color: #75dd8b;
        }
        .preview-pill--warning {
            background: rgba(255, 193, 7, 0.16);
            border: 1px solid #ffc107;
            color: #ffe082;
        }
        .preview-pill--danger {
            background: rgba(220, 53, 69, 0.18);
            border: 1px solid #dc3545;
            color: #ff8591;
        }
        .preview-note-draft {
            font-size: 11px;
            color: rgba(255, 224, 130, 0.9);
            font-style: italic;
        }
        .preview-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(207, 168, 81, 0.12);
            border: 1px solid rgba(207, 168, 81, 0.35);
            color: var(--archive-gold-bright);
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            user-select: none;
        }
        .preview-action-btn:hover {
            background: rgba(207, 168, 81, 0.24);
            color: #fff;
            border-color: var(--archive-gold-bright);
            text-decoration: none;
        }
        .preview-readiness-details {
            margin-top: 8px;
        }
        .preview-details-toggle {
            cursor: pointer;
            padding: 5px 12px;
            font-size: 11px;
            font-weight: 700;
            color: var(--archive-gold-bright);
            background: rgba(207, 168, 81, 0.10);
            border: 1px solid rgba(207, 168, 81, 0.28);
            border-radius: 6px;
            display: inline-block;
            user-select: none;
            transition: all 0.2s ease;
        }
        .preview-details-toggle:hover {
            background: rgba(207, 168, 81, 0.20);
            color: #fff;
        }
        .preview-diagnostics-drawer {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(207, 168, 81, 0.2);
        }
        .preview-summary-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            font-size: 12px;
            color: rgba(250, 245, 234, 0.88);
            margin: 0 0 10px;
            padding: 0;
            list-style: none;
        }
        .preview-summary-list li strong {
            color: var(--archive-gold-bright);
        }
        .preview-readiness-checklist {
            padding: 12px 14px;
            background: rgba(0, 0, 0, 0.55);
            border: 1px solid rgba(207, 168, 81, 0.22);
            border-radius: 8px;
            font-size: 12px;
            max-height: 260px;
            overflow-y: auto;
        }
        .preview-checklist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 10px;
            margin-top: 8px;
        }
        .preview-checklist-item {
            padding: 8px 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.03);
            border-left: 3px solid #6c757d;
        }
        .preview-checklist-item--ready {
            border-left-color: #28a745;
        }
        .preview-checklist-item--blocked {
            border-left-color: #dc3545;
        }
        .preview-checklist-item strong {
            display: block;
            color: var(--archive-cream);
            margin-bottom: 2px;
        }
        .preview-checklist-item span {
            color: rgba(250, 245, 234, 0.7);
            font-size: 11px;
            line-height: 1.4;
            display: block;
        }
        .preview-missing-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            margin: 12px 0;
            border-radius: 6px;
            background: rgba(207, 168, 81, 0.08);
            border: 1px dashed rgba(207, 168, 81, 0.40);
            color: var(--archive-gold-bright);
            font-size: 12px;
            font-style: italic;
            line-height: 1.45;
            max-width: 100%;
        }
        .preview-missing-badge--crest {
            margin: 16px auto;
            display: table;
            text-align: center;
        }

        /* ==========================================================================
           Breadcrumbs Navigation
           ========================================================================== */
        .archive-breadcrumbs {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: clamp(32px, 5vw, 56px);
            color: var(--archive-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .archive-breadcrumbs a {
            color: var(--archive-gold);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .archive-breadcrumbs a:hover {
            color: var(--archive-gold-bright);
        }
        .archive-breadcrumbs__divider {
            color: var(--archive-faint);
            font-size: 10px;
        }
        .archive-breadcrumbs [aria-current="page"] {
            color: var(--archive-cream);
            opacity: 0.85;
        }

        /* ==========================================================================
           Living Crest Presentation
           ========================================================================== */
        .ceremonial-crest-wrap {
            text-align: center;
            margin: 0 auto clamp(24px, 4vw, 44px);
        }
        .ceremonial-crest {
            max-width: clamp(140px, 16vw, 200px);
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.70));
            display: inline-block;
        }
        .ceremonial-crest--closing {
            max-width: clamp(150px, 18vw, 220px);
        }

        /* ==========================================================================
           Hero & Ceremonial Entrance
           ========================================================================== */
        .ceremonial-entrance {
            text-align: center;
            margin-bottom: clamp(70px, 10vw, 130px);
        }
        .ceremonial-entrance__label {
            font-family: "Manrope", sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: var(--archive-gold-bright);
            margin: 0 auto 16px;
            display: block;
        }
        .ceremonial-entrance__title {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(2.5rem, 5.5vw, 4.6rem);
            font-weight: 600;
            line-height: 1.12;
            letter-spacing: 0.02em;
            color: var(--archive-cream);
            margin: 0 auto 16px;
            max-width: 980px;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.7);
        }
        .ceremonial-entrance__subtitle {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-style: italic;
            font-size: clamp(1.35rem, 2.4vw, 1.95rem);
            color: var(--archive-gold);
            margin: 0 auto clamp(32px, 5vw, 56px);
            letter-spacing: 0.02em;
            line-height: 1.4;
        }
        .ceremonial-entrance__teaching {
            width: min(100%, 780px);
            margin: 0 auto;
            text-align: left;
            font-family: "Manrope", sans-serif;
            font-size: clamp(1.05rem, 1.4vw, 1.2rem);
            line-height: 2.0;
            color: var(--archive-ivory);
            padding: 0 clamp(8px, 2vw, 24px);
        }
        .ceremonial-entrance__teaching p {
            margin-bottom: 1.5em;
        }
        .ceremonial-entrance__teaching p:last-child {
            margin-bottom: 0;
        }

        /* Editorial Dividers */
        .editorial-divider {
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--archive-gold-hairline), transparent);
            margin: clamp(70px, 10vw, 130px) auto;
            width: min(100%, 540px);
        }

        /* ==========================================================================
           The Eleven Canonical Companion Chapters
           Spacious Two-Column Editorial Layout
           ========================================================================== */
        .companion-chapter {
            margin-bottom: clamp(90px, 13vw, 160px);
            padding-bottom: clamp(60px, 9vw, 110px);
            border-bottom: 1px solid var(--archive-hairline);
        }
        .companion-chapter:last-of-type {
            border-bottom: none;
            margin-bottom: clamp(70px, 10vw, 120px);
        }

        .companion-grid {
            display: grid;
            grid-template-columns: minmax(460px, 560px) 1fr;
            gap: clamp(40px, 5vw, 76px);
            align-items: start;
        }

        /* Artwork Column: Visual Centerpiece */
        .companion-artwork-col {
            position: sticky;
            top: 70px;
        }
        .companion-figure {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .companion-img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.70);
            background: #020306;
            object-fit: contain;
        }

        /* Narrative Column: Text & Teachings */
        .companion-narrative-col {
            min-width: 0; /* Prevents grid blowout */
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .companion-sequence-label {
            font-family: "Manrope", sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: var(--archive-gold-bright);
            display: block;
        }

        .companion-name {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(2.6rem, 4.2vw, 3.8rem);
            font-weight: 600;
            letter-spacing: 0.02em;
            color: var(--archive-cream);
            margin: 0;
            line-height: 1.15;
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.5);
        }

        .companion-themes {
            font-family: "Manrope", sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: var(--archive-gold);
            line-height: 1.65;
            margin: 0;
        }

        /* Expanded Teaching */
        .companion-teaching {
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--archive-hairline);
        }
        .companion-section-title {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: 1.45rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            color: var(--archive-gold);
            margin: 0 0 12px;
        }
        .companion-prose {
            font-size: 1.04rem;
            line-height: 1.95;
            color: var(--archive-ivory);
        }
        .companion-prose p {
            margin-bottom: 1.35em;
        }
        .companion-prose p:last-child {
            margin-bottom: 0;
        }

        /* What We Are Asked to Remember (Distinctive Contemplative Panel) */
        .companion-remember {
            margin-top: 8px;
            padding: 20px 24px;
            background: linear-gradient(135deg, rgba(12, 17, 26, 0.90) 0%, rgba(16, 22, 33, 0.80) 100%);
            border-left: 3px solid var(--archive-gold);
            border-radius: 0 8px 8px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }
        .companion-remember .companion-section-title {
            color: var(--archive-gold-bright);
            font-size: 1.45rem;
            margin-bottom: 10px;
        }
        .companion-remember .companion-prose {
            font-style: italic;
            color: var(--archive-parchment);
            font-size: 1.05rem;
            line-height: 1.9;
        }

        /* Five Feathers Companion Branding Block */
        .companion-branding {
            margin-top: 16px;
            padding-top: 20px;
            border-top: 1px solid var(--archive-hairline);
        }
        .companion-branding__text {
            font-family: "Manrope", sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--archive-muted);
            line-height: 1.85;
            margin: 0;
            opacity: 0.85;
        }

        /* ==========================================================================
           Narrative Chapters: Regeneration & The Witnesses
           ========================================================================== */
        .narrative-chapter {
            margin-bottom: clamp(80px, 11vw, 140px);
        }
        .narrative-header {
            text-align: center;
            margin-bottom: clamp(32px, 5vw, 56px);
        }
        .narrative-title {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(2.4rem, 4.5vw, 3.6rem);
            font-weight: 600;
            color: var(--archive-cream);
            letter-spacing: 0.02em;
            margin: 0 0 12px;
            line-height: 1.15;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.65);
        }
        .narrative-subtitle {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-style: italic;
            font-size: clamp(1.25rem, 2.2vw, 1.75rem);
            color: var(--archive-gold);
            margin: 0;
            line-height: 1.4;
        }
        .editorial-prose {
            width: min(100%, 780px);
            margin: 0 auto;
            font-size: 1.06rem;
            line-height: 2.0;
            color: var(--archive-ivory);
        }
        .editorial-prose p {
            margin-bottom: 1.4em;
        }
        .editorial-prose p:last-child {
            margin-bottom: 0;
        }

        /* Regeneration Artwork */
        .regeneration-artwork {
            max-width: 600px;
            margin: 0 auto clamp(28px, 4vw, 44px);
            text-align: center;
        }
        .regeneration-artwork img {
            width: 100%;
            height: auto;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.65);
        }

        /* The Witnesses Invocation */
        .witnesses-invocation {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(1.6rem, 2.8vw, 2.3rem);
            font-style: italic;
            line-height: 1.7;
            text-align: center;
            margin: 0 auto clamp(40px, 6vw, 64px);
            color: var(--archive-gold-bright);
            letter-spacing: 0.04em;
            max-width: 780px;
        }

        /* The Six Elemental Stations Grid */
        .witnesses-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: clamp(24px, 3.5vw, 40px);
            max-width: 1040px;
            margin: 0 auto;
        }
        .witness-station {
            padding: clamp(24px, 3.5vw, 36px);
            background: linear-gradient(135deg, rgba(12, 16, 24, 0.85) 0%, rgba(15, 21, 31, 0.70) 100%);
            border: 1px solid var(--archive-hairline);
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.40);
            transition: border-color 0.3s ease;
        }
        .witness-station:hover {
            border-color: var(--archive-gold-hairline);
        }
        .witness-station--full {
            grid-column: 1 / -1;
        }
        .witness-station__title {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: 1.55rem;
            font-weight: 600;
            color: var(--archive-gold-bright);
            letter-spacing: 0.02em;
            margin: 0 0 12px;
        }
        .witness-station__body {
            font-size: 1.02rem;
            line-height: 1.95;
            color: var(--archive-ivory);
            margin: 0;
        }

        /* ==========================================================================
           Closing Flow (Strict Required Sequence)
           ========================================================================== */
        .ceremonial-closing-flow {
            text-align: center;
            margin: clamp(70px, 10vw, 130px) auto clamp(40px, 6vw, 80px);
            width: min(100%, 860px);
        }
        .ceremonial-closing__new-beginning {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(1.6rem, 3.0vw, 2.3rem);
            font-style: italic;
            line-height: 1.6;
            color: var(--archive-gold-bright);
            margin-bottom: clamp(36px, 5vw, 60px);
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.5);
        }
        .ceremonial-crest-wrap--closing {
            margin: clamp(36px, 5vw, 60px) auto;
        }
        .ceremonial-closing__statement {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(2.0rem, 3.8vw, 2.9rem);
            font-weight: 600;
            line-height: 1.4;
            color: var(--archive-cream);
            margin-bottom: clamp(32px, 4.5vw, 52px);
            letter-spacing: 0.03em;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.7);
        }
        .ceremonial-closing__refrain {
            font-family: "Manrope", sans-serif;
            font-size: clamp(1.15rem, 1.8vw, 1.5rem);
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--archive-gold);
            margin-bottom: 0;
            text-shadow: 0 2px 12px rgba(207, 168, 81, 0.35);
        }
        .ceremonial-conclusion-space {
            height: clamp(80px, 11vw, 140px);
        }

        /* Quiet Return Action */
        .archive-actions {
            text-align: center;
            margin-top: 28px;
        }
        .archive-action {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            border-radius: 999px;
            border: 1px solid var(--archive-gold-hairline);
            color: var(--archive-muted);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-decoration: none;
            transition: all 0.3s ease;
            background: rgba(14, 19, 29, 0.65);
        }
        .archive-action:hover {
            color: var(--archive-gold-bright);
            border-color: var(--archive-gold-bright);
            background: rgba(14, 19, 29, 0.95);
            text-decoration: none;
            transform: translateY(-2px);
        }
        .archive-action:focus-visible {
            outline: 3px solid rgba(244, 211, 124, 0.7);
            outline-offset: 4px;
        }

        /* ==========================================================================
           Living Archive Quiet Footer Variant
           ========================================================================== */
        .archive-footer {
            border-top: 1px solid rgba(207, 168, 81, 0.14);
            background: #040609;
            margin-top: clamp(70px, 10vw, 120px);
        }
        .archive-footer__inner {
            width: min(100%, 1240px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 0 auto;
            padding: 28px 20px;
            color: rgba(250, 245, 234, 0.65);
            font-size: 13px;
        }
        .archive-footer__inner p {
            margin: 0;
        }
        .archive-footer__links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .archive-footer__links a {
            color: rgba(250, 245, 234, 0.65);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .archive-footer__links a:hover {
            color: var(--archive-gold-bright);
        }

        /* ==========================================================================
           Responsive Layout Refinements
           ========================================================================== */
        @media (max-width: 991.98px) {
            .companion-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }
            .companion-artwork-col {
                position: static;
                max-width: 620px;
                margin: 0 auto;
            }
            .witnesses-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 767.98px) {
            .archive-footer__inner {
                flex-direction: column;
                text-align: center;
                gap: 14px;
            }
            .archive-footer__links {
                justify-content: center;
            }
            .preview-summary-list {
                gap: 8px 14px;
            }
            .editorial-shell {
                padding: 24px 18px 60px;
            }
        }

        /* ==========================================================================
           Accessibility & Reduced Motion
           ========================================================================== */
        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }
            *,
            *::before,
            *::after {
                transition: none !important;
                animation: none !important;
            }
        }

        /* Final editorial composition: restrained archive treatment, scoped to this page. */
        .ceremonial-page .editorial-shell {
            width: min(100%, 1320px);
            padding: clamp(28px, 5vw, 72px) clamp(20px, 4vw, 56px) clamp(76px, 9vw, 128px);
        }
        .ceremonial-page .ceremonial-entrance {
            display: grid;
            grid-template-columns: minmax(160px, 0.32fr) minmax(0, 1fr);
            align-items: center;
            gap: clamp(28px, 5vw, 76px);
            margin: clamp(22px, 4vw, 54px) 0 clamp(54px, 7vw, 88px);
            text-align: left;
        }
        .ceremonial-page .ceremonial-entrance__identity {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 150px;
        }
        .ceremonial-page .ceremonial-crest-wrap {
            margin: 0;
            max-width: 230px;
        }
        .ceremonial-page .ceremonial-crest {
            max-height: 230px;
            width: auto;
        }
        .ceremonial-page .ceremonial-entrance__content {
            min-width: 0;
        }
        .ceremonial-page .ceremonial-entrance__label {
            margin: 0 0 10px;
        }
        .ceremonial-page .ceremonial-entrance__title {
            max-width: 900px;
            margin: 0 0 14px;
            font-size: clamp(2.8rem, 5.4vw, 5.3rem);
            line-height: 0.94;
        }
        .ceremonial-page .ceremonial-entrance__subtitle {
            margin: 0 0 22px;
            font-size: clamp(1.28rem, 2.2vw, 1.8rem);
        }
        .ceremonial-page .ceremonial-entrance__teaching {
            width: min(100%, 780px);
            margin: 0;
            padding: 0;
            font-size: 1.08rem;
            line-height: 1.85;
        }
        .ceremonial-page .editorial-divider {
            width: 100%;
            margin: 0 0 clamp(58px, 8vw, 100px);
            background: var(--archive-hairline);
        }
        .ceremonial-page .companion-chapter {
            margin-bottom: clamp(68px, 9vw, 118px);
            padding-bottom: clamp(60px, 8vw, 96px);
        }
        .ceremonial-page .companion-grid {
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
            gap: clamp(32px, 5vw, 82px);
            align-items: center;
        }
        .ceremonial-page .companion-chapter:nth-child(even) .companion-artwork-col {
            order: 2;
        }
        .ceremonial-page .companion-artwork-col {
            position: static;
        }
        .ceremonial-page .companion-img {
            max-height: none;
            border-radius: 2px;
            box-shadow: 0 26px 72px rgba(0, 0, 0, 0.52);
        }
        .ceremonial-page .companion-narrative-col {
            gap: 16px;
        }
        .ceremonial-page .companion-name {
            font-size: clamp(2.7rem, 4.5vw, 4.25rem);
            line-height: 0.98;
        }
        .ceremonial-page .companion-themes {
            font-size: 0.95rem;
            line-height: 1.65;
        }
        .ceremonial-page .companion-prose,
        .ceremonial-page .editorial-prose {
            font-size: 1.08rem;
            line-height: 1.82;
        }
        .ceremonial-page .companion-remember {
            padding: 20px 22px;
            border: 0;
            border-left: 2px solid var(--archive-gold);
            border-radius: 0;
            box-shadow: none;
            background: rgba(255, 255, 255, 0.035);
        }
        .ceremonial-page .narrative-chapter {
            margin-bottom: clamp(64px, 8vw, 104px);
        }
        .ceremonial-page .narrative-header {
            margin-bottom: clamp(26px, 4vw, 42px);
            text-align: left;
        }
        .ceremonial-page .editorial-prose {
            width: min(100%, 850px);
            margin: 0;
        }
        .ceremonial-page .preview-readiness-bar {
            position: static;
            padding: 8px clamp(14px, 3vw, 28px);
            box-shadow: none;
        }
        @media (max-width: 767.98px) {
            .ceremonial-page .ceremonial-entrance,
            .ceremonial-page .companion-grid {
                grid-template-columns: 1fr;
            }
            .ceremonial-page .ceremonial-entrance {
                gap: 20px;
            }
            .ceremonial-page .ceremonial-entrance__identity {
                justify-content: flex-start;
                min-height: 0;
            }
            .ceremonial-page .ceremonial-crest-wrap {
                max-width: 150px;
            }
            .ceremonial-page .ceremonial-crest {
                max-height: 150px;
            }
            .ceremonial-page .companion-chapter:nth-child(even) .companion-artwork-col {
                order: 0;
            }
            .ceremonial-page .companion-img {
                width: 100%;
            }
            .ceremonial-page .preview-toolbar,
            .ceremonial-page .preview-toolbar__left,
            .ceremonial-page .preview-toolbar__right {
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
<div class="ceremonial-page">

    @if ($isPreview && $readiness)
        <!-- ==========================================================================
             Admin Preview Bar (Collapsible, Non-intrusive, Authenticated Only)
             ========================================================================== -->
        <aside class="preview-readiness-bar" aria-label="Admin Preview Readiness Diagnostics">
            <div class="preview-readiness-bar__inner">
                <div class="preview-toolbar">
                    <div class="preview-toolbar__left">
                        <span class="preview-badge-status">
                            Protected Admin Preview (noindex, nofollow)
                        </span>
                        <span class="preview-pill {{ $readiness['page_active'] ? 'preview-pill--success' : 'preview-pill--warning' }}">
                            Page Status: {{ $readiness['page_active'] ? 'Active' : 'Inactive (Draft)' }}
                        </span>
                        <span class="preview-pill {{ $readiness['is_ready'] ? 'preview-pill--success' : 'preview-pill--danger' }}">
                            {{ $readiness['is_ready'] ? 'Ready for Publication' : 'Publication Blocked (Incomplete Content)' }}
                        </span>
                        <span class="preview-note-draft">Public URL is currently unavailable because this page is an unpublished draft.</span>
                    </div>

                    <div class="preview-toolbar__right">
                        <button type="button" class="preview-action-btn" id="copyPublicUrlBtn" onclick="if(navigator.clipboard){navigator.clipboard.writeText('{{ route('front.living-archive.companions') }}'); this.querySelector('.btn-label').textContent='Copied'; setTimeout(()=>{this.querySelector('.btn-label').textContent='Copy Public URL'},2500);}" title="Copy public URL (/living-archive/companions-along-the-journey)">
                            <span class="btn-label">Copy Public URL</span>
                        </button>
                        <a href="{{ route('admin.companions-journey.settings') }}" class="preview-action-btn" target="_blank" title="Open Page Settings">
                            Settings
                        </a>
                        @if (app()->environment(['local', 'testing']))
                            <a href="{{ route('admin.companions-journey.preview-as-public') }}" class="preview-action-btn" target="_blank" title="Open the toolbar-free local design preview">
                                View as Public
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Collapsible Diagnostics Drawer -->
                <details class="preview-readiness-details mt-2">
                    <summary class="preview-details-toggle">
                        View Mandatory Publication Checklist ({{ $readiness['missing_item_count'] }} tasks remaining)
                    </summary>
                    <div class="preview-diagnostics-drawer">
                        <ul class="preview-summary-list">
                            <li>
                                Canonical Sequence:
                                <strong>{{ $readiness['canonical_sequence_valid'] ? '11 Valid (Wolf to Otter)' : 'Invalid / Incomplete' }}</strong>
                            </li>
                            <li>
                                Opening Crest:
                                <strong>{{ $readiness['missing_opening_crest'] ? 'Missing' : 'Uploaded' }}</strong>
                            </li>
                            <li>
                                Closing Crest:
                                <strong>{{ $readiness['missing_closing_crest'] ? 'Missing' : 'Uploaded' }}</strong>
                            </li>
                            <li>
                                Missing Artworks:
                                <strong>{{ $readiness['missing_artworks_count'] }} / {{ $readiness['total_companions'] }}</strong>
                            </li>
                            <li>
                                Missing Teachings:
                                <strong>{{ $readiness['missing_teachings_count'] }} / {{ $readiness['total_companions'] }}</strong>
                            </li>
                            <li>
                                Missing Remembrance:
                                <strong>{{ $readiness['missing_remember_count'] }} / {{ $readiness['total_companions'] }}</strong>
                            </li>
                            <li>
                                Inactive Companions:
                                <strong>{{ $readiness['inactive_companions_count'] }} / {{ $readiness['total_companions'] }}</strong>
                            </li>
                        </ul>

                        @if (! $readiness['is_ready'])
                            <div class="preview-readiness-checklist">
                                @if (! empty($readiness['global_missing']))
                                    <div class="mb-3">
                                        <strong style="color: var(--archive-gold-bright);">Global Settings & Crests:</strong>
                                        <ul class="mt-1 mb-0 ps-3">
                                            @foreach ($readiness['global_missing'] as $globalIssue)
                                                <li style="color: #f4d37c;">{{ $globalIssue }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <strong style="color: var(--archive-gold-bright);">The Eleven Canonical Companions:</strong>
                                <div class="preview-checklist-grid">
                                    @foreach ($readiness['companion_checklists'] as $item)
                                        <div class="preview-checklist-item {{ $item['has_issues'] ? 'preview-checklist-item--blocked' : 'preview-checklist-item--ready' }}">
                                            <strong>
                                                {{ sprintf('%02d', $item['sequence']) }}. {{ $item['name'] }}
                                                @if ($item['has_issues'])
                                                    <span class="text-danger d-inline">({{ count($item['issues']) }} missing)</span>
                                                @else
                                                    <span class="text-success d-inline">(Ready)</span>
                                                @endif
                                            </strong>
                                            @if ($item['has_issues'])
                                                <span>{{ implode('; ', $item['issues']) }}</span>
                                            @else
                                                <span class="text-success">All artwork and teachings satisfied.</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </details>
            </div>
        </aside>
    @endif

    <div class="editorial-shell">

        <!-- Breadcrumbs Navigation -->
        <nav class="archive-breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ route('front.home.living-archive') }}">Living Archive</a>
            <span class="archive-breadcrumbs__divider">&rsaquo;</span>
            <span aria-current="page">Companions Along the Journey</span>
        </nav>

        <!-- 1. Hero & Ceremonial Entrance -->
        <header class="ceremonial-entrance">
            <div class="ceremonial-entrance__identity">
                @if ($setting->entrance_crest_image)
                    <div class="ceremonial-crest-wrap">
                        <img src="{{ asset($setting->entrance_crest_image) }}"
                             alt="{{ $setting->entrance_crest_image_alt ?: 'The Full Ceremonial Living Crest' }}"
                             class="ceremonial-crest ceremonial-crest--entrance">
                    </div>
                @elseif ($isPreview)
                    <div class="preview-missing-badge preview-missing-badge--crest">
                        Full Ceremonial Living Crest (Entrance) - Pending Admin Upload
                    </div>
                @endif
            </div>

            <div class="ceremonial-entrance__content">
                <span class="ceremonial-entrance__label">{{ $setting->entrance_section_label ?: 'THE LIVING ARCHIVE' }}</span>
                <h1 class="ceremonial-entrance__title">{{ $setting->entrance_title ?: 'COMPANIONS ALONG THE JOURNEY' }}</h1>
                @if ($setting->entrance_subtitle)
                    <p class="ceremonial-entrance__subtitle">{{ $setting->entrance_subtitle }}</p>
                @endif

                @if ($setting->opening_teaching)
                    <div class="ceremonial-entrance__teaching">
                        {!! $renderText($setting->opening_teaching) !!}
                    </div>
                @endif
                </div>
        </header>

        <hr class="editorial-divider" aria-hidden="true">

        <!-- 2. The Eleven Canonical Companions (Strict Ordered Sequence 1..11) -->
        <main id="companions-circle" aria-label="Companions Along the Journey">
            @foreach ($companions as $companion)
                <article class="companion-chapter" id="{{ $companion->slug }}" data-sequence="{{ $companion->sequence }}">
                    <div class="companion-grid">

                        <!-- Column A: Large Original Painting (Visual Centerpiece) -->
                        <div class="companion-artwork-col">
                            @if ($companion->artwork_image)
                                <figure class="companion-figure">
                                    <img src="{{ asset($companion->artwork_image) }}"
                                         alt="{{ $companion->artwork_image_alt ?: ($companion->name . ' — Companions Along the Journey') }}"
                                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                         class="companion-img">
                                </figure>
                            @elseif ($isPreview)
                                <div class="preview-missing-badge preview-missing-badge--artwork">
                                    🖼️ Artwork pending upload for {{ $companion->name }}
                                </div>
                            @endif
                        </div>

                        <!-- Column B: Narrative, Themes & Teachings -->
                        <div class="companion-narrative-col">
                            <!-- Sequence Label -->
                            <span class="companion-sequence-label">COMPANION {{ sprintf('%02d', $companion->sequence) }}</span>

                            <!-- Name -->
                            <h2 class="companion-name">{{ $companion->name }}</h2>

                            <!-- Themes -->
                            @if ($companion->themes)
                                <p class="companion-themes">{{ $companion->themes }}</p>
                            @endif

                            <!-- Expanded Teaching -->
                            @if ($companion->expanded_teaching)
                                <div class="companion-teaching">
                                    <h3 class="companion-section-title">Expanded Teaching</h3>
                                    <div class="companion-prose">
                                        {!! $renderText($companion->expanded_teaching) !!}
                                    </div>
                                </div>
                            @elseif ($isPreview)
                                <div class="preview-missing-badge preview-missing-badge--teaching">
                                    📜 Expanded teaching pending for {{ $companion->name }}
                                </div>
                            @endif

                            <!-- What We Are Asked to Remember -->
                            @if ($companion->remember_teaching)
                                <div class="companion-remember">
                                    <h3 class="companion-section-title">What We Are Asked to Remember</h3>
                                    <div class="companion-prose">
                                        {!! $renderText($companion->remember_teaching) !!}
                                    </div>
                                </div>
                            @elseif ($isPreview)
                                <div class="preview-missing-badge preview-missing-badge--remember">
                                    🕯️ Remembrance passage pending for {{ $companion->name }}
                                </div>
                            @endif

                            <!-- Five Feathers Companion Branding Block -->
                            @if ($companion->branding_info)
                                <div class="companion-branding">
                                    <div class="companion-branding__text">{!! $renderText($companion->branding_info) !!}</div>
                                </div>
                            @endif
                        </div>

                    </div>
                </article>
            @endforeach
        </main>

        <hr class="editorial-divider" aria-hidden="true">

        <!-- 3. Regeneration and The Witnesses -->
        <section class="narrative-chapter" id="regeneration-and-witnesses" aria-label="Regeneration and The Witnesses">
            @if ($setting->regeneration_title)
                <header class="narrative-header">
                    <h2 class="narrative-title">{{ $setting->regeneration_title }}</h2>
                    @if ($setting->regeneration_subtitle)
                        <p class="narrative-subtitle">{{ $setting->regeneration_subtitle }}</p>
                    @endif
                </header>
            @endif

            @if ($setting->regeneration_image)
                <div class="regeneration-artwork">
                    <img src="{{ asset($setting->regeneration_image) }}"
                         alt="{{ $setting->regeneration_image_alt ?: 'Regeneration' }}"
                         loading="lazy">
                </div>
            @endif

            @if ($setting->regeneration_body)
                <div class="editorial-prose mb-5">
                    {!! $renderText($setting->regeneration_body) !!}
                </div>
            @endif

            <!-- The Witnesses -->
            @if ($setting->witnesses_title)
                <div class="witnesses-block">
                    <header class="narrative-header">
                        <h2 class="narrative-title">{{ $setting->witnesses_title }}</h2>
                    </header>

                    @if ($setting->witnesses_intro)
                        <div class="witnesses-invocation">
                            {!! $renderText($setting->witnesses_intro) !!}
                        </div>
                    @endif

                    <div class="witnesses-grid">
                        <!-- Witness 1: Wind -->
                        @if ($setting->witness_wind_title || $setting->witness_wind_body)
                            <div class="witness-station witness-station--wind">
                                @if ($setting->witness_wind_title)
                                    <h3 class="witness-station__title">{{ $setting->witness_wind_title }}</h3>
                                @endif
                                @if ($setting->witness_wind_body)
                                    <div class="witness-station__body">{!! $renderText($setting->witness_wind_body) !!}</div>
                                @endif
                            </div>
                        @endif

                        <!-- Witness 2: Earth -->
                        @if ($setting->witness_earth_title || $setting->witness_earth_body)
                            <div class="witness-station witness-station--earth">
                                @if ($setting->witness_earth_title)
                                    <h3 class="witness-station__title">{{ $setting->witness_earth_title }}</h3>
                                @endif
                                @if ($setting->witness_earth_body)
                                    <div class="witness-station__body">{!! $renderText($setting->witness_earth_body) !!}</div>
                                @endif
                            </div>
                        @endif

                        <!-- Witness 3: Water -->
                        @if ($setting->witness_water_title || $setting->witness_water_body)
                            <div class="witness-station witness-station--water">
                                @if ($setting->witness_water_title)
                                    <h3 class="witness-station__title">{{ $setting->witness_water_title }}</h3>
                                @endif
                                @if ($setting->witness_water_body)
                                    <div class="witness-station__body">{!! $renderText($setting->witness_water_body) !!}</div>
                                @endif
                            </div>
                        @endif

                        <!-- Witness 4: Sunlight -->
                        @if ($setting->witness_sunlight_title || $setting->witness_sunlight_body)
                            <div class="witness-station witness-station--sunlight">
                                @if ($setting->witness_sunlight_title)
                                    <h3 class="witness-station__title">{{ $setting->witness_sunlight_title }}</h3>
                                @endif
                                @if ($setting->witness_sunlight_body)
                                    <div class="witness-station__body">{!! $renderText($setting->witness_sunlight_body) !!}</div>
                                @endif
                            </div>
                        @endif

                        <!-- Witness 5: Night -->
                        @if ($setting->witness_night_title || $setting->witness_night_body)
                            <div class="witness-station witness-station--night">
                                @if ($setting->witness_night_title)
                                    <h3 class="witness-station__title">{{ $setting->witness_night_title }}</h3>
                                @endif
                                @if ($setting->witness_night_body)
                                    <div class="witness-station__body">{!! $renderText($setting->witness_night_body) !!}</div>
                                @endif
                            </div>
                        @endif

                        <!-- Witness 6: Rest -->
                        @if ($setting->witness_rest_title || $setting->witness_rest_body)
                            <div class="witness-station witness-station--rest">
                                @if ($setting->witness_rest_title)
                                    <h3 class="witness-station__title">{{ $setting->witness_rest_title }}</h3>
                                @endif
                                @if ($setting->witness_rest_body)
                                    <div class="witness-station__body">{!! $renderText($setting->witness_rest_body) !!}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </section>

        <!-- 4. The Human Responsibility -->
        @if ($setting->human_responsibility_title || $setting->human_responsibility_body)
            <section class="narrative-chapter" id="human-responsibility" aria-label="The Human Responsibility">
                @if ($setting->human_responsibility_title)
                    <header class="narrative-header">
                        <h2 class="narrative-title">{{ $setting->human_responsibility_title }}</h2>
                    </header>
                @endif
                @if ($setting->human_responsibility_body)
                    <div class="editorial-prose">
                        {!! $renderText($setting->human_responsibility_body) !!}
                    </div>
                @endif
            </section>
        @endif

        <!-- 5. Final Rest and Continuance -->
        @if ($setting->continuance_title || $setting->continuance_body)
            <section class="narrative-chapter" id="continuance" aria-label="Final Rest and Continuance">
                @if ($setting->continuance_title)
                    <header class="narrative-header">
                        <h2 class="narrative-title">{{ $setting->continuance_title }}</h2>
                    </header>
                @endif
                @if ($setting->continuance_body)
                    <div class="editorial-prose">
                        {!! $renderText($setting->continuance_body) !!}
                    </div>
                @endif
            </section>
        @endif

        <!-- 6. New Beginning and Closing Crest (Mandatory Exact Sequence) -->
        <footer class="ceremonial-closing-flow" aria-label="Ceremonial Conclusion">

            <!-- FIRST: new_beginning_body -->
            @if ($setting->new_beginning_body)
                <div class="ceremonial-closing__new-beginning">
                    {!! $renderText($setting->new_beginning_body) !!}
                </div>
            @endif

            <!-- THEN: closing_crest_image -->
            @if ($setting->closing_crest_image)
                <div class="ceremonial-crest-wrap ceremonial-crest-wrap--closing">
                    <img src="{{ asset($setting->closing_crest_image) }}"
                         alt="{{ $setting->closing_crest_image_alt ?: 'The Full Ceremonial Living Crest' }}"
                         class="ceremonial-crest ceremonial-crest--closing">
                </div>
            @elseif ($isPreview)
                <div class="preview-missing-badge preview-missing-badge--crest">
                    ⚜️ Full Ceremonial Living Crest (Closing) &mdash; Pending Admin Upload
                </div>
            @endif

            <!-- THEN: closing_text -->
            @if ($setting->closing_text)
                <div class="ceremonial-closing__statement">
                    {!! $renderText($setting->closing_text) !!}
                </div>
            @endif

            <!-- FINALLY: final_refrain -->
            @if ($setting->final_refrain)
                <div class="ceremonial-closing__refrain">
                    {!! $renderText($setting->final_refrain) !!}
                </div>
            @endif

            <!-- Generous Spacing Before Navigation (Quiet Conclusion without promotional distractions) -->
            <div class="ceremonial-conclusion-space"></div>

            <div class="archive-actions">
                <a class="archive-action" href="{{ route('front.home.living-archive') }}">
                    &larr; Back to Living Archive
                </a>
            </div>
        </footer>

    </div>

    <!-- Living Archive Quiet Footer Variant -->
    @include('frontend.living-archive.partials.footer')
</div>
@endsection
