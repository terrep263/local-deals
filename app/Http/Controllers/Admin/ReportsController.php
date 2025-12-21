<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deal;
use App\Models\DealPurchase;
use App\Models\Subscription;
use App\Services\ReportExportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    protected $exportService;

    public function __construct(ReportExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function vendorGrowth(Request $request)
    {
        $period = $request->get('period', 'month');
        
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)
            : Carbon::now()->subMonths(6);
        
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $data = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $periodEnd = match($period) {
                'day' => $current->copy()->endOfDay(),
                'week' => $current->copy()->endOfWeek(),
                'month' => $current->copy()->endOfMonth(),
            };

            $newVendors = User::where('usertype', '!=', 'admin')
                ->whereBetween('created_at', [$current, $periodEnd])
                ->count();

            $data[] = [
                'period' => $current->format($period === 'day' ? 'Y-m-d' : ($period === 'week' ? 'Y-W' : 'Y-m')),
                'new_vendors' => $newVendors,
            ];

            $current = match($period) {
                'day' => $current->addDay(),
                'week' => $current->addWeek(),
                'month' => $current->addMonth(),
            };
        }

        return response()->json($data);
    }

    public function dealPerformance(Request $request)
    {
        $deals = Deal::with('category')
            ->selectRaw('
                category_id,
                COUNT(*) as total_deals,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_deals,
                SUM(CASE WHEN status = "sold_out" THEN 1 ELSE 0 END) as sold_out_deals
            ')
            ->groupBy('category_id')
            ->get();

        return response()->json($deals);
    }

    public function revenue(Request $request)
    {
        $period = $request->get('period', 'month');
        
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->subMonths(6);
        
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $data = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $periodEnd = match($period) {
                'day' => $current->copy()->endOfDay(),
                'week' => $current->copy()->endOfWeek(),
                'month' => $current->copy()->endOfMonth(),
            };

            $gmv = DealPurchase::whereBetween('purchase_date', [$current, $periodEnd])
                ->sum('purchase_amount');

            $mrr = Subscription::where('status', 'active')
                ->where('created_at', '<=', $periodEnd)
                ->join('package_features', 'subscriptions.package_tier', '=', 'package_features.package_tier')
                ->sum('package_features.monthly_price');

            $data[] = [
                'period' => $current->format($period === 'day' ? 'Y-m-d' : ($period === 'week' ? 'Y-W' : 'Y-m')),
                'gmv' => $gmv,
                'mrr' => $mrr,
            ];

            $current = match($period) {
                'day' => $current->addDay(),
                'week' => $current->addWeek(),
                'month' => $current->addMonth(),
            };
        }

        return response()->json($data);
    }

    public function topPerformers()
    {
        $topVendors = User::where('usertype', '!=', 'admin')
            ->withCount(['deals' => function($query) {
                $query->where('status', 'active');
            }])
            ->get()
            ->map(function($vendor) {
                $vendor->revenue = DealPurchase::whereIn('deal_id', $vendor->deals()->pluck('id'))
                    ->sum('purchase_amount');
                return $vendor;
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values();

        $topDeals = Deal::with('vendor', 'category')
            ->withCount('purchases')
            ->orderByDesc('purchases_count')
            ->take(10)
            ->get();

        return response()->json([
            'vendors' => $topVendors,
            'deals' => $topDeals,
        ]);
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'csv');
        $report = $request->get('report');
        $startDate = $request->get('start_date', now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $period = $request->get('period', 'month');

        return match($report) {
            'vendor-growth' => $this->exportService->exportVendorGrowth($startDate, $endDate, $period),
            'deal-performance' => $this->exportService->exportDealPerformance(),
            'revenue' => $this->exportService->exportRevenue($startDate, $endDate, $period),
            'top-performers' => $this->exportService->exportTopPerformers(),
            default => response()->json(['error' => 'Invalid report type'], 400)
        };
    }
}
