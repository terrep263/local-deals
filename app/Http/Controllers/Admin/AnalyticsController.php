<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealPurchase;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display admin platform analytics
     */
    public function index(Request $request)
    {
        // Platform Overview
        try {
            $totalActiveDeals = Deal::where('status', 'active')->count();
        } catch (\Exception $e) {
            $totalActiveDeals = 0;
        }
        
        try {
            $totalDealsThisMonth = Deal::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } catch (\Exception $e) {
            $totalDealsThisMonth = 0;
        }
        
        try {
            $totalGMV = DealPurchase::sum('purchase_amount') ?? 0;
        } catch (\Exception $e) {
            $totalGMV = 0;
        }
        
        try {
            $totalVendors = User::whereHas('deals')->count();
        } catch (\Exception $e) {
            // Fallback if deals relationship doesn't exist
            $totalVendors = User::where('usertype', '!=', 'admin')->count();
        }
        
        try {
            $activeSubscriptions = Subscription::where('status', 'active')->count();
        } catch (\Exception $e) {
            $activeSubscriptions = 0;
        }
        try {
            $mrr = Subscription::where('status', 'active')
                ->join('package_features', 'subscriptions.package_tier', '=', 'package_features.package_tier')
                ->sum('package_features.monthly_price');
        } catch (\Exception $e) {
            // If package_features table doesn't exist, calculate MRR differently
            $mrr = 0;
        }
        
        $dealsCreatedThisMonth = $totalDealsThisMonth;
        
        try {
            $revenueThisMonth = DealPurchase::whereMonth('purchase_date', now()->month)
                ->whereYear('purchase_date', now()->year)
                ->sum('purchase_amount') ?? 0;
        } catch (\Exception $e) {
            $revenueThisMonth = 0;
        }
        
        try {
            $avgDealPrice = Deal::where('status', 'active')->avg('deal_price') ?? 0;
        } catch (\Exception $e) {
            $avgDealPrice = 0;
        }
        $platformRevenue = $mrr; // Subscription revenue

        // Growth charts data (last 12 months)
        $months = collect(range(11, 0))->map(function($i) {
            return now()->subMonths($i);
        });

        try {
            $newVendorsPerMonth = $months->map(function($month) {
                return User::whereHas('deals')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            });
        } catch (\Exception $e) {
            // Fallback if deals relationship doesn't exist
            $newVendorsPerMonth = $months->map(function($month) {
                return User::where('usertype', '!=', 'admin')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            });
        }

        try {
            $dealsCreatedPerMonth = $months->map(function($month) {
                return Deal::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            });
        } catch (\Exception $e) {
            $dealsCreatedPerMonth = $months->map(fn() => 0);
        }

        try {
            $gmvPerMonth = $months->map(function($month) {
                return DealPurchase::whereYear('purchase_date', $month->year)
                    ->whereMonth('purchase_date', $month->month)
                    ->sum('purchase_amount') ?? 0;
            });
        } catch (\Exception $e) {
            $gmvPerMonth = $months->map(fn() => 0);
        }

        try {
            $subscriptionRevenuePerMonth = $months->map(function($month) {
                return Subscription::where('status', 'active')
                    ->whereYear('created_at', '<=', $month->year)
                    ->whereMonth('created_at', '<=', $month->month)
                    ->join('package_features', 'subscriptions.package_tier', '=', 'package_features.package_tier')
                    ->sum('package_features.monthly_price');
            });
        } catch (\Exception $e) {
            $subscriptionRevenuePerMonth = $months->map(fn() => 0);
        }

        // Category performance
        try {
            $categoryPerformance = Categories::all()->map(function($category) {
                try {
                    $deals = Deal::where('category_id', $category->id)->pluck('id');
                    $activeDeals = Deal::where('category_id', $category->id)->where('status', 'active')->count();
                    $totalSales = DealPurchase::whereIn('deal_id', $deals)->count();
                    $avgDiscount = Deal::where('category_id', $category->id)
                        ->where('status', 'active')
                        ->avg('discount_percentage');
                    
                    return [
                        'name' => $category->category_name,
                        'active_deals' => $activeDeals,
                        'total_sales' => $totalSales,
                        'avg_discount' => round($avgDiscount ?? 0, 1),
                    ];
                } catch (\Exception $e) {
                    return [
                        'name' => $category->category_name,
                        'active_deals' => 0,
                        'total_sales' => 0,
                        'avg_discount' => 0,
                    ];
                }
            })->sortByDesc('total_sales');
        } catch (\Exception $e) {
            $categoryPerformance = collect([]);
        }

        // Vendor leaderboards
        try {
            $topVendorsByRevenue = User::whereHas('deals')
                ->with('deals')
                ->get()
                ->map(function($user) {
                    $dealIds = $user->deals->pluck('id');
                    $revenue = DealPurchase::whereIn('deal_id', $dealIds)->sum('purchase_amount');
                    $activeDeals = $user->deals->where('status', 'active')->count();
                    
                    return [
                        'user' => $user,
                        'revenue' => $revenue,
                        'active_deals' => $activeDeals,
                    ];
                })
                ->sortByDesc('revenue')
                ->take(20);

            $topVendorsBySales = User::whereHas('deals')
                ->with('deals')
                ->get()
                ->map(function($user) {
                    $dealIds = $user->deals->pluck('id');
                    $sales = DealPurchase::whereIn('deal_id', $dealIds)->count();
                    $activeDeals = $user->deals->where('status', 'active')->count();
                    
                    return [
                        'user' => $user,
                        'sales' => $sales,
                        'active_deals' => $activeDeals,
                    ];
                })
                ->sortByDesc('sales')
                ->take(20);
        } catch (\Exception $e) {
            // Fallback if deals relationship doesn't exist
            $topVendorsByRevenue = collect([]);
            $topVendorsBySales = collect([]);
        }

        // Geographic insights
        try {
            $dealsByCity = Deal::where('status', 'active')
                ->select('location_city', DB::raw('count(*) as count'))
                ->groupBy('location_city')
                ->orderByDesc('count')
                ->get();
        } catch (\Exception $e) {
            $dealsByCity = collect([]);
        }

        try {
            $salesByZip = DealPurchase::join('deals', 'deal_purchases.deal_id', '=', 'deals.id')
                ->select('deals.location_zip', DB::raw('count(*) as count'))
                ->groupBy('deals.location_zip')
                ->orderByDesc('count')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $salesByZip = collect([]);
        }

        // Subscription metrics
        try {
            $subscriptionBreakdown = DB::table('subscriptions')
                ->join('package_features', 'subscriptions.package_tier', '=', 'package_features.package_tier')
                ->where('subscriptions.status', 'active')
                ->select('package_features.package_tier', DB::raw('count(*) as count'))
                ->groupBy('package_features.package_tier')
                ->get();
        } catch (\Exception $e) {
            // Fallback if package_features table doesn't exist
            $subscriptionBreakdown = Subscription::where('status', 'active')
                ->select('package_tier', DB::raw('count(*) as count'))
                ->groupBy('package_tier')
                ->get()
                ->map(function($item) {
                    return (object)['package_tier' => $item->package_tier, 'count' => $item->count];
                });
        }

        $monthLabels = $months->map(fn($m) => $m->format('M Y'))->toArray();

        return view('admin.analytics.index', compact(
            'totalActiveDeals', 'totalDealsThisMonth', 'totalGMV', 'totalVendors',
            'activeSubscriptions', 'mrr', 'revenueThisMonth', 'avgDealPrice', 'platformRevenue',
            'monthLabels', 'newVendorsPerMonth', 'dealsCreatedPerMonth', 'gmvPerMonth', 'subscriptionRevenuePerMonth',
            'categoryPerformance', 'topVendorsByRevenue', 'topVendorsBySales',
            'dealsByCity', 'salesByZip', 'subscriptionBreakdown'
        ));
    }

    /**
     * Export admin analytics as CSV
     */
    public function exportCsv()
    {
        $filename = 'platform-analytics-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Deals sheet
            fputcsv($file, ['Deal ID', 'Title', 'Vendor', 'Status', 'Price', 'Views', 'Clicks', 'Sales', 'Revenue']);
            
            try {
                $deals = Deal::with('vendor')->get();
                foreach ($deals as $deal) {
                    try {
                        $sales = DealPurchase::where('deal_id', $deal->id)->count();
                        $revenue = DealPurchase::where('deal_id', $deal->id)->sum('purchase_amount') ?? 0;
                        $vendorName = $deal->vendor ? ($deal->vendor->first_name . ' ' . $deal->vendor->last_name) : 'N/A';
                        fputcsv($file, [
                            $deal->id ?? 'N/A',
                            $deal->title ?? 'N/A',
                            $vendorName,
                            $deal->status ?? 'N/A',
                            $deal->deal_price ?? 0,
                            $deal->view_count ?? 0,
                            $deal->click_count ?? 0,
                            $sales,
                            $revenue,
                        ]);
                    } catch (\Exception $e) {
                        // Skip this deal if there's an error
                        continue;
                    }
                }
            } catch (\Exception $e) {
                // If deals table doesn't exist, just write header
                fputcsv($file, ['No data available']);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

