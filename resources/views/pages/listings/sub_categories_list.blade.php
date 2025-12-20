@extends('app')

@section('head_title', $cat_info->category_name.' | '.getcong('site_name') )

@section('head_url', Request::url())

@section("content")

 
<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{$cat_info->category_name}} - {{trans('words.sub_categories')}}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}" title="Home">{{trans('words.home')}}</a></li>
                <li>{{$cat_info->category_name}} - {{trans('words.sub_categories')}}</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

<!-- ================================
     Start Sub Category Area
================================= -->
<section class="sub_category_area bg-gray section_item_padding">
    <div class="container">
        <div class="row">
          @foreach($sub_cat_list as $sub_catdata)  
           <div class="col-lg-3">
               <a href="{{URL::to('listings/'.$cat_info->category_slug.'/'.$sub_catdata->sub_category_slug.'/'.$sub_catdata->id)}}" class="card text-center text-gray hover-y" title="{{$sub_catdata->sub_category_name}}">
                   <div class="card-body">
                       <span>{{$sub_catdata->sub_category_name}}</span>
                   </div>
               </a>
           </div>
           @endforeach
             
        </div>
         
    </div>
</section>
<!-- ================================
     End Sub Category Area
================================= -->

 
@endsection