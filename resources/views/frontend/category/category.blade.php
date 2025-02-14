@extends('frontend.app')
@section('title', 'Category List')
@section('content')

<main class="pt-24 lg:pt-36">
  <!-- Navigation Menu Bar -->
<div class="menu-header fixed w-full z-50">
    <div class="px-6 max-w-7xl mx-auto bg-orange-500 rounded-lg shadow-md py-4">
        <div class="flex justify-between items-center">
            <!-- Menu Title Added Here -->
            <div class="text-lg mr-4 font-bold text-white">
                Our Menu
            </div>
            <!-- Desktop Menu Links -->
            <div class="hidden md:flex gap-1 space-x-4">
                @foreach($categories as $category)
                    <a href="#{{ strtolower(str_replace(' ', '_', $category->name)) }}" class="menu-link text-white font-semibold text-[12px] hover:text-black">{{ Str::limit($category->name, 10) }}</a>
                @endforeach
            </div>
            <!-- Mobile Menu Toggle Button -->
            <div class="md:hidden">
                <button id="menu-toggle" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden bg-orange-500 rounded-lg md:hidden">
        @foreach($categories as $category)
            <a href="#{{ strtolower(str_replace(' ', '_', $category->name)) }}" class="menu-link block text-white text-center font-semibold hover:text-black text-lg py-2">{{ $category->name }}</a>
        @endforeach
    </div>
</div>


    <!-- Main Content -->
    @foreach($categories as $category)
        <section id="{{ strtolower(str_replace(' ', '_', $category->name)) }}" class="pt-12">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <h2 class="text-3xl font-semibold text-gray-800">{{ $category->name }}</h2>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($category->products as $product)
                     <a
          href="{{route('front.product.show', $product->id)}}"
          class="duration-300 ease-in-out hover:scale-105"
        >
                        <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            <div class="p-4 flex items-center">
                                <img src="{{asset('uploads/custom-images/'.$product->thumb_image)}}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-full mr-4" />
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h3>
                                    <p class="text-gray-600 mt-2">${{ $product->price }}</p>
                                </div>
                            </div>
                        </div>
        </a>                
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
</main>

<script>
    document
          .getElementById("menu-toggle")
          .addEventListener("click", function () {
            var menu = document.getElementById("mobile-menu");
            menu.classList.toggle("hidden");
          });

        document.querySelectorAll(".menu-link").forEach((anchor) => {
          anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1);
            const targetElement = document.getElementById(targetId);
            const offset = 150;

            // Check if mobile menu is already hidden before scrolling
            const mobileMenu = document.getElementById("mobile-menu");
            if (!mobileMenu.classList.contains("hidden")) {
              mobileMenu.classList.add("hidden");
            }

            window.scrollTo({
              top: targetElement.offsetTop - offset,
              behavior: "smooth",
            });
          });
        });
</script>

@endsection
