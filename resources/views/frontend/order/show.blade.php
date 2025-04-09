@extends('frontend.app')
@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/checkout.css') }}">
@endpush
@section('content')
<div class="ms_index_wrapper common_pages_space">
    <div class="container pt-5">
        <div class="container pt-5">
            <h2 class="text-center mb-4 fw-bold">Order Details</h2>
        
            <p class="mb-4 ms-3 ms-xl-0"><strong>Order Number:</strong> # {{ $order->order_id }}</p>
        
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th scope="col">Product Image</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Product Quantity</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAmount = 0;
                        @endphp
                        @forelse($order->orderProducts as $item)
                            <tr style="    background: #316ca1;
    font-weight: bolder;">
                                <td>
                                    <img src="{{ asset('uploads/custom-images2/'.$item->product->thumb_image) }}" alt="Product Image" class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover;">
                                </td>
                                <td class="fw-semibold">
                                    {{ $item->product->name }}<br>
                                    <audio controls>
                                        <source src="{{ asset($item->product->music) }}" type="audio/mpeg">
                                        </audio>
                                </td>
                                <td>{{ $item->qty }}</td>
                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                <td>${{ number_format($item->unit_price * $item->qty, 2) }}</td>
                            </tr>
                            @php
                                $totalAmount += ($item->unit_price * $item->qty);
                            @endphp
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-danger fw-bold">
                                    No products are available!
                                </td>
                            </tr>
                        @endforelse
                        <tr class="table-light fw-bold">
                            <td colspan="4" class="text-end">Total Amount:</td>
                            <td class="text-success">${{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        

    </div>
</div>
@endsection
