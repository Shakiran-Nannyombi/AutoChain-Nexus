<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\ManufacturerVendorOrderService;

class ManufacturerVendorOrderController extends Controller
{
    protected $orderService;

    public function __construct(ManufacturerVendorOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $vendorOrders = $this->orderService->getVendorOrders(Auth::id());
        return view('dashboards.manufacturer.vendor-orders', compact('vendorOrders'));
    }

    public function show($id)
    {
        $order = $this->orderService->getOrder($id, Auth::id());
        return view('dashboards.manufacturer.vendor-order-detail', compact('order'));
    }

    public function accept(Request $request, $id)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'expected_delivery_date' => 'nullable|date|after:today',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $result = $this->orderService->acceptOrder($id, Auth::id(), $validated);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $result = $this->orderService->rejectOrder($id, Auth::id(), $validated['rejection_reason']);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    public function updateNotes(Request $request, $id)
    {
        $validated = $request->validate(['notes' => 'required|string|max:1000']);
        
        $order = $this->orderService->getOrder($id, Auth::id());
        $order->update(['notes' => $validated['notes']]);

        return response()->json(['success' => true, 'message' => 'Notes updated successfully!', 'order' => $order]);
    }

    public function updateInvoice(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $result = $this->orderService->updateInvoice($id, Auth::id(), $validated);
        return response()->json($result);
    }

    public function resendInvoice(Request $request, $id)
    {
        $result = $this->orderService->resendInvoice($id, Auth::id());
        return response()->json($result);
    }

    public function getProducts()
    {
        // Keeping this sample data here for now as it's just a simple return
        $products = [
            ['id' => 1, 'name' => 'Toyota Corolla 2024', 'category' => 'Sedan', 'base_price' => 25000, 'available' => true],
            ['id' => 2, 'name' => 'Honda Civic 2024', 'category' => 'Sedan', 'base_price' => 24000, 'available' => true],
            ['id' => 3, 'name' => 'Ford F-150 2024', 'category' => 'Truck', 'base_price' => 35000, 'available' => true],
            ['id' => 4, 'name' => 'BMW 3 Series 2024', 'category' => 'Luxury', 'base_price' => 45000, 'available' => true],
            ['id' => 5, 'name' => 'Mercedes-Benz C-Class 2024', 'category' => 'Luxury', 'base_price' => 48000, 'available' => true]
        ];
        return response()->json($products);
    }

    public function confirm(Request $request, $id)
    {
        $result = $this->orderService->confirmOrder($id, Auth::id(), $request->all());

        if (!$result['success']) {
             return response()->json($result, isset($result['message']) && strpos($result['message'], 'Product not found') !== false ? 404 : 400);
        }

        return response()->json($result);
    }

    public function invoicePreview($id)
    {
        $order = $this->orderService->getOrder($id, Auth::id());
        $product = \App\Models\Product::find($order->product);
        $manufacturer = Auth::user();
        $vendor = $order->vendor;
        return view('dashboards.manufacturer.invoice-preview', compact('order', 'product', 'manufacturer', 'vendor'));
    }
}
