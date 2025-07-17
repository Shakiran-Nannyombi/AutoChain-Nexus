<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerStock;
use App\Models\RetailerSale;
use App\Models\RetailerOrder;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\RetailerNotification;

class RetailerController extends Controller
{
    public function dashboard() {
        $retailerId = Auth::id();
        $acceptedStock = RetailerStock::where('retailer_id', $retailerId)->where('status', 'accepted')->get();
        $sales = RetailerSale::where('retailer_id', $retailerId)->latest()->get();
        $orders = RetailerOrder::where('retailer_id', $retailerId)->latest()->get();

        // Fetch notifications for the current user
        $user = Auth::user();
        $unreadNotifications = ($user && is_object($user) && method_exists($user, 'unreadNotifications')) ? $user->unreadNotifications()->take(5)->get() : collect();
        $allNotifications = ($user && is_object($user) && method_exists($user, 'notifications')) ? $user->notifications()->take(10)->get() : collect();

        return view('dashboards.retailer.index', compact('acceptedStock', 'sales', 'orders', 'unreadNotifications', 'allNotifications'));
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
    $vendors = User::where('role', 'vendor')->where('status', 'approved')->get();
    return view('dashboards.retailer.order-placement', compact('vendors'));
}


    public function submitOrder(Request $request) {
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'car_model' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
        'vendor_id' => 'required|exists:users,id'
    ]);

    $vendor = User::where('id', $request->vendor_id)
                  ->where('role', 'vendor')
                  ->where('status', 'approved')
                  ->first();

    if (!$vendor) {
        return back()->with('error', 'Selected vendor is not available.');
    }

    $order = RetailerOrder::create([
        'retailer_id' => Auth::id(),
        'vendor_id' => $vendor->id,
        'customer_name' => $request->customer_name,
        'car_model' => $request->car_model,
        'quantity' => $request->quantity,
        'status' => 'pending',
        'ordered_at' => now(),
        'total_amount' => $request->quantity * rand(25000, 50000) // demo price logic
    ]);

    Auth::user()->notify(new \App\Notifications\RetailerNotification(
        'Order Placed', 
        'Your order #' . $order->id . ' to vendor ' . $vendor->name . ' has been placed and is pending confirmation.'
    ));

    return back()->with('success', 'Order placed successfully and sent to ' . $vendor->name . '.');
}


    public function viewOrders() {
        $retailerId = Auth::id();
        $retailerOrders = RetailerOrder::where('retailer_id', $retailerId)
            ->with('vendor')
            ->orderByDesc('created_at')
            ->get();
        
        return view('dashboards.retailer.orders', compact('retailerOrders'));
    }

    public function orderDetail($id) {
        $retailerId = Auth::id();
        $order = RetailerOrder::where('retailer_id', $retailerId)
            ->with('vendor')
            ->findOrFail($id);
        
        return view('dashboards.retailer.order-detail', compact('order'));
    }
}
