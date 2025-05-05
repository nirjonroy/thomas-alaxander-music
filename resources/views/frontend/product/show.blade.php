@extends('frontend.app')
@section('title', 'Music List')

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
        <source src="...." type="audio/mpeg">
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
        @if($product->offer_price != '0')
        <b>${{ number_format($product->offer_price, 2) }}</b>
        <sub><del style="color: red">${{ number_format($product->price, 2) }}</del></sub>
        <input type="hidden" name="price" id="price_val" value="{{ $product->offer_price }}">
      @else
        ${{ number_format($product->price, 2) }}
        <input type="hidden" name="price" id="price_val" value="{{ $product->price }}">
      @endif
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
  <script
    src="
     https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js
     "></scri < script src = "https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js" ></script>

  <script>
    function showToasterMessage(message, type) {
      toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: "toast-top-right",
      timeOut: 8000 // Display time in milliseconds
      };

      toastr[type](message);
    }
  </script>
  <script>
    $(document).ready(function () {
    let basePrice = parseFloat($('#price_val').val()) || 0; // Initial base price from product

    // Function to calculate total price
    function calculateTotalPrice() {
      let sidesPrice = 0;
      let proteinPrice = 0;
      let finalPrice = basePrice; // Start with the base price
      let quantity = parseInt($('#quantity').val()) || 1;

      // For premium sides (additional cost)
      const selectedPremiumSideCheckboxes = $('.side-checkbox2:checked');
      selectedPremiumSideCheckboxes.each(function () {
      sidesPrice += parseFloat($(this).data('price')) || 0;
      });

      // For free sides, no additional cost
      const selectedFreeSideCheckboxes = $('.side-checkbox:checked');
      selectedFreeSideCheckboxes.each(function () {
      // No price addition for free sides
      });

      // Get the selected product variation price (replace base price if variation is selected)
      const selectedProductVariation = $('.variation-radio:checked');
      if (selectedProductVariation.length) {
      finalPrice = parseFloat(selectedProductVariation.data('price')) || basePrice;
      }

      // Get the selected protein price (add to the final price)
      const selectedProtein = $('.protein-radio:checked');
      if (selectedProtein.length) {
      proteinPrice = parseFloat(selectedProtein.data('price')) || 0;
      }

      // Add sides and protein prices to the final price
      finalPrice = (finalPrice + sidesPrice + proteinPrice) * quantity;

      // Update the displayed price and hidden input value
      $('#final_price').text(finalPrice.toFixed(2)); // Update the displayed price
      $('#price_val').val(finalPrice); // Update hidden input value
    }

    // Limit free side selection to two for free sides
    $('.side-checkbox').on('change', function () {
      const selectedCheckboxes = $('.side-checkbox:checked');

      // If more than 2 free sides are selected, uncheck the last one and show an alert
      if (selectedCheckboxes.length > 2) {
      $(this).prop('checked', false); // Uncheck the last checkbox if more than 2 are selected
      alert('You can only select two free sides.');
      }

      // Recalculate the price after enforcing the two-sides limit
      calculateTotalPrice();
    });

    // Event listeners to recalculate price for premium sides, product variations, and proteins
    $('.side-checkbox2').on('change', calculateTotalPrice); // For premium sides
    $('.variation-radio').on('change', calculateTotalPrice); // For product variations
    $('.protein-radio').on('change', calculateTotalPrice); // For proteins

    // Quantity update
    $('.increase-qty, .decrease-qty').on('click', function () {
      let qtyInput = $(this).siblings('.qty');
      let newQuantity = parseInt(qtyInput.val());
      newQuantity = $(this).hasClass('increase-qty') ? newQuantity + 1 : newQuantity - 1;
      if (newQuantity < 1) newQuantity = 1; // Avoid going below 1
      qtyInput.val(newQuantity);
      calculateTotalPrice(); // Update price with new quantity
    });

    // Add to cart functionality
    $(document).on('click', '.add-to-cart', function (e) {
      e.preventDefault();

      // Gather selected options
      let productId = $(this).data('id');
      let cartUrl = $(this).data('url');
      let selectedSides = [];
      let selectedFreeSides = [];
      let quantity = parseInt($('#quantity').val()) || 1;

      // Collect selected premium sides (with additional cost)
      $('input[name="side[]"]:checked').each(function () {
      selectedSides.push({
        name: $(this).data('name'),
        price: $(this).data('price')
      });
      });

      // Collect selected free sides (without additional cost)
      $('input[name="freeSides[]"]:checked').each(function () {
      selectedFreeSides.push($(this).data('name'));
      });

      // Collect other attributes like protein, flavour, topping, etc.
      let flavour = $('input[name="flavour"]:checked').val() || null;
      let topping = $('input[name="topping"]:checked').val() || null;
      let dip = $('input[name="dip"]:checked').val() || null;

      let selectedProtein = $('.protein-radio:checked').data('name') || null;
      let proteinPrice = $('.protein-radio:checked').data('price') || 0;

      let selectedProductVariation = $('.variation-radio:checked');
      let productVariationId = selectedProductVariation.attr('id') ? selectedProductVariation.attr('id').replace('variation', '') : null;
      let productVariationName = selectedProductVariation.data('name') || null;
      let productVariationPrice = selectedProductVariation.data('price') || 0;

      let cheese = $('input[name="cheese"]:checked').val() || null;
      let veggies = [];
      $('input[name="vaggi"]:checked').each(function () {
      veggies.push($(this).val());
      });

      let sauce = $('input[name="sauce"]:checked').val() || null;

      // Get the final calculated price (with proteins, sides, and product variations)
      let finalPrice = $('#price_val').val(); // Get the calculated final price from the hidden input

      // Add to cart via AJAX
      $.ajax({
      type: "POST",
      url: cartUrl,
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      data: {
        productId,
        quantity,
        finalPrice,
        selectedSides,
        selectedFreeSides, // Add free sides to data payload
        flavour,
        topping,
        dip,
        protein: {
        name: selectedProtein,
        price: proteinPrice
        },
        product_variation: {
        id: productVariationId,
        name: productVariationName,
        price: productVariationPrice
        },
        cheese,
        veggies,
        sauce
      },

      success: function (res) {
        if (res.status) {
        toastr.success(res.msg);
        alert('Added to cart');
        if (res.url) {
          window.location.href = res.url;
        } else {
          location.reload();
        }
        } else {
        toastr.error(res.msg || 'Something went wrong');
        }
      },
      error: function () {
        toastr.error('An error occurred while adding to cart.');
      }
      });
    });
    });














  </script>









@endpush