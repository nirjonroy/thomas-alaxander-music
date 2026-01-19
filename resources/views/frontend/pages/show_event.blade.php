@extends('frontend.app')

@section('title', $event->name)
@section('seos')
@php
    use Illuminate\Support\Str;

    $eventName = $event->name;
    $pageUrl   = url()->current();
    $desc      = Str::limit(strip_tags($event->description ?? ''), 180);
    $seoDefaults = \App\Models\SeoSetting::where('page_name', 'Events')->first();
    $siteName = optional($seoDefaults)->site_name ?? (siteInfo()->site_name ?? 'Website');
    $keywords = optional($seoDefaults)->seo_keywords ?? $eventName;
    $author = optional($seoDefaults)->seo_author ?? $siteName;
    $publisher = optional($seoDefaults)->seo_publisher ?? $siteName;
    $copyright = optional($seoDefaults)->meta_copyright;

    // Resolve image to an absolute URL
    $imgRaw = $event->image;
    $eventImage = null;
    if ($imgRaw) {
        if (preg_match('#^(https?:)?//#', $imgRaw)) {
            $eventImage = $imgRaw;                     // already absolute (CDN/S3)
        } elseif (strpos($imgRaw, '/') !== false) {
            $eventImage = asset($imgRaw);              // e.g. 'uploads/.../file.jpg'
        } else {
            $eventImage = asset('uploads/custom-images/'.$imgRaw);
        }
    }
    $metaImageValue = optional($seoDefaults)->meta_image;
    $metaImage = $eventImage ?: ($metaImageValue
        ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
        : asset(siteInfo()->logo));

    // Dates for structured data
    $start = \Carbon\Carbon::parse(trim(($event->date ?? '').' '.($event->time ?? '')));
    $end   = $start ? (clone $start)->addHours(3) : null;
@endphp

<title>{{ $eventName }}</title>
<link rel="canonical" href="{{ $pageUrl }}"/>

<meta name="robots" content="index, follow, max-image-preview:large">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">
<meta name="publisher" content="{{ $publisher }}">
@if ($copyright)
  <meta name="copyright" content="{{ $copyright }}">
@endif
<meta name="description" content="{{ $desc }}"/>

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $eventName }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:secure_url" content="{{ $metaImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $eventName }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $eventName }}">
<meta name="twitter:description" content="{{ $desc }}">
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
    "url": {{ json_encode($pageUrl) }}
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
<div class="ms_content_wrapper padder_top8">
  <div class="ms_index_wrapper common_pages_space">

    <div class="container event-page">
      <div class="event-grid"><!-- GRID instead of bootstrap row -->

        {{-- LEFT: poster + meta + description --}}
        <div class="ep-left">
          <div class="card ep-card shadow-sm border-0">
            @php
              // Resolve event image to a valid URL
              $src = null;
              if (!empty($event->image)) {
                  $img = $event->image;
                  if (preg_match('#^(https?:)?//#', $img)) {
                      $src = $img;                       // full URL / CDN
                  } elseif (strpos($img, '/') !== false) {
                      $src = asset($img);                // relative like uploads/...
                  } else {
                      $src = asset('uploads/custom-images/'.$img); // filename only
                  }
              }
            @endphp

            @if ($src)
              <img src="{{ $src }}" class="card-img-top ep-img" alt="{{ $event->name }}">
            @elseif (!empty($event->location))
              <div class="ratio ratio-16x9">
                <iframe
                  src="https://www.google.com/maps?q={{ urlencode($event->location) }}&output=embed"
                  allowfullscreen loading="lazy"></iframe>
              </div>
            @else
              <img src="{{ asset(siteInfo()->logo) }}" class="card-img-top ep-img" alt="{{ $event->name }}">
            @endif

            <div class="card-body ep-meta">
              <h1 class="ep-title mb-2">{{ $event->name }}</h1>

              @if(!empty($event->location))
                <div class="small ep-muted mb-2">
                  <i class="fa fa-map-marker-alt me-1"></i> {{ $event->location }}
                </div>
              @endif

              @php
                $startsAt = \Carbon\Carbon::parse(trim(($event->date ?? '').' '.($event->time ?? '')));
              @endphp
              @if($startsAt)
                <div class="small ep-muted mb-2">
                  <i class="fa fa-calendar-alt me-1"></i> {{ $startsAt->format('M d, Y h:i A') }}
                </div>
              @endif

              @if(!is_null($event->ticket_price))
                <div class="ep-price">
                  <strong>Price:</strong>
                  {{ $event->ticket_price > 0 ? '$'.number_format($event->ticket_price,2) : 'Free' }}
                </div>
              @endif
            </div>
          </div>

          @if(!empty($event->description))
            <div class="card ep-card shadow-sm border-0 mt-3">
              <div class="card-body">{!! $event->description !!}</div>
            </div>
          @endif
        </div>

        {{-- RIGHT: reviews + form --}}
        <div class="ep-right">

          {{-- Reviews list --}}
          <div class="card ep-card shadow-sm border-0 mb-3">
            <div class="card-body">
              <h2 class="ep-subtitle mb-3">Reviews</h2>

              @forelse($reviews as $rev)
                <div class="ep-item border rounded p-3 mb-3">
                  <div class="d-flex justify-content-between flex-wrap gap-2">
                    <strong>{{ $rev->user->name ?? $rev->name ?? 'Anonymous' }}</strong>

                    @if($rev->rating)
                      <span class="text-warning" aria-label="Rating {{ $rev->rating }} of 5">
                        @for($i=1; $i<=5; $i++)
                          <i class="fa{{ $i <= $rev->rating ? 's' : 'r' }} fa-star"></i>
                        @endfor
                        <small class="ep-muted">{{ $rev->rating }}/5</small>
                      </span>
                    @endif
                  </div>
                  <div class="small ep-muted">{{ $rev->created_at->format('M d, Y') }}</div>
                  <p class="mb-0 mt-2">{{ $rev->comment }}</p>
                </div>
              @empty
                <p class="ep-muted mb-0">No reviews yet.</p>
              @endforelse

              <div class="mt-3">{{ $reviews->links() }}</div>
            </div>
          </div>

          {{-- Review form (opens only after event ends) --}}
          <div class="card ep-card shadow-sm border-0">
            <div class="card-body">
              <h2 class="ep-subtitle mb-3">Leave a review</h2>

              @if (!$hasEnded)
                <div class="alert alert-info mb-0">Reviews will open after the event ends.</div>
              @else
                @if (session('ok'))
                  <div class="alert alert-success">{{ session('ok') }}</div>
                @endif

                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                      @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form method="POST" action="{{ route('front.events.reviews.store', $event->id) }}" class="ep-form">
                  @csrf

                  @guest
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Name (optional)</label>
                      <input type="text" name="name" class="form-control ep-control" value="{{ old('name') }}">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Email (optional)</label>
                      <input type="email" name="email" class="form-control ep-control" value="{{ old('email') }}">
                    </div>
                  </div>
                  @endguest

                  <div class="mt-3">
                    <label class="form-label">Rating (optional)</label>
                    <select name="rating" class="form-select ep-control">
                      <option value="">No rating</option>
                      @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} star{{ $i>1 ? 's' : '' }}</option>
                      @endfor
                    </select>
                  </div>

                  <div class="mt-3">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" rows="4" required class="form-control ep-control">{{ old('comment') }}</textarea>
                  </div>

                  <button class="btn btn-danger ep-submit mt-3">Submit review</button>
                </form>
              @endif
            </div>
          </div>

        </div><!-- /ep-right -->

      </div><!-- /event-grid -->
    </div><!-- /container -->

  </div>
