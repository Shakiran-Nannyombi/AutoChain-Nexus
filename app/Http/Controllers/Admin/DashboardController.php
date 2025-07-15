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
            // Do not pass customerSegmentCounts or segmentSummaries
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

        // New: Get daily breakdown of new users by status for the last 7 days
        $userRegistrationStatusData = User::select(
                DB::raw('DATE(created_at) as date'),
                'status',
                DB::raw('count(*) as registrations')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date', 'status')
            ->orderBy('date', 'asc')
            ->get();

        // Format for frontend: {date => ['approved' => X, 'pending' => Y]}
        $userRegistrationStatusByDay = [];
        foreach ($userRegistrationStatusData as $row) {
            $date = $row->date;
            $status = $row->status;
            $count = $row->registrations;
            if (!isset($userRegistrationStatusByDay[$date])) {
                $userRegistrationStatusByDay[$date] = ['approved' => 0, 'pending' => 0];
            }
            $userRegistrationStatusByDay[$date][$status] = $count;
        }

        // For donut: sum all approved and pending in last 7 days
        $totalApprovedLast7 = 0;
        $totalPendingLast7 = 0;
        foreach ($userRegistrationStatusByDay as $day) {
            $totalApprovedLast7 += $day['approved'];
            $totalPendingLast7 += $day['pending'];
        }

        // Keep the old data for compatibility (total registrations per day)
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

        // --- Login Activity by Role for Last 7 Days ---
        $allRoles = User::distinct()->pluck('role')->toArray();
        $loginActivityRaw = DB::table('user_logins')
            ->join('users', 'user_logins.user_id', '=', 'users.id')
            ->selectRaw('DATE(user_logins.logged_in_at) as date, users.role, COUNT(*) as count')
            ->where('user_logins.logged_in_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date', 'users.role')
            ->orderBy('date', 'asc')
            ->get();
        $days = [];
        $roleCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $days[] = \Carbon\Carbon::parse($date)->format('d M');
            foreach ($allRoles as $role) {
                $roleCounts[$role][$date] = 0;
            }
        }
        foreach ($loginActivityRaw as $row) {
            $date = $row->date;
            $role = $row->role;
            if (isset($roleCounts[$role][$date])) {
                $roleCounts[$role][$date] += $row->count;
            }
        }
        $loginActivityData = [
            'days' => array_values($days),
            'roles' => $allRoles,
            'data' => array_map(function($role) use ($days, $roleCounts) {
                $result = [];
                foreach ($days as $i => $label) {
                    $date = now()->subDays(6 - $i)->format('Y-m-d');
                    $result[] = $roleCounts[$role][$date] ?? 0;
                }
                return $result;
            }, $allRoles),
        ];

        // Inject demo data if empty
        if (empty(array_filter(array_merge(...array_values($loginActivityData['data']))))) {
            $loginActivityData = [
                'days' => [
                    now()->subDays(6)->format('d M'),
                    now()->subDays(5)->format('d M'),
                    now()->subDays(4)->format('d M'),
                    now()->subDays(3)->format('d M'),
                    now()->subDays(2)->format('d M'),
                    now()->subDays(1)->format('d M'),
                    now()->format('d M'),
                ],
                'roles' => ['analyst', 'manufacturer', 'retailer', 'supplier', 'vendor'],
                'data' => [
                    // analyst
                    [3, 7, 4, 8, 5, 9, 6],
                    // manufacturer
                    [2, 5, 3, 7, 4, 8, 5],
                    // retailer
                    [4, 6, 8, 5, 9, 7, 10],
                    // supplier
                    [1, 3, 2, 4, 3, 5, 2],
                    // vendor
                    [2, 4, 3, 6, 2, 7, 3],
                ]
            ];
        }

        return view('dashboards.admin.analytics', compact(
            'totalUsers',
            'pendingUsers',
            'approvedUsers',
            'usersByRole',
            'userRegistrationData',
            'userRegistrationStatusByDay',
            'totalApprovedLast7',
            'totalPendingLast7',
            'recentActivities',
            'monthlySessions',
            'annualSessions',
            'loginActivityData'
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
        $userRoles = User::distinct()->pluck('role')->filter()->values();
        $users = User::select('id', 'name', 'email', 'role')->get();
        return view('dashboards.admin.reports', compact('scheduledReports', 'userRoles', 'users'));
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

    public function getChatMessages($userId)
    {
        $adminId = Auth::guard('admin')->id() ?? Auth::id() ?? session('user_id');
        if (!$adminId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $selectedUser = \App\Models\User::find($userId);
        if (!$selectedUser) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $messages = \App\Models\Chat::with('sender')->where(function($q) use ($adminId, $userId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($adminId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $adminId);
        })->orderBy('created_at')->get();
        if ($messages->isEmpty()) {
            $messagesArr = collect([
                [
                    'id' => 1,
                    'message' => 'Hello! I have an urgent issue with my recent order #ORD-2024-001. The delivery was supposed to arrive yesterday but it\'s still showing "in transit" on the tracking page.',
                    'sender_id' => $userId,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 2,
                    'message' => 'Hi there! I understand your concern. Let me check the status of your order #ORD-2024-001 right away.',
                    'sender_id' => $adminId,
                    'sender_name' => 'Admin',
                    'created_at' => now()->subHours(2)->addMinutes(1)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 3,
                    'message' => 'I can see that your order was processed and shipped on time, but there was a delay at the local distribution center due to weather conditions. The package is currently at the final sorting facility.',
                    'sender_id' => $adminId,
                    'sender_name' => 'Admin',
                    'created_at' => now()->subHours(2)->addMinutes(3)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 4,
                    'message' => 'That\'s frustrating. I really needed those parts for a client project that\'s due tomorrow. Is there anything you can do to expedite the delivery?',
                    'sender_id' => $userId,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(2)->addMinutes(5)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 5,
                    'message' => 'Absolutely! I\'ve just contacted our logistics partner and arranged for priority delivery. Your package will be delivered by 2 PM today. I\'ve also added a 15% discount to your next order as compensation for the inconvenience.',
                    'sender_id' => $adminId,
                    'sender_name' => 'Admin',
                    'created_at' => now()->subHours(2)->addMinutes(8)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 6,
                    'message' => 'That\'s amazing! Thank you so much for your quick response and going above and beyond. I really appreciate the discount too. You\'ve saved my project!',
                    'sender_id' => $userId,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(2)->addMinutes(10)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 7,
                    'message' => 'You\'re very welcome! Customer satisfaction is our top priority. I\'ve also updated your tracking information - you should receive a notification when the package is out for delivery. Is there anything else I can help you with today?',
                    'sender_id' => $adminId,
                    'sender_name' => 'Admin',
                    'created_at' => now()->subHours(2)->addMinutes(12)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 8,
                    'message' => 'Actually, yes! I was thinking about placing another order for some additional parts. Do you have any recommendations for the new XYZ model components?',
                    'sender_id' => $userId,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(1)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 9,
                    'message' => 'Great question! For the XYZ model, I\'d recommend our premium line - they have better durability and come with extended warranty. I can send you our latest catalog with detailed specifications. Would you like me to email it to you?',
                    'sender_id' => $adminId,
                    'sender_name' => 'Admin',
                    'created_at' => now()->subHours(1)->addMinutes(2)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 10,
                    'message' => 'Perfect! That would be very helpful. Thanks again for all your help today. You\'ve been incredibly professional and responsive.',
                    'sender_id' => $userId,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(1)->addMinutes(4)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
            ]);
        } else {
            $messagesArr = $messages->map(function($msg) use ($adminId) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender ? $msg->sender->name : 'Unknown',
                    'created_at' => $msg->created_at ? $msg->created_at->format('Y-m-d H:i') : '',
                    'is_me' => $msg->sender_id == $adminId,
                ];
            });
        }
        return response()->json([
            'status' => 'success',
            'messages' => $messagesArr,
            'user' => [
                'id' => $selectedUser->id,
                'name' => $selectedUser->name,
                'avatar' => $selectedUser->profile_photo 
                    ? asset($selectedUser->profile_photo) 
                    : null,
                'status' => 'online', // or use a real status if available
            ]
        ]);
    }

    public function sendChatMessage(Request $request)
    {
        $adminId = Auth::guard('admin')->id() ?? Auth::id() ?? session('user_id');
        if (!$adminId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);
        $chat = \App\Models\Chat::create([
            'sender_id' => $adminId,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
        return response()->json(['status' => 'success', 'message' => 'Message sent!', 'data' => $chat]);
    }
}
