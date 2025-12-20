<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'transaction';

    protected $fillable = ['email','plan_id','gateway','payment_amount','date'];


	public $timestamps = false;
  
	 
}
