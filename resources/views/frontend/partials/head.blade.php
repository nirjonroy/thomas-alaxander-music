@php
    $settings = DB::table('settings')->first();
@endphp

  <head>
    <title>@yield('title', 'Thomas Alexander')</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta name="keywords" content="thomas, alexander, music, t-shirt">
    <meta name="author" content="Thomas Alexander">
    <meta name="MobileOptimized" content="320">
    <!--Start Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/fonts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
<link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/js/plugins/swiper/css/swiper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/js/plugins/nice_select/nice-select.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/js/plugins/player/volume.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/js/plugins/scroll/jquery.mCustomScrollbar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/style.css') }}">
@stack('css')
@php
    $seo = DB::table('settings')->first();
     $SeoSettings = DB::table('seo_settings')->where('id', 1)->first();
    @endphp
    @yield('seos')
    <!-- Favicon Link -->
    <link rel="shortcut icon" type="image/png" href="{{ asset(siteInfo()->logo) }}"">
</head>
