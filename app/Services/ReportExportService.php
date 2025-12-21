<?php

namespace App\Services;

use App\Models\User;
use App\Models\Deal;
use App\Models\DealPurchase;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportExportService
{
    /**
     * Export vendor growth report to CSV
     */
    public function exportVendorGrowth($startDate, $endDate, $period = 'month')
    {
        $data = $this->getVendorGrowthData($startDate, $endDate, $period);
        
        $filename = 'vendor-growth-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Period', 'New Vendors', 'Total Vendors']);
            
            $totalVendors = 0;
            foreach ($data as $row) {
                $totalVendors += $row['new_vendors'];
                fputcsv($file, [
                    $row['period'],
                    $row['new_vendors'],
                    $totalVendors
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export deal performance report to CSV
     */
    public function exportDealPerformance()
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
        
        $filename = 'deal-performance-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($deals) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Category', 'Total Deals', 'Active Deals', 'Sold Out Deals']);
            
            foreach ($deals as $deal) {
                fputcsv($file, [
                    $deal->category->name ?? 'Uncategorized',
                    $deal->total_deals,
                    $deal->active_deals,
                    $deal->sold_out_deals
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export revenue report to CSV
     */
    public function exportRevenue($startDate, $endDate, $period = 'month')
    {
        $data = $this->getRevenueData($startDate, $endDate, $period);
        
        $filename = 'revenue-report-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Period', 'GMV', 'MRR']);
            
            foreach ($data as $row) {
                fputcsv($file, [
                    $row['period'],
                    '$' . number_format($row['gmv'], 2),
                    '$' . number_format($row['mrr'], 2)
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export top performers report to CSV
     */
    public function exportTopPerformers()
    {
        $topVendors = User::where('usertype', '!=', 'admin')
            ->withCount(['deals' => function($query) {
                $query->where('status', 'active');
            }])
            ->get()
            ->map(function($vendor) {
                $dealIds = $vendor->deals()->pluck('id');
                $vendor->revenue = DealPurchase::whereIn('deal_id', $dealIds)->sum('purchase_amount');
                return $vendor;
            })
            ->sortByDesc('revenue')
            ->take(20)
            ->values();
        
        $filename = 'top-performers-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($topVendors) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Rank', 'Vendor Name', 'Email', 'Active Deals', 'Total Revenue']);
            
            $rank = 1;
            foreach ($topVendors as $vendor) {
                fputcsv($file, [
                    $rank++,
                    $vendor->name,
                    $vendor->email,
                    $vendor->deals_count,
                    '$' . number_format($vendor->revenue, 2)
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get vendor growth data
     */
    private function getVendorGrowthData($startDate, $endDate, $period)
    {
        $data = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current <= $end) {
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

        return $data;
    }

    /**
     * Get revenue data
     */
    private function getRevenueData($startDate, $endDate, $period)
    {
        $data = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current <= $end) {
            $periodEnd = match($period) {
                'day' => $current->copy()->endOfDay(),
                'week' => $current->copy()->endOfWeek(),
                'month' => $current->copy()->endOfMonth(),
            };

            $gmv = DealPurchase::whereBetween('purchase_date', [$current, $periodEnd])
                ->sum('purchase_amount');

            $data[] = [
                'period' => $current->format($period === 'day' ? 'Y-m-d' : ($period === 'week' ? 'Y-W' : 'Y-m')),
                'gmv' => $gmv,
                'mrr' => 0,
            ];

            $current = match($period) {
                'day' => $current->addDay(),
                'week' => $current->addWeek(),
                'month' => $current->addMonth(),
            };
        }

        return $data;
    }
}
