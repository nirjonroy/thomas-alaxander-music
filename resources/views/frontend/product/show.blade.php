@extends('frontend.app')
@section('title', 'Shop Product List')

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

 <!--Single Food Section-->
 <section class="py-10 pt-32 lg:pt-48 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
        <div
          class="pro-detail w-full flex flex-col justify-center order-last lg:order-none max-lg:max-w-[608px] max-lg:mx-auto"
        >
          <h2 class="mb-2 font-manrope font-bold text-4xl leading-10">
            {{$product->name}}
          </h2>
          <div class="flex flex-col sm:flex-row sm:items-center mb-6">
            <div>
              <h6 class="font-manrope font-semibold text-2xl leading-9 pr-5 sm:border-r border-gray-200 mr-5">
                  <span class="current-price-product" id="current_price_product">
                      @if($product->offer_price != '0')
                          ${{ number_format($product->offer_price, 2) }}
                          <input type="hidden" name="price" id="price_val" value="{{ $product->offer_price }}">
                      @else
                          ${{ number_format($product->price, 2) }}
                          <input type="hidden" name="price" id="price_val" value="{{ $product->price }}">
                      @endif
                  </span>
              </h6>
              <!-- Hidden input for the base price -->
              <input type="hidden" id="base_price" value="{{ $product->price }}">
          
              <!-- Dynamically updated price -->
              <div id="displayed_price">
                  Price: $<span id="final_price">{{ number_format($product->offer_price != '0' ? $product->offer_price : $product->price, 2) }}</span>
              </div>


              
          
              
          </div>

            <div class="flex items-center gap-2">
              <div class="flex items-center gap-1">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_12029_1640)">
                    <path
                      d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                      fill="#FBBF24" />
                  </g>
                  <defs>
                    <clipPath id="clip0_12029_1640">
                      <rect width="20" height="20" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
                 <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_12029_1640)">
                    <path
                      d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                      fill="#FBBF24" />
                  </g>
                  <defs>
                    <clipPath id="clip0_12029_1640">
                      <rect width="20" height="20" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
                 <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_12029_1640)">
                    <path
                      d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                      fill="#FBBF24" />
                  </g>
                  <defs>
                    <clipPath id="clip0_12029_1640">
                      <rect width="20" height="20" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
                 <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_12029_1640)">
                    <path
                      d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                      fill="#FBBF24" />
                  </g>
                  <defs>
                    <clipPath id="clip0_12029_1640">
                      <rect width="20" height="20" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
                 <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_12029_1640)">
                    <path
                      d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                      fill="#FBBF24" />
                  </g>
                  <defs>
                    <clipPath id="clip0_12029_1640">
                      <rect width="20" height="20" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
               
              </div>
              <p class="text-sm font-medium text-gray-500"></p>
            </div>
          </div>
          @if($product->type == 'variable')
          <h6 id="select_size" class="text-lg font-semibold mb-2">Select Combo:</h6>
          @endif
          
          @if($product->type == 'variable')
              @if(count($product->variations))
              <div class="sizes mb-5" id="sizes">
                  @foreach($product->variations as $v)
                      @if(!empty($v->size->title))
                      <div class="size p-2 border rounded-md mb-2 cursor-pointer hover:bg-gray-100" 
                           data-proid="{{ $v->product_id }}" 
                           data-varprice="{{ $v->sell_price }}" 
                           data-varsize="{{ $v->size->title }}"
                           data-varSizeId="{{$v->size_id}}"
                           onclick="selectSize(this)">  <!-- Add onclick event -->
                          <span class="text-sm font-medium">{{ $v->size->title }}</span>
                      </div>
                      @else
                      <div class="text-red-500 text-sm">Size Not Available</div>
                      @endif
                  @endforeach
              </div>
              @else
              <input type="hidden" id="size_value" name="variation_id" value="free">
              <input type="text" name="variation_size_id" id="variation_size_id" value="1" class="hidden">
              @endif
          @else
          <input type="hidden" id="size_value" name="variation_id" value="free">
          <input type="hidden" name="variation_size_id" id="variation_size_id" value="1">
          @endif
          
          <!-- Hidden inputs for selected variation -->
          <input type="hidden" id="pro_price" name="pro_price">
          <input type="hidden" id="size_variation_id" name="size_variation_id">
          
          <p class="mb-4 text-lg font-normal">
            {!!$product->long_description!!}
          </p>

          @if($product->productVariations->isNotEmpty())
    <div class="mb-4">
        <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg font-bold rounded-md flex justify-between items-center">
            Would you like to add a Product Variation?
            <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
            </svg>
        </button>
        <div x-show="open" class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
            <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($product->productVariations as $variation)
                <li class="flex items-center">
                    <input 
                        type="radio" 
                        name="product_variation" 
                        id="variation{{$variation->id}}" 
                        value="{{$variation->id}}" 
                        data-name="{{$variation->name}}" 
                        data-price="{{$variation->products_price}}" 
                        class="mr-2 variation-radio"
                    >
                    <label for="variation{{$variation->id}}" class="font-semibold">{{$variation->name}} - ${{ number_format($variation->products_price, 2) }}</label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


          <!-- Accordion Dropdown -->
          @if($flavours->isNotEmpty())
          <div x-data="{ open: false }" class="my-4 lg:my-8">
            <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg  font-bold rounded-md flex justify-between items-center">
              Choose Flavour
              <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
              </svg>
            </button>
            <div x-show="open" class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
              <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($flavours as $flavour)
                @if(!empty($flavour->name))
                <li class="flex items-center">
  <input type="radio" name="flavour" id="flavour{{$flavour->id}}" value="{{$flavour->name}}" class="mr-2">
  <label for="flavour{{$flavour->id}}" class="font-semibold">{{$flavour->name}}</label>
  {{-- let flavour = $('#flavour_select option:selected').text() || null; --}}
