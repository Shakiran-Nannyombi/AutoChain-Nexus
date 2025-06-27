<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerStock;
use App\Models\RetailerSale;
use App\Models\RetailerOrder;
use Illuminate\Support\Facades\Auth;

class RetailerController extends Controller
{
    public function dashboard() {
        $retailerId = Auth::id();
        $acceptedStock = RetailerStock::where('retailer_id', $retailerId)->where('status', 'accepted')->get();
        $sales = RetailerSale::where('retailer_id', $retailerId)->latest()->get();
        $orders = RetailerOrder::where('retailer_id', $retailerId)->latest()->get();

        return view('dashboards.retailer.index', compact('acceptedStock', 'sales', 'orders'));
    }

    public function stockOverview() {
        $retailerId = Auth::id();
        $stocks = RetailerStock::with('vendor')->where('retailer_id', $retailerId)->get();
        return view('dashboards.retailer.stock-overview', compact('stocks'));
    }

    public function acceptStock($id) {
        RetailerStock::where('id', $id)->update(['status' => 'accepted']);
        return back()->with('success', 'Stock accepted.');
    }

    public function rejectStock($id) {
        RetailerStock::where('id', $id)->update(['status' => 'rejected']);
        return back()->with('success', 'Stock rejected.');
    }

    public function salesForm() {
        $stock = RetailerStock::where('retailer_id', Auth::id())
                    ->where('status', 'accepted')
                    ->get()
                    ->groupBy('car_model');
        return view('dashboards.retailer.sales-update', compact('stock'));
    }

    public function submitSale(Request $request) {
        $request->validate([
            'car_model' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $totalStock = RetailerStock::where('retailer_id', Auth::id())
                        ->where('car_model', $request->car_model)
                        ->where('status', 'accepted')
                        ->sum('quantity_received');

        $sold = RetailerSale::where('retailer_id', Auth::id())
                    ->where('car_model', $request->car_model)
                    ->sum('quantity_sold');

        if ($request->quantity > ($totalStock - $sold)) {
            return back()->with('error', 'Not enough stock.');
        }

        RetailerSale::create([
            'retailer_id' => Auth::id(),
            'car_model' => $request->car_model,
            'quantity_sold' => $request->quantity
        ]);

        return back()->with('success', 'Sale recorded.');
    }

    public function orderForm() {
        return view('dashboards.retailer.order-placement');
    }

    public function submitOrder(Request $request) {
        $request->validate([
            'customer_name' => 'required',
            'car_model' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        RetailerOrder::create([
            'retailer_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'car_model' => $request->car_model,
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Order placed.');
    }
}
