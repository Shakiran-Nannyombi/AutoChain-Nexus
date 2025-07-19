<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalystReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\RetailerSale;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AnalystReportController extends Controller
{
    public function index()
    {
        $reports = AnalystReport::latest()->paginate(10);
        return view('dashboards.analyst.reports', compact('reports'));
    }

    public function create()
    {
        $manufacturers = \App\Models\User::where('role', 'manufacturer')->orderBy('name')->get();
        return view('dashboards.analyst.create-reports', compact('manufacturers'));
    }


public function store(Request $request)
{
    $request->validate([
            'title' => 'required',
            'type' => 'required',
            'target_user_id' => 'required|exists:users,id',
            'summary' => 'required|string',
        ]);

        $user = User::findOrFail($request->target_user_id);

        // Fetch real sales data for retailers
        $labels = [];
        $values = [];
        $salesByModel = [];

        if ($user->role === 'retailer' && $user->retailer) {
            $sales = RetailerSale::where('retailer_id', $user->retailer->id)
                ->selectRaw('DATE(created_at) as date, SUM(quantity_sold) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $salesByModel = RetailerSale::where('retailer_id', $user->retailer->id)
                ->selectRaw('car_model, SUM(quantity_sold) as total_sold')
                ->groupBy('car_model')
                ->orderByDesc('total_sold')
                ->get();

            foreach ($sales as $entry) {
                $labels[] = Carbon::parse($entry->date)->format('M d'); // Ex: Jul 06
                $values[] = $entry->total;
            }
        }

        // Fallback in case there's no data
        if (empty($labels)) {
            $labels = ['No Data'];
            $values = [0];
        }

        // Generate chart image using QuickChart
        $chartUrl = $this->generateChartUrl($labels, $values);
        $chartResponse = Http::get($chartUrl);
        $base64Chart = base64_encode($chartResponse->body());

        // Save report entry
        $report = new AnalystReport();
        $report->title = $request->title;
        $report->type = $request->type;
        $report->target_user_id = $user->id;
        $report->target_role = $user->role;
        $report->summary = $request->summary;

        // Generate PDF
        $pdf = Pdf::loadView('dashboards.analyst.pdf-template', [
            'report' => $report,
            'chartBase64' => $base64Chart,
            'salesByModel' => $salesByModel,
        ]);

        $fileName = 'reports/' . uniqid() . '_' . Str::slug($report->title) . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $report->report_file = $fileName;
        $report->save();

    return redirect()->route('analyst.reports')->with('success', 'Report generated.');
}


    protected function generateChartUrl(array $labels, array $values): string
{
    $chartConfig = [
        'type' => 'bar',
        'data' => [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Sales Data',
                'data' => $values,
                'backgroundColor' => 'rgba(78, 115, 223, 0.6)'
            ]]
        ],
        'options' => [
            'title' => [
                'display' => true,
                'text' => 'Sales Overview'
            ],
            'legend' => ['display' => false]
        ]
    ];

    return 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));
}

// For target user to view their own reports
    public function userReports()
    {
        $reports = AnalystReport::where('target_user_id', Auth::id())->latest()->get();
        return view('dashboards.shared.user-reports', compact('reports'));
    }

    // --- Add these methods for analyst report routes ---
    public function salesReports()
    {
        // TODO: Implement actual logic and view
        return view('dashboards.analyst.sales-reports');
    }

    public function inventoryReports()
    {
        // TODO: Implement actual logic and view
        return view('dashboards.analyst.inventory-reports');
    }

    public function performanceReports()
    {
        // TODO: Implement actual logic and view
        return view('dashboards.analyst.performance-reports');
    }

    public function generate(Request $request)
    {
        $type = $request->input('type');
        $dateFrom = $request->input('start_date');
        $dateTo = $request->input('end_date');
        $orderReportType = $request->input('order_report_type');
        $manufacturerId = $request->input('manufacturer_id');
        $reportData = null;
        $headers = [];
        $rows = [];

        // Fetch all manufacturers for the selector
        $manufacturers = \App\Models\User::where('role', 'manufacturer')->orderBy('name')->get();

        if ($orderReportType === 'supplier_orders') {
            $query = \App\Models\ChecklistRequest::query();
            if ($manufacturerId) $query->where('manufacturer_id', $manufacturerId);
            if ($dateFrom) $query->whereDate('created_at', '>=', $dateFrom);
            if ($dateTo) $query->whereDate('created_at', '<=', $dateTo);
            $orders = $query->orderBy('created_at', 'desc')->limit(50)->get();
            $headers = ['Manufacturer', 'Supplier', 'Materials Requested', 'Status', 'Created At'];
            foreach ($orders as $order) {
                $materials = is_array($order->materials_requested) ? json_encode($order->materials_requested) : $order->materials_requested;
                $rows[] = [
                    optional($order->manufacturer)->name ?? 'N/A',
                    optional($order->supplier)->name ?? 'N/A',
                    $materials,
                    $order->status,
                    $order->created_at ? $order->created_at->format('Y-m-d') : '',
                ];
            }
        } elseif ($orderReportType === 'vendor_orders') {
            $query = \App\Models\VendorOrder::query();
            if ($manufacturerId) $query->where('manufacturer_id', $manufacturerId);
            if ($dateFrom) $query->whereDate('created_at', '>=', $dateFrom);
            if ($dateTo) $query->whereDate('created_at', '<=', $dateTo);
            $orders = $query->orderBy('created_at', 'desc')->limit(50)->get();
            $headers = ['Manufacturer', 'Vendor', 'Product', 'Quantity', 'Status', 'Ordered At'];
            foreach ($orders as $order) {
                $rows[] = [
                    optional($order->manufacturer)->name ?? 'N/A',
                    optional($order->vendor)->name ?? 'N/A',
                    $order->product_name ?? $order->product,
                    $order->quantity,
                    $order->status,
                    $order->ordered_at ? $order->ordered_at->format('Y-m-d') : '',
                ];
            }
        }
        if ($headers && $rows) {
            $queryString = http_build_query([
                'type' => $type,
                'start_date' => $dateFrom,
                'end_date' => $dateTo,
                'order_report_type' => $orderReportType,
                'manufacturer_id' => $manufacturerId,
            ]);
            $reportData = [
                'headers' => $headers,
                'rows' => $rows,
                'pdfUrl' => url('/analyst/reports/generate-pdf?' . $queryString),
                'csvUrl' => url('/analyst/reports/generate-csv?' . $queryString),
            ];
        }
        return view('dashboards.analyst.create-reports', compact('reportData', 'manufacturers'));
    }

    // Show upload form for sales analysis (analyst only)
    public function showUploadForm()
    {
        return view('dashboards.analyst.upload-sales-analysis');
    }

    // Handle upload of sales analysis CSV/JSON (analyst only)
    public function uploadSalesAnalysis(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'summary' => 'required|string',
            'file' => 'required|file|mimes:csv,txt,json',
        ]);
        $user = Auth::user();
        $file = $request->file('file');
        $filename = 'reports/' . uniqid() . '_' . $file->getClientOriginalName();
        $file->storeAs('public', $filename);
        $report = new AnalystReport();
        $report->title = $request->title;
        $report->type = 'sales';
        $report->target_user_id = $user->id;
        $report->target_role = $user->role;
        $report->summary = $request->summary;
        $report->report_file = $filename;
        $report->save();
        return redirect()->route('analyst.reports')->with('success', 'Sales analysis uploaded successfully.');
    }
}

