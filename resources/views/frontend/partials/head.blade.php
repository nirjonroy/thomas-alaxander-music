@php
    $settings = DB::table('settings')->first();
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MilkSoulFood')</title>
    <!-- x-icon  -->
    <link rel="shortcut icon" href="{{$settings->favicon}}" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
  />
 
    <link rel="shortcut icon" href="{{$settings->favicon}}" type="image/x-icon">
    {{-- <link rel="stylesheet" href="{{asset('frontend/assets3/css/login.css')}}"> --}}
   
    <style>
  
     
      header {
        position: fixed;
        z-index: 1000;
        width: 100%;
        background-color: white;
        transition: background-color 0.3s ease;
      }

      header.scroll {
        background-color: rgb(255, 243, 243);
      }
      .dropdown {
        display: inline-block;
        position: relative;
      }

      .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 300px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
      }

      .dropdown:hover .dropdown-content {
        margin-top: 5px;
        display: flex;

        flex-direction: column;
        border-radius: 10px;
      }

      .dropdown-content a {
        color: black;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 12px 16px;
      }

      .dropdown-content img {
        width: 40px;
        margin-right: 10px;
      }

      .dropdown-content p {
        margin: 0;
      }

      .dropdown-content a:hover {
        background-color: #f37016;
        color: white;
      }
    </style>

  </head>
