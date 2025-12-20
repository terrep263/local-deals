<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Categories;
use App\Models\Listings;
use App\Models\SubscriptionPlan;

use Session;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\Crypt;

class IndexController extends Controller
{

    public function index()
    { 
    	if(!$this->alreadyInstalled())
        {
            return redirect('public/install');
        }

        // Featured Deals (featured listings with active deals)
        $featuredDeals = \DB::table('listings')
            ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
            ->leftJoin('location', 'listings.location_id', '=', 'location.id')
            ->select(
                'listings.*',
                'categories.category_name as category_name',
                'location.location_name as city_name'
            )
            ->where('listings.status', 1)
            ->where(function($q) {
                $q->where('listings.deal_expires_at', '>', now())
                  ->orWhereNull('listings.deal_expires_at');
            })
            ->where(function($q) {
                $q->whereNotNull('listings.deal_price')
                  ->where('listings.deal_price', '>', 0);
            })
            ->orderByDesc('listings.featured_listing')
            ->orderByDesc('listings.created_at')
            ->limit(8)
            ->get();

        // Hot Deals (Highest discount percentage)
        $hotDeals = \DB::table('listings')
            ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
            ->leftJoin('location', 'listings.location_id', '=', 'location.id')
            ->select(
                'listings.*',
                'categories.category_name as category_name',
                'location.location_name as city_name'
            )
            ->where('listings.status', 1)
            ->where(function($q) {
                $q->where('listings.deal_expires_at', '>', now())
                  ->orWhereNull('listings.deal_expires_at');
            })
            ->where(function($q) {
                $q->whereNotNull('listings.deal_price')
                  ->where('listings.deal_price', '>', 0);
            })
            ->whereNotNull('listings.deal_discount_percentage')
            ->orderByDesc('listings.deal_discount_percentage')
            ->limit(6)
            ->get();

        // Ending Soon (Within 7 days)
        $endingSoon = \DB::table('listings')
            ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
            ->leftJoin('location', 'listings.location_id', '=', 'location.id')
            ->select(
                'listings.*',
                'categories.category_name as category_name',
                'location.location_name as city_name'
            )
            ->where('listings.status', 1)
            ->where('listings.deal_expires_at', '>', now())
            ->where('listings.deal_expires_at', '<', now()->addDays(7))
            ->whereNotNull('listings.deal_price')
            ->orderBy('listings.deal_expires_at')
            ->limit(6)
            ->get();

        // New Deals (Last 14 days)
        $newDeals = \DB::table('listings')
            ->leftJoin('categories', 'listings.cat_id', '=', 'categories.id')
            ->leftJoin('location', 'listings.location_id', '=', 'location.id')
            ->select(
                'listings.*',
                'categories.category_name as category_name',
                'location.location_name as city_name'
            )
            ->where('listings.status', 1)
            ->where('listings.created_at', '>', now()->subDays(14))
            ->where(function($q) {
                $q->where('listings.deal_expires_at', '>', now())
                  ->orWhereNull('listings.deal_expires_at');
            })
            ->whereNotNull('listings.deal_price')
            ->orderByDesc('listings.created_at')
            ->limit(6)
            ->get();

        // Cities for search dropdown (legacy location table)
        $cities = \DB::table('location')
            ->orderBy('location_slug')
            ->orderBy('location_name')
            ->get();

        return view('pages.index', compact(
            'featuredDeals',
            'hotDeals',
            'endingSoon',
            'newDeals',
            'cities'
        ));
    }
 
    public function about_us()
    { 
                  
        return view('pages.extra.about');
    }
 
    public function termsandconditions()
    { 
                  
        return view('pages.extra.terms');
    }

    public function privacypolicy()
    { 
                  
        return view('pages.extra.privacy');
    }

    public function contact_us()
    {        
        return view('pages.extra.contact');
    }

    public function contact_send(Request $request)
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
            'subject' => $inputs['subject'],
            'user_message' => $inputs['message'],
             );

            $subject=$inputs['subject'];
            
           $from_email= getenv("MAIL_FROM_ADDRESS"); 

            try{ 

            \Mail::send('emails.contact', $data, function ($message) use ($subject,$from_email){

                $message->from($from_email, getcong('site_name'));

                $message->to(getcong('site_email'))->subject($subject);

            });

            }catch (\Throwable $e) {
                
                \Log::info($e->getMessage()); 
                
                \Session::flash('flash_message',$e->getMessage());
                return \Redirect::back();
                        
            }
             
            \Session::flash('flash_message', trans('words.contact_msg'));
            return \Redirect::back();
         
    }    


    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {   
         
        return file_exists(base_path('/public/.lic'));
    }
 
      
}
