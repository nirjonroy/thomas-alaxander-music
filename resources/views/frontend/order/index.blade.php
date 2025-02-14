@extends('frontend.app')
@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
@endpush
@section('content')

<div class="max-w-7xl mx-auto pt-32 lg:pt-36 py-8">
    <h2 class="text-center text-2xl font-bold mb-6">Order List</h2>

    <div class="relative overflow-x-auto shadow-md rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400 text-xs uppercase">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Order ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Order Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Total Amount
                    </th>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        View
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse($orders as $order)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                            {{ $order->order_id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('j M Y / H:i:s') }}
                        </td>
                        <td class="px-6 py-4">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('front.order.show', [$order->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-xs font-bold leading-snug uppercase rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition">
                                View Details
                                <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 15.293a1 1 0 010-1.414L13.586 10 10.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-red-600 font-bold">
                            No orders are available!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
