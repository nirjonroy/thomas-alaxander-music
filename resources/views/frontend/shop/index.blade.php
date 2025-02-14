@extends('frontend.app')
@section('title', 'Shop Product List')

@section('content')
 <!-- Popular Foods Section -->
 <section class="popular-foods py-8 pt-32 lg:pt-48 bg-white">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl lg:text-4xl font-bold text-center text-white bg-gradient-to-r from-orange-500 to-red-500 py-3 mb-8 rounded-md">
        {{$data->name}}
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($filteredProducts  as $key => $product)

        <a
          href="{{ route('front.product.show', [ $product->id ] ) }}"
          class="duration-300 ease-in-out hover:scale-105"
        >
          <div class="bg-white shadow-lg h-88 rounded-lg p-6">
            <img
              src="{{ asset('uploads/custom-images2/'.$product->thumb_image) }}"
              alt="Appetizers"
              class="w-full h-48 object-fill mb-4 rounded-lg"
            />
            <h3 class="text-xl font-bold text-black mb-2">{{ $product->name}} </h3>
            <p class="text-gray-600">
                {!! \Illuminate\Support\Str::limit($product->long_description, 120) !!}
            </p>
            <div class="flex mt-4">
              <h6
                class="font-manrope font-semibold text-2xl leading-9 text-black pr-5 sm:border-r border-gray-200 mr-5"
              >
               $ {{$product->price}}
              </h6>
              <button
                class="bg-gradient-to-r from-orange-500 to-red-500 px-3 py-2 text-white font-bold rounded-full"
              >
                Order Now
              </button>
            </div>
          </div>
        </a>
        @empty

        @endforelse



      </div>
    </div>
  </section>



@endsection

@push('js')
<script>
$(document).ready(function () {

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
$(document).ready(function () {
    var urlParams = new URLSearchParams(window.location.search);
    var minPrice = urlParams.get('min_price');
    var maxPrice = urlParams.get('max_price');

    $('#min-price').val(minPrice || 0);
    $('#max-price').val(maxPrice || {{ $maxPrice }});
    $('#min-price-input').val(minPrice || 0);
    $('#max-price-input').val(maxPrice || {{ $maxPrice }});

     $('#availability-filter-form').on('submit', function (e) {
        e.preventDefault();
        var minPrice = $('#min-price').val();
        var maxPrice = $('#max-price').val();
        var inStock = $('#check-1').prop('checked') ? 1 : 0;
        var outOfStock = $('#check-2').prop('checked') ? 1 : 0;

        var redirectUrl = '{{ route('front.shop') }}' +
            '?min_price=' + minPrice +
            '&max_price=' + maxPrice +
            '&in_stock=' + inStock +
            '&out_of_stock=' + outOfStock;

        window.location.href = redirectUrl;
    });

    $('#clear-filter').on('click', function () {
        window.location.href = '{{ route('front.shop') }}';
    });
});

</script>


@endpush

