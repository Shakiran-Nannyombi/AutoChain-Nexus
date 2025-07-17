<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorOrder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\VendorActivity;
use App\Notifications\VendorNotification;

class VendorOrderController extends Controller
{
    // List all orders for the current vendor (orders TO manufacturers)
    public function index()
    {
        $vendorId = Auth::id();
        $orders = VendorOrder::where('vendor_id', $vendorId)
            ->with('manufacturer')
            ->orderByDesc('created_at')
            ->get();
            
        // Get all manufacturers for the dropdown
        $manufacturers = User::where('role', 'manufacturer')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get(['id', 'name', 'company']);          
            
        // Get all products for the dropdown
        $products = Product::orderBy('name')->get(['id', 'name', 'category', 'price']);
        
        return view('dashboards.vendor.orders', compact('orders', 'manufacturers', 'products'));
    }

    // Get products for a specific manufacturer (AJAX endpoint)
    public function getManufacturerProducts($manufacturerId)
    {
        $products = Product::where('manufacturer_id', $manufacturerId)
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'price']);
        return response()->json($products);
    }

    // Store a new order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'required|in:manufacturer,vendor',
            'partner_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
            'special_instructions' => 'nullable|string',
        ]);

        // Get product information from database
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        // Verify manufacturer exists
        $manufacturer = User::where('id', $validated['partner_id'])           ->where('role', 'manufacturer')
            ->where('status', 'active')
            ->first();
            
        if (!$manufacturer) {
            return response()->json(['success' => false, 'message' => 'Manufacturer not found.'], 404);
        }

        $order = VendorOrder::create([
            'manufacturer_id' => $request->order_type === 'manufacturer' ? $validated['partner_id'] : null,
            'vendor_id' => Auth::id(),
            'product' => $validated['product_id'],
            'product_name' => $product->name,
            'product_category' => $product->category,
            'quantity' => $validated['quantity'],
            'unit_price' => $product->price,
            'total_amount' => $product->price * $validated['quantity'],
            'status' => 'pending',
            'ordered_at' => Carbon::now(),
            'expected_delivery_date' => $validated['delivery_date'],
            'notes' => $validated['special_instructions'],
        ]);

        // Log activity
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Created new order',
            'details' => 'Order ID: ' . $order->id . ', Product: ' . $product->name . ', Quantity: ' . $order->quantity,
        ]);
        
        // Notify vendor
        Auth::user()->notify(new VendorNotification('Order Created', 'Your order #' . $order->id . ' has been created.'));

        return response()->json(['success' => true, 'message' => 'Order created successfully!', 'order' => $order]);
    }

    // Update order status or details
    public function update(Request $request, $id)
    {
        $order = VendorOrder::where('vendor_id', Auth::id())->findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|string',
        ]);
        $order->status = $validated['status'];
        $order->save();
        // Log activity
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Updated order status',
            'details' => 'Order ID: ' . $order->id . ', New Status: ' . $order->status,
        ]);
        // Notify vendor
        Auth::user()->notify(new VendorNotification('Order Updated', 'Order #' . $order->id . ' status updated to ' . $order->status . '.'));
        return response()->json(['success' => true, 'message' => 'Order updated.', 'order' => $order]);
    }

    // Delete an order
    public function destroy($id)
    {
        $order = VendorOrder::where('vendor_id', Auth::id())->findOrFail($id);
        $order->delete();
        // Log activity
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Deleted order',
            'details' => 'Order ID: ' . $id,
        ]);
        // Notify vendor
        Auth::user()->notify(new VendorNotification('Order Deleted', 'Order #' . $id . ' has been deleted.'));
        return response()->json(['success' => true, 'message' => 'Order deleted.']);
    }
}
