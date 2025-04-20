@extends('frontend.app')
@section('title', 'Home')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="ms_content_wrapper padder_top8">

  <div class="ms_index_wrapper common_pages_space">
<section class="">
  <div class="container-fluid pt-5 pt-lg-5">

    <h2 class="text-center text-white bg-warning py-3 mb-4 rounded fw-bold fs-2">
      Blogs
    </h2>

    <div class="row g-4">
      @foreach ($blogs as $blog)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow mx-2">
          <div class="position-relative">
            <a href="{{ route('front.blog_details', [$blog->slug]) }}">
              <img
                src="{{ asset($blog->image) }}"
                class="card-img-top"
                alt="Blog Image"
                style="height: 240px; object-fit: cover;"
              />
              <div class="position-absolute top-0 bottom-0 start-0 end-0 bg-secondary opacity-25 transition-opacity"></div>
            </a>
          </div>
          <div class="card-body d-flex flex-column">
            <a
              href="{{ route('front.blog_details', [$blog->slug]) }}"
              class="card-title h5 text-decoration-none text-dark fw-semibold mb-2 hover-text-warning"
            >
              {!! Str::limit($blog->title, 90, ' ...') !!}
            </a>
            <p class="card-text text-muted small">
              {!! Str::limit($blog->description, 100, ' ...') !!}
            </p>
          </div>
          <div class="card-footer bg-light d-flex justify-content-between align-items-center small">
            <div class="d-flex align-items-center text-muted">
              <svg height="13" width="13" fill="currentColor" class="me-1" viewBox="0 0 512 512">
                <path
                  d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256 c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128 c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z"
                ></path>
              </svg>
              {{ date('m/d/Y', strtotime($blog->created_at)) }}
            </div>
            <div class="d-flex align-items-center text-muted">
              <svg height="16" viewBox="0 0 24 24" fill="none" class="me-1" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section>

  </div>
</div>
@endsection
