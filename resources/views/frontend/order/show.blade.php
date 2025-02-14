@extends('frontend.app')
@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/checkout.css') }}">
@endpush
@section('content')

<div class="max-w-7xl mx-auto pt-32 lg:pt-36 py-8">
    <h2 class="text-center text-2xl font-bold mb-4">Order Details</h2>

    <p class="mb-4 ml-4 xl:ml-0"><strong>Order Number:</strong> {{ $order->order_id }}</p>

    <div class="relative overflow-x-auto shadow-md rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400 text-xs uppercase">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Product Image
                    </th>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Product Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Product Quantity
                    </th>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Unit Price
                    </th>
                    <th scope="col" class="px-6 py-3 text-left font-medium">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @php
                    $totalAmount = 0;
                @endphp
                @forelse($order->orderProducts as $item)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">
                            <img src="{{ asset('uploads/custom-images2/'.$item->product->thumb_image) }}" alt="Product Image" class="h-20 w-20 object-cover rounded-md">
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                            {{ $item->product->name }},
                            <br>{{$item->variation}}
                            <br>{{$item->flavour}}
                           <br> {{$item->toping}}
                           <br> {{$item->dip}}
                           <br> {{$item->protin}}
                           <br> {{$item->cheese}}
                           <br> {{$item->vaggi}}
                           <br> {{$item->sauce}}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->qty }}
                        </td>
                        <td class="px-6 py-4">
                            ${{ number_format($item->unit_price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            ${{ number_format($item->unit_price * $item->qty, 2) }}
                        </td>
                    </tr>
                    @php
                        $totalAmount += ($item->unit_price * $item->qty);
                    @endphp
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-red-600 font-bold">
                            No products are available!
                        </td>
                    </tr>
                @endforelse
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <td colspan="4" class="px-6 py-4 text-right font-bold">
                        Total Amount:
                    </td>
                    <td class="px-6 py-4 text-green-600 font-bold">
                        ${{ number_format($totalAmount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
