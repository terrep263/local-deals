<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealPurchase;
use App\Models\DealDailyStat;
use App\Models\AnalyticsEvent;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->middleware('auth');
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display analytics dashboard based on subscription tier
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $packageFeatures = $this->subscriptionService->getUserPackageFeatures($user);
        
        // Check if user has analytics access
        if (!$this->subscriptionService->checkFeatureAccess($user, 'analytics_access')) {
            \Session::flash('error_flash_message', 'Analytics requires Basic plan or higher. Upgrade now!');
            return redirect()->route('pricing');
        }

        // Get time range from request or session
        $timeRange = $request->get('time_range', session('analytics_time_range', '30'));
        session(['analytics_time_range' => $timeRange]);
        
        $startDate = $this->getStartDate($timeRange, $request);
        $endDate = now();

        // Determine which view to show based on tier
        $isPro = ($packageFeatures && in_array($packageFeatures->package_tier, ['pro', 'enterprise']));

        if ($isPro) {
            return $this->showProAnalytics($user, $startDate, $endDate, $packageFeatures);
        } else {
            return $this->showBasicAnalytics($user);
        }
    }

    /**
     * Basic analytics for Basic tier
     */
    protected function showBasicAnalytics($user)
    {
        $deals = Deal::where('vendor_id', $user->id)->get();
        
        $totalViews = $deals->sum('view_count');
        $totalClicks = $deals->sum('click_count');
        $totalPurchases = DealPurchase::whereIn('deal_id', $deals->pluck('id'))->count();
        
        $ctr = $totalViews > 0 ? ($totalClicks / $totalViews) * 100 : 0;
        $conversionRate = $totalClicks > 0 ? ($totalPurchases / $totalClicks) * 100 : 0;

        // Top performing deals
        $topDeals = Deal::where('vendor_id', $user->id)
            ->withCount(['purchases'])
            ->orderBy('purchases_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($deal) {
                $revenue = DealPurchase::where('deal_id', $deal->id)
                    ->sum('purchase_amount');
                $deal->revenue = $revenue;
                return $deal;
            });

        // Recent activity
        $recentPurchases = DealPurchase::whereIn('deal_id', $deals->pluck('id'))
            ->with('deal')
            ->orderBy('purchase_date', 'desc')
            ->limit(20)
            ->get();

        return view('vendor.analytics.basic', compact(
            'totalViews', 'totalClicks', 'totalPurchases', 
            'ctr', 'conversionRate', 'topDeals', 'recentPurchases'
        ));
    }

    /**
     * Pro analytics with charts
     */
    protected function showProAnalytics($user, $startDate, $endDate, $packageFeatures)
    {
        $deals = Deal::where('vendor_id', $user->id)->get();
        $dealIds = $deals->pluck('id');

        // Enhanced metrics
        $totalRevenue = DealPurchase::whereIn('deal_id', $dealIds)
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->sum('purchase_amount');
        
        $totalDeals = $deals->count();
        $avgDealValue = $totalDeals > 0 ? $totalRevenue / $totalDeals : 0;
        
        $avgDiscount = $deals->where('status', 'active')->avg('discount_percentage') ?? 0;
        
        $totalInventory = $deals->sum('inventory_total');
        $totalSold = $deals->sum('inventory_sold');
        $inventoryUtilization = $totalInventory > 0 ? ($totalSold / $totalInventory) * 100 : 0;
        
        $revenuePerDeal = $totalDeals > 0 ? $totalRevenue / $totalDeals : 0;

        // Get daily stats for charts
        $dailyStats = DealDailyStat::whereIn('deal_id', $dealIds)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('date')
            ->selectRaw('date, SUM(views) as views, SUM(clicks) as clicks, SUM(purchases) as purchases, SUM(revenue) as revenue')
            ->orderBy('date')
            ->get();
        
        // If no daily stats, fall back to calculating from events (slower but works)
        if ($dailyStats->isEmpty()) {
            // Fill in dates array
            $dates = [];
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dates[] = $currentDate->format('Y-m-d');
                $currentDate->addDay();
            }
            
            $views = [];
            $clicks = [];
            $purchases = [];
            $revenue = [];
            
            foreach ($dates as $date) {
                $views[] = AnalyticsEvent::whereIn('deal_id', $dealIds)
                    ->where('event_type', 'view')
                    ->whereDate('created_at', $date)
                    ->count();
                $clicks[] = AnalyticsEvent::whereIn('deal_id', $dealIds)
                    ->where('event_type', 'click')
                    ->whereDate('created_at', $date)
                    ->count();
                $purchases[] = DealPurchase::whereIn('deal_id', $dealIds)
                    ->whereDate('purchase_date', $date)
                    ->count();
                $revenue[] = DealPurchase::whereIn('deal_id', $dealIds)
                    ->whereDate('purchase_date', $date)
                    ->sum('purchase_amount') ?? 0;
            }
            
            $dates = array_map(function($d) {
                return Carbon::parse($d)->format('M d');
            }, $dates);
        } else {
            $dates = $dailyStats->pluck('date')->map(fn($d) => $d->format('M d'))->toArray();
            $views = $dailyStats->pluck('views')->toArray();
            $clicks = $dailyStats->pluck('clicks')->toArray();
            $purchases = $dailyStats->pluck('purchases')->toArray();
            $revenue = $dailyStats->pluck('revenue')->toArray();
        }

        // Total metrics for time range
        $totalViews = array_sum($views);
        $totalClicks = array_sum($clicks);
        $totalPurchases = array_sum($purchases);

        // Deals by status
        $dealsByStatus = $deals->groupBy('status')->map->count();

        // Top deals for comparison
        $allDeals = Deal::where('vendor_id', $user->id)
            ->withCount(['purchases'])
            ->get()
            ->map(function($deal) {
                $deal->revenue = DealPurchase::where('deal_id', $deal->id)->sum('purchase_amount');
                $deal->ctr = $deal->view_count > 0 ? ($deal->click_count / $deal->view_count) * 100 : 0;
                $deal->conversion_rate = $deal->click_count > 0 ? ($deal->purchases_count / $deal->click_count) * 100 : 0;
                return $deal;
            });

        // Funnel data
        $funnelViews = $totalViews;
        $funnelClicks = $totalClicks;
        $funnelPurchases = $totalPurchases;
        $funnelCTR = $funnelViews > 0 ? ($funnelClicks / $funnelViews) * 100 : 0;
        $funnelConversion = $funnelClicks > 0 ? ($funnelPurchases / $funnelClicks) * 100 : 0;

        // AI insights (if enabled)
        $insights = [];
        if ($packageFeatures && $packageFeatures->ai_scoring_enabled) {
            $insights = $this->generateInsights($deals, $startDate, $endDate);
        }

        return view('vendor.analytics.pro', compact(
            'totalRevenue', 'avgDealValue', 'avgDiscount', 'inventoryUtilization',
            'revenuePerDeal', 'totalViews', 'totalClicks', 'totalPurchases',
            'dates', 'views', 'clicks', 'purchases', 'revenue',
            'dealsByStatus', 'allDeals', 'funnelViews', 'funnelClicks', 
            'funnelPurchases', 'funnelCTR', 'funnelConversion', 'insights',
            'startDate', 'endDate', 'timeRange'
        ));
    }

    /**
     * Generate AI-powered insights
     */
    protected function generateInsights($deals, $startDate, $endDate)
    {
        $insights = [];
        
        // Analyze by category
        $categoryPerformance = $deals->groupBy('category_id')->map(function($categoryDeals) {
            $totalSold = $categoryDeals->sum('inventory_sold');
            $avgDays = $categoryDeals->filter(fn($d) => $d->expires_at)->map(function($d) {
                return $d->created_at->diffInDays($d->expires_at);
            })->avg();
            return ['sold' => $totalSold, 'avg_days' => $avgDays];
        });
        
        if ($categoryPerformance->count() > 1) {
            $bestCategory = $categoryPerformance->sortByDesc('sold')->first();
            $insights[] = "Your best performing category has " . round($bestCategory['sold']) . " sales.";
        }

        // Day of week analysis
        $dayOfWeek = $deals->groupBy(function($deal) {
            return $deal->created_at->format('l');
        })->map->count();
        
        if ($dayOfWeek->count() > 0) {
            $bestDay = $dayOfWeek->sortByDesc(function($count) {
                return $count;
            })->keys()->first();
            $insights[] = "Deals posted on {$bestDay}s tend to perform better.";
        }

        // Average deal duration
        $avgDuration = $deals->filter(fn($d) => $d->expires_at)->map(function($d) {
            return $d->created_at->diffInDays($d->expires_at);
        })->avg();
        
        if ($avgDuration) {
            $insights[] = "Your average deal duration is " . round($avgDuration) . " days.";
        }

        return array_slice($insights, 0, 5); // Max 5 insights
    }

    /**
     * Get start date based on time range
     */
    protected function getStartDate($timeRange, $request)
    {
        if ($request->filled('date_from')) {
            return Carbon::parse($request->date_from);
        }

        switch ($timeRange) {
            case '7':
                return now()->subDays(7);
            case '30':
                return now()->subDays(30);
            case '90':
                return now()->subDays(90);
            case 'custom':
                return $request->filled('date_from') ? Carbon::parse($request->date_from) : now()->subDays(30);
            default:
                return now()->subDays(30);
        }
    }

    /**
     * Export analytics as CSV
     */
    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->subscriptionService->checkFeatureAccess($user, 'analytics_access')) {
            abort(403);
        }

        // Implementation will use Maatwebsite Excel
        // For now, return simple CSV
        $deals = Deal::where('vendor_id', $user->id)->get();
        
        $filename = 'deals-analytics-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($deals) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Deal Title', 'Views', 'Clicks', 'Purchases', 'Revenue', 'Start Date', 'End Date', 'Status']);
            
            // Data rows
            foreach ($deals as $deal) {
                $purchases = DealPurchase::where('deal_id', $deal->id)->count();
                $revenue = DealPurchase::where('deal_id', $deal->id)->sum('purchase_amount');
                
                fputcsv($file, [
                    $deal->title,
                    $deal->view_count,
                    $deal->click_count,
                    $purchases,
                    number_format($revenue, 2),
                    $deal->starts_at ? $deal->starts_at->format('Y-m-d') : 'N/A',
                    $deal->expires_at ? $deal->expires_at->format('Y-m-d') : 'N/A',
                    $deal->status,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export analytics as PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->subscriptionService->checkFeatureAccess($user, 'analytics_access')) {
            abort(403);
        }

        // Get metrics
        $deals = Deal::where('vendor_id', $user->id)->get();
        $totalViews = $deals->sum('view_count');
        $totalClicks = $deals->sum('click_count');
        $totalPurchases = DealPurchase::whereIn('deal_id', $deals->pluck('id'))->count();
        $totalRevenue = DealPurchase::whereIn('deal_id', $deals->pluck('id'))->sum('purchase_amount');

        $topDeals = Deal::where('vendor_id', $user->id)
            ->withCount(['purchases'])
            ->orderBy('purchases_count', 'desc')
            ->limit(10)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('vendor.analytics.report', compact(
            'user', 'totalViews', 'totalClicks', 'totalPurchases', 'totalRevenue', 'topDeals'
        ));

        return $pdf->download('analytics-report-' . now()->format('Y-m-d') . '.pdf');
    }
}

