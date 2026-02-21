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
            <p class="nav_heading">Browse Music</p>
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
                    <a href="{{ route('front.shop') }}" title="Albums">
                        <span class="nav_icon">
                            <span class="icon icon_albums"></span>
                        </span>
                        <span class="nav_text">
                            All Products
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('front.events') }}" title="Albums">
                        <span class="nav_icon">
                            <span class="fab fa-calendar"></span>
                        </span>
                        <span class="nav_text">
                            Event
                        </span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('front.product.all.video') }}" title="Albums">
                        <span class="nav_icon">
                            <span class="icon icon_albums"></span>
                        </span>
                        <span class="nav_text">
                            All Video
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

                @php $custom_pages = DB::table('custom_pages')->where('status', 1)->get();  @endphp
                @foreach($custom_pages as $page)
                <li><a href="{{route('front.customPages', $page->slug)}}" title="Music">
                <span class="nav_icon">
                    <span class="icon icon_music"></span>
                </span>
                <span class="nav_text">
                    {{$page->page_name}}
                </span>
                </a>
                </li>
                @endforeach

                @php $sLinks =DB::table('footer_social_links')->get(); @endphp
                <div style="display: flex; margin-left: 20%;">
                 <p>get me:  </p> <br/>
                @foreach($sLinks as $link)
                
                    <a href="{{$link->link}}" class="me-6 [&>svg]:h-4 [&>svg]:w-4" style="padding: 5px"><i class="{{$link->icon}}"></i>
                    
                    </a>
               
            @endforeach
        </div>
           {{--  </ul>
            <p class="nav_heading">Your Music</p>
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
