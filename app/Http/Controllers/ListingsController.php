<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Listings;
use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\Location;
use App\Models\City;
use App\Models\ListingGallery;
use App\Models\Reviews;
use App\Models\SubscriptionPlan;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class ListingsController extends Controller
{ 
    public function __construct()
    {
        $this->pagination_limit=8;
          
    }
    
    public function submit_listing()
    { 
        
        if(!Auth::check())
        {
            \Session::flash('error_flash_message', trans('words.access_denied'));
            return redirect('login');            
        }

        //Check Plan Exp
        if(Auth::User()->usertype =="User")
        {   
            $user_id=Auth::User()->id;

            $user_info = User::findOrFail($user_id);
            $user_plan_id=$user_info->plan_id;
            $user_plan_exp_date=$user_info->exp_date;

            if($user_plan_id==0 OR strtotime(date('m/d/Y'))>$user_plan_exp_date)
            {      
                return redirect('pricing');
            }
        }

        //Check Limit
        $user_id = Auth::User()->id;
        $user_plan_id = Auth::User()->plan_id;

        $plan_info = SubscriptionPlan::findOrFail($user_plan_id);
        $plan_listing_limit=$plan_info->plan_listing_limit;

        $listings = Listings::where('user_id',$user_id)->count();
 
        if($listings >= $plan_listing_limit)
        {
            \Session::flash('error_flash_message', trans('words.limit_reached'));

            return redirect('dashboard');
        }

        $categories = Categories::orderBy('category_name')->get();

        $locations = City::ordered()->get();

        return view('pages.listings.addeditlisting',compact('categories','locations'));
    }

    public function addnew(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        $rule=array(                      
                'title' => 'required',
                'description' => 'required',
                'category' => 'required',
                'sub_category' => 'required',
                'featured_image' => 'mimes:jpg,jpeg,gif,png'
                 );
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
        $inputs = $request->all();
        
        if(!empty($inputs['id'])){
           
            $listings = Listings::findOrFail($inputs['id']);

        }else{

            $listings = new Listings;

        }
 
        $listing_slug = Str::slug($inputs['title'], "-");
        
        //Featured image
        $featured_image = $request->file('featured_image');
         
        if($featured_image){
            
            \File::delete(public_path() .'/upload/listings/'.$listings->featured_image.'-b.jpg');
            \File::delete(public_path() .'/upload/listings/'.$listings->featured_image.'-s.jpg');
            
            $tmpFilePath = public_path('upload/listings/');          
             
            $hardPath = substr($listing_slug,0,100).'_'.time();
            
            $img = Image::make($featured_image);

            $img->save($tmpFilePath.$hardPath.'-b.jpg');
            
            //$img->fit(300, 300)->save($tmpFilePath.$hardPath. '-s.jpg');

            $img->resize(350, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($tmpFilePath.$hardPath. '-s.jpg');

            $listings->featured_image = $hardPath;
             
        }

        if(empty($inputs['id'])){
           
            $listings->user_id = Auth::User()->id;
        } 
 
        $listings->title = $inputs['title']; 
        $listings->listing_slug = $listing_slug;
        $listings->cat_id = $inputs['category'];
        $listings->sub_cat_id = $inputs['sub_category'];
        $listings->description = $inputs['description'];
        $listings->address = $inputs['address'];
        $listings->location_id = $inputs['location'];
        $listings->google_map_code = $inputs['google_map_code'];    
        
        
        if(checkUserPlanFeatures(Auth::User()->id,'plan_featured_option')==1) 
        {
            $listings->featured_listing = $inputs['featured_listing'];   
        }
        
        if(checkUserPlanFeatures(Auth::User()->id,'plan_amenities_option')==1) 
        {
            $listings->amenities = $inputs['amenities'];
        }

        if(checkUserPlanFeatures(Auth::User()->id,'plan_business_hours_option')==1) 
        {        
            $listings->working_hours_mon = $inputs['working_hours_mon'];
            $listings->working_hours_tue = $inputs['working_hours_tue'];
            $listings->working_hours_wed = $inputs['working_hours_wed'];
            $listings->working_hours_thurs = $inputs['working_hours_thurs'];
            $listings->working_hours_fri = $inputs['working_hours_fri'];
            $listings->working_hours_sat = $inputs['working_hours_sat'];
            $listings->working_hours_sun = $inputs['working_hours_sun'];
        }
       
        if(checkUserPlanFeatures(Auth::User()->id,'plan_video_option')==1) 
        {
            $listings->video = $inputs['video'];
        }        

          
        $listings->save();
        

        if(checkUserPlanFeatures(Auth::User()->id,'plan_gallery_images_option')==1) 
        {

                //News Gallery image
                $listing_gallery_files = $request->file('gallery_file');
                
                //$file_count = count($listing_gallery_files);
                
                if($request->hasFile('gallery_file'))
                {

                    if(!empty($inputs['id']))
                    {

                        foreach($listing_gallery_files as $file) {
                            
                            $listing_gallery_obj = new ListingGallery;
                            
                            $tmpFilePath = public_path('upload/gallery/');           
                            
                            $hardPath = substr($listing_slug,0,100).'_'.rand(0,9999).'-b.jpg';
                            
                            $g_img = Image::make($file);

                            $g_img->save($tmpFilePath.$hardPath);
                            

                            $listing_gallery_obj->listing_id = $inputs['id'];
                            $listing_gallery_obj->image_name = $hardPath;
                            $listing_gallery_obj->save();
                            
                        }

                    }
                    else
                    {   
                        foreach($listing_gallery_files as $file) {
                            
                            $listing_gallery_obj = new ListingGallery;
                            
                            $tmpFilePath = public_path('upload/gallery/');           
                            
                            $hardPath = substr($listing_slug,0,100).'_'.rand(0,9999).'-b.jpg';
                            
                            $g_img = Image::make($file);

                            $g_img->save($tmpFilePath.$hardPath);
                            
                            $listing_gallery_obj->listing_id = $listings->id;
                            $listing_gallery_obj->image_name = $hardPath;
                            $listing_gallery_obj->save();
                            
                        }
                    }
                }

        }

        if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.chanages_saved'));

            return \Redirect::back();
        }else{

            \Session::flash('flash_message', trans('words.listing_added_success'));

            return \Redirect::back();

        }            
        
         
    }

    public function editlisting($id)    
    {     
           
         if(!Auth::check())
         {

            \Session::flash('error_flash_message', trans('words.access_denied'));
            return redirect('login');
        
         }    

          //Check Plan Exp
        if(Auth::User()->usertype =="User")
        {   
            $user_id=Auth::User()->id;

            $user_info = User::findOrFail($user_id);
            $user_plan_id=$user_info->plan_id;
            $user_plan_exp_date=$user_info->exp_date;

            if($user_plan_id==0 OR strtotime(date('m/d/Y'))>$user_plan_exp_date)
            {      
                return redirect('pricing');
            }
        }          
           
          $listing = Listings::findOrFail($id);
            
         if($listing->user_id!=Auth::User()->id and Auth::User()->usertype!="Admin")
         {

            \Session::flash('error_flash_message', trans('words.access_denied'));

            return redirect('dashboard');
        
         }

          
          $categories = Categories::orderBy('category_name')->get(); 

          $subcategories = SubCategories::where('cat_id',$listing->cat_id)->orderBy('sub_category_name')->get();

          $locations = City::ordered()->get();

          $listing_gallery_images = ListingGallery::where('listing_id',$listing->id)->orderBy('id')->get();


          return view('pages.listings.addeditlisting',compact('listing','categories','subcategories','locations','listing_gallery_images'));
        
    }    
    
    public function delete($listing_id)
    {   

        $listing = Listings::findOrFail($listing_id);


         if($listing->user_id!=Auth::User()->id and Auth::User()->usertype!="Admin")
         {

            \Session::flash('error_flash_message', trans('words.access_denied'));

            return redirect('dashboard');
        
         }


        if(Auth::User()->usertype=="Admin" or Auth::User()->usertype=="User")
        {
             

            $listing_gallery_obj = ListingGallery::where('listing_id',$listing->id)->get();
            
            foreach ($listing_gallery_obj as $listing_gallery) {
                
                \File::delete('upload/gallery/'.$listing_gallery->image_name);
                \File::delete('upload/gallery/'.$listing_gallery->image_name);
                
                $listing_gallery_del = ListingGallery::findOrFail($listing_gallery->id);
                $listing_gallery_del->delete(); 

                
            }   

            
            \File::delete(public_path() .'/upload/listings/'.$listing->featured_image.'-b.jpg');
            \File::delete(public_path() .'/upload/listings/'.$listing->featured_image.'-s.jpg');    

            $listing->delete();

            \Session::flash('flash_message', trans('words.deleted'));

            return redirect()->back();
        }
        else
        {
            \Session::flash('error_flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        
        }
    }

    public function gallery_image_delete($id)
    {
        
        $listing_gallery_obj = ListingGallery::findOrFail($id);
        
        \File::delete('upload/gallery/'.$listing_gallery_obj->image_name);
         
        $listing_gallery_obj->delete(); 

        \Session::flash('flash_message', trans('words.deleted'));

        return redirect()->back();

    }


    public function ajax_subcategories($cat_id){ 
        
               
        $subcategories = SubCategories::where('cat_id',$cat_id)->orderBy('sub_category_name')->get();

         
        return view('pages.listings.ajax_subcategories',compact('subcategories'));
    } 


    public function listings()
    {   
        if(isset($_GET['search_text']) OR isset($_GET['cat_id']) OR isset($_GET['location_id']))
        {      

                $search_text=isset($_GET['search_text'])?$_GET['search_text']:'';
                $cat_id=isset($_GET['cat_id'])?$_GET['cat_id']:'';
                $location_id=isset($_GET['location_id'])?$_GET['location_id']:'';

                if($search_text!="" AND $cat_id!="" AND $location_id!="")
                {                     
                    $listings = Listings::where('status','1')->where('title',"LIKE","%$search_text%")->where('cat_id',$cat_id)->where('location_id',$location_id)->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }
                else if($search_text!="" AND $cat_id!="")
                {
                    $listings = Listings::where('status','1')->where('title',"LIKE","%$search_text%")->where('cat_id',$cat_id)->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }
                else if($search_text!="" AND $location_id!="")
                {
                    $listings = Listings::where('status','1')->where('title',"LIKE","%$search_text%")->where('location_id',$location_id)->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }
                else if($cat_id!="" AND $location_id!="")
                {
                    $listings = Listings::where('status','1')->where('cat_id',$cat_id)->where('location_id',$location_id)->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }
                else if($search_text!="")
                {
                    $listings = Listings::where('status','1')->where('title',"LIKE","%$search_text%")->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }
                else if($location_id!="")
                {
                    $listings = Listings::where('status','1')->where('location_id',$location_id)->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }
                else if($cat_id!="")
                {
                    $listings = Listings::where('status','1')->where('cat_id',$cat_id)->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }
                else
                {
                    $listings = Listings::where('status','1')->orderBy('title')->paginate($this->pagination_limit)->withQueryString();
                }

  
        }         
        else if(isset($_GET['rate']))
        {       
                $rate=$_GET['rate'];

                $listings = Listings::where('status','1')->where('review_avg',$rate)->orderBy('review_avg','desc')->paginate(8)->withQueryString();

        }
        else
        {
                $listings = Listings::where('status','1')->orderBy('id','desc')->paginate(8);
        }
 
        return view('pages.listings.listings_list',compact('listings'));
    }

    public function listings_sub_categories($cat_slug,$sub_cat_slug,$sub_cat_id)
    {  
        $listings = Listings::where('status','1')->where('sub_cat_id',$sub_cat_id)->orderBy('id','desc')->paginate($this->pagination_limit);

        $sub_cat_info = SubCategories::findOrFail($sub_cat_id);
          
        return view('pages.listings.listings_by_sub_categories',compact('listings','sub_cat_info'));
    }


    public function single_listing($listing_slug, $listing_id)
    {
        $listing = Listings::with(['user', 'category', 'location', 'gallery', 'reviews'])
            ->withCount('reviews')
            ->where(['status' => '1', 'id' => $listing_id])
            ->first();

        if (!$listing) {
            abort(404);
        }

        $vendorDeals = Listings::where('user_id', $listing->user_id)
            ->where('id', '!=', $listing->id)
            ->activeDeals()
            ->take(4)
            ->get();

        $similarDeals = Listings::where('cat_id', $listing->cat_id)
            ->where('id', '!=', $listing->id)
            ->activeDeals()
            ->take(4)
            ->get();

        $countdownData = null;
        if ($listing->is_deal_active) {
            $countdownData = [
                'expires_at' => $listing->deal_expires_at->toIso8601String(),
                'is_active' => true,
            ];
        }

        return view('pages.listings.details', [
            'listing' => $listing,
            'vendorDeals' => $vendorDeals,
            'similarDeals' => $similarDeals,
            'countdownData' => $countdownData,
        ]);
    }

    public function submit_review(Request $request)
    {   
        if(!Auth::check())
        {
           \Session::flash('error_flash_message', trans('words.login_required'));
           return \Redirect::back(); 
        }

        $data =  \Request::except(array('_token')) ;
        
        $rule=array(
                'rating' => 'required'                
                 );
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
        $inputs = $request->all();
        
        $user_id = Auth::User()->id;
        $listing_id = $inputs['listing_id'];

        $review_info = Reviews::where('user_id',$user_id)->where('listing_id',$listing_id)->first();
        
        if($review_info){

            \Session::flash('error_flash_message', trans('words.review_already'));

            return \Redirect::back(); 
        }
    
        $review = new Reviews;

        //echo $inputs['rating'];exit;

      
        $review->user_id = $user_id;
        $review->listing_id = $listing_id;
        $review->review = $inputs['review']; 
        $review->rating = $inputs['rating']; 
        $review->date= strtotime(date('Y-m-d'));  
          
        $review->save();

        $total_avg=round(DB::table('listings_reviews')->where('listing_id', $inputs['listing_id'])->avg('rating'));

        $listing_obj = Listings::findOrFail($inputs['listing_id']);

        $listing_obj->review_avg = $total_avg;  
        $listing_obj->save(); 
        
        
            \Session::flash('flash_message', trans('words.review_submitted'));

            return \Redirect::back();
             
    }
    
    public function inquiry_send(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        $inputs = $request->all();
        
        $rule=array(                
                'name' => 'required',
                'email' => 'required|email|max:75'
                 );
                
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
 
            $data = array(
            'name' => $inputs['name'],
            'email' => $inputs['email'],           
            'phone' => $inputs['phone'],
            'user_message' => $inputs['message'],
             );

            $listing_id= $inputs['listing_id'];
            $listing_info = Listings::where('id',$listing_id)->first(); 
             
            $subject='Enquiry for'.stripslashes($listing_info->title);
            $contact_email = User::getUserInfo($listing_info->user_id)->contact_email;
              

            \Mail::send('emails.inquiry', $data, function ($message) use ($subject,$contact_email){

                $message->from(getcong('site_email'), getcong('site_name'));

                $message->to($contact_email)->subject($subject);

            });
        

            \Session::flash('flash_message', trans('words.inquiry_msg'));

            return \Redirect::back();

         
    }    

    public function user_listings($user_id)
    {  
        $user_info = User::findOrFail($user_id);

        if(!$user_info){
            abort('404');
        }

        $listings = Listings::where('status','1')->where('user_id',$user_id)->orderBy('id','desc')->paginate($this->pagination_limit);
           
        return view('pages.listings.user_listing',compact('listings','user_info'));
    }
 
}
