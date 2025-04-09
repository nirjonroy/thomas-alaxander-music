<div class="ms_header">
  <div class="ms_header_inner">
      <div class="ms_top_left">
          <div class="ms_top_search">
            <form action="{{ route('front.product.search') }}">
                <input type="text" class="form-control" placeholder="Search for Song, Artists, Playlists and More..." name="query">
              
                <button type="submit">
                    <span class="search_icon">
                    <img src="{{asset('frontend/assets/images/svg/search.svg')}}" alt="search">
                </span>    
                </button>
            </form>
              
          </div>
          <div class="ms_noti_wrap">
              <span class="noti_icon bg_cmn_iconwrap"><i class="bg_cmn_icon"></i></span>
          </div>
      </div>
      <div class="ms_top_right">
        @guest  
        <div class="ms_pro_inner">
              
              <div class="ms_pro_namewrap">
                  <a href="{{url('login-user')}}" class="btn btn-danger btn-lg" style="font-size: 18px"> Login</a>
              </div>
              
          </div>
          @else
          <div class="ms_pro_inner">
            {{-- <div class="ms_pro_img"> <img src="assets/images/proflile.jpg" alt="Profile"></div> --}}
            <div class="ms_pro_namewrap">
                <span class="pro_name">Hello, {{Auth::user()->name}}</span> <i class="fa fa-caret-down"></i>
            </div>
            <ul class="ms_common_dropdown ms_profile_dropdown">
                <li>
                    <a href="{{url('profile-user')}}">
                        <span class="common_drop_icon drop_pro"></span>Profile
                    </a>
                </li>
                <li>
                    <a href="{{url('logout')}}">
                        <span class="common_drop_icon drop_logt"></span>Logout
                    </a>
                </li>
            </ul>
        </div>
          @endguest
          <div class="ms_cmenu_toggle ms_menu_toggle">
              <span></span>
              <span></span>
              <span></span>
          </div>
      </div>
  </div>
</div>






    

