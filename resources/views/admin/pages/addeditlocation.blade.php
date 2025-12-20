@extends("admin.admin_app")

@section("content")

  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                               {{ isset($location->id) ? trans('words.edit_location') : trans('words.add_location') }}
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/locations') }}">{{trans('words.locations')}}</a></li>
                                <li><a class="link-effect" href="">{{ isset($location->id) ? trans('words.edit_location') : trans('words.add_location') }}</a></li>
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
                                <form action="{{ url('admin/locations/addlocation') }}" class="form-horizontal padding-15" name="location_form" id="location_form" role="form" method="POST" enctype="multipart/form-data"> 
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $location->id ?? '' }}">
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">{{trans('words.location_name')}}</label>
                                          <div class="col-sm-9">
                                            <input type="text" name="location_name" value="{{ old('location_name', $location->location_name ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">{{trans('words.location_slug')}}</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="location_slug" value="{{ old('location_slug', $location->location_slug ?? '') }}" class="form-control">
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