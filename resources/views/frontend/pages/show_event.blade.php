@extends('frontend.app')

@section('title', $event->meta_title ?: ($event->seo_title ?: $event->name))
@section('seos')
@php
    use Illuminate\Support\Str;

    $eventName = $event->name;
    $pageUrl   = url()->current();
    $seoDefaults = \App\Models\SeoSetting::where('page_name', 'Events')->first();
    $siteName = $event->site_name ?: (optional($seoDefaults)->site_name ?? (siteInfo()->site_name ?? 'Website'));
    $pageTitle = $event->meta_title ?: ($event->seo_title ?: $eventName);
    $rawDesc = $event->meta_description ?: ($event->seo_description ?: ($event->description ?: $eventName));
    $desc = Str::limit(strip_tags($rawDesc), 180);
    $canonical = $event->canonical_url ?: $pageUrl;
    $keywords = $event->seo_keywords ?: (optional($seoDefaults)->seo_keywords ?? $eventName);
    $author = $event->seo_author ?: (optional($seoDefaults)->seo_author ?? $siteName);
    $publisher = $event->seo_publisher ?: (optional($seoDefaults)->seo_publisher ?? $siteName);
    $copyright = $event->meta_copyright ?: optional($seoDefaults)->meta_copyright;

    $imgRaw = $event->image;
    $eventImage = null;
    if ($imgRaw) {
        if (preg_match('#^(https?:)?//#', $imgRaw)) {
            $eventImage = $imgRaw;
        } elseif (strpos($imgRaw, '/') !== false) {
            $eventImage = asset($imgRaw);
        } else {
            $eventImage = asset('uploads/custom-images/'.$imgRaw);
        }
    }
    $metaImageValue = $event->meta_image ?: optional($seoDefaults)->meta_image;
    $metaImage = $eventImage ?: ($metaImageValue
        ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
        : asset(siteInfo()->logo));

    $start = \Carbon\Carbon::parse(trim(($event->date ?? '').' '.($event->time ?? '')));
    $end   = $start ? (clone $start)->addHours(3) : null;
@endphp

<title>{{ $pageTitle }}</title>
<link rel="canonical" href="{{ $canonical }}"/>

<meta name="robots" content="index, follow, max-image-preview:large">
<meta name="title" content="{{ $pageTitle }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">
<meta name="publisher" content="{{ $publisher }}">
@if ($copyright)
  <meta name="copyright" content="{{ $copyright }}">
@endif
<meta name="description" content="{{ $desc }}"/>

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:secure_url" content="{{ $metaImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $pageTitle }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $desc }}">
<meta name="twitter:url" content="{{ $canonical }}">
<meta name="twitter:image" content="{{ $metaImage }}">

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": {{ json_encode($eventName) }},
  "description": {{ json_encode($desc) }},
  "startDate": {{ json_encode(optional($start)->toIso8601String()) }},
  "endDate": {{ json_encode(optional($end)->toIso8601String()) }},
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "location": {
    "@type": "Place",
    "name": {{ json_encode($event->location ?? '') }},
    "address": {{ json_encode($event->location ?? '') }}
  },
  "image": [{{ json_encode($metaImage) }}],
  "offers": {
    "@type": "Offer",
    "price": {{ json_encode((string)($event->ticket_price ?? 0)) }},
    "priceCurrency": "USD",
    "availability": "https://schema.org/InStock",
    "url": {{ json_encode($canonical) }}
  },
  "organizer": {
    "@type": "Organization",
    "name": {{ json_encode($siteName) }},
    "url": {{ json_encode(url('/')) }}
  }
}
</script>
@endsection

