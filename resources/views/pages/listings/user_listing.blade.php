@extends('app')

@section('head_title', 'User Listings | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', trans('words.listings'))
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => trans('words.listings'), 'url' => '']]))

@section("content")

@include('common.page-hero-header', ['title' => trans('words.listings')]) 

<!-- ================================
    Start Card Area
================================= -->
<section class="listing_card_area bg-gray section_item_padding">
    <div class="container">
        <div class="row">
        <div class="col-lg-4">
               <div class="sidebar mb-4">
      				   <div class="breadcrumb-content media mb-4">
                    @if($user_info->image_icon)
                    <img src="{{ URL::asset('upload/members/'.$user_info->image_icon) }}" alt="avatar" class="user-avatar flex-shrink-0 mr-3" title="user">
                    @else
                    <img src="{{URL::to('assets/images/avatar.jpg')}}" alt="avatar" class="user-avatar flex-shrink-0 mr-3" title="user">
                    @endif  
                              
      						
                  <div class="media-body align-self-center">
      							<h4 class="font-size-20 font-weight-semi-bold mb-1">{{ \App\Models\User::getUserFullname($user_info->id) }}</h4>
      							<div>
      								<div class="rating-counter pl-0">({{countUserListing($user_info->id)}} {{trans('words.listings')}})</div>
      							</div>
      						</div>
      				   </div>	
                    
                   <div class="card">
                       <div class="card-body">
                           <h4 class="card-title mb-3">{{trans('words.contact_information')}}</h4>
                           <hr class="border-top-gray my-0">
                           <ul class="list-items my-4">
                               <li><span class="fal fa-envelope icon-element icon-element-sm shadow-sm mr-2 font-size-14"></span> <a href="mailto:{{ $user_info->contact_email }}" title="email">{{ $user_info->contact_email }}</a></li>
                               <li><span class="fal fa-phone icon-element icon-element-sm shadow-sm mr-2 font-size-14"></span> <a href="tel:{{ $user_info->mobile }}" title="phone">{{ $user_info->mobile }}</a></li>
                               <li><span class="fal fa-external-link icon-element icon-element-sm shadow-sm mr-2 font-size-14"></span> <a href="{{ $user_info->website }}" target="_blank" title="website">{{ $user_info->website }}</a></li>
                           </ul>
                           <div class="social-icons mb-4">
                               @if($user_info->facebook_url)
                                <a href="{{$user_info->facebook_url}}" target="_blank" title="facebook"><i class="fab fa-facebook-f"></i></a>          
                               @endif

                               @if($user_info->twitter_url)
                                <a href="{{$user_info->twitter_url}}" target="_blank" title="twitter"><i class="fab fa-twitter"></i></a>          
                               @endif

                               @if($user_info->linkedin_url)
                                <a href="{{$user_info->linkedin_url}}" target="_blank" title="linkedin"><i class="fab fa-linkedin"></i></a>          
                               @endif

                               @if($user_info->instagram_url)
                                <a href="{{$user_info->instagram_url}}" target="_blank" title="instagram"><i class="fab fa-instagram"></i></a>          
                               @endif                                
                                
                           </div>                            
                       </div>
                   </div>
               </div>
            </div>
            
            <div class="col-lg-8">
              
                <div class="row">
                    @foreach($listings as $listing) 
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card hover-y">
                            <a href="{{URL::to('listings/'.$listing->listing_slug.'/'.$listing->id)}}" class="card-image" title="{{$listing->title}}">
                                <img src="{{URL::to('assets/images/img-loading.jpg')}}" data-src="{{ URL::asset('upload/listings/'.$listing->featured_image.'-s.jpg') }}" class="card-img-top lazy" alt="card image" title="{{$listing->title}}">                                 
                                <div class="list-tag-badge"><span class="fal {{\App\Models\Categories::getCategoryInfo($listing->cat_id)->category_icon}} icon-element icon-element-sm"></span> {{\App\Models\Categories::getCategoryInfo($listing->cat_id)->category_name}}</div>
                            </a>
                            <div class="card-body position-relative">
                                <div class="d-flex align-items-center mb-1">
                                    <h4 class="card-title mb-0"><a href="{{URL::to('listings/'.$listing->listing_slug.'/'.$listing->id)}}" title="{{$listing->title}}">{{$listing->title}}</a></h4>
                                     
                                </div>
                                <p class="card-text"><i class="fal fa-map-marker-alt icon"></i>{{Str::limit($listing->address,50)}}</p>
                                 
                            </div>
                            <div class="card-footer bg-transparent border-top-gray d-flex align-items-center justify-content-between">
                                <div class="star-rating" @if($listing->review_avg) data-rating="{{$listing->review_avg}}"@endif>
                                    <div class="rating-counter">{{\App\Models\Reviews::getTotalReview($listing->id)}} {{trans('words.reviews')}}</div>
                                </div>                                 
                            </div>
                        </div>
                    </div>
                    @endforeach

                     
                     
                </div>
                <nav aria-label="navigation">
                    @include('common.pagination', ['paginator' => $listings])
                </nav>
            </div>
        </div>        
    </div>
</section>
<!-- ================================
     End Card Area
================================= -->
<script src="{{ URL::asset('assets/js/jquery-3.4.1.min.js') }}"></script>
<script>
   $(function(){

    $('#fiveStarRadio,#fourStarRadio,#threeStarRadio,#twoStarRadio,#oneStarRadio').on('click', function () {
          var rate_val = $(this).val(); // get selected value
          
          var url = '{{URL::to('listings/')}}?rate='+rate_val;
 
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });

    
    });

</script>
 
@endsection