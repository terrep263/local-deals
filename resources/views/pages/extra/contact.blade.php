@extends('app')

@section('head_title', getcong('contact_title').' | '.getcong('site_name') )

@section('head_url', Request::url())

@section("content")

 
<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/about-hero.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{getcong('contact_title')}}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}" title="Home">{{trans('words.home')}}</a></li>
                <li>{{getcong('contact_title')}}</li>
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

        @if (count($errors) > 0)
            <div class="alert alert-danger mb-3">
            
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('flash_message'))

        <div class="alert alert-success mb-3" role="alert">{{ Session::get('flash_message') }}</div>

        @endif   

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('words.contact_information')}}</h4>
                        <hr class="border-top-gray">
                        <ul class="list-items mb-4">
                            <li><span class="fal fa-map-marker-alt icon-element icon-element-sm shadow-sm mr-2 font-size-14"></span> {!!getcong('contact_address')!!}</li>
                            <li><span class="fal fa-envelope icon-element icon-element-sm shadow-sm mr-2 font-size-14"></span> <a href="#" title="email">{{getcong('contact_email')}}</a></li>
                            <li><span class="fal fa-phone icon-element icon-element-sm shadow-sm mr-2 font-size-14"></span> {{getcong('contact_number')}}</li>
                        </ul>
                          
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                 <form action="{{ url('contact_send') }}" class="contact-form card mb-0" id="contact_form" role="form" method="POST">
                    @csrf   
                    <div class="card-body">
                        <h4 class="card-title">{{trans('words.get_in_touch')}}</h4>
                        <hr class="border-top-gray">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label class="label-text">{{trans('words.name')}}</label>
                                <input id="name" class="form-control form--control pl-3" type="text" name="name" value="{{ old('name') }}" placeholder="{{trans('words.enter_name')}}" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="label-text">{{trans('words.email')}}</label>
                                <input id="email" class="form-control form--control pl-3" type="email" name="email" value="{{ old('email') }}" placeholder="{{trans('words.enter_email')}}" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="label-text">{{trans('words.subject')}}</label>
                                <input id="subject" class="form-control form--control pl-3" type="text" name="subject" value="{{ old('subject') }}" placeholder="{{trans('words.enter_subject')}}" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="label-text">{{trans('words.message')}}</label>
                                <textarea id="message" class="form-control form--control pl-3" rows="4" name="message" placeholder="{{trans('words.message')}}...">{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <button id="send-message-btn" class="primary_item_btn border-0" type="submit">{{trans('words.send_message')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- ================================
     End Contact Area
================================= -->

 
@endsection