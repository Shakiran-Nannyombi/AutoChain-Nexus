@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<div class="content-card dashboard-page">
    <h2 style="color: var(--text) !important; font-size: 1.8rem; margin-bottom: 1.5rem; font-weight:bold;"><i class="fas fa-tachometer-alt"></i> Overview of Controls</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Quick Stats -->
            <div style="background: #10b981; color: var(--text); padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(37,99,235,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--text);">{{ $pendingUsers ?? 0 }}</div>
                        <div style="opacity: 0.9; color: var(--text);">Pending Approvals</div>
                    </div>
                <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.8; color: var(--text);"></i>
                </div>
            </div>

            <div style="background: #3b82f6; color: var(--text); padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(245,158,66,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--text);">{{ $totalUsers ?? 0 }}</div>
                        <div style="opacity: 0.9; color: var(--text);">Total Users</div>
                    </div>
                <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.8; color: var(--text);"></i>
                </div>
            </div>

            <div style="background: #f59e0b; color: var(--text); padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(34,197,94,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--text);">{{ $activeUsers ?? 0 }}</div>
                        <div style="opacity: 0.9; color: var(--text);">Active Users</div>
                    </div>
                <i class="fas fa-user-check" style="font-size: 2.5rem; opacity: 0.8; color: var(--text);"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--text); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="/admin/user-management" style="display: block; padding: 1rem; background: #10b981; border-radius: 8px; text-decoration: none; color: var(--text); text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-user-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text);"></i>
                    <div>Review Pending Users</div>
                </a>
                <a href="/admin/user-management" style="display: block; padding: 1rem; background: #3b82f6; border-radius: 8px; text-decoration: none; color: var(--text); text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-user-cog" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text);"></i>
                    <div>Manage Users</div>
                </a>
                <a href="/admin/settings" style="display: block; padding: 1rem; background: #f59e0b; border-radius: 8px; text-decoration: none; color: var(--text); text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-cog" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text);"></i>
                    <div>System Settings</div>
                </a>
                <a href="/admin/reports" style="display: block; padding: 1rem; background: #ef4444; border-radius: 8px; text-decoration: none; color: var(--text); text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-chart-bar" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text);"></i>
                    <div>View Reports</div>
                </a>
            </div>
        </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <!-- Recent Activity -->
        <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow);">
            <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-history"></i> Recent Activity
            </h3>
            @if(isset(
$recentActivities) && $recentActivities->count())
                @foreach($recentActivities as $activity)
                    <div style="border-bottom: 1px solid #eee; padding: 0.75rem 0;">
                        <div style="font-weight: 600; color: #0F2C67;">{{ ucfirst($activity->action) }}</div>
                        <div style="color: #555; font-size: 0.97em;">{{ $activity->details }}</div>
                        <div style="color: #888; font-size: 0.9em;">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; color: #6c757d; padding: 2rem 0;">
                    No recent activity to display.
                </div>
            @endif
        </div>
                
        <!-- User Distribution by Role -->
        <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow);">
            <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
                User Distribution by Role
            </h3>
                    <div>
                @if (isset($roleCounts) && !empty($roleCounts))
                    @foreach ($roleCounts as $role => $count)
                        <div class="role-item" style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; @if (!$loop->last) border-bottom: 1px solid #e0e0e0; @endif">
                            <span>{{ $role }}</span>
                            <span style="font-weight: 600;">{{ $count }}</span>
                    </div>
                    @endforeach
                @else
                    <div style="text-align: center; color: #6c757d; padding: 2rem 0;">
                        No user data available.
                    </div>
                @endif
            </div>
        </div>
    </div>

    </div>
@endsection 