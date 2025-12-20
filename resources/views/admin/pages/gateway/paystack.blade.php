@extends("admin.admin_app")

@section("content")

  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                                
                            {{trans('words.edit_gateway')}}
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/payment_gateway') }}">{{trans('words.payment_gateway')}}</a></li>
                                <li><a class="link-effect" href="">{{trans('words.edit_gateway')}}</a></li>
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



                                <form action="{{ url('admin/payment_gateway/paystack') }}" class="form-horizontal padding-15" name="category_form" id="category_form" role="form" method="POST" enctype="multipart/form-data">  
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $post_info->id ?? '' }}">
                                    
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Title</label>
                                          <div class="col-sm-9">
                                            <input type="text" name="gateway_name" value="{{ old('gateway_name', $post_info->gateway_name ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                     

                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Paystack Secret Key</label>
                                          <div class="col-sm-9">
                                            <input type="text" name="paystack_secret_key" value="{{ old('paystack_secret_key', $gateway_info->paystack_secret_key ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Paystack Public Key</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="paystack_public_key" value="{{ old('paystack_public_key', $gateway_info->paystack_public_key ?? '') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Payment Mode</label>
                                        
                                        <div class="col-sm-9">
                                                                                             
                                            @php($statusValue = old('status', $post_info->status ?? 0))
                                            <select id="status" class="js-select2 form-control" name="status">                               
                                                <option value="1" @selected((int)$statusValue === 1)>{{trans('words.active')}}</option>
                                                <option value="0" @selected((int)$statusValue === 0)>{{trans('words.inactive')}}</option>                            
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