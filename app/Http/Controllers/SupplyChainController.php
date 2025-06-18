<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class SupplyChainController extends Controller
{
    public function index()
    {
        // Assuming you want to display existing purchase orders on the page
        $purchaseOrders = PurchaseOrder::all(); // Fetch all purchase orders
        $activeOrders = PurchaseOrder::where('status', 'processing')->count();
        $pendingDeliveries = PurchaseOrder::where('status', 'in_transit')->count();
        // Dummy data for now, you'd calculate these based on your logic
        $onTimeDelivery = 95.5;
        $supplierPerformance = 90.0;
        $topSuppliers = [
            (object)['name' => 'Supplier A', 'performance' => 98],
            (object)['name' => 'Supplier B', 'performance' => 92],
        ];
        $qualityAcceptance = 97.0;
        $logisticsUpdates = [
            (object)['title' => 'Order #1234 in transit', 'description' => 'Shipped from warehouse, expected delivery tomorrow', 'timestamp' => now()->subHours(5)],
            (object)['title' => 'Order #5678 delivered', 'description' => 'Received by customer', 'timestamp' => now()->subDays(1)],
        ];


        return view('pages.supply-chain', compact('purchaseOrders', 'activeOrders', 'pendingDeliveries', 'onTimeDelivery', 'supplierPerformance', 'topSuppliers', 'qualityAcceptance', 'logisticsUpdates'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'po_number' => 'required|string|max:255|unique:purchase_orders',
            'supplier_name' => 'required|string|max:255',
            'item_count' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:processing,in_transit,delivered',
            'expected_delivery' => 'required|date',
        ]);

        PurchaseOrder::create($validatedData);

        return redirect()->route('supply-chain')->with('success', 'Purchase Order added successfully!');
    }
} 