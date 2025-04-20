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
<div class="ms_content_wrapper padder_top8">
  <style>
    .form-control {
        background-color: aliceblue !important;
    }
</style>

  <div class="ms_index_wrapper common_pages_space">
    <section class="py-5" style="background-color: transparent; min-height: 100vh;">

      <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-12 col-lg-12">
          <form action="{{ route('front.checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="card shadow-lg border-0 rounded-4 p-4">
              <h5 class="text-center mb-4 fw-bold text-uppercase small">Shipping Information</h5>
    
              <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input id="name" name="shipping_name" type="text"
                  value="{{ $user ? $user->name : '' }}" class="form-control" placeholder="Enter your name">
                <input type="hidden" name="ip_address" id="ip_address" value="">
              </div>
    
              <div class="mb-3">
                <label for="phone" class="form-label">Your Phone Number</label>
                <input id="phone" name="order_phone" type="text"
                  value="{{ $user ? $user->phone : '' }}" class="form-control" placeholder="Enter your phone number">
              </div>
    
              <div class="mb-3">
                <label for="address" class="form-label">Your Address</label>
                <textarea id="address" name="shipping_address" class="form-control"
                  rows="3" placeholder="Enter your address">{{ $user ? $user->address : '' }}</textarea>
              </div>
    
              @php
                $tax = number_format($sub_total * 6 / 100, 2);
                $sub_total = number_format($sub_total, 2);
              @endphp
    
              <input type="hidden" name="sub_total" value="{{ number_format($sub_total , 2) }}">
              <input type="hidden" name="tax" value="{{ $tax }}">
              <input type="hidden" name="shipping_method" value="21" class="delivery_charge_id" id="ship">
              <input type="hidden" name="total_amount" id="total_amount" value="{{ number_format($sub_total, 2) }}">
    
              <div class="d-grid">
                <button type="submit" class="btn btn-gradient text-white fw-bold"
                  style="background: linear-gradient(to right, #f97316, #ef4444); border-radius: 12px; height: 40px; font-size: 20px;">
                  Confirm Order
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
    


  </div>
</div>


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
