<div class="ms_header">
    <div class="ms_header_inner">
        <!-- Left Section: Logo (Hidden on mobile, shown on larger screens) -->
        <div class="ms_header_logo d-none d-md-block">
            <img src="{{asset('frontend/assets/images/logo.png')}}" alt="Thomas Alexander" class="logo-img">
        </div>

        <!-- Center Section: Search Bar -->
        <div class="ms_header_search">
            <div class="ms_top_search">
                <form action="{{ route('front.product.search') }}" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" 
                               class="form-control search-input" 
                               placeholder="Search for Song, Artists, Playlists and More..." 
                               name="query">
                        <button type="submit" class="search-btn">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Section: Cart & User Actions -->
        <div class="ms_header_actions">
            <!-- Cart -->
            <div class="ms_cart_wrap">
                <a href="{{route('front.cart.index')}}" class="cart-link">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="cart-count">{{ totalCartItems() }}</span>
                </a>
            </div>

            <!-- User Authentication -->
            @guest  
            <div class="ms_auth_wrap">
                <a href="{{url('login-user')}}" class="login-btn">Login</a>
            </div>
            @else
            <div class="ms_user_wrap">
                <div class="ms_user_dropdown">
                    <button class="user-toggle">
                        <span class="user-name d-none d-sm-inline">{{Auth::user()->name}}</span>
                        <i class="fa-solid fa-user d-sm-none"></i>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <ul class="user-dropdown-menu">
                        <li>
                            <a href="{{ route('front.order.index') }}">
                                <i class="fa-solid fa-list"></i>
                                <span>All Orders</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('logout')}}">
                                <i class="fa-solid fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endguest

            <!-- Mobile Menu Toggle -->
            <div class="ms_cmenu_toggle ms_menu_toggle d-block d-md-none">
                <button class="custom-toggle-btn">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Base Header Styles */
.ms_header {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    padding: 12px 0;
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.ms_header_inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
    gap: 20px;
}

/* Logo Section */
.ms_header_logo {
    flex-shrink: 0;
}

.logo-img {
    height: 40px;
    width: auto;
}

/* Search Section */
.ms_header_search {
    flex: 1;
    max-width: 500px;
    margin: 0 20px;
}

.search-form {
    width: 100%;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    background: rgba(255,255,255,0.1);
    border-radius: 25px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.search-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 12px 20px;
    color: #fff;
    font-size: 14px;
}

.search-input::placeholder {
    color: rgba(255,255,255,0.7);
}

.search-input:focus {
    outline: none;
    background: rgba(255,255,255,0.05);
}

.search-btn {
    background: #ff4d4f;
    border: none;
    padding: 12px 20px;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: #e04345;
}

/* Actions Section */
.ms_header_actions {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-shrink: 0;
}

/* Cart Styles */
.cart-link {
    position: relative;
    background: rgba(255,255,255,0.1);
    padding: 10px 12px;
    border-radius: 10px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.cart-link:hover {
    background: rgba(255,255,255,0.2);
    color: #fff;
    text-decoration: none;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff4d4f;
    color: #fff;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
}

/* Auth Styles */
.login-btn {
    background: #ff4d4f;
    color: #fff;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.login-btn:hover {
    background: #e04345;
    color: #fff;
    text-decoration: none;
    transform: translateY(-2px);
}

/* User Dropdown */
.ms_user_dropdown {
    position: relative;
}

.user-toggle {
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
    padding: 10px 15px;
    border-radius: 25px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.user-toggle:hover {
    background: rgba(255,255,255,0.2);
}

.user-dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    min-width: 180px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    list-style: none;
    padding: 0;
    margin: 8px 0 0 0;
    z-index: 1001;
}

.ms_user_dropdown:hover .user-dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.user-dropdown-menu li {
    margin: 0;
}

.user-dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.user-dropdown-menu a:hover {
    background: #f8f9fa;
    color: #ff4d4f;
    text-decoration: none;
}

.user-dropdown-menu li:first-child a {
    border-radius: 10px 10px 0 0;
}

.user-dropdown-menu li:last-child a {
    border-radius: 0 0 10px 10px;
}

/* Mobile Menu Toggle - Your Original Style */
.ms_cmenu_toggle {
    display: flex;
    justify-content: flex-end;
    width: auto;
    padding-right: 0;
}

.custom-toggle-btn {
    background: rgba(255,255,255,0.1);
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    color: #fff;
    font-size: 18px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.custom-toggle-btn:hover {
    background: rgba(255,255,255,0.2);
}

.custom-toggle-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(255,77,79,0.3);
}

/* Offcanvas Mobile Menu */

/* Responsive Design */
@media (max-width: 768px) {
    .ms_header_inner {
        gap: 10px;
        padding: 0 10px;
    }
    
    .ms_header_search {
        margin: 0 10px;
        max-width: none;
    }
    
    .search-input {
        padding: 10px 15px;
        font-size: 13px;
    }
    
    .search-btn {
        padding: 10px 15px;
    }
    
    .ms_header_actions {
        gap: 10px;
    }
}

@media (max-width: 576px) {
    .ms_header_search {
        margin: 0 5px;
    }
    
    .search-input::placeholder {
        font-size: 12px;
    }
    
    .cart-link, .user-toggle {
        padding: 8px 10px;
    }
}
</style>