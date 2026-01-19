@extends('frontend.app')

@section('title', data_get($handoff ?? [], 'page_name', 'The Yamassee Rising - Living Archive'))

@push('css')
<style>
    :root {
        --earth-ochre: #c9871f;
        --ceremonial-white: #f4efe3;
        --charcoal: #0b0b0a;
        --muted-gold: #b78a2d;
        --forest-green: #1f4b2c;
        --crimson-red: #8c1f28;
        --sky-silver: #bcc6cf;
        --yamassee-blue: #10344d;
    }
    .living-archive-shell {
        background: linear-gradient(135deg, #0b0b0a 0%, #0f0f0e 50%, #0b0b0a 100%);
        color: var(--ceremonial-white);
        padding: 48px 16px 80px;
    }
    .living-archive-shell a {
        color: var(--ceremonial-white);
        text-decoration: none;
    }
    .crest-hero {
        max-width: 1100px;
        margin: 0 auto 32px;
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
        font-size: 0.9rem;
        letter-spacing: 0.08rem;
        text-transform: uppercase;
        color: var(--ceremonial-white);
    }
    .crest-mark .crest-sub {
        font-size: 0.8rem;
        color: rgba(244,239,227,0.7);
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
        font-size: 2rem;
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
        max-width: 820px;
        margin: 26px auto 0;
        background: rgba(11,11,10,0.92);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 22px;
        padding: 28px;
        box-shadow: 0 16px 38px rgba(0,0,0,0.32);
    }
    .donation-panel h2 {
        font-size: 1.4rem;
        letter-spacing: 0.08rem;
        text-transform: uppercase;
        margin-bottom: 8px;
        color: var(--earth-ochre);
    }
    .donation-panel p {
        color: rgba(244,239,227,0.82);
        margin-bottom: 14px;
        line-height: 1.55;
    }
    .donation-panel label {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.1rem;
        color: #ddceb0;
    }
    .donation-panel .form-control {
        background: rgba(18,18,18,0.9);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fefaf0;
    }
    .donation-panel .btn-donate {
        background: linear-gradient(120deg, var(--earth-ochre), #9f6115);
        border: none;
        color: #1b1107;
        padding: 14px 22px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.12rem;
        border-radius: 999px;
        width: 100%;
        box-shadow: 0 14px 28px rgba(201,135,31,0.35);
    }
    .donation-panel small {
        color: #c6b18d;
    }
    .footer-line {
        max-width: 1180px;
        margin: 26px auto 0;
        text-align: center;
        padding-top: 10px;
        color: rgba(244,239,227,0.7);
        letter-spacing: 0.08rem;
        text-transform: uppercase;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    @media (max-width: 920px) {
        .crest-hero {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .crest-mark {
            max-width: 260px;
            margin: 0 auto;
        }
        .cta-row {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="living-archive-shell">
    <div class="crest-hero" id="top">
        <div class="crest-mark">
            <div class="crest-circle">
                <span class="crest-label">Living Crest</span>
            </div>
            <div class="crest-sub">Official crest image here</div>
        </div>
        <div class="hero-copy">
            <div class="eyebrow">Living Archive Team Handoff</div>
            <h1>{{ data_get($handoff, 'page_name', 'The Yamassee Rising - Living Archive') }}</h1>
            <p class="hero-tagline">{{ data_get($handoff, 'taglines.0', 'Carrying the Breath-line, Restoring the Living Memory.') }}</p>
            <div class="cta-row">
                <a class="cta primary" href="#donation">Donate Now</a>
                <a class="cta ghost" href="#contact">Contact and Links</a>
            </div>
        </div>
    </div>

    <div class="summary-grid">
        <section class="card-block">
            <h2>Page Setup Checklist</h2>
            <ul class="checklist">
                <li><span class="label">Page name:</span> {{ data_get($handoff, 'page_name', 'The Yamassee Rising - Living Archive') }}.</li>
                <li><span class="label">Logo:</span> {{ data_get($handoff, 'logo_url') ? 'Configured crest logo' : 'Official Living Crest image (default)' }}.</li>
                <li><span class="label">Primary tagline:</span> {{ data_get($handoff, 'taglines.0', 'Carrying the Breath-line, Restoring the Living Memory.') }}</li>
                <li><span class="label">Contact email:</span> <a href="mailto:{{ data_get($handoff, 'email', 'info@thomasalexanderthevoice.com') }}">{{ data_get($handoff, 'email', 'info@thomasalexanderthevoice.com') }}</a></li>
                <li><span class="label">Phone:</span> {{ data_get($handoff, 'phone', '(to be added)') }}.</li>
                <li><span class="label">Social links:</span>
                    Instagram, Facebook, YouTube
                    @php
                        $social = data_get($handoff, 'social', []);
                    @endphp
                    @if(!empty($social['instagram']) || !empty($social['facebook']) || !empty($social['youtube']))
                        <span>(handles configured)</span>
                    @endif
                </li>
                <li><span class="label">Address:</span> {{ data_get($handoff, 'address', 'Pending; use P.O. Box or publishing company address once registered.') }}</li>
            </ul>
        </section>

        <section class="card-block" id="contact">
            <h2>Text Blocks</h2>
            <div class="text-block">
                <h3>Intro</h3>
                <p>{{ data_get($handoff, 'intro') }}</p>
            </div>
            <div class="text-block">
                <h3>Mission</h3>
                <p>{{ data_get($handoff, 'mission') }}</p>
            </div>
            <div class="text-block">
                <h3>Supporter Acknowledgment</h3>
                <p>{{ data_get($handoff, 'supporter') }}</p>
            </div>
            <div class="text-block">
                <h3>Coming Soon Banner</h3>
                <p>{{ data_get($handoff, 'coming_soon') }}</p>
            </div>
        </section>

        <section class="card-block">
            <h2>Taglines</h2>
            <div class="tagline-chips">
                @forelse(data_get($handoff, 'taglines', []) as $line)
                    <span class="tagline-chip">{{ $line }}</span>
                @empty
                    <span class="tagline-chip">Carrying the Breath-line, Restoring the Living Memory.</span>
                @endforelse
            </div>
            <h3>Merch Catalogue Flow</h3>
            <div class="merch-flow">
                <div class="merch-row"><strong>Apparel:</strong> "{{ data_get($handoff, 'merch.apparel', 'Where the Crest breathes, the lineage lives.') }}"</div>
                <div class="merch-row"><strong>Posters and festival cards:</strong> "{{ data_get($handoff, 'merch.posters', 'Carrying the Breath-line, Restoring the Living Memory.') }}"</div>
                <div class="merch-row"><strong>Music scores and charts:</strong> "{{ data_get($handoff, 'merch.music', 'The Breath-line carried forward, the memory restored.') }}"</div>
                <div class="merch-row"><strong>Donor items:</strong> "{{ data_get($handoff, 'merch.donor', 'Every supporter a carrier of the Living Crest.') }}"</div>
                <div class="merch-row"><strong>Digital products:</strong> "{{ data_get($handoff, 'merch.digital', 'Carrying the Breath-line, Restoring the Living Memory.') }}"</div>
            </div>
        </section>

        <section class="card-block">
            <h2>Visual Hierarchy</h2>
            <ul class="visual-list">
                <li>{{ data_get($handoff, 'visual_hierarchy', 'Primary: Living Crest. Secondary: Tagline. Supporting: Glyphs, feathers, Yamassee wheel. Informational: Mission, supporter text, contact info.') }}</li>
            </ul>
        </section>

        <section class="card-block">
            <h2>Color Palette</h2>
            <div class="color-swatches">
                <div class="swatch" style="background: var(--earth-ochre);">{{ data_get($handoff, 'palette_primary', 'Earth Ochre, Ceremonial White, Charcoal Black') }}</div>
                <div class="swatch" style="background: var(--muted-gold);">{{ data_get($handoff, 'palette_secondary', 'Muted Gold, Forest Green, Crimson Red') }}</div>
                <div class="swatch" style="background: var(--sky-silver); color: #0b0b0a;">{{ data_get($handoff, 'palette_accent', 'Sky Silver, Deep Yamassee Blue') }}</div>
            </div>
            <h3>Background Pairing Guide</h3>
            <ul class="guide-list">
                <li>{{ data_get($handoff, 'background_guide', 'Posters: parchment beige with texture. Apparel: charcoal black, deep blue, or warm brown. Website: ceremonial white or parchment beige; optional deep blue with crest border. Music scores: charcoal black or deep blue, matte/parchment overlay.') }}</li>
            </ul>
        </section>
    </div>

    <div class="coming-soon">{{ data_get($handoff, 'coming_soon', 'The Yamassee Rising Suite - audio recording in progress.') }}</div>

    <div class="donation-panel" id="donation">
        <h2>Support the Living Archive</h2>
        <p>This archive is sustained by the covenant of supporters, each honored as a carrier of the Breath-line. Enter any amount to keep the crest, music, and ceremony moving forward.</p>

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
              data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
              id="donation-payment-form">
            @csrf
            <div class="mb-3 form-group">
                <label for="donation-amount">Donation Amount (USD)</label>
                <input type="number" min="5" step="1" class="form-control" id="donation-amount" name="total_amount" value="25" required>
                <small>Minimum $5.00</small>
            </div>

            <div class="row g-3">
                <div class="col-md-12 form-group">
                    <label>Name on Card</label>
                    <input class="form-control" type="text" required>
                </div>
                <div class="col-md-12 form-group">
                    <label>Card Number</label>
                    <input class="form-control card-number" type="text" autocomplete="off" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>CVC</label>
                    <input class="form-control card-cvc" type="text" autocomplete="off" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Exp. Month</label>
                    <input class="form-control card-expiry-month" type="text" placeholder="MM" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Exp. Year</label>
                    <input class="form-control card-expiry-year" type="text" placeholder="YYYY" required>
                </div>
            </div>

            <div class="alert alert-danger mt-3 d-none payment-error">
                Please correct the highlighted fields.
            </div>

            <input type="hidden" name="order_id" value="">
            <button class="btn btn-donate mt-4" type="submit" style="font-size:22px">Donate Now</button>
        </form>
    </div>

    <div class="footer-line">{{ data_get($handoff, 'footer', 'The Yamassee Rising - A Living Archive of Ceremony and Song.') }}</div>
</div>
@endsection

@push('js')
<script src="https://js.stripe.com/v2/"></script>
<script>
(function($){
    "use strict";
    const $form = $('#donation-payment-form');
    $form.on('submit', function(e){
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
                number: $('.card-number').val(),
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


