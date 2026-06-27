@php
    $closingIdentityText = trim((string) ($closingIdentityText
        ?? optional(DB::table('settings')->select('dual_identity_summary_bar')->first())->dual_identity_summary_bar
        ?? ''));

    if ($closingIdentityText === '') {
        $closingIdentityText = 'Chief & Elder • Executive Artist • Founder & Director • The Voice';
    }
@endphp

@once
    <style>
        .closing-identity-bar {
            padding: 0 24px 44px;
            background: transparent;
        }
        .closing-identity-bar-inner {
            width: min(100%, 980px);
            margin: 0 auto;
            padding: 18px 24px;
            text-align: center;
            color: #f6edd0;
            font-family: "Cinzel", Georgia, serif;
            font-size: clamp(15px, 2.4vw, 20px);
            font-weight: 600;
            line-height: 1.6;
            letter-spacing: 0.08em;
            border-top: 1px solid rgba(201, 162, 39, 0.35);
            border-bottom: 1px solid rgba(201, 162, 39, 0.35);
            background:
                linear-gradient(90deg, rgba(184, 134, 11, 0), rgba(201, 162, 39, 0.12), rgba(184, 134, 11, 0)),
                rgba(10, 10, 12, 0.72);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.25);
        }
        @media (max-width: 576px) {
            .closing-identity-bar {
                padding: 0 14px 34px;
            }
            .closing-identity-bar-inner {
                padding: 15px 16px;
                letter-spacing: 0.045em;
            }
        }
    </style>
@endonce

<section class="closing-identity-bar" aria-label="Thomas Alexander identity">
    <div class="closing-identity-bar-inner">
        {{ $closingIdentityText }}
    </div>
</section>
