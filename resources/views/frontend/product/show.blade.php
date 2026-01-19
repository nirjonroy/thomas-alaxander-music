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
        $publisher = optional($seoDefaults)->seo_publisher ?? 'Thomas Alexander';
    @endphp

    <meta charset="UTF-8">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $authorMeta }}">
    <meta name="publisher" content="{{ $publisher }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="{{ $pageTitle }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="product">
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:image:secure_url" content="{{ $imageUrl }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:image" content="{{ $imageUrl }}">
@endsection
@section('content')

<style>
  .sizes{
  /*display: flex;*/
  }
  .sizes .size {
  padding: 5px;
  margin: 5px;
  border: 1px solid #FE9017;
  width: auto;
  text-align: center;
  cursor: pointer;
  min-width: 45px;
  display: inline-block;
  }
  .sizes .size.active{
  background: #f66326;
  color: white;
  font-weight: bold;
  }
  .colors{
  /*display: flex;*/
  }
  .colors .color {
  padding: 5px;
  margin: 5px;
  border: 1px solid #FE9017;
  width: auto;
  text-align: center;
  cursor: pointer;
  display: inline-block;
  height: 35px;
  width: 35px;
  }
 .colors .color.active{
 background: #0d6efd;
 color: white;
 font-weight: bold;
 padding: 6px;
 border: 4px solid white;
 outline: 2px solid red;
 }
</style>

  <div class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">

    <div class="container">
      <div class="row">
      <div class="col-md-12">
        <div class="ms_about_wrapper">
        
        <div class="ms_about_content" style="margin-top:10px; background: rgb(243, 241, 241); padding: 5px;">
          <div class="ms_about_img " style="float: right">
            <input type="hidden" value="{{$product->type}}" name="type" id="type">
            @if($product->type == 'single')
            <img src="{{asset('uploads/custom-images/' . $product->thumb_image)}}" alt="About Thomas Alexander"
            width="200px" height="200px" style="background:white;  border-radius:50%; ">
            @else
            <img src="{{asset('uploads/custom-images/' . $product->thumb_image)}}" alt="About Thomas Alexander"
            width="200px" height="200px" style="background:white  ">
            @endif
          </div>
          <h1 style="font-size:14pt">{{$product->name}}</h1>

          @if($product->type == 'variable') <h6 id="select_size">Select Size : </h6> @else @endif
               @if($product->type == 'variable')

               @if(count($product->variations))

               <div class="sizes" id="sizes" style="margin-bottom: 5px;">
                  @foreach($product->variations as $v)
                  @if(!empty($v->size->title))
                  <div class="size" data-proid="{{ $v->product_id }}" data-varprice="{{ $v->sell_price }}" data-varsize="{{ $v->size->title }}"
                     value="{{$v->id}}" data-varSizeId="{{$v->size_id}}">
                     @if($v->size->title == 'free')
                     {{ $v->size->title }}
                     <input type="hidden" id="size_value" name="variation_id">
                     <input type="hidden" id="size_variation_id" name="size_variation_id">
                     <input type="hidden" name="pro_price" id="pro_price">
                     <input type="hidden" name="variation_size_id" id="variation_size_id">
                     @else
                     {{ $v->size->title }}
                     <input type="hidden" id="size_value" name="variation_id">
                     <input type="hidden" id="size_variation_id" name="size_variation_id">
                     <input type="hidden" name="pro_price" id="pro_price">
                     <input type="hidden" name="variation_size_id" id="variation_size_id">
                     @endif
                  </div>
                  @else
                  Size Not Available
                  @endif
                  @endforeach
               </div>
               @else
               <input type="hidden" id="size_value" name="variation_id" value="free">
               <input type="text" name="variation_size_id" id="variation_size_id" value="1">
               @endif
               @else
               <input type="hidden" id="size_value" name="variation_id" value="free">
               <input type="hidden" name="variation_size_id" id="variation_size_id" value="1">
               @endif
          
          @if($product->type == 'single')
        @if($product->download_type == 'free')
        <audio controls>
        <source src="{{ asset($product->music) }}" type="audio/mpeg">
        </audio>
      @else
        <audio controls>
        <source src="{{ asset($product->demo_song) }}" type="audio/mpeg">
        </audio>
      @endif
      @endif
          @if($product->download_type == 'free')
        {{-- <a href="{{ asset($product->download_link) }}" class="btn btn-danger btn-lg" style="
        width: 10%;
        height: 25px;
        font-size: 13px;
        ">Download
        </a> --}}
      @else
        <br>
        <div style="text-align: center"></div>
        @if($product->offer_price != 0)
        <b>${{ number_format($product->price, 2) }}</b>
        <sub><del style="color: red">${{ number_format($product->offer_price, 2) }}</del></sub>
        <input type="hidden" name="price" id="price_val" value="{{ $product->offer_price }}">
      @else
        ${{ number_format($product->price, 2) }}
        <input type="hidden" name="price" id="price_val" value="{{ $product->price }}">
      @endif
        <br>
        <br>
        <div class="qty-btn-box mt-3 col-4">
                     <div class="qty-box mb-2" >
                        <p>Quantity: </p>
                        <input type="number" min="1" name="quantity" id="quantity" value="1" class="form-control font-20 rounded-0 shadow-none qty" style="background: #f66326; color: white; font-weight: bold; text-align: center;">
                        
                     </div>
                  </div>

        <br>
        <br>          
        @guest
        <a href="#" class="add_cart mt-4 add-to-cart " style="background: green; color: black; padding: 5px; font-weight: bold;" data-id="{{ $product->id }}"
        data-url="{{ route('front.cart.store') }}" onclick="alert('Please login first')">Order Now</a>
      @else
        <a href="#" class=" add_cart mt-4 add-to-cart btn btn-success btn-lg" data-id="{{ $product->id }}"
        data-url="{{ route('front.cart.store') }}" style="width: 100px; height: auto; font-size: 14px;">
        Order Now
        </a>
      @endguest
        <br><br>
      @endif
      @if($product->artist_name !== null)
          <span><b>Artist : {{$product->artist_name}}</b></span>
          <br> <br>
          @endif   
          
          <p style="background: white !important">
           {!!$product->long_description!!}
          </p>

          <br>

          <div class="container pt-5" style="margin: 5px">
            <div>
              <h2 style="text-align: center">All Comments</h2>
            
              @foreach ($reviews as $review)
              <img src="https://merics.org/sites/default/files/styles/ct_team_member_default/public/2022-01/avatar-placeholder_neu.png?h=ecfff384&itok=4epCYDGE" alt="" style="width: 50px; height: 50px; border-radius: 50px;">
              <b>{{$review->user->name}}</b> <br>
              <p style="text-align: left; margin-left: 45px;">{{$review->review}}</p> 
              @endforeach
            </div>
            <br> <br>
          <h2 style="text-align: center">Make Comment</h2>
          <form action="{{route('front.product.product-reviews.store')}}" method="post">

            @csrf
            <input type="hidden" value="{{$product->id}}" name="product_id">
            <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea class="form-control" name="review" rows="5" id="comment"></textarea>
            </div>

            <button type="submit" class="btn btn-danger"
            style="width: 20%; height: 20px; font-size: 14px; display:block;  margin: auto; ">Submit</button>
          </form>
          </div>

        </div>


        </div>
      </div>
      </div>
    </div>

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
