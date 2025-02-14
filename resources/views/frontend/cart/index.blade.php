@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link rel="stylesheet" href="{{asset('frontend/assets/css/cart.css')}}">
@endpush
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



<section>
    <div class="w-full pt-32 lg:pt-48 bg-white border-t border-b border-gray-200 px-4 py-10 text-gray-800">
      <div class="w-full max-w-3xl mx-auto">
        <div class="md:flex items-start">
          <div class="w-full lg:pr-10">
            <!-- Item Details -->
            <div class="w-full lg:pr-10">
                @php
                    $totalAmount = 0; // Initialize the total amount
                @endphp
                @forelse($cart as $key => $item)
            <div class="w-full mx-auto text-gray-800 font-light mb-6 border-b border-gray-200 pb-6">
              <div class="w-full flex items-center">
                <div class="remove">
                    <button class="btn remove-item" data-id="{{ $key }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Item Image -->
                <div class="overflow-hidden rounded-lg w-32 h-20 bg-gray-50 border border-gray-200">
                  <img src="{{ asset('uploads/custom-images2/'.$item['image']) }}" alt="Whole Turkey Chicken">
                </div>
  
                <!-- Item Title and Additional Details -->
                <div class="flex-grow pl-3">
                  <h6 class="font-semibold uppercase text-gray-600">{{ $item['name'] }}</h6>
                  <div class="flex flex-col lg:flex-row lg:gap-10 lg:justify-start justify-center lg:items-center">

                    <!-- Display Selected Flavors -->
                    <div class="mt-2">
                        <!-- Product Name and Image -->
                       
                        <!-- Show Sides -->
                        @if (!empty($item['sides']))
                            <p class="text-sm text-gray-500 mb-2">Selected Premium Sides:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                @foreach ($item['sides'] as $side)
                                    <li>{{ $side['name'] }} ({{ number_format($side['price'], 2) }})</li>
                                @endforeach
                            </ul>
                        @endif
                    
                        @if (!empty($item['freeSides']))
                            <p class="text-sm text-gray-500 mb-2">Selected Free Sides:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                @foreach ($item['freeSides'] as $side)
                                    <li>{{ $side }}</li>
                                @endforeach
                            </ul>
                        @endif


                        <!-- Show Flavour -->
                        @if (!empty($item['flavour']))
                            <p class="text-sm text-gray-500 mb-2">Flavour:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                <li>{{ $item['flavour'] }}</li>
                            </ul>
                        @endif
                    
                        <!-- Show Topping -->
                        @if (!empty($item['topping']))
                            <p class="text-sm text-gray-500 mb-2">Topping:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                <li>{{ $item['topping'] }}</li>
                            </ul>
                        @endif
                    
                        <!-- Show Dip -->
                        @if (!empty($item['dip']))
                            <p class="text-sm text-gray-500 mb-2">Dip:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                <li>{{ $item['dip'] }}</li>
                            </ul>
                        @endif
                    
                        <!-- Show Protein -->
                        @if (!empty($item['protein_name']))
                            <p class="text-sm text-gray-500 mb-2">Protein:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                <li>{{ $item['protein_name'] }} (+{{ number_format($item['protein_price'], 2) }})</li>
                            </ul>
                        @endif
                    
                        <!-- Show Cheese -->
                        @if (!empty($item['cheese']))
                            <p class="text-sm text-gray-500 mb-2">Cheese:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                <li>{{ $item['cheese'] }}</li>
                            </ul>
                        @endif
                    
                        <!-- Show Veggies -->
                        @if (!empty($item['veggies']))
                            <p class="text-sm text-gray-500 mb-2">Veggies:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                @foreach ($item['veggies'] as $veggie)
                                    <li>{{ $veggie }}</li>
                                @endforeach
                            </ul>
                        @endif
                    
                        <!-- Show Sauce -->
                        @if (!empty($item['sauce']))
                            <p class="text-sm text-gray-500 mb-2">Sauce:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                <li>{{ $item['sauce'] }}</li>
                            </ul>
                        @endif
                    
                        <!-- Show Product Variation -->
                        @if (!empty($item['variation_name']))
                            <p class="text-sm text-gray-500 mb-2">Product Variation:</p>
                            <ul class="font-semibold bg-orange-100 rounded-md px-2 py-1 flex justify-center gap-2">
                                <li>{{ $item['variation_name'] }}</li>
                            </ul>
                        @endif
                    
                       
                    </div>
                    
    
                    <!-- Quantity Selection -->
                    <div class="mt-4">
                      <p class="text-sm text-gray-500">Quantity:</p>
                      <div class="flex items-center mt-1">
                        <button  class="px-4 font-bold text-center text-2xl text-white py-1 bg-orange-500 rounded-md border-muted dec" data-id="{{ $key }}">-</button>
                        <input  type="number" min="1" value="{{ $item['quantity'] }}" data-id="{{ $key }}" class="px-2 py-2 mx-2 text-center border border-gray-200 rounded-md w-12 quantity-value">
                        <button class="px-4 font-bold text-center text-2xl text-white py-1 bg-orange-500 rounded-md border-muted inc" data-id="{{ $key }}">+</button>
                      </div>
                    </div>

                    

                  </div>
                </div>
  
                <!-- Item Price -->
                <div class="text-xl font-semibold text-gray-600">
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
  
            <!-- Subtotal and Tax Details -->
            @if($totalAmount > 0)
            <div class="mb-6 pb-6 border-b border-gray-200 text-gray-800">
              <div class="w-full flex mb-3 items-center">
                <div class="flex-grow">
                  <span class="text-gray-600">Subtotal</span>
                </div>
                <div class="pl-3">
                  <span class="font-semibold">${{ $totalAmount }}</span>
                </div>
              </div>

              <div class="w-full flex mb-3 items-center">
                <div class="flex-grow">
                  <span class="text-gray-600">Tax</span>
                </div>
                <div class="pl-3">
                  <span class="font-semibold">${{ $totalAmount *6/100}}</span>
                </div>
              </div>
              {{-- <div class="w-full flex items-center">
                <div class="flex-grow">
                  <span class="text-gray-600">Taxes (GST)</span>
                </div>
                <div class="pl-3">
                  <span class="font-semibold">$0.20</span>
                </div>
              </div> --}}
            </div>
            @php
                
                $tax = $totalAmount *6/100;
            @endphp
  
            <!-- Total Amount -->
            <div class="mb-6 pb-6 border-b border-gray-200 md:border-none text-gray-800 text-xl">
              <div class="w-full flex items-center">
                <div class="flex-grow">
                  <span class="text-gray-600">Total</span>
                </div>
                <div class="pl-3">
                  <span class="font-semibold">$ {{ $totalAmount + $tax }}</span>
                </div>
              </div>
            </div>
  
            <!-- Proceed to Checkout Button -->
            <a class="inline-block py-3 text-center px-6 bg-gradient-to-r from-orange-500 to-red-500 w-full shadow-lg hover:bg-[#F37016] text-md text-white font-bold rounded-md transition duration-200" href="{{ route('front.checkout.index') }}">Proceed to Checkout</a>
            @else
                    <!-- Optionally, you can add a disabled button if you prefer to show it disabled -->
                    <a class="inline-block py-3 text-center px-6 bg-gray-500 w-full shadow-lg text-md text-white font-bold rounded-md transition duration-200 cursor-not-allowed" href="#" disabled>Proceed to Checkout</a>
                    @endif
        </div>
        </div>
      </div>
    </div>
  </section>






@endsection

