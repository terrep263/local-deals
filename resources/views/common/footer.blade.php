<!-- ================================
     Start Footer Area
================================= -->
<section class="footer_area padding-top-80px pattern-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="footer_item">

                    <a href="{{ URL::to('/') }}" class="footer_logo mb-3 d-inline-block" title="logo">
                      @php
                        $footerLogo = getcong('site_footer_logo');
                        $siteLogo = getcong('site_logo');
                      @endphp
                      @if($footerLogo)
                        <img src="{{ URL::asset('upload/'.$footerLogo) }}" alt="logo" style="max-width: 200px;" title="footer logo">
                      @elseif($siteLogo)
                        <img src="{{ URL::asset('upload/'.$siteLogo) }}" alt="logo" style="max-width: 200px;" title="logo">
                      @else
                        <img src="{{ URL::asset('logo.png') }}" alt="logo" style="max-width: 200px;" title="logo">
                      @endif
                    </a>
 
                    <p class="mb-3">{!!getcong('site_footer_text')!!}</p>
                    <div class="social-icons">
                        @if(getcong('facebook_url'))
                        <a href="{{getcong('facebook_url')}}" target="_blank" title="facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif

                        @if(getcong('twitter_url'))
                        <a href="{{getcong('twitter_url')}}" target="_blank" title="twitter"><i class="fab fa-twitter"></i></a>
                        @endif

                        @if(getcong('instagram_url'))
                        <a href="{{getcong('instagram_url')}}" target="_blank" title="instagram"><i class="fab fa-instagram"></i></a>
                        @endif

                        @if(getcong('linkedin_url'))
                        <a href="{{getcong('linkedin_url')}}" target="_blank" title="linkedin"><i class="fab fa-linkedin"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer_item">
                    <h4 class="footer_item_title mb-2">{{trans('words.quick_links')}}</h4>
                    <div class="stroke-shape mb-4"></div>
                    <ul class="list-items list-items-underline">
                        <li><a href="{{ URL::to('about-us/') }}" title="{{getcong('about_title')}}">{{getcong('about_title')}}</a></li>
                        <li><a href="{{ URL::to('privacy-policy/') }}" title="{{getcong('privacy_policy_title')}}">{{getcong('privacy_policy_title')}}</a></li>
                        <li><a href="{{ URL::to('terms-conditions/') }}" title="{{getcong('terms_of_title')}}">{{getcong('terms_of_title')}}</a></li>
                         <li><a href="{{ URL::to('contact/') }}" title="{{getcong('contact_title')}}">{{getcong('contact_title')}}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="footer_item">
                    <h4 class="footer_item_title mb-2">{{trans('words.contact_with_us')}}</h4>
                    <div class="stroke-shape mb-4"></div>
                    <ul class="info-list">
                        <li><span class="fal fa-map-marker-alt icon mr-2"></span>{!!getcong('contact_address')!!}</li>
                        <li><span class="fal fa-phone icon mr-2"></span><a href="#" title="phone">{{getcong('contact_number')}}</a></li>
                        <li><span class="fal fa-envelope icon mr-2"></span><a href="#" title="email">{{getcong('contact_email')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="border-top-gray">
        <div class="copy-right d-flex flex-wrap align-items-center justify-content-between pb-4">
            <p class="copyright_desc py-2">{{getcong('site_copyright')}}</p>
             
        </div>
    </div>
</section>
<!-- ================================
     End Footer Area
================================= -->