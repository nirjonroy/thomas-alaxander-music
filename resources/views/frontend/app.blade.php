<!DOCTYPE html>
<html lang="en">
    @include('frontend.partials.head')


<body>
        <!----loader Start---->
    @php
        $loaderImage = optional(siteInfo())->loader_image;
        $loaderSrc = $loaderImage ? asset($loaderImage) : asset('frontend/assets/images/loader.gif');
    @endphp
    <div class="ms_loader">
        <div class="wrap">
            <img src="{{ $loaderSrc }}" alt="loader">
        </div>
    </div>
    <!----Main Wrapper Start---->
    

<div class="ms_main_wrapper ms_mainindex_wrapper" 
     >

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

    <div class="living-float-group">
        <a href="{{ route('front.home.living-archive') }}" class="living-float-btn">
            Living Archive
        </a>
        <a href="{{ route('living-archive.donate') }}" class="living-float-btn living-float-btn--donate">
            Donate
        </a>
    </div>

    <style>
        .living-float-group {
            position: fixed;
            right: 24px;
            bottom: 32px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 1050;
        }
        .living-float-btn {
            background: linear-gradient(120deg,#0f0f0f,#2b2b2b);
            color: #fefefe;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12rem;
            padding: 12px 22px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.25);
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
            text-align: center;
            transition: transform 0.2s ease;
        }
        .living-float-btn:hover {
            color: #fff;
            background: linear-gradient(120deg,#1c1c1c,#3d3d3d);
            transform: translateY(-1px);
        }
        .living-float-btn--donate {
            background: linear-gradient(120deg,#ff4f5e,#ff9330);
            color: #1c0f05;
            border-color: rgba(255,255,255,0.35);
        }
        .living-float-btn--donate:hover {
            color: #1c0f05;
            background: linear-gradient(120deg,#ff6a6c,#ffb347);
        }
        @media (max-width: 767px) {
            .living-float-group {
                left: 0;
                right: 0;
                bottom: 16px;
                flex-direction: row;
                width: calc(100% - 32px);
                margin: 0 auto;
                justify-content: center;
                padding: 0 8px;
                gap: 10px;
            }
            .living-float-btn {
                flex: 1 1 auto;
                padding: 10px 14px;
                font-size: 12px;
                letter-spacing: 0.08rem;
            }
        }
    </style>
