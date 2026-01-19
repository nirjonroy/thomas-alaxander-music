@extends('frontend.app')
@section('title', $product->seo_title ?? $product->name ?? 'Product Details')
@section('seos')
    @php
        $pageTitle = $product->seo_title ?? $product->name;
        $pageDesc = \Illuminate\Support\Str::limit(strip_tags($product->long_description), 180);
        $pageUrl = url()->current();
        $imageUrl = $product->thumb_image
            ? asset('uploads/custom-images2/' . ltrim($product->thumb_image, '/'))
            : asset(siteInfo()->logo);
        $seoDefaults = \App\Models\SeoSetting::where('page_name', 'Product Details')->first();
        $canonical = optional($seoDefaults)->canonical_url ?: $pageUrl;
        $keywords = optional($seoDefaults)->seo_keywords ?? ($product->name . ', Thomas Alexander merchandise');
        $authorMeta = optional($seoDefaults)->seo_author ?? 'Thomas Alexander';
        $siteName = optional($seoDefaults)->site_name ?? config('app.name', 'Thomas Alexander');
        $authorMeta = optional($seoDefaults)->seo_author ?? $siteName;
        $pageTitle = $pageTitle ?: (optional($seoDefaults)->meta_title ?? $siteName);
        $rawDesc = $product->long_description;
        $rawDesc = $rawDesc ?: optional($seoDefaults)->meta_description;
        $pageDesc = \Illuminate\Support\Str::limit(strip_tags($rawDesc ?? ''), 180);
        $metaImageValue = optional($seoDefaults)->meta_image;
        $metaImage = $imageUrl
            ?: ($metaImageValue
                ? (str_starts_with($metaImageValue, 'http') ? $metaImageValue : asset($metaImageValue))
                : asset(siteInfo()->logo));
        $publisher = optional($seoDefaults)->seo_publisher ?? $siteName;
        $copyright = optional($seoDefaults)->meta_copyright;
    @endphp

    <meta charset="UTF-8">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $authorMeta }}">
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
    <meta property="og:type" content="product">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
@endsection
@section('content')

