@extends('frontend.layouts.living-archive')

@php
    $isCategoryPage = $directChildren->isNotEmpty();
    $parentPage = $ancestors->last();
    $siteName = siteInfo()->site_name ?? siteInfo()->website_name ?? config('app.name', 'Thomas Alexander');
    $title = $archivePage->meta_title ?: $archivePage->title . ' | Living Archive';
    $descSource = $archivePage->meta_description ?: ($archivePage->teaser ?: $archivePage->content);
    $desc = \Illuminate\Support\Str::limit(strip_tags($descSource ?: 'Explore the Living Archive of Thomas Alexander.'), 180);
    $image = $archivePage->og_image ?: $archivePage->featured_image;
    $fallbackLogo = siteInfo()->logo ?? null;
    $fallbackImage = $fallbackLogo ? asset($fallbackLogo) : asset('images/og-default.jpg');
    $imageUrl = $image ? (str_starts_with($image, 'http') ? $image : asset($image)) : $fallbackImage;
    $imageAlt = $archivePage->og_image_alt ?: ($archivePage->featured_image_alt ?: $archivePage->title);
    $featuredImageUrl = $archivePage->featured_image
        ? (str_starts_with($archivePage->featured_image, 'http') ? $archivePage->featured_image : asset($archivePage->featured_image))
        : null;
    $featuredImageAlt = $archivePage->featured_image_alt ?: $archivePage->title . ' featured image';
    $canonical = url()->current();
    $breadcrumbItems = collect([[
        'name' => 'Living Archive',
        'url' => route('front.home.living-archive'),
    ]])
        ->merge($ancestors->map(fn ($entry) => [
            'name' => $entry->title,
            'url' => route('front.living-archive.show', ['path' => $resolver->pathFor($entry)]),
        ]))
        ->push([
            'name' => $archivePage->title,
            'url' => $canonical,
        ])
        ->values();
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbItems->map(fn ($item, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url'],
        ])->all(),
    ];
    $contentSchema = !$isCategoryPage ? [
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'headline' => $archivePage->title,
        'description' => $desc,
        'url' => $canonical,
        'image' => $imageUrl,
        'datePublished' => optional($archivePage->published_at)->toAtomString(),
        'dateModified' => optional($archivePage->updated_at)->toAtomString(),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonical,
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $siteName,
        ],
    ] : null;
    $schemaGraph = array_values(array_filter([$breadcrumbSchema, $contentSchema]));
@endphp

@section('title', $title)

