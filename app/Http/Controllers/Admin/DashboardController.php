<?php

/**
 * @method \Illuminate\Database\Eloquent\Relations\MorphMany notifications()
 */

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
use App\Models\AdminActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Auth\Authenticatable;

class DashboardController extends Controller
{
    /**
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany notifications()
     */
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')->count();
        $totalUsers = User::count();

        // Updated role counts for user distribution table
        $roleCounts = [
            'Manufacturers' => User::where('role', 'manufacturer')->where('status', 'approved')->count(),
            'Suppliers' => User::where('role', 'supplier')->where('status', 'approved')->count(),
            'Vendors' => User::where('role', 'vendor')->where('status', 'approved')->count(),
            'Retailers' => User::where('role', 'retailer')->where('status', 'approved')->count(),
            'Analysts' => User::where('role', 'analyst')->where('status', 'approved')->count(),
        ];

        $activeUsers = User::where('status', 'approved')->where('role', '!=', 'admin')->count();

        // Add demo recent activity if not present
        $recentActivities = \App\Models\AdminActivity::latest()->take(5)->get();
        if ($recentActivities->count() < 5) {
            $recentActivities = collect([
                (object)['action' => 'approved user', 'details' => 'Approved manufacturer user.', 'created_at' => now()->subMinutes(1)],
                (object)['action' => 'approved user', 'details' => 'Approved supplier user.', 'created_at' => now()->subMinutes(2)],
                (object)['action' => 'approved user', 'details' => 'Approved vendor user.', 'created_at' => now()->subMinutes(3)],
                (object)['action' => 'approved user', 'details' => 'Approved retailer user.', 'created_at' => now()->subMinutes(4)],
                (object)['action' => 'approved user', 'details' => 'Approved analyst user.', 'created_at' => now()->subMinutes(5)],
            ]);
        }

        // Fetch notifications for the current user (admin or user)
        $user = Auth::guard('admin')->user() ?? Auth::user();

        // Use notification relationships if available, else return empty collections
        $unreadNotifications = collect();
        $allNotifications = collect();

        // Ensure the user is an Authenticatable instance and has the notifications method
        // This check is crucial. If the error persists here, it's likely a caching issue
        // or an unexpected object type being returned by Auth::user()
        if ($user instanceof \Illuminate\Database\Eloquent\Model && method_exists($user, 'notifications')) {
            // Attempt to fetch notifications. If this still fails,
            // it strongly indicates a caching problem or a misconfigured model.
            try {
                $unreadNotifications = $user->notifications()->whereNull('read_at')->take(5)->get();
                $allNotifications = $user->notifications()->take(10)->get();
            } catch (\BadMethodCallException $e) {
                // Log the error or handle it gracefully if notifications method is truly missing
                // For now, we'll just let it fall through to empty collections
                Log::error("Failed to fetch notifications for user: " . $e->getMessage());
            }
        }

