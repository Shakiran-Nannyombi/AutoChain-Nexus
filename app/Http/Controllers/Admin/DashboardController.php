<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analyst;
use App\Models\Manufacturer;
use App\Models\Retailer;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vendor;
use App\Models\FacilityVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\BackupCollection;
use Spatie\Backup\Helpers\Format;
use App\Models\ScheduledReport;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')->count();
        $totalUsers = User::count();

        $roleCounts = [
            'Suppliers' => Supplier::count(),
            'Manufacturers' => Manufacturer::count(),
            'Retailers' => Retailer::count(),
            'Vendors' => Vendor::count(),
            'Analysts' => Analyst::count(),
        ];

        $activeUsers = User::where('status', 'approved')->where('role', '!=', 'admin')->count();

        return view('dashboards.admin.index', [
            'pendingUsers' => $pendingUsers,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'roleCounts' => $roleCounts,
        ]);
    }

    public function systemFlow()
    {
        // --- System Components Data ---
        $componentData = [
            'manufacturers' => ['count' => User::where('role', 'manufacturer')->where('status', 'approved')->count(), 'connections' => 'suppliers, vendors'],
            'suppliers' => ['count' => User::where('role', 'supplier')->where('status', 'approved')->count(), 'connections' => 'manufacturers'],
            'vendors' => ['count' => User::where('role', 'vendor')->where('status', 'approved')->count(), 'connections' => 'manufacturers, retailers'],
            'retailers' => ['count' => User::where('role', 'retailer')->where('status', 'approved')->count(), 'connections' => 'vendors, customers'],
            'analysts' => ['count' => User::where('role', 'analyst')->where('status', 'approved')->count(), 'connections' => 'all'],
        ];
        $totalActiveUsers = array_sum(array_column($componentData, 'count'));

        // --- Stats Cards Data ---
        // Check API health for a more dynamic "System Health"
        $validationApiHealthy = $this->checkApiHealth('http://localhost:8080/api/v1/health');
        $emailApiHealthy = $this->checkApiHealth('http://localhost:8082/api/v1/health');
        $systemHealth = ($validationApiHealthy && $emailApiHealthy) ? 98 : 50;

        // Placeholder for active connections
        $activeConnections = $totalActiveUsers * 3 + rand(-10, 10);

        // Check for bottlenecks (e.g., pending visits older than a week)
        $bottlenecksCount = FacilityVisit::where('status', 'pending')->where('created_at', '<', now()->subWeek())->count();

        $stats = [
            'activeUsers' => $totalActiveUsers,
            'systemHealth' => $systemHealth,
            'activeConnections' => $activeConnections,
            'bottlenecks' => $bottlenecksCount,
        ];

        return view('dashboards.admin.system-flow', compact('stats', 'componentData'));
    }

    private function checkApiHealth(string $url): bool
    {
        try {
            $response = Http::timeout(2)->get($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function analytics()
    {
        $totalUsers = User::count();
        $pendingUsers = User::where('status', 'pending')->count();
        $approvedUsers = User::where('status', 'approved')->count();

        $usersByRole = User::where('status', 'approved')
            ->where('role', '!=', 'admin')
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        $userRegistrationData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as registrations'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
            
        $recentActivities = User::latest()->take(5)->get();

        return view('dashboards.admin.analytics', compact(
            'totalUsers',
            'pendingUsers',
            'approvedUsers',
            'usersByRole',
            'userRegistrationData',
            'recentActivities'
        ));
    }

    public function reports()
    {
        $scheduledReports = ScheduledReport::all();
        return view('dashboards.admin.reports', compact('scheduledReports'));
    }

    public function scheduleReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:user_activity,inventory_summary,validation_outcomes',
            'recipients' => 'required|string',
            'frequency' => 'required|string|in:daily,weekly,monthly',
        ]);

        ScheduledReport::create($request->all());

        return redirect()->route('admin.reports')->with('success', 'Report scheduled successfully!');
    }

    public function destroyReport(ScheduledReport $report)
    {
        $report->delete();
        return redirect()->route('admin.reports')->with('success', 'Scheduled report deleted successfully!');
    }

    public function inventoryOverview()
    {
        return view('dashboards.admin.inventory-overview');
    }

    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('dashboards.admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }

    public function backups()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->allFiles(config('backup.backup.name'));
    
        $backups = collect($files)
            ->map(function ($file) use ($disk) {
                return [
                    'path' => $file,
                    'date' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                    'size' => Format::humanReadableSize($disk->size($file)),
                ];
            })
            ->sortByDesc('date')
            ->values();
    
        return view('dashboards.admin.backups', ['backups' => $backups]);
    }

    public function createBackup()
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            return redirect()->back()->with('success', 'New backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup creation failed: ' . $e->getMessage());
        }
    }
}
