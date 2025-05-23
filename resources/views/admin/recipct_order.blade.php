<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="style.css">
        <title>Receipt </title>
        <style type="text/css">
        	* {
    font-size: 12px;
    font-family: 'Times New Roman';
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
}

td.description,
th.description {
    width: 85px;
    max-width: 85px;
}

td.quantity,
th.quantity {
    width: 50px;
    max-width: 40px;
    word-break: break-all;
}

td.price,
th.price {
    width: 50px;
    max-width: 50px;
    word-break: break-all;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    width: 155px;
    max-width: 155px;
}

img {
    max-width: inherit;
    width: inherit;
}

@media print {
    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}



        </style>
    </head>
    <body>
        <div class="ticket">
            <img src="{{ asset($setting->logo) }}" alt="Logo" >
            <p class="centered">RECEIPT
                <br>Order Number :  #{{ $order->order_id }}
                <br>Name : {{ $order->orderAddress->shipping_name }}
                @if ($order->orderAddress->shipping_phone)
                {{ $order->orderAddress->shipping_phone }}<br>
                @endif
                {{ $order->orderAddress->shipping_address }},
                {{ $order->orderAddress->shipping_city.', '. $order->orderAddress->shipping_state.', '.$order->orderAddress->shipping_country }}<br>
            </p>
            <table>
                <thead>
                    <tr>
                        <th class="description">Name</th>
                        <th class="quantity">Qty</th>
                        <th class="">Rate</th>
                        <th class="">$$</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $subTotal = 0;
                $total_discount = 0;
                @endphp
                @foreach ($order->orderProducts as $index => $orderProduct)
                @php
                    $variantPrice = 0;
                    $totalVariant = $orderProduct->orderProductVariants->count();
                @endphp
                    <tr>
                        <td class="description">
                            {{ $orderProduct->product_name }},
                            
                            <br>
                            
                       
                       
                            
                        </td>
                        <td class="quantity">{{ $orderProduct->qty }}</td>
                        <td class="price">{{ $orderProduct->unit_price }}</td>

                        @php
                        $total = ($orderProduct->unit_price * $orderProduct->qty);
                        $total_discount += $orderProduct->total_discount;

                    @endphp
                    <td class="">{{ $setting->currency_icon }}{{ $total }}</td>
                    </tr>
                    @php
                    $totalVariant = 0;
                @endphp
            @endforeach
            @php
            $sub_total = $order->total_amount;
            $sub_total = $sub_total - $order->shipping_cost;
            $sub_total = $sub_total + $order->coupon_coast;

        @endphp
<tr>
    <td colspan="" style="text-align: right">
        Subtotal : ${{ $order->sub_total }}
    </td>
</tr>    
<tr>
{{-- <td colspan="" style="text-align: right">
        Tax : ${{ $order->tax }}
    </td> --}}
</tr>
<tr>    
    <td colspan="" style="text-align: right">
        Total : {{ $setting->currency_icon }}{{ round($sub_total, 2) }}
    </td>

</tr>


                </tbody>
            </table>
            <p class="centered">
                <br>Thomas Alexander Music</p>
        </div>
        <button id="btnPrint" class="hidden-print">Print</button>
        <script src="script.js"></script>

        <script type="text/javascript">
        	const $btnPrint = document.querySelector("#btnPrint");
$btnPrint.addEventListener("click", () => {
    window.print();
});
        </script>
    </body>
</html>
