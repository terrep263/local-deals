<?php

namespace App\Http\Controllers;

use App\Services\PricingService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    private PricingService $pricingService;
    
    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }
    
    public function index()
    {
        $plans = $this->pricingService->getPlanComparison();
        $founderSlotsLeft = $this->pricingService->founderSlotsAvailable();
        
        return view('pricing', compact('plans', 'founderSlotsLeft'));
    }
}
