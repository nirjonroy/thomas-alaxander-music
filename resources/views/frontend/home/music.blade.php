@extends('frontend.app')

@section('seos')
    @php
        $title = 'Thomas Alexander — The Voice | Music';
        $description = 'Jazz. Soul. Blues. Stories Only a Lifetime Can Sing.';
        $url = url()->current();
        $image = asset('uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg');
    @endphp
    @section('title', $title)
    <meta name="title" content="{{ $title }}">
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $url }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $image }}">
@endsection

@push('css')
    <style>
        .music-story-page.common_pages_space {
            padding-top: 48px;
        }
        .music-story {
            --music-ink: #070706;
            --music-panel: rgba(11, 10, 8, 0.92);
            --music-gold: #f1c76b;
            --music-copper: #b96f37;
            --music-cream: #fff7e8;
            --music-muted: rgba(255, 247, 232, 0.78);
            width: min(100%, 1180px);
            margin: 0 auto;
            padding: 20px;
            color: var(--music-cream);
        }
        .music-hero,
        .music-panel,
        .music-booking {
            border: 1px solid rgba(241, 199, 107, 0.26);
            border-radius: 24px;
            background:
                radial-gradient(circle at 84% 12%, rgba(217, 164, 65, 0.13), transparent 34%),
                linear-gradient(145deg, rgba(255, 247, 232, 0.06), rgba(255, 247, 232, 0.015)),
                var(--music-panel);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
        }
        .music-hero {
            min-height: 430px;
            display: flex;
            align-items: flex-end;
            padding: clamp(34px, 6vw, 76px);
            margin-bottom: 24px;
            background:
                linear-gradient(105deg, rgba(7, 7, 6, 0.96), rgba(43, 26, 16, 0.82) 50%, rgba(7, 7, 6, 0.32)),
                url('{{ asset('uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg') }}') center / cover;
        }
        .music-hero__content {
            max-width: 760px;
        }
        .music-eyebrow,
        .music-panel h2,
        .music-booking h2 {
            color: var(--music-gold);
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .music-hero h1,
        .music-panel h2,
        .music-booking h2 {
            font-family: "Cormorant Garamond", Georgia, serif;
            letter-spacing: 0;
        }
        .music-hero h1 {
            margin: 14px 0 14px;
            color: var(--music-cream);
            font-size: clamp(50px, 6.5vw, 90px);
            line-height: 0.96;
        }
        .music-tagline {
            color: var(--music-gold);
            font-size: clamp(22px, 2vw, 30px);
            font-weight: 800;
            line-height: 1.45;
        }
        .music-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, 420px);
            gap: 24px;
            align-items: start;
            margin-bottom: 24px;
        }
        .music-panel,
        .music-booking {
            padding: clamp(26px, 4vw, 46px);
        }
        .music-panel h2,
        .music-booking h2 {
            margin: 0 0 18px;
            font-size: clamp(34px, 3.2vw, 54px);
            line-height: 1.05;
        }
        .music-panel p,
        .music-booking p {
            color: var(--music-muted);
            font-size: clamp(18px, 1.35vw, 22px);
            line-height: 1.82;
        }
        .music-portrait {
            overflow: hidden;
            margin: 0 0 22px;
            border: 1px solid rgba(241, 199, 107, 0.28);
            border-radius: 20px;
            background: #000;
        }
        .music-portrait img {
            width: 100%;
            min-height: 320px;
            object-fit: cover;
            display: block;
            opacity: 0.9;
        }
        .music-booking {
            margin-bottom: 28px;
        }
        .music-booking a {
            color: var(--music-gold);
            font-weight: 800;
        }
        @media (max-width: 991px) {
            .music-grid {
                grid-template-columns: 1fr;
            }
            .music-hero {
                min-height: 340px;
            }
        }
        @media (max-width: 575px) {
            .music-story-page.common_pages_space {
                padding-top: 18px;
            }
            .music-story {
                padding: 12px;
            }
            .music-hero,
            .music-panel,
            .music-booking {
                border-radius: 18px;
            }
            .music-hero {
                min-height: 300px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="ms_index_wrapper common_pages_space music-story-page">
        <div class="music-story">
            <header class="music-hero">
                <div class="music-hero__content">
                    <span class="music-eyebrow">Thomas Alexander — The Voice</span>
                    <h1>Thomas Alexander — The Voice</h1>
                    <p class="music-tagline">Jazz. Soul. Blues. Stories Only a Lifetime Can Sing.</p>
                </div>
            </header>

            <section class="music-panel" aria-labelledby="main-music-narrative">
                <h2 id="main-music-narrative">Main Music Narrative</h2>
                <p>On a rainy evening in a small jazz club — one of those hidden gems like Edmonton’s Yardbird Suite — a hush fell over the room the moment Thomas Alexander stepped onto the stage. At seventy-seven, he carried decades of lived stories in his bones, and every eye followed him as if witnessing a legend return home.</p>
                <p>His voice unfurled like velvet in the smoky air — warm, grounded, familiar.</p>
                <p>A sound shaped by lineage and life: echoes of Luther Vandross, Marvin Gaye, the Isley Brothers, the Motown and Jazz greats, and Barry White, yet unmistakably his own.</p>
                <p>Thomas doesn’t sing to a crowd. He sings to you.</p>
                <p>Every lyric feels like a confession, every phrase like a gift.</p>
            </section>

            <div class="music-grid">
                <section class="music-panel" aria-labelledby="leather-chair-hybrid-narrative">
                    <h2 id="leather-chair-hybrid-narrative">Leather Chair Hybrid Narrative</h2>
                    <p>Seated in a deep leather chair, Thomas Alexander carries the quiet authority of lineage and the refined sophistication of Jazz. The warm tones of leather, the subtle whiskey-room ambiance, and the tailored suit evoke the era when Jazz clubs were sanctuaries of class — places where patrons arrived well-dressed, well-mannered, and ready to savour fine whiskey and finer music.</p>
                    <p>At the same time, the portrait reflects the grounding of his Black Indigenous heritage — Creek, Cherokee, Yamassee, and Copper-coloured skinned homesteader ancestry. It shows a man shaped by lineage and life, whose presence is both artistic and ancestral.</p>
                    <p>As the Clan Mother noted, the photo “gives an authoritative history background vibe” and reveals the lineage-based connectivity between Thomas’s heritage and his artistic identity. Her insight affirms the dual truth visible in the image: Jazz sophistication and ancestral authority living in the same frame.</p>
                    <p>This is Thomas Alexander — The Voice — carrying forward memory, elegance, and history in a single portrait.</p>
                </section>

                <aside class="music-panel" aria-label="Thomas Alexander portrait">
                    <figure class="music-portrait">
                        <img src="{{ asset('uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg') }}" alt="Thomas Alexander portrait and performance imagery" loading="lazy">
                    </figure>
                </aside>
            </div>

            <section class="music-booking" aria-labelledby="music-booking-footer">
                <h2 id="music-booking-footer">Booking Footer</h2>
                <p>Five Feathers Music Agency — Booking Division<br>
                    <a href="mailto:info@thomasalexanderthevoice.com">info@thomasalexanderthevoice.com</a>
                </p>
            </section>
        </div>
    </main>
@endsection
