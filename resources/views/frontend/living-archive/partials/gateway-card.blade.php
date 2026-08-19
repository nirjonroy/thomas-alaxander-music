<article class="living-archive-gateway-card">
    <div class="living-archive-gateway-card__top">
        <span class="living-archive-gateway-card__eyebrow">{{ $gateway['eyebrow'] }}</span>
        <span class="living-archive-gateway-card__motif" aria-hidden="true">
            {!! $iconSvg($gateway['icon'], 'living-svg-icon') !!}
        </span>
    </div>

    <h3 class="living-archive-gateway-card__title">{{ $gateway['title'] }}</h3>
    <p class="living-archive-gateway-card__teaser">{{ $gateway['teaser'] }}</p>

    @if (!empty($gateway['children']) && $gateway['children']->isNotEmpty())
        <ul class="living-archive-gateway-card__list">
            @foreach ($gateway['children'] as $child)
                <li>
                    <a href="{{ $child['url'] }}">{{ $child['title'] }}</a>
                </li>
            @endforeach
        </ul>
    @endif

    <a class="living-archive-gateway-card__cta" href="{{ $gateway['url'] }}" aria-label="{{ $gateway['cta_label'] }}">
        {{ $gateway['cta_label'] }}
    </a>
</article>
