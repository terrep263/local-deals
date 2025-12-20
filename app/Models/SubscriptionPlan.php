<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plan';

    protected $fillable = ['plan_name'];


	public $timestamps = false;
 
	
	public static function getSubscriptionPlanInfo($id) 
    { 	
    	if($id==0)
    	{
    		$plan_data=SubscriptionPlan::find(1);	

			return $plan_data;
    	}
    	else
    	{
    		$plan_data=SubscriptionPlan::find($id);	

			return $plan_data;
    	}
 
	}

	public static function getPlanDuration($id) 
    { 
		$plan_obj = SubscriptionPlan::find($id);

		if($plan_obj->plan_duration_type==1)
		{
			$plan_duration_type='Day(s)';
		}
		else if($plan_obj->plan_duration_type==30)
		{
			$plan_duration_type='Month(s)';
		}
		else
		{
			$plan_duration_type='Year(s)';
		}

		$duration_final=$plan_obj->plan_duration.' '.$plan_duration_type; 

		return $duration_final;
	}
	
}
