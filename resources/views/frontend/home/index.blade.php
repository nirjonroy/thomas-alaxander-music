@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="ms_content_wrapper padder_top8">
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
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="top-picks" role="tabpanel" aria-labelledby="top-picks">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">
                                        @foreach ($top_picks as $product)
                                      <li>
                                        <div class="ms_songslist_inner">
                                            <div class="ms_songslist_left">
                                                <div class="songslist_number">
                                                    <h4 class="songslist_sn">01</h4>
                                                    <span class="songslist_play" onclick="playAudio({{$product->id}})"><img src="{{('frontend/assets/images/svg/play_songlist.svg')}}" alt="Play" class="img-fluid"/></span>
                                                    <audio id="audio-{{$product->id}}" src="{{ asset($product->music) }}" preload="none"></audio>
                                                </div>
                                                <div class="songslist_details">
                                                    <div class="songslist_thumb">
                                                        <img src="{{asset('uploads/custom-images2/' . $product->thumb_image)}}" alt="thumb" class="img-fluid" />
                                                    </div>
                                                    <div class="songslist_name">

                                                        <h3 class="song_name"><a href="javascript:void(0);">{{$product->name}}</a></h3>
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
                                                
                                                <div class="ms_songslist_more">
                                                    <span class="songslist_moreicon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.105 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.105 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg></span>
                                                    <ul class="ms_common_dropdown ms_songslist_dropdown">
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_fav"></span>Favourites
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" 
                                                               class="download-link" 
                                                               data-product-id="{{ $product->id }}" 
                                                               data-download-type="{{ $product->download_type }}"
                                                               data-file-url="{{ asset($product->music) }}">
                                                                <span class="common_drop_icon drop_downld"></span>Download Now
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_playlist"></span>Add to Playlist
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_share"></span>Share
                                                            </a>
                                                        </li>
                                                    </ul>
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
                                        @foreach ($tranding_songs as $product)
                                      <li>
                                        <div class="ms_songslist_inner">
                                            <div class="ms_songslist_left">
                                                <div class="songslist_number">
                                                    <h4 class="songslist_sn">01</h4>
                                                    <span class="songslist_play" onclick="playAudio({{$product->id}})"><img src="{{('frontend/assets/images/svg/play_songlist.svg')}}" alt="Play" class="img-fluid"/></span>
                                                    <audio id="audio-{{$product->id}}" src="{{ asset($product->music) }}" preload="none"></audio>
                                                </div>
                                                <div class="songslist_details">
                                                    <div class="songslist_thumb">
                                                        <img src="{{asset('uploads/custom-images2/' . $product->thumb_image)}}" alt="thumb" class="img-fluid" />
                                                    </div>
                                                    <div class="songslist_name">

                                                        <h3 class="song_name"><a href="javascript:void(0);">{{$product->name}}</a></h3>
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
                                                
                                                <div class="ms_songslist_more">
                                                    <span class="songslist_moreicon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.105 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.105 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg></span>
                                                    <ul class="ms_common_dropdown ms_songslist_dropdown">
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_fav"></span>Favourites
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" 
                                                               class="download-link" 
                                                               data-product-id="{{ $product->id }}" 
                                                               data-download-type="{{ $product->download_type }}"
                                                               data-file-url="{{ asset($product->music) }}">
                                                                <span class="common_drop_icon drop_downld"></span>Download Now
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_playlist"></span>Add to Playlist
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_share"></span>Share
                                                            </a>
                                                        </li>
                                                    </ul>
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
                                      @foreach ($products as $product)
                                      <li>
                                        <div class="ms_songslist_inner">
                                            <div class="ms_songslist_left">
                                                <div class="songslist_number">
                                                    <h4 class="songslist_sn">01</h4>
                                                    <span class="songslist_play" onclick="playAudio({{$product->id}})"><img src="{{('frontend/assets/images/svg/play_songlist.svg')}}" alt="Play" class="img-fluid"/></span>
                                                    <audio id="audio-{{$product->id}}" src="{{ asset($product->music) }}" preload="none"></audio>
                                                </div>
                                                <div class="songslist_details">
                                                    <div class="songslist_thumb">
                                                        <img src="{{asset('uploads/custom-images2/' . $product->thumb_image)}}" alt="thumb" class="img-fluid" />
                                                    </div>
                                                    <div class="songslist_name">

                                                        <h3 class="song_name"><a href="javascript:void(0);">{{$product->name}}</a></h3>
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
                                                
                                                <div class="ms_songslist_more">
                                                    <span class="songslist_moreicon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.105 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.105 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg></span>
                                                    <ul class="ms_common_dropdown ms_songslist_dropdown">
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_fav"></span>Favourites
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" 
                                                               class="download-link" 
                                                               data-product-id="{{ $product->id }}" 
                                                               data-download-type="{{ $product->download_type }}"
                                                               data-file-url="{{ asset($product->music) }}">
                                                                <span class="common_drop_icon drop_downld"></span>Download Now
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_playlist"></span>Add to Playlist
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);">
                                                                <span class="common_drop_icon drop_share"></span>Share
                                                            </a>
                                                        </li>
                                                    </ul>
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
@endsection

@push('js')
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
