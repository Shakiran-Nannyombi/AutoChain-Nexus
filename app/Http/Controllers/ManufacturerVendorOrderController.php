<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\VendorNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

        // --- Real-time stock update logic ---
        $product = \App\Models\Product::find($order->product);
        if ($product) {
            if ($product->stock < $order->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock to confirm this order.',
                    'order' => $order
                ], 400);
            }
            $product->decrement('stock', $order->quantity);
            // Optional: Notify manufacturer if stock is low
            if ($product->stock < 5) { // threshold can be adjusted
                // You can use a notification system here
                // For example, log or send an email/notification
                Log::warning('Stock for product ' . $product->name . ' is low: ' . $product->stock);
                // Optionally, notify manufacturer via email/notification
            }
        }
        // --- End stock update logic ---

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

    public function confirm(Request $request, $id)
    {
        $manufacturerId = Auth::id();
        $order = VendorOrder::where('manufacturer_id', $manufacturerId)->findOrFail($id);
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order is not pending.');
        }
        $request->validate([
            'delivery_date' => 'required|date|after:today',
            'delivery_address' => 'required|string|max:255',
            'driver_name' => 'required|string|max:255',
        ]);
        $product = \App\Models\Product::find($order->product);
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }
        if ($product->stock < $order->quantity) {
            return back()->with('error', 'Insufficient stock to confirm this order.');
        }
        // Update order status
        $order->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'notes' => ($order->notes ? $order->notes . "\n" : '') .
                'Delivery Date: ' . $request->delivery_date . ', Address: ' . $request->delivery_address . ', Driver: ' . $request->driver_name,
        ]);
        $product->decrement('stock', $order->quantity);
        if ($product->stock < 5) {
            Log::warning('Stock for product ' . $product->name . ' is low: ' . $product->stock);
        }
        // Generate invoice (simple HTML for now)
        $invoiceHtml = view('emails.reports.invoice', [
            'order' => $order,
            'product' => $product,
            'manufacturer' => Auth::user(),
            'vendor' => $order->vendor,
            'delivery_date' => $request->delivery_date,
            'delivery_address' => $request->delivery_address,
            'driver_name' => $request->driver_name,
        ])->render();
        // Send invoice via Java email API
        try {
            $response = Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $order->vendor->email,
                'subject' => 'Order Invoice #' . $order->id,
                'body' => $invoiceHtml,
            ]);
            if ($response->failed()) {
                Log::error('Failed to send invoice email for order #' . $order->id);
            }
        } catch (\Exception $e) {
            Log::error('Error sending invoice email: ' . $e->getMessage());
        }
        $vendor = User::find($order->vendor_id);
        if ($vendor) {
            $vendor->notify(new VendorNotification(
                'Order Accepted',
                'Your order #' . $order->id . ' has been accepted by the manufacturer. An invoice has been sent to your email.'
            ));
        }
        return redirect()->route('manufacturer.vendor-orders.invoice-preview', $order->id)->with('success', 'Order confirmed, stock updated, and invoice sent to vendor.');
    }

    public function invoicePreview($id)
    {
        $manufacturerId = Auth::id();
        $order = VendorOrder::where('manufacturer_id', $manufacturerId)->with('vendor')->findOrFail($id);
        $product = \App\Models\Product::find($order->product);
        $manufacturer = Auth::user();
        $vendor = $order->vendor;
        // Optionally, parse delivery details from notes if needed
        return view('dashboards.manufacturer.invoice-preview', compact('order', 'product', 'manufacturer', 'vendor'));
    }
}
