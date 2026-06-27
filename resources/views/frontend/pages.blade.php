@extends('frontend.app')
@section('title')
    {{$customPage->page_name}}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/cart.css') }}">
@endpush

@section('content')
    @php
        $closingIdentitySlugs = [
            'identity',
            'five-feathers-lineage-society',
        ];
        $closingIdentityPageKey = $customPage ? \Illuminate\Support\Str::slug($customPage->page_name) : '';
        $showsClosingIdentityBar = $customPage && (in_array($customPage->slug, $closingIdentitySlugs, true) || in_array($closingIdentityPageKey, $closingIdentitySlugs, true));
    @endphp
    @if($showsClosingIdentityBar)
        <style>
            .custom-page-archive-return {
                padding: 0 24px 24px;
                text-align: center;
            }
            .custom-page-archive-return-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 48px;
                padding: 12px 22px;
                border-radius: 999px;
                border: 1px solid rgba(201, 162, 39, 0.42);
                background: rgba(10, 10, 12, 0.72);
                color: #f6edd0;
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                box-shadow: 0 14px 30px rgba(0, 0, 0, 0.24);
            }
            .custom-page-archive-return-link:hover {
                color: #17120a;
                background: linear-gradient(135deg, #c9a227, #f3d67b);
                border-color: rgba(201, 162, 39, 0.7);
            }
            @media (max-width: 576px) {
                .custom-page-archive-return {
                    padding: 0 14px 20px;
                }
                .custom-page-archive-return-link {
                    width: 100%;
                    padding: 12px 16px;
                    font-size: 12px;
                }
            }
        </style>
    @endif

    <div class="ms_content_wrapper padder_top8">

        <div class="ms_index_wrapper common_pages_space">
            <div class="container" style="background: white; padding: 5px;">
                <h1>{{$customPage->page_name}}</h1>
                <p style="text-align:justify">{!!$customPage->description!!}</p>
            </div>

        </div>
    </div>

    @if($showsClosingIdentityBar)
        <div class="custom-page-archive-return">
            <a class="custom-page-archive-return-link" href="{{ route('front.home.living-archive') }}">Return to the Living Archive</a>
        </div>
        @include('frontend.partials.closing_identity_bar')
    @endif
@endsection
