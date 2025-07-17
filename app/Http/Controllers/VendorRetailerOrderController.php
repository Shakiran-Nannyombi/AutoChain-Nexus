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

class VendorRetailerOrderController extends Controller
{
    // List all retailer orders for the current vendor
    public function index()
    {
        $vendorId = Auth::id();
        $retailerOrders = RetailerOrder::where('vendor_id', $vendorId)
            ->with('retailer')
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
        
        return view('dashboards.vendor.retailer-order-detail', compact('order'));
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
        Auth::user()->notify(new VendorNotification(
            'Order Confirmed', 
            'Retailer order #' . $order->id . ' has been confirmed.'
        ));

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

        // Log activity
        VendorActivity::create([
            'vendor_id' => $vendorId,
            'activity' => 'Shipped retailer order',
            'details' => 'Order ID: ' . $order->id . ', Tracking: ' . ($validated['tracking_number'] ?? 'N/A'),
        ]);

        // Notify retailer
        $retailer = User::find($order->retailer_id);
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
        
        $order->update([
            'status' => 'delivered',
            'delivered_at' => Carbon::now(),
        ]);

        // Log activity
        VendorActivity::create([
            'vendor_id' => $vendorId,
            'activity' => 'Delivered retailer order',
            'details' => 'Order ID: ' . $order->id . ' has been delivered successfully.',
        ]);

        // Notify retailer
        $retailer = User::find($order->retailer_id);
        if ($retailer) {
            $retailer->notify(new RetailerNotification(
                'Order Delivered', 
                'Your order #' . $order->id . ' has been delivered successfully.'
            ));
        }

        return response()->json([
            'success' => true, 
            'message' => 'Order marked as delivered!', 
            'order' => $order
        ]);
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
