<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Location;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Str;

class LocationController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
		  
		parent::__construct(); 	
		  
    }
    public function locations()
    {  
              
        $locations = Location::orderBy('location_name')->get();
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');            
        } 
         
        return view('admin.pages.locations',compact('locations'));
    }
    
    public function addeditLocation()    { 
         
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        

        return view('admin.pages.addeditlocation');
    }
    
    public function addnew(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        $rule=array(
                'location_name' => 'required'                
                 );
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
        $inputs = $request->all();
        
        if(!empty($inputs['id'])){
           
            $location = Location::findOrFail($inputs['id']);

        }else{

            $location = new Location;

        }
        
        
        if($inputs['location_slug']=="")
        {
            $location_slug = Str::slug($inputs['location_name'], "-");
        }
        else
        {
            $location_slug =Str::slug($inputs['location_slug'], "-"); 
        }
        
        $location->location_name = $inputs['location_name']; 
        $location->location_slug = $location_slug;
         
        
         
        $location->save();
        
        if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        }else{

            \Session::flash('flash_message', trans('words.successfully_added'));

            return \Redirect::back();

        }            
        
         
    }     
   
    
    public function editLocation($location_id)    
    {     
    
    	  if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
        	     
          $location = Location::findOrFail($location_id);
          
           

          return view('admin.pages.addeditlocation',compact('location'));
        
    }	 
    
    public function delete($location_id)
    {
    	if(Auth::User()->usertype=="Admin" or Auth::User()->usertype=="Owner")
        {
        	
        $location = Location::findOrFail($location_id);
        $location->delete();

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
