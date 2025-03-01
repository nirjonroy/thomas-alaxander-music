@extends('frontend.app')
@section('title', 'Home')

@push('css')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="ms_content_wrapper padder_top8">
    <!---Header--->
   
    <!---index page--->
    <div class="ms_index_wrapper common_pages_space">
<div class="log-register-main py-5 px-4">
    <form action="{{ url('login') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email or Phone Number</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email or Phone Number" required>
            @error('email')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
            @error('password')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary ">Login Now</button>
        <div class="w-full text-center mt-4">
            <a class="text-base font-bold mb-4 text-orange-400 hover:underline" data-bs-toggle="modal" href="#forget-pass">Are you new here? Please Register First !!!</a>
        </div>
    </form>

<div class="container">


    <div class="ms_index_wrapper common_pages_space">

    </div>
</div>
@endsection

@push('js')
@endpush
