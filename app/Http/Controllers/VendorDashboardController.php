<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class VendorDashboardController extends Controller
{
    public function index()
    {
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
            DB::raw('AVG((SELECT DATEDIFF(CURDATE(), MAX(purchase_date)) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_recency'),
            DB::raw('COUNT(*) as count')
        )
        ->whereNotNull('segment')
        ->groupBy('segment')
        ->get();

        return view('dashboards.vendor.index', compact('customerSegmentCounts', 'segmentNames', 'segmentSummaries'));
    }
} 