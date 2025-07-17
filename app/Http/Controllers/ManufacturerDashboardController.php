<?php

namespace App\Http\Controllers;

use App\Models\ProcessFlow;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SupplierStock;
use App\Models\RetailerStock;
use App\Models\ChecklistRequest;
use App\Models\VendorOrder;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ManufacturerDashboardController extends Controller
{
    public function chat()
    {
        $userId = session('user_id') ?? Auth::id();
        // Manufacturer can chat with suppliers, vendors, and admin
        $users = \App\Models\User::whereIn('role', ['supplier', 'vendor'])
            ->where('id', '!=', $userId)
            ->get();
        // Add admin users from the admins table
        $adminUsers = \App\Models\Admin::where('is_active', true)
            ->get()
            ->map(function($admin) {
                return (object) [
                    'id' => 'admin_' . $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => 'admin',
                    'profile_photo' => $admin->profile_photo,
                    'company' => $admin->company,
                    'phone' => $admin->phone,
                    'address' => $admin->address,
                    'documents' => collect(),
                ];
            });
        $users = $users->concat($adminUsers);

        // Demo chat messages for each user (will be handled in Blade)
        return view('dashboards.manufacturer.chat', compact('users'));
    }

    public function checklists()
    {
        $manufacturerId = session('user_id') ?? Auth::id();
        $suppliers = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->get();
        $sentChecklists = ChecklistRequest::where('manufacturer_id', $manufacturerId)->with('supplier')->latest()->get();
        return view('dashboards.manufacturer.checklists', compact('suppliers', 'sentChecklists'));
    }

    public function sendChecklist(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'materials' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
        ]);
        $manufacturerId = session('user_id') ?? Auth::id();
        $materials = $request->input('materials');
        $quantities = $request->input('quantities');
        $materialsRequested = [];
        foreach ($materials as $i => $mat) {
            $mat = trim($mat);
            $qty = isset($quantities[$i]) ? (int)$quantities[$i] : 0;
            if ($mat && $qty > 0) {
                $materialsRequested[$mat] = $qty;
            }
        }
        ChecklistRequest::create([
            'manufacturer_id' => $manufacturerId,
            'supplier_id' => $request->supplier_id,
            'materials_requested' => $materialsRequested,
            'status' => 'pending',
        ]);
        return redirect()->route('manufacturer.checklists')->with('success', 'Checklist sent to supplier!');
    }

    public function inventory_status()
    {
        $supplierStocks = SupplierStock::all();
        $retailerStocks = RetailerStock::all();

        $totalSupplierStock = $supplierStocks->sum('quantity');
        $totalRetailerStock = $retailerStocks->sum('quantity_received');

        return view('dashboards.manufacturer.inventory-status', compact('supplierStocks', 'retailerStocks', 'totalSupplierStock', 'totalRetailerStock'));
    }

    public function machine_health()
    {
        return view('dashboards.manufacturer.machine-health');
    }

    public function maintenance()
    {
        return view('dashboards.manufacturer.maintenance');
    }

    public function material_receipt()
    {
        $manufacturerId = session('user_id') ?? Auth::id();
        $deliveries = \App\Models\Delivery::where('manufacturer_id', $manufacturerId)->with('supplier')->latest()->get();
        $confirmedOrders = \App\Models\ChecklistRequest::where('manufacturer_id', $manufacturerId)
            ->where('status', 'fulfilled')
            ->with('supplier')
            ->latest()
            ->get();
        return view('dashboards.manufacturer.material-receipt', compact('deliveries', 'confirmedOrders'));
    }

    public function production_lines()
    {
        $processFlows = ProcessFlow::all();

        // Define stage order and their corresponding progress percentages
        $stageProgressMap = [
            'raw_materials' => 20,
            'manufacturing' => 40,
            'quality_control' => 60,
            'distribution' => 80,
            'retail' => 100,
            'completed' => 100, // Completed items are 100% done
            'failed' => 0, // Failed items don't contribute to forward progress
        ];

        $productionLines = [];
        foreach ($processFlows as $flow) {
            $itemName = $flow->item_name;
            if (!isset($productionLines[$itemName])) {
                $productionLines[$itemName] = [
                    'completed_items' => 0,
                    'total_items' => 0,
                    'has_failed_item' => false,
                    'has_incomplete_item' => false,
                    'current_stage' => 'N/A', // Will be updated later
                    'status' => 'N/A', // Will be updated later
                    'max_stage_progress' => 0, // To track the furthest stage reached
                ];
            }
            $productionLines[$itemName]['total_items']++;

            if ($flow->status === 'completed') {
                $productionLines[$itemName]['completed_items']++;
            } else {
                $productionLines[$itemName]['has_incomplete_item'] = true;
            }
            if ($flow->status === 'failed') {
                $productionLines[$itemName]['has_failed_item'] = true;
            }
        }

        // Determine the overall status and current stage for each line
        foreach ($productionLines as $itemName => &$data) {
            // Calculate max_stage_progress for the line
            $currentLineItems = collect($processFlows)->where('item_name', $itemName);
            foreach ($currentLineItems as $item) {
                $stage = $item->status === 'completed' ? 'completed' : ($item->status === 'failed' ? 'failed' : $item->current_stage);
                $progress = $stageProgressMap[$stage] ?? 0;
                if ($progress > $data['max_stage_progress']) {
                    $data['max_stage_progress'] = $progress;
                }
            }

            if ($data['has_failed_item']) {
                $data['status'] = 'stopped';
                $failedItem = $currentLineItems->where('status', 'failed')->first();
                $data['current_stage'] = $failedItem->current_stage ?? 'N/A';
            } elseif (!$data['has_incomplete_item']) {
                $data['status'] = 'completed';
                $data['current_stage'] = 'Finished';
            } else {
                $data['status'] = 'running';
                $incompleteItem = $currentLineItems
                                    ->where('status', '!=', 'completed')
                                    ->sortBy('entered_stage_at')
                                    ->first();
                $data['current_stage'] = $incompleteItem->current_stage ?? 'N/A';
            }
        }
        unset($data); // Unset the reference

        return view('dashboards.manufacturer.production-lines', compact('productionLines', 'processFlows'));
    }

    public function production_analytics()
    {
        $processFlows = ProcessFlow::all();

        // 1. Summary Production Statistics
        $totalItemsProcessed = $processFlows->count();
        $totalCompletedItems = $processFlows->where('status', 'completed')->count();
        $totalFailedItems = $processFlows->where('status', 'failed')->count();
        $overallYield = ($totalItemsProcessed > 0) ? round(($totalCompletedItems / $totalItemsProcessed) * 100, 2) : 0;

        // 2. Stage Distribution (for pie chart)
        $stageCounts = [
            'raw_materials' => 0,
            'manufacturing' => 0,
            'quality_control' => 0,
            'distribution' => 0,
            'retail' => 0,
            'completed' => 0,
            'failed' => 0,
        ];

        foreach ($processFlows as $flow) {
            if ($flow->status === 'completed') {
                $stageCounts['completed']++;
            } elseif ($flow->status === 'failed') {
                $stageCounts['failed']++;
            } else {
                if (array_key_exists($flow->current_stage, $stageCounts)) {
                    $stageCounts[$flow->current_stage]++;
                }
            }
        }

        $stageLabels = array_keys($stageCounts);
        $stageData = array_values($stageCounts);

        // 3. Stage Duration Analysis
        $stageDurations = [
            'raw_materials' => [],
            'manufacturing' => [],
            'quality_control' => [],
            'distribution' => [],
            'retail' => [],
        ];

        foreach ($processFlows as $flow) {
            if ($flow->entered_stage_at) {
                $start = Carbon::parse($flow->entered_stage_at);
                $end = null;

                if ($flow->status === 'completed' && $flow->completed_stage_at) {
                    $end = Carbon::parse($flow->completed_stage_at);
                } elseif ($flow->status === 'failed') {
                    $end = Carbon::parse($flow->updated_at); // Use updated_at for failed items
                } elseif ($flow->status === 'in_progress') {
                    $end = Carbon::now(); // For items still in progress
                }

                if ($end && $flow->current_stage && array_key_exists($flow->current_stage, $stageDurations)) {
                    $duration = $end->diffInMinutes($start); // Duration in minutes
                    $stageDurations[$flow->current_stage][] = $duration;
                }
            }
        }

        $averageStageDurations = [];
        foreach ($stageDurations as $stage => $durations) {
            $averageStageDurations[$stage] = count($durations) > 0 ? round(array_sum($durations) / count($durations), 2) : 0;
        }

        // 4. Production Rate Over Time
        $completedItemsByDate = $processFlows->where('status', 'completed')
                                            ->groupBy(function($date) {
                                                return Carbon::parse($date->completed_stage_at)->format('Y-m-d');
                                            })
                                            ->map->count();

        $productionRateLabels = $completedItemsByDate->keys()->sort()->values()->toArray();
        $productionRateData = $completedItemsByDate->values()->toArray();

        // 5. Failure Trends Over Time
        $failedItemsByDate = $processFlows->where('status', 'failed')
                                        ->groupBy(function($date) {
                                            return Carbon::parse($date->updated_at)->format('Y-m-d');
                                        })
                                        ->map->count();

        $failureTrendLabels = $failedItemsByDate->keys()->sort()->values()->toArray();
        $failureTrendData = $failedItemsByDate->values()->toArray();

        return view('dashboards.manufacturer.production-analytics', compact(
            'stageLabels',
            'stageData',
            'totalItemsProcessed',
            'totalCompletedItems',
            'totalFailedItems',
            'overallYield',
            'averageStageDurations',
            'productionRateLabels',
            'productionRateData',
            'failureTrendLabels',
            'failureTrendData'
        ));
    }

    public function production_reports(Request $request)
    {
        $query = ProcessFlow::query();

        // Apply date filters
        if ($request->has('start_date') && $request->input('start_date')) {
            $query->whereDate('entered_stage_at', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date') && $request->input('end_date')) {
            $query->whereDate('entered_stage_at', '<=', $request->input('end_date'));
        }

        $processFlows = $query->get();

        // Summary Production Statistics
        $totalItemsProcessed = $processFlows->count();
        $totalCompletedItems = $processFlows->where('status', 'completed')->count();
        $totalFailedItems = $processFlows->where('status', 'failed')->count();

        // Handle export
        if ($request->has('export')) {
            $format = $request->input('export');
            if ($format === 'csv') {
                $filename = 'production_report_' . now()->format('Ymd_His') . '.csv';
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ];
                $callback = function() use ($processFlows) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Item Name', 'Current Stage', 'Status', 'Entered Stage At', 'Completed Stage At', 'Failure Reason']);
                    foreach ($processFlows as $flow) {
                        fputcsv($file, [
                            $flow->item_name,
                            $flow->current_stage,
                            $flow->status,
                            $flow->entered_stage_at,
                            $flow->completed_stage_at,
                            $flow->failure_reason,
                        ]);
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            } elseif ($format === 'pdf') {
                // PDF generation would require a library like barryvdh/laravel-dompdf
                // For now, we'll just return a message.
                return response()->json(['message' => 'PDF export not yet implemented. Please use CSV.']);
            }
        }

        return view('dashboards.manufacturer.production-reports', compact(
            'processFlows',
            'totalItemsProcessed',
            'totalCompletedItems',
            'totalFailedItems'
        ));
    }

    public function quality_control()
    {
        return view('dashboards.manufacturer.quality-control');
    }

    public function scheduling()
    {
        return view('dashboards.manufacturer.scheduling');
    }

    public function settings()
    {
        return view('dashboards.manufacturer.settings');
    }

    public function workflow()
    {
        return view('dashboards.manufacturer.workflow');
    }

    public function index()
    {
        // Get all vendors with their segments
        $vendors = DB::table('vendors')->get();
        $vendorIds = $vendors->pluck('user_id')->toArray();

        // Get all vendor orders
        $orders = DB::table('vendor_orders')
            ->whereIn('vendor_id', $vendorIds)
            ->get();

        // Get product prices
        $productPrices = DB::table('products')->pluck('price', 'name');

        // Group vendors by segment
        $segments = $vendors->groupBy('segment');
        $segmentAnalytics = [];
        $vendorAnalytics = [];

        foreach ($segments as $segment => $segmentVendors) {
            $segmentVendorIds = $segmentVendors->pluck('user_id')->toArray();
            $segmentOrders = $orders->whereIn('vendor_id', $segmentVendorIds);

            // --- Segment-level analytics ---
            $totalOrders = $segmentOrders->count();
            $totalVendors = count($segmentVendorIds);
            $totalValue = $segmentOrders->sum(function($order) use ($productPrices) {
                return $order->quantity * ($productPrices[$order->product] ?? 0);
            });
            $avgOrderValue = $totalOrders > 0 ? $totalValue / $totalOrders : 0;
            $avgOrdersPerVendor = $totalVendors > 0 ? $totalOrders / $totalVendors : 0;
            $recency = $segmentOrders->max('ordered_at') ? now()->diffInDays($segmentOrders->max('ordered_at')) : null;
            $firstOrder = $segmentOrders->min('ordered_at');
            $lastOrder = $segmentOrders->max('ordered_at');
            $fulfilled = $segmentOrders->where('status', 'fulfilled')->count();
            $cancelled = $segmentOrders->where('status', 'cancelled')->count();
            $fulfillmentRate = $totalOrders > 0 ? round($fulfilled / $totalOrders * 100, 2) : 0;
            $cancellationRate = $totalOrders > 0 ? round($cancelled / $totalOrders * 100, 2) : 0;
            $ordersByMonth = $segmentOrders->groupBy(function($order) { return \Carbon\Carbon::parse($order->ordered_at)->format('Y-m'); });
            $orderFrequency = $ordersByMonth->count() > 0 ? round($totalOrders / $ordersByMonth->count(), 2) : 0;
            // Most ordered product (segment)
            $productCounts = $segmentOrders->groupBy('product')->map->count();
            $mostOrderedProduct = $productCounts->sortDesc()->keys()->first();
            $top3Products = $productCounts->sortDesc()->take(3)->toArray();

            $segmentAnalytics[$segment ?? 'Unsegmented'] = [
                'total_orders' => $totalOrders,
                'total_value' => $totalValue,
                'avg_order_value' => $avgOrderValue,
                'avg_orders_per_vendor' => $avgOrdersPerVendor,
                'recency_days' => $recency,
                'first_order' => $firstOrder,
                'last_order' => $lastOrder,
                'fulfillment_rate' => $fulfillmentRate,
                'cancellation_rate' => $cancellationRate,
                'order_frequency_per_month' => $orderFrequency,
                'most_ordered_product' => $mostOrderedProduct,
                'top3_products' => $top3Products,
                'vendor_count' => $totalVendors,
            ];

            // --- Per-vendor analytics ---
            foreach ($segmentVendors as $vendor) {
                $vendorOrders = $orders->where('vendor_id', $vendor->user_id);
                $vendorTotalOrders = $vendorOrders->count();
                $vendorTotalValue = $vendorOrders->sum(function($order) use ($productPrices) {
                    return $order->quantity * ($productPrices[$order->product] ?? 0);
                });
                $vendorAvgOrderValue = $vendorTotalOrders > 0 ? $vendorTotalValue / $vendorTotalOrders : 0;
                $vendorRecency = $vendorOrders->max('ordered_at') ? now()->diffInDays($vendorOrders->max('ordered_at')) : null;
                $vendorFirstOrder = $vendorOrders->min('ordered_at');
                $vendorLastOrder = $vendorOrders->max('ordered_at');
                $vendorFulfilled = $vendorOrders->where('status', 'fulfilled')->count();
                $vendorCancelled = $vendorOrders->where('status', 'cancelled')->count();
                $vendorFulfillmentRate = $vendorTotalOrders > 0 ? round($vendorFulfilled / $vendorTotalOrders * 100, 2) : 0;
                $vendorCancellationRate = $vendorTotalOrders > 0 ? round($vendorCancelled / $vendorTotalOrders * 100, 2) : 0;
                $vendorOrdersByMonth = $vendorOrders->groupBy(function($order) { return \Carbon\Carbon::parse($order->ordered_at)->format('Y-m'); });
                $vendorOrderFrequency = $vendorOrdersByMonth->count() > 0 ? round($vendorTotalOrders / $vendorOrdersByMonth->count(), 2) : 0;
                $vendorProductCounts = $vendorOrders->groupBy('product')->map->count();
                $vendorMostOrderedProduct = $vendorProductCounts->sortDesc()->keys()->first();
                $vendorTop3Products = $vendorProductCounts->sortDesc()->take(3)->toArray();

                $vendorAnalytics[$vendor->id] = [
                    'vendor' => $vendor,
                    'total_orders' => $vendorTotalOrders,
                    'total_value' => $vendorTotalValue,
                    'avg_order_value' => $vendorAvgOrderValue,
                    'recency_days' => $vendorRecency,
                    'first_order' => $vendorFirstOrder,
                    'last_order' => $vendorLastOrder,
                    'fulfillment_rate' => $vendorFulfillmentRate,
                    'cancellation_rate' => $vendorCancellationRate,
                    'order_frequency_per_month' => $vendorOrderFrequency,
                    'most_ordered_product' => $vendorMostOrderedProduct,
                    'top3_products' => $vendorTop3Products,
                ];
            }
        }

        // Compute top products across all vendors
        $topProducts = $orders->groupBy('product')->map(function($orders) {
            return $orders->count();
        })->sortDesc()->take(5)->toArray();

        // Segment labels and colors for charts
        $segmentLabels = [
            0 => 'Gold',
            1 => 'Silver',
            2 => 'Bronze',
            null => 'Unsegmented',
        ];
        $segmentColors = [
            0 => '#4CAF50',
            1 => '#2196F3',
            2 => '#FFC107',
            null => '#BDBDBD',
        ];

        $vendorSegmentNames = [
            0 => 'Top Vendors',
            1 => 'Growth Vendors',
            2 => 'New/Low Activity Vendors',
        ];
        return view('dashboards.manufacturer.index', [
            'segmentAnalytics' => $segmentAnalytics,
            'vendorAnalytics' => $vendorAnalytics,
            'vendorSegmentNames' => $vendorSegmentNames,
            'segmentLabels' => $segmentLabels,
            'segmentColors' => $segmentColors,
            'topProducts' => $topProducts,
        ]);
    }

    public function updateProcessFlowItem(Request $request)
    {
        $itemName = $request->input('item_name');
        $newStage = $request->input('new_stage');
        $markFailed = $request->input('mark_as_failed');
        $failureReason = $request->input('failure_reason');

        // Find the first incomplete item for the given item_name
        $item = ProcessFlow::where('item_name', $itemName)
                            ->where('status', '!=', 'completed')
                            ->first();

        if (!$item) {
            return response()->json(['message' => 'No incomplete item found for this line.'], 404);
        }

        if ($markFailed) {
            $item->status = 'failed';
            $item->failure_reason = $failureReason;
            // current_stage remains as is when failed
        } else {
            if ($newStage === 'completed') {
                $item->status = 'completed';
                $item->completed_stage_at = now();
                $item->current_stage = 'retail'; // Set to the last valid stage before completion
            } else {
                $item->current_stage = $newStage;
                $item->status = 'in_progress';
            }
            $item->failure_reason = null;
        }

        $item->save();

        return response()->json(['message' => 'Process flow item updated successfully.']);
    }

    public function orders()
    {
        $manufacturerId = session('user_id') ?? Auth::id();
        $supplierOrders = ChecklistRequest::where('manufacturer_id', $manufacturerId)->get();
        $vendorOrders = VendorOrder::where('manufacturer_id', $manufacturerId)->get();
        return view('dashboards.manufacturer.orders', compact('supplierOrders', 'vendorOrders'));
    }

    public function remakeOrder($id)
    {
        $order = \App\Models\ChecklistRequest::findOrFail($id);
        \App\Models\ChecklistRequest::create([
            'manufacturer_id' => $order->manufacturer_id,
            'supplier_id' => $order->supplier_id,
            'materials_requested' => $order->materials_requested,
            'status' => 'pending',
        ]);
        return back()->with('success', 'Order has been remade and sent to the supplier!');
    }

    public function orderDelivered($id)
    {
        $delivery = \App\Models\Delivery::findOrFail($id);
        $delivery->delete(); // Placeholder for closing the order
        return back()->with('success', 'Order marked as delivered and closed.');
    }

    public function analystApplications()
    {
        $manufacturerId = optional(Auth::user())->id;
        $applications = DB::table('analyst_manufacturer')
            ->where('manufacturer_id', $manufacturerId)
            ->join('users', 'analyst_manufacturer.analyst_id', '=', 'users.id')
            ->select('analyst_manufacturer.*', 'users.name as analyst_name', 'users.company as analyst_company', 'users.profile_photo as analyst_photo')
            ->get();
        return view('dashboards.manufacturer.analyst-applications', compact('applications'));
    }

    public function approveAnalyst($applicationId)
    {
        $manufacturerId = optional(Auth::user())->id;
        $application = DB::table('analyst_manufacturer')->where('id', $applicationId)->where('manufacturer_id', $manufacturerId)->first();
        if (!$application) {
            return back()->with('error', 'Application not found.');
        }
        // Approve this application
        DB::table('analyst_manufacturer')->where('id', $applicationId)->update(['status' => 'approved', 'updated_at' => now()]);
        // Optionally reject all other pending applications for this manufacturer
        DB::table('analyst_manufacturer')->where('manufacturer_id', $manufacturerId)->where('id', '!=', $applicationId)->where('status', 'pending')->update(['status' => 'rejected', 'updated_at' => now()]);
        return back()->with('success', 'Analyst approved!');
    }

    public function rejectAnalyst($applicationId)
    {
        $manufacturerId = optional(Auth::user())->id;
        $application = DB::table('analyst_manufacturer')->where('id', $applicationId)->where('manufacturer_id', $manufacturerId)->first();
        if (!$application) {
            return back()->with('error', 'Application not found.');
        }
        DB::table('analyst_manufacturer')->where('id', $applicationId)->update(['status' => 'rejected', 'updated_at' => now()]);
        return back()->with('success', 'Analyst rejected.');
    }

    public function viewAnalystPortfolio($analystId)
    {
        $analyst = \App\Models\User::findOrFail($analystId);
        // You can fetch more details or reports as needed
        return view('dashboards.manufacturer.analyst-portfolio', compact('analyst'));
    }

    public function exportAnalyticsPdf()
    {
        // Gather analytics as in index()
        $vendors = DB::table('vendors')->get();
        $vendorIds = $vendors->pluck('user_id')->toArray();
        $orders = DB::table('vendor_orders')->whereIn('vendor_id', $vendorIds)->get();
        $productPrices = DB::table('products')->pluck('price', 'name');
        $segments = $vendors->groupBy('segment');
        $segmentLabels = [0 => 'Gold', 1 => 'Silver', 2 => 'Bronze', null => 'Unsegmented'];
        $segmentColors = [0 => '#4CAF50', 1 => '#2196F3', 2 => '#FFC107', null => '#BDBDBD'];
        $segmentAnalytics = [];
        $vendorAnalytics = [];
        foreach ($segments as $segment => $segmentVendors) {
            $segmentVendorIds = $segmentVendors->pluck('user_id')->toArray();
            $segmentOrders = $orders->whereIn('vendor_id', $segmentVendorIds);
            $segmentAnalytics[$segment] = [
                'count' => count($segmentVendorIds),
                'total_orders' => $segmentOrders->count(),
                'total_value' => $segmentOrders->sum(function($o) use ($productPrices) {
                    return $productPrices[$o->product] ?? 0 * $o->quantity;
                }),
            ];
        }
        foreach ($vendors as $vendor) {
            $vendorOrders = $orders->where('vendor_id', $vendor->user_id);
            $products = $vendorOrders->pluck('product');
            $top3 = $products->countBy()->sortDesc()->take(3)->keys()->toArray();
            $mostOrdered = $top3[0] ?? 'N/A';
            $totalValue = $vendorOrders->sum(function($o) use ($productPrices) {
                return ($productPrices[$o->product] ?? 0) * $o->quantity;
            });
            $vendorAnalytics[] = [
                'name' => $vendor->name ?? ('Vendor #' . $vendor->user_id),
                'segment' => $segmentLabels[$vendor->segment] ?? 'Unsegmented',
                'total_orders' => $vendorOrders->count(),
                'most_ordered_product' => $mostOrdered,
                'top_3_products' => $top3,
                'total_value' => $totalValue,
            ];
        }
        // Top 5 products overall
        $topProducts = $orders->groupBy('product')->map(function($orders) {
            return $orders->count();
        })->sortDesc()->take(5)->toArray();
        // Generate charts as images using QuickChart.io
        $segmentChartConfig = [
            'type' => 'pie',
            'data' => [
                'labels' => array_values($segmentLabels),
                'datasets' => [[
                    'data' => array_map(fn($d) => $d['count'], $segmentAnalytics),
                    'backgroundColor' => array_values($segmentColors),
                ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['position' => 'bottom']],
                'responsive' => true,
            ],
        ];
        $segmentChartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($segmentChartConfig));
        $segmentChartImg = base64_encode(Http::get($segmentChartUrl)->body());
        $topProductsChartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => array_keys($topProducts),
                'datasets' => [[
                    'label' => 'Orders',
                    'data' => array_values($topProducts),
                    'backgroundColor' => ['#4CAF50', '#2196F3', '#FFC107', '#FF5722', '#9C27B0'],
                ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'responsive' => true,
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];
        $topProductsChartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($topProductsChartConfig));
        $topProductsChartImg = base64_encode(Http::get($topProductsChartUrl)->body());
        // Render PDF
        $pdf = Pdf::loadView('dashboards.manufacturer.pdf-report', [
            'segmentAnalytics' => $segmentAnalytics,
            'vendorAnalytics' => $vendorAnalytics,
            'segmentLabels' => $segmentLabels,
            'segmentChartImg' => $segmentChartImg,
            'topProductsChartImg' => $topProductsChartImg,
            'topProducts' => $topProducts,
        ]);
        $fileName = 'reports/vendor_analytics_' . now()->format('Ymd_His') . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());
        return response()->download(storage_path('app/public/' . $fileName));
    }
}