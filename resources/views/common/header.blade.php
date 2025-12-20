<!-- ================================
     Start Header Area
================================= -->
<header id="site-header" class="nav-header-area">
    <div class="main-nav-header py-3">
        <div class="container-fluid">
            <div class="nav-main-nav-header-wrap">
                <div class="logo">
                 <a href="{{ URL::to('/') }}" title="logo">   
                  @if(getcong('site_logo'))
                    <img src="{{ URL::asset('upload/'.getcong('site_logo')) }}" alt="logo" style="max-width: 300px; height: auto;" title="logo">
                  @else
                    <img src="{{ URL::asset('site_assets/images/logo_white.png') }}" alt="logo" style="max-width: 300px; height: auto;" title="logo">
                  @endif    
                  </a>
                     
                </div>
                <nav class="main-menu ml-auto">
                    <ul>
                        <li><a class="{{classActivePathSite('/')}}" href="{{ URL::to('/') }}" title="{{trans('words.home')}}">{{trans('words.home')}}</a></li>
                        <li><a class="{{classActivePathSite('categories')}}" href="{{ URL::to('categories') }}" title="{{trans('words.categories')}}">{{trans('words.categories')}}</a></li>
                        <li><a class="{{classActivePathSite('listings')}}" href="{{ URL::to('listings') }}" title="{{trans('words.listings')}}">{{trans('words.listings')}}</a></li>
                        <li><a class="{{classActivePathSite('pricing')}}" href="{{ URL::to('pricing') }}" title="{{trans('words.pricing')}}">{{trans('words.pricing')}}</a></li>
                         
                        <li><a class="{{classActivePathSite('about-us')}}" href="{{ URL::to('about-us') }}" title="{{getcong('about_title')}}">{{getcong('about_title')}}</a></li>
                        <li><a class="{{classActivePathSite('contact')}}" href="{{ URL::to('contact') }}" title="{{getcong('contact_title')}}">{{getcong('contact_title')}}</a></li>

                         
                    </ul>
                </nav>
                <div class="nav-right-content ml-auto d-flex align-items-center">
                    <div class="author-access-list mr-3">
                        <a href="{{ URL::to('submit_listing') }}" class="primary_item_btn btn btn-primary" title="{{trans('words.add_listing')}}"><span class="fal fa-plus-circle"></span>{{trans('words.add_listing')}}</a>                         
                    </div>

                    @if(!Auth::check())
                    <a class="primary_item_btn btn btn-primary" href="{{ URL::to('login/') }}" title="{{trans('words.login')}}">{{trans('words.login')}}</a>
                    @endif

                    @if(Auth::check())
                    <div class="user-menu">
					  <div class="user-name">
						<span>
                            @if(Auth::User()->image_icon AND file_exists(public_path('upload/members/'.Auth::User()->image_icon)))
                            <img src="{{ URL::asset('upload/members/'.Auth::User()->image_icon) }}" alt="profile_img" title="profile img" id="login-user-pic">
                            @else
                            <img src="{{ URL::asset('assets/images/avatar.jpg') }}" alt="profile_img" title="profile img" id="login-user-pic">          
                            @endif
                            
                        </span>
					  </div>
					  <ul class="content-user" style="opacity: 0; visibility: hidden;">				
						  <li><a href="{{ URL::to('dashboard') }}" title="dashboard"><i class="fal fa-tachometer"></i>{{trans('words.dashboard')}}</a></li>
						  @if(config('training.enabled') && Auth::user()->usertype != 'Admin')
						  <li><a href="{{ route('vendor.training.index') }}" title="training"><i class="fal fa-graduation-cap"></i>Training</a></li>
						  @endif
						  <li><a href="{{ URL::to('profile') }}" title="profile"><i class="fal fa-user"></i>{{trans('words.profile')}}</a></li>
						  <li><a href="{{ URL::to('logout') }}" title="logout"><i class="fal fa-sign-out"></i>{{trans('words.logout')}}</a></li>						  
					  </ul>
				    </div>
                    @endif

                    <div class="side-menu-open ml-2"><i class="fal fa-bars"></i></div>

                </div>
            </div>
        </div>
    </div>
    <div class="off-canvas">
        <div class="off-canvas-close icon-element icon-element-sm shadow-sm">
            <i class="fal fa-times"></i>
        </div>
        <ul class="off-canvas-menu">
            <li><a class="{{classActivePathSite('/')}}" href="{{ URL::to('/') }}" title="{{trans('words.home')}}">{{trans('words.home')}}</a></li>
            <li><a class="{{classActivePathSite('categories')}}" href="{{ URL::to('categories') }}" title="{{trans('words.categories')}}">{{trans('words.categories')}}</a></li>
            <li><a class="{{classActivePathSite('listings')}}" href="{{ URL::to('listings') }}" title="{{trans('words.listings')}}">{{trans('words.listings')}}</a></li>
            <li><a class="{{classActivePathSite('pricing')}}" href="{{ URL::to('pricing') }}" title="{{trans('words.pricing')}}">{{trans('words.pricing')}}</a></li>  
                        
            <li><a class="{{classActivePathSite('about-us')}}" href="{{ URL::to('about-us') }}" title="{{getcong('about_title')}}">{{getcong('about_title')}}</a></li>
            <li><a class="{{classActivePathSite('contact')}}" href="{{ URL::to('contact') }}" title="{{getcong('contact_title')}}">{{getcong('contact_title')}}</a></li>
 
        </ul>
        <div class="mt-4 text-center">
            <a href="{{ URL::to('submit_listing') }}" class="primary_item_btn btn btn-primary" title="{{trans('words.add_listing')}}"><span class="fal fa-plus-circle"></span>{{trans('words.add_listing')}}</a>            
        </div>
    </div>
</header>
<!-- ================================
     End Header Area
================================= -->