@php
    $ceremonialPageSlugs = [
        'identity',
        'five-feathers-lineage-society',
    ];
    $pageSlug = $customPage ? (string) $customPage->slug : '';
    $pageKey = $customPage ? \Illuminate\Support\Str::slug($customPage->page_name) : '';
    $isCeremonialPage = $customPage && (in_array($pageSlug, $ceremonialPageSlugs, true) || in_array($pageKey, $ceremonialPageSlugs, true));
@endphp

@extends($isCeremonialPage ? 'frontend.layouts.living-archive' : 'frontend.app')

@section('title')
    {{ $customPage->page_name }}
@endsection

@if(!$isCeremonialPage)
    @push('css')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/cart.css') }}">
    @endpush
@endif

@if($isCeremonialPage)
    @push('css')
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Work+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
                background:
                    radial-gradient(circle at 18% 10%, rgba(201, 162, 39, 0.12), transparent 28%),
                    linear-gradient(180deg, #07111b 0%, #0b121c 58%, #070b11 100%);
                color: #f6edd0;
                font-family: "Work Sans", Arial, sans-serif;
                overflow-x: hidden;
            }
            .ceremonial-page-wrapper {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                background:
                    linear-gradient(180deg, rgba(7, 17, 27, 0.88), rgba(7, 11, 17, 0.94)),
                    radial-gradient(circle at 82% 22%, rgba(184, 134, 11, 0.13), transparent 34%);
            }
            .ceremonial-page-hero {
                padding: clamp(56px, 9vw, 108px) 20px 32px;
                text-align: center;
            }
            .ceremonial-page-kicker {
                display: inline-block;
                margin-bottom: 16px;
                color: #c9a227;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.28em;
                text-transform: uppercase;
            }
            .ceremonial-page-title {
                max-width: 980px;
                margin: 0 auto 16px;
                color: #f6edd0;
                font-family: "Cinzel", Georgia, serif;
                font-size: clamp(34px, 6vw, 72px);
                line-height: 1.08;
                letter-spacing: 0.04em;
            }
            .ceremonial-page-subtitle {
                max-width: 760px;
                margin: 0 auto;
                color: rgba(246, 237, 208, 0.72);
                font-size: clamp(15px, 2vw, 18px);
                line-height: 1.8;
            }
            .ceremonial-page-container {
                width: min(100%, 1060px);
                margin: 0 auto;
                padding: 0 20px 28px;
            }
            .ceremonial-page-card {
                width: 100%;
                padding: clamp(24px, 4vw, 48px);
                border: 1px solid rgba(201, 162, 39, 0.24);
                border-radius: 18px;
                background:
                    linear-gradient(180deg, rgba(16, 18, 22, 0.94), rgba(9, 12, 17, 0.88)),
                    rgba(10, 10, 12, 0.82);
                box-shadow: 0 30px 80px rgba(0, 0, 0, 0.34);
                color: rgba(246, 237, 208, 0.82);
                line-height: 1.85;
            }
            .ceremonial-page-card h1,
            .ceremonial-page-card h2,
            .ceremonial-page-card h3,
            .ceremonial-page-card h4,
            .ceremonial-page-card h5,
            .ceremonial-page-card h6 {
                color: #f6edd0;
                font-family: "Cinzel", Georgia, serif;
                letter-spacing: 0.03em;
            }
            .ceremonial-page-card p,
            .ceremonial-page-card li {
                color: rgba(246, 237, 208, 0.78);
            }
            .ceremonial-page-card a {
                color: #c9a227;
            }
            .ceremonial-page-card img,
            .ceremonial-page-card iframe,
            .ceremonial-page-card video {
                max-width: 100%;
            }
            .ceremonial-page-back {
                padding: 4px 20px 24px;
                text-align: center;
            }
            .ceremonial-page-back-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 50px;
                padding: 13px 24px;
                border-radius: 999px;
                border: 1px solid rgba(201, 162, 39, 0.46);
                background: rgba(10, 10, 12, 0.72);
                color: #f6edd0;
                font-size: 13px;
                font-weight: 800;
                letter-spacing: 0.1em;
                text-decoration: none;
                text-transform: uppercase;
                box-shadow: 0 14px 30px rgba(0, 0, 0, 0.24);
                transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
            }
            .ceremonial-page-back-link:hover {
                color: #17120a;
                background: linear-gradient(135deg, #c9a227, #f3d67b);
                transform: translateY(-1px);
            }
            .ceremonial-page-footer {
                margin-top: auto;
                padding: 20px;
                text-align: center;
                color: rgba(246, 237, 208, 0.62);
                font-size: 13px;
            }
            .ceremonial-page-footer a {
                color: #c9a227;
                text-decoration: none;
                font-weight: 700;
            }
            @media (max-width: 576px) {
                .ceremonial-page-hero {
                    padding: 44px 14px 26px;
                }
                .ceremonial-page-container {
                    padding: 0 14px 22px;
                }
                .ceremonial-page-card {
                    border-radius: 14px;
                }
                .ceremonial-page-back {
                    padding: 0 14px 20px;
                }
                .ceremonial-page-back-link {
                    width: 100%;
                    padding: 13px 16px;
                    font-size: 12px;
                }
            }
        </style>
    @endpush
@endif

@section('content')
    @if($isCeremonialPage)
        @php
            $pageHtml = html_entity_decode($customPage->description ?? '', ENT_QUOTES, 'UTF-8');
            $pageHtml = str_replace("\xC2\xA0", ' ', $pageHtml);
            $pageHtml = preg_replace('/<p>(\s|&nbsp;)*<\/p>/i', '', $pageHtml);
            $pagePlain = trim(preg_replace('/\s+/', ' ', strip_tags($pageHtml)));
            $pageSubtitle = \Illuminate\Support\Str::limit($pagePlain, 190);
        @endphp

        <main class="ceremonial-page-wrapper">
            <section class="ceremonial-page-hero">
                <span class="ceremonial-page-kicker">Living Archive</span>
                <h1 class="ceremonial-page-title">{{ $customPage->page_name }}</h1>
                @if($pageSubtitle !== '')
                    <p class="ceremonial-page-subtitle">{{ $pageSubtitle }}</p>
                @endif
            </section>

            <section class="ceremonial-page-container">
                <div class="ceremonial-page-card">
                    {!! $pageHtml !!}
                </div>
            </section>

            <div class="ceremonial-page-back">
                <a class="ceremonial-page-back-link" href="{{ route('front.home.living-archive') }}">Return to the Living Archive</a>
            </div>

            @include('frontend.partials.closing_identity_bar')

            <div class="ceremonial-page-footer">
                @copyright Thomas Alexander. Develop by
                <a href="https://nirjonroy.com" target="_blank" rel="noopener">Nirjon roy</a>.
            </div>
        </main>
    @else
        <div class="ms_content_wrapper padder_top8">
            <div class="ms_index_wrapper common_pages_space">
                <div class="container" style="background: white; padding: 5px;">
                    <h1>{{ $customPage->page_name }}</h1>
                    <p style="text-align:justify">{!! $customPage->description !!}</p>
                </div>
            </div>
        </div>
    @endif
@endsection
