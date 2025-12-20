<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Listings;
use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\Location;
use App\Models\ListingGallery;
use App\Models\SubscriptionPlan;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;

class ListingsController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
		  
		parent::__construct(); 	
		  
    }
    public function listings()    { 
        
        if(isset($_GET['s']))
        {   
            $keyword = $_GET['s'];  

            $listings = DB::table('listings')
                           ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
                           ->leftJoin('sub_categories', 'listings.sub_cat_id', '=', 'sub_categories.id')
                           ->select('listings.*','categories.category_name','sub_categories.sub_category_name')
                           ->where("listings.title", "LIKE","%$keyword%")
                           ->orwhere("listings.address", "LIKE","%$keyword%")
                           ->orderBy('id','DESC')
                           ->paginate(15); 

            $listings->appends(\Request::only('s'))->links();              
        }
        else if(isset($_GET['listing_status']))
        {
            $listing_status = $_GET['listing_status'];  

            if($listing_status=='featured')
            {
                $listings = DB::table('listings')
                           ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
                           ->leftJoin('sub_categories', 'listings.sub_cat_id', '=', 'sub_categories.id')
                           ->select('listings.*','categories.category_name','sub_categories.sub_category_name')
                           ->where("listings.featured_listing", "=",1)
                           ->orderBy('id','DESC')
                           ->paginate(15);
            }
            else
            {
                $listings = DB::table('listings')
                           ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
                           ->leftJoin('sub_categories', 'listings.sub_cat_id', '=', 'sub_categories.id')
                           ->select('listings.*','categories.category_name','sub_categories.sub_category_name')
                           ->where("listings.status", "=",0)
                           ->orderBy('id','DESC')
                           ->paginate(15); 
            }

            $listings->appends(\Request::only('listing_status'))->links();
        }
        else
        {


        $listings = DB::table('listings')
                           ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
                           ->leftJoin('sub_categories', 'listings.sub_cat_id', '=', 'sub_categories.id')
                           ->select('listings.*','categories.category_name','sub_categories.sub_category_name')
                           ->paginate(15);

        
        }


        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
 
         
        return view('admin.pages.listings',compact('listings'));
    }
    
    public function featured_listing($listing_id,$status)
    {


        if(Auth::User()->usertype=="Admin")
        {
            
            $listing = Listings::findOrFail($listing_id); 
            
            $listing->featured_listing = $status;
 
            $listing->save();
         
            \Session::flash('flash_message', trans('words.chanages_saved'));

            return redirect()->back();
        }
        else
        {
            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
             
        }
    }
 
    public function status_listing($listing_id,$status)
    {


        if(Auth::User()->usertype=="Admin")
        {
            
            $listing = Listings::findOrFail($listing_id);
 
            
            $listing->status = $status;
 
            $listing->save();
         
            \Session::flash('flash_message', trans('words.status_changed'));

            return redirect()->back();
        }
        else
        {
            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
             
        }
    }
      
    public function delete($listing_id)
    {
        if(Auth::User()->usertype=="Admin" or Auth::User()->usertype=="Owner")
        {
            
            $listing = Listings::findOrFail($listing_id);
            

            $listing_gallery_obj = ListingGallery::where('listing_id',$listing->id)->get();
            
            foreach ($listing_gallery_obj as $listing_gallery) {
                
                \File::delete('upload/gallery/'.$listing_gallery->image_name);
                \File::delete('upload/gallery/'.$listing_gallery->image_name);
                
                $listing_gallery_del = ListingGallery::findOrFail($listing_gallery->id);
                $listing_gallery_del->delete(); 

                
            }   

            
            \File::delete('upload/listings/'.$listing->featured_image.'-b.jpg');
            \File::delete('upload/listings/'.$listing->featured_image.'-s.jpg');    

            $listing->delete();

            \Session::flash('flash_message', trans('words.deleted'));

            return redirect()->back();
        }
        else
        {
            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        
        }
    }
     
    	
}
