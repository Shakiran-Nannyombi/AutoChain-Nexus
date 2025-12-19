<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\RetailerStock;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorActivity;
use App\Models\RetailerOrder; // Added this import

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        // Dynamic: Pending Orders from Retailers
        $pendingOrders = RetailerStock::where('vendor_id', $vendorId)
            ->where('status', 'to_be_packed')
            ->count();
        // Dynamic: Active Car Models
        $activeProducts = Product::where('vendor_id', $vendorId)->count();
        // Dynamic: Retailer Customers (unique customers who purchased from this vendor)
        $totalCustomers = Purchase::where('vendor_id', $vendorId)->distinct('customer_id')->count('customer_id');
        // Dynamic: Monthly Revenue (sum of purchases for this vendor in current month)
        $monthlyRevenue = Purchase::where('vendor_id', $vendorId)
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('amount');

        // Fetch recent activities for the vendor
        $recentActivities = VendorActivity::where('vendor_id', $vendorId)
            ->latest()
            ->take(5)
            ->get();

        // Dynamic: Best selling cars from retailer orders
        $bestSellingCars = RetailerOrder::where('vendor_id', $vendorId)
            ->select('car_model', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('car_model')
            ->orderByDesc('total_sold')
            ->limit(4)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'id' => $index + 1,
                    'car_model' => $item->car_model,
                    'manufacturer' => explode(' ', $item->car_model)[0], // Extract manufacturer from car model
                    'price' => rand(19000,25000), // Random price for demo
                    'stock' => rand(1, 5), // Random stock for demo
                    'units_sold' => $item->total_sold
                ];
            });

        $customerSegmentCounts = Customer::select('segment', DB::raw('count(*) as count'))
            ->whereNotNull('segment')
            ->groupBy('segment')
            ->get();

        $segmentNames = [
            1 => 'Occasional Buyers',
            2 => 'High Value Customers',
            3 => 'At Risk Customers',
        ];

        $segmentSummaries = Customer::select(
            'segment',
            DB::raw('AVG((SELECT SUM(amount) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_total_spent'),
            DB::raw('AVG((SELECT COUNT(* ) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_purchases'),
            DB::raw('AVG((SELECT CAST(julianday("now") - julianday(MAX(purchase_date)) AS INTEGER) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_recency'),
            DB::raw('COUNT(*) as count')
        )
        ->whereNotNull('segment')
        ->groupBy('segment')
        ->get();

        // Notifications for header
        $user = Auth::user();
        $unreadNotifications = $user ? $user->unreadNotifications()->take(5)->get() : collect();
        $allNotifications = $user ? $user->notifications()->take(10)->get() : collect();

        $realTimeOrders = \App\Models\RetailerOrder::where('vendor_id', $vendorId)
            ->with('retailer')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboards.vendor.index', compact(
            'customerSegmentCounts',
            'segmentNames',
            'segmentSummaries',
            'activeProducts',
            'pendingOrders',
            'totalCustomers',
            'monthlyRevenue',
            'recentActivities',
            'bestSellingCars',
            'unreadNotifications',
            'allNotifications',
            'realTimeOrders' // add this
        ));
    }

    public function customerSegmentation()
    {
        $customerSegmentCounts = \App\Models\Customer::select('segment', \DB::raw('count(*) as count'))
            ->whereNotNull('segment')
            ->groupBy('segment')
            ->get();

        $segmentNames = [
            1 => 'Occasional Buyers',
            2 => 'High Value Customers',
            3 => 'At Risk Customers',
        ];

        $segmentSummaries = \App\Models\Customer::select(
            'segment',
            \DB::raw('AVG((SELECT SUM(amount) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_total_spent'),
            \DB::raw('AVG((SELECT COUNT(*) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_purchases'),
            \DB::raw('AVG((SELECT CAST(julianday("now") - julianday(MAX(purchase_date)) AS INTEGER) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_recency'),
            \DB::raw('COUNT(*) as count')
        )
        ->whereNotNull('segment')
        ->groupBy('segment')
        ->get();

        return view('dashboards.vendor.customer-segmentation', compact(
            'customerSegmentCounts', 'segmentNames', 'segmentSummaries'
        ));
    }
} 