@php
    $sectionTitle = trim((string) data_get($section, 'title'));
    $sectionBody = trim((string) data_get($section, 'body'));
    $sectionType = trim((string) data_get($section, 'type', 'content'));
    $items = collect(data_get($section, 'items', []))->filter(fn ($item) => $item !== null && $item !== '');
    $isCrooners = $epkPage->slug === 'crooners';
@endphp

@if($sectionTitle !== '' || $sectionBody !== '' || $items->isNotEmpty())
    <section class="epk-card epk-card--{{ \Illuminate\Support\Str::slug($sectionType) }}">
        @if($sectionTitle !== '')
            <h3>{{ $sectionTitle }}</h3>
        @endif

        @if($sectionType === 'performance_lanes' && $items->isNotEmpty())
            <div class="epk-lane-grid">
                @foreach($items as $item)
                    <div class="epk-lane">
                        <strong>{{ data_get($item, 'title') }}</strong>
                        <span>{{ data_get($item, 'body') }}</span>
                    </div>
                @endforeach
            </div>
        @elseif($sectionType === 'engagements' && $items->isNotEmpty())
            <ul class="epk-engagement-list">
                @foreach($items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @elseif($sectionType === 'tags' && $items->isNotEmpty())
            <div class="epk-tag-list" aria-label="{{ $sectionTitle }}">
                @foreach($items as $item)
                    <span>{{ $item }}</span>
                @endforeach
            </div>
        @elseif($sectionType === 'testimonials' && $items->isNotEmpty())
            <div class="epk-quote-grid">
                @foreach($items as $item)
                    <figure class="epk-quote">
                        <blockquote>{{ data_get($item, 'quote') }}</blockquote>
                        <figcaption>
                            <strong>{{ data_get($item, 'source') }}</strong>
                            @if(data_get($item, 'credential'))
                                <span>{{ data_get($item, 'credential') }}</span>
                            @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @elseif($sectionType === 'video')
            @php
                $videoUrl = trim((string) (data_get($section, 'url') ?: $epkPage->video_url));
                $youtubeId = null;

                if ($videoUrl && preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([^&?\/]+)/', $videoUrl, $matches)) {
                    $youtubeId = $matches[1];
                }
            @endphp
            @if($sectionBody !== '')
                <div class="epk-copy">{!! clean($sectionBody) !!}</div>
            @endif
            @if($youtubeId)
                <div class="epk-video-frame">
                    <iframe src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}?rel=0"
                        title="{{ data_get($section, 'video_title', $epkPage->video_title ?: $epkPage->title . ' live performance') }}"
                        loading="lazy"
                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                </div>
            @elseif($videoUrl)
                <a class="epk-button" href="{{ $videoUrl }}" target="_blank" rel="noopener">Open Live Performance</a>
            @endif
        @elseif($sectionType === 'medley')
            @php
                $heroImage = $epkPage->hero_image
                    ? (str_starts_with($epkPage->hero_image, 'http') ? $epkPage->hero_image : asset($epkPage->hero_image))
                    : null;
                $logoImage = $epkPage->gold_feather_image
                    ? (str_starts_with($epkPage->gold_feather_image, 'http') ? $epkPage->gold_feather_image : asset($epkPage->gold_feather_image))
                    : null;
            @endphp
            <figure class="epk-medley" @if($heroImage) style="--epk-medley-image: url('{{ $heroImage }}');" @endif>
                @if($logoImage)
                    <img src="{{ $logoImage }}" alt="{{ $epkPage->gold_feather_image_alt ?: 'Thomas Alexander The Voice logo' }}" loading="lazy">
                @endif
                <figcaption>{!! clean($sectionBody) !!}</figcaption>
            </figure>
            @if($epkPage->audio_url)
                @php
                    $audioSource = str_starts_with($epkPage->audio_url, 'http') ? $epkPage->audio_url : asset($epkPage->audio_url);
                @endphp
                <audio controls controlsList="nodownload" preload="none" aria-label="{{ $epkPage->audio_title ?: 'Thomas Alexander audio medley' }}">
                    <source src="{{ $audioSource }}">
                    Your browser does not support the audio element.
                </audio>
            @endif
        @elseif($sectionType === 'booking')
            @php
                $bookingEmail = $epkPage->booking_email ?: config('artist_representation.email');
                $mailSubject = rawurlencode($isCrooners ? config('artist_representation.crooners_subject') : config('artist_representation.epk_subject'));
            @endphp
            <div class="epk-copy">{!! clean($sectionBody) !!}</div>
            <div class="epk-card__actions">
                <a class="epk-button epk-button--primary" href="mailto:{{ $bookingEmail }}?subject={{ $mailSubject }}">Request EPK</a>
                <a class="epk-button" href="{{ route('front.contact_us', ['inquiry' => 'epk']) }}">Contact Page</a>
            </div>
        @elseif($sectionBody !== '')
            <div class="epk-copy">{!! clean($sectionBody) !!}</div>
        @endif
    </section>
@endif