@section('content')
@php
  $eventImageSrc = null;
  if (!empty($event->image)) {
      $img = $event->image;
      if (preg_match('#^(https?:)?//#', $img)) {
          $eventImageSrc = $img;
      } elseif (strpos($img, '/') !== false) {
          $eventImageSrc = asset($img);
      } else {
          $eventImageSrc = asset('uploads/custom-images/'.$img);
      }
  }

  $eventImageSrc = $eventImageSrc ?: asset(siteInfo()->logo);
  $eventPageTitle = $event->meta_title ?: ($event->seo_title ?: $event->name);
  $eventPageUrl = $event->canonical_url ?: url()->current();
  $displayDate = optional($startsAt)->format('F j, Y');
  $displayTime = optional($startsAt)->format('h:i A');
  $ticketPrice = is_numeric($event->ticket_price)
      ? ((float) $event->ticket_price > 0 ? '$'.number_format((float) $event->ticket_price, 2) : 'Free')
      : ($event->ticket_price ?: 'Free');
  $shareUrl = urlencode($eventPageUrl);
  $shareTitle = urlencode($eventPageTitle);
@endphp

<div class="ms_index_wrapper common_pages_space event-detail-page">
  <div class="event-detail-shell" style="--detail-bg: url('{{ $eventImageSrc }}');">
    <div class="event-detail-content">
      <div class="event-detail-hero">
        <div class="event-detail-hero-content">
          <div class="event-share">
            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener">
              <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" target="_blank" rel="noopener">
              <i class="fa-brands fa-linkedin-in"></i>
            </a>
          </div>
          <span class="event-detail-date">{{ $displayDate }} @if($displayTime) at {{ $displayTime }} @endif</span>
          <h1 class="event-detail-title">{{ $event->name }}</h1>
          <div class="event-detail-meta">
            @if(!empty($event->location))
              <span><i class="fa fa-map-marker-alt"></i> {{ $event->location }}</span>
            @endif
            @if($displayDate)
              <span><i class="fa fa-calendar-alt"></i> {{ $displayDate }}</span>
            @endif
            @if($displayTime)
              <span><i class="fa fa-clock"></i> {{ $displayTime }}</span>
            @endif
            <span><i class="fa fa-ticket-alt"></i> {{ $ticketPrice }}</span>
          </div>
        </div>
        <div class="event-detail-hero-media" style="--hero-image: url('{{ $eventImageSrc }}');"></div>
      </div>

      <div class="event-detail-body">
        <main class="event-article">
          <h2>{{ $event->name }}</h2>
          <div class="event-article-meta">
            {{ $displayDate }} @if($displayTime) - {{ $displayTime }} @endif
          </div>
          <div class="event-article-content">
            @if(!empty($event->description))
              {!! $event->description !!}
            @else
              <p>Event details will be updated soon.</p>
            @endif
          </div>

          <section class="event-review-section">
            <h2>Reviews</h2>
            @forelse($reviews as $rev)
              <div class="event-review-item">
                <div class="event-review-head">
                  <strong>{{ $rev->user->name ?? $rev->name ?? 'Anonymous' }}</strong>
                  @if($rev->rating)
                    <span class="event-rating" aria-label="Rating {{ $rev->rating }} of 5">
                      @for($i=1; $i<=5; $i++)
                        <i class="fa{{ $i <= $rev->rating ? 's' : 'r' }} fa-star"></i>
                      @endfor
                      <small>{{ $rev->rating }}/5</small>
                    </span>
                  @endif
                </div>
                <div class="event-review-date">{{ $rev->created_at->format('F j, Y') }}</div>
                <p>{{ $rev->comment }}</p>
              </div>
            @empty
              <p class="event-muted">No reviews yet.</p>
            @endforelse

            <div class="event-pagination">{{ $reviews->links() }}</div>
          </section>

          <section class="event-review-section">
            <h2>Leave a Review</h2>
            @if (!$hasEnded)
              <div class="event-alert">Reviews will open after the event ends.</div>
            @else
              @if (session('ok'))
                <div class="event-alert event-alert-success">{{ session('ok') }}</div>
              @endif

              @if ($errors->any())
                <div class="event-alert event-alert-error">
                  <ul>
                    @foreach ($errors->all() as $e)
                      <li>{{ $e }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form method="POST" action="{{ route('front.events.reviews.store', $event) }}" class="event-form">
                @csrf

                @guest
                  <div class="event-form-grid">
                    <label>
                      <span>Name (optional)</span>
                      <input type="text" name="name" value="{{ old('name') }}">
                    </label>
                    <label>
                      <span>Email (optional)</span>
                      <input type="email" name="email" value="{{ old('email') }}">
                    </label>
                  </div>
                @endguest

                <label>
                  <span>Rating (optional)</span>
                  <select name="rating">
                    <option value="">No rating</option>
                    @for ($i = 5; $i >= 1; $i--)
                      <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} star{{ $i>1 ? 's' : '' }}</option>
                    @endfor
                  </select>
                </label>

                <label>
                  <span>Comment</span>
                  <textarea name="comment" rows="4" required>{{ old('comment') }}</textarea>
                </label>

                <button type="submit" class="event-action-btn">Submit Review</button>
              </form>
            @endif
          </section>
        </main>

        <aside class="event-side">
          @if(!empty($event->location))
            <div class="event-side-card event-map-card">
              <span class="event-side-kicker">Location</span>
              <p class="event-side-title">{{ $event->location }}</p>
              <div class="event-map">
                <iframe
                  src="https://www.google.com/maps?q={{ urlencode($event->location) }}&output=embed"
                  allowfullscreen
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>
          @endif

          <div class="event-side-card">
            <span class="event-side-kicker">Event</span>
            <p class="event-side-title">Details</p>
            <dl class="event-detail-list">
              @if($displayDate)
                <div>
                  <dt>Date</dt>
                  <dd>{{ $displayDate }}</dd>
                </div>
              @endif
              @if($displayTime)
                <div>
                  <dt>Time</dt>
                  <dd>{{ $displayTime }}</dd>
                </div>
              @endif
              <div>
                <dt>Price</dt>
                <dd>{{ $ticketPrice }}</dd>
              </div>
            </dl>
          </div>

          <div class="event-side-card">
            <span class="event-side-kicker">Browse</span>
            <p class="event-side-title">More Events</p>
            <a class="event-action-btn" href="{{ route('front.events') }}">Back to Events</a>
          </div>
        </aside>
      </div>
    </div>
  </div>
