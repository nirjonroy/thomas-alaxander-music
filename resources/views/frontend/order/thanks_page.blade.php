@extends('frontend.app')
@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
@endpush
@section('content')
<div class="ms_content_wrapper padder_top8">
  <style>
    .form-control {
        background-color: aliceblue !important;
    }
</style>

  <div class="ms_index_wrapper common_pages_space">

    <div class="container mt-5 pt-5">
      <div class="mt-4 p-5 rounded text-center" style="margin-bottom: 5%; box-shadow: 10px 10px 5px rgb(79, 6, 6); background:#f86f1b;">
        <h1 style="font-size: 40px; font-weight: bold;">Thanks For Order</h1>
        <p style="font-size: 16px; font-weight: bold;">Your Order Has Been Received</p>
        <p style="font-size: 16px; font-weight: bold;">Our Sales Representative will contact you to ensure this order</p>
    
        @if(!empty($order_inv->order_id))
          <p>For Your Order. Invoice Number is: <strong>#{{$order_inv->order_id}}</strong></p>
        @endif
    
        <a class="btn btn-dark mt-2" href="{{ route('front.home') }}" style="    font-weight: bold;
    width: 140px;
    height: 25px;
    font-size: 14px;">Back To Home</a><br>
    
        <a class="btn btn-dark mt-2" href="{{ url('order') }}" style="    font-weight: bold;
    width: 140px;
    height: 25px;
    font-size: 14px;">See all Orders</a>
    
        @if(!empty($order_inv->order_phone))
          <a class="btn btn-dark mt-2" href="{{ route('front.order-list', $order_inv->order_phone) }}">See all Orders</a>
        @endif
      </div>
    </div>
    

  </div>
  </div>
@endsection
