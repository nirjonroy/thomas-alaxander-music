@extends('frontend.app')

@php
    $isEpkInquiry = request('inquiry') === 'epk';
    $bookingEmail = config('artist_representation.email', 'info@thomasalexanderthevoice.com');
    $subject = $isEpkInquiry
        ? 'EPK Request — Thomas Alexander (The Voice)'
        : config('artist_representation.booking_subject', 'Booking Inquiry - Thomas Alexander (The Voice)');
    $siteName = siteInfo()->site_name ?? siteInfo()->website_name ?? config('app.name', 'Thomas Alexander');
    $pageTitle = $isEpkInquiry ? 'Request EPK | Thomas Alexander - The Voice' : 'Contact Thomas Alexander - The Voice';
    $pageDescription = $isEpkInquiry
        ? 'Request the Thomas Alexander - The Voice EPK through Five Feathers Music Agency for artist booking, performance inquiries, and professional representation.'
        : 'Contact Thomas Alexander - The Voice for booking, inquiries, and general messages.';
    $canonical = $isEpkInquiry ? url()->full() : route('front.contact_us');
    $fallbackLogo = siteInfo()->logo ?? null;
    $metaImage = $fallbackLogo ? asset($fallbackLogo) : asset('images/og-default.jpg');
    $contactSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => $pageTitle,
        'description' => $pageDescription,
        'url' => $canonical,
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => 'Five Feathers Music Agency',
            'email' => $bookingEmail,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => $bookingEmail,
                'contactType' => 'booking and EPK inquiries',
            ],
        ],
    ];
@endphp

@section('title', request('inquiry') === 'epk' ? 'EPK Booking Inquiry' : 'Contact Us')

