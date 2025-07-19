<a href="{{ route('analyst.dashboard') }}" class="nav-item{{ request()->is('analyst/dashboard*') ? ' active' : '' }}">
    <i class="fas fa-home"></i> Control Panel
</a>
<a href="{{ route('analyst.reports.store') }}" class="nav-item{{ request()->is('analyst/reports') ? ' active' : '' }}">
    <i class="fas fa-file-upload"></i> Create Reports
</a>
<a href="{{ route('analyst.reports') }}" class="nav-item{{ request()->is('analyst/reports') ? ' active' : '' }}">
    <i class="fas fa-file"></i> Reports
</a>
<a href="{{ route('analyst.analytics') }}" class="nav-item @if(request()->routeIs('analyst.analytics')) active @endif">
    <i class="fas fa-chart-line"></i> Analytics
</a>
<a href="{{ route('analyst.forecasting') }}" class="nav-item @if(request()->routeIs('analyst.forecasting')) active @endif">
    <i class="fas fa-magic"></i> Forecasting
</a>
<a href="{{ route('analyst.trends') }}" class="nav-item{{ request()->is('analyst/trends') ? ' active' : '' }}">
    <i class="fas fa-chart-line"></i> Trends
</a>
<a href="{{ route('analyst.chat') }}" class="nav-item{{ request()->is('analyst/chat') ? ' active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
<a href="{{ route('analyst.settings') }}" class="nav-item{{ request()->is('analyst/settings') ? ' active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>