</li>

                @endif
                @endforeach
              </ul>

            </div>
          </div>
          @endif


          @if($topings->isNotEmpty())
          <div x-data="{ open: false }" class="mb-4">
            <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg  font-bold rounded-md flex justify-between items-center">
              Choose Topings
              <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
              </svg>
            </button>
            <div x-show="open" class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
              <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($topings as $toping)
                @if(!empty($toping->name))
                <li class="flex items-center">
  <input type="radio" name="toping" id="toping{{$toping->id}}" value="{{$toping->name}}" class="mr-2">
  <label for="toping{{$toping->id}}" class="font-semibold">{{$toping->name}}</label>
</li>

                @endif
                @endforeach
              </ul>

            </div>
          </div>
          @endif

          @if($dips->isNotEmpty())
          <div x-data="{ open: false }" class="mb-4">
            <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg  font-bold rounded-md flex justify-between items-center">
              Choose Fish
              <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
              </svg>
            </button>
            <div x-show="open" class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
              <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($dips as $dip)
                @if(!empty($dip->name))
                <li class="flex items-center">
                  <input type="radio" name="dip" id="dip{{$dip->id}}" value="{{$dip->name}}" class="mr-2">
                  <label for="dip{{$dip->id}}" class="font-semibold">{{$dip->name}}</label>
                </li>
                @endif
                @endforeach
              </ul>

            </div>
          </div>
          @endif

          

        

          @if($cheeses->isNotEmpty())
          <div x-data="{ open: false }" class="mb-4">
            <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg  font-bold rounded-md flex justify-between items-center">
              Choose cheeses
              <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
              </svg>
            </button>
            <div x-show="open" class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
              <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($cheeses as $cheese)
                @if(!empty($cheese->name))
                <li class="flex items-center">
                  <input type="radio" name="cheese" id="cheese{{$cheese->id}}" value="{{$cheese->name}}" class="mr-2">
                  <label for="cheese{{$cheese->id}}" class="font-semibold">{{$cheese->name}}</label>
                </li>
                @endif
                @endforeach
              </ul>

            </div>
          </div>
          @endif

          @if($vaggies->isNotEmpty())
          <div x-data="{ open: false }" class="mb-4">
            <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg  font-bold rounded-md flex justify-between items-center">
              Choose vaggies
              <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
              </svg>
            </button>
            <div x-show="open" class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
              <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($vaggies as $vaggi)
                @if(!empty($vaggi->name))
                <li class="flex items-center">
                  <input type="radio" name="vaggi" id="vaggi{{$vaggi->id}}" value="{{$vaggi->name}}" class="mr-2">
                  <label for="vaggi{{$vaggi->id}}" class="font-semibold">{{$vaggi->name}}</label>
                </li>
                @endif
                @endforeach
              </ul>

            </div>
          </div>
          @endif

          @if($sauces->isNotEmpty())
          <div x-data="{ open: false }" class="mb-4">
            <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg  font-bold rounded-md flex justify-between items-center">
              Choose sauces
              <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
              </svg>
            </button>
            <div x-show="open" class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
              <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($sauces as $sauce)
                @if(!empty($sauce->id))
                <li class="flex items-center">
                  <input type="radio" name="sauce" id="sauce{{$sauce->id}}" value="{{$sauce->name}}" class="mr-2">
                  <label for="sauce{{$sauce->id}}" class="font-semibold">{{$sauce->name}} </label>
                </li>
                @endif
                @endforeach
              </ul>

            </div>
          </div>
          @endif

          @if($protins->isNotEmpty())
          <div class="mb-4">
              <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg font-bold rounded-md flex justify-between items-center">
                  Would you like to add Combo ?
                  <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
                  </svg>
              </button>
              <div class="mt-2 bg-orange-100 border border-gray-200 rounded-md shadow-lg">
                  <ul class="p-4 grid grid-cols-2 gap-4">
                      @foreach ($protins as $protin)
                      <li class="flex items-center">
                          <input type="radio" name="protein" id="protein{{$protin->id}}" value="{{$protin->name}}" data-name="{{$protin->name}}" data-price="{{$protin->protin_price}}" class="mr-2 protein-radio">
                          <label for="protein{{$protin->id}}" class="font-semibold">{{$protin->name}} - ${{ number_format($protin->protin_price, 2) }}</label>
                      </li>
                      @endforeach
                  </ul>
              </div>
          </div>
          @endif

          @if($sides->isNotEmpty())
    <div class="mb-4">
        <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg font-bold rounded-md flex justify-between items-center">
            Choose Premium Sides
            <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
            </svg>
        </button>
        <div class="mt-2 bg-blue-100 border border-gray-200 rounded-md shadow-lg">
            <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($sides as $side)
                <li class="flex items-center">
                   
                        <!-- Checkbox for two_sides -->
                        <input type="checkbox" name="side[]" id="side{{ $side->id }}" value="{{ $side->side_price }}" data-name="{{ $side->name }}" data-price="{{ $side->sides_price }}" class="mr-2 side-checkbox2">
                    
                    <label for="side{{ $side->id }}" class="font-semibold">{{ $side->name }} - ${{ $side->sides_price }}</label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if($freeSides->isNotEmpty())
    <div class="mb-4">
        <button @click="open = !open" class="w-full text-left px-6 py-3 bg-gray-100 shadow-lg font-bold rounded-md flex justify-between items-center">
            Choose Free Sides
            <svg :class="{ 'transform rotate-180': open }" class="w-5 h-5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.832.445l6 8a1 1 0 01-1.664 1.11L10 5.882 4.832 12.556A1 1 0 013.168 11.445l6-8A1 1 0 0110 3z" clip-rule="evenodd" />
            </svg>
        </button>
        <div class="mt-2 bg-blue-100 border border-gray-200 rounded-md shadow-lg">
            <ul class="p-4 grid grid-cols-2 gap-4">
                @foreach ($freeSides as $side)
                <li class="flex items-center">
                    @if ($product->sides_type == 'one_sides')
                        <!-- Radio for one_sides -->
                        <input type="radio" name="freeSides[]" id="freeSides{{ $side->id }}" data-name="{{ $side->name }}" class="mr-2 side-radio">
                    @elseif ($product->sides_type == 'two_sides')
                        <!-- Checkbox for two_sides -->
                        <input type="checkbox" name="freeSides[]" id="freeSides{{ $side->id }}" data-name="{{ $side->name }}" class="mr-2 side-checkbox">
                    @endif
                    <label for="freeSides{{ $side->id }}" class="font-semibold">{{ $side->name }}</label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

      

      
      
      


          <div class="flex gap-3">
          

