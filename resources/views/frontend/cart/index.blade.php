@extends('frontend.app')
@section('title', 'Home')

@section('content')
@php
$totalAmount = 0 ;
@endphp

{{-- <section>
    <div class="w-full pt-32 lg:pt-48 bg-white border-t border-b border-gray-200 px-4 py-10 text-gray-800">
        <div class="w-full max-w-3xl mx-auto">
            <div class=" md:flex items-start">
                <div class="w-full lg:pr-10">
                    @php
                        $totalAmount = 0; // Initialize the total amount
                    @endphp
                    @forelse($cart as $key => $item)
                    <div class="w-full mx-auto text-gray-800 font-light mb-6 border-b border-gray-200 pb-6">
                        <div class="w-full flex justify-between items-center">
                            <div class="remove">
                                <button class="btn remove-item" data-id="{{ $key }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="overflow-hidden rounded-lg w-32 h-20 bg-gray-50 border border-gray-200">
                                <img src="{{ asset('uploads/custom-images2/'.$item['image']) }}" alt="">
                            </div>
                            <div class="flex items-center mt-1">
                                <button  class="px-4 font-bold text-center text-2xl text-white py-1 bg-orange-500 rounded-md border-muted dec" data-id="{{ $key }}">-</button>
                                <input  type="number" min="1" value="{{ $item['quantity'] }}" data-id="{{ $key }}" class="px-2 py-2 mx-2 text-center border border-gray-200 rounded-md w-12 quantity-value">
                                <button class="px-4 font-bold text-center text-2xl text-white py-1 bg-orange-500 rounded-md border-muted inc" data-id="{{ $key }}">+</button>
                                <!--<a href="#" class="btn border-0 ml-4 btn-sm update-cart" data-id="{{ $key }}"><i class="fas fa-repeat"></i></a>-->
                            </div>

                            <div>
                                <span class="font-semibold text-gray-600 text-xl" id="subtotal-{{ $key }}" data-price="{{ number_format($item['price'], 2) }}
"> {{ number_format($item['price'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @php
                        // Calculate the total amount for the cart items
                        $totalAmount += ($item['price'] * $item['quantity']);
                    @endphp
                    @empty
                        <p>No items added</p>
                    @endforelse

                    @if($totalAmount > 0) <!-- Check if cart has items -->
                    <div class="mb-6 pb-6 border-b border-gray-200 text-gray-800">
                        <div class="w-full flex mb-3 items-center">
                            <div class="flex-grow">
                                <span class="text-gray-600">Subtotal</span>
                            </div>
                            <div class="pl-3">
                                <span class="font-semibold">$ {{ $totalAmount }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b border-gray-200 md:border-none text-gray-800 text-xl">
                        <div class="w-full flex items-center">
                            <div class="flex-grow">
                                <span class="text-gray-600">Total</span>
                            </div>
                            <div class="pl-3">
                                <span class="font-semibold">$ {{ $totalAmount }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Show the button if there are items in the cart -->
                    <a class="inline-block py-3 text-center px-6 bg-gradient-to-r from-orange-500 to-red-500 w-full shadow-lg hover:bg-[#F37016] text-md text-white font-bold rounded-md transition duration-200" href="{{ route('front.checkout.index') }}">Proceed to Checkout</a>
                    @else
                    <!-- Optionally, you can add a disabled button if you prefer to show it disabled -->
                    <a class="inline-block py-3 text-center px-6 bg-gray-500 w-full shadow-lg text-md text-white font-bold rounded-md transition duration-200 cursor-not-allowed" href="#" disabled>Proceed to Checkout</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section> --}}




<d class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">


      
      <section>
        <div class="container py-5 mt-5 border-top border-bottom bg-white">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @php $totalAmount = 0; @endphp
    
                    @forelse($cart as $key => $item)
                        <div class="card mb-4 border-0 border-bottom pb-3">
                            <div class="row g-3 align-items-center">
                                <!-- Remove Button -->
                                <div class="col-auto">
                                    <button class="btn btn-sm btn-danger remove-item" data-id="{{ $key }}" style="width:20px; height: 20px; color: black; font-weight: bolder;">
                                        X
                                    </button>
                                </div>
    
                                <!-- Product Image -->
                                <div class="col-auto">
                                    <div class="border rounded-circle overflow-hidden" style="width: 80px; height: 80px;">
                                        <img src="{{ asset('uploads/custom-images2/'.$item['image']) }}"
                                             class="img-fluid rounded-circle" alt="Item Image">
                                    </div>
                                </div>
    
                                <!-- Product Info -->
                                <div class="col">
                                    <h5 class="small text-muted mb-4 pb-2 song_name small">{{ $item['name'] }}</h5>
    
                                    <div class="row align-items-center">
                                        <!-- Quantity -->
                                        <div class="col-auto">
                                            <label class="form-label mb-0 text-muted fs-6">Quantity:</label>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <button class="btn btn-sm btn-warning text-white fw-bold px-3 dec" data-id="{{ $key }}" style="width: 20px; height: 20px;">-</button>
                                            <input type="number" min="1" value="{{ $item['quantity'] }}"
                                                   class="form-control form-control-sm text-center mx-2 quantity-value"
                                                   style="width: 100px;" data-id="{{ $key }}">
                                            <button class="btn btn-sm btn-warning text-white fw-bold px-3 inc" data-id="{{ $key }}" style="width: 20px; height: 20px;">+</button>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Price -->
                                <div class="col-auto text-end">
                                    <span class="small text-muted mb-4 pb-2 song_name small" id="subtotal-{{ $key }}"
                                          data-price="{{ number_format($item['price'], 2) }}">
                                        ${{ number_format($item['price'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @php
                            $totalAmount += ($item['price'] * $item['quantity']);
                        @endphp
                    @empty
                        <p class="text-center">No items added</p>
                    @endforelse
    
                    @if($totalAmount > 0)
                        @php $tax = $totalAmount * 0.06; @endphp
    
                        <!-- Summary -->
                        <div class="card p-4 border-0 border-bottom mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted mb-4 pb-2 song_name small">Subtotal</span>
                                <span class="small text-muted mb-4 pb-2 song_name small">${{ number_format($totalAmount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted mb-4 pb-2 song_name small">Tax (6%)</span>
                                <span class="small text-muted mb-4 pb-2 song_name small">${{ number_format($tax, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted mb-4 pb-2 song_name small">Total</span>
                                <span class="small text-muted mb-4 pb-2 song_name small">${{ number_format($totalAmount + $tax, 2) }}</span>
                            </div>
                        </div>
    
                        <!-- Checkout Button -->
                        <a href="{{ route('front.checkout.index') }}"
                           class="btn btn-lg btn-primary w-100 text-uppercase fw-bold" style="height: 50px; font-size:14px">
                            Proceed to Checkout
                        </a>
                    @else
                        <!-- Disabled Checkout -->
                        <button class="btn btn-lg btn-secondary w-100 text-uppercase fw-bold" disabled>
                            Proceed to Checkout
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>
    
            


{{-- <section class="vh-100" style="background-color: #fdccbc;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center ">
        <div class="col">
          <p style="text-align: center"><span class="h2">Shopping Cart </span><span class="h4"></span></p>
          @php
          $totalAmount = 0; // Initialize the total amount
          @endphp
          <div class="card mb-4">
            <div class="card-body p-4">
                @forelse($cart as $key => $item)
              <div class="row align-items-center">
                <div class="col-md-2">
                  <img src="{{ asset('uploads/custom-images2/'.$item['image']) }}"
                    class="img-fluid" alt="Generic placeholder image" alt="Thomas Alexander" style="border-radius:100px; width: 50px;">
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                  <div>
                    <p class="small text-muted mb-4 pb-2 song_name" >{{ $item['name'] }}</p>
                  
                  </div>
                </div>
                
                <div class="col-md-2 d-flex justify-content-center">
                  <div>
                    <p class="small text-muted mb-4 pb-2" id="subtotal-{{ $key }}" data-price="{{ number_format($item['price'], 2) }}
                    "> ${{ number_format($item['price'], 2) }}</p>
                    
                  </div>
                </div>
                
              </div>
              @empty
                <p>No items added</p>
            @endforelse
            </div>
          </div>
  
          <div class="card mb-5">
            <div class="card-body p-4">
  
              <div class="float-start">
                <p class="mb-0 me-5 d-flex align-items-center">
                  <span class="small me-2">Order total:</span> <span
                    class="small">${{ $totalAmount }}</span>
                </p>
              </div>
  
            </div>
          </div>
  
          <div class="d-flex justify-content-end">
            <button  type="button" small data-mdb-button-init data-mdb-ripple-init class="btn btn-light btn-lg me-2">Continue shopping</button>
            <button  type="button" small data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg">Add to cart</button>
          </div>
  
        </div>
      </div>
    </div>
  </section> --}}

    </div>
</div>









@endsection

