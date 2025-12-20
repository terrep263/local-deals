@extends("admin.admin_app")

@section("content")

				<!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                            {{trans('words.users')}}
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/dashboard') }}">{{trans('words.dashboard')}}</a></li>
                                <li><a class="link-effect" href="">{{trans('words.users')}}</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- END Page Header --> 
				 <!-- Page Content -->
                <div class="content">
                    <!-- Dynamic Table Full -->
                    <div class="block">
                        <div class="block-header" style="padding-bottom:0">                            
                            <a class="pull-right btn btn-primary push-5-r push-10" href="{{URL::to('admin/users/adduser')}}"><i class="fa fa-plus"></i> {{trans('words.add_user')}}</a>
                        </div>
                        <div class="block-content">
                            @if(Session::has('flash_message'))
                                <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    {{ Session::get('flash_message') }}
                                </div>
                            @endif
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                            <table class="table table-bordered table-striped users-dataTable-full">
                                <thead>
                                    <tr>

                                        <th>{{trans('words.first_name')}}</th>
						                <th>{{trans('words.last_name')}}</th>
						                <th>{{trans('words.email')}}</th>
						                <th>{{trans('words.phone')}}</th> 						                
                                        <th class="text-center" style="width: 10%;">{{trans('words.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($allusers as $i => $users)
                                    <tr>
                                        <td>{{ $users->first_name }}</td>
						                <td>{{ $users->last_name }}</td>
						                <td>{{ $users->email}}</td>
						                <td>{{ $users->mobile}}</td>
                                        <td class="text-center">
                                             
                                                <a href="{{ url('admin/users/adduser/'.$users->id) }}" class="btn btn-xs btn-success"  data-toggle="tooltip" title="{{trans('words.edit')}}"><i class="fa fa-pencil"></i></a>

                                                 <a href="{{ url('admin/users/delete/'.$users->id) }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" title="{{trans('words.remove')}}" onclick="return confirm('{{trans('words.remove_cofirm_msg')}}')"><i class="fa fa-times"></i></a>
                                              
                                    </td>
                                        
                                    </tr>
                                   @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                    
                </div>
                <!-- END Page Content -->

@endsection