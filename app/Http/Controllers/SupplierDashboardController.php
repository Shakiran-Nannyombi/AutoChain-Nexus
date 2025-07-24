<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierStock;
use App\Models\SupplierOrder;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ChecklistRequest;
use App\Models\SupplierOrderItem;

class SupplierDashboardController extends Controller
{
    public function index()
    {
        $supplierId = session('user_id') ?? Auth::id();
        
        // For demo purposes, use first supplier if no session (remove this in production)
        if (!$supplierId) {
            $supplier = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->first();
            $supplierId = $supplier ? $supplier->id : null;
        }
        
        // Active Supplies: count of supplier's stocks with quantity > 0
        $activeSupplies = SupplierStock::where('supplier_id', $supplierId)
            ->where('quantity', '>', 0)
            ->count();
        
        // Pending Orders: count of checklist requests with status 'pending' for this supplier
        $pendingOrders = ChecklistRequest::where('supplier_id', $supplierId)
            ->where('status', 'pending')
            ->count();
        
        // Active Shipments: count of deliveries with status 'in_transit' or similar
        $activeShipments = Delivery::where('supplier_id', $supplierId)
            ->whereIn('status', ['in_transit', 'out_for_delivery'])
            ->count();
        
        // Monthly Revenue: calculate from supplier order items for fulfilled orders
        $monthlyRevenue = SupplierOrder::where('supplier_id', $supplierId)
            ->where('status', 'fulfilled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('items')
            ->get()
            ->sum(function($order) {
                return $order->items->sum(function($item) {
                    return $item->quantity * $item->unit_price;
                });
            });
        
        // Format monthly revenue
        $monthlyRevenue = 'shs ' . number_format($monthlyRevenue, 2);
        

        
        return view('dashboards.supplier.index', compact(
            'activeSupplies',
            'pendingOrders',
            'activeShipments',
            'monthlyRevenue'
        ));
    }
    
    public function shipments()
    {
        $supplierId = session('user_id') ?? Auth::id();
        
        // For demo purposes, use first supplier if no session
        if (!$supplierId) {
            $supplier = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->first();
            $supplierId = $supplier ? $supplier->id : null;
        }
        
        $shipments = Delivery::where('supplier_id', $supplierId)
            ->whereNotNull('destination')
            ->whereNotNull('driver')
            ->whereNotNull('manufacturer_id')
            ->where('destination', '!=', '')
            ->where('driver', '!=', '')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboards.supplier.shipments', compact('shipments'));
    }
    
    public function deliveryDetails($id)
    {
        $delivery = Delivery::findOrFail($id);
        return view('dashboards.supplier.delivery-details', compact('delivery'));
    }
    
    public function liveTracking()
    {
        $supplierId = session('user_id') ?? Auth::id();
        
        if (!$supplierId) {
            $supplier = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->first();
            $supplierId = $supplier ? $supplier->id : null;
        }
        
        $inTransitDeliveries = Delivery::where('supplier_id', $supplierId)
            ->where('status', 'in_transit')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $deliveredToday = Delivery::where('supplier_id', $supplierId)
            ->where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->count();
            
        $avgProgress = $inTransitDeliveries->avg('progress') ?? 0;
        
        return view('dashboards.supplier.live-tracking', compact(
            'inTransitDeliveries',
            'deliveredToday', 
            'avgProgress'
        ));
    }
    
    public function orders()
    {
        $supplierId = session('user_id') ?? Auth::id();
        
        if (!$supplierId) {
            $supplier = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->first();
            $supplierId = $supplier ? $supplier->id : null;
        }
        
        $orders = ChecklistRequest::where('supplier_id', $supplierId)
            ->with('manufacturer')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboards.supplier.orders', compact('orders'));
    }
    
    public function inventory()
    {
        $supplierId = session('user_id') ?? Auth::id();
        
        if (!$supplierId) {
            $supplier = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->first();
            $supplierId = $supplier ? $supplier->id : null;
        }
        
        $stocks = SupplierStock::where('supplier_id', $supplierId)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('dashboards.supplier.inventory', compact('stocks'));
    }
    
    public function createSupply()
    {
        return view('dashboards.supplier.add-supply');
    }
    
    public function confirmOrder($id)
    {
        $order = ChecklistRequest::findOrFail($id);
        $order->update(['status' => 'fulfilled']);
        
        return redirect()->back()->with('success', 'Order confirmed successfully!');
    }
    
    public function rejectOrder(Request $request, $id)
    {
        $order = ChecklistRequest::findOrFail($id);
        $order->update([
            'status' => 'declined',
            'rejection_reason' => $request->reason
        ]);
        
        return redirect()->back()->with('success', 'Order rejected successfully!');
    }
    
    public function updateOrder(Request $request, $id)
    {
        $order = ChecklistRequest::findOrFail($id);
        
        $materials = json_decode($request->materials, true);
        if (!$materials) {
            return redirect()->back()->with('error', 'Invalid materials data.');
        }
        
        $order->update([
            'materials_requested' => json_encode($materials)
        ]);
        
        return redirect()->back()->with('success', 'Order updated successfully!');
    }
    
    public function shipOrder(Request $request, $id)
    {
        $order = ChecklistRequest::findOrFail($id);
        $supplierId = session('user_id') ?? Auth::id();
        
        if (!$supplierId) {
            $supplier = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->first();
            $supplierId = $supplier ? $supplier->id : null;
        }
        
        // Check if delivery already exists
        $existingDelivery = Delivery::where('supplier_id', $supplierId)
            ->where('checklist_request_id', $order->id)
            ->first();
            
        if (!$existingDelivery) {
            Delivery::create([
                'supplier_id' => $supplierId,
                'manufacturer_id' => $order->manufacturer_id,
                'checklist_request_id' => $order->id,
                'materials_delivered' => $order->materials_requested,
                'status' => 'in_transit',
                'driver' => $request->driver,
                'destination' => $request->destination,
                'progress' => 25,
                'eta' => now()->addDays(rand(1, 5))
            ]);
        } else {
            $existingDelivery->update([
                'status' => 'in_transit',
                'driver' => $request->driver,
                'destination' => $request->destination,
                'progress' => 25
            ]);
        }
        
        return redirect()->back()->with('success', 'Order shipped successfully!');
    }
    
    public function deliverOrder($id)
    {
        $order = ChecklistRequest::findOrFail($id);
        $supplierId = session('user_id') ?? Auth::id();
        
        if (!$supplierId) {
            $supplier = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->first();
            $supplierId = $supplier ? $supplier->id : null;
        }
        
        $delivery = Delivery::where('supplier_id', $supplierId)
            ->where('checklist_request_id', $order->id)
            ->first();
            
        if ($delivery) {
            $delivery->update([
                'status' => 'delivered',
                'progress' => 100
            ]);
        }
        
        return redirect()->back()->with('success', 'Order delivered successfully!');
    }
} 