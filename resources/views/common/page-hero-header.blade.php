{{-- 
    LOCKED COMPONENT - DO NOT SEPARATE INTO MULTIPLE SECTIONS
    Combined: Full-width background + Overlay + Navigation + Title + Breadcrumb
    ALL IN ONE SECTION
--}}
<section class="page-hero-header" style="background-image: url('{{ $bgImage ?? asset('assets/images/bread-bg.jpg') }}');">
    <div class="page-hero-overlay"></div>
    
    {{-- Navigation --}}
    <nav class="page-hero-nav">
        <div class="container-fluid">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ URL::to('/') }}" title="logo">
                        @if(getcong('site_logo'))
                            <img src="{{ URL::asset('upload/'.getcong('site_logo')) }}" alt="logo" title="logo">
                        @else
                            <img src="{{ URL::asset('site_assets/images/logo_white.png') }}" alt="logo" title="logo">
                        @endif
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li><a class="{{classActivePathSite('/')}}" href="{{ URL::to('/') }}">{{trans('words.home')}}</a></li>
                    <li><a class="{{classActivePathSite('categories')}}" href="{{ URL::to('categories') }}">{{trans('words.categories')}}</a></li>
                    <li><a class="{{classActivePathSite('listings')}}" href="{{ URL::to('listings') }}">{{trans('words.listings')}}</a></li>
                    <li><a class="{{classActivePathSite('pricing')}}" href="{{ URL::to('pricing') }}">{{trans('words.pricing')}}</a></li>
                    <li><a class="{{classActivePathSite('about-us')}}" href="{{ URL::to('about-us') }}">{{getcong('about_title')}}</a></li>
                    <li><a class="{{classActivePathSite('contact')}}" href="{{ URL::to('contact') }}">{{getcong('contact_title')}}</a></li>
                </ul>
                
                <div class="nav-right">
                    <a href="{{ URL::to('submit_listing') }}" class="btn btn-primary btn-sm">
                        <span class="fal fa-plus-circle"></span> {{trans('words.add_listing')}}
                    </a>
                    
                    @if(!Auth::check())
                        <a class="btn btn-outline-light btn-sm ml-2" href="{{ URL::to('login/') }}">{{trans('words.login')}}</a>
                    @else
                        <div class="user-dropdown">
                            <button class="user-dropdown-toggle">
                                @if(Auth::User()->image_icon AND file_exists(public_path('upload/members/'.Auth::User()->image_icon)))
                                    <img src="{{ URL::asset('upload/members/'.Auth::User()->image_icon) }}" alt="profile">
                                @else
                                    <img src="{{ URL::asset('assets/images/avatar.jpg') }}" alt="profile">
                                @endif
                            </button>
                            <ul class="user-dropdown-menu">
                                <li><a href="{{ URL::to('dashboard') }}"><i class="fal fa-tachometer"></i> {{trans('words.dashboard')}}</a></li>
                                @if(config('training.enabled') && Auth::user()->usertype != 'Admin')
                                    <li><a href="{{ route('vendor.training.index') }}"><i class="fal fa-graduation-cap"></i> Training</a></li>
                                @endif
                                <li><a href="{{ URL::to('profile') }}"><i class="fal fa-user"></i> {{trans('words.profile')}}</a></li>
                                <li><a href="{{ URL::to('logout') }}"><i class="fal fa-sign-out"></i> {{trans('words.logout')}}</a></li>
                            </ul>
                        </div>
                    @endif
                    
                    <button class="mobile-menu-toggle ml-2">
                        <i class="fal fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    {{-- Page Title + Breadcrumb --}}
    <div class="page-hero-content">
        <div class="container">
            <h1 class="page-hero-title">{{ $title ?? 'Page' }}</h1>
            @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                <ul class="page-hero-breadcrumb">
                    @foreach($breadcrumbs as $label => $url)
                        @if($url)
                            <li><a href="{{ $url }}">{{ $label }}</a></li>
                        @else
                            <li class="active">{{ $label }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    
    {{-- Mobile Menu (hidden by default) --}}
    <div class="mobile-menu-panel">
        <button class="mobile-menu-close"><i class="fal fa-times"></i></button>
        <ul class="mobile-menu-list">
            <li><a href="{{ URL::to('/') }}">{{trans('words.home')}}</a></li>
            <li><a href="{{ URL::to('categories') }}">{{trans('words.categories')}}</a></li>
            <li><a href="{{ URL::to('listings') }}">{{trans('words.listings')}}</a></li>
            <li><a href="{{ URL::to('pricing') }}">{{trans('words.pricing')}}</a></li>
            <li><a href="{{ URL::to('about-us') }}">{{getcong('about_title')}}</a></li>
            <li><a href="{{ URL::to('contact') }}">{{getcong('contact_title')}}</a></li>
        </ul>
        <div class="mobile-menu-cta">
            <a href="{{ URL::to('submit_listing') }}" class="btn btn-primary btn-block">{{trans('words.add_listing')}}</a>
        </div>
    </div>
</section>
