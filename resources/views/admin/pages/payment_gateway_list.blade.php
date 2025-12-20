@extends("admin.admin_app")

@section("content")

  
 <!-- Page Header -->
 <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                {{trans('words.payment_gateway')}}
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ URL::to('admin/dashboard') }}">{{trans('words.dashboard')}}</a></li>
                    <li><a class="link-effect" href="">{{trans('words.payment_gateway')}}</a></li>
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
                <div class="clearfix"></div>
                @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('flash_message') }}
                    </div>
                @endif
            </div> 
            <div class="block-content">
                
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{trans('words.gateway_name')}}</th>
                            <th class="text-center">{{trans('words.status')}}</th>
                            <th class="text-center">{{trans('words.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $i => $data)
                        <tr>
                            <td class="text-center font-w600">{{ stripslashes($data->gateway_name) }}</td> 
                            <td class="text-center">@if($data->status=="1")<span class="badge badge-success">{{trans('words.active')}}</span> @else<span class="badge badge-danger"> {{trans('words.inactive')}}</span>@endif</td>
                            <td class="text-center">
                              <a href="{{ url('admin/payment_gateway/edit/'.$data->id) }}" class="btn btn-xs btn-success"  data-toggle="tooltip" title="{{trans('words.edit')}}"><i class="fa fa-pencil"></i></a>
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