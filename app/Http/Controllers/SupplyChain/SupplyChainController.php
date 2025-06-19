<?php

namespace App\Http\Controllers\SupplyChain;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Shipment;
use App\Models\Suppliers;
use App\Http\Controllers\Controller;

class SupplyChainController extends Controller
{
    public function index()
    {
        $activeShipmentsData = Shipment::all();
        $activeShipments = Shipment::where('status', 'in-transit')->count();
        $completedToday = Shipment::where('status', 'delivered')->whereDate('updated_at', today())->count();

        $onTimeDeliveriesCount = Shipment::where('status', 'delivered')
                                        ->whereRaw('updated_at <= expected_delivery_date') // Assuming you add expected_delivery_date to shipments table
                                        ->count();
        $totalDeliveriesCount = Shipment::where('status', 'delivered')->count();
        $onTimeDelivery = $totalDeliveriesCount > 0 ? ($onTimeDeliveriesCount / $totalDeliveriesCount) * 100 : 0;

        $activeSuppliers = Suppliers::count();

        $supplierPerformanceData = Suppliers::all();

        $headerTitle = 'Supply Chain Management';

        return view('pages.supply-chain', compact('activeShipmentsData', 'activeShipments', 'completedToday', 'onTimeDelivery', 'activeSuppliers', 'supplierPerformanceData', 'headerTitle'));
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