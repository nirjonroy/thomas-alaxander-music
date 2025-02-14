@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')

<section class="bg-white pt-32 lg:pt-40">

    <div class="2xl:max-w-7xl 2xl:mx-auto lg:py-16  md:py-12 md:px-6 py-9 px-4">
        <div class="flex flex-col lg:flex-row justify-between gap-8">
            <div class="w-full lg:w-1/2 flex flex-col justify-center">
                <h1 class="text-3xl lg:text-4xl font-bold leading-9 text-gray-800 pb-4">About Us</h1>
                <p class="font-normal text-base leading-6 text-gray-600">Step into the soulful world of MLK Soulfood, where we celebrate the rich tapestry of African-American culinary heritage. At MLK Soulfood, we honor our roots with every dish we serve, infusing each bite with history, culture, and love.

                  Indulge in the comforting embrace of traditional soul food classics, from golden-fried chicken to velvety macaroni and cheese. Savor the soul-warming flavors of slow-cooked collard greens, tender barbecue ribs, and buttery cornbread straight from the oven.

                  Our menu is a celebration of community and connection, offering a soulful feast for every occasion. Whether you're sharing a meal with loved ones or savoring a solo dining experience, every bite at MLK Soulfood is a tribute to the resilience and spirit of the African-American culinary tradition.

                  Join us as we journey through the heart and soul of soul food, where every dish tells a story and every meal is a celebration of heritage. Experience the warmth, flavor, and soul of MLK Soulfood – where every bite is a taste of history.

                    </p>
            </div>
            <div class="w-full  lg:w-1/2">
                <img class="w-full h-full rounded-lg" src="https://t3.ftcdn.net/jpg/03/24/73/92/360_F_324739203_keeq8udvv0P2h1MLYJ0GLSlTBagoXS48.jpg" alt="A group of People" />
            </div>
        </div>

    </div>
</section>

@endsection
