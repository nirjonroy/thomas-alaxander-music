@extends('frontend.app')
@section('title')
    {{$customPage->page_name}}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/cart.css') }}">
@endpush

@section('content')
    <div class="ms_content_wrapper padder_top8">

        <div class="ms_index_wrapper common_pages_space">
            <div class="container" style="background: white; padding: 5px;">
                <h1>{{$customPage->page_name}}</h1>
                <p style="text-align:justify">{!!$customPage->description!!}</p>
            </div>

        </div>
    </div>

@endsection