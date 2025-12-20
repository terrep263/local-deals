<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\SubscriptionPlan;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Str;

class PlanController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
		  
		parent::__construct(); 	
		  
    }
    public function plan_list()    { 
        
              
        $subscription_plan = SubscriptionPlan::orderBy('id')->get();
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
 
         
        return view('admin.pages.subscription_plan',compact('subscription_plan'));
    }


    public function add_plan()    { 
         
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        } 
        return view('admin.pages.add_edit_plan');
    }

     public function edit_plan($plan_id)    
    {     
    
          if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
                 
          $plan_info = SubscriptionPlan::findOrFail($plan_id);
           
          return view('admin.pages.add_edit_plan',compact('plan_info'));
        
    }    
    
     
    public function addnew(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        $rule=array(
                'plan_name' => 'required',
                'plan_price' => 'required'    
                 );
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
        $inputs = $request->all();
        
        if(!empty($inputs['id'])){
           
            $plan_obj = SubscriptionPlan::findOrFail($inputs['id']);

        }else{

            $plan_obj = new SubscriptionPlan;
        }
        
          
        $plan_days_final=$inputs['plan_duration']*$inputs['plan_duration_type'];
         
        $plan_obj->plan_name = $inputs['plan_name'];
        $plan_obj->plan_duration = $inputs['plan_duration']; 
        $plan_obj->plan_duration_type = $inputs['plan_duration_type']; 
        $plan_obj->plan_days = $plan_days_final;           
        $plan_obj->plan_price = $inputs['plan_price']; 
        
        
        $plan_obj->plan_listing_limit = $inputs['plan_listing_limit'];
        
        $plan_obj->plan_featured_option = isset($inputs['plan_featured_option'])?$inputs['plan_featured_option']:0;

        $plan_obj->plan_business_hours_option = isset($inputs['plan_business_hours_option'])?$inputs['plan_business_hours_option']:0;

        $plan_obj->plan_amenities_option = isset($inputs['plan_amenities_option'])?$inputs['plan_amenities_option']:0;

        $plan_obj->plan_gallery_images_option = isset($inputs['plan_gallery_images_option'])?$inputs['plan_gallery_images_option']:0;

        $plan_obj->plan_video_option = isset($inputs['plan_video_option'])?$inputs['plan_video_option']:0;

        $plan_obj->plan_enquiry_form = isset($inputs['plan_enquiry_form'])?$inputs['plan_enquiry_form']:0;

        if(isset($inputs['plan_recommended']))
        {
           $plan_obj->plan_recommended = $inputs['plan_recommended'];  
        }

        $plan_obj->status = $inputs['status'];  
          
        $plan_obj->save();
 
        
        if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        }else{

            \Session::flash('flash_message', trans('words.successfully_added'));

            return \Redirect::back();

        }            
        
         
    }     
    
    public function delete($plan_id)
    {
        if(Auth::User()->usertype=="Admin")
        {
            
            $plan = SubscriptionPlan::findOrFail($plan_id);
            $plan->delete();

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
