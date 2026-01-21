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
        padding: 56px 0;
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
        justify-content: space-between;
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
        flex-wrap: wrap;
        gap: 14px;
        justify-content: flex-end;
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
    }
    .crest-nav-links a i {
        font-size: 16px;
        color: var(--living-gold);
    }
    .crest-nav-links a:hover {
        color: var(--living-gold);
    }
    .living-card .living-card {
        background: rgba(10, 14, 22, 0.7);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: none;
        padding: 20px;
    }
    .living-hero {
        padding: 60px 0 40px;
    }
    .living-hero-card {
        position: relative;
        border-radius: 26px;
        padding: 42px;
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
        gap: 16px;
        align-items: center;
    }
    .living-hero-crest img {
        max-width: 300px;
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
        font-style: italic;
        color: rgba(244, 239, 227, 0.85);
        margin-bottom: 10px;
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
        padding: 18px;
        position: relative;
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
        margin-top: 12px;
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
            padding: 24px 18px;
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
    $primaryCrestImage = $crest['primary_image'] ?? asset('frontend/living-archive/Dreamcatcher-style crest.jpeg');
    $secondaryCrestImage = $crest['secondary_image'] ?? asset('frontend/living-archive/crest represents the Five Civilized Tribes.jpeg');
    $heroImage = data_get($page, 'media.hero', asset('frontend/living-archive/banner3.jpg'));
    $logoImage = data_get($page, 'media.logo', asset('frontend/living-archive/images/logo.png'));

    $youthCrestPath = 'frontend/living-archive/crests/youth-crest.jpg';
    $keeperCrestPath = 'frontend/living-archive/crests/eagle.jpg';
    $witnessCrestPath = 'frontend/living-archive/crests/elder-crest.jpg';
    $qrCrestPath = 'frontend/living-archive/crests/qr-crest.jpg';

    $youthCrestImage = file_exists(public_path($youthCrestPath)) ? asset($youthCrestPath) : $secondaryCrestImage;
    $keeperCrestImage = file_exists(public_path($keeperCrestPath)) ? asset($keeperCrestPath) : $secondaryCrestImage;
    $witnessCrestImage = file_exists(public_path($witnessCrestPath)) ? asset($witnessCrestPath) : $secondaryCrestImage;
    $qrCrestImage = file_exists(public_path($qrCrestPath)) ? asset($qrCrestPath) : $secondaryCrestImage;

    $introText = data_get($page, 'intro')
        ?? 'Thomas Alexander — The Voice — carries the Living Crest of the Breath-line, a ceremonial archive of memory, music, and lineage.';
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
                    <div class="living-hero-crest">
                        <img src="{{ $primaryCrestImage }}" alt="Main Ceremonial Crest">
                    </div>
                    <p class="living-affirmation">We Were Never Erased. We Were Replanted.</p>
                    <p class="living-hero-intro">{{ $introText }}</p>
                    <div class="living-cta-row">
                        <a href="#lineage-story" class="living-primary-btn">Explore the Five Feathers Lineage</a>
                        <a href="#carrier-pathway" class="living-secondary-btn">Begin the Carrier Pathway</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="lineage-story">
        <div class="container">
            <div class="living-section-title">
                <h2>About the Lineage</h2>
                <p>The Living Archive is a ceremonial record — an ancestral ledger where memory, symbol, and song return to their rightful lineage.</p>
            </div>
            <div class="living-card">
                <div class="lineage-crest">
                    <img src="{{ $primaryCrestImage }}" alt="Ceremonial Crest">
                </div>
                <p>The Tree of Life stands at the center of the crest, holding the Breath-line across generations and returning each name to ceremony.</p>
                <div class="lineage-grid">
                    <div class="living-card">
                        <span class="living-pill">Tree of Life</span>
                        <p>Root and canopy unite the Breath-line, keeping the living memory in motion.</p>
                    </div>
                    <div class="living-card">
                        <span class="living-pill">Ten Yamassee Clan Animals</span>
                        <p>Guardians of medicine, each one marking protection, vow, and teaching.</p>
                    </div>
                    <div class="living-card">
                        <span class="living-pill">Three Ancestral Shields</span>
                        <p>Three shields hold sovereignty, continuity, and ceremonial protection.</p>
                    </div>
                    <div class="living-card">
                        <span class="living-pill">Five Feathers + Ghost Feather</span>
                        <p>The five tribes honored; the Ghost Feather holds the ancestor still returning.</p>
                    </div>
                </div>
                <div class="lineage-endline">We Were Never Erased. We Were Replanted.</div>
            </div>
        </div>
    </section>

    <section class="living-section" id="three-crests">
        <div class="container">
            <div class="living-section-title">
                <h2>The Three Crests</h2>
                <p>These are sacred displays — static and enduring, held as testimony for the youth, the keepers, and the elders of the lineage.</p>
            </div>
            <div class="crest-grid">
                <div class="living-card crest-card" id="youth-crest">
                    <img src="{{ $youthCrestImage }}" alt="Youth Crest - Great Horned Owls">
                    <h3>Youth Crest — The Listener</h3>
                    <p class="crest-declaration">"We perch where the roof gave way."</p>
                    <p>The Listener enters by listening first — observing, gathering, and holding the earliest teachings.</p>
                    <p>They are welcomed into the lineage as the first witnesses, carrying the hush of beginnings.</p>
                </div>
                <div class="living-card crest-card" id="keeper-crest">
                    <img src="{{ $keeperCrestImage }}" alt="Keeper Crest - Eagle">
                    <h3>Keeper Crest — The Bearer</h3>
                    <p class="crest-declaration">"As the eagle, I did not blink, for I saw and see it all."</p>
                    <p>The Bearer holds responsibility for the crest, the teachings, and the living record.</p>
                    <p>They rise into sight through service, courage, and the clear gaze of stewardship.</p>
                </div>
                <div class="living-card crest-card" id="witness-crest">
                    <img src="{{ $witnessCrestImage }}" alt="Witness Crest - White Buffalo and Snowy Owl">
                    <h3>Witness Crest — The Elder</h3>
                    <p class="crest-declaration">"We kept the fire when the world went dark."</p>
                    <p>The Elder carries memory as ceremony, protecting the line when silence falls.</p>
                    <p>They are continuity itself — the living archive made flesh and breath.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="carrier-pathway">
        <div class="container">
            <div class="living-section-title">
                <h2>Carrier Pathway</h2>
                <p>The lineage moves with intention — Youth to Keeper to Witness — each step recognized through ceremony, accountability, and protection.</p>
            </div>
            <div class="pathway-flow">
                <div class="living-card pathway-step">
                    <i class="fa fa-owl"></i>
                    <h4>Youth → Keeper</h4>
                    <p>Requirements: attentive listening, ceremonial training, and commitment to the Breath-line.</p>
                    <p>Recognition: named by elders through witness and documented in the archive.</p>
                </div>
                <div class="living-card pathway-step">
                    <i class="fa fa-feather"></i>
                    <h4>Keeper → Witness</h4>
                    <p>Requirements: stewardship of rituals, protection of crest teachings, and community responsibility.</p>
                    <p>Recognition: rises into sight through service, guarded by the shields.</p>
                </div>
                <div class="living-card pathway-step">
                    <i class="fa fa-shield-alt"></i>
                    <h4>Protection of Lineage</h4>
                    <p>The lineage is protected by ceremony, council, and the living record held within the crest.</p>
                    <p>Each carrier is acknowledged and affirmed in the archive.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="media-merch">
        <div class="container">
            <div class="living-section-title">
                <h2>Media & Merch as Ceremonial Artifacts</h2>
                <p>Music scores, apparel, and recordings are extensions of the Breath-line — artifacts that carry ceremony into the everyday.</p>
            </div>
            <div class="media-merch-grid">
                <div class="living-card">
                    <img src="{{ $secondaryCrestImage }}" alt="Merch Crest">
                    <h3>Merch Crest</h3>
                    <p>Apparel, scores, and ceremonial items are lineage extensions — worn and shared to keep the crest visible.</p>
                    <p class="mb-0"><a href="{{ route('front.shop') }}" class="subtle-link">Enter the Artifact Hall</a></p>
                </div>
                <div class="living-card">
                    <img src="{{ $qrCrestImage }}" alt="QR Crest">
                    <h3>QR Crest</h3>
                    <p>The QR Crest is a digital gateway — a quiet entry into the archive’s living record.</p>
                    <p class="mb-0"><a href="#qr-access" class="subtle-link">Open the QR Gateway</a></p>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="qr-access">
        <div class="container">
            <div class="living-section-title">
                <h2>QR Access</h2>
                <p>The QR Crest offers a direct ceremonial passage — a digital doorway into the lineage archive.</p>
            </div>
            <div class="living-card text-center">
                <img src="{{ $qrCrestImage }}" alt="QR Crest" style="max-width: 320px; margin: 0 auto 18px;">
                <p style="font-size: 16px;">{{ data_get($page, 'header.qr_intro', 'Scan to enter the Living Archive.') }}</p>
                <a href="{{ route('living-archive.donate') }}" class="living-primary-btn" style="margin-top: 10px;">Open the QR Gateway</a>
            </div>
        </div>
    </section>

    <section class="living-section" id="contact-invitations">
        <div class="container">
            <div class="living-section-title">
                <h2>Contact & Invitations</h2>
                <p>Enter the circle through training, ceremony, and direct invitation.</p>
            </div>
            <div class="contact-grid">
                <div class="living-card">
                    <span class="living-pill">Training Invitation</span>
                    <p>Receive training in the Five Feathers lineage and learn the responsibilities of ceremonial care.</p>
                    <div class="contact-actions">
                        <a href="mailto:{{ data_get($contact, 'email', 'info@thomasalexanderthevoice.com') }}">Request Training</a>
                    </div>
                </div>
                <div class="living-card">
                    <span class="living-pill">Ceremonial Events</span>
                    <p>Join ceremonial gatherings that affirm the Breath-line and honor the crest as living memory.</p>
                    <div class="contact-actions">
                        <a href="{{ route('living-archive.donate') }}">See Ceremonial Calendar</a>
                    </div>
                </div>
                <div class="living-card">
                    <span class="living-pill">Contact</span>
                    <p>Email: {{ data_get($contact, 'email', 'info@thomasalexanderthevoice.com') }}</p>
                    <p>Phone: {{ data_get($contact, 'phone', '(to be added)') }}</p>
                    <div class="contact-actions">
                        <a href="mailto:{{ data_get($contact, 'email', 'info@thomasalexanderthevoice.com') }}">Email the Archive</a>
                        <a href="{{ route('living-archive.donate') }}">Offer Support</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="living-section" id="certification">
        <div class="container">
            <div class="living-section-title">
                <h2>Printable Certification</h2>
                <p>Static ceremonial document for carriers within the Five Feathers lineage.</p>
            </div>
            <div class="certification-block">
                <strong>THE FIVE FEATHERS LINEAGE CARRIER CERTIFICATION DOCUMENT</strong>
                <div class="certification-lines">
                    CARRIER NAME: ____________________________<br>
                    CREST ROLE: _______________________________<br>
                    FEATHER DESIGNATION: ______________________<br>
                    DATE RECEIVED: ____________________________<br>
                    WITNESS SIGNATURE: ________________________<br>
                    SEAL OF THE LIVING ARCHIVE: _______________
                </div>
            </div>
        </div>
    </section>
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
                var navHeight = $('.living-crest-nav').outerHeight() || 0;
                $('html, body').stop().animate({ scrollTop: target.offset().top - navHeight }, 700);
            }
        });
    })(jQuery);
</script>
@endpush