@section('seos')
    <meta name="title" content="{{ $title }}">
    <meta name="description" content="{{ $desc }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="{{ $isCategoryPage ? 'website' : 'article' }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $desc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:image:secure_url" content="{{ $imageUrl }}">
    <meta property="og:image:alt" content="{{ $imageAlt }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $desc }}">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:image" content="{{ $imageUrl }}">
    <script type="application/ld+json">{!! json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --archive-ink: #070706;
            --archive-deep: #12100d;
            --archive-brown: #2b1a10;
            --archive-cream: #fff7e8;
            --archive-muted: rgba(255, 247, 232, 0.76);
            --archive-soft: rgba(255, 247, 232, 0.07);
            --archive-gold: #d9a441;
            --archive-gold-bright: #f1c76b;
            --archive-copper: #b96f37;
            --archive-teal: #0e6f66;
        }
        body {
            margin: 0;
            background: var(--archive-ink);
            color: var(--archive-cream);
            font-family: "Manrope", Arial, sans-serif;
            overflow-x: hidden;
        }
        .archive-page,
        .archive-page * {
            box-sizing: border-box;
        }
        .archive-page {
            min-height: 100vh;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 82% 12%, rgba(14, 111, 102, 0.16), transparent 34%),
                radial-gradient(circle at 10% 30%, rgba(217, 164, 65, 0.12), transparent 28%),
                linear-gradient(135deg, var(--archive-ink) 0%, var(--archive-deep) 56%, #07111b 100%);
        }
        .archive-shell {
            width: min(100%, 1180px);
            margin: 0 auto;
            padding: clamp(30px, 5vw, 70px) 18px;
        }
        .archive-page a {
            color: var(--archive-gold-bright);
            text-decoration: none;
        }
        .archive-page a:hover {
            color: var(--archive-cream);
        }
        .archive-page a:focus-visible,
        .archive-page button:focus-visible {
            outline: 3px solid rgba(241, 199, 107, 0.7);
            outline-offset: 4px;
        }
        .archive-breadcrumbs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 22px;
            color: rgba(255, 247, 232, 0.62);
            font-size: 13px;
            font-weight: 700;
        }
        .archive-local-nav {
            margin-bottom: 22px;
            border: 1px solid rgba(217, 164, 65, 0.22);
            border-radius: 18px;
            background: rgba(10, 10, 8, 0.7);
        }
        .archive-local-nav summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            min-height: 48px;
            padding: 12px 16px;
            color: var(--archive-gold-bright);
            font-weight: 900;
            cursor: pointer;
        }
        .archive-local-nav summary::after {
            content: "+";
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(217, 164, 65, 0.36);
            border-radius: 50%;
            color: var(--archive-cream);
        }
        .archive-local-nav[open] summary::after {
            content: "-";
        }
        .archive-local-nav__links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0 16px 16px;
        }
        .archive-local-nav__links a {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border: 1px solid rgba(255, 247, 232, 0.12);
            border-radius: 999px;
            color: rgba(255, 247, 232, 0.82);
            font-size: 13px;
            font-weight: 800;
        }
        .archive-local-nav__links a[aria-current="page"],
        .archive-local-nav__links a:hover {
            color: var(--archive-ink);
            background: linear-gradient(135deg, var(--archive-gold-bright), var(--archive-copper));
            border-color: transparent;
        }
        .archive-hero {
            position: relative;
            overflow: hidden;
            min-width: 0;
            padding: clamp(28px, 4vw, 48px);
            border: 1px solid rgba(217, 164, 65, 0.28);
            border-radius: 28px;
            background:
                linear-gradient(145deg, rgba(255, 247, 232, 0.08), rgba(255, 247, 232, 0.025)),
                rgba(10, 10, 8, 0.9);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.28);
        }
        .archive-hero::after,
        .archive-card::before {
            content: "";
            position: absolute;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--archive-gold), var(--archive-copper));
        }
        .archive-hero::after {
            left: 28px;
            right: 28px;
            bottom: 0;
            height: 4px;
        }
        .archive-eyebrow {
            display: block;
            margin-bottom: 12px;
            color: var(--archive-gold-bright);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }
        .archive-title,
        .archive-section-heading h2,
        .archive-card__title,
        .archive-article h2,
        .archive-aside h2 {
            color: var(--archive-cream);
            font-family: "Cormorant Garamond", Georgia, serif;
        }
        .archive-title {
            max-width: 920px;
            margin: 0;
            font-size: clamp(3rem, 6vw, 5.2rem);
            line-height: 0.96;
            overflow-wrap: break-word;
        }
        .archive-lead {
            max-width: 820px;
            margin: 20px 0 0;
            color: var(--archive-muted);
            font-size: clamp(1rem, 1.6vw, 1.2rem);
            line-height: 1.75;
        }
        .archive-main {
            margin-top: 28px;
        }
        .archive-category-grid,
        .archive-related__grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }
        .archive-category-grid {
            margin-top: 28px;
        }
        .archive-card {
            position: relative;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            padding: 26px;
            border: 1px solid rgba(217, 164, 65, 0.24);
            border-radius: 24px;
            background:
                linear-gradient(145deg, var(--archive-soft), rgba(14, 111, 102, 0.06)),
                rgba(10, 10, 8, 0.82);
            box-shadow: 0 22px 58px rgba(0, 0, 0, 0.22);
            transition: transform 0.22s ease, border-color 0.22s ease, box-shadow 0.22s ease;
        }
        .archive-card::before {
            top: 0;
            left: 22px;
            right: 22px;
            height: 4px;
        }
        .archive-card:hover,
        .archive-card:focus-within {
            transform: translateY(-5px);
            border-color: rgba(241, 199, 107, 0.5);
            box-shadow: 0 28px 68px rgba(0, 0, 0, 0.3);
        }
        .archive-card__eyebrow {
            margin-bottom: 12px;
            color: var(--archive-copper);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
        .archive-card__title {
            margin: 0 0 12px;
            font-size: clamp(1.65rem, 2.5vw, 2.25rem);
            line-height: 1.03;
        }
        .archive-card__teaser {
            margin: 0 0 18px;
            color: var(--archive-muted);
            line-height: 1.65;
        }
        .archive-card__cta,
        .archive-action {
            width: max-content;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: auto;
            padding: 10px 16px;
            border: 1px solid rgba(217, 164, 65, 0.46);
            border-radius: 999px;
            color: var(--archive-gold-bright);
            background: rgba(7, 7, 6, 0.58);
            font-size: 13px;
            font-weight: 800;
        }
        .archive-card__cta:hover,
        .archive-action:hover {
            color: var(--archive-ink);
            background: linear-gradient(135deg, var(--archive-gold-bright), var(--archive-copper));
        }
        .archive-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 34px;
            align-items: start;
        }
        .archive-article {
            width: min(100%, 820px);
            min-width: 0;
            padding: clamp(26px, 4vw, 46px);
            border: 1px solid rgba(217, 164, 65, 0.22);
            border-radius: 26px;
            background: rgba(10, 10, 8, 0.78);
            box-shadow: 0 24px 62px rgba(0, 0, 0, 0.24);
        }
        .archive-featured-image,
        .archive-document {
            margin: 0 0 28px;
        }
        .archive-featured-image img,
        .archive-document img {
            width: 100%;
            border-radius: 20px;
            border: 1px solid rgba(217, 164, 65, 0.22);
            background: #000;
        }
        .archive-article__body {
            color: rgba(255, 247, 232, 0.82);
            font-size: 1.03rem;
            line-height: 1.9;
        }
        .archive-article__body p {
            margin-bottom: 1.15rem;
        }
        .archive-document {
            padding-top: 28px;
            border-top: 1px solid rgba(217, 164, 65, 0.2);
        }
        .archive-document__button {
            width: 100%;
            padding: 0;
            border: 0;
            color: var(--archive-gold-bright);
            background: transparent;
            text-align: left;
            cursor: pointer;
        }
        .archive-document__button span {
            display: inline-block;
            margin-top: 10px;
            font-weight: 800;
        }
        figcaption,
        .archive-dialog p {
            margin-top: 10px;
            color: rgba(255, 247, 232, 0.66);
            font-size: 0.92rem;
            line-height: 1.55;
        }
        .archive-aside {
            display: grid;
            gap: 18px;
        }
        .archive-aside__panel,
        .archive-related {
            min-width: 0;
            padding: 22px;
            border: 1px solid rgba(217, 164, 65, 0.22);
            border-radius: 22px;
            background: rgba(10, 10, 8, 0.7);
        }
        .archive-aside h2,
        .archive-section-heading h2 {
            margin: 0 0 14px;
            font-size: 1.75rem;
        }
        .archive-list {
            display: grid;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .archive-list a {
            display: block;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 247, 232, 0.12);
            font-weight: 800;
        }
        .archive-record-nav {
            width: min(100%, 820px);
            display: flex;
            justify-content: space-between;
            gap: 18px;
            margin-top: 24px;
        }
        .archive-record-nav > div {
            flex: 1 1 0;
            padding: 18px;
            border: 1px solid rgba(217, 164, 65, 0.2);
            border-radius: 18px;
            background: rgba(10, 10, 8, 0.58);
        }
        .archive-record-nav > div:last-child {
            text-align: right;
        }
        .archive-record-nav span {
            display: block;
            margin-bottom: 6px;
            color: rgba(255, 247, 232, 0.54);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .archive-related {
            margin-top: 34px;
        }
        .archive-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }
        .archive-dialog {
            width: min(94vw, 1120px);
            max-height: 90vh;
            border: 1px solid rgba(217, 164, 65, 0.4);
            border-radius: 24px;
            color: var(--archive-cream);
            background: #070706;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.55);
        }
        .archive-dialog::backdrop {
            background: rgba(0, 0, 0, 0.76);
        }
        .archive-dialog__surface {
            padding: 18px;
        }
        .archive-dialog__surface img {
            width: 100%;
            max-height: 74vh;
            object-fit: contain;
        }
        .archive-dialog__close {
            float: right;
            margin-bottom: 12px;
            border: 1px solid rgba(217, 164, 65, 0.44);
            border-radius: 999px;
            color: var(--archive-gold-bright);
            background: rgba(255, 247, 232, 0.06);
            font-weight: 800;
        }
        .archive-footer {
            border-top: 1px solid rgba(255, 247, 232, 0.1);
            background: #070706;
        }
        .archive-footer__inner {
            width: min(100%, 1180px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 0 auto;
            padding: 24px 18px;
            color: rgba(255, 247, 232, 0.7);
            font-size: 13px;
        }
        .archive-footer__links {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }
        @media (max-width: 991.98px) {
            .archive-category-grid,
            .archive-related__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .archive-layout {
                grid-template-columns: 1fr;
            }
            .archive-article {
                width: 100%;
            }
        }
        @media (max-width: 767.98px) {
            .archive-shell {
                padding-top: 24px;
            }
            .archive-category-grid,
            .archive-related__grid {
                grid-template-columns: 1fr;
            }
            .archive-card {
                padding: 22px;
            }
            .archive-title {
                font-size: clamp(2.15rem, 11vw, 2.9rem);
                line-height: 1;
            }
            .archive-record-nav,
            .archive-footer__inner {
                flex-direction: column;
            }
            .archive-record-nav > div:last-child,
            .archive-footer__inner {
                text-align: left;
            }
            .archive-card__cta,
            .archive-action {
                width: 100%;
                white-space: normal;
                text-align: center;
                line-height: 1.3;
            }
            .archive-local-nav__links {
                display: grid;
                grid-template-columns: 1fr;
            }
            .archive-local-nav__links a {
                width: 100%;
                justify-content: center;
                border-radius: 12px;
            }
        }
        @media (max-width: 575.98px) {
            html,
            body,
            .archive-page {
                width: 100% !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            .archive-page,
            .archive-page * {
                box-sizing: border-box;
            }
            .archive-shell {
                width: 100% !important;
                max-width: 100vw !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
            .archive-hero,
            .archive-article,
            .archive-aside__panel,
            .archive-related,
            .archive-record-nav,
            .archive-actions,
            .archive-record-nav > div {
                width: calc(100vw - 24px) !important;
                max-width: calc(100vw - 24px) !important;
                min-width: 0 !important;
                margin-left: auto !important;
                margin-right: auto !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
                overflow: hidden;
            }
            .archive-record-nav,
            .archive-actions {
                width: calc(100vw - 24px) !important;
            }
            .archive-title {
                display: block !important;
                width: 100% !important;
                max-width: calc(100vw - 64px) !important;
                font-size: clamp(1.75rem, 8.5vw, 2.25rem) !important;
                line-height: 1.04 !important;
                white-space: normal !important;
                overflow-wrap: anywhere !important;
            }
            .archive-lead,
            .archive-article__body,
            .archive-card__teaser {
                max-width: calc(100vw - 64px) !important;
                overflow-wrap: anywhere !important;
            }
            .archive-action,
            .archive-card__cta {
                max-width: calc(100vw - 64px) !important;
                white-space: normal !important;
                line-height: 1.3 !important;
                text-align: center !important;
                overflow-wrap: anywhere !important;
                word-break: normal !important;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .archive-card,
            .archive-card__cta,
            .archive-action {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <main class="archive-page">
        <div class="archive-shell">
            @include('frontend.living-archive.partials.breadcrumbs')
            @if (!empty($rootPages) && $rootPages->isNotEmpty())
                <details class="archive-local-nav">
                    <summary>Living Archive Navigation</summary>
                    <div class="archive-local-nav__links">
                        <a href="{{ route('front.home.living-archive') }}">Archive Home</a>
                        @foreach ($rootPages as $rootPage)
                            <a href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($rootPage)]) }}" {{ ($ancestors->first()?->id ?? $archivePage->id) === $rootPage->id ? 'aria-current="page"' : '' }}>
                                {{ $rootPage->title }}
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif
            @include('frontend.living-archive.partials.hero')

            @if ($isCategoryPage)
                <section class="archive-main" aria-labelledby="archive-child-pages-title">
                    <div class="archive-section-heading">
                        <span class="archive-eyebrow">Archive Records</span>
                        <h2 id="archive-child-pages-title">In This Section</h2>
                    </div>
                    <div class="archive-category-grid">
                        @foreach ($directChildren as $entry)
                            @include('frontend.living-archive.partials.archive-card', ['entry' => $entry, 'resolver' => $resolver])
                        @endforeach
                    </div>
                </section>

                @include('frontend.living-archive.partials.related-pages')
            @else
                <div class="archive-main archive-layout">
                    <article class="archive-article">
                        @if ($featuredImageUrl)
                            <figure class="archive-featured-image">
                                <img src="{{ $featuredImageUrl }}" alt="{{ $featuredImageAlt }}" loading="lazy">
                            </figure>
                        @endif

                        <section class="archive-article__body">
                            {!! $archivePage->content !!}
                        </section>

                        @if ($archivePage->document_image)
                            @include('frontend.living-archive.partials.document-block')
                        @endif
                    </article>

                    <aside class="archive-aside" aria-label="Archive page links">
                        @if ($parentPage)
                            <section class="archive-aside__panel">
                                <h2>Parent Section</h2>
                                <a class="archive-action" href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($parentPage)]) }}">
                                    Back to {{ $parentPage->title }}
                                </a>
                            </section>
                        @endif

                        @if ($relatedPages->isNotEmpty())
                            <section class="archive-aside__panel">
                                <h2>Related Pages</h2>
                                <ul class="archive-list">
                                    @foreach ($relatedPages as $entry)
                                        <li>
                                            <a href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($entry)]) }}">{{ $entry->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                        @endif
                    </aside>
                </div>

                @include('frontend.living-archive.partials.navigation')
            @endif

            <div class="archive-actions">
                @if ($parentPage && $isCategoryPage)
                    <a class="archive-action" href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($parentPage)]) }}">
                        Back to {{ $parentPage->title }}
                    </a>
                @endif
                <a class="archive-action" href="{{ route('front.home.living-archive') }}">Back to Living Archive</a>
            </div>
        </div>
    </main>

    @include('frontend.living-archive.partials.footer')
@endsection

@push('js')
    <script>
        (function () {
            'use strict';

            document.querySelectorAll('[data-archive-dialog-open]').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    var dialog = document.getElementById(trigger.getAttribute('data-archive-dialog-open'));

                    if (!dialog) {
                        return;
                    }

                    if (typeof dialog.showModal === 'function') {
                        dialog.showModal();
                    }
                });
            });

            document.querySelectorAll('[data-archive-dialog-close]').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    var dialog = trigger.closest('dialog');

                    if (dialog) {
                        dialog.close();
                    }
                });
            });
        })();
    </script>
@endpush
