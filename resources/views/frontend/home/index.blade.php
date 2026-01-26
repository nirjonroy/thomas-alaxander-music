@extends('frontend.app')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('seos')
    @php
        $seoSetting = \App\Models\SeoSetting::where('page_name', 'Home Page')->first();
        $pageTitle = optional($seoSetting)->seo_title ?? 'Thomas Alexander | Home';
        $pageDesc = optional($seoSetting)->seo_description ?? 'Official home of Thomas Alexander — explore music, events, and ceremonial merchandise.';
        $pageUrl = url()->current();
        $canonical = optional($seoSetting)->canonical_url ?: $pageUrl;
        $keywords = optional($seoSetting)->seo_keywords ?? 'Thomas Alexander, music, Living Archive';
        $siteName = optional($seoSetting)->site_name ?? config('app.name', 'Thomas Alexander');
        $pageTitle = optional($seoSetting)->meta_title ?: $pageTitle;
        $rawDesc = optional($seoSetting)->meta_description ?: $pageDesc;
        $pageDesc = \Illuminate\Support\Str::limit(strip_tags($rawDesc), 180);
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
    <meta name="twitter:url" content="{{ $pageUrl }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
@endsection
@section('content')



<style>
    .slider-img {
        height: 75vh;
        width: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .slider-img {
            height: 45vh;
        }

        .carousel-caption h2 {
            font-size: 18px;
        }

        .carousel-caption p {
            font-size: 14px;
        }

        .carousel-caption .btn {
            font-size: 14px;
            padding: 8px 16px;
        }
    }

    .carousel-caption {
        background: rgba(0, 0, 0, 0.5);
        padding: 1rem 2rem;
        border-radius: 10px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 50%;
        padding: 10px;
    }

    .home-blog-section {
        margin: 32px auto 20px;
        color: #f7f1e6;
    }
    .home-blog-title {
        margin: 0 0 18px;
        font-size: 22px;
        text-align: center;
        color: #ff4b2b;
        text-decoration: underline;
        text-decoration-thickness: 2px;
        text-underline-offset: 6px;
    }
    .home-blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }
    .home-blog-card {
        background: rgba(12, 18, 30, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 16px 28px rgba(6, 10, 18, 0.4);
        height: 100%;
    }
    .home-blog-media {
        position: relative;
        background-image: var(--card-image);
        background-size: cover;
        background-position: center;
        padding-top: 60%;
        display: block;
    }
    .home-blog-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(12, 18, 30, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 11px;
        color: #f7f1e6;
    }
    .home-blog-body {
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }
    .home-blog-card-title {
        margin: 0;
        font-size: 15px;
        line-height: 1.4;
    }
    .home-blog-card-title a {
        color: #ff4b2b;
        text-decoration: none;
    }
    .home-blog-excerpt {
        margin: 0;
        font-size: 13px;
        color: rgba(245, 235, 220, 0.75);
        line-height: 1.5;
    }
    .home-blog-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        font-size: 12px;
        color: rgba(245, 235, 220, 0.7);
    }
    .home-blog-cta {
        color: #ff9b60;
        text-decoration: none;
        font-weight: 600;
    }
    .home-blog-empty {
        padding: 24px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.04);
        text-align: center;
        color: rgba(245, 235, 220, 0.75);
    }
    @media (max-width: 576px) {
        .home-blog-title {
            font-size: 20px;
        }
        .home-blog-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if($sliders->count())
<section class="mb-4">
    <div id="homepageCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">

            @foreach($sliders as $index => $slider)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    
                    <img src="{{ asset($slider->image) }}" class="d-block w-100 slider-img"
                        alt="{{ $slider->title_one ?? 'Slider' }}">
                    
                    <div class="carousel-caption d-block">

                        @if($slider->title_one)
                            <h2 class="text-white">{{ $slider->title_one }}</h2>
                        @endif
                        @if($slider->title_two)
                            <p class="text-light">{{ $slider->title_two }}</p>
                        @endif
                        @if($slider->slider_location && $slider->link)
                            <a href="{{ $slider->link }}" class="btn btn-light btn-lg" style="font-size: 14px; font-weight: bold;">
                                {{ $slider->slider_location }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Controls --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#homepageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homepageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
@endif

<div class="container" style="margin:20px">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <h1 style="text-align: center; font-size: 25px; padding: 5px;">Who is : Thomas Alexander - "The Voice"</h1>
            <p style="color: white">
A legendary Edmonton musician with over 50 years in the industry, Thomas Alexander began his career in 1968 with The Patchwork Quilt and went on to front Edmonton's first funk band, The Key. His musical journey spans from touring North America with Southbound Freeway to performing in Japan where he was dubbed "The Canadian Bluebird" for his stunning tenor voice.
Thomas has shared the stage with music icons including James Brown (who called him "Soul Brother Number Two"), Irene Cara, and members of The 5th Dimension. His versatile range covers Jazz, R&B, Funk, Gospel, Country, and Pop. Notable performances include starring as Judas in Jesus Christ Superstar and singing for Alberta's centennial celebrations.
With roots tracing back to Alberta's first Black pioneers, Thomas continues to create and perform, cementing his status as one of Edmonton's most celebrated musical legends.

</p>
        </div>
    </div>
</div>

<div class="ms_content_wrapper padder_top8" >
    <!---Header--->
   
    <!---index page--->
    <div class="ms_index_wrapper common_pages_space">
        <div class="ms_index_inner">
            <div class="ms_index_secwrap">
                <div class="ms_songslist_main">
                    <div class="ms_songslist_wrap">
                        <ul class="ms_songslist_nav nav nav-pills" role="tablist">
                            <li>
                                <a class="active" data-bs-toggle="pill" href="#top-picks" role="tab" aria-controls="top-picks" aria-selected="true">Today Top Picks</a>
                            </li>
                            <li>
                                <a class="" data-bs-toggle="pill" href="#trending-songs" role="tab" aria-controls="trending-songs" aria-selected="false">Trending Songs</a>
                            </li>
                            <li>
                                <a class="" data-bs-toggle="pill" href="#new-release" role="tab" aria-controls="new-release" aria-selected="false">New Release</a>
                            </li>

                            <li>
                                <a class="" data-bs-toggle="pill" href="#physical-product" role="tab" aria-controls="physical-product" aria-selected="false">Physical Products</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="top-picks" role="tabpanel" aria-labelledby="top-picks">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">
                                        @foreach ($top_picks as $key=>$product)
                                        <li>
                                            <div class="ms_songslist_inner">
                                                <div class="ms_songslist_left">
                                                    <div class="songslist_number">
                                                        <h4 class="songslist_sn">{{++$key}}</h4>
                                                        <span class="songslist_play" onclick="playAudio({{$product->id}})"><img src="{{('frontend/assets/images/svg/play_songlist.svg')}}" alt="Play" class="img-fluid"/></span>
                                                        <audio id="audio-{{$product->id}}" src="{{ asset($product->demo_song) }}" preload="none"></audio>
                                                    </div>
                                                    <div class="songslist_details">
                                                        <div class="songslist_thumb">
                                                            <a href="{{ route('front.product.show', $product->slug ) }}">
                                                                <img src="{{asset('uploads/custom-images2/' . $product->thumb_image)}}" alt="thumb" class="img-fluid" />
                                                            </a>
                                                            
                                                        </div>
                                                        <div class="songslist_name">
    
                                                            <h3 class="song_name"><a href="{{ route('front.product.show', $product->slug ) }}">{{$product->name}}</a></h3>
                                                            <p class="song_artist">{{$product->artist_name}}</p>
                                                        </div>
                                                    </div>
    
                                                </div>
                                                <div class="ms_songslist_right">
                                                    <span class="ms_songslist_like">
                                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                    </span>
                                                    <div>
                                                        <span class="ms_songslist_time">{{$product->duration}}</span>
                                                        @if($product->download_type == 'free')
                                                        <p class="ms_songslist_time">download</p>
                                                        @else
                                                            @if($product->offer_price == 0)
                                                            <p class="ms_songslist_time">$ {{$product->price}}</p>
                                                            @else
                                                            <p class="ms_songslist_time">$ {{$product->offer_price}}</p>
                                                            <strike class="ms_songslist_time"> $ {{$product->price}}</strike>
                                                            @endif
    
                                                        @endif
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </li>
                                      @endforeach
                                       
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="trending-songs" role="tabpanel" aria-labelledby="trending-songs">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">
                                        @foreach ($tranding_songs as $key=>$product)
                                        <li>
                                            <div class="ms_songslist_inner">
                                                <div class="ms_songslist_left">
                                                    <div class="songslist_number">
                                                        <h4 class="songslist_sn">{{++$key}}</h4>
                                                        <span class="songslist_play" onclick="playAudio({{$product->id}})"><img src="{{('frontend/assets/images/svg/play_songlist.svg')}}" alt="Play" class="img-fluid"/></span>
                                                        <audio id="audio-{{$product->id}}" src="{{ asset($product->demo_song) }}" preload="none"></audio>
                                                    </div>
                                                    <div class="songslist_details">
                                                        <div class="songslist_thumb">
                                                            <a href="{{ route('front.product.show', $product->slug ) }}">
                                                                <img src="{{asset('uploads/custom-images2/' . $product->thumb_image)}}" alt="thumb" class="img-fluid" />
                                                            </a>
                                                            
                                                        </div>
                                                        <div class="songslist_name">
    
                                                            <h3 class="song_name"><a href="{{ route('front.product.show', $product->slug ) }}">{{$product->name}}</a></h3>
                                                            <p class="song_artist">{{$product->artist_name}}</p>
                                                        </div>
                                                    </div>
    
                                                </div>
                                                <div class="ms_songslist_right">
                                                    <span class="ms_songslist_like">
                                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                    </span>
                                                    <div>
                                                        <span class="ms_songslist_time">{{$product->duration}}</span>
                                                        @if($product->download_type == 'free')
                                                        <p class="ms_songslist_time">download</p>
                                                        @else
                                                            @if($product->offer_price == 0)
                                                            <p class="ms_songslist_time">$ {{$product->price}}</p>
                                                            @else
                                                            <p class="ms_songslist_time">$ {{$product->offer_price}}</p>
                                                            <strike class="ms_songslist_time"> $ {{$product->price}}</strike>
                                                            @endif
    
                                                        @endif
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </li>
                                      @endforeach
                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="new-release" role="tabpanel" aria-labelledby="new-release">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">
                                      @foreach ($products as $key=>$product)
                                      <li>
                                        <div class="ms_songslist_inner">
                                            <div class="ms_songslist_left">
                                                <div class="songslist_number">
                                                    <h4 class="songslist_sn">{{++$key}}</h4>
                                                    <span class="songslist_play" onclick="playAudio({{$product->id}})"><img src="{{('frontend/assets/images/svg/play_songlist.svg')}}" alt="Play" class="img-fluid"/></span>
                                                    <audio id="audio-{{$product->id}}" src="{{ asset($product->demo_song) }}" preload="none"></audio>
                                                </div>
                                                <div class="songslist_details">
                                                    <div class="songslist_thumb">
                                                        <a href="{{ route('front.product.show', $product->slug ) }}">
                                                            <img src="{{asset('uploads/custom-images2/' . $product->thumb_image)}}" alt="thumb" class="img-fluid" />
                                                        </a>
                                                        
                                                    </div>
                                                    <div class="songslist_name">

                                                        <h3 class="song_name"><a href="{{ route('front.product.show', $product->slug ) }}">{{$product->name}}</a></h3>
                                                        <p class="song_artist">{{$product->artist_name}}</p>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="ms_songslist_right">
                                                <span class="ms_songslist_like">
                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                </span>
                                                <div>
                                                    <span class="ms_songslist_time">{{$product->duration}}</span>
                                                    @if($product->download_type == 'free')
                                                    <p class="ms_songslist_time">download</p>
                                                    @else
                                                        @if($product->offer_price == 0)
                                                        <p class="ms_songslist_time">$ {{$product->price}}</p>
                                                        @else
                                                        <p class="ms_songslist_time">$ {{$product->offer_price}}</p>
                                                        <strike class="ms_songslist_time"> $ {{$product->price}}</strike>
                                                        @endif

                                                    @endif
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                    </li>
                                      @endforeach
                                        
                                        
                                    </ul>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="physical-product" role="tabpanel" aria-labelledby="physical-product">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">
                                      @foreach ($physical_product as $key=>$product)
                                      <li>
                                        <div class="ms_songslist_inner">
                                            <div class="ms_songslist_left">
                                                <div class="songslist_number">
                                                    <h4 class="songslist_sn">{{$key++}}</h4>
                                                    <span class="songslist_play" onclick="playAudio({{$product->id}})"><img src="{{('frontend/assets/images/svg/play_songlist.svg')}}" alt="Play" class="img-fluid"/></span>
                                                    <audio id="audio-{{$product->id}}" src="{{ asset($product->demo_song) }}" preload="none"></audio>
                                                </div>
                                                <div class="songslist_details">
                                                    <div class="songslist_thumb">
                                                        <a href="{{ route('front.product.show', $product->slug ) }}">
                                                            <img src="{{asset('uploads/custom-images2/' . $product->thumb_image)}}" alt="thumb" class="img-fluid" />
                                                        </a>
                                                        
                                                    </div>
                                                    <div class="songslist_name">

                                                        <h3 class="song_name"><a href="{{ route('front.product.show', $product->slug ) }}">{{$product->name}}</a></h3>
                                                        <p class="song_artist">{{$product->artist_name}}</p>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="ms_songslist_right">
                                                <span class="ms_songslist_like">
                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                </span>
                                                <div>
                                                    <span class="ms_songslist_time">{{$product->duration}}</span>
                                                    @if($product->download_type == 'free')
                                                    <p class="ms_songslist_time">download</p>
                                                    @else
                                                        @if($product->offer_price == 0)
                                                        <p class="ms_songslist_time">$ {{$product->price}}</p>
                                                        @else
                                                        <p class="ms_songslist_time">$ {{$product->offer_price}}</p>
                                                        <strike class="ms_songslist_time"> $ {{$product->price}}</strike>
                                                        @endif

                                                    @endif
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                    </li>
                                      @endforeach
                                        
                                        
                                    </ul>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<!---Main Content end--->

<style>
    .event-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .event-map iframe,
    .event-map img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }

    .event-content {
        padding: 15px;
    }

    .event-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        min-height: 40px;
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
    .event-content{
        margin: 10px !important;
    }
</style>

<div class="container my-4 event-content">
    <div class="row">
        <h1 class="song_name" style="text-align: center; margin-bottom: 10px; font-size: 25px"><u>Recent Event's</u></h1>
           @forelse($events as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
        <div class="event-card">
            <div class="event-map">
                 <a href="{{ route('front.events.show', $product->id) }}">
                @if(!empty($product->image))
                    <img src="{{ asset('uploads/custom-images/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid w-100" style="height: auto; max-height: 200px; object-fit: contain;"
                    onclick="showEventImage('{{ asset('uploads/custom-images/' . $product->image) }}')"
                    >
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
                </a>
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


<div class="container home-blog-section">
    <h2 class="home-blog-title">Latest Blogs</h2>

    <div class="home-blog-grid">
        @forelse ($blogs as $blog)
            @php
                $cardImage = $blog->image ? asset($blog->image) : asset(siteInfo()->logo);
                $tag = optional($blog->category)->name ?? 'Blog';
            @endphp
            <article class="home-blog-card">
                <a class="home-blog-media" href="{{ route('front.blog_details', [$blog->slug]) }}" style="--card-image: url('{{ $cardImage }}');">
                    <span class="home-blog-tag">{{ $tag }}</span>
                </a>
                <div class="home-blog-body">
                    <h3 class="home-blog-card-title">
                        <a href="{{ route('front.blog_details', [$blog->slug]) }}">
                            {{ Str::limit($blog->title, 70) }}
                        </a>
                    </h3>
                    <p class="home-blog-excerpt">{{ Str::limit(strip_tags($blog->description), 90) }}</p>
                    <div class="home-blog-footer">
                        <span>{{ date('F j, Y', strtotime($blog->created_at)) }}</span>
                        <a class="home-blog-cta" href="{{ route('front.blog_details', [$blog->slug]) }}">Read More</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="home-blog-empty">
                No blog posts are available yet.
            </div>
        @endforelse
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

<script>
  $(document).ready(function () {
    $(".owl-carousel").owlCarousel({
      loop: true,
      margin: 10,
      nav: true,
      dots: true,
      items: 1,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplayHoverPause: true,
    });
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script>
$(document).ready(function () {
    $('.buy-now').on('click', function (e) {
        e.preventDefault();

        var productId = $(this).attr('href').split('/').pop();
        var proQty = 1;
        var addToCartUrl = $(this).data('url');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include CSRF token in AJAX request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        // Perform AJAX request to add the product to the cart
        $.post(addToCartUrl, { id: productId, quantity: proQty }, function (response) {
            // toastr.success(response.msg);
            if(response.status)
            {
                // Redirect to checkout page after adding to cart
                window.location.href = "{{ route('front.checkout.index') }}";
            } else
            {

            }

        });
    });
});
</script>

<script>
 $(function () {
   // Add CSS to initially hide the .offerBox
        function setCookie(name, value, minutes) {
            var expires = "";
            if (minutes) {
                var date = new Date();
                date.setTime(date.getTime() + (minutes * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1, c.length);
                }
                if (c.indexOf(nameEQ) == 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        }

        $(".modal-overlay").click(function(){
            $('.offerBox').hide();
            setCookie('offerBoxHidden', 'true', 5);
        })

        $(".offerBox .content .close").click(function(){
            $('.offerBox').hide();
            setCookie('offerBoxHidden', 'true', 5);
        })

        // Check if the offerBox should be hidden based on the cookie
        var offerBoxHidden = getCookie('offerBoxHidden');

        if (offerBoxHidden === 'true') {
            $('.offerBox').hide();
        }





    $(document).on('click', '.add-to-cart', function (e) {
        let id = $(this).data('id');
        let url = $(this).data('url');
        addToCart(url, id);
    });

    // ... other click event handlers ...

    function addToCart(url, id, variation = "", quantity = 1) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: { id, quantity, variation},
            success: function (res) {
                if (res.status) {
                        //  toastr.success(res.msg);
                         window.location.reload();

                } else {
                    toastr.error(res.msg);
                }
            },
            error: function (xhr, status, error) {
                toastr.error('An error occurred while processing your request.');
            }
        });
    }

    // ... other functions ...

});

</script>

<script>
    $(document).ready(function() {
    $('.select2').select2({
    closeOnSelect: true
});
});
</script>

<!-- Place this JavaScript code after your HTML content -->
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
            toastr.success(response.msg);
            if(response.status)
            {
                // Redirect to checkout page after adding to cart
                window.location.href = "{{ route('front.checkout.index') }}";
            } else
            {

            }

        });
    });
});
</script>


