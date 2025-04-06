@extends('frontend.app')
@section('title', 'Music List')

@section('content')

  <div class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">

    <div class="container">
      <div class="row">
      <div class="col-md-12">
        <div class="ms_about_wrapper">
        <div class="ms_about_img">
          <img src="{{asset('uploads/custom-images/' . $product->thumb_image)}}" alt="About Thomas Alexander"
          width="200px" height="200px" style="background:white">
        </div>
        <div class="ms_about_content" style="margin-top:10px; background: rgb(243, 241, 241); padding: 5px;">

          <h1 style="font-size:14pt">{{$product->name}}</h1>
          <audio controls>
          <source src="{{ asset($product->music) }}" type="audio/mpeg">
          </audio>

          @if($product->download_type == 'free')
        {{-- <a href="{{ asset($product->download_link) }}" class="btn btn-danger btn-lg" style="
            width: 10%;
            height: 25px;
            font-size: 13px;
          ">Download
        </a> --}}
      @else
      @if($product->offer_price != '0')
      ${{ number_format($product->offer_price, 2) }}
      <sub><del>${{ number_format($product->price, 2) }}</del></sub>
      <input type="hidden" name="price" id="price_val" value="{{ $product->offer_price }}">
    @else
      ${{ number_format($product->price, 2) }}
      <input type="hidden" name="price" id="price_val" value="{{ $product->price }}">
    @endif

      <a href="#" class=" add_cart mt-4 add-to-cart" data-id="{{ $product->id }}"
      data-url="{{ route('front.cart.store') }}">
      Order Now
      </a>
    @endif
          <span>{{$product->artist_name}}</span>
          <p style="background: white !important">
          {!!$product->long_description!!}
          </p>

          <div class="container pt-5" style="margin: 5px">
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