@extends("admin.admin_app")

@section("content")
 
  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                            {{trans('words.locations')}}  
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/dashboard') }}">{{trans('words.dashboard')}}</a></li>
                                <li><a class="link-effect" href="">{{trans('words.locations')}}</a></li>
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
                            <a class="pull-right btn btn-primary push-5-r push-10" href="{{URL::to('admin/locations/addlocation')}}"><i class="fa fa-plus"></i> {{trans('words.add_location')}}</a>
                        </div>
                        <div class="block-content">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                            <table class="table table-bordered table-striped cat-dataTable-full">
                                <thead>
                                    <tr>

                                        <th>{{trans('words.location_name')}}</th>
                                        <th class="hidden-xs">{{trans('words.location_slug')}}</th>      
                                        <th class="text-center" style="width: 10%;">{{trans('words.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($locations as $i => $location)
                                    <tr>
                                        <td class="font-w600">{{ $location->location_name }}</td>
                                        <td class="font-w600">{{ $location->location_slug }}</td>
                                        <td class="text-center">
                                             
                                                <a href="{{ url('admin/locations/addlocation/'.$location->id) }}" class="btn btn-xs btn-success"  data-toggle="tooltip" title="{{trans('words.edit')}}"><i class="fa fa-pencil"></i></a>

                                                 <a href="{{ url('admin/locations/delete/'.$location->id) }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" title="{{trans('words.remove')}}" onclick="return confirm('{{trans('words.remove_cofirm_msg')}}')"><i class="fa fa-times"></i></a>
                                             
                                        
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