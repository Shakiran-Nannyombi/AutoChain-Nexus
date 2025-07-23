<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RetailerStock;
use App\Models\VendorOrder;
use App\Models\VendorActivity;
use App\Models\Delivery;
use Carbon\Carbon;

class VendorDeliveryController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        // Fetch all deliveries for demo/testing
        $deliveries = Delivery::orderByDesc('created_at')->get();
        // Keep the old variables for now if needed by the view
        $pendingDeliveries = collect();
        $inTransitDeliveries = collect();
        $completedDeliveries = collect();
        $vendorOrders = collect();
        $totalPending = 0;
        $totalInTransit = 0;
        $totalCompleted = 0;
        $totalOrders = 0;
        $deliveryPerformance = collect();
        $upcomingDeliveries = collect();
        return view('dashboards.vendor.delivery', compact(
            'deliveries',
            'pendingDeliveries',
            'inTransitDeliveries',
            'completedDeliveries',
            'vendorOrders',
            'totalPending',
            'totalInTransit',
            'totalCompleted',
            'totalOrders',
            'deliveryPerformance',
            'upcomingDeliveries'
        ));
    }
    
    public function updateDeliveryStatus(Request $request, $stockId)
    {
        $request->validate([
            'status' => 'required|in:to_be_shipped,to_be_delivered,shipped'
        ]);
        
        $stock = RetailerStock::where('vendor_id', Auth::id())
            ->findOrFail($stockId);
        
        $oldStatus = $stock->status;
        $stock->update(['status' => $request->status]);
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Updated delivery status',
            'details' => "Stock ID: {$stockId}, Old: {$oldStatus}, New: {$request->status}",
        ]);
        
        return back()->with('success', 'Delivery status updated successfully.');
    }
    
    public function scheduleDelivery(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:retailer_stocks,id',
            'scheduled_date' => 'required|date|after:today'
        ]);
        
        $stock = RetailerStock::where('vendor_id', Auth::id())
            ->findOrFail($request->stock_id);
        
        $stock->update([
            'scheduled_delivery_date' => $request->scheduled_date,
            'status' => 'scheduled'
        ]);
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Scheduled delivery',
            'details' => "Stock ID: {$stock->id}, Date: {$request->scheduled_date}",
        ]);
        
        return back()->with('success', 'Delivery scheduled successfully.');
    }
} 