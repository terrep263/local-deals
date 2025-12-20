<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorSubscriptionController extends Controller
{
    public function subscribe($id, Request $request): JsonResponse
    {
        // Placeholder subscription endpoint; implement persistence as needed
        return response()->json(['success' => true]);
    }
}