<script>


    document.addEventListener("DOMContentLoaded", function () {
        var popUpForm = document.getElementById("popUpForm");

        var shouldShowPopup = localStorage.getItem("showPopup");
        var lastCloseTime = localStorage.getItem("lastCloseTime");

        if (!shouldShowPopup || (shouldShowPopup && lastCloseTime && Date.now() - lastCloseTime >= 5 * 60 * 1000)) {
            popUpForm.style.display = "block";
        }
        // setTimeout(function () {
        //         popUpForm.style.display = "none";
        //     }, 10000);
        document.querySelector('.popupGrid').addEventListener('click', function(event) {
            if (event.target.classList.contains('popupGrid')) {
                popUpForm.style.display = "none";
                localStorage.setItem("showPopup", false);
                localStorage.setItem("lastCloseTime", Date.now());
            }
        });
        document.getElementById("close").addEventListener("click", function () {
            popUpForm.style.display = "none";
            localStorage.setItem("showPopup", false);
            localStorage.setItem("lastCloseTime", Date.now());
        });
    });

</script>





@endpush

@push('js')
<script>
$(document).ready(function() {
    $('.download-link').click(function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        const downloadType = $(this).data('download-type');
        const fileUrl = $(this).data('file-url');

        if(downloadType === 'free') {
            // Direct download for free songs
            window.location.href = fileUrl;
        } else {
            // Redirect to payment page for paid songs
            window.location.href = "{{ route('front.checkout.index') }}?product_id=" + productId;
        }
    });
});
</script>
@endpush