</div>

<style>
  .event-detail-page {
    padding: 36px 24px 40px;
  }
  .event-detail-shell {
    position: relative;
    width: 100%;
    border-radius: 26px;
    padding: 28px;
    background: linear-gradient(140deg, #0c1424 0%, #0b1626 52%, #0a111f 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 30px 70px rgba(6, 10, 18, 0.55);
    overflow: hidden;
  }
  .event-detail-shell::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: var(--detail-bg);
    background-size: cover;
    background-position: center;
    opacity: 0.1;
  }
  .event-detail-content {
    position: relative;
    z-index: 1;
    color: #f7f1e6;
  }
  .event-detail-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
    border-radius: 22px;
    overflow: hidden;
    background: rgba(12, 18, 30, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
  }
  .event-detail-hero-content {
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .event-share {
    display: flex;
    gap: 12px;
    font-size: 14px;
  }
  .event-share a {
    color: rgba(245, 235, 220, 0.8);
    text-decoration: none;
  }
  .event-share a:hover {
    color: #ff9b60;
  }
  .event-detail-date {
    font-size: 13px;
    color: rgba(245, 235, 220, 0.72);
  }
  .event-detail-title {
    margin: 0;
    font-size: 32px;
    line-height: 1.2;
    color: #f7f1e6;
  }
  .event-detail-meta {
    display: flex;
    gap: 10px 14px;
    flex-wrap: wrap;
    font-size: 14px;
    color: rgba(245, 235, 220, 0.78);
  }
  .event-detail-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .event-detail-hero-media {
    background-image: var(--hero-image);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    background-color: rgba(10, 16, 28, 0.68);
    min-height: 360px;
  }
  .event-detail-body {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 20px;
    margin-top: 22px;
  }
  .event-article,
  .event-side-card {
    background: rgba(12, 18, 30, 0.75);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(245, 235, 220, 0.86);
  }
  .event-article {
    padding: 24px;
  }
  .event-article h2,
  .event-review-section h2 {
    margin: 0 0 8px;
    font-size: 22px;
    color: #f7f1e6;
  }
  .event-article-meta,
  .event-muted,
  .event-review-date {
    font-size: 13px;
    color: rgba(245, 235, 220, 0.7);
  }
  .event-article-meta {
    margin-bottom: 16px;
  }
  .event-article-content {
    line-height: 1.7;
  }
  .event-article-content p,
  .event-article-content li {
    font-size: 15px;
  }
  .event-review-section {
    margin-top: 24px;
    padding-top: 22px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }
  .event-review-item {
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 14px;
    margin-top: 12px;
    background: rgba(10, 16, 28, 0.55);
  }
  .event-review-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
  }
  .event-rating {
    color: #ffc857;
    font-size: 13px;
  }
  .event-review-item p {
    margin: 8px 0 0;
  }
  .event-pagination {
    margin-top: 16px;
  }
  .event-alert {
    padding: 12px 14px;
    border-radius: 12px;
    background: rgba(125, 211, 252, 0.18);
    border: 1px solid rgba(125, 211, 252, 0.28);
    color: #d9f4ff;
  }
  .event-alert-success {
    background: rgba(34, 197, 94, 0.16);
    border-color: rgba(34, 197, 94, 0.28);
  }
  .event-alert-error {
    background: rgba(239, 68, 68, 0.16);
    border-color: rgba(239, 68, 68, 0.28);
  }
  .event-alert ul {
    margin: 0;
    padding-left: 18px;
  }
  .event-form {
    display: grid;
    gap: 14px;
  }
  .event-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
  }
  .event-form label {
    display: grid;
    gap: 7px;
    margin: 0;
    font-size: 14px;
    color: rgba(245, 235, 220, 0.82);
  }
  .event-form input,
  .event-form select,
  .event-form textarea {
    width: 100%;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(10, 16, 28, 0.65);
    color: #f7f1e6;
    font-size: 16px;
    line-height: 1.45;
    padding: 10px 12px;
  }
  .event-form select option {
    color: #0f172a;
  }
  .event-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    border-radius: 999px;
    border: none;
    background: linear-gradient(120deg, #ff7a2c, #ff4b2b);
    color: #1b0d05;
    font-weight: 700;
    font-size: 12px;
    text-decoration: none;
    width: fit-content;
  }
  .event-action-btn:hover {
    color: #1b0d05;
  }
  .event-side {
    display: grid;
    gap: 16px;
    align-content: start;
  }
  .event-side-card {
    padding: 18px;
  }
  .event-side-kicker {
    display: inline-flex;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(245, 235, 220, 0.6);
    margin-bottom: 8px;
  }
  .event-side-title {
    margin: 0 0 12px;
    font-size: 18px;
    line-height: 1.35;
    color: #f7f1e6;
  }
  .event-map {
    width: 100%;
    aspect-ratio: 1 / 1;
    min-height: 280px;
    border-radius: 16px;
    overflow: hidden;
    background: rgba(10, 16, 28, 0.65);
  }
  .event-map iframe {
    width: 100%;
    height: 100%;
    border: 0;
    display: block;
  }
  .event-detail-list {
    display: grid;
    gap: 10px;
    margin: 0;
  }
  .event-detail-list div {
    display: grid;
    gap: 2px;
  }
  .event-detail-list dt {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(245, 235, 220, 0.55);
  }
  .event-detail-list dd {
    margin: 0;
    color: rgba(245, 235, 220, 0.88);
  }
  @media (max-width: 991px) {
    .event-detail-page {
      padding: 28px 18px 36px;
    }
    .event-detail-hero,
    .event-detail-body,
    .event-form-grid {
      grid-template-columns: 1fr;
    }
    .event-detail-hero-media {
      min-height: 280px;
    }
    .event-map {
      aspect-ratio: 16 / 10;
      min-height: 240px;
    }
  }
  @media (max-width: 576px) {
    .event-detail-page {
      padding: 22px 14px 32px;
    }
    .event-detail-shell {
      padding: 18px;
      border-radius: 20px;
    }
    .event-detail-hero-content,
    .event-article,
    .event-side-card {
      padding: 18px;
    }
    .event-detail-title {
      font-size: 25px;
    }
  }
</style>
@endsection
