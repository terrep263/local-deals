<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Listings;
use App\Models\Reviews;
use App\Models\SubscriptionPlan;

use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;

class UsersController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');	
		
		 parent::__construct();
         
    }
    public function userslist()    { 
         
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        } 
          
        $allusers = User::where('usertype', '!=', 'Admin')->orderBy('id')->get();
       
         
        return view('admin.pages.users',compact('allusers'));
    } 
     
    public function addeditUser()    { 
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
        
        $plan_list = SubscriptionPlan::where('status','1')->orderby('id')->get();     
        
          
        return view('admin.pages.addeditUser',compact('plan_list'));
    }
    
    public function addnew(Request $request)
    { 
    	
    	$data =  \Request::except(array('_token')) ;
	    
	    $inputs = $request->all();
	    
	    if(!empty($inputs['id']))
	    {
			$rule=array(
		        'first_name' => 'required',
                'last_name' => 'required',
		        'email' => 'required|email|max:200',
                'image_icon' => 'mimes:jpg,jpeg,gif,png' 		        	        
		   		 );
			
		}
		else
		{
			$rule=array(
		        'first_name' => 'required',
                'last_name' => 'required',
		        'email' => 'required|email|max:75|unique:users',
		        'password' => 'required|min:8|max:50',
                'image_icon' => 'mimes:jpg,jpeg,gif,png' 		        
		   		 );
		}
	    
	    
	    
	   	 $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
	      
		if(!empty($inputs['id'])){
           
            $user = User::findOrFail($inputs['id']);

        }else{

            $user = new User;

        }
		
		//User image
        $user_image = $request->file('image_icon');
         
        if($user_image){
             
            $tmpFilePath = public_path('upload/members/');

            $hardPath =  Str::slug($inputs['first_name'], '-').'-'.md5(time());
            
            $img = Image::make($user_image);

            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            //$img->fit(80, 80)->save($tmpFilePath.$hardPath. '-s.jpg');

            $user->image_icon = $tmpFilePath.$hardPath.'-b.jpg';
             
        } 

        //Get Plan info 
        $plan_id=$inputs['plan_id'];
        $plan_info = SubscriptionPlan::where('id',$plan_id)->first();        
        $plan_days=$plan_info->plan_days;
		
		$user->usertype = 'User';
		$user->first_name = $inputs['first_name']; 
        $user->last_name = $inputs['last_name'];       
        $user->email = $inputs['email'];
        $user->mobile = $inputs['mobile'];
        $user->contact_email = $inputs['contact_email'];
        $user->website = $inputs['website'];           
        $user->address = $inputs['address']; 
        $user->facebook_url = $inputs['facebook_url'];
        $user->twitter_url = $inputs['twitter_url'];
        $user->linkedin_url = $inputs['linkedin_url'];
        $user->instagram_url = $inputs['instagram_url'];       		 
		
		if($inputs['password'])
		{
			$user->password= bcrypt($inputs['password']); 
		}

        if(empty($inputs['id']) && $inputs['exp_date']=="")
        {
            $user->exp_date = $inputs['exp_date']?strtotime(date('m/d/Y', strtotime("+$plan_days days"))):null;
        }
        else
        {
            $user->exp_date = $inputs['exp_date']?strtotime($inputs['exp_date']):null;
        }

		$user->plan_id = $inputs['plan_id'];

		$user->status = $inputs['status'];
		 
	    $user->save();
		
		if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        }else{

            \Session::flash('flash_message', trans('words.successfully_added'));

            return \Redirect::back();

        }		     
        
         
    }     
    
    public function editUser($id)    
    {     
    	  if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');            
        }		
    		     
          $user = User::findOrFail($id);

          $plan_list = SubscriptionPlan::where('status','1')->orderby('id')->get();
           
          return view('admin.pages.addeditUser',compact('user','plan_list'));
        
    }	 
    
    public function delete($id)
    {
    	
    	if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        $listings_obj = Listings::where('user_id',$id)->delete();
        $reviews_obj = Reviews::where('user_id',$id)->delete();
 
        $user = User::findOrFail($id);
        
		\File::delete(public_path() .'/upload/members/'.$user->image_icon.'-b.jpg');
		\File::delete(public_path() .'/upload/members/'.$user->image_icon.'-s.jpg');
			
		$user->delete();
		
        \Session::flash('flash_message', trans('words.deleted'));

        return redirect()->back();

    }
    
     
   
    	
}
