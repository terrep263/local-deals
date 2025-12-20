@extends("admin.admin_app")

@section("content")
 
  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                            {{trans('words.plan')}}  
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/dashboard') }}">{{trans('words.dashboard')}}</a></li>
                                <li><a class="link-effect" href="">{{trans('words.plan')}}</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Page Content -->
                <div class="content">
                    <!-- Dynamic Table Full -->
                    <div class="block">
                        <div class="block-header" style="padding-bottom: 0;">                            
                            <a class="pull-right btn btn-primary push-5-r push-10" href="{{ url('admin/plan/add') }}"><i class="fa fa-plus"></i> {{trans('words.add_plan')}}</a>
                          
                            <div class="clearfix"></div>

                          @if(Session::has('flash_message'))
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                                        {{ Session::get('flash_message') }}
                                    </div>
                          @endif
                        </div> 
                        <div class="block-content">
                            
                            <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                         
                                        <th class="text-center">{{trans('words.plan_name')}}</th>
                                        <th class="text-center">{{trans('words.price')}}</th>
                                        <th class="text-center">{{trans('words.duration')}}</th>
                                        <th class="text-center">{{trans('words.listing_limits')}}</th>
                                        <th class="text-center">{{trans('words.recommended')}}</th>   
                                        <th class="text-center">{{trans('words.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($subscription_plan as $i => $plan_info)
                                    <tr>
                                        <td class="text-center font-w600">{{ $plan_info->plan_name }}</td>                                        
                                        <td class="text-center font-w600">{{html_entity_decode(getCurrencySymbols(getcong('currency_code')))}}{{ $plan_info->plan_price }}</td>                                         
                                        <td class="text-center font-w600">{{ App\Models\SubscriptionPlan::getPlanDuration($plan_info->id) }}</td>
                                        <td class="text-center font-w600">{{ $plan_info->plan_listing_limit }}</td>
                                        <td class="text-center">@if($plan_info->plan_recommended=="1")<span class="badge badge-success">{{trans('words.yes')}}</span> @else<span class="badge badge-danger">{{trans('words.no')}}</span>@endif</td>
                                         
                                        <td class="text-center">
                                          
                                                <a href="{{ url('admin/plan/edit/'.$plan_info->id) }}" class="btn btn-xs btn-success"  data-toggle="tooltip" title="{{trans('words.edit')}}"><i class="fa fa-pencil"></i></a>

                                                <a href="{{ url('admin/plan/delete/'.$plan_info->id) }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" title="{{trans('words.remove')}}" onclick="return confirm('{{trans('words.remove_cofirm_msg')}}')"><i class="fa fa-times"></i></a>
                                                 
                                    </td>
                                        
                                    </tr>
                                   @endforeach
                                    
                                </tbody>
                            </table>
                            </div>
                         </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                    
                </div>
                <!-- END Page Content -->

@endsection