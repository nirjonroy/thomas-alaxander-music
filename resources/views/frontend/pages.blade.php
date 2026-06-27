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
            'identity-page',
            'about-thomas',
            'about-thomas-alexander',
            'five-feathers-lineage-society',
            'five-feathers',
            'lineage-society',
        ];
        $closingIdentityPageKey = $customPage ? \Illuminate\Support\Str::slug($customPage->page_name) : '';
    @endphp
    <div class="ms_content_wrapper padder_top8">

        <div class="ms_index_wrapper common_pages_space">
            <div class="container" style="background: white; padding: 5px;">
                <h1>{{$customPage->page_name}}</h1>
                <p style="text-align:justify">{!!$customPage->description!!}</p>
            </div>

        </div>
    </div>

    @if($customPage && (in_array($customPage->slug, $closingIdentitySlugs, true) || in_array($closingIdentityPageKey, $closingIdentitySlugs, true)))
        @include('frontend.partials.closing_identity_bar')
    @endif
@endsection
