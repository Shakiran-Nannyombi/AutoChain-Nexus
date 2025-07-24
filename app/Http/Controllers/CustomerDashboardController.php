<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Product;
use App\Models\Retailer;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::user();
        $segment = $customer->segment ?? null;

        // Get available products from retailer stocks
        $availableProducts = Product::join('retailer_stocks', 'products.id', '=', 'retailer_stocks.product_id')
            ->where('retailer_stocks.stock', '>', 0)
            ->select('products.*', 'retailer_stocks.stock as retailer_stock')
            ->groupBy('products.id', 'retailer_stocks.stock')
            ->get();

        // Get recommendations: top 3 most bought products in customer orders
        $recommendations = Product::select('products.*')
            ->join('customer_orders', 'products.id', '=', 'customer_orders.product_id')
            ->selectRaw('COUNT(customer_orders.id) as orders_count')
            ->groupBy('products.id')
            ->orderByDesc('orders_count')
            ->take(3)
            ->get();

        // Get top selling products in customer's segment (customize logic as needed)
        $topSegmentProducts = collect();
        if ($segment) {
            $topSegmentProducts = Product::where('category', $segment)
                ->orderByDesc('stock')
                ->take(3)
                ->get();
        }

        // Get retailers and their inventories
        $retailers = Retailer::all();

        return view('dashboards.customer.index', compact(
            'availableProducts',
            'recommendations',
            'topSegmentProducts',
            'retailers'
        ));
    }
}