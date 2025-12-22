@extends('app')

@section('head_title',trans('words.register').' | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', trans('words.register'))
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => trans('words.register'), 'url' => '']]))

@section("content")

@include('common.page-hero-header', ['title' => trans('words.register')]) 

 <!-- ================================
     Start Sign Up Area
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


                 <form action="{{ url('register') }}" class="card mb-0" id="register" role="form" method="POST">
                     @csrf
                <div class="card-body">
                     
                     
                    <div class="form-group">
                        <label class="label-text">{{trans('words.first_name')}}</label>
                        <input class="form-control form--control pl-3" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="{{trans('words.first_name')}}">
                    </div>
                    <div class="form-group">
                        <label class="label-text">{{trans('words.last_name')}}</label>
                        <input class="form-control form--control pl-3" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="{{trans('words.last_name')}}">
                    </div>
                    <div class="form-group">
                        <label class="label-text">{{trans('words.email')}}</label>
                        <input class="form-control form--control pl-3" type="email" name="email" value="{{ old('email') }}" placeholder="{{trans('words.email')}}">
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
                    <div class="form-group">
                        <label class="label-text">{{trans('words.confirm_password')}}</label>
                        <div class="position-relative">
                            <input class="form-control form--control pl-3 password-field" type="password" name="password_confirmation" placeholder="{{trans('words.confirm_password')}}">
                            <a href="javascript:void(0)" class="position-absolute top-0 right-0 h-100 btn toggle-password" title="toggle show/hide password">
                                <i class="far fa-eye eye-on"></i>
                                <i class="far fa-eye-slash eye-off"></i>
                            </a>
                        </div>                        
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="privacyCheckbox" name="accept_terms" value="1" {{ old('accept_terms', '1') ? 'checked' : '' }} required>
                            <label class="custom-control-label" for="privacyCheckbox">
                            {{trans('words.by_signing_up')}} <a href="{{ URL::to('privacy-policy/') }}" class="btn-link" target="_blank">{{getcong('privacy_policy_title')}}</a> {{trans('words.and')}} <a href="{{ URL::to('terms-conditions/') }}" class="btn-link" target="_blank">{{getcong('terms_of_title')}}</a>.
                            </label>
                        </div>                        
                    </div>
                    <button class="primary_item_btn border-0 w-100" type="submit">{{trans('words.register')}}</button>
                    <p class="mt-3 text-center">{{trans('words.already_have_account')}}  <a href="{{ URL::to('login/') }}" class="btn-link">{{trans('words.login')}}</a></p>
                </div>
            </form> 
        </div>
    </div>
</section>
<!-- ================================
     End Sign Up Area
================================= -->

 
@endsection