@push('js')
<script>
$(document).ready(function() {
    $('.download-link').on('click', function(e) {
        e.preventDefault();
        
        const downloadType = $(this).data('download-type');
        const productId = $(this).data('product-id');
        const fileUrl = $(this).data('file-url');

        if (downloadType === 'free') {
            // Direct download for free songs
            window.location.href = fileUrl;
        } else {
            // Redirect to payment page for paid songs
            window.location.href = "{{ route('front.checkout.index') }}?product_id=" + productId;
        }
    });
});
</script>
@endpush

<script>
let currentAudio = null;

function playAudio(productId) {
    // Stop currently playing audio
    if (currentAudio) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
    }

    // Play new audio
    currentAudio = document.getElementById('audio-' + productId);
    currentAudio.play();

    // Update footer player
    const productElement = currentAudio.closest('li');
    const trackName = productElement.querySelector('.song_name').innerText;
    const artistName = productElement.querySelector('.song_artist').innerText;
    
    // Update footer player info
    document.querySelector('.jp-track-name').innerText = trackName;
    document.querySelector('.jp-artist-name').innerText = artistName;
    
    // Update audio source in footer player
    const footerPlayer = document.querySelector('#jquery_jplayer_1');
    if (footerPlayer) {
        footerPlayer.jPlayer("setMedia", {
            mp3: currentAudio.src
        }).jPlayer("play");
    }
}
</script>
