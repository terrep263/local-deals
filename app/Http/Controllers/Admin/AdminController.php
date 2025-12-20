<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Str;

class AdminController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');	
         
    }
    public function index()
    { 
        return view('admin.pages.dashboard');
    }
	
	public function profile()
    { 
        return view('admin.pages.profile');
    }
    
    public function updateProfile(Request $request)
    {  
    		 
    	$user = User::findOrFail(Auth::user()->id);
 
	    
	    $data =  \Request::except(array('_token')) ;
	    
	    $rule=array(
		        'first_name' => 'required',
                'last_name' => 'required',
		        'email' => 'required|email|max:75|unique:users,id',
		        'image_icon' => 'mimes:jpg,jpeg,gif,png'
		   		 );
	    
	   	 $validator = \Validator::make($data,$rule);
 
            if ($validator->fails())
            {
                    return redirect()->back()->withErrors($validator->messages());
            }
	    

	    $inputs = $request->all();
		
		$icon = $request->file('user_icon');
		 
        
        if($icon){
            $tmpFilePath = public_path('upload/members/');

            $hardPath =  Str::slug($inputs['first_name'], '-').'-'.md5(time());

            $img = Image::make($icon);

            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
 
            $user->image_icon = $hardPath.'-b.jpg';
        }
        
		
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
		
		
	   // $user->fill($input)->save();
	   
	    $user->save();

	    Session::flash('flash_message', trans('words.successfully_updated'));

        return redirect()->back();
    }
    
    public function updatePassword(Request $request)
    { 
    	 
    		//$user = User::findOrFail(Auth::user()->id);
		
		
		    $data =  \Request::except(array('_token')) ;
            $rule  =  array(
                    'password'       => 'required|min:8|confirmed',
                    'password_confirmation'       => 'required'
                ) ;
 
            $validator = \Validator::make($data,$rule);
 
            if ($validator->fails())
            {
                    return redirect()->back()->withErrors($validator->messages());
            }
		
	   		/* $val=$this->validate($request, [
                    'password' => 'required|confirmed',
            ]);  */      
         
	    $credentials = $request->only('password', 'password_confirmation'
            );
            
        $user = \Auth::user();
        $user->password = bcrypt($credentials['password']);
        $user->save();

	    Session::flash('flash_message', trans('words.successfully_updated'));

        return redirect()->back();
    }
	 
    	
}
