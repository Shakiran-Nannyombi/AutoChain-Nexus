<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RetailerStock;
use App\Models\VendorOrder;
use App\Models\VendorActivity;
use Carbon\Carbon;

class VendorDeliveryController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        
        // Get pending deliveries (stock to be shipped)
        $pendingDeliveries = RetailerStock::where('vendor_id', $vendorId)
            ->whereIn('status', ['to_be_packed', 'to_be_shipped'])
            ->with('retailer')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get in-transit deliveries
        $inTransitDeliveries = RetailerStock::where('vendor_id', $vendorId)
            ->where('status', 'to_be_delivered')
            ->with('retailer')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get completed deliveries
        $completedDeliveries = RetailerStock::where('vendor_id', $vendorId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->with('retailer')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        // Get vendor orders (incoming from manufacturers)
        $vendorOrders = VendorOrder::where('vendor_id', $vendorId)
            ->with('manufacturer')
            ->orderBy('ordered_at', 'desc')
            ->get();
        
        // Calculate delivery statistics
        $totalPending = $pendingDeliveries->count();
        $totalInTransit = $inTransitDeliveries->count();
        $totalCompleted = $completedDeliveries->count();
        $totalOrders = $vendorOrders->count();
        
        // Get delivery performance metrics
        $deliveryPerformance = RetailerStock::where('vendor_id', $vendorId)
            ->where('status', 'accepted')
            ->selectRaw('
                DATE(created_at) as delivery_date,
                COUNT(*) as successful_deliveries,
                AVG(DATEDIFF(updated_at, created_at)) as avg_delivery_time
            ')
            ->groupBy('delivery_date')
            ->orderBy('delivery_date', 'desc')
            ->take(30)
            ->get()
            ->map(function ($item) {
                $item->delivery_date = Carbon::parse($item->delivery_date);
                return $item;
            });
        
        // Get upcoming deliveries (next 7 days)
        $upcomingDeliveries = RetailerStock::where('vendor_id', $vendorId)
            ->whereIn('status', ['to_be_packed', 'to_be_shipped'])
            ->where('created_at', '<=', Carbon::now()->addDays(7))
            ->with('retailer')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('dashboards.vendor.delivery', compact(
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