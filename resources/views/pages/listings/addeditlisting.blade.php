@extends("app")

@section('head_title', isset($listing->id) ? trans('words.edit_listing') : trans('words.add_listing').' | '.getcong('site_name') )

@section('head_url', Request::url())

@section("content")
 
 

 <!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{isset($listing->id) ? trans('words.edit_listing') : trans('words.add_listing')}}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li>{{isset($listing->id) ? trans('words.edit_listing') : trans('words.add_listing')}}</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

<!-- ================================
     Start Add Listing Area
================================= -->
<section class="add-listing-area bg-gray section_item_padding">
    <div class="container">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul style="list-style: none;">
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
         
        <form action="{{ url('submit_listing') }}" class="" name="listing_form" id="listing_form" role="form" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id" value="{{ isset($listing->id) ? $listing->id : null }}">

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{trans('words.basic_information')}}</h4>
                    <hr class="border-top-gray my-0">
                    <div class="row mt-4">
                         
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-text d-flex align-items-center">{{trans('words.title')}} * <!-- <span class="fas fa-question tip ml-2" data-toggle="tooltip" data-placement="top" title="Name of your business"></span> --></label>
                                <input class="form-control form--control pl-3" type="text" name="title" id="title" value="{{ old('title', isset($listing->title) ? $listing->title : null) }}" placeholder="e.g. Super duper burger">
                            </div> 
                        </div>

                        <?php 
                           $featured_option = checkUserPlanFeatures(Auth::User()->id,'plan_featured_option');

                           if($featured_option==1)
                           {
                            $featured_class='col-lg-4';
                           }
                           else
                           {
                            $featured_class='col-lg-6';
                           }
                        ?>
                        
                        <div class="{{$featured_class}}">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.category')}} *</label>
                                                                @php($selectedCategory = old('category', isset($listing->cat_id) ? $listing->cat_id : null))
                                <select id="category" name="category" class="select-picker" data-width="100%" data-live-search="true">
                                   <option value="">{{trans('words.select_category')}}</option> 
                                    @foreach($categories as $i => $category) 
                                                                        <option value="{{$category->id}}" @if($selectedCategory == $category->id) selected @endif>{{$category->category_name}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="{{$featured_class}}">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.sub_category')}} *</label>
                                                                @php($selectedSubCategory = old('sub_category', isset($listing->sub_cat_id) ? $listing->sub_cat_id : null))
                                <select id="sub_category" name="sub_category" class="select-picker" data-width="100%" data-live-search="true">
                                    <option value="">{{trans('words.select_sub_category')}}</option>
                                    @if(isset($listing->sub_cat_id))
                                      @foreach($subcategories as $i => $subcategory)    
                                                                                        <option value="{{$subcategory->id}}" @if($selectedSubCategory == $subcategory->id) selected @endif>{{$subcategory->sub_category_name}}</option>
                                      @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        @if($featured_option==1)
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.featured')}}</label>
                                                                @php($featuredSelection = old('featured_listing', isset($listing->featured_listing) ? $listing->featured_listing : 0))
                                <select id="featured_listing" name="featured_listing" class="select-picker" data-width="100%">
                                                                <option value="1" @if($featuredSelection == 1) selected @endif>Featured</option>
                                                                    <option value="0" @if($featuredSelection == 0) selected @endif>None Featured</option>  
                                </select>
                            </div>
                        </div> 
                        @endif                        
                        
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.description')}}</label>
                                                                <textarea name="description" id="description" class="form-control form--control pl-3 user-text-editor elm1_editor" rows="5">{{ old('description', isset($listing->description) ? $listing->description : null) }}</textarea>
                            </div>
                        </div>

                           <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-text">{{trans('words.address')}}</label>
                                    <input class="form-control form--control pl-3" type="text" name="address" id="address" placeholder="{{trans('words.address')}}" value="{{ old('address', isset($listing->address) ? $listing->address : null) }}">
                                </div>
                            </div>
                          
                          <div class="col-lg-6">
                              <div class="form-group">
                                  <label class="label-text">{{trans('words.location')}}</label>
                                                                    @php($selectedLocation = old('location', isset($listing->location_id) ? $listing->location_id : null))
                                                                    <select id="location" name="location" class="select-picker" data-width="100%" data-size="5" data-live-search="true">
                                      <option value="">{{trans('words.select_location')}}</option>
                                                                                @foreach($locations as $i => $location) 
                                                                                    <option value="{{$location->id}}" @if($selectedLocation == $location->id) selected @endif>{{$location->location_name}}</option> 
                                        @endforeach
                                  </select>
                              </div>
                          </div>
                           

                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                                <label class="label-text">{{trans('words.google_map_code')}}</label>
                                                                <textarea name="google_map_code" id="google_map_code" class="form-control form--control pl-3 user-text-editor" rows="3">{{ old('google_map_code', isset($listing->google_map_code) ? $listing->google_map_code : null) }}</textarea>
                                                        </div>
                                                </div>

                    </div>
                </div>
            </div>

            @if(checkUserPlanFeatures(Auth::User()->id,'plan_amenities_option')==1) 
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{trans('words.amenities')}}</h4>
                    <hr class="border-top-gray my-0">
                    <div class="amenities-wrap mt-4">
						<div class="form-group">
                           <input id="amenities_tags" type="text" name="amenities" class="form-control form--control pl-3" value="{{ old('amenities', isset($listing->amenities) ? $listing->amenities : '') }}" />
						</div>
                    </div>
                </div>
            </div>
            @endif

            @if(checkUserPlanFeatures(Auth::User()->id,'plan_business_hours_option')==1)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{trans('words.business_hours')}}</h4>
                    <hr class="border-top-gray my-0">
                    <div class="row mt-4">

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.monday')}}</label>
                                <input id="working_hours_mon" type="text" name="working_hours_mon" class="form-control form--control pl-3" value="{{ old('working_hours_mon', isset($listing->working_hours_mon) ? $listing->working_hours_mon : null) }}" placeholder="9am - 5pm" />
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.tuesday')}}</label>
                                <input id="working_hours_tue" type="text" name="working_hours_tue" class="form-control form--control pl-3" value="{{ old('working_hours_tue', isset($listing->working_hours_tue) ? $listing->working_hours_tue : null) }}" placeholder="9am - 5pm"/>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.wednesday')}}</label>
                                <input id="working_hours_wed" type="text" name="working_hours_wed" class="form-control form--control pl-3" value="{{ old('working_hours_wed', isset($listing->working_hours_wed) ? $listing->working_hours_wed : null) }}" placeholder="9am - 5pm"/>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.thursday')}}</label>
                                <input id="working_hours_thurs" type="text" name="working_hours_thurs" class="form-control form--control pl-3" value="{{ old('working_hours_thurs', isset($listing->working_hours_thurs) ? $listing->working_hours_thurs : null) }}" placeholder="9am - 5pm"/>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.friday')}}</label>
                                <input id="working_hours_fri" type="text" name="working_hours_fri" class="form-control form--control pl-3" value="{{ old('working_hours_fri', isset($listing->working_hours_fri) ? $listing->working_hours_fri : null) }}" placeholder="9am - 5pm"/>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.saturday')}}</label>
                                <input id="working_hours_sat" type="text" name="working_hours_sat" class="form-control form--control pl-3" value="{{ old('working_hours_sat', isset($listing->working_hours_sat) ? $listing->working_hours_sat : null) }}" placeholder="9am - 5pm"/>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.sunday')}}</label>
                                <input id="working_hours_sun" type="text" name="working_hours_sun" class="form-control form--control pl-3" value="{{ old('working_hours_sun', isset($listing->working_hours_sun) ? $listing->working_hours_sun : null) }}" placeholder="Closed"/>
                            </div>
                        </div>
 
                    
                  </div>  
                     
                </div>
            </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{trans('words.media')}}</h4>
                    <hr class="border-top-gray my-0">
                    <label class="label-text mt-4">{{trans('words.featured_image')}} ({{trans('words.recommended_resolution')}}: 1200X675)</label>
                    <div class="file-upload-wrap file-upload-wrap-layout-2">
                        <input type="file" name="featured_image" class="multi file-upload-input with-preview">
                        <span class="file-upload-text"><i class="fal fa-image mr-2"></i>{{trans('words.choose_file')}}</span>
                    </div>



                     @if(isset($listing->featured_image))
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                 <a href="javascript::void(0);" class="sec_category_item d-block hover-y">
                                 <img src="{{ URL::asset('upload/listings/'.$listing->featured_image.'-s.jpg') }}" width="" alt="featured" class="category-img lazy">
                                </a>
                            </div>
                        </div>
                     @endif

                @if(checkUserPlanFeatures(Auth::User()->id,'plan_gallery_images_option')==1)
     
                    <label class="label-text mt-2">{{trans('words.gallery_images')}} ({{trans('words.recommended_resolution')}}: 900X500)</label>
                    <div class="file-upload-wrap">
                        <input type="file" name="gallery_file[]" class="multi file-upload-input with-preview" multiple>
                        <span class="file-upload-text"><i class="fal fa-upload mr-2"></i>{{trans('words.drag_drop_file')}}</span>
                    </div>

                    @if(isset($listing->id))
                    <div class="row">
                        @foreach($listing_gallery_images as $i => $gallery_img)

                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 col-6">
                                <div class="sec_category_item upload_listing_gallery d-block hover-y">
                                     <a class="remove_listing_pic" href="{{ url('listing/galleryimage_delete/'.$gallery_img->id) }}" onclick="return confirm('{{trans('words.remove_cofirm_msg')}}')"><i class="fas fa-trash"> </i></a>                                  
                                     <img src="{{ URL::asset('upload/gallery/'.$gallery_img->image_name) }}" width="100" alt="image" class="category-img lazy">                                     
                                </div>
                            </div>
 
                        @endforeach
                        </div>
                    @endif
                @endif    
                </div>
            </div>

            @if(checkUserPlanFeatures(Auth::User()->id,'plan_video_option')==1)    
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{trans('words.video')}}</h4>
                    <hr class="border-top-gray my-0">
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-text">{{trans('words.video_embed')}}</label>

                                <textarea name="video" id="video" class="form-control form--control pl-3 user-text-editor" rows="3">{{ old('video', isset($listing->video) ? $listing->video : null) }}</textarea>
 
                             </div>
                        </div>
                          
                    </div>
                </div>
            </div>
            @endif  
            
            @if(!isset($listing->id))
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="termsCheckbox" required>
                <label class="custom-control-label" for="termsCheckbox">{{trans('words.i_agree')}} <a href="{{ URL::to('terms-conditions/') }}" class="btn-link">{{getcong('terms_of_title')}}</a></label>
            </div>
            @endif 

            <button class="primary_item_btn border-0 mt-3" type="submit">{{trans('words.save_listing')}}</button>
            
        </form> 
    </div>
</section>
<!-- ================================
    End Add Listing Area
================================= -->
    
@endsection