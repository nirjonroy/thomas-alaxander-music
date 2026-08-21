@php
    $variant = $variant ?? 'full';
    $agency = config('artist_representation.agency');
    $email = config('artist_representation.email');
    $line = $variant === 'short'
        ? config('artist_representation.short_line')
        : config('artist_representation.line');
    $subject = rawurlencode($subject ?? config('artist_representation.booking_subject'));
@endphp

<section class="artist-representation artist-representation--{{ $variant }}" aria-label="Artist representation">
    <span class="artist-representation__kicker">{{ $agency }}</span>
    <p class="artist-representation__line">{{ $line }}</p>
    <p class="artist-representation__contact">
        <span>{{ config('artist_representation.booking_label') }}:</span>
        <a href="mailto:{{ $email }}?subject={{ $subject }}">{{ $email }}</a>
    </p>
</section>

@once
    <style>
        .artist-representation {
            display: grid;
            gap: 8px;
            padding: 18px;
            border: 1px solid rgba(217, 164, 65, 0.3);
            border-radius: 18px;
            color: rgba(255, 247, 232, 0.84);
            background: linear-gradient(145deg, rgba(255, 247, 232, 0.07), rgba(255, 247, 232, 0.02)), rgba(10, 10, 8, 0.88);
        }
        .artist-representation__kicker {
            color: #f1c76b;
            font-size: 0.74rem;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .artist-representation__line,
        .artist-representation__contact {
            margin: 0;
            line-height: 1.6;
        }
        .artist-representation__line {
            font-size: 13px;
        }
        .artist-representation__contact {
            display: grid;
            gap: 2px;
            font-weight: 800;
        }
        .artist-representation__contact span {
            color: #fff7e8;
        }
        .artist-representation a {
            color: #f1c76b;
            overflow-wrap: anywhere;
        }
        .artist-representation a:focus-visible {
            outline: 3px solid rgba(241, 199, 107, 0.74);
            outline-offset: 3px;
        }
        .artist-representation--short {
            padding: 14px 16px;
            border-radius: 14px;
        }
        .artist-representation--short .artist-representation__line {
            font-size: 13px;
        }
    </style>
@endonce
