@extends('app')

@section('head_title', getcong('privacy_policy_title').' | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', getcong('privacy_policy_title'))
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => getcong('privacy_policy_title'), 'url' => '']]))

@section("content")

@include('common.page-hero-header') 

 <!-- ================================
     Start About Area
================================= -->
<section class="about-area bg-gray section_item_padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="section-heading">                   
                    <p class="item_sec_desc">{!!getcong('privacy_policy_description')!!}</p>
                </div>
            </div>             
        </div>
    </div>
</section>
<!-- ================================
     End About Area
================================= -->

 
@endsection