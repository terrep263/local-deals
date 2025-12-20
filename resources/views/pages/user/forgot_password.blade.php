@extends('app')

@section('head_title', trans('words.forgot_password').' | '.getcong('site_name') )

@section('head_url', Request::url())

@section('content')
 

<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{trans('words.forgot_password')}}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li>{{trans('words.forgot_password')}}</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= -->


 <!-- ================================
     Start Contact Area
================================= -->
<section class="contact-area bg-gray section_item_padding">
    <div class="container">
        <div class="col-lg-5 mx-auto p-0">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">                         
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif   

                @if(Session::has('flash_message'))
                          <div class="alert alert-success">
                         
                              {{ Session::get('flash_message') }}
                          </div>
                @endif
                @if(Session::has('error_flash_message'))
                          <div class="alert alert-danger">                          
                              {{ Session::get('error_flash_message') }}
                          </div>
                @endif

             <form action="{{ url('password/email') }}" class="card mb-0" id="forget_pass_form" role="form" method="POST">
                @csrf 
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 class="font-weight-semi-bold mb-1">{{trans('words.forgot_password')}}</h4>
                    </div>
                    <div class="form-group">
                        <label class="label-text">{{trans('words.email')}}</label>
                        <input class="form-control form--control pl-3" type="text" name="email" value="{{ old('email') }}" placeholder="{{trans('words.email')}}" required>
                    </div>
                    <button class="primary_item_btn border-0 w-100" type="submit">{{trans('words.reset_password')}}</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- ================================
     End Contact Area
================================= -->
 
@endsection