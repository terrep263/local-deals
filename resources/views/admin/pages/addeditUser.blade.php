@extends("admin.admin_app")

@section("content")

                <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                               {{ isset($user->id) ? trans('words.edit_user') : trans('words.add_user') }}
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/users') }}">{{trans('words.users')}}</a></li>
                                <li><a class="link-effect" href="">{{ isset($user->id) ? trans('words.edit_user') : trans('words.add_user') }}</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Page Content -->
                <div class="content content-boxed">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div class="block">
                               <div class="block-content block-content-narrow"> 
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
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                                                    {{ Session::get('flash_message') }}
                                                </div>
                                @endif
                                <form action="{{ url('admin/users/adduser') }}" class="form-horizontal padding-15" name="user_form" id="user_form" role="form" method="POST" enctype="multipart/form-data"> 
                @csrf
                <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                  
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.first_name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.last_name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.email')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" value="" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.contact_email')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="contact_email" value="{{ old('contact_email', $user->contact_email ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.website')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="website" value="{{ old('website', $user->website ?? '') }}" class="form-control">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.address')}}</label>
                    <div class="col-sm-9">
                        
                        <textarea name="address" cols="30" rows="5" class="form-control">{{ old('address', $user->address ?? '') }}</textarea>
                    </div>
                </div>

                <hr>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Facebook Url</label>
                    <div class="col-sm-9">
                        <input type="text" name="facebook_url" value="{{ old('facebook_url', $user->facebook_url ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Twitter Url</label>
                    <div class="col-sm-9">
                        <input type="text" name="twitter_url" value="{{ old('twitter_url', $user->twitter_url ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Linkedin Url</label>
                    <div class="col-sm-9">
                        <input type="text" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url ?? '') }}" class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Instagram Url</label>
                    <div class="col-sm-9">
                        <input type="text" name="instagram_url" value="{{ old('instagram_url', $user->instagram_url ?? '') }}" class="form-control">
                    </div>
                </div>
                <hr>
                 
                <div class="form-group">
                    <label for="avatar" class="col-sm-3 control-label">{{trans('words.profile_picture')}}</label>
                    <div class="col-sm-9">
                        <div class="media">
                            <div class="media-left">
                                @if(isset($user->image_icon))
                                 
                                    <img src="{{URL::to('upload/members/'.$user->image_icon)}}" width="100" alt="person">
                                @endif
                                                                
                            </div>
                            <div class="media-body media-middle">
                                <input type="file" name="image_icon" class="filestyle"> 
                            </div>
                        </div>
    
                    </div>
                </div>                  
                <hr>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.expiry_date')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="exp_date" id="datepicker-autoclose" value="{{ old('exp_date', isset($user->exp_date) ? date('m/d/Y',$user->exp_date) : '') }}" class="datepicker form-control" placeholder="mm/dd/yyyy">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.plan')}}</label>
                    <div class="col-sm-9">
                        @php($selectedPlan = old('plan_id', $user->plan_id ?? ''))
                        <select class="form-control" name="plan_id">                               
                            @foreach($plan_list as $plan_data)
                                <option value="{{$plan_data->id}}" @selected((string)$selectedPlan === (string)$plan_data->id)>{{$plan_data->plan_name}}</option>
                            @endforeach                            
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">{{trans('words.status')}}</label>
                    <div class="col-sm-9">
                        @php($statusValue = old('status', $user->status ?? 1))
                        <select class="form-control" name="status">                               
                            <option value="1" @selected((int)$statusValue === 1)>{{trans('words.active')}}</option>
                            <option value="0" @selected((int)$statusValue === 0)>{{trans('words.inactive')}}</option>                            
                        </select>
                    </div>
                </div>

                <hr>
                <div class="form-group">
                    <div class="col-md-offset-3 col-sm-9 ">
                        <button type="submit" class="btn btn-primary">{{trans('words.save')}}</button>
                         
                    </div>
                </div>
                
                </form> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->     

@endsection