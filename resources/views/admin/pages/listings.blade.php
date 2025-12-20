@extends("admin.admin_app")

@section("content")
 
  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                            {{trans('words.listings')}}  
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/dashboard') }}">{{trans('words.dashboard')}}</a></li>
                                <li><a class="link-effect" href="">{{trans('words.listings')}} </a></li>
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
                            <div class="col-lg-3 col-md-3" style="padding-left:0">
                                <select name="listing_status" id="listing_status" class="form-control">
                                    <option value="0">--- {{trans('words.filter_listing')}} ---</option>
                                    <option value="?listing_status=pending">{{trans('words.pending')}}</option>
                                    <option value="?listing_status=featured">{{trans('words.featured')}}</option>
                                 </select>
                            </div> 
                            <div class="col-lg-4 col-md-6" style="padding-left:0;display: flex;">
                                <form action="{{ url('admin/listings') }}" class="" id="search" role="form" method="GET">
                                            <input type="text" class="span8 form-control" name="s" value="{{ request('s') }}" placeholder="{{trans('words.search_by_title_address')}}" style="float: left;display: inline;width: 70%;">
                                            <button type="submit" class="btn btn-primary">{{trans('words.search')}}</button>
                                </form>
                            </div>                          
                            <a class="pull-right btn btn-primary push-5-r push-10" href="{{URL::to('submit_listing')}}" target="_blank"><i class="fa fa-plus"></i> {{trans('words.add_listing')}}</a>
                          
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
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{trans('words.id')}}</th>
                                        <th class="text-center">{{trans('words.user')}}</th>
                                        <th class="text-center">{{trans('words.category')}}</th>
                                        <th class="text-center">{{trans('words.sub_category')}}</th>                                         
                                        <th class="text-center">{{trans('words.title')}}</th>
                                         <th class="text-center">{{trans('words.featured')}}</th>
                                        <th class="text-center">{{trans('words.status')}}</th>
                                           
                                        <th class="text-center" style="width: 10%;">{{trans('words.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($listings as $i => $listing)
                                    <tr>
                                        <td class="text-center">{{$listing->id}}</td>
                                        <td class="text-center">{{ \App\Models\User::getUserFullname($listing->user_id) }}</td>
                                        <td class="text-center">{{$listing->category_name}}</td>
                                        <td class="text-center">{{$listing->sub_category_name}}</td>
                                        <td class="text-center font-w600">{{$listing->title}}</td>
                                         
                                        <td class="text-center">
                                            @if($listing->featured_listing=='0')
                                            <a href="{{URL::to('admin/listings/featured_listing/'.$listing->id.'/1')}}" data-toggle="tooltip" title="{{trans('words.set_featured')}}"><i class="si si-close text-danger" style="font-size:20px;"></i></a>
                                            @else
                                            <a href="{{URL::to('admin/listings/featured_listing/'.$listing->id.'/0')}}" data-toggle="tooltip" title="{{trans('words.unset_featured')}}"><i class="si si-check text-success" style="font-size:20px;"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($listing->status=='0')
                                            <a href="{{URL::to('admin/listings/status_listing/'.$listing->id.'/1')}}" data-toggle="tooltip" title="{{trans('words.published')}}?"><span class="label label-danger">{{trans('words.pending')}}</span></a>
                                            @else
                                            <a href="{{URL::to('admin/listings/status_listing/'.$listing->id.'/0')}}" data-toggle="tooltip" title="{{trans('words.pending')}}?"><span class="label label-success">{{trans('words.published')}}</span></a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                             
                                                <a href="{{URL::to('edit_listing/'.$listing->id)}}" class="btn btn-xs btn-success"  data-toggle="tooltip" title="{{trans('words.edit')}}" target="_blank"><i class="fa fa-pencil"></i></a>

                                                 <a href="{{URL::to('admin/listings/delete_listing/'.$listing->id)}}" class="btn btn-xs btn-danger"  data-toggle="tooltip" title="{{trans('words.remove')}}" onclick="return confirm('{{trans('words.remove_cofirm_msg')}}')"><i class="fa fa-times"></i></a>
                                             
                                        
                                       </td>
                                        
                                    </tr>
                                   @endforeach
                                    
                                </tbody>
                            </table>

                            @include('admin.pagination', ['paginator' => $listings]) 
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                    
                </div>
                <!-- END Page Content -->

@endsection