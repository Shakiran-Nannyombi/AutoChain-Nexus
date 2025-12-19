<?php

namespace App\Services;

use App\Models\User;
use App\Models\FacilityVisit;
use App\Models\ProcessFlow;
use App\Models\Communication;
use App\Models\AdminActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminDashboardService
{
    /**
     * Get main dashboard statistics and recent activity.
     */
    public function getDashboardStats()
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
        $recentActivities = AdminActivity::latest()->take(5)->get();
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

        if ($user instanceof \Illuminate\Database\Eloquent\Model && method_exists($user, 'notifications')) {
            try {
                $unreadNotifications = $user->notifications()->whereNull('read_at')->take(5)->get();
                $allNotifications = $user->notifications()->take(10)->get();
            } catch (\BadMethodCallException $e) {
                Log::error("Failed to fetch notifications for user: " . $e->getMessage());
            }
        }

        return compact(
            'pendingUsers',
            'totalUsers',
            'activeUsers',
            'roleCounts',
            'recentActivities'
        );
    }

    /**
     * Get comprehensive system flow and health data.
     */
    public function getSystemFlowData()
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

        // --- Active Connections Calculation ---
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
                $count = Communication::whereIn('sender_id', $users)
                    ->whereIn('receiver_id', $targetUsers)
                    ->select('sender_id', 'receiver_id')
                    ->distinct()
                    ->get()
                    ->count();
                $roleConnectionCount += $count;
            }
            $activeConnections += $roleConnectionCount;
            $activeConnectionsPerRole[$role] = $roleConnectionCount;
        }

        // --- Stats Cards Data ---
        $validationApiHealthy = $this->checkApiHealth('http://localhost:8084/api/v1/health');
        $emailApiHealthy = $this->checkApiHealth('http://localhost:8082/api/v1/health');
        $systemHealth = ($validationApiHealthy && $emailApiHealthy) ? 98 : 50;

        $bottlenecksCount = FacilityVisit::where('status', 'pending')
            ->where('created_at', '<', now()->subWeek())
            ->count();

        $pendingValidations = User::where('status', 'pending')->count();
        $visitsScheduled = FacilityVisit::whereIn('status', ['pending', 'approved'])->count();
        $visitsCompleted = FacilityVisit::where('status', 'completed')->count();
        
        $stats = [
            'activeUsers' => array_sum(array_column($componentData, 'count')),
            'systemHealth' => $systemHealth,
            'activeConnections' => max(0, $activeConnections),
            'bottlenecks' => $bottlenecksCount,
            'pendingValidations' => $pendingValidations,
            'visitsScheduled' => $visitsScheduled,
            'visitsCompleted' => $visitsCompleted,
        ];

        // --- Flow Performance Metrics ---
        $stages = ['raw_materials', 'manufacturing', 'quality_control', 'distribution', 'retail'];
        $flowPerformance = [];
        $bottlenecks = [];
        
        foreach ($stages as $stage) {
            $stageItems = ProcessFlow::where('current_stage', $stage)->get();
            $completed = $stageItems->where('status', 'completed');
            $failed = $stageItems->where('status', 'failed');
            $inProgress = $stageItems->where('status', 'in_progress');
            
            $avgTime = $completed->count() > 0
                ? $completed->map(function ($item) {
                     return $item->completed_stage_at && $item->entered_stage_at 
                        ? strtotime($item->completed_stage_at) - strtotime($item->entered_stage_at) 
                        : null;
                })->filter()->avg() 
                : null;
                
            $avgTime = $avgTime ? round($avgTime / 3600, 1) : null; // in hours
            $utilization = rand(50, 100); // Simulated
            
            // Bottleneck logic
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
                'active' => 0, 
                'status' => 'normal',
            ],
            [
                'name' => 'Manufacturing',
                'active' => ProcessFlow::where('current_stage', 'manufacturing')->where('status', 'in_progress')->count(),
                'status' => 'normal',
            ],
            [
                'name' => 'Warehouse',
                'active' => 0, 
                'status' => 'normal',
            ],
            [
                'name' => 'Delivery',
                'active' => 0, 
                'status' => 'normal',
            ],
            [
                'name' => 'Retail',
                'active' => ProcessFlow::where('current_stage', 'retail')->where('status', 'in_progress')->count(),
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
            $notifications[] = ['type' => 'system_health', 'message' => 'System health is below optimal!', 'level' => 'warning'];
        } else {
            $notifications[] = ['type' => 'system_health', 'message' => 'All services running smoothly.', 'level' => 'success'];
        }
        if ($pendingUsers > 0) {
            $notifications[] = ['type' => 'pending_users', 'message' => "$pendingUsers users pending approval.", 'level' => 'info'];
        }
        if ($bottlenecksCount > 0) {
            $notifications[] = ['type' => 'bottleneck', 'message' => "$bottlenecksCount bottleneck(s) detected.", 'level' => 'warning'];
        }

        // Active Connections List
        $activeConnectionsList = Communication::with(['sender:id,name,role', 'receiver:id,name,role'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        if ($activeConnectionsList->count() < 5) {
            $demoConnections = collect([
                (object)['sender' => (object)['name' => 'Vendor Alpha', 'role' => 'vendor'], 'receiver' => (object)['name' => 'Manufacturer Beta', 'role' => 'manufacturer'], 'created_at' => now()->subMinutes(10)],
                (object)['sender' => (object)['name' => 'Retailer Gamma', 'role' => 'retailer'], 'receiver' => (object)['name' => 'Supplier Delta', 'role' => 'supplier'], 'created_at' => now()->subMinutes(20)],
                (object)['sender' => (object)['name' => 'Manufacturer Beta', 'role' => 'manufacturer'], 'receiver' => (object)['name' => 'Analyst Zeta', 'role' => 'analyst'], 'created_at' => now()->subMinutes(30)],
                (object)['sender' => (object)['name' => 'Supplier Delta', 'role' => 'supplier'], 'receiver' => (object)['name' => 'Vendor Alpha', 'role' => 'vendor'], 'created_at' => now()->subMinutes(40)],
                (object)['sender' => (object)['name' => 'Admin', 'role' => 'admin'], 'receiver' => (object)['name' => 'Retailer Gamma', 'role' => 'retailer'], 'created_at' => now()->subMinutes(50)],
            ]);
            $activeConnectionsList = $activeConnectionsList->concat($demoConnections)->take(5);
        }

        return compact(
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
            'notifications',
            'activeConnectionsList'
        );
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

    /**
     * Get analytics data (graphs/charts).
     */
    public function getAnalyticsData()
    {
        $totalUsers = User::count();
        $pendingUsers = User::where('status', 'pending')->count();
        $approvedUsers = User::where('status', 'approved')->count();

        $usersByRole = User::where('status', 'approved')
            ->where('role', '!=', 'admin')
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        $userRegistrationStatusData = User::select(
                DB::raw('DATE(created_at) as date'),
                'status',
                DB::raw('count(*) as registrations')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date', 'status')
            ->orderBy('date', 'asc')
            ->get();

        $userRegistrationStatusByDay = [];
        foreach ($userRegistrationStatusData as $row) {
            $date = $row->date;
            $status = $row->status;
            if (!isset($userRegistrationStatusByDay[$date])) {
                $userRegistrationStatusByDay[$date] = ['approved' => 0, 'pending' => 0];
            }
            $userRegistrationStatusByDay[$date][$status] = $row->registrations;
        }

        $totalApprovedLast7 = 0;
        $totalPendingLast7 = 0;
        foreach ($userRegistrationStatusByDay as $day) {
            $totalApprovedLast7 += $day['approved'] ?? 0;
            $totalPendingLast7 += $day['pending'] ?? 0;
        }

        $userRegistrationData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as registrations'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
            
        $recentActivities = User::latest()->take(5)->get();

        // Monthly sessions
        // Monthly sessions
        $monthlySessions = DB::table('user_logins')
            ->selectRaw('strftime("%Y-%m", logged_in_at) as month, COUNT(*) as sessions')
            ->where('logged_in_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Annual sessions
        // Annual sessions
        $annualSessions = DB::table('user_logins')
            ->selectRaw('strftime("%Y", logged_in_at) as year, COUNT(*) as sessions')
            ->where('logged_in_at', '>=', now()->subYears(5)->startOfYear())
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // Inject demo data if empty
        if ($monthlySessions->isEmpty()) {
            $monthlySessions = [
                ['month' => '2024-08', 'sessions' => 12], ['month' => '2024-09', 'sessions' => 18],
                ['month' => '2024-10', 'sessions' => 25], ['month' => '2024-11', 'sessions' => 30],
                ['month' => '2024-12', 'sessions' => 22], ['month' => '2025-01', 'sessions' => 28],
                ['month' => '2025-02', 'sessions' => 35], ['month' => '2025-03', 'sessions' => 40],
                ['month' => '2025-04', 'sessions' => 38], ['month' => '2025-05', 'sessions' => 45],
                ['month' => '2025-06', 'sessions' => 50], ['month' => '2025-07', 'sessions' => 55],
            ];
        }
        if ($annualSessions->isEmpty()) {
            $annualSessions = [
                ['year' => '2021', 'sessions' => 120], ['year' => '2022', 'sessions' => 340],
                ['year' => '2023', 'sessions' => 410], ['year' => '2024', 'sessions' => 520],
                ['year' => '2025', 'sessions' => 600],
            ];
        }

        // Login Activity by Role
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

        if (empty(array_filter(array_merge(...array_values($loginActivityData['data']))))) {
            $loginActivityData = [
                'days' => [now()->subDays(6)->format('d M'), now()->subDays(5)->format('d M'), now()->subDays(4)->format('d M'), now()->subDays(3)->format('d M'), now()->subDays(2)->format('d M'), now()->subDays(1)->format('d M'), now()->format('d M')],
                'roles' => ['analyst', 'manufacturer', 'retailer', 'supplier', 'vendor'],
                'data' => [
                    [3, 7, 4, 8, 5, 9, 6], [2, 5, 3, 7, 4, 8, 5], [4, 6, 8, 5, 9, 7, 10], [1, 3, 2, 4, 3, 5, 2], [2, 4, 3, 6, 2, 7, 3],
                ]
            ];
        }

        return compact(
            'totalUsers', 'pendingUsers', 'approvedUsers', 'usersByRole',
            'userRegistrationData', 'userRegistrationStatusByDay', 'totalApprovedLast7',
            'totalPendingLast7', 'recentActivities', 'monthlySessions',
            'annualSessions', 'loginActivityData'
        );
    }
}
