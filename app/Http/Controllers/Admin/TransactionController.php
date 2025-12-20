<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\PaymentTransaction;
use App\Models\PaymentGateway;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;

class TransactionController extends MainAdminController
{
    public function __construct()
    {
         $this->middleware('auth');
          
        parent::__construct();  
          
    }
    public function transaction_list()    
    { 
        
        if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }          
        //$videos = Videos::orderBy('id','DESC')->paginate(10);
 
        if(isset($_GET['s']))
        {   
            $keyword = $_GET['s'];  

            $transaction = DB::table('transaction')                            
                           ->where("transaction.email", "LIKE","%$keyword%")
                           ->orwhere("transaction.payment_id", "LIKE","%$keyword%")
                           ->orderBy('id','DESC')
                           ->paginate(15); 

            $transaction->appends(\Request::only('s'))->links();              
        }
        else if(isset($_GET['gateway']))
        {
            $gateway = $_GET['gateway'];  

            $transaction = DB::table('transaction')
                           ->where("transaction.gateway", "=",$gateway)
                           ->orderBy('id','DESC')
                           ->paginate(15); 

            $transaction->appends(\Request::only('gateway'))->links();
        }
        else
        {
            $transaction = DB::table('transaction')                           
                           ->orderBy('id','DESC')
                           ->paginate(15);
        }

        $gateway_list = PaymentGateway::orderBy('id')->get();
          
        return view('admin.pages.transaction',compact('transaction','gateway_list'));
    }
 
    
        
}
