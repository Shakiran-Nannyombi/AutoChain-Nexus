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
        $customerSegmentCounts = Customer::select('segment', DB::raw('count(*) as count'))
            ->whereNotNull('segment')
            ->groupBy('segment')
            ->get();

        // Use the same segment names as admin
        $segmentNames = [
            1 => 'Occasional Buyers',
            2 => 'High Value Customers',
            3 => 'At Risk Customers',
        ];

        $segmentSummaries = Customer::select(
            'segment',
            DB::raw('AVG((SELECT SUM(amount) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_total_spent'),
            DB::raw('AVG((SELECT COUNT(*) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_purchases'),
            DB::raw('AVG((SELECT DATEDIFF(CURDATE(), MAX(purchase_date)) FROM purchases WHERE purchases.customer_id = customers.id)) as avg_recency'),
            DB::raw('COUNT(*) as count')
        )
        ->whereNotNull('segment')
        ->groupBy('segment')
        ->get();

        return view('dashboards.manufacturer.index', compact('customerSegmentCounts', 'segmentNames', 'segmentSummaries'));
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
}