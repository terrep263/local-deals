@extends('app')

@section('head_title',trans('words.login').' | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', trans('words.login'))
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => trans('words.login'), 'url' => '']]))

@section("content")

@include('common.page-hero-header') 

<!-- ================================
     Start Login Area
================================= -->
<section class="contact-area bg-gray section_item_padding">
    <div class="container">
        <div class="col-lg-5 mx-auto p-0">

                @if (count($errors) > 0)
                  <div class="alert alert-danger">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                      <ul style="list-style: none;">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
                @endif

                @if(Session::has('flash_message'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                     {{ Session::get('flash_message') }}
                   </div>                     
                @endif
                
                @if(Session::has('error_flash_message'))
                  <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                     {{ Session::get('error_flash_message') }}
                   </div>                     
                @endif

            <form action="{{ url('login') }}" class="card mb-0" id="login" role="form" method="POST">
                @csrf
                <div class="card-body">
                    
                    
                    @if(getcong('facebook_login') OR getcong('google_login')) 
                    <div class="text-center">
                        <h4 class="font-weight-semi-bold mb-1">{{trans('words.login_to_account')}}</h4>    
                    
                        <p class="card-text">{{trans('words.with_social')}}</p>
                        <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 mb-2">
                        @if(getcong('facebook_login'))    
                        <a href="{{ url('auth/facebook') }}" class="primary_item_btn flex-grow-1 mx-1 my-1 bg-5"><i class="fab fa-facebook-f mr-2"></i>Facebook</a>
                        @endif
                        @if(getcong('google_login'))    
                            <a href="{{ url('auth/google') }}" class="primary_item_btn flex-grow-1 mx-1 my-1"><i class="fab fa-google mr-2"></i>Google</a>
                        @endif    
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <hr class="border-top-gray flex-grow-1">
                        <span class="mx-1 text-uppercase">{{trans('words.or')}}</span>
                        <hr class="border-top-gray flex-grow-1">
                    </div>
                    @else
                    <div class="text-center">
                        <h4 class="font-weight-semi-bold mb-1">{{trans('words.login_to_account')}}</h4>
                        <p class="card-text">&nbsp;</p>
                    </div>    
                    @endif
                    <div class="form-group">
                        <label class="label-text">{{trans('words.email')}}</label>
                        <input class="form-control form--control pl-3" type="text" name="email" value="{{ old('email') }}" placeholder="{{trans('words.email')}}">
                    </div>
                    <div class="form-group">
                        <label class="label-text">{{trans('words.password')}}</label>
                        <div class="position-relative">
                            <input class="form-control form--control pl-3 password-field" type="password" name="password" placeholder="{{trans('words.password')}}">
                            <a href="javascript:void(0)" class="position-absolute top-0 right-0 h-100 btn toggle-password" title="toggle show/hide password">
                                <i class="far fa-eye eye-on"></i>
                                <i class="far fa-eye-slash eye-off"></i>
                            </a>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="remember">{{trans('words.remember_me')}}</label>
                        </div>
                        <a href="{{ URL::to('password/email/') }}" class="btn-link">{{trans('words.forgot_pass_text')}}</a>
                    </div>
                    <button class="primary_item_btn border-0 w-100" type="submit">{{trans('words.login_now')}}</button>
                    <p class="mt-3 text-center">{{trans('words.dont_have_account')}} <a href="{{ URL::to('register/') }}" class="btn-link">{{trans('words.register')}}</a></p>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- ================================
     End Login Area
================================= -->

 
@endsection