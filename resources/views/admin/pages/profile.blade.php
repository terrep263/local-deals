@extends("admin.admin_app")

@section("content")

 <!-- Page Header -->
<div class="content bg-image" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15 clearfix">
        <div class="push-15-r pull-left animated fadeIn">
            
            @if(Auth::user()->image_icon)                                 
                <img src="{{URL::to('upload/members/'.Auth::user()->image_icon)}}" alt="Avatar" class="img-avatar img-avatar-thumb">                            
            @else
                
            <img src="{{ URL::asset('admin_assets/img/avatars/avatar10.jpg') }}" alt="Avatar" class="img-avatar img-avatar-thumb"/>
            
            @endif
        </div>
        <h1 class="h2 text-white push-5-t animated zoomIn">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
        <h2 class="h5 text-white-op animated zoomIn">{{ Auth::user()->usertype }}</h2>
    </div>
</div>
<!-- END Page Header -->



<!-- Page Content -->
<div class="content content-boxed">
    <div class="row">
        <div class="col-sm-12 col-lg-12">

             <!-- Block Tabs Alternative Style -->
            <div class="block">
                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                    <li class="active">
                        <a href="#btabs-alt-static-profile">{{trans('words.profile')}}</a>
                    </li>
                    <li>
                        <a href="#btabs-alt-static-password">{{trans('words.password')}}</a>
                    </li>
                     
                </ul>
                <div class="block-content tab-content">


                    <div class="col-lg-10 tab-pane active" id="btabs-alt-static-profile">

                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                 @if(Session::has('flash_message'))
                        <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ Session::get('flash_message') }}
                        </div>
                    @endif
                        <form action="{{ url('admin/profile') }}" class="form-horizontal padding-15" name="account_form" id="account_form" role="form" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="avatar" class="col-sm-3 control-label">{{trans('words.profile_picture')}}</label>
                            <div class="col-sm-9">
                                <div class="media">
                                    <div class="media-left">
                                        @if(Auth::user()->image_icon)
                                         
                                            <img src="{{URL::to('upload/members/'.Auth::user()->image_icon)}}" class="user_person" alt="person">
                                        
                                        @else
                                        
                                            <img src="{{ URL::asset('admin_assets/images/guy.jpg') }}" alt="person" class="img-circle" width="80"/>
                                    
                                        @endif
                                         
                                                                         
                                    </div>
                                    <div class="media-body media-middle">
                                        <input type="file" name="user_icon" class="filestyle">
                                        <small class="text-muted bold">Size 200x200px</small>
                                    </div>
                                </div>
            
                            </div>
                        </div>
                         
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.first_name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.last_name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.email')}}</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.phone')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="mobile" value="{{ old('mobile', Auth::user()->mobile) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.contact_email')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="contact_email" value="{{ old('contact_email', Auth::user()->contact_email) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.website')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="website" value="{{ old('website', Auth::user()->website) }}" class="form-control">
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.address')}}</label>
                            <div class="col-sm-9">
                                
                                <textarea name="address" cols="30" rows="5" class="form-control">{{ old('address', Auth::user()->address) }}</textarea>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Facebook Url</label>
                            <div class="col-sm-9">
                                <input type="text" name="facebook_url" value="{{ old('facebook_url', Auth::user()->facebook_url) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Twitter Url</label>
                            <div class="col-sm-9">
                                <input type="text" name="twitter_url" value="{{ old('twitter_url', Auth::user()->twitter_url) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Linkedin Url</label>
                            <div class="col-sm-9">
                                <input type="text" name="linkedin_url" value="{{ old('linkedin_url', Auth::user()->linkedin_url) }}" class="form-control">
                            </div>
                        </div>                                         
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Instagram Url</label>
                            <div class="col-sm-9">
                                <input type="text" name="instagram_url" value="{{ old('instagram_url', Auth::user()->instagram_url) }}" class="form-control">
                            </div>
                        </div> 
                        
                        <hr>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-sm-9 ">
                                <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                 
                            </div>
                        </div>

                    </form>
                    </div>
                    <div class="col-lg-10 tab-pane" id="btabs-alt-static-password">
                       <form action="{{ url('admin/profile_pass') }}" class="form-horizontal padding-15" name="pass_form" id="pass_form" role="form" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.new_password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" value="" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">{{trans('words.confirm_password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" value="" class="form-control" value="">
                            </div>
                        </div>
                         
                        <hr>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-sm-9 ">
                                <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                            </div>
                        </div>

                    </form> 
                    </div>
                    
                </div>
            </div>
            <!-- END Block Tabs Alternative Style -->
        </div>
        
    </div>
</div>
<!-- END Page Content -->
@endsection