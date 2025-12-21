@extends('app')

@section('head_title',trans('words.pricing_plan').' | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', trans('words.pricing'))
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => trans('words.pricing'), 'url' => '']]))

@section("content")

@include('common.page-hero-header') 

<!-- ================================
     Start Pricing Area
================================= -->
<section class="pricing-area bg-gray section_item_padding">
    <div class="container">
        <div class="row">
        @foreach($subscription_plan as $plan_data)    
        <div class="col-lg-4 col-md-6">
                <div class="price-item @if($plan_data->plan_recommended)pricing-active @endif">
                    <div class="price-head">
                        @if($plan_data->plan_recommended)
                        <span class="badge badge-light">{{trans('words.recommended')}}</span>
                        @endif
                        
                        <i class="fas fa-gem item_price_icon"></i>
                        <h3 class="price_title mt-3">{{$plan_data->plan_name}}</h3>
                    </div>
                    <div class="price-content">
                        <div class="price-number">
                            <p class="price_value"><sup>{{html_entity_decode(getCurrencySymbols(getcong('currency_code')))}}</sup>{{$plan_data->plan_price}}</p>
                            <p class="price_subtitle">{{trans('words.for')}} {{ App\Models\SubscriptionPlan::getPlanDuration($plan_data->id) }}</p>
                        </div>
                        <hr class="border-top-gray my-4">
                        <ul class="list-items text-left mb-4">
                        
                            <li><i class="fal fa-check text-success mr-2"></i> {{$plan_data->plan_listing_limit}} Listings {{trans('words.allowed')}}</li>

                            @if($plan_data->plan_amenities_option)
                            <li><i class="fal fa-check text-success mr-2"></i> {{trans('words.amenities')}}</li>
                            @else
                            <li><i class="fal fa-times text-danger mr-2"></i> {{trans('words.amenities')}}</li>
                            @endif

                            @if($plan_data->plan_business_hours_option)
                            <li><i class="fal fa-check text-success mr-2"></i> {{trans('words.business_hours')}}</li>
                            @else
                            <li><i class="fal fa-times text-danger mr-2"></i> {{trans('words.business_hours')}}</li>
                            @endif

                            @if($plan_data->plan_gallery_images_option)
                            <li><i class="fal fa-check text-success mr-2"></i> {{trans('words.gallery_images')}}</li>
                            @else
                            <li><i class="fal fa-times text-danger mr-2"></i> {{trans('words.gallery_images')}}</li>
                            @endif

                            @if($plan_data->plan_video_option)
                            <li><i class="fal fa-check text-success mr-2"></i> {{trans('words.video')}}</li>
                            @else
                            <li><i class="fal fa-times text-danger mr-2"></i> {{trans('words.video')}}</li>
                            @endif

                            @if($plan_data->plan_featured_option)
                            <li><i class="fal fa-check text-success mr-2"></i> {{trans('words.featured')}} {{trans('words.listings')}} {{trans('words.allowed')}}</li>
                            @else
                            <li><i class="fal fa-times text-danger mr-2"></i> {{trans('words.featured')}} {{trans('words.listings')}}  {{trans('words.not_allowed')}}</li>
                            @endif

                            @if($plan_data->plan_enquiry_form)
                            <li><i class="fal fa-check text-success mr-2"></i> {{trans('words.enquiry_form')}}  {{trans('words.allowed')}}</li>
                            @else
                            <li><i class="fal fa-times text-danger mr-2"></i> {{trans('words.enquiry_form')}} {{trans('words.not_allowed')}}</li>
                            @endif
                              
                        </ul>
                        <a href="{{URL::to('payment_method/'.$plan_data->id)}}" class="primary_item_btn w-100">{{trans('words.continue')}}</a>
                    </div>
                </div>
            </div>
            @endforeach
             
             
        </div>
    </div>
</section>
<!-- ================================
     End Pricing Area
================================= -->

 
@endsection