@php
    $setting = App\Models\Setting::first();
    $footer = App\Models\Footer::first();
@endphp

@include('admin.header')
<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg custom_click"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle"><a target="_blank" href="{{route('front.home')}}" class="nav-link nav-link-lg" style="color:black"><i class="fas fa-home" ></i> {{__('admin.Visit Website')}}</i></a>

          </li>

          @php
              $header_admin=Auth::guard('admin')->user();
              $defaultProfile = App\Models\BannerImage::whereId('15')->first();
          @endphp
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
          @if(!empty($header_admin->image))
            @if ($header_admin->image)
              <img alt="image" src="{{ asset($header_admin->image) }}" class="rounded-circle mr-1">
              @else
              <img alt="image" src="{{ asset($defaultProfile->image) }}" class="rounded-circle mr-1">
              @endif
              @else
              @endif
              @if(!empty($header_admin->name))
            <div class="d-sm-none d-lg-inline-block" style="color: black">{{ $header_admin->name }}</div>
            @else
            <div class="d-sm-none d-lg-inline-block" style="color: black">{{ Auth::user()->name }}</div>
            @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right">

              <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> {{__('admin.Profile')}}
              </a>
              <div class="dropdown-divider"></div>
              <a href="{{ route('admin.logout') }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault();
              document.getElementById('admin-logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> {{__('admin.Logout')}}
              </a>
            {{-- start admin logout form --}}
            <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            {{-- start admin logout form --}}
            <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            {{-- end admin logout form --}}


            </div>
          </li>
        </ul>
      </nav>




      @include('admin.sidebar')

      @yield('admin-content')



      <footer class="main-footer">
        <div class="footer-left">
            {{ $footer->copyright }}
          </div>
          <div class="footer-right">
              {{ $setting->app_version }}
            </div>
        </footer>
      </footer>
    </div>
  </div>

  @include('admin.footer')
