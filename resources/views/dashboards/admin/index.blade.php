@extends('layouts.dashboard')

@section('sidebar-content')
    <div class="nav-section">
        <div class="nav-section-title">Main</div>
        <a href="/admin/dashboard" class="nav-item active">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="/admin/users" class="nav-item">
            <i class="fas fa-users"></i> User Management
        </a>
        <a href="/admin/pending" class="nav-item">
            <i class="fas fa-clock"></i> Pending Approvals
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">System</div>
        <a href="/admin/settings" class="nav-item">
            <i class="fas fa-cog"></i> System Settings
        </a>
        <a href="/admin/logs" class="nav-item">
            <i class="fas fa-file-alt"></i> System Logs
        </a>
        <a href="/admin/backup" class="nav-item">
            <i class="fas fa-database"></i> Backup & Restore
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Reports</div>
        <a href="/admin/reports/users" class="nav-item">
            <i class="fas fa-chart-bar"></i> User Reports
        </a>
        <a href="/admin/reports/activity" class="nav-item">
            <i class="fas fa-chart-line"></i> Activity Reports
        </a>
    </div>
@endsection

@section('content')
    @php
        $title = 'Admin Dashboard';
    @endphp

    <div class="content-card">
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
                    <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--maroon), var(--orange)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $totalUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Total Users</div>
                    </div>
                    <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--blue), var(--light-cyan)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Users</div>
                    </div>
                    <i class="fas fa-user-check" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="/admin/pending" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-user-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Review Pending Users</div>
                </a>
                
                <a href="/admin/users" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
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

        <!-- Recent Activity -->
        <div>
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-history"></i> Recent Activity
            </h3>
            <div style="background: var(--gray); padding: 1rem; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-user-plus" style="color: var(--orange);"></i>
                    <div>
                        <div style="font-weight: 600;">New user registration</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">John Doe registered as Manufacturer</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">2 hours ago</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-check-circle" style="color: var(--blue);"></i>
                    <div>
                        <div style="font-weight: 600;">User approved</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Jane Smith approved as Supplier</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">4 hours ago</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0;">
                    <i class="fas fa-sign-in-alt" style="color: var(--maroon);"></i>
                    <div>
                        <div style="font-weight: 600;">User login</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Admin user logged in</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">6 hours ago</div>
                </div>
            </div>
        </div>
    </div>
@endsection 