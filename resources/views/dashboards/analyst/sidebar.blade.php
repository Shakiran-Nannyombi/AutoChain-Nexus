<a href="{{ route('analyst.dashboard') }}" class="nav-item{{ request()->is('analyst/dashboard*') ? ' active' : '' }}">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="{{ route('analyst.reports') }}" class="nav-item{{ request()->is('analyst/reports') ? ' active' : '' }}">
    <i class="fas fa-chart-bar"></i> Reports
</a>
<a href="{{ route('analyst.reports.store') }}" class="nav-item{{ request()->is('analyst/reports') ? ' active' : '' }}">
    <i class="fas fa-chart-bar"></i> Create Reports
</a>
<a href="{{ route('analyst.sales-analysis') }}" class="nav-item{{ request()->is('analyst/sales-analysis') ? ' active' : '' }}">
    <i class="fas fa-chart-pie"></i> Sales Analysis
</a>
<a href="{{ route('analyst.inventory-analysis') }}" class="nav-item{{ request()->is('analyst/inventory-analysis') ? ' active' : '' }}">
    <i class="fas fa-warehouse"></i> Inventory Analysis
</a>
<a href="{{ route('analyst.trends') }}" class="nav-item{{ request()->is('analyst/trends') ? ' active' : '' }}">
    <i class="fas fa-chart-line"></i> Trends
</a>
{{-- <a href="{{ route('analyst.sales-reports') }}" class="nav-item{{ request()->is('analyst/reports/sales') ? ' active' : '' }}">
    <i class="fas fa-file-alt"></i> Sales Reports
</a>
<a href="{{ route('analyst.inventory-reports') }}" class="nav-item{{ request()->is('analyst/reports/inventory') ? ' active' : '' }}">
    <i class="fas fa-file-alt"></i> Inventory Reports
</a>
<a href="{{ route('analyst.performance-reports') }}" class="nav-item{{ request()->is('analyst/reports/performance') ? ' active' : '' }}">
    <i class="fas fa-file-alt"></i> Performance Reports
</a> --}}
<a href="{{ route('analyst.chat') }}" class="nav-item{{ request()->is('analyst/chat') ? ' active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
<a href="{{ route('analyst.settings') }}" class="nav-item{{ request()->is('analyst/settings') ? ' active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>
