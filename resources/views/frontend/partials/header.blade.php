


    <!--Navbar-->
    <header id="navbar">
        <div
          class="shadow-md rounded-2xl absolute top-4 left-1/2 transform -translate-x-1/2 z-50 bg-orange-100 w-full px-4 lg:max-w-7xl"
        >
          <nav
            id="navbar"
            class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center"
          >
            <a class="text-3xl font-bold leading-none" href="{{route('front.home')}}">
              <img src="{{ asset(siteInfo()->logo) }}" alt="logo" class="w-12 lg:w-20" />
            </a>
            
            <ul
              class="hidden lg:flex lg:mx-auto lg:items-center lg:w-auto lg:space-x-6 relative"
            >
              <div class="dropdown inline-block">
                <a
                  id="menu-button"
                  class="text-lg px-4 py-3 hover:text-white hover:bg-gradient-to-r from-orange-500 to-red-500 rounded-md font-bold dropbtn"
                  href="{{route('front.category.all')}}"
                >
                  Menu <i class="fas fa-chevron-down ml-2"></i>
                </a>

               <div
  id="dropdown-content"
  class="dropdown-content hidden mt-1 border-b-2 rounded-md shadow-lg"
  style="max-height: 700px; overflow-y: auto;"
>
  @forelse(categories() as $key => $item)
  <a
    href="{{ route('front.shop', [
                                  'slug'=> $item->slug
                                ] ) }}"
    class="border-b border-orange-300 flex items-center px-4 py-2 hover:bg-[#F37016]"
  >
    <img
      src="{{ asset('uploads/custom-images2/'.$item->image) }}"
      alt="{{$item->name}}"
      class="w-10 h-10 rounded-full"
    />
    <p class="font-bold ml-4">{{$item->name}}</p>
  </a>
  @endforeach
</div>

              </div>
              <li class="text-gray-300">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  stroke="currentColor"
                  class="w-4 h-4 current-fill"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                  />
                </svg>
              </li>
              <li>
                <a
                  class="text-lg px-4 py-3 hover:text-white hover:bg-gradient-to-r from-orange-500 to-red-500 rounded-md font-bold"
                  href="{{route('front.home.about')}}"
                  >About</a
                >
              </li>
              <li class="text-gray-300">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  stroke="currentColor"
                  class="w-4 h-4 current-fill"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                  />
                </svg>
              </li>
              <li>
                <a
                  class="text-lg px-4 py-3 hover:text-white hover:bg-gradient-to-r from-orange-500 to-red-500 rounded-md font-bold"
                  href="{{route('front.contact_us')}}"
                  >Contact</a
                >
              </li>
              <li class="text-gray-300">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  stroke="currentColor"
                  class="w-4 h-4 current-fill"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                  />
                </svg>
              </li>
              <li>
                <a
                  class="text-lg px-4 py-3 hover:text-white hover:bg-gradient-to-r from-orange-500 to-red-500 rounded-md font-bold"
                  href="{{route('front.blog')}}"
                  >Blog</a
                >
              </li>
            </ul>
            <a
              class="hidden lg:inline-block py-2 px-4 mr-4 bg-gradient-to-r from-orange-500 to-red-500 rounded-full hover:bg-[#100d20] border text-lg text-white font-bold transition duration-200"
              href="{{route('front.category.all')}}"
              >Order Now</a
            >
            
            <!--new user-->
            <!--<div class="relative inline-block">-->
            <!--  <a-->
            <!--    href="#"-->
            <!--    class="relative lg:inline-block text-lg text-white font-bold transition duration-200"-->
            <!--  >-->
            <!--    <svg-->
            <!--      xmlns="http://www.w3.org/2000/svg"-->
            <!--      class="h-10 px-2 py-1 w-10 lg:w-12 lg:h-12 bg-[#FFC55A] text-black rounded-full inline"-->
            <!--      fill="none"-->
            <!--      viewBox="0 0 24 24"-->
            <!--      stroke="currentColor"-->
            <!--    >-->
            <!--      <path-->
            <!--        stroke-linecap="round"-->
            <!--        stroke-linejoin="round"-->
            <!--        stroke-width="2"-->
            <!--        d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M12 7a4 4 0 100-8 4 4 0 000 8zm0 2a9 9 0 00-9 9h18a9 9 0 00-9-9z"-->
            <!--      />-->
            <!--    </svg>-->
            <!--  </a>-->
              <!-- Dropdown Menu -->
            <!--  <div-->
            <!--    class="absolute right-0 hidden w-48 bg-white rounded-md shadow-lg z-20 group-hover:block"-->
            <!--  >-->
            <!--    @guest-->
            <!--    <a-->
            <!--      href="{{url('login-user')}}"-->
            <!--      class="block px-4 py-2 text-gray-800 border-b-2 border-orange-300 font-bold hover:bg-[#FFC55A] hover:text-black"-->
            <!--    >-->
            <!--      Log In-->
            <!--    </a>-->
            <!--    @else-->
            <!--    <a-->
            <!--      href="{{url('logout')}}"-->
            <!--      class="block px-4 py-2 text-gray-800 font-bold border-b-2 border-orange-300 hover:bg-[#FFC55A] hover:text-black"-->
            <!--    >-->
            <!--      Logout-->
            <!--    </a>-->
                
            <!--    <a-->
            <!--      href="{{url('order')}}"-->
            <!--      class="block px-4 py-2 text-gray-800 font-bold border-b-2 border-orange-300 hover:bg-[#FFC55A] hover:text-black"-->
            <!--    >-->
            <!--      order-->
            <!--    </a>-->
            <!--    @endif-->
            <!--  </div>-->
            <!--</div>-->
            <!--end user-->
            
            
           <!-- User Icon with Dropdown -->
