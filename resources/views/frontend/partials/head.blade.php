@php
    $settings = DB::table('settings')->first();
@endphp

  <head>
    <title>@yield('title', 'Thomas Alexander')</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Music">
    <meta name="keywords" content="">
    <meta name="author" content="kamleshyadav">
    <meta name="MobileOptimized" content="320">
    <!--Start Style -->
    <link rel="stylesheet" type="text/css" href="assets/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/js/plugins/swiper/css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="assets/js/plugins/nice_select/nice-select.css">
    <link rel="stylesheet" type="text/css" href="assets/js/plugins/player/volume.css">
    <link rel="stylesheet" type="text/css" href="assets/js/plugins/scroll/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- Favicon Link -->
    <link rel="shortcut icon" type="image/png" href="{{$settings->favicon}}">
</head>
