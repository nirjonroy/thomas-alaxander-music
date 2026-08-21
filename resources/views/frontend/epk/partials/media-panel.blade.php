@php
    $logoImage = $epkPage->gold_feather_image
        ? (str_starts_with($epkPage->gold_feather_image, 'http') ? $epkPage->gold_feather_image : asset($epkPage->gold_feather_image))
        : null;
    $videoUrl = trim((string) $epkPage->video_url);
    $youtubeId = null;

    if ($videoUrl && preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([^&?\/]+)/', $videoUrl, $matches)) {
        $youtubeId = $matches[1];
    }

    $hasMedleySection = collect($epkPage->sections ?? [])->contains(fn ($section) => data_get($section, 'type') === 'medley');
    $hasVideoSection = collect($epkPage->sections ?? [])->contains(fn ($section) => data_get($section, 'type') === 'video');
    $isCrooners = $epkPage->slug === 'crooners';
    $mailSubject = rawurlencode($isCrooners ? config('artist_representation.crooners_subject') : config('artist_representation.epk_subject'));
    $bookingEmail = $epkPage->booking_email ?: config('artist_representation.email');
@endphp

<section class="epk-card epk-media">
    @if($logoImage)
        <img class="epk-logo" src="{{ $logoImage }}" alt="{{ $epkPage->gold_feather_image_alt ?: 'Thomas Alexander EPK logo' }}" loading="lazy">
    @endif

    <h3>Booking &amp; Inquiries</h3>
    <p>{{ config('artist_representation.line') }}</p>
    <div class="epk-card__actions">
        <a class="epk-button epk-button--primary" href="mailto:{{ $bookingEmail }}?subject={{ $mailSubject }}">Request EPK</a>
        <a class="epk-button" href="{{ route('front.contact_us', ['inquiry' => 'epk']) }}">Contact Page</a>
    </div>
</section>

@if($epkPage->audio_url && ! $hasMedleySection)
    @php
        $audioSource = str_starts_with($epkPage->audio_url, 'http') ? $epkPage->audio_url : asset($epkPage->audio_url);
    @endphp
    <section class="epk-card epk-media">
        <h3>{{ $epkPage->audio_title ?: 'Audio' }}</h3>
        <audio controls controlsList="nodownload" preload="none">
            <source src="{{ $audioSource }}">
            Your browser does not support the audio element.
        </audio>
    </section>
@endif

@if($videoUrl && ! $hasVideoSection)
    <section class="epk-card epk-media">
        <h3>{{ $epkPage->video_title ?: 'Video' }}</h3>
        @if($youtubeId)
            <iframe src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}?rel=0" title="{{ $epkPage->video_title ?: $epkPage->title . ' video' }}" loading="lazy" allowfullscreen></iframe>
        @else
            <a class="epk-button" href="{{ $videoUrl }}" target="_blank" rel="noopener">Open Video</a>
        @endif
    </section>
@endif
