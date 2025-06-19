<?php

namespace App\Http\Controllers\Retailer;

use Illuminate\Http\Request;
use App\Models\SupplyChain\Retailer;
use App\Models\SupplyChain\Shipment;
use App\Models\Warehouse\Car;
use App\Models\Order\Purchase;
use App\Models\UserManagement\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RetailController extends Controller
{
    public function index()
    {
        $headerTitle = 'Retail Dashboard';

        // Monthly Sales & Total Revenue
        $currentMonthSales = Purchase::whereYear('purchase_date', now()->year)
                                     ->whereMonth('purchase_date', now()->month)
                                     ->sum('price');
        $lastMonthSales = Purchase::whereYear('purchase_date', now()->subMonth()->year)
                                  ->whereMonth('purchase_date', now()->subMonth()->month)
                                  ->sum('price');
        $monthlySalesCount = Purchase::whereYear('purchase_date', now()->year)
                                     ->whereMonth('purchase_date', now()->month)
                                     ->count();

        $monthlySalesGrowth = 0;
        if ($lastMonthSales > 0) {
            $monthlySalesGrowth = (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        }

        $totalRevenue = Purchase::sum('price');
        // Assuming an overall revenue growth metric would need more historical data/targets
        $totalRevenueGrowth = 18; // Placeholder

        // Active Dealers - assuming 'retailers' table has an 'is_active' or similar field, or just count all
        $activeDealers = Retailer::count(); // Or Retailer::where('is_active', true)->count(); if applicable

        // Customer Satisfaction - placeholder, needs a dedicated reviews/ratings system
        $customerSatisfaction = 4.7; // Placeholder
        $customerSatisfactionGrowth = 0.2; // Placeholder

        // Sales Performance by Model
        $salesByModel = Car::select('cars.model', DB::raw('COUNT(purchases.id) as units_sold'), DB::raw('SUM(purchases.price) as revenue'))
                           ->join('purchases', 'cars.id', '=', 'purchases.car_id')
                           ->groupBy('cars.model')
                           ->get();

        $salesPerformanceByModel = $salesByModel->map(function ($item) {
            $totalUnitsSold = Purchase::count(); // Total units sold across all models
            $percentageSold = ($totalUnitsSold > 0) ? ($item->units_sold / $totalUnitsSold) * 100 : 0;
            return [
                'model' => $item->model,
                'sold_units' => $item->units_sold,
                'revenue' => $item->revenue,
                'percentage_sold' => round($percentageSold, 1),
                'growth' => rand(5, 20), // Placeholder for growth
            ];
        });

        // Top Performing Dealers
        $topPerformingDealers = Retailer::select('retailers.name', 'retailers.location', DB::raw('COUNT(purchases.id) as units_sold'), DB::raw('SUM(purchases.price) as revenue'))
                                       ->join('purchases', 'retailers.id', '=', 'purchases.retailer_id')
                                       ->groupBy('retailers.id', 'retailers.name', 'retailers.location')
                                       ->orderByDesc('units_sold')
                                       ->limit(4) // Limiting to top 4 as per image
                                       ->get();

        $topDealersData = $topPerformingDealers->map(function ($dealer) {
            $status = 'good'; // Placeholder, would need logic for excellent/good/average/warning
            if ($dealer->units_sold > 100) $status = 'excellent';
            if ($dealer->units_sold < 50) $status = 'average';

            return [
                'name' => $dealer->name,
                'location' => $dealer->location,
                'sales_units' => $dealer->units_sold,
                'revenue' => $dealer->revenue,
                'status' => $status,
            ];
        });

        return view('pages.retail', compact(
            'headerTitle',
            'currentMonthSales',
            'monthlySalesGrowth',
            'totalRevenue',
            'totalRevenueGrowth',
            'activeDealers',
            'customerSatisfaction',
            'customerSatisfactionGrowth',
            'salesPerformanceByModel',
            'topDealersData',
            'monthlySalesCount'
        ));
    }

    public function receiveShipment(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'condition' => 'required|string',
            'received_date' => 'required|date',
        ]);

        $shipment = Shipment::findOrFail($validated['shipment_id']);

        if ($shipment->status !== 'Delivered') {
            return response()->json(['error' => 'Shipment not delivered yet. Cannot receive.'], 400);
        }

        // Record car condition and receipt at retailer
        $car = $shipment->car;
        $car->condition = $validated['condition'];
        $car->retailer_id = $shipment->retailer_id;
        $car->received_date = $validated['received_date'];
        $car->save();

        return response()->json(['message' => 'Shipment received and recorded at retailer.', 'car' => $car]);
    }

    /**
     * Record customer purchase
     */
    public function recordPurchase(Request $request)
    {
        $validated = $request->validate([
            'retailer_id' => 'required|exists:retailers,id',
            'car_id' => 'required|exists:cars,id',
            'customer_name' => 'required|string',
            'purchase_date' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        if ($car->sold) {
            return response()->json(['error' => 'Car already sold.'], 400);
        }

        // Mark car as sold
        $car->sold = true;
        $car->save();

        $purchase = Purchase::create([
            'retailer_id' => $validated['retailer_id'],
            'car_id' => $validated['car_id'],
            'customer_name' => $validated['customer_name'],
            'purchase_date' => $validated['purchase_date'],
            'price' => $validated['price'],
        ]);

        return response()->json(['message' => 'Purchase recorded successfully.', 'purchase' => $purchase]);
    }
}
