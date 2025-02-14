@extends('frontend.app')
@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/cart.css') }}">
@endpush
@section('content')
@php $sub_total = 0; @endphp



<div style="display: none; margin-top: 10%">
<table border="1">
  <tbody style="display: block">
      
  @forelse($cart as $key => $item)
    <tr style="display: block">
      <td>
            <div class="remove">
                <button class="btn remove-item" data-id="{{ $key }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </td>
      <th scope="row"><img src="{{ asset('uploads/custom-images2/'.$item['image']) }}" alt="" class="rounded border" style="height: 60px;width: 60px;" width=""></th>
      <td>{{ Str::limit($item['name'], 15) }}</td>
      <td>
            <div class="quantity d-flex">
                <button class="btn rounded-0 border border-muted dec" data-id="{{ $key }}">-</button>
                <input type="number" style="width: 45px;"  min="1" class="border border-muted text-center rounded-0 quantity-value" value="{{ $item['quantity'] }}" data-id="{{ $key }}" required>
                <button class="btn rounded-0 border border-muted inc" data-id="{{ $key }}">+</button>
            </div>
        </td>
      <td>{{ $item['price'] }}</td>
    </tr>
     @php
     $sub_total += $item['quantity'] * $item['price']; @endphp
     @empty
  @endforelse
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Subtotal</td>
        <td>{{ number_format($sub_total, 2) }}
          <input type="text" name="sub_total" value="{{ number_format($sub_total, 2) }}">
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Shipping</td>
        <td><p id="shipping_value">0.00 </p></td>
    </tr>





   
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Total</td>
        <td>
        <p id="total_amount">${{ number_format($sub_total, 2) }} </p>
        <input type="text" name="total_amount" id="total_amount" value="{{ number_format($sub_total, 2) }}">
        </td>
    </tr>
  </tbody>
</table>
</div>

<section class="pt-32 lg:pt-48">
    <div
      class="max-w-3xl mx-auto bg-white border-t border-b border-gray-200  py-10 text-gray-800"
    >
      <div class="w-full max-w-7xl mx-auto">
        <div class="">

          <div class="px-3 ">
            <form action="{{ route('front.checkout.store') }}" method="POST" id="checkoutForm">
                @csrf
            <div
              class="w-full mx-auto text-gray-800 font-light mb-6 pb-6"
            >
            <div
            class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-3 text-gray-800 font-light mb-6"
          >
            <div class="mb-4">
              <label for="name" class="text-gray-600 font-semibold"
                >Your Name</label
              >
              <input
                id="name"
                name="shipping_name"
                type="text"
                value="{{ $user ? $user->name : '' }}"
                placeholder="Enter your name"
                class="w-full px-3 py-2 mt-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
              />
              <input type="hidden" name="ip_address" id="ip_address" value="">
            </div>
            <div class="mb-4">
              <label for="phone" class="text-gray-600 font-semibold"
                >Your Phone Number</label
              >
              <input
                id="phone"
                name="order_phone"
                type="text"
                value="{{ $user ? $user->phone : '' }}"
                placeholder="Enter your phone number"
                class="w-full px-3 py-2 mt-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
              />
            </div>
            <div class="mb-4">
              <label for="address" class="text-gray-600 font-semibold"
                >Your Address</label
              >
              <textarea
                id="address"
                name="shipping_address"
                value="{{ $user ? $user->address : '' }}"
                rows="4"
                placeholder="Enter your address"
                class="w-full px-3 py-2 mt-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
              ></textarea>
            </div>
            <input type="hidden" name="sub_total" value="{{ number_format($sub_total, 2) }}">

@php    
// Example subtotal; replace with your actual value.
 $tax = number_format($sub_total * 6 / 100, 2);
 $sub_total = number_format($sub_total + $tax, 2);
@endphp
{{-- <h1>dfsjljk</h1> --}}
<input type="hidden" name="tax" value="{{$tax}}">
            <input selected type="hidden" value="21" class="charge_radio delivery_charge_id" id="ship" data-shippingid="" name="shipping_method" data-shipping="">
                        <input type="hidden" name="total_amount" id="total_amount" value="{{ number_format($sub_total, 2) }}">
          </div>
            </div>

            <div>
                <button type="submit"
              class="inline-block py-3 text-center px-6 bg-gradient-to-r from-orange-500 to-red-500 w-full shadow-lg hover:bg-[#F37016] text-md text-white font-bold rounded-md transition duration-200"

              >Confirm Order</button
            >
            </div>
        </form>
          </div>
          <div class="px-3 md:w-2/12">


