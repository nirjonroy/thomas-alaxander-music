@extends('frontend.app')
@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
@endpush
@section('content')
<div class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">
<div class="container pt-5">
    <h2 class="text-center mb-4 fw-bold">Order List</h2>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-uppercase small">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Total Amount</th>
                    <th scope="col">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr style="    background: #316ca1;
    font-weight: bolder;">
                        <td class="fw-semibold">{{ $order->order_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('j M Y / H:i:s') }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            
                            <a href="{{ route('front.order.show', [$order->id]) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right ms-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.146 12.354a.5.5 0 0 1 0-.708L12.793 9H1.5a.5.5 0 0 1 0-1h11.293l-2.647-2.646a.5.5 0 0 1 .708-.708l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708 0z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-danger fw-bold">No orders are available!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    </div>
</div>

@endsection