@php
    // Get the current day of the week (1 = Monday, 7 = Sunday)
    $currentDay = date('N');
@endphp

@if ($product->is_weekend_only == 1)
    @if ($currentDay == 6 || $currentDay == 7) <!-- Saturday or Sunday -->
    <a href="#" class="inline-block py-3 px-6 bg-[#F37016] shadow-lg hover:bg-[#F37016] text-sm text-white font-bold rounded-md transition duration-200 add_cart mt-4 add-to-cart"
    data-id="{{ $product->id }}"
    data-url="{{ route('front.cart.store') }}">
    Order Now
 </a>
    @else
        <a class="inline-block py-3 px-6 bg-[#F37016] shadow-lg hover:bg-[#F37016] text-sm text-white font-bold rounded-md transition duration-200 add_cart mt-4" onclick="weekendAlert()" disabled>
            Order will be processed only Sat and Sun
        </a>
    @endif
@else
<a href="#" class="inline-block py-3 px-6 bg-[#F37016] shadow-lg hover:bg-[#F37016] text-sm text-white font-bold rounded-md transition duration-200 add_cart mt-4 add-to-cart"
data-id="{{ $product->id }}"
data-url="{{ route('front.cart.store') }}">
Order Now
</a>
@endif

<script>
  function weekendAlert() {
      alert("Order will be processed only Sat and Sun");
  }
</script>
          </div>
        </div>
        <div class="relative h-full min-h-[384px] max-h-[420px]">
          <img src="{{asset('uploads/custom-images/'.$product->thumb_image)}}" alt="Samosa Chaat" class="w-full h-full object-cover object-center rounded-md shadow-md" />
        </div>
      </div>
    </div>
  </section>




@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="
   https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js
   "></scri<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

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
