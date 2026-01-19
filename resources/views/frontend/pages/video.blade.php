@extends('frontend.app')
@push('css')
<style>
    .form-check-label {
        color: black !important;
        font-weight: bold;
    }

    .product-img {
    width: 100%;
    height: auto;
    max-height: 250px;
    object-fit: contain; /* keep full image visible */
}

@media (max-width: 576px) {
    .product-img {
        max-height: 200px;
    }
}

</style>
@endpush
@section('seos')
    @php
        $seoSetting = \App\Models\SeoSetting::where('page_name', 'Video')->first();
        $siteName = optional($seoSetting)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = optional($seoSetting)->meta_title ?: (optional($seoSetting)->seo_title ?: 'Video Gallery');
        $rawDesc = optional($seoSetting)->meta_description
            ?: (optional($seoSetting)->seo_description ?: 'Watch official videos and performances from Thomas Alexander.');
        $pageDesc = \Illuminate\Support\Str::limit(strip_tags($rawDesc), 180);
        $pageUrl = url()->current();
        $canonical = optional($seoSetting)->canonical_url ?: $pageUrl;
        $keywords = optional($seoSetting)->seo_keywords ?? 'Thomas Alexander videos, performances, gallery';
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
<div class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">
<div class="categoryHeader">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page" style="background:lightblue;">
                
            </li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
<div class="main-wrapper">
    <div class="overlay-sidebar"></div>
    <div class="category-page col-lg-12 col-12 p-0 m-auto mt-2 mb-2">
        <div class="row">
       
            <section class="products-box col-lg-12 col-md-12 custom-padding-10" style="padding: 10px">
                <div class="bg-white p-3 pt-1">
                    <div class="product-bar">
                        <div class="btn-list">
               
                            <!--<button class="filter-btn btn d-block d-md-block d-lg-none">-->
                            <!--   <i class="fas fa-filter"></i> Filter-->
                            <!--</button>-->
                            <!--<button class="compare-btn btn d-none d-md-none d-lg-block">-->
                            <!--   <i class="fas fa-repeat"></i> Product Compare-->
                            <!--</button>-->
                        </div>
                        <div class="filter-sort d-flex align-items-center">
                            <div class="d-flex align-items-center me-2">
                              <!--<label for="" class="form-label me-2 mb-0" style="white-space: nowrap;">Sort By: </label>-->
                              <!--<select name="" id="" class="form-select shadow-none">-->
                              <!--  <option value="">Select One</option>-->
                              <!--  <option value="">High to Low</option>-->
                              <!--</select>-->
                            </div>
                            <div class="d-lg-flex d-md-none d-none align-items-center">
                              <!--<label for="" class="form-label me-2 mb-0">Show:</label>-->
                              <!--<select name="" id="" class="form-select shadow-none">-->
                              <!--  <option value="">10</option>-->
                              <!--  <option value="">20</option>-->
                              <!--</select>-->
                            </div>
                        </div>
                    </div>

                    <div class="product-box py-1 bg-muted row g-3">
    @forelse($videos as $video)
        @php
            $url = trim($video->url ?? '');
            $isYoutube = Str::contains($url, ['youtube.com', 'youtu.be']);
            // Extract YouTube video ID
            $ytId = null;
            if ($isYoutube) {
                if (preg_match('~youtu\.be/([^\?\&]+)~', $url, $m)) {
                    $ytId = $m[1];
                } elseif (preg_match('~v=([^&]+)~', $url, $m)) {
                    $ytId = $m[1];
                } elseif (preg_match('~embed/([^\?\&]+)~', $url, $m)) {
                    $ytId = $m[1];
                }
            }
        @endphp
        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="product-item h-100 d-flex flex-column">
                <div class="product_thumb text-center">
                    @if(!empty($video->thumbnail))
                        <a class="secondary_img" href="{{ $video->url }}" target="_blank">
                            <img src="{{ asset('uploads/video-thumbnails/'.$video->thumbnail) }}"
                                 alt="{{ $video->title }}"
                                 class="img-fluid product-img">
                        </a>
                    @elseif($isYoutube && $ytId)
                        <iframe src="https://www.youtube.com/embed/{{ $ytId }}"
                                title="{{ $video->title }}"
                                class="img-fluid product-img"
                                style="width:100%; height:250px; border:0;"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                        </iframe>
                    @else
                        <video class="img-fluid product-img" controls>
                            <source src="{{ $video->url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>

                <div class="product_content flex-grow-1 d-flex flex-column justify-content-between">
                    <h4 class="ps-1" style="height: 40px; overflow: hidden;">
                        <a href="{{ $video->url }}" class="font-16" style="font-size: 14px;">
                            {{ \Illuminate\Support\Str::limit($video->title, 30) }}
                        </a>
                    </h4>

                   <div class="p-2">
                        <a href="{{ $video->url }}" target="_blank" 
                           style="color: white; font-size: 15px; padding-top: 4%; background: red; border: solid; width: 100%;"
                           class="btn btn-sm btn-warning semi">
                            <i class="fa fa-play"></i> Watch Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-danger">No videos are available</div>
    @endforelse
</div>

                </div>
            </section>
        <!-- Products -->
        </div>
    </div>

</div>
</div>

</div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function () {
    $('.buy-now').on('click', function (e) {
        e.preventDefault();
        
        var productId = $(this).attr('href').split('/').pop();
        var proQty = 1;
        var addToCartUrl = $(this).data('url');
        var checkoutUrl = "{{ route('front.cart.index') }}";
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include CSRF token in AJAX request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        // Perform AJAX request to add the product to the cart
        $.post(addToCartUrl, { id: productId, quantity: proQty }, function (response) {
            // Redirect to checkout page after adding to cart
           window.location.href = "{{ route('front.checkout.index') }}";
        });
    });
});
</script>
@endpush
