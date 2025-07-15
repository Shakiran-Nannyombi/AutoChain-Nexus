<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RetailerOrder;
use App\Models\RetailerStock;

class VendorAnalyticsController extends Controller
{
    public function dashboard()
    {
        $vendorId = Auth::id();
        $toBePacked = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_packed')->count();
        $toBeShipped = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_shipped')->count();
        $toBeDelivered = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_delivered')->count();
        $toBeInvoiced = \App\Models\RetailerStock::where('vendor_id', $vendorId)->where('status', 'to_be_invoiced')->count();

        $topSellingItems = [
            [
                'image' => '/images/car1.png',
                'name' => 'Toyota Corolla 2022',
                'quantity' => 20080,
            ],
            [
                'image' => '/images/car2.png',
                'name' => 'Honda CR-V 2023',
                'quantity' => 18004,
            ],
            [
                'image' => '/images/car3.png',
                'name' => 'Ford F-150',
                'quantity' => 16370,
            ],
            [
                'image' => '/images/car4.png',
                'name' => 'BMW 3 Series',
                'quantity' => 11850,
            ],
            [
                'image' => '/images/car5.png',
                'name' => 'Chevrolet Camaro',
                'quantity' => 10090,
            ],
        ];
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