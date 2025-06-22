@extends('layouts.dashboard')

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    @php
        $title = 'Admin Dashboard';
    @endphp

        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-shield-alt"></i> Admin Control Panel
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Quick Stats -->
            <div style="background: linear-gradient(135deg, var(--deep-purple), var(--orange)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $pendingUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Pending Approvals</div>
                    </div>
                <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--maroon), var(--orange)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $totalUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Total Users</div>
                    </div>
                <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--blue), var(--light-cyan)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Users</div>
                    </div>
                <i class="fas fa-user-check" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <a href="/admin/user-management" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-user-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Review Pending Users</div>
                </a>
                
                <a href="/admin/user-management" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-user-cog" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Manage Users</div>
                </a>
                
                <a href="/admin/settings" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-cog" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">System Settings</div>
                </a>
                
                <a href="/admin/reports" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-chart-bar" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">View Reports</div>
                </a>
            </div>
        </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <!-- Recent Activity -->
        <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow);">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-history"></i> Recent Activity
            </h3>
            <div style="text-align: center; color: #6c757d; padding: 2rem 0;">
                No recent activity to display.
                    </div>
                </div>
                
        <!-- User Distribution by Role -->
        <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow);">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
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
@endsection 