@section('seos')
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:alt" content="{{ $siteName }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <script type="application/ld+json">{!! json_encode($contactSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@push('css')
    <style>
        .contact-page.common_pages_space {
            padding-top: 42px;
        }
        .contact-shell {
            --contact-ink: #070706;
            --contact-panel: rgba(11, 10, 8, 0.92);
            --contact-gold: #f1c76b;
            --contact-copper: #b96f37;
            --contact-cream: #fff7e8;
            --contact-muted: rgba(255, 247, 232, 0.78);
            width: min(100%, 1160px);
            margin: 0 auto;
            padding: 20px;
            color: var(--contact-cream);
        }
        .contact-hero,
        .contact-card {
            border: 1px solid rgba(241, 199, 107, 0.28);
            border-radius: 24px;
            background:
                radial-gradient(circle at 82% 16%, rgba(217, 164, 65, 0.13), transparent 34%),
                linear-gradient(145deg, rgba(255, 247, 232, 0.07), rgba(255, 247, 232, 0.02)),
                var(--contact-panel);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
        }
        .contact-hero {
            padding: clamp(32px, 5vw, 64px);
            margin-bottom: 24px;
        }
        .contact-eyebrow {
            display: block;
            margin-bottom: 12px;
            color: var(--contact-gold);
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .contact-hero h1,
        .contact-card h2 {
            margin: 0 0 14px;
            color: var(--contact-cream);
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: clamp(42px, 5vw, 72px);
            line-height: 1.02;
            letter-spacing: 0;
        }
        .contact-hero p,
        .contact-card p,
        .contact-card label {
            color: var(--contact-muted);
            font-size: clamp(17px, 1.2vw, 20px);
            line-height: 1.75;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr);
            gap: 24px;
            align-items: start;
        }
        .contact-card {
            padding: clamp(26px, 4vw, 42px);
        }
        .contact-booking {
            margin-top: 22px;
        }
        .contact-social {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }
        .contact-social a,
        .contact-mail-link,
        .contact-submit {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border: 1px solid rgba(241, 199, 107, 0.38);
            border-radius: 999px;
            color: var(--contact-gold);
            background: rgba(7, 7, 6, 0.58);
            font-weight: 800;
            text-decoration: none;
        }
        .contact-social a:hover,
        .contact-mail-link:hover,
        .contact-submit:hover {
            color: var(--contact-ink);
            background: linear-gradient(135deg, var(--contact-gold), var(--contact-copper));
        }
        .contact-field {
            display: grid;
            gap: 8px;
            margin-bottom: 18px;
        }
        .contact-field input,
        .contact-field textarea {
            width: 100%;
            border: 1px solid rgba(241, 199, 107, 0.24);
            border-radius: 14px;
            background: rgba(255, 247, 232, 0.96);
            color: #17100a;
            font-size: 18px;
            line-height: 1.5;
            padding: 12px 14px;
            outline: none;
        }
        .contact-field input:focus,
        .contact-field textarea:focus,
        .contact-submit:focus-visible,
        .contact-social a:focus-visible,
        .contact-mail-link:focus-visible {
            outline: 3px solid rgba(241, 199, 107, 0.74);
            outline-offset: 3px;
        }
        .contact-field textarea {
            min-height: 150px;
            resize: vertical;
        }
        .contact-submit {
            cursor: pointer;
            color: var(--contact-ink);
            background: linear-gradient(135deg, var(--contact-gold), var(--contact-copper));
        }
        @media (max-width: 991px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 575px) {
            .contact-page.common_pages_space {
                padding-top: 18px;
            }
            .contact-shell {
                padding: 12px;
            }
            .contact-hero,
            .contact-card {
                border-radius: 18px;
            }
            .contact-social a,
            .contact-mail-link,
            .contact-submit {
                width: 100%;
            }
        }
        .contact-page--epk span,
        .contact-page--epk h1,
        .contact-page--epk h2,
        .contact-page--epk h3,
        .contact-page--epk h4,
        .contact-page--epk h5,
        .contact-page--epk h6 {
            font-size: 14px;
        }
    </style>
@endpush

@section('content')

    <main class="ms_index_wrapper common_pages_space contact-page {{ $isEpkInquiry ? 'contact-page--epk' : '' }}">
        <div class="contact-shell">
            <header class="contact-hero">
                <span class="contact-eyebrow">{{ $isEpkInquiry ? 'EPK Inquiry' : 'Contact' }}</span>
                <h1>{{ $isEpkInquiry ? 'Request EPK' : 'Contact Us' }}</h1>
                <p>
                    {{ $isEpkInquiry
                        ? 'For EPK requests, artist bookings, and professional performance inquiries, contact Five Feathers Music Agency.'
                        : 'We’re here to assist you. If you have any questions or need assistance, please reach out.' }}
                </p>
            </header>

            <div class="contact-grid">
                <section class="contact-card" aria-labelledby="contact-booking">
                    <h2 id="contact-booking">Booking &amp; Inquiries</h2>
                    <p>Five Feathers Music Agency</p>
                    <p>The Artist Thomas Alexander — The Voice is booked and exclusively presented and represented by Five Feathers Music Agency.</p>
                    <p>
                        <a class="contact-mail-link" href="mailto:{{ $bookingEmail }}?subject={{ rawurlencode($subject) }}">
                            {{ $bookingEmail }}
                        </a>
                    </p>

                    <div class="contact-booking">
                        @include('frontend.partials.artist_representation', [
                            'variant' => 'short',
                            'subject' => $subject,
                        ])
                    </div>

                    <div class="contact-social" aria-label="Social links">
                        <a href="https://twitter.com/example" target="_blank" rel="noopener">Twitter/X</a>
                        <a href="https://www.instagram.com/example/" target="_blank" rel="noopener">Instagram</a>
                    </div>
                </section>

                <section class="contact-card" aria-labelledby="contact-form-title">
                    <h2 id="contact-form-title">Contact Form</h2>
                    <form action="https://fabform.io/f/{form-id}" method="post">
                        <input type="hidden" name="subject" value="{{ $subject }}">
                        <div class="contact-field">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="contact-field">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="contact-field">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" required>{{ $isEpkInquiry ? 'I would like to request the Thomas Alexander EPK.' : '' }}</textarea>
                        </div>
                        <button type="submit" class="contact-submit">Send Message</button>
                    </form>
                </section>
            </div>
        </div>
    </main>
@endsection