<div class="relative inline-block group">
  <a
    href="#"
    class="relative lg:inline-block text-lg text-white font-bold transition duration-200"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      class="h-12 px-3 py-2 w-12 text-white bg-gradient-to-r from-orange-500 to-red-500 rounded-full inline"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M12 7a4 4 0 100-8 4 4 0 000 8zm0 2a9 9 0 00-9 9h18a9 9 0 00-9-9z"
      />
    </svg>
  </a>
  <!-- Dropdown Menu -->
  <div
    class="absolute left-0 hidden w-48 bg-white rounded-md shadow-lg z-20 group-hover:block"
  >
      @guest
    <a
      href="{{url('login-user')}}"
      class="block px-4 py-2 text-gray-800 border-b-2 border-orange-300 font-bold bg-white hover:bg-gradient-to-r from-orange-500 to-red-500 hover:text-white"
    >
      Log In
    </a>
    <a
      href="{{url('login-user')}}"
      class="block px-4 py-2 text-gray-800 font-bold border-b-2 border-orange-300 bg-white hover:bg-gradient-to-r from-orange-500 to-red-500 hover:text-white"
    >
      Create Account
    </a>
    @else
         <a
      href="{{url('logout')}}"
      class="block px-4 py-2 text-gray-800 border-b-2 border-orange-300 font-bold bg-white hover:bg-gradient-to-r from-orange-500 to-red-500 hover:text-white"
    >
      Log Out
    </a>
    <a
      href="{{url('order')}}"
      class="block px-4 py-2 text-gray-800 font-bold border-b-2 border-orange-300 bg-white hover:bg-gradient-to-r from-orange-500 to-red-500 hover:text-white"
    >
      order
    </a>
    @endif
  </div>