</div>

<style>
  /* ===== Layout: use a robust CSS grid just for this page ===== */
  .event-page{ max-width:1200px; margin:16px auto; }
  .event-grid{
    display:grid;
    grid-template-columns: minmax(0,5fr) minmax(0,7fr); /* poster / reviews */
    gap:24px;
    align-items:start;
  }

  /* Stack on tablets/phones */
  @media (max-width: 991.98px){
    .event-grid{ grid-template-columns: 1fr; }
  }

  /* Make sure any bootstrap floats/widths don’t interfere */
  .event-grid > *{ min-width:0; }
  .event-grid > [class*="col"]{ float:none !important; width:auto !important; max-width:none !important; padding:0 !important; }

  /* ===== Theme-matching visuals ===== */
  .event-page .card.ep-card{
    background:#0e1b2d !important;
    border:1px solid rgba(255,255,255,.08) !important;
    border-radius:12px;
    color:#e6eef7;
  }
  .event-page .ep-item{
    background:#0c1b2e !important;
    border:1px solid #22334a !important;
    color:#e6eef7;
  }
  .event-page .ep-muted{ color:#9fb2c8 !important; }

  /* Titles (no tiny headings) */
  .event-page .ep-title{ font-size:22px; line-height:1.3; font-weight:700; }
  .event-page .ep-subtitle{ font-size:18px; line-height:1.35; font-weight:700; }

  /* Poster image scales nicely at all widths */
  .event-page .ep-img{
    display:block;
    width:100%;
    height:auto;           /* natural aspect */
    max-height: 620px;     /* comfortable cap on large screens */
    object-fit:contain;    /* keep the whole poster visible */
    background:#0b1b2f;    /* letterbox bands look intentional */
    border-radius:12px;
  }
  @media (max-width: 575.98px){
    .event-page .ep-img{ max-height:70vh; }
  }

  /* Meta text sizing (fix “tiny red text”) */
  .event-page .ep-meta{ font-size:15px; line-height:1.6; }
  .event-page .ep-meta .small,
  .event-page .ep-meta small{ font-size:15px !important; }
  .event-page .text-danger{ color:#ff5176 !important; }

  /* Form controls readable & consistent */
  .event-page .ep-control,
  .event-page .form-control,
  .event-page .form-select{
    background:#0c1b2e !important;
    color:#e6eef7 !important;
    border:1px solid #22334a !important;
    font-size:16px !important;   /* prevents mobile auto-zoom */
    line-height:1.45;
    min-height:46px;
    padding:10px 12px;
    width:100%;
    border-radius:10px;
  }
  .event-page .form-control:focus,
  .event-page .form-select:focus{
    border-color:#3b82f6 !important;
    box-shadow:none !important;
    outline:0;
  }
  .event-page .ep-form .form-select option{ color:#0f223a; }
  .event-page .ep-form label{ font-size:15px; color:#cdd8e6; }

  /* Submit button */
  .event-page .ep-submit{
    font-size:16px;
    padding:.65rem 1.2rem;
    border-radius:10px;
  }
</style>
@endsection
