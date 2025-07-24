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
    public function index(Request $request)
    {
        $vendorId = Auth::id();
        
        // Get all retailer orders for this vendor
        $retailerOrders = RetailerOrder::where('vendor_id', $vendorId)
            ->with('retailer')
            ->orderByDesc('created_at')
            ->get();
        
        $selectedOrder = null;
        
        // Get the selected order if specified, otherwise get the first order
        if ($request->has('selected')) {
            $selectedOrder = $retailerOrders->firstWhere('id', $request->selected);
        } elseif ($retailerOrders->isNotEmpty()) {
            $selectedOrder = $retailerOrders->first();
        }
        
        return view('dashboards.vendor.retailer-order-details', compact('retailerOrders', 'selectedOrder'));
    }

    // Show a specific retailer order
    public function show($id)
    {
        $vendorId = Auth::id();
        
        // Get the selected order
        $selectedOrder = RetailerOrder::where('vendor_id', $vendorId)
            ->with('retailer')
            ->findOrFail($id);
        
        return view('dashboards.vendor.retailer-order-show', compact('selectedOrder'));
    }

    // Confirm a retailer order
    public function confirm(Request $request, $id)
    {
        try {
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

            // Refresh the order to get the updated data with timestamps
            $order->refresh();

            // Log activity
            VendorActivity::create([
                'vendor_id' => $vendorId,
                'activity' => 'Confirmed retailer order',
                'details' => 'Order ID: ' . $order->id . ', Customer: ' . $order->customer_name . ', Product: ' . $order->car_model,
            ]);

            // Always return JSON for AJAX requests
            return response()->json([
                'success' => true, 
                'message' => 'Order confirmed successfully!', 
                'order' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Error confirming order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm order: ' . $e->getMessage()
            ], 500);
        }
    }

    // Ship a retailer order
    public function ship(Request $request, $id)
    {
        try {
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
            
            // Refresh the order to get the updated data with timestamps
            $order->refresh();

            // Log activity
            VendorActivity::create([
                'vendor_id' => $vendorId,
                'activity' => 'Shipped retailer order',
                'details' => 'Order ID: ' . $order->id . ', Tracking: ' . ($validated['tracking_number'] ?? 'N/A'),
            ]);

            // Always return JSON for AJAX requests
            return response()->json([
                'success' => true, 
                'message' => 'Order shipped successfully!', 
                'order' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Error shipping order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to ship order: ' . $e->getMessage()
            ], 500);
        }
    }

    // Mark order as delivered
    public function deliver(Request $request, $id)
    {
        try {
            $vendorId = Auth::id();
            $order = RetailerOrder::where('vendor_id', $vendorId)->findOrFail($id);

            // Update order status and delivery time
            $order->update([
                'status' => 'delivered',
                'delivered_at' => now(),
                'notes' => $this->appendNote($order->notes, 'Delivered: ' . ($request->input('delivery_notes') ?? 'Order delivered')),
            ]);
            
            // Refresh the order to get the updated data with timestamps
            $order->refresh();

            // Log vendor activity
            VendorActivity::create([
                'vendor_id' => $vendorId,
                'activity' => 'Delivered retailer order',
                'details' => 'Order ID: ' . $order->id . ' has been delivered.',
            ]);

            // Always return JSON for AJAX requests
            return response()->json([
                'success' => true,
                'message' => 'Order marked as delivered successfully!',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Error delivering order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to deliver order: ' . $e->getMessage()
            ], 500);
        }
    }

private function appendNote($existing, $new)
{
    return ($existing ? $existing . "\n" : '') . $new;
}

    // Reject a retailer order
    public function reject(Request $request, $id)
    {
        try {
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
            
            // Refresh the order to get the updated data with timestamps
            $order->refresh();

            // Log activity
            VendorActivity::create([
                'vendor_id' => $vendorId,
                'activity' => 'Rejected retailer order',
                'details' => 'Order ID: ' . $order->id . ', Reason: ' . $validated['rejection_reason'],
            ]);

            // Always return JSON for AJAX requests
            return response()->json([
                'success' => true, 
                'message' => 'Order rejected successfully!', 
                'order' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Error rejecting order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject order: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get order notes
    public function getNotes($id)
    {
        $vendorId = Auth::id();
        $order = RetailerOrder::where('vendor_id', $vendorId)->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'notes' => $order->notes
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
        
        // Refresh the order to get the updated data
        $order->refresh();

        // For AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Notes updated successfully!', 
                'order' => $order
            ]);
        }
        
        // For regular form submissions
        return back()->with('success', 'Notes updated successfully!');
    }
}
