<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RetailerStock;
use App\Models\VendorActivity;
use Carbon\Carbon;

class VendorTrackingController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        
        // Get all active deliveries (not completed)
        $activeDeliveries = RetailerStock::where('vendor_id', $vendorId)
            ->whereNotIn('status', ['accepted', 'rejected'])
            ->with('retailer')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get delivery tracking history
        $trackingHistory = RetailerStock::where('vendor_id', $vendorId)
            ->with('retailer')
            ->orderBy('updated_at', 'desc')
            ->take(50)
            ->get();
        
        // Get delivery status distribution
        $statusDistribution = RetailerStock::where('vendor_id', $vendorId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Get delivery timeline for the last 30 days
        $deliveryTimeline = RetailerStock::where('vendor_id', $vendorId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('
                DATE(created_at) as date,
                status,
                COUNT(*) as count
            ')
            ->groupBy('date', 'status')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);
                return $item;
            });
        
        // Get average delivery times by status
        $deliveryTimes = RetailerStock::where('vendor_id', $vendorId)
            ->whereNotNull('updated_at')
            ->selectRaw('
                status,
                AVG((julianday(updated_at) - julianday(created_at)) * 24) as avg_hours
            ')
            ->groupBy('status')
            ->get();
        
        // Get real-time tracking data (simulated)
        $realTimeTracking = $activeDeliveries->map(function ($delivery) {
            $delivery->estimated_delivery = $this->calculateEstimatedDelivery($delivery);
            $delivery->current_location = $this->getCurrentLocation($delivery);
            $delivery->progress_percentage = $this->calculateProgress($delivery);
            return $delivery;
        });
        
        return view('dashboards.vendor.tracking', compact(
            'activeDeliveries',
            'trackingHistory',
            'statusDistribution',
            'deliveryTimeline',
            'deliveryTimes',
            'realTimeTracking'
        ));
    }
    
    public function getDeliveryDetails($stockId)
    {
        $delivery = RetailerStock::where('vendor_id', Auth::id())
            ->with('retailer')
            ->findOrFail($stockId);
        
        // Get delivery timeline events
        $timelineEvents = [
            [
                'event' => 'Order Created',
                'timestamp' => $delivery->created_at,
                'status' => 'completed'
            ],
            [
                'event' => 'Processing',
                'timestamp' => $delivery->created_at->addHours(2),
                'status' => 'completed'
            ],
            [
                'event' => 'Packed',
                'timestamp' => $delivery->status === 'to_be_shipped' ? now() : null,
                'status' => $delivery->status === 'to_be_shipped' ? 'completed' : 'pending'
            ],
            [
                'event' => 'Shipped',
                'timestamp' => $delivery->status === 'to_be_delivered' ? now() : null,
                'status' => $delivery->status === 'to_be_delivered' ? 'completed' : 'pending'
            ],
            [
                'event' => 'Delivered',
                'timestamp' => $delivery->status === 'accepted' ? now() : null,
                'status' => $delivery->status === 'accepted' ? 'completed' : 'pending'
            ]
        ];
        
        return response()->json([
            'delivery' => $delivery,
            'timeline' => $timelineEvents,
            'estimated_delivery' => $this->calculateEstimatedDelivery($delivery),
            'current_location' => $this->getCurrentLocation($delivery),
            'progress_percentage' => $this->calculateProgress($delivery)
        ]);
    }
    
    private function calculateEstimatedDelivery($delivery)
    {
        $baseTime = $delivery->created_at;
        
        switch ($delivery->status) {
            case 'to_be_packed':
                return $baseTime->addDays(2);
            case 'to_be_shipped':
                return $baseTime->addDays(3);
            case 'to_be_delivered':
                return $baseTime->addDays(5);
            default:
                return $baseTime->addDays(7);
        }
    }
    
    private function getCurrentLocation($delivery)
    {
        switch ($delivery->status) {
            case 'to_be_packed':
                return 'Warehouse - Processing';
            case 'to_be_shipped':
                return 'Warehouse - Ready for Shipment';
            case 'to_be_delivered':
                return 'In Transit';
            default:
                return 'Unknown';
        }
    }
    
    private function calculateProgress($delivery)
    {
        switch ($delivery->status) {
            case 'to_be_packed':
                return 25;
            case 'to_be_shipped':
                return 50;
            case 'to_be_delivered':
                return 75;
            case 'accepted':
            case 'rejected':
                return 100;
            default:
                return 0;
        }
    }
} 