<style>
  .product-detail-shell {
    max-width: 100%;
    margin: 0;
    width: 100%;
  }
  .product-detail-page {
    padding: 40px 12px 0 12px;
  }
  .product-detail-card {
    position: relative;
    overflow: hidden;
    border-radius: 28px;
    padding: 32px;
    background: linear-gradient(120deg, rgba(18, 24, 38, 0.95), rgba(16, 22, 36, 0.85));
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 30px 70px rgba(6, 10, 18, 0.55);
    width: 100%;
  }
  .product-detail-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: var(--product-bg);
    background-size: cover;
    background-position: right center;
    opacity: 0.35;
  }
  .product-detail-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 20% 20%, rgba(255, 124, 68, 0.18), transparent 45%),
      radial-gradient(circle at 80% 0%, rgba(255, 255, 255, 0.12), transparent 40%),
      linear-gradient(110deg, rgba(12, 18, 30, 0.95), rgba(12, 18, 30, 0.75) 55%, rgba(12, 18, 30, 0.28) 100%);
  }
  .product-detail-inner {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 36px;
    align-items: center;
  }
  .product-detail-left {
    display: flex;
    flex-direction: column;
    gap: 16px;
    color: #f7f1e6;
  }
  .product-title-block {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .product-kicker {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: rgba(245, 235, 220, 0.75);
  }
  .product-title {
    font-size: clamp(26px, 3vw, 38px);
    margin: 0;
    font-weight: 700;
  }
  .product-artist {
    margin: 0;
    font-size: 15px;
    color: rgba(245, 235, 220, 0.8);
  }
  .product-variant h6 {
    margin: 0 0 8px;
    font-size: 12px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(245, 235, 220, 0.7);
  }
  .product-detail-shell .sizes {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 0;
  }
  .product-detail-shell .sizes .size {
    padding: 6px 14px;
    border: 1px solid rgba(255, 140, 80, 0.55);
    border-radius: 999px;
    font-size: 12px;
    background: rgba(14, 20, 32, 0.7);
    color: #f7f1e6;
    cursor: pointer;
    min-width: 60px;
    text-align: center;
  }
  .product-detail-shell .sizes .size.active {
    background: linear-gradient(120deg, #ff7a2c, #ff4b2b);
    color: #1b0d05;
    border-color: rgba(255, 150, 90, 0.9);
    font-weight: 700;
  }
  .product-detail-shell .colors {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }
  .product-detail-shell .colors .color {
    border: 1px solid rgba(255, 140, 80, 0.55);
    border-radius: 10px;
    height: 34px;
    width: 34px;
    cursor: pointer;
    background: rgba(14, 20, 32, 0.7);
  }
  .product-detail-shell .colors .color.active {
    outline: 2px solid rgba(255, 150, 90, 0.9);
    border-color: rgba(255, 150, 90, 0.9);
  }
  .product-audio {
    background: rgba(12, 18, 30, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 999px;
    padding: 10px 16px;
  }
  .product-audio audio {
    width: 100%;
    height: 36px;
  }
  .product-audio audio::-webkit-media-controls-panel {
    background-color: rgba(18, 24, 38, 0.9);
  }
  .product-audio audio::-webkit-media-controls-play-button {
    background-color: #ff7a2c;
    border-radius: 50%;
  }
  .product-price {
    display: flex;
    align-items: baseline;
    gap: 12px;
    font-size: 22px;
    font-weight: 700;
  }
  .product-price .price-old {
    font-size: 14px;
    color: rgba(255, 140, 140, 0.8);
  }
  .product-qty {
    display: flex;
    flex-direction: column;
    gap: 6px;
    font-size: 13px;
    color: rgba(245, 235, 220, 0.8);
  }
  .qty-control {
    display: flex;
    align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 10px;
    background: rgba(11, 16, 28, 0.6);
    overflow: hidden;
    width: fit-content;
  }
  .qty-btn {
    width: 40px;
    height: 38px;
    border: none;
    background: rgba(255, 255, 255, 0.05);
    color: #f7f1e6;
    font-size: 18px;
    cursor: pointer;
  }
  .qty-input {
    width: 72px;
    height: 38px;
    border: none;
    background: transparent;
    color: #fdf7ed;
    text-align: center;
    font-weight: 700;
  }
  .qty-input:focus {
    outline: none;
  }
  .qty-input::-webkit-outer-spin-button,
  .qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  .qty-input[type=number] {
    -moz-appearance: textfield;
  }
  .product-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
  }
  .product-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 22px;
    border-radius: 999px;
    font-weight: 700;
    border: 1px solid transparent;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .product-btn--primary {
    background: linear-gradient(120deg, #ff7a2c, #ff4b2b);
    color: #1b0d05;
    box-shadow: 0 12px 24px rgba(255, 115, 54, 0.35);
  }
  .product-btn--ghost {
    background: rgba(10, 16, 28, 0.6);
    color: #f7f1e6;
    border-color: rgba(255, 255, 255, 0.2);
  }
  .product-btn:hover {
    transform: translateY(-1px);
  }
  .product-meta-line {
    font-size: 13px;
    color: rgba(245, 235, 220, 0.8);
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
  }
  .meta-divider {
    color: rgba(245, 235, 220, 0.5);
  }
  .product-detail-right {
    display: flex;
    justify-content: center;
  }
  .product-cover {
    width: 260px;
    height: 260px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(10, 16, 28, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 18px 32px rgba(6, 10, 18, 0.45);
    overflow: hidden;
  }
  .product-cover--round {
    border-radius: 50%;
    padding: 10px;
  }
  .product-cover--round img {
    border-radius: 50%;
  }
  .product-cover--square {
    border-radius: 20px;
    padding: 8px;
  }
  .product-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .product-section-card {
    margin-top: 26px;
    background: rgba(11, 16, 28, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 24px;
    color: #f7f1e6;
    box-shadow: 0 18px 40px rgba(6, 10, 18, 0.35);
  }
  .product-section-title {
    font-size: 18px;
    margin: 0 0 14px;
  }
  .product-description {
    color: rgba(245, 235, 220, 0.85);
    line-height: 1.7;
  }
  .product-description p {
    color: inherit;
  }
  .comment-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-top: 12px;
  }
  .comment-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: rgba(10, 16, 28, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 12px;
  }
  .comment-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.12);
  }
  .comment-body strong {
    color: #fdf7ed;
  }
  .comment-body p {
    margin: 6px 0 0;
    color: rgba(245, 235, 220, 0.8);
  }
  .comment-empty {
    color: rgba(245, 235, 220, 0.7);
    margin: 0;
  }
  .comment-form {
    margin-top: 18px;
  }
  .comment-label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    color: rgba(245, 235, 220, 0.7);
  }
  .comment-input {
    width: 100%;
    background: rgba(10, 16, 28, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    padding: 14px;
    color: #f7f1e6;
    resize: vertical;
    min-height: 120px;
  }
  .comment-input:focus {
    outline: none;
    border-color: rgba(255, 150, 90, 0.8);
  }
  .comment-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 12px;
  }
  @media (max-width: 991px) {
    .product-detail-page {
      padding: 60px 16px 0 16px;
    }
    .product-detail-inner {
      grid-template-columns: 1fr;
    }
    .product-detail-right {
      order: -1;
    }
    .product-cover {
      width: 220px;
      height: 220px;
    }
  }
  @media (max-width: 576px) {
    .product-detail-page {
      padding: 80px 12px 0 12px;
    }
    .product-detail-card {
      padding: 22px;
    }
    .product-actions {
      flex-direction: column;
      align-items: stretch;
    }
    .product-btn {
      width: 100%;
    }
  }
</style>

  <div class="ms_index_wrapper common_pages_space product-detail-page">
      @php
        $coverImage = $product->thumb_image
            ? asset('uploads/custom-images/' . ltrim($product->thumb_image, '/'))
            : asset(siteInfo()->logo);
        $displayArtist = $product->artist_name;
        if ($product->type == 'single') {
          $displayArtist = trim((string) $displayArtist);
          if ($displayArtist === '' || preg_match('/^\d+(\.\d+)?$/', $displayArtist)) {
            $displayArtist = config('app.name', 'Thomas Alexander');
          }
        }
        $durationDisplay = null;
        if (!empty($product->duration)) {
          $durationRaw = trim((string) $product->duration);
          if (preg_match('/^\d+(\.\d+)?$/', $durationRaw)) {
            $minutes = (int) floor((float) $durationRaw);
            $seconds = (int) round(((float) $durationRaw - $minutes) * 60);
            if ($seconds >= 60) {
              $minutes += 1;
              $seconds = 0;
            }
            $durationDisplay = sprintf('%02d:%02d', $minutes, $seconds);
          } else {
            $durationDisplay = $durationRaw;
          }
        }
      @endphp
      <div class="product-detail-shell">
        <div class="product-detail-card" style="--product-bg: url('{{ $coverImage }}');">
          <div class="product-detail-inner">
            <div class="product-detail-left">
              <input type="hidden" value="{{ $product->type }}" name="type" id="type">
              <div class="product-title-block">
                <span class="product-kicker">{{ $product->type == 'single' ? 'Single' : 'Product' }}</span>
                <h1 class="product-title">{{ $product->name }}</h1>
                @if(!empty($displayArtist))
                  <p class="product-artist">{{ $displayArtist }}</p>
                @endif
              </div>

              @if($product->type == 'variable')
                <div class="product-variant">
                  <h6 id="select_size">Select Size : </h6>
                  @if(count($product->variations))
                    <div class="sizes" id="sizes">
                      @foreach($product->variations as $v)
                        @if(!empty($v->size->title))
                          <div class="size" data-proid="{{ $v->product_id }}" data-varprice="{{ $v->sell_price }}" data-varsize="{{ $v->size->title }}"
                               value="{{$v->id}}" data-varSizeId="{{$v->size_id}}">
                            {{ $v->size->title }}
                            <input type="hidden" id="size_value" name="variation_id">
                            <input type="hidden" id="size_variation_id" name="size_variation_id">
                            <input type="hidden" name="pro_price" id="pro_price">
                            <input type="hidden" name="variation_size_id" id="variation_size_id">
                          </div>
                        @else
                          <span>Size Not Available</span>
                        @endif
                      @endforeach
                    </div>
                  @else
                    <input type="hidden" id="size_value" name="variation_id" value="free">
                    <input type="text" name="variation_size_id" id="variation_size_id" value="1">
                  @endif
                </div>
              @else
                <input type="hidden" id="size_value" name="variation_id" value="free">
                <input type="hidden" name="variation_size_id" id="variation_size_id" value="1">
              @endif

              @if($product->type == 'single')
                <div class="product-audio">
                  @if($product->download_type == 'free')
                    <audio controls>
                      <source src="{{ asset($product->music) }}" type="audio/mpeg">
                    </audio>
                  @else
                    <audio controls>
                      <source src="{{ asset($product->demo_song) }}" type="audio/mpeg">
                    </audio>
                  @endif
                </div>
              @endif

              @if($product->download_type != 'free')
                <div class="product-price">
                  @if($product->offer_price != 0)
                    <span class="price-current">${{ number_format($product->offer_price, 2) }}</span>
                    <span class="price-old"><del>${{ number_format($product->price, 2) }}</del></span>
                    <input type="hidden" name="price" id="price_val" value="{{ $product->offer_price }}">
                  @else
                    <span class="price-current">${{ number_format($product->price, 2) }}</span>
                    <input type="hidden" name="price" id="price_val" value="{{ $product->price }}">
                  @endif
                </div>

                <div class="product-qty">
                  <span>Quantity:</span>
                  <div class="qty-control">
                    <button type="button" class="qty-btn decrease-qty">-</button>
                    <input type="number" min="1" name="quantity" id="quantity" value="1" class="qty-input qty">
                    <button type="button" class="qty-btn increase-qty">+</button>
                  </div>
                </div>

                <div class="product-actions">
                  @guest
                    <a href="#" class="product-btn product-btn--primary add_cart add-to-cart" data-id="{{ $product->id }}"
                       data-url="{{ route('front.cart.store') }}" onclick="alert('Please login first')">Buy Now</a>
                  @else
                    <a href="#" class="product-btn product-btn--primary add_cart add-to-cart" data-id="{{ $product->id }}"
                       data-url="{{ route('front.cart.store') }}">Buy Now</a>
                  @endguest
                  <a href="{{ route('living-archive.donate') }}" class="product-btn product-btn--ghost">Donate</a>
                </div>
              @endif

              <div class="product-meta-line">
                @if(!empty($displayArtist))
                  <span>Artist: {{ $displayArtist }}</span>
                @endif
                @if(!empty($durationDisplay))
                  <span class="meta-divider">|</span>
                  <span>Length: {{ $durationDisplay }}</span>
                @endif
              </div>
            </div>

            <div class="product-detail-right">
              <div class="product-cover {{ $product->type == 'single' ? 'product-cover--round' : 'product-cover--square' }}">
                <img src="{{ $coverImage }}" alt="{{ $product->name }}">
              </div>
            </div>
          </div>
        </div>

        <div class="product-section-card">
          <h2 class="product-section-title">About this {{ $product->type == 'single' ? 'track' : 'product' }}</h2>
          <div class="product-description">
            {!!$product->long_description!!}
          </div>
        </div>

        <div class="product-section-card">
          <h2 class="product-section-title">Comments</h2>
          <div class="comment-list">
            @forelse ($reviews as $review)
              <div class="comment-item">
                <img src="https://merics.org/sites/default/files/styles/ct_team_member_default/public/2022-01/avatar-placeholder_neu.png?h=ecfff384&itok=4epCYDGE" alt="{{ $review->user->name }}" class="comment-avatar">
                <div class="comment-body">
                  <strong>{{ $review->user->name }}</strong>
                  <p>{{ $review->review }}</p>
                </div>
              </div>
            @empty
              <p class="comment-empty">No comments yet.</p>
            @endforelse
          </div>

          <form action="{{route('front.product.product-reviews.store')}}" method="post" class="comment-form">
            @csrf
            <input type="hidden" value="{{$product->id}}" name="product_id">
            <label for="comment" class="comment-label">Write a comment</label>
            <textarea class="comment-input" name="review" rows="5" id="comment" placeholder="Write a comment..."></textarea>
            <div class="comment-actions">
              <button type="submit" class="product-btn product-btn--primary">Post Comment</button>
            </div>
          </form>
        </div>
      </div>
  </div>


@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="
   https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js
   "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script src="https://www.jqueryscript.net/demo/magnify-image-hover-touch/dist/jquery.izoomify.js"></script>
<script>
   $(document).ready(function () {
       $('.buy-now').on('click', function (e) {
           e.preventDefault();

           let variation_id = $('#size_variation_id').val();
           let variation_size = $('#size_value').val();
           let variation_color = $('#color_value').val();
           let variation_price = $('#pro_price').val();
           var productId = $(this).attr('href').split('/').pop();
           
           var proQty = $('#quantity').val();
           let variation_size_id = $('input[name="variation_size_id"]').val();
        
           let variation_color_id = $('input[name="variation_color_id"]').val();
           var retrieve_discount = $('input[id="retrieve_discount"]').val();
          
           let image = $('input#pro_img').val();
           let pro_type = $('input#type').val();
          
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
           $.post(addToCartUrl,
           {
               id              : productId,
               quantity        : proQty,
               variation_id    : variation_id,
               varSize         : variation_size,
               varColor        : variation_color,
               variation_price : variation_price,
               variation_size_id : variation_size_id,
               variation_color_id : variation_color_id,
               retrieve_discount : retrieve_discount,
               image          : image,
               pro_type       : pro_type
           },

           function (response) {

               if(response.status)
               {
                   toastr.success(response.msg);
                   // Redirect to checkout page after adding to cart
                   window.location.href = "{{ route('front.checkout.index') }}";
               } else {
            // Check if the response contains validation errors
            if (response.errors) {
                for (var field in response.errors) {
                    if (response.errors.hasOwnProperty(field)) {
                        for (var i = 0; i < response.errors[field].length; i++) {
                            toastr.error(response.errors[field][i]);
                        }
                    }
                }
            } else {
                toastr.error(response.msg || 'An error occurred while processing your request.');
            }
        }

           });
       });
   });

</script>
<script>
   $(document).ready(function () {
       $('.increase-qty').on('click', function () {
           var qtyInput = $(this).siblings('.qty');
           var newQuantity = parseInt(qtyInput.val()) + 1;
           qtyInput.val(newQuantity);
       });

       $('.decrease-qty').on('click', function () {
           var qtyInput = $(this).siblings('.qty');
           var newQuantity = parseInt(qtyInput.val()) - 1;
           if (newQuantity > 0) {
               qtyInput.val(newQuantity);
           }
         else{

         }
       });
   });


</script>
<script>
   $(function () {

      $(document).on('click', '.add-to-cart', function (e) {
          
          let variation_id = $('#size_variation_id').val();
          let variation_size = $('#size_value').val();
          let variation_size_id = $('input[name="variation_size_id"]').val();
          let variation_color = $('#color_value').val();
          let variation_color_id = $('input[name="variation_color_id"]').val();
          let variation_price = $('#pro_price').val();
          var quantity = $('#quantity').val();
          let image = $('input#pro_img').val();
          let pro_type = $('input#type').val();
          // alert(pro_type);
          
          let proName=$('input[name="product_name"]').val();
          let proId=$('input[name="product_id"]').val();
          let catId=$('input[name="category_id"]').val();
          
          window.dataLayer = window.dataLayer || [];

        	dataLayer.push({ecommerce:null});
                dataLayer.push({
                    event: "add_to_cart",
                    ecommerce : {
                        currency: "BDT",
                        value: variation_price,
                        items: [
                            {
                              item_id: proId,
                              item_name: proName,
                              item_category: catId,
                              price: variation_price,
                              quantity: quantity
                            }
                        ]
                    }
            });
          

          let id = $(this).data('id');
          let url = $(this).data('url');

          addToCart(url, id,variation_size, variation_color, variation_id,variation_price,quantity, variation_size_id, variation_color_id,image,pro_type,type="");
      });
     

      function addToCart(url, id, varSize ="", varColor = "", variation_id="",variation_price="",quantity, variation_size_id, variation_color_id,image="", pro_type,type="") {
          var csrfToken = $('meta[name="csrf-token"]').attr('content');

          $.ajax({
              type: "POST",
              url: url,
              headers: {
                  'X-CSRF-TOKEN': csrfToken
              },
              data: { id,varSize,varColor,variation_id, variation_price,quantity, variation_size_id,  variation_color_id,image,pro_type},
             success: function (res) {
                
                  if (res.status) {
                      toastr.success(res.msg);
            if (type) {
               
                if (res.url !== '') {
                    document.location.href = res.url;
                } else {
                    alert('no');
                    // Handle specific case
                }
            } else {
                window.location.reload();
            }
        } else {
            // Check if the response contains validation errors
            if (res.errors) {
                for (var field in res.errors) {
                    if (res.errors.hasOwnProperty(field)) {
                        for (var i = 0; i < res.errors[field].length; i++) {
                            toastr.error(res.errors[field][i]);
                        }
                    }
                }
            } else {
                toastr.error(res.msg || 'An error occurred while processing your request.');
            }
        }

              },
              error: function (xhr, status, error) {
                  toastr.error('An error occurred while processing your request.');
              }
          });
      }

      // ... other functions ...


   });



   $(document).ready(function() {
       
            var firstSizeElement = $('#sizes .size:first');
            var firstColorElement = $('#colors .color:first');
            firstSizeElement.click();
            firstColorElement.click();
       
              $('.popup-link').magnificPopup({
                  type: 'image', // Set the content type to 'image'
                  gallery: {
                      enabled: true // Enable gallery mode
                  }
              });
          });

   $('#sizes .size').on('click', function(){
      $('#sizes .size').removeClass('active');
      $(this).addClass('active');
      let value = $(this).attr('value');
      let varSize = $(this).data('varsize');
      let variation_size_id = $(this).data('varsizeid');
      //  alert(variation_size_id);
      $('#select_size').text('Select Size : '+varSize);
      
      var retrieve_price = $('input[id="retrieve_price"]').val();

      // Assuming you have retrieved the selected variation price somehow
      let variationPrice = parseFloat($(this).data('varprice'));

      $.ajax({
          type: 'get',
          url: '{{ route("front.product.get-variation_price") }}',
          data: {
              varSize,value,
              variationPrice,
              variation_size_id
          },
          success: function(res) {
              $('.current-price-product').text('' + res.price);
              $('#size_value').val();
              $('#variation_size_id').val();
              $('#price_val').val(res.price);
              $('#pro_price').val(res.price);
              
              var retrieve_discount = Number(retrieve_price) - Number(res.price);
              $('input[id="retrieve_discount"]').val(retrieve_discount);
              $('span#dis_amount').text(retrieve_discount);
              console.log(res);
          }
      });

      $("#size_value").val(varSize);
      $("#size_variation_id").val(value);
      $("#variation_size_id").val(variation_size_id);
   });


   let imageClick = false;

   $('#colors .color').on('click', function(){
      $('#colors .color').removeClass('active');
      $(this).addClass('active');
      let value = $(this).attr('value');
      let varColor = $(this).data('varcolor');
      let product_id = $(this).data('proid');
      let color_id = $(this).data('colorid');
      let variation_color_id = $(this).data('variationcolorid');
      let variation_size_id = $('input[name="variation_size_id"]').val();
    //   alert(product_id);

      $('#select_color').text('Select Color : '+varColor);

      // Assuming you have retrieved the selected variation price somehow
      let variationColor = parseFloat($(this).data('varcolor'));

      $.ajax({
          type: 'get',
          url: '{{ route("front.product.get-variation_color") }}',
          data: {
              varColor,
            	value,
              variationColor,
            	product_id,
              color_id,
              variation_color_id,
              variation_size_id
            // Pass variation price to the server
          },
          success: function(res) {
              //$('.current-price-product').text('' + res.price);
            	$('.testslide-image').html(res.var_images);
                $('input[name="pro_img"]').val(res.pro_img);
            	$('#color_value').val();
              //$('#price_val1').val(res.price);
              console.log(res);
              imageClick = true;
              
            if(res.stock != '0' ){
                $('p.stock_check').text('');
                
            }              
            else{
                
                 $('p.stock_check').text('Stock not available');
            }
              
              
          }
      });

      $("#color_value").val(varColor);
      $("#color_value1").val(value);
      $("#variation_color_id").val(variation_color_id);
   });

   $(document).on('click', '.slider-container', function() {
      if (imageClick) {

      }
   });


   // JavaScript function to change the big image
    function changeImage(imageUrl) {

        document.getElementById('big-image').src = imageUrl;
    }


    function changeImage(newImageSrc) {
        // Get the "big-image" element by its ID
        var bigImage = document.getElementById("big-image");

        // Update the source of the big image with the new image source
        bigImage.src = newImageSrc;
    }
    $(document).ready(function () {
            $('.testslide-image').izoomify();
        });


</script>
@endpush
