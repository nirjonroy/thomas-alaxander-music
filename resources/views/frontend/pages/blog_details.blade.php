@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')

  <!--Single Blog Section-->
  <section>
    <!--Title-->
    <div class="text-center pt-32 lg:pt-48 pt-4">
     <p class="text-sm md:text-base text-orange-500 font-bold">
        {{date('m/d/Y', strtotime($blog->created_at))}} <span class="text-gray-900"></span> 
     </p>
     <h1 class="font-bold break-normal text-3xl md:text-5xl">
        {{$blog->title}}
     </h1>
   </div>
   
   <!--image-->
   <div
     class="w-full max-w-6xl mx-auto h-[400px] lg:h-[650px] bg-white bg-cover mt-8 rounded"
     style="
       background-image: url('{{asset($blog->image)}}');
     "
   ></div>
   
   <!--Container-->
   <div class="container max-w-5xl mx-auto -mt-32">
     <div class="mx-0 sm:mx-6">
       <div
         class="bg-white w-full p-8 md:p-12 text-xl md:text-2xl text-gray-800 leading-normal"
         style="font-family: Georgia, serif"
       >
         <!--Post Content-->
   
         <p class="py-6">
            {!! $blog->description !!}
         </p>
       </div>
     </div>
   </div>
   
        </section>
        <!--End Single Blog Section-->
@endsection
