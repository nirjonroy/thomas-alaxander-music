@extends('frontend.app')

@section('title', 'All Orders')

@push('css')
    <style>
        .ta-order-page {
            min-height: calc(100vh - 160px);
            padding: clamp(32px, 5vw, 70px) clamp(16px, 3vw, 34px);
            color: #f8f4e8;
            background:
                radial-gradient(circle at 80% 8%, rgba(244, 185, 72, 0.16), transparent 32%),
                radial-gradient(circle at 10% 86%, rgba(255, 77, 89, 0.12), transparent 30%),
                linear-gradient(135deg, #071726 0%, #0c1729 48%, #10152a 100%);
        }
        .ta-order-shell {
            width: min(100%, 1100px);
            margin: 0 auto;
        }
        .ta-order-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 24px;
            align-items: end;
            margin-bottom: 28px;
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
        .ta-order-btn:hover {
            color: #160f06;
            background: linear-gradient(135deg, #f4b948, #ff8b43);
            transform: translateY(-1px);
        }
        .ta-order-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
            font-size: clamp(22px, 3vw, 32px);
            line-height: 1;
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
        .ta-order-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ta-order-table th,
        .ta-order-table td {
            padding: 18px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            vertical-align: middle;
        }
        .ta-order-table th {
            color: rgba(248, 244, 232, 0.52);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .ta-order-table td {
            color: rgba(248, 244, 232, 0.86);
            font-weight: 700;
        }
        .ta-order-table tbody tr {
            transition: background 0.2s ease;
        }
        .ta-order-table tbody tr:hover {
            background: rgba(244, 185, 72, 0.06);
        }
        .ta-order-id {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            padding: 7px 12px;
            border-radius: 999px;
            color: #160f06;
            background: linear-gradient(135deg, #f4b948, #ff8b43);
            font-weight: 900;
        }
        .ta-order-amount {
            color: #59d69b;
        }
        .ta-order-status {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            color: #f4b948;
            background: rgba(244, 185, 72, 0.11);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .ta-order-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 14px;
            border-radius: 999px;
            color: #fff7e8;
            background: rgba(255, 255, 255, 0.08);
            font-size: 12px;
            font-weight: 900;
            text-decoration: none;
        }
        .ta-order-view:hover {
            color: #160f06;
            background: #f4b948;
        }
        .ta-order-empty {
            padding: 54px 22px;
            text-align: center;
            color: rgba(248, 244, 232, 0.72);
        }
        .ta-order-mobile-list {
            display: none;
        }
        @media (max-width: 991px) {
            .ta-order-hero {
                grid-template-columns: 1fr;
            }
            .ta-order-actions {
                justify-content: flex-start;
            }
            .ta-order-stats {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 767px) {
            .ta-order-page {
                padding: 28px 14px 110px;
            }
            .ta-order-table-wrap {
                display: none;
            }
            .ta-order-mobile-list {
                display: grid;
                gap: 12px;
                padding: 14px;
            }
            .ta-order-mobile-card {
                padding: 16px;
                border: 1px solid rgba(255, 255, 255, 0.09);
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.055);
            }
            .ta-order-mobile-top,
            .ta-order-mobile-meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }
            .ta-order-mobile-meta {
                margin: 14px 0;
                color: rgba(248, 244, 232, 0.7);
                font-size: 13px;
            }
            .ta-order-view {
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
        $orderCount = $orders->count();
        $totalSpent = $orders->sum('total_amount');
        $latestOrder = $orders->first();
    @endphp

    <main class="ta-order-page">
        <div class="ta-order-shell">
            <section class="ta-order-hero">
                <div>
                    <span class="ta-order-kicker">Customer Orders</span>
                    <h1 class="ta-order-title">All Orders</h1>
                    <p class="ta-order-subtitle">
                        Review your Thomas Alexander purchases, open order details, and access eligible music downloads after payment confirmation.
                    </p>
                </div>
                <div class="ta-order-actions">
                    <a href="{{ route('front.shop') }}" class="ta-order-btn">Continue Shopping</a>
                    <a href="{{ route('front.home.living-archive') }}" class="ta-order-btn">Living Archive</a>
                </div>
            </section>

            <section class="ta-order-stats" aria-label="Order summary">
                <div class="ta-order-stat">
                    <span>Total Orders</span>
                    <strong>{{ $orderCount }}</strong>
                </div>
                <div class="ta-order-stat">
                    <span>Total Spent</span>
                    <strong>${{ number_format($totalSpent, 2) }}</strong>
                </div>
                <div class="ta-order-stat">
                    <span>Latest Order</span>
                    <strong>{{ $latestOrder ? \Carbon\Carbon::parse($latestOrder->created_at)->format('M j') : '--' }}</strong>
                </div>
            </section>

            <section class="ta-order-panel">
                <div class="ta-order-panel-head">
                    <div>
                        <h2>Order History</h2>
                        <span>{{ $orderCount }} {{ \Illuminate\Support\Str::plural('record', $orderCount) }} found</span>
                    </div>
                </div>

                @if($orders->isNotEmpty())
                    <div class="ta-order-table-wrap">
                        <table class="ta-order-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><span class="ta-order-id">#{{ $order->order_id }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('j M Y / H:i:s') }}</td>
                                        <td><span class="ta-order-status">{{ $statusLabels[(int) $order->order_status] ?? 'Order' }}</span></td>
                                        <td class="ta-order-amount">${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('front.order.show', [$order->id]) }}" class="ta-order-view">View Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="ta-order-mobile-list">
                        @foreach($orders as $order)
                            <article class="ta-order-mobile-card">
                                <div class="ta-order-mobile-top">
                                    <span class="ta-order-id">#{{ $order->order_id }}</span>
                                    <span class="ta-order-status">{{ $statusLabels[(int) $order->order_status] ?? 'Order' }}</span>
                                </div>
                                <div class="ta-order-mobile-meta">
                                    <span>{{ \Carbon\Carbon::parse($order->created_at)->format('j M Y') }}</span>
                                    <strong class="ta-order-amount">${{ number_format($order->total_amount, 2) }}</strong>
                                </div>
                                <a href="{{ route('front.order.show', [$order->id]) }}" class="ta-order-view">View Details</a>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="ta-order-empty">
                        <h2>No orders yet</h2>
                        <p>Your purchases will appear here after checkout.</p>
                        <a href="{{ route('front.shop') }}" class="ta-order-btn">Browse Products</a>
                    </div>
                @endif
            </section>
        </div>
    </main>
@endsection