        return view('dashboards.admin.index', [
            'pendingUsers' => $pendingUsers,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'roleCounts' => $roleCounts,
            'recentActivities' => $recentActivities,
            'unreadNotifications' => $unreadNotifications,
            'allNotifications' => $allNotifications,
        ]);
    }

    public function systemFlow()
    {
        // --- System Components Data ---
        $componentData = [
            'manufacturers' => [
                'count' => User::where('role', 'manufacturer')->where('status', 'approved')->count(),
                'connections' => 'suppliers, vendors, analysts'
            ],
            'suppliers' => [
                'count' => User::where('role', 'supplier')->where('status', 'approved')->count(),
                'connections' => 'manufacturers'
            ],
            'vendors' => [
                'count' => User::where('role', 'vendor')->where('status', 'approved')->count(),
                'connections' => 'manufacturers, retailers, analysts'
            ],
            'retailers' => [
                'count' => User::where('role', 'retailer')->where('status', 'approved')->count(),
                'connections' => 'vendors, analysts'
            ],
            'analysts' => [
                'count' => User::where('role', 'analyst')->where('status', 'approved')->count(),
                'connections' => 'all'
            ],
        ];

        // --- Active Connections Calculation (based on communications table) ---
        $rolePairs = [
            'manufacturers' => ['supplier', 'vendor', 'analyst'],
            'suppliers' => ['manufacturer'],
            'vendors' => ['manufacturer', 'retailer', 'analyst'],
            'retailers' => ['vendor', 'analyst'],
            'analysts' => ['manufacturer', 'supplier', 'vendor', 'retailer'],
        ];
        $activeConnections = 0;
        $activeConnectionsPerRole = [];
        foreach ($rolePairs as $role => $targets) {
            $users = User::where('role', rtrim($role, 's'))->pluck('id');
            $roleConnectionCount = 0;
            foreach ($targets as $targetRole) {
                $targetUsers = User::where('role', $targetRole)->pluck('id');
                $count = \App\Models\Communication::whereIn('sender_id', $users)
                    ->whereIn('receiver_id', $targetUsers)
                    ->distinct('sender_id', 'receiver_id')
                    ->count();
                $roleConnectionCount += $count;
            }
            $activeConnections += $roleConnectionCount;
            $activeConnectionsPerRole[$role] = $roleConnectionCount;
        }

        // --- Stats Cards Data ---
        $validationApiHealthy = $this->checkApiHealth('http://localhost:8080/api/v1/health');
        $emailApiHealthy = $this->checkApiHealth('http://localhost:8082/api/v1/health');
        $systemHealth = ($validationApiHealthy && $emailApiHealthy) ? 98 : 50;

        $bottlenecksCount = FacilityVisit::where('status', 'pending')->where('created_at', '<', now()->subWeek())->count();

        $stats = [
            'activeUsers' => $componentData['manufacturers']['count'] + $componentData['suppliers']['count'] + $componentData['vendors']['count'] + $componentData['retailers']['count'] + $componentData['analysts']['count'],
            'systemHealth' => $systemHealth,
            'activeConnections' => max(0, $activeConnections),
            'bottlenecks' => $bottlenecksCount,
        ];

        // --- Flow Performance Metrics ---
        $stages = ['raw_materials', 'manufacturing', 'quality_control', 'distribution', 'retail'];
        $flowPerformance = [];
        $bottlenecks = [];
        foreach ($stages as $stage) {
            $stageItems = \App\Models\ProcessFlow::where('current_stage', $stage)->get();
            $completed = $stageItems->where('status', 'completed');
            $failed = $stageItems->where('status', 'failed');
            $inProgress = $stageItems->where('status', 'in_progress');
            $avgTime = $completed->count() > 0
                ? $completed->map(function ($item) {
                    return $item->completed_stage_at && $item->entered_stage_at ? strtotime($item->completed_stage_at) - strtotime($item->entered_stage_at) : null;
                })->filter()->avg() : null;
            $avgTime = $avgTime ? round($avgTime / 3600, 1) : null; // in hours
            $utilization = rand(50, 100); // Simulated for now
            // Bottleneck: any in_progress item in this stage for >2 days
            $bottleneckItems = $inProgress->filter(function ($item) {
                return $item->entered_stage_at && now()->diffInHours($item->entered_stage_at) > 48;
            });
            foreach ($bottleneckItems as $bItem) {
                $bottlenecks[] = [
                    'stage' => $stage,
                    'item' => $bItem->item_name,
                    'entered_stage_at' => $bItem->entered_stage_at,
                ];
            }
            $flowPerformance[$stage] = [
                'avg_time_hours' => $avgTime,
                'processed' => $completed->count(),
                'in_progress' => $inProgress->count(),
                'failed' => $failed->count(),
                'failures' => $failed->pluck('failure_reason')->filter()->values()->all(),
                'utilization' => $utilization,
            ];
        }

        // --- Quick Stats ---
        $pendingUsers = User::where('status', 'pending')->count();
        $scheduledVisitsCount = FacilityVisit::where('status', 'pending')->orWhere('status', 'approved')->count();
        $scheduledVisits = FacilityVisit::orderBy('visit_date', 'asc')->take(5)->get();

        // --- System Flow Stages for Visualization ---
        $flowStages = [
            [
                'name' => 'User Registration',
                'active' => User::where('status', 'pending')->count(),
                'status' => $pendingUsers > 0 ? 'warning' : 'normal',
            ],
            [
                'name' => 'Validation',
                'active' => User::where('status', 'approved')->count(),
                'status' => 'normal',
            ],
            [
                'name' => 'Inventory',
                'active' => 0, // Placeholder, update if you have inventory data
                'status' => 'normal',
            ],
            [
                'name' => 'Manufacturing',
                'active' => \App\Models\ProcessFlow::where('current_stage', 'manufacturing')->where('status', 'in_progress')->count(),
                'status' => 'normal',
            ],
            [
                'name' => 'Warehouse',
                'active' => 0, // Placeholder
                'status' => 'normal',
            ],
            [
                'name' => 'Delivery',
                'active' => 0, // Placeholder
                'status' => 'normal',
            ],
            [
                'name' => 'Retail',
                'active' => \App\Models\ProcessFlow::where('current_stage', 'retail')->where('status', 'in_progress')->count(),
                'status' => 'normal',
            ],
        ];

        // --- Recent Activity Feed ---
        $recentActivities = collect();
        $recentActivities = $recentActivities->merge(
            User::where('status', 'approved')->latest()->take(3)->get()->map(function ($u) {
                return [
                    'type' => 'user_approved',
                    'message' => "{$u->name} approved as {$u->role}",
                    'time' => $u->updated_at,
                ];
            })
        );
        $recentActivities = $recentActivities->merge(
            User::where('status', 'pending')->latest()->take(2)->get()->map(function ($u) {
                return [
                    'type' => 'user_registered',
                    'message' => "{$u->name} registered as {$u->role}",
                    'time' => $u->created_at,
                ];
            })
        );
        $recentActivities = $recentActivities->merge(
            FacilityVisit::latest()->take(2)->get()->map(function ($v) {
                return [
                    'type' => 'visit_scheduled',
                    'message' => "Scheduled visit to {$v->facility_name} confirmed",
                    'time' => $v->visit_date,
                ];
            })
        );
        $recentActivities = $recentActivities->sortByDesc('time')->take(5)->values();

        // --- Notifications & Alerts ---
        $notifications = [];
        if ($systemHealth < 90) {
            $notifications[] = [
                'type' => 'system_health',
                'message' => 'System health is below optimal!',
                'level' => 'warning',
            ];
        } else {
            $notifications[] = [
                'type' => 'system_health',
                'message' => 'All services running smoothly.',
                'level' => 'success',
            ];
        }
        if ($pendingUsers > 0) {
            $notifications[] = [
                'type' => 'pending_users',
                'message' => "$pendingUsers users pending approval.",
                'level' => 'info',
            ];
        }
        if ($bottlenecksCount > 0) {
            $notifications[] = [
                'type' => 'bottleneck',
                'message' => "$bottlenecksCount bottleneck(s) detected.",
                'level' => 'warning',
            ];
        }

        return view('dashboards.admin.system-flow', compact(
            'stats',
            'componentData',
            'activeConnectionsPerRole',
            'flowPerformance',
            'bottlenecks',
            'pendingUsers',
            'scheduledVisitsCount',
            'scheduledVisits',
            'flowStages',
            'recentActivities',
            'notifications'
        ));
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

        // User session analytics (bar graph)
        // Monthly sessions for the last 12 months
        $monthlySessions = DB::table('user_logins')
            ->selectRaw('DATE_FORMAT(logged_in_at, "%Y-%m") as month, COUNT(*) as sessions')
            ->where('logged_in_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Annual sessions for the last 5 years
        $annualSessions = DB::table('user_logins')
            ->selectRaw('YEAR(logged_in_at) as year, COUNT(*) as sessions')
            ->where('logged_in_at', '>=', now()->subYears(5)->startOfYear())
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // Inject demo data if empty
        if ($monthlySessions->isEmpty()) {
            $monthlySessions = [
                ['month' => '2024-08', 'sessions' => 12],
                ['month' => '2024-09', 'sessions' => 18],
                ['month' => '2024-10', 'sessions' => 25],
                ['month' => '2024-11', 'sessions' => 30],
                ['month' => '2024-12', 'sessions' => 22],
                ['month' => '2025-01', 'sessions' => 28],
                ['month' => '2025-02', 'sessions' => 35],
                ['month' => '2025-03', 'sessions' => 40],
                ['month' => '2025-04', 'sessions' => 38],
                ['month' => '2025-05', 'sessions' => 45],
                ['month' => '2025-06', 'sessions' => 50],
                ['month' => '2025-07', 'sessions' => 55],
            ];
        }
        if ($annualSessions->isEmpty()) {
            $annualSessions = [
                ['year' => '2021', 'sessions' => 120],
                ['year' => '2022', 'sessions' => 340],
                ['year' => '2023', 'sessions' => 410],
                ['year' => '2024', 'sessions' => 520],
                ['year' => '2025', 'sessions' => 600],
            ];
        }

        return view('dashboards.admin.analytics', compact(
            'totalUsers',
            'pendingUsers',
            'approvedUsers',
            'usersByRole',
            'userRegistrationData',
            'recentActivities',
            'monthlySessions',
            'annualSessions'
        ));
    }

    public function reports()
    {
        $scheduledReports = ScheduledReport::all();
        if ($scheduledReports->isEmpty()) {
            $scheduledReports = collect([
                (object)[
                    'id' => 1,
                    'report_type' => 'user_activity',
                    'recipients' => 'manager@example.com, ceo@example.com',
                    'frequency' => 'weekly',
                ],
                (object)[
                    'id' => 2,
                    'report_type' => 'inventory_summary',
                    'recipients' => 'ops@example.com',
                    'frequency' => 'monthly',
                ],
                (object)[
                    'id' => 3,
                    'report_type' => 'validation_outcomes',
                    'recipients' => 'compliance@example.com',
                    'frequency' => 'daily',
                ],
            ]);
        }
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

    public function chat(Request $request)
    {
        $adminId = Auth::check() ? Auth::id() : session('user_id');
        if (!$adminId) {
            abort(403, 'Unauthorized');
        }
        $users = \App\Models\User::where('id', '!=', $adminId)->get();
        $selectedUserId = $request->query('user');
        $messages = collect();
        $selectedUser = null;
        if ($selectedUserId) {
            $selectedUser = \App\Models\User::find($selectedUserId);
            $messages = \App\Models\Chat::where(function($q) use ($adminId, $selectedUserId) {
                $q->where('sender_id', $adminId)->where('receiver_id', $selectedUserId);
            })->orWhere(function($q) use ($adminId, $selectedUserId) {
                $q->where('sender_id', $selectedUserId)->where('receiver_id', $adminId);
            })->orderBy('created_at')->get();
        }
        return view('dashboards.admin.chat', compact('users', 'messages', 'selectedUser'));
    }
}
