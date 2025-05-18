


@extends('frontend.app')
@section('title', 'Shop Product List')
@push('css')
    {{-- <link rel="stylesheet" href="{{ asset('frontend/silck/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/silck/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/food.css') }}"> --}}
@endpush
@section('content')
<d class="ms_content_wrapper padder_top8">

    <div class="ms_index_wrapper common_pages_space">
<div class="categoryHeader">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page" style="background:lightblue;">
                
            </li>
        </ol>
    </nav>
</div>

<style>
    .form-check-label {
        color: black !important;
        font-weight: bold;
    }
</style>
<div class="container-fluid">
<div class="main-wrapper">
    <div class="overlay-sidebar"></div>
    <div class="category-page col-lg-12 col-12 p-0 m-auto mt-2 mb-2">
        <div class="row">
       
            <section class="products-box col-lg-12 col-md-12 custom-padding-10" style="padding: 10px">
                <div class="bg-white p-3 pt-1">
                    <div class="product-bar">
                        <div class="btn-list">
               
                            <!--<button class="filter-btn btn d-block d-md-block d-lg-none">-->
                            <!--   <i class="fas fa-filter"></i> Filter-->
                            <!--</button>-->
                            <!--<button class="compare-btn btn d-none d-md-none d-lg-block">-->
                            <!--   <i class="fas fa-repeat"></i> Product Compare-->
                            <!--</button>-->
                        </div>
                        <div class="filter-sort d-flex align-items-center">
                            <div class="d-flex align-items-center me-2">
                              <!--<label for="" class="form-label me-2 mb-0" style="white-space: nowrap;">Sort By: </label>-->
                              <!--<select name="" id="" class="form-select shadow-none">-->
                              <!--  <option value="">Select One</option>-->
                              <!--  <option value="">High to Low</option>-->
                              <!--</select>-->
                            </div>
                            <div class="d-lg-flex d-md-none d-none align-items-center">
                              <!--<label for="" class="form-label me-2 mb-0">Show:</label>-->
                              <!--<select name="" id="" class="form-select shadow-none">-->
                              <!--  <option value="">10</option>-->
                              <!--  <option value="">20</option>-->
                              <!--</select>-->
                            </div>
                        </div>
                    </div>
                    <div class="product-box py-1 bg-muted row">
                        @foreach ($blogs as $blog)


                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 custom-padding-10">
                            <div class="product-item">
                                <div class="product_thumb">
                                    
                                    <a class="secondary_img" href="{{ route('front.blog_details', [$blog->slug]) }}"><img src="{{ asset($blog->image) }}" alt="" style="height: 240px; width: 220px; object-fit: cover;"></a>
                                   
                                </div>
                                <div class="product_content ">
                                    <h4 class="ps-1" style="height: 40px;">
                                         
                                        <a href="{{ route('front.blog_details', [$blog->slug]) }}" class="font-16" style="font-size: 14px">{!! Str::limit($blog->title, 90, ' ...') !!}</a>
                                    </h4>
                                   
                                    <div class="price_box ps-1" style="padding-bottom: 0px;">
                                        
                                        <span class="current_price">{{ date('m/d/Y', strtotime($blog->created_at)) }}</span>

                                       

                                        

                                    </div>
                                    <div class="rounded-0 bg-muted p-2 d-flex justify-content-between">

                        
                      	<a href="{{ route('front.blog_details', [$blog->slug]) }}"
                                           style="color: white; font-size: 15px;padding-top: 4%;background: red;border: solid;width: 100%;"
                                           class="btn btn-sm btn-warning semi "
                                          >
                                       <i class="fas fa-shopping-cart"></i> &nbsp;  Details
                                        </a>
                      
                      	
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    
                                    @endforeach


                    </div>
                </div>
            </section>
        <!-- Products -->
        </div>
    </div>

</div>
</div>

</div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function () {
    $('.buy-now').on('click', function (e) {
        e.preventDefault();
        
        var productId = $(this).attr('href').split('/').pop();
        var proQty = 1;
        var addToCartUrl = $(this).data('url');
        var checkoutUrl = "{{ route('front.cart.index') }}";
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include CSRF token in AJAX request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        // Perform AJAX request to add the product to the cart
        $.post(addToCartUrl, { id: productId, quantity: proQty }, function (response) {
            // Redirect to checkout page after adding to cart
           window.location.href = "{{ route('front.checkout.index') }}";
        });
    });
});
</script>




@endpush

