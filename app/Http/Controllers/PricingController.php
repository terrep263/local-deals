<?php

namespace App\Http\Controllers;

use App\Models\PackageFeature;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $features = PackageFeature::orderByRaw("FIELD(package_tier, 'starter', 'basic', 'pro')")->get();
        
        return view('pricing', compact('features'));
    }
}