{{-- <div class="w-full mx-auto rounded-lg bg-white border border-gray-200 text-gray-800 font-light mb-6" >
              <div class="w-full p-3 border-b border-gray-200">
                <div class="mb-5">
                  <label
                    for="type1"
                    class="flex items-center cursor-pointer"
                  >
                    <input
                      type="radio"
                      class="h-5 w-5 text-[#FF007F]"
                      name="type"
                      id="type1"
                      checked
                    />
                    <img
                      src="https://leadershipmemphis.org/wp-content/uploads/2020/08/780370.png"
                      class="h-6 ml-3"
                    />
                  </label>
                </div>
                <div>
                  <div class="mb-3">
                    <label
                      class="text-gray-600 font-semibold text-sm mb-2 ml-1"
                      >Name on card</label
                    >
                    <div>
                      <input
                        class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                        placeholder="John Smith"
                        type="text"
                      />
                    </div>
                  </div>
                  <div class="mb-3">
                    <label
                      class="text-gray-600 font-semibold text-sm mb-2 ml-1"
                      >Card number</label
                    >
                    <div>
                      <input
                        class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                        placeholder="0000 0000 0000 0000"
                        type="text"
                      />
                    </div>
                  </div>
                  <div class="mb-3 -mx-2 flex items-end">
                    <div class="px-2 w-1/4">
                      <label
                        class="text-gray-600 font-semibold text-sm mb-2 ml-1"
                        >Expiration date</label
                      >
                      <div>
                        <select
                          class="form-select w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer"
                        >
                          <option value="01">01 - January</option>
                          <option value="02">02 - February</option>
                          <option value="03">03 - March</option>
                          <option value="04">04 - April</option>
                          <option value="05">05 - May</option>
                          <option value="06">06 - June</option>
                          <option value="07">07 - July</option>
                          <option value="08">08 - August</option>
                          <option value="09">09 - September</option>
                          <option value="10">10 - October</option>
                          <option value="11">11 - November</option>
                          <option value="12">12 - December</option>
                        </select>
                      </div>
                    </div>
                    <div class="px-2 w-1/4">
                      <select
                        class="form-select w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer"
                      >
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                      </select>
                    </div>
                    <div class="px-2 w-1/4">
                      <label
                        class="text-gray-600 font-semibold text-sm mb-2 ml-1"
                        >Security code</label
                      >
                      <div>
                        <input
                          class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                          placeholder="000"
                          type="text"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="w-full p-3">
                <label for="type2" class="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    class="form-radio h-5 w-5 text-indigo-500"
                    name="type"
                    id="type2"
                  />
                  <img
                    src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg"
                    width="80"
                    class="ml-3"
                  />
                </label>
              </div>
            </div> --}}

          </div>
        </div>
      </div>
    </div>


    
  </section>




@endsection

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    // Toggle coupon form visibility on button click
    $("#coupon-toggle").click(function() {
        $("#coupon-form").toggle();
    });
});
</script>


<script>
    // Fetch user's IP address using a third-party API
    fetch('https://api64.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            document.getElementById('ip_address').value = data.ip;
        })
        .catch(error => {
            console.error('Error fetching IP address:', error);
        });
</script>


<script type="text/javascript">

// document.addEventListener("DOMContentLoaded", function () {
//   // Your code here
//   const myDiv = document.getElementById("data_info");
//   const myDiv2 = document.getElementById("order_info");
//   window.addEventListener("resize", function () {
//         if (window.innerWidth <= 768) {
//             myDiv.classList.add("order-1");
//             myDiv2.classList.add("order-2");
//         } else {
//           myDiv.classList.remove("order-1");
//           myDiv2.classList.remove("order-2");
//     }
//   });
// });

    $(document).ready(function () {

  		$('.charge_radio').click(function(){
          getCharge();
        // alert('hi');
        });

        function getCharge(){getCharge

            var testval = $('input:radio:checked.charge_radio').map(function(){
                return Number($(this).data('shipping')); }).get().join(",");
            var shipping_id = $('input:radio:checked.charge_radio').val();
            $('#shipping_id').val(shipping_id);
            $('p#shipping_value').text(testval);
            let sub_total = '{{cartTotalAmount()['total']}}';
            let total=Number(testval)+Number(sub_total);
            $('p#total_amount').text(total.toFixed(2));

        }

    $(document).on('submit', 'form#coupon_form', function(e){
        e.preventDefault();
        let url = $(this).attr('action');
        let method = $(this).attr('method');
        let data = $(this).serialize();
        $.ajax({
            type: method,
            url:  url,
            data: data,
            success : function(res) {
                if(res.status == true)
                {
                    $('p#total_amount').text(res.total_price);
                    // alert(res.total_price);
                    // $('p#total_amount').text('sg');
                    // res.total_price.toFixed(2);
                }
            }
        });
    });

    $(document).on('submit', 'form#checkoutForm', function(e){
  	e.preventDefault();
    $('span.error').text('');
    let url = $(this).attr('action');
    let method = $(this).attr('method');
    let data =$(this).serialize();
    $.ajax({
      type:method,
      url:url,
      data:data,
      success: function(res)
      {
        if(res.status)
        {
          toastr.success(res.msg);
          if(res.url)
          {
              document.location.href = res.url;
          }
          else{
             // $('#optver').modal('show');
             window.location.reload();
          }
        }
        else{
          toastr.error(res.msg);
        }
      },
      error: function(response)
      {
        $.each(response.responseJSON.errors, function(name, error){
          $(document).find('[name='+name+']').closest('div').after('<span class="error text-red-400 font-semibold">'+error+'</span>');
          toastr.error(error);
        });
      }
    });

  });

  });

 </script>
