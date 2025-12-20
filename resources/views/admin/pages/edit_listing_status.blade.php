@extends("admin.admin_app")

@section("content")

  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                               Edit Listing Status
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a class="link-effect" href="{{ URL::to('admin/locations') }}">Edit Listing Status</a></li>
                                 
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
                                                                <form action="{{ url('admin/listings/save_status_listing') }}" class="form-horizontal padding-15" name="status_form" id="status_form" role="form" method="POST" enctype="multipart/form-data"> 
                                                                        @csrf
                                    <input type="hidden" name="listing_id" value="{{$listing_id}}">
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Select Plan</label>
                                          <div class="col-sm-9">
                                            <select class="form-control" id="plan_id" name="plan_id" required>
                                              @foreach($subscription_plan as $i => $plan_obj) 
                                                 
                                                                                                <option value="{{$plan_obj->id}}" @selected(old('plan_id') == $plan_obj->id)>{{$plan_obj->plan_name.' - $'.$plan_obj->plan_price.' '.__('messages.for').' '.$plan_obj->plan_exp_days.' '.__('messages.days')}}</option> 
                                                                   
                                              @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-sm-9 ">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                             
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