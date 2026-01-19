@extends('frontend.app')

@section('title', data_get($handoff ?? [], 'page_name', 'The Yamassee Rising - Living Archive'))

@push('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Manrope:wght@400;500;600;700&display=swap');
    :root {
        --earth-ochre: #c9871f;
        --ceremonial-white: #f4efe3;
        --charcoal: #0b0b0a;
        --muted-gold: #b78a2d;
        --forest-green: #1f4b2c;
        --crimson-red: #8c1f28;
        --sky-silver: #bcc6cf;
        --yamassee-blue: #10344d;
        --donate-serif: "Cinzel", serif;
        --donate-sans: "Manrope", sans-serif;
    }
    .living-archive-shell {
        background: radial-gradient(circle at 10% 20%, rgba(16,52,77,0.55), transparent 55%),
                    radial-gradient(circle at 80% 10%, rgba(201,135,31,0.25), transparent 60%),
                    linear-gradient(160deg, #07111c 0%, #0b1726 45%, #0b0b0a 100%);
        color: var(--ceremonial-white);
        padding: 50px 24px 160px;
        font-family: var(--donate-sans);
        min-height: 100vh;
    }
    .living-archive-shell a {
        color: var(--ceremonial-white);
        text-decoration: none;
    }
    .living-archive-shell h1,
    .living-archive-shell h2,
    .living-archive-shell h3 {
        color: #fdf8f0 !important;
    }
    .living-float-group {
        display: none !important;
    }
    body .living-float-group {
        display: none !important;
    }
    .donation-page {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 28px;
        padding: 0 12px;
        align-items: center;
    }
    .donation-hero {
        text-align: left;
        padding: 0 28px;
        width: 100%;
        max-width: 760px;
        margin: 0 auto;
    }
    .donation-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.18);
        text-transform: uppercase;
        letter-spacing: 0.12rem;
        font-size: 12px !important;
        font-weight: 700;
        color: rgba(244,239,227,0.9);
    }
    .donation-hero h1 {
        font-family: var(--donate-serif);
        font-size: clamp(22px, 2.4vw + 10px, 32px) !important;
        margin: 14px 0 6px;
        color: #fdf8f0 !important;
        letter-spacing: 0.05rem;
        text-shadow: 0 8px 24px rgba(0,0,0,0.35);
        line-height: 1.2;
    }
    .living-archive-shell .donation-hero h1 {
        color: #fdf8f0 !important;
    }
    .donation-hero p {
        margin: 0;
        color: rgba(244,239,227,0.78) !important;
        font-size: 16px !important;
        line-height: 1.55;
        text-decoration: none !important;
    }
    .donation-hero h1,
    .donation-hero p,
    .donation-panel label {
        border: 0;
        box-shadow: none;
    }
    .donation-hero h1::before,
    .donation-hero h1::after,
    .donation-hero p::before,
    .donation-hero p::after,
    .donation-badge::before,
    .donation-badge::after,
    .donation-panel label::before,
    .donation-panel label::after,
    .donation-form-card::before,
    .donation-form-card::after {
        content: none !important;
    }
    .crest-hero {
        max-width: 1100px;
        margin: 0 auto 40px;
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 32px;
        padding: 32px;
        background: linear-gradient(120deg, rgba(201,135,31,0.18), rgba(16,52,77,0.25));
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 28px;
        box-shadow: 0 18px 48px rgba(0,0,0,0.35);
        position: relative;
        overflow: hidden;
    }
    .crest-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.04), transparent 45%),
                    radial-gradient(circle at 80% 10%, rgba(16,52,77,0.15), transparent 50%);
        pointer-events: none;
    }
    .crest-mark {
        background: radial-gradient(circle at 30% 30%, rgba(201,135,31,0.35), rgba(11,11,10,0.95));
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 20px;
        display: grid;
        place-content: center;
        text-align: center;
        position: relative;
        z-index: 1;
        color: var(--ceremonial-white);
    }
    .crest-mark .crest-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 2px dashed rgba(255,255,255,0.25);
        display: grid;
        place-content: center;
        margin: 0 auto 12px;
        background: radial-gradient(circle at 50% 40%, rgba(16,52,77,0.35), rgba(11,11,10,0.9));
    }
    .crest-mark .crest-label {
        font-size: 0.95rem;
        letter-spacing: 0.08rem;
        text-transform: uppercase;
        color: #f9f3e7;
    }
    .crest-mark .crest-sub {
        font-size: 0.82rem;
        color: rgba(244,239,227,0.85);
    }
    .hero-copy {
        position: relative;
        z-index: 1;
    }
    .hero-copy .eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.18rem;
        font-size: 0.78rem;
        color: var(--muted-gold);
        margin-bottom: 6px;
    }
    .hero-copy h1 {
        font-family: var(--donate-serif);
        font-size: 2.2rem;
        letter-spacing: 0.05rem;
        margin-bottom: 10px;
    }
    .hero-tagline {
        color: rgba(244,239,227,0.85);
        font-size: 1rem;
        margin-bottom: 18px;
    }
    .cta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .cta {
        padding: 12px 20px;
        border-radius: 999px;
        font-weight: 700;
        letter-spacing: 0.08rem;
        text-transform: uppercase;
        font-size: 0.9rem;
        border: 1px solid var(--earth-ochre);
        transition: transform 0.15s ease, box-shadow 0.2s ease;
        display: inline-block;
    }
    .cta.primary {
        background: linear-gradient(120deg, var(--earth-ochre), #9f6115);
        color: #1c1207;
        box-shadow: 0 10px 26px rgba(201,135,31,0.35);
    }
    .cta.ghost {
        background: transparent;
        color: var(--ceremonial-white);
        border-color: rgba(255,255,255,0.35);
    }
    .cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.3);
    }
    .summary-grid {
        max-width: 1180px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
    }
    .card-block {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 18px;
        padding: 20px 20px 18px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.28);
    }
    .card-block h2 {
        font-size: 1.15rem;
        letter-spacing: 0.06rem;
        text-transform: uppercase;
        color: var(--earth-ochre);
        margin-bottom: 10px;
    }
    .card-block h3 {
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.04rem;
        margin: 12px 0 6px;
        color: var(--muted-gold);
    }
    .card-block p {
        color: rgba(244,239,227,0.82);
        line-height: 1.55;
        margin-bottom: 10px;
    }
    .checklist {
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .checklist li {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        padding: 6px 0;
        border-bottom: 1px dashed rgba(255,255,255,0.08);
        color: rgba(244,239,227,0.9);
        font-size: 0.95rem;
    }
    .checklist li:last-child {
        border-bottom: none;
    }
    .checklist li::before {
        content: "-";
        color: var(--earth-ochre);
        font-weight: 700;
        position: relative;
        top: 1px;
    }
    .checklist .label {
        font-weight: 700;
        color: var(--ceremonial-white);
    }
    .tagline-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .tagline-chip {
        padding: 8px 12px;
        background: rgba(201,135,31,0.12);
        border: 1px solid rgba(201,135,31,0.3);
        border-radius: 12px;
        font-size: 0.9rem;
        color: rgba(244,239,227,0.92);
    }
    .merch-flow {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 12px;
        margin-top: 10px;
    }
    .merch-row {
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(244,239,227,0.9);
        font-size: 0.95rem;
    }
    .merch-row:last-child {
        border-bottom: none;
    }
    .merch-row strong {
        color: var(--ceremonial-white);
    }
    .visual-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .visual-list li {
        padding: 6px 0;
        border-bottom: 1px dashed rgba(255,255,255,0.08);
        color: rgba(244,239,227,0.88);
    }
    .visual-list li:last-child {
        border-bottom: none;
    }
    .color-swatches {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin: 10px 0 6px;
    }
    .swatch {
        border-radius: 12px;
        padding: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        color: #0b0b0a;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04rem;
        font-size: 0.78rem;
    }
    .guide-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .guide-list li {
        padding: 5px 0;
        color: rgba(244,239,227,0.9);
    }
    .coming-soon {
        max-width: 1180px;
        margin: 20px auto;
        padding: 18px 20px;
        border-radius: 14px;
        background: rgba(16,52,77,0.3);
        border: 1px solid rgba(16,52,77,0.5);
        color: var(--ceremonial-white);
        font-weight: 700;
        letter-spacing: 0.05rem;
        text-transform: uppercase;
        box-shadow: 0 16px 32px rgba(0,0,0,0.28);
    }
    .donation-panel {
        margin: 0;
        background: transparent;
        border: none;
        padding: 0;
        box-shadow: none;
        animation: riseIn 0.6s ease-out;
        width: 100%;
    }
    .donation-grid {
        display: grid;
        grid-template-columns: minmax(280px, 1fr) minmax(320px, 420px);
        gap: 28px;
        align-items: start;
    }
    .donation-intro {
        font-family: var(--donate-sans);
    }
    .donation-kicker {
        text-transform: uppercase;
        letter-spacing: 0.2rem;
        font-size: 0.7rem;
        color: rgba(244,239,227,0.65);
        margin-bottom: 10px;
    }
    .donation-intro h2 {
        font-family: var(--donate-serif);
        font-size: 1.9rem;
        letter-spacing: 0.08rem;
        text-transform: uppercase;
        margin-bottom: 10px;
        color: var(--earth-ochre);
    }
    .donation-intro p {
        color: rgba(244,239,227,0.82);
        margin-bottom: 14px;
        line-height: 1.6;
    }
    .donation-list {
        list-style: none;
        padding: 0;
        margin: 0 0 18px;
    }
    .donation-list li {
        padding: 8px 0;
        border-bottom: 1px dashed rgba(255,255,255,0.08);
        color: rgba(244,239,227,0.85);
        font-size: 0.95rem;
        display: flex;
        gap: 8px;
    }
    .donation-list li:last-child {
        border-bottom: none;
    }
    .donation-list .label {
        font-weight: 700;
        color: var(--ceremonial-white);
    }
    .donation-summary {
        border-radius: 16px;
        padding: 14px 16px;
        background: rgba(11,11,10,0.6);
        border: 1px solid rgba(255,255,255,0.08);
    }
    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 6px;
    }
    .summary-label {
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.14rem;
        color: rgba(244,239,227,0.7);
    }
    .donation-total {
        font-family: var(--donate-serif);
        font-size: 1.6rem;
        color: var(--earth-ochre);
    }
    .summary-note {
        font-size: 0.88rem;
        color: rgba(244,239,227,0.7);
        margin: 0;
    }
    .donation-help {
        margin-top: 16px;
        font-size: 0.9rem;
        color: rgba(244,239,227,0.75);
    }
    .donation-help a {
        color: var(--earth-ochre);
        font-weight: 600;
    }
    .donation-form-card {
        background: #fdfaf3 !important;
        border: 1px solid rgba(222,207,184,0.9) !important;
        border-radius: 20px;
        padding: 30px 30px 32px;
        box-shadow: 0 18px 40px rgba(6,10,16,0.35) !important;
        font-family: var(--donate-sans);
        width: 100%;
        max-width: 760px;
        margin: 0;
        position: relative;
        color: #2b2318;
        font-size: 16px;
        line-height: 1.5;
    }
    .donation-form-card .form-group {
        margin-bottom: 16px;
    }
    .stepper {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 18px;
    }
    .step {
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.12rem;
        font-size: 0.72rem;
        color: rgba(244,239,227,0.55);
    }
    .step span {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.18);
        display: grid;
        place-content: center;
        font-weight: 700;
        background: rgba(255,255,255,0.06);
        color: var(--ceremonial-white);
    }
    .step.is-active {
        color: var(--earth-ochre);
    }
    .step.is-active span {
        border-color: var(--earth-ochre);
        background: rgba(201,135,31,0.2);
    }
    .amount-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 12px;
    }
    .amount-chip {
        border: 1px solid #d8c8b0 !important;
        background: #f7f2e8 !important;
        color: #2b2318 !important;
        padding: 12px 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15.9px !important;
        letter-spacing: 0.04rem;
        text-transform: uppercase;
        text-align: center;
        transition: transform 0.15s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        appearance: none;
        cursor: pointer;
        line-height: 1.2 !important;
        opacity: 1 !important;
        text-shadow: none !important;
        -webkit-text-fill-color: #2b2318;
    }
    .amount-chip:hover {
        transform: translateY(-1px);
        border-color: rgba(201,135,31,0.6);
    }
    .amount-chip.is-active {
        background: linear-gradient(120deg, var(--earth-ochre), #9f6115);
        color: #1b1107 !important;
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(201,135,31,0.35);
        -webkit-text-fill-color: #1b1107;
    }
    .amount-input {
        position: relative;
        margin-top: 14px;
    }
    .amount-input input {
        padding-right: 64px;
        height: 52px;
    }
    .amount-currency {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6f5f47;
        font-size: 0.78rem;
        letter-spacing: 0.12rem;
        text-transform: uppercase;
    }
    .donation-panel label {
        text-transform: uppercase;
        font-size: 15.85px !important;
        letter-spacing: 0.1rem;
        color: #2b2318 !important;
        font-family: var(--donate-sans);
        display: block;
        margin-bottom: 6px;
        line-height: 1.2;
        opacity: 1 !important;
        text-decoration: none !important;
        text-shadow: none !important;
        -webkit-text-fill-color: #2b2318;
    }
    .donation-panel .form-control {
        background: #ffffff !important;
        border: 1px solid #d9cbb6;
        color: #1b1b1b !important;
        border-radius: 12px;
        padding: 12px 14px;
        font-family: var(--donate-sans);
        font-size: 15px;
    }
    .donation-panel .form-control::placeholder {
        color: #6f6a60;
    }
    .donation-panel .form-control:focus {
        outline: none;
        border-color: var(--earth-ochre);
        box-shadow: 0 0 0 3px rgba(201,135,31,0.2);
    }
            .donation-panel .btn-donate {
                background: linear-gradient(120deg, var(--earth-ochre), #9f6115);
                border: none;
                color: #1b1107;
        padding: 16px 22px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.12rem;
        border-radius: 999px;
        width: 100%;
        box-shadow: 0 14px 28px rgba(201,135,31,0.35);
        font-family: var(--donate-serif);
        font-size: 15.5px !important;
        transition: transform 0.15s ease, box-shadow 0.2s ease;
        display: block;
        margin-top: 14px;
        cursor: pointer;
        line-height: 1.2;
        opacity: 1 !important;
                text-shadow: none !important;
                -webkit-text-fill-color: #1b1107;
            }
            .donation-panel .btn-donate[disabled] {
                opacity: 0.55;
                cursor: not-allowed;
                box-shadow: none;
                transform: none;
            }
    .donation-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        margin-top: 14px;
    }
    .donation-actions .btn {
        width: 100%;
        margin-top: 0 !important;
    }
    .donation-panel .btn-paypal {
        background: #f7c34b;
        color: #1b1107;
        border: 1px solid #e2b23e;
        padding: 15px 20px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.1rem;
        border-radius: 999px;
        font-family: var(--donate-sans);
        font-size: 15px;
        transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
        box-shadow: 0 14px 24px rgba(25, 20, 8, 0.2);
        cursor: pointer;
    }
    .donation-panel .btn-paypal:hover {
        transform: translateY(-1px);
        background: #f2b940;
        box-shadow: 0 16px 28px rgba(25, 20, 8, 0.24);
    }
    .donation-panel .btn-donate:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(201,135,31,0.4);
    }
    .donation-panel small {
        color: #5e4f3c !important;
        display: block;
        margin-top: 8px;
        font-size: 13px;
        line-height: 1.45;
    }
    .donation-secure {
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 0.82rem;
        color: #5c4d3a !important;
    }
    .donation-secure i {
        color: var(--muted-gold);
        margin-right: 6px;
    }
    .payment-error {
        background: rgba(140,31,40,0.12);
        border: 1px solid rgba(140,31,40,0.5);
        color: #6d1f26;
        border-radius: 12px;
    }
    .has-error .form-control {
        border-color: #d1443a;
        box-shadow: 0 0 0 2px rgba(209,68,58,0.2);
    }
    @keyframes riseIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .footer-line {
        max-width: 1180px;
        margin: 24px auto 0;
        text-align: center;
        padding-top: 10px;
        color: rgba(244,239,227,0.85);
        letter-spacing: 0.08rem;
        text-transform: uppercase;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    @media (max-width: 920px) {
        .living-archive-shell {
            padding: 40px 18px 160px;
        }
        .donation-hero h1 {
            font-size: 2rem;
        }
        .donation-page {
            align-items: stretch;
        }
        .donation-hero {
            padding: 0 20px;
        }
        .donation-form-card {
            margin: 0 auto;
        }
    }
    @media (max-width: 640px) {
        .amount-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .donation-secure {
            flex-direction: column;
            align-items: flex-start;
        }
        .donation-panel .btn-donate {
            font-size: 1rem;
        }
        .donation-form-card {
            padding: 22px;
        }
        .donation-hero {
            padding: 0 14px;
        }
    }
    @media (max-width: 420px) {
        .amount-grid {
            grid-template-columns: 1fr;
        }
        .donation-panel {
            padding: 16px;
        }
        .donation-panel .form-control {
            height: 50px;
        }
        .donation-hero {
            padding: 0 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="living-archive-shell">
    <div class="donation-page">
        <div class="donation-hero" id="top">
            <span class="donation-badge">Living Archive</span>
            <h1>{{ data_get($handoff, 'page_name', 'Support the Living Archive') }}</h1>
            <p>Secure, one-time donation. Fast checkout.</p>
        </div>

        <div class="donation-panel" id="donation">
            <div class="donation-form-card">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form role="form"
                  action="{{ route('stripe.post') }}"
                  method="post"
                  class="require-validation"
                  data-cc-on-file="false"
                  data-stripe-publishable-key="{{ $stripePublishableKey ?? '' }}"
                  data-stripe-enabled="{{ !empty($stripeEnabled) ? '1' : '0' }}"
                  id="donation-payment-form">
                @csrf
                <div class="mb-3 form-group">
                    <label for="donation-amount">Donation amount (USD)</label>
                    <div class="amount-grid" role="group" aria-label="Choose a donation amount">
                        <button type="button" class="amount-chip" data-amount="10" aria-pressed="false">$10</button>
                        <button type="button" class="amount-chip is-active" data-amount="25" aria-pressed="true">$25</button>
                        <button type="button" class="amount-chip" data-amount="50" aria-pressed="false">$50</button>
                        <button type="button" class="amount-chip" data-amount="75" aria-pressed="false">$75</button>
                        <button type="button" class="amount-chip" data-amount="100" aria-pressed="false">$100</button>
                        <button type="button" class="amount-chip" data-amount="250" aria-pressed="false">$250</button>
                    </div>
                    <div class="amount-input">
                        <input type="number" min="5" step="1" class="form-control" id="donation-amount" name="total_amount" value="25" required>
                        <span class="amount-currency">USD</span>
                    </div>
                    <small>Minimum $5.00. Choose a quick amount or enter your own.</small>
                </div>

                <div class="row g-3">
                    <div class="col-md-12 form-group">
                        <label for="card-name">Name on card</label>
                        <input class="form-control" id="card-name" type="text" autocomplete="cc-name" placeholder="Full name" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="card-number">Card number</label>
                        <input class="form-control card-number" id="card-number" type="text" inputmode="numeric" autocomplete="cc-number" placeholder="1234 1234 1234 1234" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="card-cvc">CVC</label>
                        <input class="form-control card-cvc" id="card-cvc" type="text" inputmode="numeric" autocomplete="cc-csc" placeholder="CVC" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="card-exp-month">Exp. month</label>
                        <input class="form-control card-expiry-month" id="card-exp-month" type="text" inputmode="numeric" autocomplete="cc-exp-month" placeholder="MM" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="card-exp-year">Exp. year</label>
                        <input class="form-control card-expiry-year" id="card-exp-year" type="text" inputmode="numeric" autocomplete="cc-exp-year" placeholder="YYYY" required>
                    </div>
                </div>

                <div class="alert alert-danger mt-3 d-none payment-error" role="alert" aria-live="polite">
                    Please correct the highlighted fields.
                </div>

                <input type="hidden" name="order_id" value="">
                <div class="donation-actions">
                    <button class="btn btn-donate" type="submit" @if(empty($stripeEnabled)) disabled aria-disabled="true" @endif>Donate with card</button>
                    @if(!empty($paypalEnabled))
                        <button class="btn btn-paypal" type="button" id="paypal-donate-button">Donate with PayPal</button>
                    @endif
                </div>
                    @if(!empty($stripeEnabled))
                        <div class="donation-secure">
                            <span><i class="fa fa-lock"></i> Secure SSL checkout</span>
                            <span>Card payments powered by Stripe</span>
                        </div>
                    @endif
                </form>
                @if(!empty($paypalEnabled))
                    <form id="paypal-donation-form" action="{{ route('living-archive.paypal') }}" method="post">
                        @csrf
                        <input type="hidden" name="total_amount" id="paypal-amount" value="25">
                    </form>
                @endif
            </div>
        </div>
        <div class="footer-line">{{ data_get($handoff, 'footer', 'The Yamassee Rising - A Living Archive of Ceremony and Song.') }}</div>
    </div>
</div>
@endsection

@push('js')
<script src="https://js.stripe.com/v2/"></script>
<script>
(function($){
    "use strict";
    const floatGroup = document.querySelector('.living-float-group');
    if (floatGroup) {
        floatGroup.style.display = 'none';
    }
    const $form = $('#donation-payment-form');
    const $amountInput = $('#donation-amount');
    const $amountChips = $('.amount-chip');
    const $paypalForm = $('#paypal-donation-form');
    const $paypalAmountInput = $('#paypal-amount');
    const $paypalButton = $('#paypal-donate-button');
    const minAmount = 5;

    function normalizeAmount(value) {
        const digits = String(value || '').replace(/[^0-9]/g, '');
        if (!digits) {
            return null;
        }
        return parseInt(digits, 10);
    }

    function syncPaypalAmount(amount) {
        if ($paypalAmountInput.length) {
            $paypalAmountInput.val(amount || minAmount);
        }
    }

    function updateAmountDisplay(amount) {
        setChipState(amount);
        syncPaypalAmount(amount);
    }

    function setChipState(amount) {
        $amountChips.each(function() {
            const chipAmount = parseInt($(this).data('amount'), 10);
            const isActive = amount && chipAmount === amount;
            $(this)
                .toggleClass('is-active', isActive)
                .attr('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    function digitsOnly(value, maxLen) {
        return String(value || '').replace(/[^0-9]/g, '').slice(0, maxLen);
    }

    $amountChips.on('click', function() {
        const amount = parseInt($(this).data('amount'), 10);
        $amountInput.val(amount);
        updateAmountDisplay(amount);
    });

    $amountInput.on('input', function() {
        const amount = normalizeAmount($(this).val());
        updateAmountDisplay(amount);
    });

    $amountInput.on('blur', function() {
        let amount = normalizeAmount($(this).val());
        if (!amount || amount < minAmount) {
            amount = minAmount;
        }
        $(this).val(amount);
        updateAmountDisplay(amount);
    });

    updateAmountDisplay(normalizeAmount($amountInput.val()) || minAmount);

    $paypalButton.on('click', function() {
        let amount = normalizeAmount($amountInput.val());
        if (!amount || amount < minAmount) {
            amount = minAmount;
            $amountInput.val(amount);
        }
        syncPaypalAmount(amount);
        if ($paypalForm.length) {
            $paypalForm.trigger('submit');
        }
    });

    $('.card-number').on('input', function() {
        const digits = digitsOnly($(this).val(), 19);
        const groups = digits.match(/.{1,4}/g) || [];
        $(this).val(groups.join(' '));
    });

    $('.card-cvc').on('input', function() {
        $(this).val(digitsOnly($(this).val(), 4));
    });

    $('.card-expiry-month').on('input', function() {
        $(this).val(digitsOnly($(this).val(), 2));
    });

    $('.card-expiry-year').on('input', function() {
        $(this).val(digitsOnly($(this).val(), 4));
    });

    $form.on('submit', function(e){
        if ($form.data('stripe-enabled') !== 1 && $form.data('stripe-enabled') !== '1') {
            e.preventDefault();
            $('.payment-error').removeClass('d-none').text('Card payments are currently unavailable.');
            return false;
        }

        const $requiredGroups = $form.find('.required, .form-control');
        let valid = true;
        $form.find('.has-error').removeClass('has-error');
        $requiredGroups.each(function(){
            const $input = $(this);
            if ($input.prop('required') && $input.val().trim() === '') {
                $input.closest('.form-group, .col-md-4, .col-md-12').addClass('has-error');
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            $('.payment-error').removeClass('d-none');
            return false;
        }

        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val().replace(/\s+/g, ''),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });

    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.payment-error').removeClass('d-none').text(response.error.message);
        } else {
            const token = response.id;
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
})(jQuery);
</script>
@endpush


