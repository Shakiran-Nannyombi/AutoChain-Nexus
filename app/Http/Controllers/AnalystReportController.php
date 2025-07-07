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
        return view('dashboards.analyst.create-reports');
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

}

