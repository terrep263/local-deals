<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('sort_order')->orderBy('name')->get(['id', 'name']);

        return view('home', compact('cities'));
    }
}

