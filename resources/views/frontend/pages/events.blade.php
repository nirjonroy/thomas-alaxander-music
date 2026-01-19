@extends('frontend.app')
@push('css')
   
@endpush
@section('seos')
    @php
        $seoSetting = \App\Models\SeoSetting::where('page_name', 'Events')->first();
        $siteName = optional($seoSetting)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = optional($seoSetting)->meta_title ?: (optional($seoSetting)->seo_title ?: 'Event List');
        $rawDesc = optional($seoSetting)->meta_description
            ?: (optional($seoSetting)->seo_description ?: 'Upcoming and past events from Thomas Alexander.');
        $pageDesc = \Illuminate\Support\Str::limit(strip_tags($rawDesc), 180);
        $pageUrl = url()->current();
        $canonical = optional($seoSetting)->canonical_url ?: $pageUrl;
        $keywords = optional($seoSetting)->seo_keywords ?? 'Thomas Alexander events, live shows, appearances';
        $author = optional($seoSetting)->seo_author ?? $siteName;
        $publisher = optional($seoSetting)->seo_publisher ?? $siteName;
        $metaImageValue = optional($seoSetting)->meta_image;
        $metaImage = $metaImageValue
            ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
            : asset(siteInfo()->logo);
        $copyright = optional($seoSetting)->meta_copyright;
    @endphp
    @section('title', $pageTitle)
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $author }}">
    <meta name="publisher" content="{{ $publisher }}">
    @if ($copyright)
        <meta name="copyright" content="{{ $copyright }}">
    @endif
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
@endsection
@section('content')
<d class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">
<div class="categoryHeader">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page" style="background:lightblue;">
                
            </li>
        </ol>
    </nav>
</div>

<style>
    .form-check-label {
        color: black !important;
        font-weight: bold;
    }
</style>
<div class="container-fluid">
<div class="main-wrapper">
    <div class="overlay-sidebar"></div>
    <div class="category-page col-lg-12 col-12 p-0 m-auto mt-2 mb-2">
        <style>
    .event-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .event-map iframe {
        width: 100%;
        height: 180px;
        border: none;
        border-bottom: 1px solid #ddd;
    }
    .event-content {
        padding: 15px;
    }
    .event-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        height: 40px;
    }
    .event-info {
        font-size: 14px;
        margin-bottom: 6px;
        color: #555;
    }
    .event-price {
        font-size: 16px;
        font-weight: bold;
        color: #007BFF;
        margin-bottom: 10px;
    }
</style>

<div class="row">
   @forelse($events as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
        <div class="event-card">
            <div class="event-map">
                @if(!empty($product->image))
                <a href="{{ route('front.events.show', $product->id) }}">
                    <img src="{{ asset('uploads/custom-images/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid w-100" style="height: auto; max-height: 200px; object-fit: contain;"
                    onclick="showEventImage('{{ asset('uploads/custom-images/' . $product->image) }}')"
                    >
                </a>
                @else
                    <iframe 
                        src="https://www.google.com/maps?q={{ urlencode($product->location) }}&output=embed" 
                        allowfullscreen 
                        width="100%" 
                        height="150" 
                        frameborder="0" 
                        style="border:0;">
                    </iframe>
                @endif
            </div>
            <div class="event-content">
                <a href="{{ route('front.events.show', $product->id) }}">
                <div class="event-title">{{ \Illuminate\Support\Str::limit($product->name, 50) }}</div>
                <div class="event-price">${{ $product->ticket_price ?? 0 }}</div>
                <div class="event-info"><i class="fa fa-map-marker-alt"></i> {{ \Illuminate\Support\Str::limit($product->location, 35) }}</div>
                <div class="event-info"><i class="fa fa-calendar-alt"></i> {{ $product->date }} {{ $product->time }}</div>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="text-center text-danger">
        <strong>No events are available</strong>
    </div>
@endforelse

</div>

    </div>

</div>
</div>

</div>
</div>
<!-- Image Modal -->
<div class="modal fade" id="eventImageModal" tabindex="-1" aria-labelledby="eventImageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <img id="modalEventImage" src="" class="img-fluid w-100" alt="Event Image">
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')
<script>
    function showEventImage(imageUrl) {
        const modalImg = document.getElementById('modalEventImage');
        modalImg.src = imageUrl;
        const modal = new bootstrap.Modal(document.getElementById('eventImageModal'));
        modal.show();
    }
</script>
@endpush


