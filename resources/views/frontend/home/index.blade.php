@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')

<main>
    <!--Hero Section-->
   

    <section class="pt-24 lg:pt-32">
      <div class="owl-carousel owl-theme">
      @foreach ($sliders as $slider)
      <div
      class="max-w-7xl flex flex-col  py-4 mx-auto "
    >
      <div class="w-full  flex items-center justify-center">
        <img
          src="{{asset($slider->image)}}"
          alt="Banner 1"
          class="rounded-lg h-[200px] lg:h-[450px] lg:object-fill object-unset w-full object-cover"
        />
      </div>
    </div>
      @endforeach
        

        
      </div>
    </section>

    <!--End Hero Section-->
    <!-- Our Popular Menu -->
    <section>
      <div class="bg-white lg:py-10 pb-10">
        <div class="max-w-7xl mx-auto px-4">
          <div class="text-center mb-16">
            <h2
              class="text-3xl lg:text-4xl font-bold text-center text-white bg-gradient-to-r from-orange-500 to-red-500 py-4 mb-8 rounded-md"
            >
              Our Popular Menu
            </h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($feateuredCategories as $key => $item)
            <a
              href="{{ route('front.subcategory', [
                                     'type'=>'subcategory',
                                     'slug'=> $item->category->slug
                                     ] ) }}"
              class="transform transition-transform duration-300 ease-in-out hover:scale-105"
            >
              <div class="bg-white shadow-lg rounded-lg p-6">
                <img
                  src="{{ asset('uploads/custom-images2/'.$item->category->image) }}"
                  alt="{{ $item->category->name }}"
                  class="w-full h-48 object-cover mb-4 rounded-lg"
                />
                <h3 class="text-xl font-bold text-black mb-2">
                    {{ $item->category->name }}
                </h3>
                <p class="text-gray-600">
                  Start your meal with our delicious {{ $item->category->name }}.
                </p>
              </div>
            </a>
            @empty
            <strong>No Categories are Available!</strong>
            @endforelse


          </div>
        </div>
      </div>
    </section>

    <!-- Popular Foods Section -->
    <section class="popular-foods py-8 py-4 bg-white">
      <div class="max-w-7xl mx-auto px-4">
        <h2
          class="text-3xl lg:text-4xl font-bold text-center text-white bg-gradient-to-r from-orange-500 to-red-500 py-3 mb-8 rounded-md"
        >
          Our Popular Items
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach ($products as $product)
          <a
          href="{{route('front.product.show', $product->id)}}"
          class="duration-300 ease-in-out hover:scale-105"
        >
          <div class="bg-white shadow-lg h-88 rounded-lg p-6">
            <img
              src="{{asset('uploads/custom-images/'.$product->thumb_image)}}"
              alt="{{$product->name}}"
              class="w-full h-48 object-cover mb-4 rounded-lg"
            />
            <h3 class="text-xl font-bold text-black mb-2">
                {{$product->name}}
            </h3>
            <p class="text-gray-600">
              {!! Str::limit($product->long_description, 100)!!}
            </p>
            <div class="flex mt-4">
              <h6
                class="font-manrope font-semibold text-2xl leading-9 text-black pr-5 sm:border-r border-gray-200 mr-5"
              >
               $ {{$product->price}}
              </h6>
              <button  class="bg-gradient-to-r from-orange-500 to-red-500 px-3 py-2 text-white font-bold rounded-full"
              >
                Order Now
              </button>
            </div>
          </div>
        </a>
          @endforeach






        </div>
      </div>
    </section>
    
  </main>
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
