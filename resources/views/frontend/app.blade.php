<!DOCTYPE html>
<html lang="en">
    @include('frontend.partials.head')


<body>
        <!----loader Start---->
    <div class="ms_loader">
        <div class="wrap">
            <img src="{{('frontend/assets/images/loader.gif')}}" alt="loader">
        </div>
    </div>
    <!----Main Wrapper Start---->
    @php
    $slide = DB::table('sliders')->first();
@endphp

<div class="ms_main_wrapper ms_mainindex_wrapper" 
     style="background: url('{{ asset($slide->image) }}') no-repeat; 
            background-position: right top; 
            height: 100vh; 
            background-size: contain;">

    @include('frontend.partials.sidebar')
    <div class="ms_content_wrapper padder_top8">
    @include('frontend.partials.header')
    @php $cart = session()->get('cart', []); @endphp

    <!--<a href="{{ route('front.checkout.index') }}"-->
    <!--class="btn pmd-btn-fab pmd-ripple-effect btn-primary fixed-cart-bottom1"-->
    <!--type="button"> @if($cart !== null) <span style="color: white;position: absolute;top: 4px;right: 17px;">{{ count($cart) }}</span>  @endif<i class="fas fa-shopping-cart"></i></a>-->
    @yield('content')
    </div>
    @include('frontend.partials.footer')


