<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Categories;
use App\Models\Listings;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;

use Session;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class UserController extends Controller
{
 
    public function login()
    { 
       if(Auth::check())
       { 
            return redirect('/dashboard');
       }
       else
       {
            return view('pages.user.login');
       }

        
    }

    public function postLogin(Request $request)
    {
         
      $this->validate($request, [
            'email' => 'required|email', 'password' => 'required|min:8',
        ]);


        $credentials = $request->only('email', 'password');

          
         if (Auth::attempt($credentials, $request->has('remember'))) {

            if(Auth::user()->status==0){
                \Auth::logout();
                return redirect('/login')->withErrors(trans('words.account_banned'));
            }

            return $this->handleUserWasAuthenticated($request);
        }

       // return array("errors" => 'The email or the password is invalid. Please try again.');
        //return redirect('/admin');
       return redirect('/login')->withErrors(trans('words.login_email_pass_wrong'));
        
    }
    
     /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request)
    {

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::user());
        }

        return redirect('/dashboard'); 
    }
    
     public function forgot_password()
    { 
       
            return view('pages.forgot_password');
        
    }

    
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

        \Session::flash('flash_message', trans('words.logout_success'));

        return redirect('/login');
    }


    public function register()
    { 
                   
        return view('pages.user.register');
    }

    public function postRegister(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        $inputs = $request->all();
        
        $rule=array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|max:200|unique:users',
                'password' => 'required|min:8|confirmed'
                 );
        
        
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
          
       
        $user = new User;

        
        $user->usertype = 'User';
        $user->first_name = $inputs['first_name']; 
        $user->last_name = $inputs['last_name'];       
        $user->email = $inputs['email'];         
        $user->password= bcrypt($inputs['password']);        
         
        $user->save();
         

        \Session::flash('flash_message', trans('words.sign_up_success'));

        return \Redirect::back();

         
    }    

    public function dashboard()
    {   
       if(Auth::check())
       { 
            $user_id=Auth::user()->id;
            $user = User::findOrFail($user_id);

            $total_listings = Listings::where('user_id',$user_id)->count();

            $publish_listings = Listings::where(array('user_id'=>$user_id,'status'=>1))->count(); 

            $pending_listings = Listings::where(array('user_id'=>$user_id,'status'=>0))->count(); 

            $my_listings = Listings::where('user_id',$user_id)->paginate(10);

            // Subscription data
            $subscription = $user->activeSubscription;
            $packageFeatures = null;
            $activeDealsCount = 0;
            $totalInventory = 0;
            
            if ($subscription) {
                $packageFeatures = $subscription->packageFeature();
            } else {
                $packageFeatures = \App\Models\PackageFeature::getByTier('starter');
            }
            
            // Count active deals (for Module 2 - deals table exists)
            if (\Schema::hasTable('deals')) {
                $activeDealsCount = \App\Models\Deal::where('vendor_id', $user_id)
                    ->where('status', 'active')
                    ->count();
                $totalInventory = \App\Models\Deal::where('vendor_id', $user_id)
                    ->where('status', 'active')
                    ->sum('inventory_total');
            }

             return view('pages.user.dashboard',compact('user','total_listings','publish_listings','pending_listings','my_listings','subscription','packageFeatures','activeDealsCount','totalInventory'));
       }
       else
       {       
            return redirect('/login');
       }   
    } 

    public function profile()
    { 
        if(!Auth::check())
       {

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('login');
            
        }

        $user_id=Auth::user()->id;
        $user = User::findOrFail($user_id);

        return view('pages.user.profile',compact('user'));
    } 
    

    public function editprofile(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        $inputs = $request->all();
        
        
            $rule=array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|max:200' 
                 );
       
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
          
        $user_id=Auth::user()->id;
           
        $user = User::findOrFail($user_id);
        

        $icon = $request->file('user_icon');
         
        if($icon){


            $tmpFilePath = public_path('upload/members/');

            $hardPath =  Str::slug($inputs['first_name'], '-').'-'.md5(time());

            $img = Image::make($icon);

            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            //$img->fit(80, 80)->save($tmpFilePath.$hardPath. '-s.jpg');

            $user->image_icon = $hardPath.'-b.jpg';
        }
         
        
        $user->first_name = $inputs['first_name']; 
        $user->last_name = $inputs['last_name'];       
        $user->email = $inputs['email'];

        if($inputs['password'])
        {
            $user->password = bcrypt($inputs['password']);
        }  

        $user->mobile = $inputs['mobile'];
        $user->contact_email = $inputs['contact_email'];
        $user->website = $inputs['website'];        
        $user->address = $inputs['address'];
        $user->facebook_url = $inputs['facebook_url'];
        $user->twitter_url = $inputs['twitter_url'];
        $user->linkedin_url = $inputs['linkedin_url'];
        $user->instagram_url = $inputs['instagram_url'];  

        $user->save();
        
         
            \Session::flash('flash_message', trans('words.chanages_saved'));

            return \Redirect::back();
         
         
    }

    public function phone_update(Request $request)
    {
        $id=Auth::user()->id;    
        $user = User::findOrFail($id);

        $data =  \Request::except(array('_token'));
        
        $rule=array(
                'phone' => 'required' 
                 );
        
         $validator = \Validator::make($data,$rule);
 
            if ($validator->fails())
            {
                    return redirect()->back()->withErrors($validator->messages());
            }
        

        $inputs = $request->all();
       
        $user->mobile = $inputs['phone'];        
        $user->save();

        Session::flash('flash_message', trans('words.chanages_saved'));

        return redirect()->back();
    }
     
    public function plan_list()
    {  
        $subscription_plan = SubscriptionPlan::orderBy('id')->get();
        
        return view('pages.payment.plan_list',compact('subscription_plan'));
    }
     

    public function payment_method($plan_id)
    { 
       if(!Auth::check())
       { 
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('login');
       }

        $plan_info = SubscriptionPlan::where('id',$plan_id)->where('status','1')->first();

        if(!$plan_info)
        {
            \Session::flash('flash_message', trans('words.select_plan'));
            return redirect('membership_plan'); 
        } 

        //For free plan
        if($plan_info->plan_price <=0)
        {
            $plan_days=$plan_info->plan_days;
            $plan_amount=$plan_info->plan_price;
 
            $currency_code=getcong('currency_code')?getcong('currency_code'):'USD';

            $user_id=Auth::user()->id;           
            $user = User::findOrFail($user_id);

            $user->plan_id = $plan_id;                    
            $user->start_date = strtotime(date('m/d/Y'));             
            $user->exp_date = strtotime(date('m/d/Y', strtotime("+$plan_days days")));            
             
            $user->plan_amount = $plan_amount;
            //$user->subscription_status = 0;
            $user->save();


            $payment_trans = new PaymentTransaction;

            $payment_trans->user_id = Auth::user()->id;
            $payment_trans->email = Auth::user()->email;
            $payment_trans->plan_id = $plan_id;
            $payment_trans->gateway = 'NA';
            $payment_trans->payment_amount = $plan_amount;
            $payment_trans->payment_id = '-';
            $payment_trans->date = strtotime(date('m/d/Y H:i:s'));                    
            $payment_trans->save();

            Session::flash('plan_id',Session::get('plan_id'));

            \Session::flash('flash_message',trans('words.plan_purchse_success'));
             return redirect('dashboard');
        }


        Session::put('plan_id', $plan_id);
        Session::flash('razorpay_order_id',Session::get('razorpay_order_id'));
          
        return view('pages.payment.payment_method',compact('plan_info'));
    }
 
}
