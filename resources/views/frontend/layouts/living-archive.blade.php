<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Living Archive')</title>
    @yield('seos')

    <!-- Core CSS from Helping Hand template -->
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/fontawesome/css/v4-shims.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/css/color.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/css/dl-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/css/flexslider.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/css/prettyphoto.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/living-archive/css/responsive.css') }}">
    {{-- Style switcher alternate styles --}}
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-one.css') }}" title="color-one" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-two.css') }}" title="color-two" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-three.css') }}" title="color-three" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-four.css') }}" title="color-four" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-five.css') }}" title="color-five" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-six.css') }}" title="color-six" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-seven.css') }}" title="color-seven" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-eight.css') }}" title="color-eight" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-nine.css') }}" title="color-nine" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-ten.css') }}" title="color-ten" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/color-black.css') }}" title="color-black" media="screen" />

    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/pattren1.css') }}" title="pattren1" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/pattren2.css') }}" title="pattren2" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/pattren3.css') }}" title="pattren3" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/pattren4.css') }}" title="pattren4" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/pattren5.css') }}" title="pattren5" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/pattren-black.css') }}" title="pattren-black" media="screen" />

    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/background1.css') }}" title="background1" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/background2.css') }}" title="background2" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/background3.css') }}" title="background3" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/background4.css') }}" title="background4" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('frontend/living-archive/switcher/background5.css') }}" title="background5" media="screen" />
    @stack('css')
</head>
<body>
    @yield('content')

    <!-- JS -->
    <script src="{{ asset('frontend/living-archive/script/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/modernizr.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/jquery.dlmenu.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/flexslider-min.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/goalProgress.min.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/jquery.prettyphoto.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/waypoints-min.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/newsticker.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/parallex.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/styleswitch.js') }}"></script>
    <script src="{{ asset('frontend/living-archive/script/functions.js') }}"></script>
    @stack('js')
</body>
</html>
