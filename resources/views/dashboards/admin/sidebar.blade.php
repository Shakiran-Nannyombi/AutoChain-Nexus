<a href="/admin/dashboard" class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Admin Dashboard
</a>
<a href="/admin/user-management" class="nav-item {{ request()->is('admin/user-management') ? 'active' : '' }}">
    <i class="fas fa-users-cog"></i> User Management
</a>
<a href="/admin/user-validation" class="nav-item {{ request()->is('admin/user-validation') ? 'active' : '' }}">
    <i class="fas fa-user-check"></i> User Validation
</a>
<a href="/admin/visit-scheduling" class="nav-item {{ request()->is('admin/visit-scheduling*') ? 'active' : '' }}">
    <i class="fas fa-calendar-check"></i> Visit Scheduling
</a>
<a href="/admin/validation-criteria" class="nav-item {{ request()->is('admin/validation-criteria') ? 'active' : '' }}">
    <i class="fas fa-check-square"></i> Validation Criteria
</a>
<a href="/admin/system-flow" class="nav-item {{ request()->is('admin/system-flow') ? 'active' : '' }}">
    <i class="fas fa-project-diagram"></i> System Flow
</a>
<a href="/admin/analytics" class="nav-item {{ request()->is('admin/analytics') ? 'active' : '' }}">
    <i class="fas fa-chart-line"></i> Analytics
</a>
<a href="/admin/reports" class="nav-item {{ request()->is('admin/reports') ? 'active' : '' }}">
    <i class="fas fa-file-alt"></i> Reports
</a>
<a href="/admin/settings" class="nav-item {{ request()->is('admin/settings') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>
<a href="/admin/backups" class="nav-item {{ request()->is('admin/backups') ? 'active' : '' }}">
    <i class="fas fa-database"></i> Backup
</a> 