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
            <label for="email" class="form-label">Email </label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email " required>
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
        <button type="submit" class="btn btn-success btn-lg" style="width: 100%; height: 50px; font-size: 14px; font-weight:bold; margin: 5px;">Login Now</button>
        <div class="w-full text-center mt-4">
            <a class=""  href="{{url('register-user')}}" style="color: red; background: black;">Are you new here? Please Register First !!!</a>
        </div>
    </form>

<div class="container">


    <div class="ms_index_wrapper common_pages_space">

    </div>
</div>
@endsection

@push('js')
@endpush
