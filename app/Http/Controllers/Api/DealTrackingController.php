<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealTrackingController extends Controller
{
    public function trackClick($id): JsonResponse
    {
        try {
            $listing = Listings::findOrFail($id);
            if (\Schema::hasColumn('listings', 'click_count')) {
                $listing->increment('click_count');
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 404);
        }
    }
}

