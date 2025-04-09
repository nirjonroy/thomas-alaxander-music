<div class="ms_sidemenu_wrapper">
    <div class="ms_nav_close ms_cmenu_toggle">
        <i class="fa fa-angle-right" aria-hidden="true"></i>
    </div>
    <div class="ms_sidemenu_inner">
        <div class="ms_logo_inner">
            <div class="ms_logo">
                <a href="{{route('front.home')}}"><img src="{{ asset(siteInfo()->logo) }}" alt="logo" class="img-fluid bg-white "/></a>
            </div>
            <div class="ms_logo_mini">
                <a href="{{route('front.home')}}"><img src="{{ asset(siteInfo()->logo) }}" alt="mini_logo" class="img-fluid"/></a>
            </div>
        </div>
        <div class="ms_nav_wrapper">
            <h4 class="nav_heading">Browse Music</h4>
            <ul>
                <li>
                    <a href="{{route('front.home')}}" class="active" title="Discover">
                        <span class="nav_icon">
                            <span class="icon icon_discover"></span>
                        </span>
                        <span class="nav_text">
                            discover
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('front.home.about')}}" title="Artists">
                        <span class="nav_icon">
                            <span class="icon icon_artists"></span>
                        </span>
                        <span class="nav_text">
                            artists
                        </span>
                    </a>
                </li>
              
                 <li>
                    <a href="{{ route('front.product.all.product') }}" title="Albums">
                        <span class="nav_icon">
                            <span class="icon icon_albums"></span>
                        </span>
                        <span class="nav_text">
                            All Songs
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('front.blog')}}" title="Stations">
                    <span class="nav_icon">
                        <span class="icon icon_station"></span>
                    </span>
                    <span class="nav_text">
                        Blogs
                    </span>
                    </a>
                </li>

               {{--  <li><a href="music.html" title="Music">
                <span class="nav_icon">
                    <span class="icon icon_music"></span>
                </span>
                <span class="nav_text">
                    music
                </span>
                </a>
                </li>

            </ul>
            <h4 class="nav_heading">Your Music</h4>
            <ul class="nav_downloads">
                <li><a href="download.html" title="Downloads">
                <span class="nav_icon">
                    <span class="icon icon_download"></span>
                </span>
                <span class="nav_text">
                    downloads
                </span>
                </a>
                </li>
                <li><a href="purchase.html" title="Purchased">
                <span class="nav_icon">
                    <span class="icon icon_purchased"></span>
                </span>
                <span class="nav_text">
                    purchased
                </span>
                </a>
                </li>
                <li><a href="favourite.html" title="Favourites">
                <span class="nav_icon">
                    <span class="icon icon_favourite"></span>
                </span>
                <span class="nav_text">
                    favourites
                </span>
                </a>
                </li>
                <li><a href="history.html" title="History">
                <span class="nav_icon">
                    <span class="icon icon_history"></span>
                </span>
                <span class="nav_text">
                    history
                </span>
                </a>
                </li> --}}
            </ul>
        </div>
    </div>
</div>