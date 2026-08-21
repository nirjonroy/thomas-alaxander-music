@extends('frontend.app')

@section('seos')
    @php
        $title = 'Thomas Alexander — Chief & Elder | Living Legacy';
        $description = 'Thomas Alexander carries a unified Black Indigenous lineage rooted in Creek, Cherokee, Yamassee, and Copper-coloured skinned homesteader ancestry.';
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
        .legacy-page.common_pages_space {
            padding-top: 48px;
        }
        .legacy-wrap {
            --legacy-ink: #070706;
            --legacy-panel: rgba(11, 10, 8, 0.92);
            --legacy-gold: #f1c76b;
            --legacy-copper: #b96f37;
            --legacy-cream: #fff7e8;
            --legacy-muted: rgba(255, 247, 232, 0.76);
            width: min(100%, 1180px);
            margin: 0 auto;
            padding: 20px;
            color: var(--legacy-cream);
        }
        .legacy-hero,
        .legacy-section,
        .legacy-identity,
        .legacy-closing {
            border: 1px solid rgba(241, 199, 107, 0.26);
            border-radius: 24px;
            background:
                radial-gradient(circle at 82% 16%, rgba(217, 164, 65, 0.12), transparent 32%),
                linear-gradient(145deg, rgba(255, 247, 232, 0.06), rgba(255, 247, 232, 0.015)),
                var(--legacy-panel);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
        }
        .legacy-hero {
            padding: clamp(34px, 6vw, 76px);
            margin-bottom: 24px;
            background:
                linear-gradient(120deg, rgba(7, 7, 6, 0.96), rgba(43, 26, 16, 0.84)),
                url('{{ asset('uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg') }}') center / cover;
        }
        .legacy-eyebrow,
        .legacy-section h2,
        .legacy-feather {
            color: var(--legacy-gold);
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .legacy-hero h1,
        .legacy-section h2,
        .legacy-closing p {
            font-family: "Cormorant Garamond", Georgia, serif;
            letter-spacing: 0;
        }
        .legacy-hero h1 {
            max-width: 860px;
            margin: 14px 0 12px;
            color: var(--legacy-cream);
            font-size: clamp(48px, 6vw, 86px);
            line-height: 0.98;
        }
        .legacy-hero p,
        .legacy-section p,
        .legacy-identity-note {
            color: var(--legacy-muted);
            font-size: clamp(18px, 1.45vw, 22px);
            line-height: 1.82;
        }
        .legacy-subtitle {
            max-width: 860px;
            color: var(--legacy-gold);
            font-weight: 700;
            line-height: 1.55;
        }
        .legacy-section {
            padding: clamp(26px, 4vw, 46px);
            margin-bottom: 20px;
        }
        .legacy-section h2 {
            margin: 0 0 18px;
            font-size: clamp(34px, 3.8vw, 58px);
            line-height: 1.05;
        }
        .legacy-portrait-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            gap: 28px;
            align-items: center;
        }
        .legacy-portrait {
            overflow: hidden;
            border: 1px solid rgba(241, 199, 107, 0.28);
            border-radius: 22px;
            background: #000;
        }
        .legacy-portrait img {
            width: 100%;
            min-height: 360px;
            object-fit: cover;
            display: block;
            opacity: 0.9;
        }
        .legacy-feathers {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin: 24px 0;
            padding: 0;
            list-style: none;
        }
        .legacy-feather {
            min-height: 104px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            border: 1px solid rgba(241, 199, 107, 0.28);
            border-radius: 999px;
            background: rgba(217, 164, 65, 0.08);
            text-align: center;
            font-size: clamp(16px, 1.1vw, 19px);
            line-height: 1.4;
        }
        .legacy-closing {
            padding: clamp(26px, 4vw, 46px);
            text-align: center;
        }
        .legacy-closing p {
            max-width: 920px;
            margin: 0 auto;
            color: var(--legacy-cream);
            font-size: clamp(30px, 3vw, 44px);
            line-height: 1.35;
        }
        @media (max-width: 991px) {
            .legacy-portrait-grid,
            .legacy-feathers {
                grid-template-columns: 1fr;
            }
            .legacy-feather {
                min-height: auto;
                border-radius: 18px;
            }
        }
        @media (max-width: 575px) {
            .legacy-page.common_pages_space {
                padding-top: 18px;
            }
            .legacy-wrap {
                padding: 12px;
            }
            .legacy-hero,
            .legacy-section,
            .legacy-closing {
                border-radius: 18px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="ms_index_wrapper common_pages_space legacy-page">
        <div class="legacy-wrap">
            <header class="legacy-hero">
                <span class="legacy-eyebrow">Five Feathers Lineage Society</span>
                <h1>Thomas Alexander — Chief &amp; Elder</h1>
                <p class="legacy-subtitle">Living Archive of the Creek, Cherokee, Yamassee, and Copper-coloured Skinned Homesteader Heritage</p>
            </header>

            <section class="legacy-section" aria-labelledby="legacy-introduction">
                <h2 id="legacy-introduction">Living Legacy Introduction</h2>
                <p>Thomas Alexander carries a unified Black Indigenous lineage rooted in Creek, Cherokee, Yamassee, and Copper-coloured skinned homesteader ancestry. His family’s footsteps echo across ancient tribal lands and the prairie soil of Alberta, where Black Indigenous homesteaders built communities that shaped the province’s early history.</p>
                <p>As Chief &amp; Elder of the Five Feathers Lineage Society, Thomas preserves and presents this heritage through ceremony, narrative, and cultural stewardship. His lineage was whispered in his ear by his mother, right up to his great-grandmothers — a breathline of resilience carried forward into the present day.</p>
            </section>

            <section class="legacy-section" aria-labelledby="clan-mother-governance">
                <h2 id="clan-mother-governance">Clan Mother Governance</h2>
                <p>In Indigenous culture, the Clan Mothers are the true chiefs — the original holders of authority, memory, and connection to the land. Their leadership is rooted in lineage, responsibility, and ancestral continuity. Historically, colonial powers refused to negotiate with women, imposing their own patriarchal systems onto Indigenous nations.</p>
                <p>To protect their sovereignty and ensure their voices were still heard, the Clan Mothers appointed men to stand as chiefs on their behalf. These men were not replacements — they were representatives chosen by the Clan Mothers to carry out leadership duties in a world shaped by colonial restrictions.</p>
                <p>This is why the recognition of an Ancestral Clan Mother carries profound weight. Her acknowledgment is not symbolic; it is authoritative. It reflects the original governance structure, the lineage-based truth, and the cultural legitimacy that predates colonial interference.</p>
            </section>

            <section class="legacy-section" aria-labelledby="leather-chair-lineage">
                <div class="legacy-portrait-grid">
                    <figure class="legacy-portrait">
                        <img src="{{ asset('uploads/custom-images/slider-2025-10-14-11-55-21-8097.jpg') }}" alt="Thomas Alexander portrait and performance imagery" loading="lazy">
                    </figure>
                    <div>
                        <h2 id="leather-chair-lineage">Leather Chair Lineage Narrative</h2>
                        <p>The Ancestral Clan Mother recognized the truth immediately.</p>
                        <p>In this portrait, Thomas Alexander sits grounded in a leather chair — a symbol of lineage, authority, and ancestral continuity. The image carries the weight of history: the quiet strength of Creek, Cherokee, Yamassee, and Copper-coloured skinned homesteader heritage. It reflects the presence of a man whose identity is rooted in generations of resilience and cultural memory.</p>
                        <p>She saw what the photo reveals: lineage-based connectivity, an authoritative historical presence, and the breathline carried forward through him. Her words affirm the ancestral grounding visible in the image — a visual declaration of heritage, responsibility, and the living archive Thomas embodies.</p>
                        <p>This is not simply a portrait — it is a lineage statement.</p>
                    </div>
                </div>
            </section>

            <section class="legacy-section legacy-identity" aria-labelledby="five-feathers-identity">
                <h2 id="five-feathers-identity">Five Feathers Identity</h2>
                <ul class="legacy-feathers">
                    <li class="legacy-feather">Creek</li>
                    <li class="legacy-feather">Cherokee</li>
                    <li class="legacy-feather">Yamassee</li>
                    <li class="legacy-feather">Copper-coloured skinned homesteader heritage</li>
                    <li class="legacy-feather">One feather left open for future ancestral confirmation</li>
                </ul>
                <p class="legacy-identity-note">It is a living crest — a cultural emblem carried forward through ceremony, narrative, and artistic expression.</p>
            </section>

            <section class="legacy-section" aria-labelledby="blue-alberta-blue">
                <h2 id="blue-alberta-blue">Blue Alberta Blue Heritage Statement</h2>
                <p>Blue Alberta Blue is rooted in the Black Indigenous homesteader history of Alberta — a legacy carried forward by Thomas Alexander, Chief &amp; Elder of the Five Feathers Lineage Society, and performed through his artistic identity as The Voice.</p>
            </section>

            <section class="legacy-closing" aria-label="Living Archive Closing Statement">
                <p>Every note Thomas sings, every crest he wears, every chart that memory stirs — is a thread in his ancestral tapestry. This is his offering. This is who he is. This is the Living Archive of The Voice.</p>
            </section>
        </div>
    </main>
@endsection
