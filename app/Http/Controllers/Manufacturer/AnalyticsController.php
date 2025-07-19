<?php

namespace App\Http\Controllers\Manufacturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Fetch vendor segmentation analytics (similar to vendorSegmentationPage)
        $vendors = DB::table('vendors')->get();
        $segmentAnalytics = [];
        $vendorAnalytics = [];
        $segmentLabels = [
            'Platinum' => 'Platinum',
            'Gold' => 'Gold',
            'Silver' => 'Silver',
        ];
        $segmentColors = [
            'Platinum' => '#E5C100',
            'Gold' => '#FFD700',
            'Silver' => '#C0C0C0',
        ];
        $segments = $vendors->groupBy('segment_name');
        foreach ($segments as $segment => $segmentVendors) {
            $segmentAnalytics[$segment] = [
                'count' => $segmentVendors->count(),
                'total_orders' => $segmentVendors->sum('total_orders'),
                'total_value' => $segmentVendors->sum(function($vendor) {
                    return (float) preg_replace('/[^\d.]/', '', $vendor->total_value);
                }),
            ];
        }
        foreach ($vendors as $vendor) {
            $vendorAnalytics[] = [
                'name' => $vendor->name,
                'segment' => $vendor->segment_name,
                'total_orders' => $vendor->total_orders,
                'most_ordered_product' => $vendor->most_ordered_product,
                'fulfillment_rate' => $vendor->fulfillment_rate,
                'total_value' => $vendor->total_value,
                'order_frequency' => $vendor->order_frequency,
                'cancellation_rate' => $vendor->cancellation_rate,
            ];
        }
        $orders = DB::table('vendor_orders')->get();
        $topProducts = $orders->groupBy('product')->map(function($orders) {
            return $orders->count();
        })->sortDesc()->take(5)->toArray();
        // Render PDF view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.vendor-segmentation-pdf', [
            'segmentAnalytics' => $segmentAnalytics,
            'vendorAnalytics' => $vendorAnalytics,
            'segmentLabels' => $segmentLabels,
            'segmentColors' => $segmentColors,
            'topProducts' => $topProducts,
        ]);
        return $pdf->download('vendor-segmentation-analytics.pdf');
    }
} 