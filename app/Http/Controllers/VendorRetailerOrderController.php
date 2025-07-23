<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\VendorActivity;
use App\Notifications\VendorNotification;
use App\Notifications\RetailerNotification;
use Illuminate\Support\Facades\Log;


class VendorRetailerOrderController extends Controller
{
    // List all retailer orders for the current vendor
    public function index()
    {
        $retailerOrders = \App\Models\RetailerOrder::with(['retailer', 'vendor'])
            ->orderByDesc('created_at')
            ->get();
        return view('dashboards.vendor.retailer-orders', compact('retailerOrders'));
    }

    // Show a specific retailer order
    public function show($id)
    {
        $vendorId = Auth::id();
        $order = RetailerOrder::where('vendor_id', $vendorId)
            ->with('retailer')
            ->findOrFail($id);
        
        return view('dashboards.vendor.retailer-order-show', compact('order'));
    }

    // Confirm a retailer order
    public function confirm(Request $request, $id)
    {
        $vendorId = Auth::id();
        $order = RetailerOrder::where('vendor_id', $vendorId)->findOrFail($id);
        
        $validated = $request->validate([
            'notes' => 'nullable|string',
            'estimated_delivery' => 'nullable|date|after:today',
        ]);

        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => Carbon::now(),
            'notes' => $validated['notes'] ?? $order->notes,
        ]);

        // Log activity
        VendorActivity::create([
            'vendor_id' => $vendorId,
            'activity' => 'Confirmed retailer order',
            'details' => 'Order ID: ' . $order->id . ', Customer: ' . $order->customer_name . ', Product: ' . $order->car_model,
        ]);

        // Notify vendor
        $vendor = Auth::user();
        if (method_exists($vendor, 'notify')) {
            $vendor->notify(new VendorNotification(
                'Order Confirmed', 
                'Retailer order #' . $order->id . ' has been confirmed.'
            ));
        }

        // Notify retailer
        $retailer = User::find($order->retailer_id);
        if ($retailer) {
            $retailer->notify(new RetailerNotification(
                'Order Confirmed', 
                'Your order #' . $order->id . ' has been confirmed by the vendor.'
            ));
        }

        return response()->json([
            'success' => true, 
            'message' => 'Order confirmed successfully!', 
            'order' => $order
        ]);
    }

    // Ship a retailer order
    public function ship(Request $request, $id)
    {
        $vendorId = Auth::id();
        $order = RetailerOrder::where('vendor_id', $vendorId)->findOrFail($id);
        
        $validated = $request->validate([
            'tracking_number' => 'nullable|string',
            'shipping_notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => 'shipped',
            'shipped_at' => Carbon::now(),
            'notes' => ($order->notes ? $order->notes . "\n" : '') . 
                      'Shipped: ' . ($validated['shipping_notes'] ?? 'Order shipped'),
        ]);

        // Create or update Delivery record
        $driver = 'Demo Driver';
        $destination = 'Demo Destination';
        $progress = 75;
        $eta = Carbon::now()->addDays(2)->format('Y-m-d H:i');
        $tracking = [
            ['status' => 'Package loaded', 'location' => 'Warehouse', 'timestamp' => Carbon::now()->subDays(2)->format('Y-m-d H:i')],
            ['status' => 'Departed', 'location' => 'Warehouse', 'timestamp' => Carbon::now()->subDay()->format('Y-m-d H:i')],
            ['status' => 'In transit', 'location' => $destination, 'timestamp' => Carbon::now()->format('Y-m-d H:i')],
        ];
        \App\Models\Delivery::updateOrCreate(
            ['retailer_order_id' => $order->id],
            [
                'status' => 'shipped',
                'driver' => $driver,
                'destination' => $destination,
                'progress' => $progress,
                'eta' => $eta,
                'tracking_history' => $tracking,
            ]
        );

        // Log activity
        VendorActivity::create([
            'vendor_id' => $vendorId,
            'activity' => 'Shipped retailer order',
            'details' => 'Order ID: ' . $order->id . ', Tracking: ' . ($validated['tracking_number'] ?? 'N/A'),
        ]);

        // Notify retailer
        $retailer = User::find($order->user_id);
        if ($retailer) {
            $retailer->notify(new RetailerNotification(
                'Order Shipped', 
                'Your order #' . $order->id . ' has been shipped by the vendor.'
            ));
        }

        return response()->json([
            'success' => true, 
            'message' => 'Order shipped successfully!', 
            'order' => $order
        ]);
    }

    // Mark order as delivered
    public function deliver(Request $request, $id)
{
    $vendorId = Auth::id();
    $order = RetailerOrder::where('vendor_id', $vendorId)->findOrFail($id);

    // Update order status and delivery time
    $order->update([
        'status' => 'delivered',
        'delivered_at' => now(),
        'notes' => $this->appendNote($order->notes, 'Delivered: ' . ($request->input('delivery_notes') ?? 'Order delivered')),
    ]);

      Log::info("Creating RetailerStock for order ID: {$order->id}");

      $retailer = \App\Models\Retailer::where('user_id', $order->user_id)->first();

    // Create retailer stock record
    $stock = \App\Models\RetailerStock::create([
        'retailer_id' => $retailer->id,
        'vendor_id' => $vendorId,
        'vendor_name' => Auth::user()->name,
        'car_model' => $order->car_model,
        'quantity_received' => $order->quantity,
        'status' => 'pending', // So the retailer can accept/reject
    ]);

     Log::info("RetailerStock created with ID: {$stock->id}");

    // Log vendor activity
    VendorActivity::create([
        'vendor_id' => $vendorId,
        'activity' => 'Delivered retailer order',
        'details' => 'Order ID: ' . $order->id . ' has been delivered and added to stock.',
    ]);

    // Notify retailer
    $retailer = User::find($order->user_id);
    if ($retailer) {
        $retailer->notify(new RetailerNotification(
            'Stock Pending Confirmation',
            'Your order #' . $order->id . ' has been delivered and is pending your confirmation in stock.'
        ));
    }

    return response()->json([
        'success' => true,
        'message' => 'Order marked as delivered and stock record created!',
        'order' => $order
    ]);
}

private function appendNote($existing, $new)
{
    return ($existing ? $existing . "\n" : '') . $new;
}

    // Reject a retailer order
    public function reject(Request $request, $id)
    {
        $vendorId = Auth::id();
        $order = RetailerOrder::where('vendor_id', $vendorId)->findOrFail($id);
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $order->update([
            'status' => 'rejected',
            'notes' => ($order->notes ? $order->notes . "\n" : '') . 
                      'Rejected: ' . $validated['rejection_reason'],
        ]);

        // Log activity
        VendorActivity::create([
            'vendor_id' => $vendorId,
            'activity' => 'Rejected retailer order',
            'details' => 'Order ID: ' . $order->id . ', Reason: ' . $validated['rejection_reason'],
        ]);

       

        // Notify retailer
        $retailer = User::find($order->retailer_id);
        if ($retailer) {
            $retailer->notify(new RetailerNotification(
                'Order Rejected',
                'Your order #' . $order->id . ' has been rejected. Reason: ' . $validated['rejection_reason']
            ));
        }

        return response()->json([
            'success' => true, 
            'message' => 'Order rejected successfully!', 
            'order' => $order
        ]);
    }

    // Update order notes
    public function updateNotes(Request $request, $id)
    {
        $vendorId = Auth::id();
        $order = RetailerOrder::where('vendor_id', $vendorId)->findOrFail($id);
        
        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $order->update([
            'notes' => $validated['notes'],
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Notes updated successfully!', 
            'order' => $order
        ]);
    }
}
