@extends("admin.admin_app")

@section("content")

  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                                
                               {{ isset($subcat->id) ? trans('words.edit_sub_category') : trans('words.add_sub_category') }}
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/subcategories') }}">{{trans('words.sub_categories')}}</a></li>
                                <li><a class="link-effect" href="">{{ isset($subcat->id) ? trans('words.edit_sub_category') : trans('words.add_sub_category') }}</a></li>
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



                                <form action="{{ url('admin/subcategories/addsubcategory') }}" class="form-horizontal padding-15" name="category_form" id="category_form" role="form" method="POST" enctype="multipart/form-data">  
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $subcat->id ?? '' }}">
                                    
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">{{trans('words.category')}}</label>
                                        
                                        <div class="col-sm-9">
                                            @php($selectedCategory = old('category', $subcat->cat_id ?? ''))
                                            <select id="basic" name="category" class="js-select2 form-control">
                                                <option value="">{{trans('words.select_category')}}</option>
                                                
                                                @foreach($categories as $i => $category)    
                                                    <option value="{{$category->id}}" @selected((string)$selectedCategory === (string)$category->id)>{{$category->category_name}}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">{{trans('words.sub_category_name')}}</label>
                                          <div class="col-sm-9">
                                            <input type="text" name="sub_category_name" value="{{ old('sub_category_name', $subcat->sub_category_name ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">{{trans('words.sub_category_slug')}}</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="sub_category_slug" value="{{ old('sub_category_slug', $subcat->sub_category_slug ?? '') }}" class="form-control">
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