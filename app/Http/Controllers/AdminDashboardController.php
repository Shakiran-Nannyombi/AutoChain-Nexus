<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\DB; // Import DB facade for sessions
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Fetch all users for the user management table, excluding the current admin
        $users = User::where('id', '!=', Auth::id())->get();

        // Fetch actual total users from the database
        $totalUsers = User::count();

        // Fetch active vendors (assuming 'vendor' is a role in the users table)
        $activeVendors = User::where('role', 'vendor')->count();

        // Fetch user distribution by role
        $userDistributionByRole = User::select('role', DB::raw('count(*) as count'))
                                        ->groupBy('role')
                                        ->pluck('count', 'role')
                                        ->all();

        // Ensure all required roles are present, even if count is 0
        $roles = ['supplier', 'manufacturer', 'distributor', 'retailer', 'vendor'];
        $formattedUserDistribution = [];
        foreach ($roles as $role) {
            $formattedUserDistribution[ucfirst($role)] = $userDistributionByRole[$role] ?? 0;
        }

        // Fetch pending users (excluding the current admin)
        $pendingUsers = User::where('status', 'pending')
            ->where('id', '!=', Auth::id())
            ->get();

        // Fetch recent activity logs
        $recentActivities = ActivityLog::with(['user', 'targetUser'])
            ->latest()
            ->take(5)
            ->get();

        // Dummy data for now (these usually require system-level integrations)
        $systemHealth = 'Healthy';
        $storageUsage = '45%';

        // Fetch active sessions if using database session driver
        // Assuming 'sessions' table exists and is used by Laravel for sessions
        // You might need to adjust this based on your session driver configuration
        $activeSessions = DB::table('sessions')->count();

        return view('pages.admin-dashboard', compact(
            'users',
            'totalUsers',
            'systemHealth',
            'storageUsage',
            'activeSessions',
            'pendingUsers',
            'recentActivities',
            'activeVendors',
            'formattedUserDistribution'
        ));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        // Log the activity
        ActivityLog::create([
            'action' => 'user_approved',
            'description' => "User {$user->name} was approved",
            'user_id' => Auth::id(),
            'target_user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'User approved successfully');
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        // Log the activity
        ActivityLog::create([
            'action' => 'user_rejected',
            'description' => "User {$user->name} was rejected",
            'user_id' => Auth::id(),
            'target_user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'User rejected successfully');
    }
}