</div>

          
            <a
              href="{{route('front.cart.index')}}"
              class="relative lg:inline-block border text-lg text-white font-bold transition duration-200 ml-0 lg:ml-4"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-12 px-2 py-2 w-12 text-white bg-gradient-to-r from-orange-500 to-red-500 rounded-full inline"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l1.3-5H6.3L7 13zM7 13L6 21h12l-1-8M3 3l3.6 2H17m-3 8h5l1-5H7L6 13h11-5m0-8H7"
                />
              </svg>
              <!-- Cart Count Badge -->
              <span
                class="absolute top-0 right-0 text-red-500 -mt-2 -mr-2 rounded-full bg-white text-sm font-extrabold py-1 px-2"
              >
              {{ totalCartItems() }} {{ totalCartItems() > 1 ? '' : '' }}
              </span>
            </a>
            <div class="lg:hidden">
              <button
                id="navbar-burger"
                class="navbar-burger flex items-center text-white rounded-md bg-gradient-to-r from-orange-500 to-red-500 rounded-md p-3"
              >
                <svg
                  class="block h-4 w-4 fill-current"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <title>Services</title>
                  <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
              </button>
            </div>
          </nav>
        </div>
        <div class="navbar-menu relative z-50 hidden">
          <a href="cart.html">
            <span class="relative">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="30px"
                height="30px"
                class="cursor-pointer fill-[#333] inline"
                viewBox="0 0 512 512"
              >
                <path
                  d="M164.96 300.004h.024c.02 0 .04-.004.059-.004H437a15.003 15.003 0 0 0 14.422-10.879l60-210a15.003 15.003 0 0 0-2.445-13.152A15.006 15.006 0 0 0 497 60H130.367l-10.722-48.254A15.003 15.003 0 0 0 105 0H15C6.715 0 0 6.715 0 15s6.715 15 15 15h77.969c1.898 8.55 51.312 230.918 54.156 243.71C131.184 280.64 120 296.536 120 315c0 24.812 20.188 45 45 45h272c8.285 0 15-6.715 15-15s-6.715-15-15-15H165c-8.27 0-15-6.73-15-15 0-8.258 6.707-14.977 14.96-14.996zM477.114 90l-51.43 180H177.032l-40-180zM150 405c0 24.813 20.188 45 45 45s45-20.188 45-45-20.188-45-45-45-45 20.188-45 45zm45-15c8.27 0 15 6.73 15 15s-6.73 15-15 15-15-6.73-15-15 6.73-15 15-15zm167 15c0 24.813 20.188 45 45 45s45-20.188 45-45-20.188-45-45-45-45 20.188-45 45zm45-15c8.27 0 15 6.73 15 15s-6.73 15-15 15-15-6.73-15-15 6.73-15 15-15zm0 0"
                  data-original="#000000"
                ></path>
              </svg>
              <span
                class="absolute left-auto ml-0 -top-2 rounded-full bg-gradient-to-r from-orange-500 to-red-500 px-1 py-0 text-xs text-white font-bold"
                >2</span
              >
            </span>
          </a>

         <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>
<nav class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-6 px-6 bg-[#100d20] border-r overflow-y-auto">
    <div class="flex items-center mb-8">
        <a class="mr-auto text-3xl font-bold leading-none" href="{{ route('front.home') }}">
            <img src="{{ asset(siteInfo()->logo) }}" class="w-32" />
        </a>
        <button class="navbar-close">
            <svg class="h-6 w-6 text-white cursor-pointer hover:text-[#F37016]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div>
        <ul>
            <div class="dropdown">
                <a id="menu-button" class="block p-4 text-lg font-bold text-white hover:bg-[#100d20] rounded-md hover:text-white rounded" href="#" onclick="toggleDropdown(event)">
                    Menu <i class="fas fa-chevron-down ml-2"></i>
                </a>
                <div id="dropdown-content"  style="max-height: 500px; overflow-y: auto;" class="dropdown-content hidden">
                    @forelse(categories() as $key => $item)
                    <a href="{{ route('front.shop', ['slug'=> $item->slug]) }}" class="border-b border-orange-300 flex items-center hover:bg-[#F37016]">
                        <img src="{{ asset('uploads/custom-images2/'.$item->image) }}" alt="{{$item->name}}" class="w-10 h-10 rounded-full" />
                        <p class="font-bold text-sm ml-4">{{$item->name}}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            <li class="mb-1">
                <a class="block p-4 text-lg font-bold text-white hover:bg-[#100d20] rounded-md hover:text-white rounded" href="{{ route('front.home.about') }}">About</a>
            </li>
            <li class="mb-1">
                <a class="block p-4 text-lg font-bold text-white hover:bg-[#100d20] rounded-md hover:text-white rounded" href="#">Contact</a>
            </li>
            <li class="mb-1">
                <a class="block p-4 text-lg font-bold text-white hover:bg-[#100d20] rounded-md hover:text-white rounded" href="#">Blog</a>
            </li>
        </ul>
    </div>
    <div class="mt-auto">
        <div class="pt-6">
            <a class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold text-white bg-[#100d20] rounded-md hover:bg-[#100d20] rounded-md hover:text-white rounded-xl" href="{{ route('front.category.all') }}">Online Orders</a>
        </div>
        <p class="my-4 text-xs text-center text-white">
            <span>Copyright © Blacktech Consultancy</span>
        </p>
    </div>
</nav>

<script>
function toggleDropdown(event) {
    event.preventDefault(); // Prevent the default anchor click behavior
    const dropdownContent = document.getElementById("dropdown-content");
    dropdownContent.classList.toggle("hidden"); // Toggle the visibility of the dropdown
}
</script>

        </div>
      </header>
      <!-- End Navbar -->

