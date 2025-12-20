@extends('app')

@section('head_title', getcong('terms_of_title').' | '.getcong('site_name') )

@section('head_url', Request::url())

@section("content")

 
<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/about-hero.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{getcong('terms_of_title')}}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}" title="Home">{{trans('words.home')}}</a></li>
                <li>{{getcong('terms_of_title')}}</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

 <!-- ================================
     Start About Area
================================= -->
<section class="about-area bg-gray section_item_padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="section-heading">                   
                    <p class="item_sec_desc">{!!getcong('terms_of_description')!!}</p>
                </div>
            </div>             
        </div>
    </div>
</section>
<!-- ================================
     End About Area
================================= -->

 
@endsection