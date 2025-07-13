<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retailer;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;


class CustomerController extends Controller
{
    public function dashboard()
    {
        $retailers = Retailer::all();
        $customer = auth()->user()->customer ?? null;
        $recommendations = $customer ? $customer->recommendProducts() : collect();
        return view('dashboards.customer.index', compact('retailers', 'recommendations'));
    }

    public function getRecommendations(Customer $customer)
    {
        $recommendations = $customer->recommendProducts();

        return response()->json([
            'customer_id' => $customer->id,
            'segment' => $customer->segment,
            'recommendations' => $recommendations
        ]);
    }

    public function list()
    {
        $customers = \App\Models\Customer::withCount('purchases')
            ->withSum('purchases as total_spent', 'amount')
            ->get();

        // Calculate recency (days since last purchase)
        foreach ($customers as $customer) {
            $lastPurchase = $customer->purchases()->orderByDesc('purchase_date')->first();
            $customer->recency = $lastPurchase
                ? now()->diffInDays($lastPurchase->purchase_date)
                : null;
        }

        return view('dashboards.customer.list', compact('customers'));
    }

    public function show(Customer $customer)
    {
        return view('dashboards.customer.show', compact('customer'));
    }
}