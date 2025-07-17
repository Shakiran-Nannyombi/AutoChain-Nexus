<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\VendorNotification;

class ManufacturerVendorOrderController extends Controller
{
    // List all vendor orders for the current manufacturer
    public function index()
    {
        $manufacturerId = Auth::id();
        $vendorOrders = VendorOrder::where('manufacturer_id', $manufacturerId)
            ->with(['vendor', 'manufacturer'])
            ->orderByDesc('created_at')
            ->get();
        
        return view('dashboards.manufacturer.vendor-orders', compact('vendorOrders'));
    }

    // Show a specific vendor order
    public function show($id)
    {
        $manufacturerId = Auth::id();
        $order = VendorOrder::where('manufacturer_id', $manufacturerId)
            ->with(['vendor', 'manufacturer'])
            ->findOrFail($id);
        
        return view('dashboards.manufacturer.vendor-order-detail', compact('order'));
    }

    // Accept a vendor order
    public function accept(Request $request, $id)
    {
        $manufacturerId = Auth::id();
        $order = VendorOrder::where('manufacturer_id', $manufacturerId)->findOrFail($id);
        
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'expected_delivery_date' => 'nullable|date|after:today',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $order->update([
            'status' => 'accepted',
            'accepted_at' => Carbon::now(),
            'notes' => $validated['notes'] ?? $order->notes,
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? $order->expected_delivery_date,
            'unit_price' => $validated['unit_price'] ?? $order->unit_price,
            'total_amount' => ($validated['unit_price'] ?? $order->unit_price) * $order->quantity,
        ]);

        // Notify vendor
        $vendor = User::find($order->vendor_id);
        if ($vendor) {
            $vendor->notify(new VendorNotification(
                'Order Accepted', 
                'Your order #' . $order->id . ' has been accepted by the manufacturer.'
            ));
        }

        return response()->json([
            'success' => true, 
            'message' => 'Order accepted successfully!', 
            'order' => $order
        ]);
    }

    // Reject a vendor order
    public function reject(Request $request, $id)
    {
        $manufacturerId = Auth::id();
        $order = VendorOrder::where('manufacturer_id', $manufacturerId)->findOrFail($id);
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'status' => 'rejected',
            'rejected_at' => Carbon::now(),
            'rejection_reason' => $validated['rejection_reason'],
            'notes' => $validated['notes'] ?? $order->notes,
        ]);

        // Notify vendor
        $vendor = User::find($order->vendor_id);
        if ($vendor) {
            $vendor->notify(new VendorNotification(
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
        $manufacturerId = Auth::id();
        $order = VendorOrder::where('manufacturer_id', $manufacturerId)->findOrFail($id);
        
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

    // Get available products for vendors to order
    public function getProducts()
    {
        // This would typically come from a products table
        // For now, returning sample products
        $products = [
            [
                'id' => 1,
                'name' => 'Toyota Corolla 2024',
                'category' => 'Sedan',
                'base_price' => 25000,
                'available' => true
            ],
            [
                'id' => 2,
                'name' => 'Honda Civic 2024',
                'category' => 'Sedan',
                'base_price' => 24000,
                'available' => true
            ],
            [
                'id' => 3,
                'name' => 'Ford F-150 2024',
                'category' => 'Truck',
                'base_price' => 35000,
                'available' => true
            ],
            [
                'id' => 4,
                'name' => 'BMW 3 Series 2024',
                'category' => 'Luxury',
                'base_price' => 45000,
                'available' => true
            ],
            [
                'id' => 5,
                'name' => 'Mercedes-Benz C-Class 2024',
                'category' => 'Luxury',
                'base_price' => 48000,
                'available' => true
            ]
        ];

        return response()->json($products);
    }
}
