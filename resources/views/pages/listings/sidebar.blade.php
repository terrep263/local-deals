<div class="sidebar">
    <div class="card">
    <div class="card-body">
            <h4 class="card-title mb-3">{{trans('words.search_listing')}}</h4>
            <form action="{{ url('listings') }}" method="GET" class="" id="search" role="form">
            <div class="form-group">
                <span class="fal fa-search form-icon"></span>
                <input class="form-control form--control" type="text" name="search_text" value="{{isset($_GET['search_text'])?$_GET['search_text']:''}}" placeholder="{{trans('words.search')}}...">
            </div>
            <div class="form-group">
                <select class="select-picker" name="cat_id" data-width="100%" data-live-search="true">
                    <option value="">{{trans('words.category')}}</option>
                    @foreach(\App\Models\Categories::orderBy('sort_order')->orderBy('category_name')->get() as $i => $category) 
                        <option value="{{$category->id}}" @if(isset($_GET['cat_id']) AND $_GET['cat_id']==$category->id) Selected @endif>{{$category->category_name}}</option> 
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select class="select-picker" name="location_id" data-width="100%" data-live-search="true">
                    <option value="">{{trans('words.location')}}</option>
                    @foreach(\App\Models\City::ordered()->get() as $location)
                        <option value="{{$location->id}}" @if(isset($_GET['location_id']) AND $_GET['location_id']==$location->id) Selected @endif>{{$location->name}}</option> 
                    @endforeach
                </select>
            </div>
            <button class="primary_item_btn border-0 w-100" type="submit">{{trans('words.search')}}</button>
           </form> 
        </div>
    </div>    
     
    
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">{{trans('words.filter_by_ratings')}}</h4>
            <div class="custom-control custom-radio mb-2">
                <input type="radio" class="custom-control-input" id="fiveStarRadio" name="radio-stacked" value="5" @if(isset($_GET['rate']) AND $_GET['rate']==5) checked @endif>
                <label class="custom-control-label" for="fiveStarRadio">
                    <div class="star-rating line-height-24 font-size-15" data-rating="5"></div>
                </label>
            </div>
            <div class="custom-control custom-radio mb-2">
                <input type="radio" class="custom-control-input" id="fourStarRadio" name="radio-stacked" value="4" @if(isset($_GET['rate']) AND $_GET['rate']==4) checked @endif>
                <label class="custom-control-label" for="fourStarRadio">
                    <div class="star-rating line-height-24 font-size-15" data-rating="4"></div>
                </label>
            </div>
            <div class="custom-control custom-radio mb-2">
                <input type="radio" class="custom-control-input" id="threeStarRadio" name="radio-stacked" value="3" @if(isset($_GET['rate']) AND $_GET['rate']==3) checked @endif>
                <label class="custom-control-label" for="threeStarRadio">
                    <div class="star-rating line-height-24 font-size-15" data-rating="3"></div>
                </label>
            </div>
            <div class="custom-control custom-radio mb-2">
                <input type="radio" class="custom-control-input" id="twoStarRadio" name="radio-stacked" value="2" @if(isset($_GET['rate']) AND $_GET['rate']==2) checked @endif>
                <label class="custom-control-label" for="twoStarRadio">
                    <div class="star-rating line-height-24 font-size-15" data-rating="2"></div>
                </label>
            </div>
            <div class="custom-control custom-radio mb-2">
                <input type="radio" class="custom-control-input" id="oneStarRadio" name="radio-stacked" value="1" @if(isset($_GET['rate']) AND $_GET['rate']==1) checked @endif>
                <label class="custom-control-label" for="oneStarRadio">
                    <div class="star-rating line-height-24 font-size-15" data-rating="1"></div>
                </label>
            </div>
        </div>
    </div>
</div>