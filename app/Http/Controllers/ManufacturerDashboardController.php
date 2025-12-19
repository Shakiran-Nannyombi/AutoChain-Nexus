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
        $data = $this->procurementService->getChecklistsData($manufacturerId);
        return view('dashboards.manufacturer.checklists', $data);
    }

    public function sendChecklist(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'materials' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
        ]);
        $manufacturerId = session('user_id') ?? Auth::id();
        $this->procurementService->createChecklist($manufacturerId, $request->all());
        
        return redirect()->route('manufacturer.checklists')->with('success', 'Checklist sent to supplier!');
    }

    public function inventory_status()
    {
        $data = $this->inventoryService->getInventoryStatus();
        return view('dashboards.manufacturer.inventory-status', $data);
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
        $data = $this->inventoryService->getMaterialReceipts($manufacturerId);
        
        return view('dashboards.manufacturer.material-receipt', $data);
    }

    protected $productionAnalyticsService;
    protected $productionLineService;
    protected $dashboardService;
    protected $inventoryService;
    protected $procurementService;

    public function __construct(
        \App\Services\ProductionAnalyticsService $productionAnalyticsService,
        \App\Services\ProductionLineService $productionLineService,
        \App\Services\ManufacturerDashboardService $dashboardService,
        \App\Services\InventoryService $inventoryService,
        \App\Services\ProcurementService $procurementService
    ) {
        $this->productionAnalyticsService = $productionAnalyticsService;
        $this->productionLineService = $productionLineService;
        $this->dashboardService = $dashboardService;
        $this->inventoryService = $inventoryService;
        $this->procurementService = $procurementService;
    }

    public function production_lines()
    {
        $data = $this->productionLineService->getProductionLinesData();
        return view('dashboards.manufacturer.production-lines', $data);
    }

    public function production_analytics()
    {
        $data = $this->productionAnalyticsService->getAnalyticsData();

        return view('dashboards.manufacturer.production-analytics', $data);
    }

    public function index()
    {
        $manufacturerId = Auth::id();
        $metrics = $this->dashboardService->getDashboardMetrics($manufacturerId);
        
        return view('dashboards.manufacturer.index', $metrics);
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

    // PDF export for production reports
    public function exportProductionReportsPdf(Request $request)
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
        $totalItemsProcessed = $processFlows->count();
        $totalCompletedItems = $processFlows->where('status', 'completed')->count();
        $totalFailedItems = $processFlows->where('status', 'failed')->count();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('processFlows', 'totalItemsProcessed', 'totalCompletedItems', 'totalFailedItems'));
        return $pdf->download('production-report.pdf');
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

    public function orders(Request $request)
    {
        $manufacturerId = session('user_id') ?? Auth::id();
        $vendorStatus = $request->input('vendor_status');
        $data = $this->procurementService->getOrdersData($manufacturerId, $vendorStatus);
        
        return view('dashboards.manufacturer.orders', $data);
    }

    public function ordersPartial(Request $request)
    {
        $manufacturerId = session('user_id') ?? Auth::id();
        $query = \App\Models\ChecklistRequest::where('manufacturer_id', $manufacturerId)->with('supplier');

        // Filter by status
        $status = $request->input('status');
        if ($status && in_array($status, ['pending', 'fulfilled', 'cancelled'])) {
            $query->where('status', $status);
        }

        // Filter by supplier name or email
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('supplier', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%") ;
            });
        }

        $supplierOrders = $query->orderByDesc('created_at')->get();
        // Return only the table rows as a Blade partial
        return response()->view('dashboards.manufacturer.partials.supplier-orders-rows', compact('supplierOrders'));
    }

    public function remakeOrder($id)
    {
        $this->procurementService->remakeOrder($id);
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
        $segments = $vendors->groupBy('segment_name');
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
            $mostOrdered = $products->countBy()->sortDesc()->keys()->first() ?? 'N/A';
            $totalValue = $vendorOrders->sum(function($o) use ($productPrices) {
                return ($productPrices[$o->product] ?? 0) * $o->quantity;
            });
            $vendorAnalytics[] = [
                'name' => $vendor->name ?? ('Vendor #' . $vendor->user_id),
                'segment' => $vendor->segment_name,
                'total_orders' => $vendorOrders->count(),
                'most_ordered_product' => $mostOrdered,
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

    public function vendorSegmentationPage()
    {
        // Get all vendors with their analytics fields
        $vendors = DB::table('vendors')->get();
        $segmentAnalytics = [];
        $vendorAnalytics = [];
        $segmentLabels = [
            'Platinum' => 'Platinum',
            'Gold' => 'Gold',
            'Silver' => 'Silver',
        ];
        $segmentColors = [
            'Platinum' => '#E5C100',
            'Gold' => '#FFD700',
            'Silver' => '#C0C0C0',
        ];
        // Group vendors by segment_name
        $segments = $vendors->groupBy('segment_name');
        foreach ($segments as $segment => $segmentVendors) {
            $segmentAnalytics[$segment] = [
                'count' => $segmentVendors->count(),
                'total_orders' => $segmentVendors->sum('total_orders'),
                'total_value' => $segmentVendors->sum(function($vendor) {
                    // Remove everything except digits and dots, then cast to float
                    return (float) preg_replace('/[^\d.]/', '', $vendor->total_value);
                }),
            ];
        }
        foreach ($vendors as $vendor) {
            $vendorAnalytics[] = [
                'name' => $vendor->name,
                'segment' => $vendor->segment_name,
                'total_orders' => $vendor->total_orders,
                'most_ordered_product' => $vendor->most_ordered_product,
                'fulfillment_rate' => $vendor->fulfillment_rate,
                'total_value' => $vendor->total_value,
                'order_frequency' => $vendor->order_frequency,
                'cancellation_rate' => $vendor->cancellation_rate,
            ];
        }
        // Top 5 products overall
        $orders = DB::table('vendor_orders')->get();
        $topProducts = $orders->groupBy('product')->map(function($orders) {
            return $orders->count();
        })->sortDesc()->take(5)->toArray();
        return view('dashboards.manufacturer.vendor-segmentation', [
            'segmentAnalytics' => $segmentAnalytics,
            'vendorAnalytics' => $vendorAnalytics,
            'segmentLabels' => $segmentLabels,
            'segmentColors' => $segmentColors,
            'topProducts' => $topProducts,
        ]);
    }

    public function selectSalesAnalysis(Request $request)
    {
        $manufacturerId = Auth::id();
        // Get analyst IDs assigned to this manufacturer
        $analystIds = DB::table('analyst_manufacturer')
            ->where('manufacturer_id', $manufacturerId)
            ->where('status', 'approved')
            ->pluck('analyst_id');
        // Get sales analysis files uploaded by those analysts
        $files = \App\Models\AnalystReport::where('type', 'sales')
            ->whereIn('target_user_id', $analystIds)
            ->orderByDesc('created_at')
            ->get();
        // Preview the most recent file (if any)
        $preview = null;
        if ($files->count() > 0) {
            $file = $files->first();
            $path = storage_path('app/public/' . $file->report_file);
            if (file_exists($path)) {
                if (str_ends_with($path, '.csv') || str_ends_with($path, '.txt')) {
                    $preview = array_slice(file($path, FILE_IGNORE_NEW_LINES), 0, 10);
                } elseif (str_ends_with($path, '.json')) {
                    $json = json_decode(file_get_contents($path), true);
                    $preview = array_slice($json, 0, 10);
                }
            }
        }
        return view('dashboards.manufacturer.select-sales-analysis', compact('files', 'preview'));
    }
    
    public function confirmDelivery($id)
    {
        $delivery = \App\Models\Delivery::findOrFail($id);
        $delivery->update(['status' => 'completed']);
        
        return redirect()->back()->with('success', 'Delivery confirmed successfully! Order is now complete.');
    }
}