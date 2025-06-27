<a href="/manufacturer/dashboard" class="nav-item {{ request()->is('manufacturer/dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Control Center
</a>
<a href="/manufacturer/production-lines" class="nav-item {{ request()->is('manufacturer/production-lines*') ? 'active' : '' }}">
    <i class="fas fa-industry"></i> Production Lines
</a>
{{-- <a href="/manufacturer/machine-health" class="nav-item {{ request()->is('manufacturer/machine-health*') ? 'active' : '' }}">
    <i class="fas fa-heartbeat"></i> Machine Health
</a> --}}
<a href="/manufacturer/quality-control" class="nav-item {{ request()->is('manufacturer/quality-control*') ? 'active' : '' }}">
    <i class="fas fa-shield-alt"></i> Quality Control
</a>
{{-- <a href="/manufacturer/maintenance" class="nav-item {{ request()->is('manufacturer/maintenance*') ? 'active' : '' }}">
    <i class="fas fa-tools"></i> Maintenance
</a> --}}
<a href="/manufacturer/inventory-status" class="nav-item {{ request()->is('manufacturer/inventory-status*') ? 'active' : '' }}">
    <i class="fas fa-cubes"></i> Inventory Status
</a>
<a href="/manufacturer/scheduling" class="nav-item {{ request()->is('manufacturer/scheduling*') ? 'active' : '' }}">
    <i class="fas fa-clock"></i> Scheduling
</a>
{{-- <a href="/manufacturer/checklists" class="nav-item {{ request()->is('manufacturer/checklists*') ? 'active' : '' }}">
    <i class="fas fa-tasks"></i> Checklists
</a> --}}
{{-- <a href="/manufacturer/material-receipt" class="nav-item {{ request()->is('manufacturer/material-receipt*') ? 'active' : '' }}">
    <i class="fas fa-truck-loading"></i> Material Receipt
</a> --}}
<a href="/manufacturer/workflow" class="nav-item {{ request()->is('manufacturer/workflow*') ? 'active' : '' }}">
    <i class="fas fa-project-diagram"></i> Workflow
</a>
<a href="/manufacturer/production-analytics" class="nav-item {{ request()->is('manufacturer/production-analytics*') ? 'active' : '' }}">
    <i class="fas fa-chart-bar"></i> Production Analytics
</a>
<a href="/manufacturer/production-reports" class="nav-item {{ request()->is('manufacturer/production-reports*') ? 'active' : '' }}">
    <i class="fas fa-file-alt"></i> Production Reports
</a>
<a href="/manufacturer/demand-prediction" class="nav-item {{ request()->is('manufacturer/demand-prediction*') ? 'active' : '' }}">
    <i class="fas fa-chart-line"></i> Demand Prediction
</a>
<a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
<a href="/manufacturer/settings" class="nav-item {{ request()->is('manufacturer/settings*') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>
