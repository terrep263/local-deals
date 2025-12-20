@extends('app')

@section('head_title',trans('words.profile').' | '.getcong('site_name') )

@section('head_url', Request::url())

@section("content")

 
<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{trans('words.profile')}}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li>{{trans('words.profile')}}</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

<!-- ================================
     Start User Details Area
================================= -->
<section class="user-details bg-gray section_item_padding">
    <div class="container">
        

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

           <form action="{{ url('profile') }}" class="" id="myProfile" role="form" method="POST" enctype="multipart/form-data">
               @csrf
           <div class="row">
           <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4 col-12"> 
               <div class="edit-profile-photo mb-4">
                   
                  @if(Auth::user()->image_icon)
                    <img alt="User Photo" src="{{URL::to('upload/members/'.$user->image_icon)}}" class="profile-img mb-4 flex-shrink-0">
                  @else
                    <img src="{{URL::to('assets/images/avatar.jpg')}}" class="profile-img mb-4 flex-shrink-0" alt="profile_img" title="profile pic">
                  @endif
 
                   <div class="file-upload-wrap file-upload-wrap-layout-2 media-body align-self-center">
                       <input type="file" name="user_icon" class="multi file-upload-input with-preview w-100" maxlength="1">
                       <span class="file-upload-text w-100 text-center"><i class="fal fa-upload mr-2"></i>Upload Photo</span>
                   </div>
               </div>
           </div>
           <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8 col-12">
               <div class="row">
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.first_name')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.last_name')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.email')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="email" value="{{ old('email', $user->email) }}" required>
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.password')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="password" name="password" value="">
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.phone')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}">
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.address')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="address" value="{{ old('address', $user->address) }}">
                       </div>
                   </div>

                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.contact_email')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="contact_email" value="{{ old('contact_email', $user->contact_email) }}" placeholder="">
                       </div>
                   </div> 
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">{{trans('words.website')}}</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="website" value="{{ old('website', $user->website) }}" placeholder="www.website.com">
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">Facebook</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="facebook_url" value="{{ old('facebook_url', $user->facebook_url) }}" placeholder="">
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">Twitter</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="twitter_url" value="{{ old('twitter_url', $user->twitter_url) }}" placeholder="">
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">Instagram</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="instagram_url" value="{{ old('instagram_url', $user->instagram_url) }}" placeholder="">
                       </div>
                   </div>
                   <div class="col-lg-6 col-md-6">
                       <label class="label-text">Linkedin</label>
                       <div class="form-group">
                           <input class="form-control form--control" type="text" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" placeholder="">
                       </div>
                   </div>
                   <div class="col-lg-12">
                       <button class="primary_item_btn mt-2 border-0" type="submit">{{trans('words.save_changes')}}</button>
                   </div>
               </div>
           </div>

           </form>   

        </div>      
    </div>
</section>
<!-- ================================
     End User Details Area
================================= -->

 
@endsection