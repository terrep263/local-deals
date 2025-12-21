@extends('app')

@section('head_title', $cat_info->category_name.' | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', $cat_info->category_name)
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => $cat_info->category_name, 'url' => '']]))

@section("content")

@include('common.page-hero-header') 

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