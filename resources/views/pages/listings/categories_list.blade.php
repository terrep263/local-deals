@extends('app')

@section('head_title',trans('words.categories').' | '.getcong('site_name') )

@section('head_url', Request::url())

@section("content")

 
<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{trans('words.categories')}}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}" title="home">{{trans('words.home')}}</a></li>
                <li>{{trans('words.categories')}}</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

 <!-- ================================
     Start Category Area
================================= -->
<section class="category_area bg-gray section_item_padding">
    <div class="container">
        <div class="row">
            @foreach($cat_list as $cat_data)
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                <a href="{{URL::to('categories/'.$cat_data->category_slug.'/'.$cat_data->id)}}" class="sec_category_item d-block hover-y" title="category">
                    <div class="overlay"></div>
                    
                    @if($cat_data->category_image)
                        <img src="{{URL::to('assets/images/img-loading.jpg')}}" data-src="{{URL::to('upload/category/'.$cat_data->category_image)}}" alt="category-image" class="category-img lazy" title="category">

                    @else
                        <img src="{{URL::to('assets/images/img-loading.jpg')}}" data-src="{{URL::to('upload/category/default.jpg')}}" alt="category-image" class="category-img lazy" title="category">
                    @endif
 
                    <div class="category-content d-flex align-items-center justify-content-center">
                        <span class="fa {{$cat_data->category_icon}} icon-element d-block mx-auto"></span>
                        <div class="cat_text_item">
                            <h4 class="cat-title mb-1">{{$cat_data->category_name}}</h4>
                             
                                <span class="badge">{{ \App\Models\Categories::countCategoryListings($cat_data->id) }} {{trans('words.listings')}}</span>
                          
                        </div>
                    </div>
                </a>
            </div>
            @endforeach

          
        </div>
        <nav aria-label="navigation">

            @include('common.pagination', ['paginator' => $cat_list])
 
        </nav>
    </div>
</section>
<!-- ================================
     End Category Area
================================= -->

 
@endsection