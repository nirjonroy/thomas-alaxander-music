@extends('admin.master_layout')
@section('title')
    <title>{{__('admin.Products')}}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{__('admin.Edit Product')}}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                    <div class="breadcrumb-item">{{__('admin.Edit Product')}}</div>
                </div>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{__('admin.Products')}}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.product.update', $product->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                            <div>
                                                <img id="preview-img" class="admin-img"
                                                    src="{{ asset('uploads/custom-images/' . $product->thumb_image) }}"
                                                    alt="">
                                            </div>

                                        </div>

                                        <div class="form-group col-6">
                                            <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control-file" name="thumb_image"
                                                onchange="previewThumnailImage(event)">
                                        </div>

                                        <div class="form-group col-4">
        

                                            <audio controls autoplay>
                                                <source src="{{ asset( $product->music) }}" type="audio/mpeg">
                                                Your browser does not support the audio tag.
                                            </audio>
                                                                           <label>Upload Music<span class="text-danger">*</span></label>
                                            <input type="file" name="song" class="form-control-file">
                                        </div>
                                        <div class="form-group col-6">
                                            @foreach($product->gallery as $key => $pGImg)

                                                <!--<i class="fa fa-times" aria-hidden="true"></i>-->
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal"
                                                    class="btn btn-danger btn-sm" onclick="deleteData({{ $pGImg->id }})"><i
                                                        class="fa fa-times" aria-hidden="true"></i></a>

                                                <img src="{{asset($pGImg->image)}}" width="100px" height="100px">
                                            @endforeach

                                        </div>
                                        <br />
                                        {{-- <div class="form-group col-6">
                                            <label for="">Select Multiple images here </label>
                                            <input type="file" class="form-control-file" name="images[]" multiple>
                                        </div> --}}
                                        <input type="hidden" name="product_id" required value="{{ $product->id }}">




                                        <!--<div class="form-group col-12">-->
                                        <!--    <label>{{__('admin.Short Name')}} <span class="text-danger">*</span></label>-->
                                        <!--    <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ $product->short_name }}">-->
                                        <!--</div>-->

                                        <div class="form-group col-6">
                                            <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                            <input type="text" id="name" class="form-control" name="name"
                                                value="{{ $product->name }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label>Duration of Music <span class="text-danger">*</span></label>
                                            <input type="text" id="" class="form-control"  name="duration" value="{{ $product->duration }}">
                                        </div>
        
                                        <div class="form-group col-6">
                                            <label>Artist Name <span class="text-danger">*</span></label>
                                            <input type="text" id="" class="form-control"  name="artist_name" value="{{ $product->duration }}">
                                        </div>
        
                                        <div class="form-group col-4">
                                            <label>Music Type <span class="text-danger">*</span></label>
                                            <select name="download_type" class="form-control select2">
                                                <option value="">Select Download Type</option>
                                                <option value="free" {{ $product->download_type == 'free' ? 'selected' : '' }}>Free</option>
                                                <option value="paid" {{ $product->download_type == 'paid' ? 'selected' : '' }}>Paid</option>
                                            </select>
                                        </div>
                                        

                                        <div class="form-group col-6">
                                            <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                            <input type="text" id="slug" class="form-control" name="slug"
                                                value="{{ $product->slug }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>Vedio URL </label>
                                            <input type="text" id="slug" class="form-control" name="video_link"
                                                value="{{ $product->video_link }}">
                                        </div>

                                        <div class="form-group col-4">
                                            <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                            <select name="category" class="form-control select2" id="category">
                                                <option value="">{{__('admin.Select Category')}}</option>
                                                @foreach ($categories as $category)
                                                    <option {{ $product->category_id == $category->id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- <div class="form-group col-4">
                                            <label>{{__('admin.Sub Category')}}</label>
                                            <select name="sub_category" class="form-control select2" id="sub_category">
                                                <option value="">{{__('admin.Select Sub Category')}}</option>
                                                @if ($product->category_id != 0)
                                                @foreach ($subCategories as $subCategory)
                                                <option {{ $product->sub_category_id == $subCategory->id ? 'selected' : ''
                                                    }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group col-4">
                                            <label>{{__('admin.Child Category')}}</label>
                                            <select name="child_category" class="form-control select2" id="child_category">
                                                <option value="">{{__('admin.Select Child Category')}}</option>
                                                @if ($product->sub_category_id != 0)
                                                @foreach ($childCategories as $childCategory)
                                                <option {{ $product->child_category_id == $childCategory->id ? 'selected' :
                                                    '' }} value="{{ $childCategory->id }}">{{ $childCategory->name }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div> --}}

                                        {{-- <div class="form-group col-6">
                                            <label>{{__('admin.Brand')}}</label>
                                            <select name="brand" class="form-control select2" id="brand">
                                                <option value="">{{__('admin.Select Brand')}}</option>
                                                @foreach ($brands as $brand)
                                                <option {{ $product->brand_id == $brand->id ? 'selected' : '' }} value="{{
                                                    $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}

                                        <div class="form-group col-6">
                                            <label>{{__('admin.SKU')}} </label>
                                            <input type="text" class="form-control" name="sku" value="{{ $product->sku }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label>{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="price"
                                                value="{{ $product->price }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label>{{__('admin.Offer Price')}} </label>
                                            <input type="text" class="form-control" name="offer_price"
                                                value="{{ $product->offer_price }}">
                                        </div>


                                        <!--<div class="col-md-12">-->
                                        <!--  <div class="row">-->

                                        <!--          <div class="col-md-6">-->
                                        <!--                    <div class="form-group col-12">-->
                                        <!--                      <label>{{__('admin.Weight')}} <span class="text-danger">*</span></label>                                            -->

                                        <!--                      <input type="text" class="form-control" name="weight" value="{{ $product->weight }}">-->
                                        <!--                  </div>-->
                                        <!--          </div>-->
                                        <!--    <div class="col-md-6">-->

                                        <!--    <div class="form-group col-12" style="margin-bottom: 7px;">-->
                                        <!--      <label></label>-->
                                        <!-- <input type="text" class="form-control" name="weight" value="{{ old('weight') }}"> -->
                                        <!--  </div>-->

                                        <!--<select name="measure" class="form-control form-select shadow-none" id="">-->
                                        <!--              <option {{ $product->measure == 'Grm' ? 'selected' : '' }} value="Grm">Grm</option>-->
                                        <!--              <option {{ $product->measure == 'Ltr' ? 'selected' : '' }} value="Ltr">Ltr</option>-->

                                        <!--</select>-->
                                        <!--  </div>                                  -->

                                        <!--    </div>-->

                                        <!--</div>     -->



                                        <!--<div class="form-group col-12">-->
                                        <!--    <label>{{__('admin.Tag')}} <span class="text-danger">*</span></label>-->
                                        <!--   <input type="text" class="form-control tags" name="tags" value="{{ $product->tags }}">-->
                                        <!--</div>-->



                                        <!--<div class="form-group col-12">-->
                                        <!--    <label>{{__('admin.Short Description') }}</label>-->
                                        <!--    <textarea name="short_description" id="" cols="30" rows="10" class="summernote">{{ $product->short_description }}</textarea>-->
                                        <!--</div>-->

                                        <div class="form-group col-12">
                                            <label>{{__('admin.Long Description')}} <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="long_description" id="" cols="30" rows="10"
                                                class="summernote">{{ $product->long_description }}</textarea>
                                        </div>



                                        @if ($product->vendor_id != 0)
                                            <div class="form-group col-12">
                                                <label>{{__('admin.Product Request from seller')}} <span
                                                        class="text-danger">*</span></label>
                                                <select name="approve_by_admin" class="form-control">
                                                    <option {{ $product->approve_by_admin == 1 ? 'selected' : '' }} value="1">
                                                        {{__('admin.Approved')}}</option>
                                                    <option {{ $product->approve_by_admin == 0 ? 'selected' : '' }} value="0">
                                                        {{__('admin.Pending')}}</option>
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group col-12">
                                            <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control">
                                                <option {{ $product->status == 1 ? 'selected' : '' }} value="1">
                                                    {{__('admin.Active')}}</option>
                                                <option {{ $product->status == 0 ? 'selected' : '' }} value="0">
                                                    {{__('admin.Inactive')}}</option>
                                            </select>
                                        </div>


                                        <div class="form-group col-12">
                                            <label>{{__('admin.SEO Title')}}</label>
                                            <input type="text" class="form-control" name="seo_title"
                                                value="{{ $product->seo_title }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{__('admin.SEO Description')}}</label>
                                            <textarea name="seo_description" id="" cols="30" rows="10"
                                                class="form-control text-area-5">{{ $product->seo_description }}</textarea>
                                        </div>













                                    </div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="productType">Product Type?</label>
                                                <select id="productType" class="form-select form-control"
                                                    aria-label="Default select example" name="product_type">
                                                    <option value="single" {{ old('product_type', $product->product_type) == 'single' ? 'selected' : '' }}>Single
                                                    </option>
                                                    <option value="variable" {{ old('product_type', $product->product_type) == 'variable' ? 'selected' : '' }}>Variable
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container" id="variableContainer" style="display: none;">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">is Weekend Only ?</label>
                                                <select class="form-select form-control" aria-label="Default select example"
                                                    name="is_weekend_only">

                                                    <option value="0" {{ old('is_weekend_only', $product->is_weekend_only) == '0' ? 'selected' : '' }}>No</option>
                                                    <option value="1" {{ old('is_weekend_only', $product->is_weekend_only) == '1' ? 'selected' : '' }}>yes</option>
                                                </select>

                                            </div>

                                            <div class="col-md-4">
                                                <label for="products">Select Product:</label>
                                                <div id="productsContainer">
                                                    @foreach($product->productVariations as $productVar) <!-- Correct the iteration -->
                                                        <div class="input-group mb-2">
                                                            <input type="text" name="products[]" value="{{ $productVar->name }}"
                                                                class="form-control">
                                                            <input type="text" name="products_price[]"
                                                                value="{{ $productVar->products_price }}" class="form-control"
                                                                placeholder="Product Price">
                                                            <button type="button"
                                                                class="btn btn-danger remove-button">Delete</button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-success add-button"
                                                    data-container="productsContainer"
                                                    data-placeholder="products">Add</button>
                                            </div>



                                        </div>




                                    </div>

                                    <div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>


    <script>
        (function ($) {
            "use strict";
            var specification = '{{ $product->is_specification == 1 ? true : false }}';
            $(document).ready(function () {
                $("#name").on("focusout", function (e) {
                    $("#slug").val(convertToSlug($(this).val()));
                })

                $("#category").on("change", function () {
                    var categoryId = $("#category").val();
                    if (categoryId) {
                        $.ajax({
                            type: "get",
                            url: "{{url('/admin/subcategory-by-category/')}}" + "/" + categoryId,
                            success: function (response) {
                                $("#sub_category").html(response.subCategories);
                                var response = "<option value=''>{{__('admin.Select Child Category')}}</option>";
                                $("#child_category").html(response);

                            },
                            error: function (err) {
                                console.log(err);

                            }
                        })
                    } else {
                        var response = "<option value=''>{{__('admin.Select Sub Category')}}</option>";
                        $("#sub_category").html(response);
                        var response = "<option value=''>{{__('admin.Select Child Category')}}</option>";
                        $("#child_category").html(response);
                    }


                })

                $("#sub_category").on("change", function () {
                    var SubCategoryId = $("#sub_category").val();
                    if (SubCategoryId) {
                        $.ajax({
                            type: "get",
                            url: "{{url('/admin/childcategory-by-subcategory/')}}" + "/" + SubCategoryId,
                            success: function (response) {
                                $("#child_category").html(response.childCategories);
                            },
                            error: function (err) {
                                console.log(err);

                            }
                        })
                    } else {
                        var response = "<option value=''>{{__('admin.Select Child Category')}}</option>";
                        $("#child_category").html(response);
                    }

                })

                $("#is_return").on('change', function () {
                    var returnId = $("#is_return").val();
                    if (returnId == 1) {
                        $("#policy_box").removeClass('d-none');
                    } else {
                        $("#policy_box").addClass('d-none');
                    }

                })

                $("#addNewSpecificationRow").on('click', function () {
                    var html = $("#hidden-specification-box").html();
                    $("#specification-box").append(html);
                })

                $(document).on('click', '.deleteSpeceficationBtn', function () {
                    $(this).closest('.delete-specification-row').remove();
                });


                $("#manageSpecificationBox").on("click", function () {
                    if (specification) {
                        specification = false;
                        $("#specification-box").addClass('d-none');
                    } else {
                        specification = true;
                        $("#specification-box").removeClass('d-none');
                    }


                })

                $(".removeExistSpecificationRow").on("click", function () {
                    var isDemo = "{{ env('APP_MODE') }}"
                    if (isDemo == 'DEMO') {
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }
                    var specificationId = $(this).attr("data-specificationiId");
                    $.ajax({
                        type: "put",
                        data: { _token: '{{ csrf_token() }}' },
                        url: "{{url('/admin/removed-product-exist-specification/')}}" + "/" + specificationId,
                        success: function (response) {
                            toastr.success(response)
                            $("#existSpecificationBox-" + specificationId).remove();
                        },
                        error: function (err) {
                            console.log(err);

                        }
                    })
                })

            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }

        function previewThumnailImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('preview-img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        };

    </script>

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url("admin/delete-product-image/") }}' + "/" + id)
        }
        function changeProductStatus(id) {
            var isDemo = "{{ env('APP_MODE') }}"
            if (isDemo == 'DEMO') {
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }
            $.ajax({
                type: "put",
                data: { _token: '{{ csrf_token() }}' },
                url: "{{url('/admin/product-gallery-status/')}}" + "/" + id,
                success: function (response) {
                    toastr.success(response)
                },
                error: function (err) {
                    console.log(err);

                }
            })
        }







    </script>



    <!-- Include your JavaScript for dynamic adding/removing input fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to add a new input group
            function addInputGroup(containerId, placeholder, hasPrice = false) {
                const container = document.getElementById(containerId);
                const div = document.createElement('div');
                div.classList.add('input-group', 'mb-2');

                const input = document.createElement('input');
                input.type = 'text';
                input.name = containerId.replace('Container', '[]');
                input.classList.add('form-control');
                input.placeholder = placeholder;

                div.appendChild(input);

                if (hasPrice) {
                    const priceInput = document.createElement('input');
                    priceInput.type = 'text';
                    priceInput.name = containerId.replace('Container', '_price[]');
                    priceInput.classList.add('form-control');
                    priceInput.placeholder = 'Price';

                    div.appendChild(priceInput);
                }

                const button = document.createElement('button');
                button.type = 'button';
                button.classList.add('btn', 'btn-danger', 'remove-button');
                button.textContent = 'Delete';
                button.addEventListener('click', function () {
                    div.remove();
                });

                div.appendChild(button);
                container.appendChild(div);
            }

            // Attach click event to add buttons
            document.querySelectorAll('.add-button').forEach(button => {
                button.addEventListener('click', function () {
                    const containerId = this.getAttribute('data-container');
                    const placeholder = this.getAttribute('data-placeholder');
                    const hasPrice = containerId === 'sidesContainer' || containerId === 'protinsContainer' || containerId === 'productsContainer'; // Add price input for sides and protins
                    addInputGroup(containerId, placeholder, hasPrice);
                });
            });

            // Attach click event to remove buttons
            document.querySelectorAll('.remove-button').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('.input-group').remove();
                });
            });
        });


    </script>


    <!-- JavaScript to toggle visibility -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productTypeSelect = document.getElementById('productType');
            const variableContainer = document.getElementById('variableContainer');

            function toggleVariableContainer() {
                if (productTypeSelect.value === 'variable') {
                    variableContainer.style.display = 'block';
                } else {
                    variableContainer.style.display = 'none';
                }
            }

            // Run function on page load to ensure correct display state
            toggleVariableContainer();

            // Add event listener to show/hide container based on selection
            productTypeSelect.addEventListener('change', toggleVariableContainer);
        });
    </script>
@endsection