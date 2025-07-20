<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerStock;
use App\Models\RetailerSale;
use App\Models\RetailerOrder;
use App\Models\CustomerOrder;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\RetailerNotification;

class RetailerController extends Controller
{
    public function dashboard() {
    $retailer = Auth::user()->retailer;
    if (!$retailer) {
        // handle error or redirect
    }
    $retailerId = $retailer->id;
        $acceptedStock = RetailerStock::where('retailer_id', $retailerId)->where('status', 'accepted')->get();
        $sales = RetailerSale::where('retailer_id', $retailerId)->latest()->get();
        $orders = RetailerOrder::where('user_id', $retailerId)->latest()->get();

        // Fetch notifications for the current user
        $user = Auth::user();
        $unreadNotifications = ($user && is_object($user) && method_exists($user, 'unreadNotifications')) ? $user->unreadNotifications()->take(5)->get() : collect();
        $allNotifications = ($user && is_object($user) && method_exists($user, 'notifications')) ? $user->notifications()->take(10)->get() : collect();

        return view('dashboards.retailer.index', compact('acceptedStock', 'sales', 'orders', 'unreadNotifications', 'allNotifications'));
    }

    public function stockOverview()
{
    $user = Auth::user();
    $retailer = $user->retailer;

    if (!$retailer) {
        // No retailer linked, return empty collection or handle gracefully
        return view('dashboards.retailer.stock-overview', ['stocks' => collect()]);
    }

    $stocks = RetailerStock::with('vendor')
        ->where('retailer_id', $retailer->id)
        ->get();

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

    public function submitSale(Request $request) {
    $request->validate([
        'car_model' => 'required',
        'quantity' => 'required|integer|min:1'
    ]);

    $retailer = Auth::user()->retailer;
    if (!$retailer) {
        return back()->with('error', 'Retailer not found.');
    }

    $retailerId = $retailer->id;
    $carModel = $request->car_model;
    $quantity = $request->quantity;

    // Get all accepted stock items for this model, ordered by oldest first (FIFO)
    $stockItems = RetailerStock::where('retailer_id', $retailerId)
                    ->where('car_model', $carModel)
                    ->where('status', 'accepted')
                    ->where('quantity_received', '>', 0)
                    ->orderBy('created_at')
                    ->get();

    $remainingQuantity = $quantity;
    $stockItemsToUpdate = [];

    foreach ($stockItems as $item) {
        if ($remainingQuantity <= 0) break;

        $deduct = min($item->quantity_received, $remainingQuantity);
        $item->quantity_received -= $deduct;
        $remainingQuantity -= $deduct;
        $stockItemsToUpdate[] = $item;
    }

    if ($remainingQuantity > 0) {
        return back()->with('error', 'Not enough stock. Only '.($quantity - $remainingQuantity).' available.');
    }

    DB::transaction(function () use ($retailerId, $carModel, $quantity, $stockItemsToUpdate) {
        // Update stock records
        foreach ($stockItemsToUpdate as $item) {
            $item->save();
        }

        // Record the sale
        RetailerSale::create([
            'retailer_id' => $retailerId,
            'car_model' => $carModel,
            'quantity_sold' => $quantity
        ]);
    });

    return back()->with('success', 'Sale recorded successfully.');
}

public function salesForm()
{
    $user = Auth::user();
    $retailer = $user->retailer;

    if (!$retailer) {
        return view('dashboards.retailer.sales-update', ['stock' => collect()]);
    }

    $stock = RetailerStock::where('retailer_id', $retailer->id)
                ->where('status', 'accepted')
                ->get()
                ->groupBy('car_model');

    return view('dashboards.retailer.sales-update', compact('stock'));
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
        'user_id' => Auth::id(),
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
        $retailerOrders = RetailerOrder::where('user_id', $retailerId)
            ->with('vendor')
            ->orderByDesc('created_at')
            ->get();
        
        return view('dashboards.retailer.orders', compact('retailerOrders'));
    }

    public function orderDetail($id) {
        $retailerId = Auth::id();
        $order = RetailerOrder::where('user_id', $retailerId)
            ->with('vendor')
            ->findOrFail($id);
        
        return view('dashboards.retailer.order-detail', compact('order'));
    }

    
public function viewCustomerOrders()
{
    $retailerId = Auth::id();

    // eager‑load product & customer, paginate if you like
    $orders = CustomerOrder::with(['product', 'customer'])
                ->where('retailer_id', $retailerId)
                ->orderByDesc('order_date')
                ->paginate(15);

    return view('dashboards.retailer.customer-orders', compact('orders'));
}


public function receiveCustomerOrder(CustomerOrder $order)
{
    $retailerId = Auth::id();

    abort_if($order->retailer_id !== $retailerId, 403);

    if ($order->status === 'pending') {
        $order->status = 'received';
        $order->save();
        return back()->with('success', "Order #{$order->id} marked received.");
    }

    return back()->with('info', 'Only pending orders can be received.');
}
}
