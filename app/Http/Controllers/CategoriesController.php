<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Listings;
use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\Location;
use App\Models\ListingGallery;
use App\Models\Reviews;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 


class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->pagination_limit=12;
          
    } 
	
   public function categories_list()
   { 
         
       $cat_list = Categories::orderBy('category_name')->paginate($this->pagination_limit);

       return view('pages.listings.categories_list',compact('cat_list'));
   }

   public function sub_categories_list($cat_slug,$cat_id)
   { 
         
       $sub_cat_list = SubCategories::where('cat_id',$cat_id)->orderBy('sub_category_name')->get();

       $cat_info = Categories::findOrFail($cat_id);

       return view('pages.listings.sub_categories_list',compact('sub_cat_list','cat_info'));
   }
 
}
