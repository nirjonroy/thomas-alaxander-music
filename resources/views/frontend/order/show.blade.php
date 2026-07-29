@extends('frontend.app')

@section('title', 'Order Details')

@push('css')
    <style>
        .ta-order-page {
            min-height: calc(100vh - 160px);
            padding: clamp(32px, 5vw, 70px) clamp(16px, 3vw, 34px);
            color: #f8f4e8;
            background:
                radial-gradient(circle at 78% 10%, rgba(244, 185, 72, 0.16), transparent 32%),
                radial-gradient(circle at 12% 88%, rgba(255, 77, 89, 0.12), transparent 30%),
                linear-gradient(135deg, #071726 0%, #0c1729 48%, #10152a 100%);
        }
        .ta-order-shell {
            width: min(100%, 1120px);
            margin: 0 auto;
        }
        .ta-order-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 24px;
            align-items: end;
            margin-bottom: 24px;
        }
        .ta-order-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: #f4b948;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }
        .ta-order-kicker::before {
            content: "";
            width: 28px;
            height: 1px;
            background: currentColor;
        }
        .ta-order-title {
            margin: 0;
            color: #fff7e8;
            font-size: clamp(34px, 5vw, 58px);
            line-height: 1;
            font-weight: 800;
            letter-spacing: -0.04em;
        }
        .ta-order-subtitle {
            max-width: 650px;
            margin: 14px 0 0;
            color: rgba(248, 244, 232, 0.68);
            font-size: 15px;
            line-height: 1.7;
        }
        .ta-order-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .ta-order-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 11px 18px;
            border: 1px solid rgba(244, 185, 72, 0.32);
            border-radius: 999px;
            color: #fff7e8;
            background: rgba(255, 255, 255, 0.06);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
        }
        .ta-order-btn:hover,
        .ta-order-btn--primary {
            color: #160f06;
            background: linear-gradient(135deg, #f4b948, #ff8b43);
            border-color: transparent;
        }
        .ta-order-btn:hover {
            transform: translateY(-1px);
        }
        .ta-order-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }
        .ta-order-stat {
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.055);
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(14px);
        }
        .ta-order-stat span {
            display: block;
            margin-bottom: 8px;
            color: rgba(248, 244, 232, 0.58);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .ta-order-stat strong {
            color: #fff7e8;
            font-size: clamp(18px, 2.6vw, 28px);
            line-height: 1.1;
        }
        .ta-order-panel {
            overflow: hidden;
            border: 1px solid rgba(244, 185, 72, 0.18);
            border-radius: 24px;
            background: rgba(5, 13, 23, 0.74);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(16px);
        }
        .ta-order-panel-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .ta-order-panel-head h2 {
            margin: 0;
            color: #fff7e8;
            font-size: 18px;
            font-weight: 800;
        }
        .ta-order-panel-head span {
            color: rgba(248, 244, 232, 0.56);
            font-size: 13px;
        }
        .ta-order-items {
            display: grid;
            gap: 14px;
            padding: 18px;
        }
        .ta-order-item {
            display: grid;
            grid-template-columns: 96px minmax(0, 1fr) minmax(230px, auto);
            gap: 18px;
            align-items: center;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.065), rgba(255, 255, 255, 0.025)),
                rgba(255, 255, 255, 0.035);
        }
        .ta-order-cover {
            width: 96px;
            height: 96px;
            overflow: hidden;
            border: 1px solid rgba(244, 185, 72, 0.24);
            border-radius: 14px;
            background: #060b13;
        }
        .ta-order-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .ta-order-product h3 {
            margin: 0 0 8px;
            color: #fff7e8;
            font-size: clamp(18px, 2.5vw, 24px);
            font-weight: 900;
        }
        .ta-order-product-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: rgba(248, 244, 232, 0.68);
            font-size: 13px;
            font-weight: 700;
        }
        .ta-order-product-meta span {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
        }
        .ta-order-media {
            display: grid;
            gap: 10px;
            justify-items: end;
        }
        .ta-order-audio {
            width: min(100%, 260px);
            height: 38px;
        }
        .ta-order-download {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 10px 16px;
            border: 0;
            border-radius: 999px;
            color: #160f06;
            background: linear-gradient(135deg, #f4b948, #ff8b43);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            box-shadow: 0 12px 28px rgba(244, 185, 72, 0.18);
        }
        .ta-order-download:hover {
            color: #160f06;
            transform: translateY(-1px);
        }
        .ta-order-note {
            color: rgba(248, 244, 232, 0.58);
            font-size: 12px;
            text-align: right;
        }
        .ta-order-total {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            padding: 20px 22px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff7e8;
            font-size: 18px;
            font-weight: 900;
        }
        .ta-order-total strong {
            color: #59d69b;
        }
        .ta-order-empty {
            padding: 54px 22px;
            text-align: center;
            color: rgba(248, 244, 232, 0.72);
        }
        @media (max-width: 991px) {
            .ta-order-hero,
            .ta-order-item {
                grid-template-columns: 1fr;
            }
            .ta-order-actions {
                justify-content: flex-start;
            }
            .ta-order-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .ta-order-media {
                justify-items: stretch;
            }
            .ta-order-audio {
                width: 100%;
            }
            .ta-order-note {
                text-align: left;
            }
        }
        @media (max-width: 767px) {
            .ta-order-page {
                padding: 28px 14px 110px;
            }
            .ta-order-summary {
                grid-template-columns: 1fr;
            }
            .ta-order-panel-head,
            .ta-order-total {
                flex-direction: column;
                align-items: flex-start;
            }
            .ta-order-cover {
                width: 100%;
                height: auto;
                aspect-ratio: 1 / 1;
                max-width: 190px;
            }
            .ta-order-btn,
            .ta-order-download {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $statusLabels = [
            0 => 'Pending',
            1 => 'Processing',
            2 => 'Courier',
            3 => 'Completed',
            4 => 'Cancelled',
            5 => 'Paid',
            6 => 'Returned',
        ];
        $orderItems = $order->orderProducts;
        $totalAmount = $orderItems->sum(fn ($item) => $item->unit_price * $item->qty);
    @endphp

    <main class="ta-order-page">
        <div class="ta-order-shell">
            <section class="ta-order-hero">
                <div>
                    <span class="ta-order-kicker">Order Details</span>
                    <h1 class="ta-order-title">Order #{{ $order->order_id }}</h1>
                    <p class="ta-order-subtitle">
                        Review each purchased item. Music downloads appear automatically for purchased products that include a music file.
                    </p>
                </div>
                <div class="ta-order-actions">
                    <a href="{{ route('front.order.index') }}" class="ta-order-btn">Back to Orders</a>
                    <a href="{{ route('front.shop') }}" class="ta-order-btn ta-order-btn--primary">Continue Shopping</a>
                </div>
            </section>

            <section class="ta-order-summary" aria-label="Order summary">
                <div class="ta-order-stat">
                    <span>Order Date</span>
                    <strong>{{ \Carbon\Carbon::parse($order->created_at)->format('M j, Y') }}</strong>
                </div>
                <div class="ta-order-stat">
                    <span>Status</span>
                    <strong>{{ $statusLabels[(int) $order->order_status] ?? 'Order' }}</strong>
                </div>
                <div class="ta-order-stat">
                    <span>Items</span>
                    <strong>{{ $orderItems->sum('qty') }}</strong>
                </div>
                <div class="ta-order-stat">
                    <span>Total</span>
                    <strong>${{ number_format($totalAmount, 2) }}</strong>
                </div>
            </section>

            <section class="ta-order-panel">
                <div class="ta-order-panel-head">
                    <div>
                        <h2>Purchased Items</h2>
                        <span>{{ $orderItems->count() }} {{ \Illuminate\Support\Str::plural('product', $orderItems->count()) }}</span>
                    </div>
                </div>

                @if($orderItems->isNotEmpty())
                    <div class="ta-order-items">
                        @foreach($orderItems as $item)
                            @php
                                $product = $item->product;
                                $hasMusic = $product && filled($product->music);
                                $musicUrl = $hasMusic ? asset($product->music) : null;
                                $imageUrl = $product && $product->thumb_image
                                    ? asset('uploads/custom-images2/' . ltrim($product->thumb_image, '/'))
                                    : asset('frontend/assets/images/logo.png');
                            @endphp

                            <article class="ta-order-item">
                                <div class="ta-order-cover">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name ?? 'Product image' }}">
                                </div>

                                <div class="ta-order-product">
                                    <h3>{{ $product->name ?? 'Unavailable Product' }}</h3>
                                    <div class="ta-order-product-meta">
                                        <span>Quantity: {{ $item->qty }}</span>
                                        <span>Unit: ${{ number_format($item->unit_price, 2) }}</span>
                                        <span>Total: ${{ number_format($item->unit_price * $item->qty, 2) }}</span>
                                        @if($hasMusic)
                                            <span>Music Product</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ta-order-media">
                                    @if($hasMusic)
                                        <audio class="ta-order-audio" controls preload="none">
                                            <source src="{{ $musicUrl }}" type="audio/mpeg">
                                        </audio>
                                        <a class="ta-order-download" href="{{ $musicUrl }}" download>
                                            Download Music
                                        </a>
                                    @else
                                        <span class="ta-order-note">No music file attached to this product.</span>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="ta-order-total">
                        <span>Total Amount:</span>
                        <strong>${{ number_format($totalAmount, 2) }}</strong>
                    </div>
                @else
                    <div class="ta-order-empty">
                        <h2>No products found</h2>
                        <p>This order does not currently have product records attached.</p>
                    </div>
                @endif
            </section>
        </div>
    </main>
@endsection
