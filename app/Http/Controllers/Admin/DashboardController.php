<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Listings;
use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\City;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Models\Deal;
use App\Models\SupportTicket;
use App\Models\Subscription;
use App\Models\DealPurchase;
 
use App\Http\Requests;
use Illuminate\Http\Request;


class DashboardController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');	
         
    }
    public function index()
    { 
    	 if(Auth::user()->usertype=='Admin')	
          {  
        		$categories = Categories::count(); 
        		$subcategories = SubCategories::count(); 
        	 	$location = City::count(); 
        	 	$users = User::where('usertype', 'User')->count();
                $listings = Listings::count();

                $plans = SubscriptionPlan::count();
                $transactions = PaymentTransaction::count();
                
                // Module 6: New Stats (with error handling)
                try {
                    $deals = Deal::count();
                    $pendingDeals = Deal::where('status', 'pending_approval')->count();
                    $activeDeals = Deal::where('status', 'active')->count();
                } catch (\Exception $e) {
                    $deals = 0;
                    $pendingDeals = 0;
                    $activeDeals = 0;
                }
                
                $vendors = User::where('usertype', '!=', 'admin')->count();
                
                try {
                    $activeSubscriptions = Subscription::where('status', 'active')->count();
                } catch (\Exception $e) {
                    $activeSubscriptions = 0;
                }
                
                try {
                    $openTickets = SupportTicket::where('status', 'open')->count();
                } catch (\Exception $e) {
                    $openTickets = 0;
                }
                
                try {
                    $totalRevenue = DealPurchase::sum('purchase_amount') ?? 0;
                    $todayRevenue = DealPurchase::whereDate('purchase_date', today())->sum('purchase_amount') ?? 0;
                } catch (\Exception $e) {
                    $totalRevenue = 0;
                    $todayRevenue = 0;
                }

            return view('admin.pages.dashboard',compact(
                'categories','subcategories','location','users','listings','plans','transactions',
                'deals','pendingDeals','activeDeals','vendors','activeSubscriptions','openTickets',
                'totalRevenue','todayRevenue'
            ));

	      }
   
    	
        
    }
	
	 
    	
}
