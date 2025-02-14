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
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Product')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Products')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-6">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset('uploads/custom-images/'.$product->thumb_image) }}" alt="">
                                    </div>

                                </div>

                                <div class="form-group col-6">
                                    <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)">
                                </div>
                                <div class="form-group col-6">
                                @foreach($product->gallery as $key=>$pGImg)

                                <!--<i class="fa fa-times" aria-hidden="true"></i>-->
                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $pGImg->id }})"><i class="fa fa-times" aria-hidden="true"></i></a>

                                <img src="{{asset($pGImg->image)}}" width="100px" height="100px">
                                @endforeach

                                </div>
                                <br/>
                                <div class="form-group col-6">
                                <label for="">Select Multiple images here </label>
                                <input type="file" class="form-control-file" name="images[]" multiple>
                            </div>
                            <input type="hidden" name="product_id" required value="{{ $product->id }}">




                                <!--<div class="form-group col-12">-->
                                <!--    <label>{{__('admin.Short Name')}} <span class="text-danger">*</span></label>-->
                                <!--    <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ $product->short_name }}">-->
                                <!--</div>-->

                                <div class="form-group col-6">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $product->name }}">
                                </div>

                                <div class="form-group col-6">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ $product->slug }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>Vedio URL </label>
                                    <input type="text" id="slug" class="form-control"  name="video_link" value="{{ $product->video_link }}">
                                </div>

                                <div class="form-group col-4">
                                    <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2" id="category">
                                        <option value="">{{__('admin.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                            <option {{ $product->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-4">
                                    <label>{{__('admin.Sub Category')}}</label>
                                    <select name="sub_category" class="form-control select2" id="sub_category">
                                        <option value="">{{__('admin.Select Sub Category')}}</option>
                                        @if ($product->category_id != 0)
                                            @foreach ($subCategories as $subCategory)
                                            <option {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
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
                                            <option {{ $product->child_category_id == $childCategory->id ? 'selected' : '' }} value="{{ $childCategory->id }}">{{ $childCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-6">
                                    <label>{{__('admin.Brand')}}</label>
                                    <select name="brand" class="form-control select2" id="brand">
                                        <option value="">{{__('admin.Select Brand')}}</option>
                                        @foreach ($brands as $brand)
                                            <option {{ $product->brand_id == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-6">
                                    <label>{{__('admin.SKU')}} </label>
                                   <input type="text" class="form-control" name="sku" value="{{ $product->sku }}">
                                </div>

                                <div class="form-group col-6">
                                    <label>{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" name="price" value="{{ $product->price }}">
                                </div>

                                <div class="form-group col-6">
                                    <label>{{__('admin.Offer Price')}} </label>
                                   <input type="text" class="form-control" name="offer_price" value="{{ $product->offer_price }}">
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
                                    <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="long_description" id="" cols="30" rows="10" class="summernote">{{ $product->long_description }}</textarea>
                                </div>



                                @if ($product->vendor_id != 0)
                                    <div class="form-group col-12">
                                        <label>{{__('admin.Product Request from seller')}} <span class="text-danger">*</span></label>
                                        <select name="approve_by_admin" class="form-control">
                                            <option {{ $product->approve_by_admin == 1 ? 'selected' : '' }} value="1">{{__('admin.Approved')}}</option>
                                            <option {{ $product->approve_by_admin == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>


                                {{-- <div class="form-group col-12">
                                    <label>{{__('admin.SEO Title')}}</label>
                                   <input type="text" class="form-control" name="seo_title" value="{{ $product->seo_title }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Description')}}</label>
                                    <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ $product->seo_description }}</textarea>
                                </div> --}}







                               {{-- <div class="form-group col-12">
                                    <label>{{__('admin.Specifications')}}</label>
                                    <div>
                                        @if ($product->is_specification==1)
                                            <a href="javascript::void()" id="manageSpecificationBox">
                                                <input name="is_specification" id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                            </a>
                                        @else
                                        <a href="javascript::void()" id="manageSpecificationBox">
                                                <input name="is_specification" id="status_toggle" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                            </a>
                                        @endif

                                    </div>
                                </div> --}}
                                @if ($product->is_specification==1)
                                    <div class="form-group col-12" id="specification-box">
                                        @if ($productSpecifications->count() != 0)
                                            @foreach ($productSpecifications as $productSpecification)
                                                <div class="row mt-2" id="existSpecificationBox-{{ $productSpecification->id }}">
                                                    <div class="col-md-5">
                                                        <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                        <select name="keys[]" class="form-control">
                                                            @foreach ($specificationKeys as $specificationKey)
                                                                <option {{ $specificationKey->id == $productSpecification->product_specification_key_id ? 'selected' : '' }} value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="specifications[]" value="{{ $productSpecification->specification }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger plus_btn removeExistSpecificationRow"  data-specificationiId="{{ $productSpecification->id }}"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success plus_btn" id="addNewSpecificationRow"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>

                                    </div>
                                @endif

                                @if ($product->is_specification==0)
                                    <div class="form-group col-12 d-none" id="specification-box">
                                        @if ($productSpecifications->count() != 0)
                                            @foreach ($productSpecifications as $productSpecification)
                                                <div class="row mt-2" id="existSpecificationBox-{{ $productSpecification->id }}">
                                                    <div class="col-md-5">
                                                        <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                        <select name="keys[]" class="form-control">
                                                            @foreach ($specificationKeys as $specificationKey)
                                                                <option {{ $specificationKey->id == $productSpecification->product_specification_key_id ? 'selected' : '' }} value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="specifications[]" value="{{ $productSpecification->specification }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger plus_btn removeExistSpecificationRow"  data-specificationiId="{{ $productSpecification->id }}"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success plus_btn" id="addNewSpecificationRow"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>

                                    </div>
                                @endif




                                <div id="hidden-specification-box" class="d-none">
                                    <div class="delete-specification-row">
                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger plus_btn deleteSpeceficationBtn"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



						<div class="row">

                                <div class="col-md-12">
                            @if(!empty($product->type == 'variable'))

                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap table-bordered text-center">
                                        <thead>
                                            <tr>
                                               <th>Combo</th>

                                                <th style="">Price</th>

                                                <th width="5">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variations as $v)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="variation_id[]" value="{{$v->id}}">
                                                    <select name="size_id[]" class="form-control">
                                                        @foreach($sizes as $size)
                                                        <option {{$size->id==$v->size_id ?'selected':''}} value="{{$size->id}}">{{ $size->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td style="">
                                                    <input class="variable_sell_price form-control" type="number" value="{{ $v->sell_price }}" name="sell_price[]" placeholder="Price">
                                                </td>

                                                <td style="display:flex; align-items: flex-end;">
                                                    <a class="action-icon btn btn-primary add_moore" style="cursor: pointer;color: #ffffff;">Add</a>
                                                    <a class="action-icon btn btn-danger remove" style="cursor: pointer;color: #ffffff;">Delete </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

							@endif
                                </div>

                                <div class="col-md-7">
                                      @if(!empty($product->prod_color == 'varcolor'))

                                        <div id="variable_table_color" class="" >
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap table-bordered text-center color_table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 23%">Color</th>
                                                    <th style="width: 45%;">Image</th>
                                                  	<th style="width: 23%"> preview <th>

                                                    <th >Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                              @foreach($product->colorVariations as $vc)

                                                <tr id="color_row">
                                                    <td>
                                                       <input type="hidden" name="color_variation_id[]" value="{{$vc->id}}">
                                                        <select name="color_id[]" class="form-control">
                                                            @foreach($colors as $color)
                                                            <option {{$color->id==$vc->color_id ? 'selected' :''}} value="{{$color->id}}">{{ $color->name }}</option>

                                                            @endforeach


                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="variable_product_image form-control" type="file" name="var_images[]" placeholder="Price">
                                                    </td>

                                                  <td>
                                                  		<img src="{{asset($vc->var_images)}}" width="100px" height="100px" />
                                                  </td>



                                                    <td style="display:flex; align-items: flex-end;">
                                                        <a class="btn action-icon btn-primary add_moore_color" style="cursor: pointer;color: #ffffff;">Add </a>
                                                      	<a class="action-icon btn btn-danger remove" style="cursor: pointer;color: #ffffff;">Delete </a>
                                                    </td>
                                                </tr>
                                              @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                                    </div>


                            @endif


                                </div>





                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="productType">Product Type?</label>
                                        <select id="productType" class="form-select form-control" aria-label="Default select example" name="product_type">
                                            <option value="single" {{ old('product_type', $product->product_type) == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="variable" {{ old('product_type', $product->product_type) == 'variable' ? 'selected' : '' }}>Variable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="container" id="variableContainer" style="display: none;">

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">is Weekend Only ?</label>
                                        <select class="form-select form-control" aria-label="Default select example" name="is_weekend_only">
                                            
                                            <option value="0" {{ old('is_weekend_only', $product->is_weekend_only) == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('is_weekend_only', $product->is_weekend_only) == '1' ? 'selected' : '' }}>yes</option>
                                        </select>
                                        
                                    </div>

                                    <div class="col-md-4">
                                        <label for="products">Select Product:</label>
                                        <div id="productsContainer">
                                            @foreach($product->productVariations as $productVar) <!-- Correct the iteration -->
                                            <div class="input-group mb-2">
                                                <input type="text" name="products[]" value="{{ $productVar->name }}" class="form-control">
                                                <input type="text" name="products_price[]" value="{{ $productVar->products_price }}" class="form-control" placeholder="Product Price">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="productsContainer" data-placeholder="products">Add</button>
                                    </div>
                                    

                                    <!-- Flavours -->
                                    <div class="col-md-4">
                                        <label for="flavours">Flavours:</label>
                                        <div id="flavoursContainer">
                                            @foreach($product->flavours as $flavour)
                                            <div class="input-group mb-2">
                                                <input type="text" name="flavours[]" value="{{ $flavour->name }}" class="form-control">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="flavoursContainer" data-placeholder="Flavour">Add</button>
                                    </div>
                        
                                    <!-- Toppings -->
                                    <div class="col-md-4">
                                        <label for="toppings">Toppings:</label>
                                        <div id="toppingsContainer">
                                            @foreach($product->toppings as $topping)
                                            <div class="input-group mb-2">
                                                <input type="text" name="toppings[]" value="{{ $topping->name }}" class="form-control">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="toppingsContainer" data-placeholder="Topping">Add</button>
                                    </div>
                        
                                    <!-- Dips -->
                                    <div class="col-md-4">
                                        <label for="dips">Options:</label>
                                        <div id="dipsContainer">
                                            @foreach($product->dips as $dip)
                                            <div class="input-group mb-2">
                                                <input type="text" name="dips[]" value="{{ $dip->name }}" class="form-control">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="dipsContainer" data-placeholder="option">Add</button>
                                    </div>
                        
                                    <div class="col-md-4">
                                        <label for="protins">Combo:</label>
                                        <div id="protinsContainer">
                                            @foreach($product->protins as $protin)
                                            <div class="input-group mb-2">
                                                <input type="text" name="protins[]" value="{{ $protin->name }}" class="form-control" placeholder="Protin">
                                                <input type="text" name="protins_price[]" value="{{ $protin->protin_price }}" class="form-control" placeholder="Protin Price">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="protinsContainer" data-placeholder="Protin" data-has-price="true">Add</button>
                                    </div>
                                    
                                    
                        
                                    <!-- Cheeses -->
                                    <div class="col-md-4">
                                        <label for="cheeses">Cheeses:</label>
                                        <div id="cheesesContainer">
                                            @foreach($product->cheeses as $cheese)
                                            <div class="input-group mb-2">
                                                <input type="text" name="cheeses[]" value="{{ $cheese->name }}" class="form-control">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="cheesesContainer" data-placeholder="Cheese">Add</button>
                                    </div>
                        
                                    <!-- Vaggies -->
                                    <div class="col-md-4">
                                        <label for="vaggies">Vaggies:</label>
                                        <div id="vaggiesContainer">
                                            @foreach($product->vaggies as $vaggie)
                                            <div class="input-group mb-2">
                                                <input type="text" name="vaggies[]" value="{{ $vaggie->name }}" class="form-control">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="vaggiesContainer" data-placeholder="Vaggie">Add</button>
                                    </div>
                        
                                    <!-- Sauces -->
                                    <div class="col-md-4">
                                        <label for="sauces">Sauces:</label>
                                        <div id="saucesContainer">
                                            @foreach($product->sauces as $sauce)
                                            <div class="input-group mb-2">
                                                <input type="text" name="sauces[]" value="{{ $sauce->name }}" class="form-control">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="saucesContainer" data-placeholder="Sauce">Add</button>
                                    </div>
                        
                                    <!-- Sides -->
                                    <div class="col-md-4">
                                        <label for="">Select Side type</label>
                                        <select class="form-select form-control" aria-label="Default select example" name="sides_type">
                                            <option value="null" {{ old('sides_type', $product->sides_type) == 'null' ? 'selected' : '' }}>None</option>
                                            <option value="one_sides" {{ old('sides_type', $product->sides_type) == 'one_sides' ? 'selected' : '' }}>One side</option>
                                            <option value="two_sides" {{ old('sides_type', $product->sides_type) == 'two_sides' ? 'selected' : '' }}>Two sides</option>
                                        </select>
                                        
                                    </div>

                                    <!-- Free Sides -->
                                    


                                    <div class="col-md-4">
                                        <label for="free_sides">Free Sides:</label>
                                        <div id="freeSidesContainer">
                                            @foreach($product->freeSides as $freeSides)
                                            <div class="input-group mb-2">
                                                <input type="text" name="freeSides[]" value="{{ $freeSides->name }}" class="form-control" placeholder="Free Side">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="freeSidesContainer" data-placeholder="Free Side">Add Free Side</button>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="sides">Sides:</label>
                                        <div id="sidesContainer">
                                            @foreach($product->sides as $side)
                                            <div class="input-group mb-2">
                                                <input type="text" name="sides[]" value="{{ $side->name }}" class="form-control">
                                                <input type="text" name="sides_price[]" value="{{ $side->sides_price }}" class="form-control" placeholder="Side Price">
                                                <button type="button" class="btn btn-danger remove-button">Delete</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success add-button" data-container="sidesContainer" data-placeholder="Side">Add</button>
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
    (function($) {
        "use strict";
        var specification = '{{ $product->is_specification == 1 ? true : false }}';
        $(document).ready(function () {
            $("#name").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })

            $("#category").on("change",function(){
                var categoryId = $("#category").val();
                if(categoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/subcategory-by-category/')}}"+"/"+categoryId,
                        success:function(response){
                            $("#sub_category").html(response.subCategories);
                            var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                            $("#child_category").html(response);

                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('admin.Select Sub Category')}}</option>";
                    $("#sub_category").html(response);
                    var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }


            })

            $("#sub_category").on("change",function(){
                var SubCategoryId = $("#sub_category").val();
                if(SubCategoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/childcategory-by-subcategory/')}}"+"/"+SubCategoryId,
                        success:function(response){
                            $("#child_category").html(response.childCategories);
                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }

            })

            $("#is_return").on('change',function(){
                var returnId = $("#is_return").val();
                if(returnId == 1){
                    $("#policy_box").removeClass('d-none');
                }else{
                    $("#policy_box").addClass('d-none');
                }

            })

            $("#addNewSpecificationRow").on('click',function(){
                var html = $("#hidden-specification-box").html();
                $("#specification-box").append(html);
            })

            $(document).on('click', '.deleteSpeceficationBtn', function () {
                $(this).closest('.delete-specification-row').remove();
            });


            $("#manageSpecificationBox").on("click",function(){
                if(specification){
                    specification = false;
                    $("#specification-box").addClass('d-none');
                }else{
                    specification = true;
                    $("#specification-box").removeClass('d-none');
                }


            })

            $(".removeExistSpecificationRow").on("click",function(){
                var isDemo = "{{ env('APP_MODE') }}"
                if(isDemo == 'DEMO'){
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }
                var specificationId = $(this).attr("data-specificationiId");
                $.ajax({
                    type:"put",
                    data: { _token : '{{ csrf_token() }}' },
                    url:"{{url('/admin/removed-product-exist-specification/')}}"+"/"+specificationId,
                    success:function(response){
                        toastr.success(response)
                        $("#existSpecificationBox-"+specificationId).remove();
                    },
                    error:function(err){
                        console.log(err);

                    }
                })
            })

        });
    })(jQuery);

    function convertToSlug(Text){
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
    }

    function previewThumnailImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-img');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    };

</script>

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/delete-product-image/") }}'+"/"+id)
    }
    function changeProductStatus(id){
        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 'DEMO'){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/product-gallery-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){
                console.log(err);

            }
        })
    }






     // add moore

     $(document).on('blur', '.variable_sell_price', function() {
            let tblrow = $(this).closest("tr");

            var discount_type=$("select[name='discount_type']").val();

            let variable_sell_price = tblrow.find('td input.variable_sell_price').val();
            var discount_amount = $("input[name='dicount_amount']").val();

            if (discount_type=='percentage') {
                new_price= (variable_sell_price / 100) * discount_amount;
                new_price=variable_sell_price - new_price;
            }else{
                new_price= variable_sell_price - discount_amount;
            }
            tblrow.find('td input.variable_dis_price').val(new_price);
        });

        $(document).on('click','a.add_moore', function(){
            let tblrow = $(this).closest("tr");

            let variable_sell_price = tblrow.find('td input.variable_sell_price').val();
            let variable_dis_price = tblrow.find('td input.variable_dis_price').val();

            let type='{{ $product->type}}';

            if (type=='single') {
                toastr.error('For Single Product You Can\'t Add Moore');
                return;
            }
            let row=`<tr>
                        <td>
                            <select name="size_id[]" class="form-control">
                                @foreach($sizes as $size)
                                <option value="{{$size->id}}">{{ $size->title }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        <input class="variable_sell_price form-control" type="number" value="${variable_sell_price}" name="sell_price[]" placeholder="Price">
                        </td>

                        <td>
                            <a class="action-icon btn-primary add_moore"> Add  </a>
                            <a class="action-icon btn-danger remove"> Delete </a>
                        </td>
                    </tr>`;
            $(document).find('.table tbody').append(row);

        });





  		 $(document).on('click','a.add_moore_color', function(){

            let tblrow = $(this).closest("#color_row");

            let variable_product_image = tblrow.find('td input.variable_product_image').val();

            let type=$("select[name='prod_color']").val();

            if (type=='sincolor') {
                toastr.error('For Single Product You Can\'t Add Moore');
                return;
            }
            let row=`<tr id="color_row">
                        <td>
                            <select name="color_id[]" class="form-control">
                                @foreach($colors as $color)
                                  <option {{$color->is_default==1 ?'selected':''}} value="{{$color->id}}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </td>


                        <td>

			<input class="variable_product_image form-control" type="file" name="var_images[]" placeholder="Price">

                        </td>

                        <td>
                            <a class="btn action-icon btn-primary add_moore_color" style="cursor: pointer;color: #ffffff;"> Add </a>
                            <a class="btn action-icon btn-danger remove" style="cursor: pointer;color: #ffffff;"> Delete </a>
                        </td>
                    </tr>`;
            $(document).find('.color_table tbody').append(row);

        });




$(document).on('change', "select[name='prod_color']", function() {
            let type=$("select[name='prod_color']").val();
            if(type == 'varcolor') {
                document.getElementById('variable_table_color').style.display = 'block';
            } else {
                document.getElementById('variable_table_color').style.display = 'none';
            }
        });




        $(document).on('click', "a.remove",function(e) {
            var whichtr = $(this).closest("tr");
            whichtr.remove();
        });
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
    document.addEventListener('DOMContentLoaded', function() {
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
