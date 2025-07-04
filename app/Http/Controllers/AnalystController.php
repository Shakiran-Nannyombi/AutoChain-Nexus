<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerSale;
use App\Models\SupplierStock;
use App\Models\RetailerStock;
use App\Models\AnalystReport;
use Illuminate\Support\Facades\DB;

class AnalystController extends Controller
{
    public function dashboard()
    {
        $totalReports = AnalystReport::count();

        $dataPoints = RetailerSale::count() +
                      RetailerStock::count() +
                      SupplierStock::count();

        $trends = RetailerSale::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
                    ->groupBy('month')
                    ->count();

        $accuracy = '95%'; // Placeholder or calculated from model later

        return view('dashboards.analyst.index', compact(
            'totalReports', 'dataPoints', 'trends', 'accuracy'
        ));
    }

    public function salesAnalysis()
{
    // Total sales by car model
    $salesByModel = RetailerSale::select('car_model', DB::raw('SUM(quantity_sold) as total_sold'))
        ->groupBy('car_model')
        ->orderByDesc('total_sold')
        ->get();

    // Monthly sales trend
    $monthlySales = RetailerSale::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(quantity_sold) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

    return view('dashboards.analyst.sales-analysis', compact('salesByModel', 'monthlySales'));
}

public function inventoryAnalysis()
{
    // Supplier materials
    $supplierStocks = SupplierStock::select('material_name', DB::raw('SUM(quantity) as total_quantity'))
        ->groupBy('material_name')
        ->orderByDesc('total_quantity')
        ->get();

    // Retailer car stock
    $retailerStocks = RetailerStock::select('car_model', DB::raw('SUM(quantity_received) as total_received'))
        ->where('status', 'accepted')
        ->groupBy('car_model')
        ->orderByDesc('total_received')
        ->get();

    return view('dashboards.analyst.inventory-analysis', compact('supplierStocks', 'retailerStocks'));
}

public function trends()
{
    // Monthly sales totals
    $monthlySales = RetailerSale::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(quantity_sold) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

    // Calculate Simple Moving Average (SMA) with window of 3
    $sma = [];
    $values = array_values($monthlySales);
    $labels = array_keys($monthlySales);
    $window = 3;

    for ($i = 0; $i < count($values); $i++) {
        if ($i >= $window - 1) {
            $avg = array_sum(array_slice($values, $i - $window + 1, $window)) / $window;
            $sma[] = round($avg, 2);
        } else {
            $sma[] = null; // not enough data to average
        }
    }

    return view('dashboards.analyst.trends', compact('monthlySales', 'sma', 'labels'));
}

}
