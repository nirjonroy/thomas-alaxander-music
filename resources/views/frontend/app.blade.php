<!DOCTYPE html>
<html lang="en">
    @include('frontend.partials.head')
    <div
      class="preloader bg-orange-100 fixed top-0 left-0 w-full h-full flex justify-center items-center z-50"
    >
      <div class="loader rounded-full w-20 h-20 lg:w-40 lg:h-40 animate-ping">
        <img
          src="{{asset('frontend/assets/images/logo.png')}}"
          alt="Loading Icon"
          class="loading-icon mx-auto"
        />
      </div>
    </div>
    <style>
        .fixed-cart-bottom1 {
    position: fixed;
    bottom: 2rem;
    right: 4px;
    background: #9d4803;
    border-radius: 50px;
    height: 60px;
    width: 60px;
    cursor: pointer;
    box-shadow: 2px 2px 8px #03259D;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.5s;
    z-index: 9999;
    border : #03259D;
}
    </style>
<body>
    <div class="content ">
    @include('frontend.partials.header')
    @php $cart = session()->get('cart', []); @endphp

    <!--<a href="{{ route('front.checkout.index') }}"-->
    <!--class="btn pmd-btn-fab pmd-ripple-effect btn-primary fixed-cart-bottom1"-->
    <!--type="button"> @if($cart !== null) <span style="color: white;position: absolute;top: 4px;right: 17px;">{{ count($cart) }}</span>  @endif<i class="fas fa-shopping-cart"></i></a>-->
    @yield('content')

    @include('frontend.partials.footer')


