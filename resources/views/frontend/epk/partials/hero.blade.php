@php
    $heroImage = $epkPage->hero_image
        ? (str_starts_with($epkPage->hero_image, 'http') ? $epkPage->hero_image : asset($epkPage->hero_image))
        : null;
    $logoImage = $epkPage->gold_feather_image
        ? (str_starts_with($epkPage->gold_feather_image, 'http') ? $epkPage->gold_feather_image : asset($epkPage->gold_feather_image))
        : null;
    $bookingEmail = $epkPage->booking_email ?: config('artist_representation.email');
    $isCrooners = $epkPage->slug === 'crooners';
    $mailSubject = rawurlencode($isCrooners ? config('artist_representation.crooners_subject') : config('artist_representation.epk_subject'));
    $heroKicker = $isCrooners ? config('artist_representation.short_line') : 'Full Artist EPK';
@endphp

<header class="epk-hero" @if($heroImage) style="--epk-hero-image: url('{{ $heroImage }}');" @endif>
    @if($logoImage)
        <img class="epk-hero__logo" src="{{ $logoImage }}" alt="{{ $epkPage->gold_feather_image_alt ?: 'Thomas Alexander The Voice logo' }}">
    @endif
    <div class="epk-hero__content">
        <span class="epk-kicker">{{ $heroKicker }}</span>
        <h1 class="epk-title">{{ $epkPage->title }}</h1>
        @if($isCrooners)
            <p class="epk-title__sub">Thomas Alexander — The Voice</p>
        @endif
        @if($epkPage->subtitle)
            <p class="epk-subtitle">{{ $epkPage->subtitle }}</p>
        @endif
        @if($isCrooners)
            <p class="epk-agency-line">{{ config('artist_representation.short_line') }}</p>
        @endif
        <p class="epk-hero__booking">
            <span>Booking &amp; Inquiries:</span>
            <a href="mailto:{{ $bookingEmail }}?subject={{ $mailSubject }}">{{ $bookingEmail }}</a>
        </p>
        <div class="epk-card__actions">
            @if($epkPage->pdf_file)
                <a class="epk-button epk-button--primary" href="{{ route('front.epk.pdf.view', $epkPage->slug) }}" target="_blank" rel="noopener">View EPK PDF</a>
                <a class="epk-button" href="{{ route('front.epk.pdf.download', $epkPage->slug) }}">Download EPK PDF</a>
            @endif
            <a class="epk-button @if(!$epkPage->pdf_file) epk-button--primary @endif" href="mailto:{{ $bookingEmail }}?subject={{ $mailSubject }}">Request EPK</a>
            <a class="epk-button" href="{{ route('front.contact_us', ['inquiry' => 'epk']) }}">Contact Page</a>
        </div>
    </div>
</header>
