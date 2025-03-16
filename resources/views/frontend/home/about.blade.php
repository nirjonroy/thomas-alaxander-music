@extends('frontend.app')
@section('title', 'About Thomas Alexander')
@push('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')

<div class="ms_content_wrapper padder_top8">
   
    <div class="ms_index_wrapper common_pages_space">
      
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ms_about_wrapper">
                        <div class="ms_about_img">
                            <img src="{{ asset($about->video_background) }}" alt="About Thomas Alexander" width="200px" height="60px" style="background:white">
                            </div>
                            <div class="ms_about_content" style="margin-top:10px; background: black; padding: 5px;">

                                <h1 style="font-size:14pt">{{$about->description_three}}</h1>

                                <p style="background: white !important">
                                    {!!$about->about_us!!}
                                </p>
                                
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>                          
                                
                                    

    </div>
</div>

@endsection
