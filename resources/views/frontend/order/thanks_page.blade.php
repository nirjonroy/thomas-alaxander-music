@extends('frontend.app')
@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
@endpush
@section('content')

<div class="pt-32 lg:pt-48">

  <div class="mt-4 p-5   rounded text-center" style="margin-bottom: 5%; box-shadow: 10px 10px 5px rgb(79, 6, 6); background:#f86f1b ">
    <h1 style="font-size:40px; font-weight: bold">Thanks For order</h1>
    <p style="font-size:16px; font-weight: bold">Your Order Has Been Received </p>
    <p style="font-size:16px; font-weight: bold"> Our Sales Representative Will contact you, to ensure this order </p>
    @if(!empty($order_inv->order_id))
    <p> For Your Order. Invoice Number is : #{{$order_inv->order_id}} </p>
    @else


    @endif
    <a class="btn bg-dark" href="{{route('front.home')}}" style="color:white; font-weight:bold"> Back To Home  </a> <br>
    <a class="btn bg-dark" href="{{url('order')}}" style="color:white; font-weight: bold"> See all Orders  </a>
    @if(!empty($order_inv->order_phone))
    <a class="btn bg-dark" href="{{route('front.order-list',$order_inv->order_phone)}}" style="color:white"> See all Orders  </a>
    @else

    @endif
  </div>
</div>

@endsection
