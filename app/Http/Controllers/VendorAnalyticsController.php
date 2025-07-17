<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RetailerOrder;
use App\Models\RetailerStock;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class VendorAnalyticsController extends Controller
{
    public function dashboard()
    {
        $vendorId = Auth::id();
        $toBePacked = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_packed')->count();
        $toBeShipped = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_shipped')->count();
        $toBeDelivered = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_delivered')->count();
        $toBeInvoiced = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_invoiced')->count();

        // Dynamic: Get top selling items from retailer orders
        $topSellingItems = RetailerOrder::where('vendor_id', $vendorId)
            ->select('car_model', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('car_model')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'image' => '/images/car' . rand(1, 5) . '.png', // Random image for demo
                    'name' => $item->car_model,
                    'quantity' => $item->total_quantity,
                ];
            })
            ->toArray();

        // If no orders exist, provide some default data
        if (empty($topSellingItems)) {
            $topSellingItems = [
                [
                    'image' => '/images/car1.png',
                    'name' => 'Toyota Corolla 2024',
                    'quantity' => 0,
                ],
                [
                    'image' => '/images/car2.png',
                    'name' => 'Honda CR-V 2024',
                    'quantity' => 0,
                ],
                [
                    'image' => '/images/car3.png',
                    'name' => 'Ford F-150 2024',
                    'quantity' => 0,
                ],
                [
                    'image' => '/images/car4.png',
                    'name' => 'BMW 3 Series 2024',
                    'quantity' => 0,
                ],
                [
                    'image' => '/images/car5.png',
                    'name' => 'Mercedes-Benz C-Class 2024',
                    'quantity' => 0,
                ],
            ];
        }

        return view('dashboards.vendor.analytics', [
            'toBePacked' => $toBePacked,
            'toBeShipped' => $toBeShipped,
            'toBeDelivered' => $toBeDelivered,
            'toBeInvoiced' => $toBeInvoiced,
            'allNotifications' => collect(),
            'topSellingItems' => $topSellingItems,
        ]);
    }
} 