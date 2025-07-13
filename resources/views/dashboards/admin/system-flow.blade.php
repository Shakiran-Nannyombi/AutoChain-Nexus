@extends('layouts.dashboard')

@section('title', 'System Flow Visualization')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <div class="system-flow-page">
  <h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-project-diagram"></i> System Flow</h2>
  <div class="content-card">
    <!-- Stats Cards -->
    <div class="stats-container mb-8" style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #174ea6 0%, #2563eb 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div style="flex: 1; color: #fff;">
                <div class="stat-title" style="font-size: 1.1rem; opacity: 0.95; color: #fff;">Active Users</div>
                <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #fff;">{{ $stats['activeUsers'] }}</div>
            </div>
            <div class="stat-icon green" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-left: 1rem;"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #15803d 0%, #166534 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center; cursor: pointer;" onclick="openSystemHealthModal()">
            <div style="flex: 1; color: #fff;">
                <div class="stat-title" style="font-size: 1.1rem; opacity: 0.95; color: #fff;">System Health</div>
                <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #fff;">{{ $stats['systemHealth'] }}%</div>
            </div>
            <div class="stat-icon green" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-left: 1rem;"><i class="fas fa-heartbeat"></i></div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #eab308 0%, #a16207 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div style="flex: 1; color: #fff;">
                <div class="stat-title" style="font-size: 1.1rem; opacity: 0.95; color: #fff;">Active Connections</div>
                <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #fff;">{{ $stats['activeConnections'] }}</div>
            </div>
            <div class="stat-icon blue" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-left: 1rem;"><i class="fas fa-link"></i></div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center; cursor: pointer;" onclick="openBottleneckModal()">
            <div style="flex: 1; color: #fff;">
                <div class="stat-title" style="font-size: 1.1rem; opacity: 0.95; color: #fff;">Overdue Facility Visits</div>
                <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #fff;">{{ $stats['bottlenecks'] }}</div>
            </div>
            <div class="stat-icon yellow" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-left: 1rem;"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <br><br><br>

    <!-- Two Column Layout for System Components and Flow Performance -->
    <div class="split-cols">
        <!-- System Components Column -->
        <div class="w-1/2">
        <h2 class="card-title mb-4">System Components</h2>
        <div class="system-components-list">
            @foreach($componentData as $role => $data)
                <button class="component-card active-btn" style="width: 100%; margin-bottom: 0.5rem; background: linear-gradient(90deg, #60a5fa 0%, #bae6fd 100%); color: #1e293b; border: none; cursor: pointer; box-shadow: 0 1px 4px rgba(96,165,250,0.06); position: relative;" onclick="showComponentInfo('{{ strtolower($role) }}')" title="Click to view details about {{ ucfirst($role) }}">
                <div class="component-card-header">
                    <span class="component-title">{{ ucfirst($role) }}</span>
                        <span class="badge badge-green-flow" style="background: #4ade80; color: #065f46;">{{ $data['count'] }} Active</span>
                </div>
                    <div class="component-card-desc" style="color: #334155;">Connected to: {{ $data['connections'] }}</div>
                </button>
                @endforeach
            </div>
        </div>
        <div class="split-divider"></div>
        <!-- Flow Performance Column -->
        <div class="w-1/2">
            <h2 class="card-title mb-4">Flow Performance</h2>
            <!-- Legend for bar colors -->
            <div class="mb-4" style="margin-bottom: 1.5rem;">
                <strong>Legend:</strong>
                <div style="display: flex; align-items: center; gap: 1.5rem; margin-top: 0.5rem;">
                    <span style="display: flex; align-items: center;"><span style="display: inline-block; width: 18px; height: 18px; background: #bbf7d0; border-radius: 4px; margin-right: 0.5em; border: 1px solid #a7f3d0;"></span> Normal (Good Performance)</span>
                    <span style="display: flex; align-items: center;"><span style="display: inline-block; width: 18px; height: 18px; background: #fde68a; border-radius: 4px; margin-right: 0.5em; border: 1px solid #fbbf24;"></span> Warning (Potential Bottleneck)</span>
        </div>
    </div>
        <div class="flow-performance-list">
            @foreach($flowPerformance as $stage => $perf)
            @php
                $util = $perf['utilization'];
                $isBottleneck = ($util < 80) || (!empty($perf['failures']));
                $utilBadgeClass = $isBottleneck ? 'badge-yellow' : 'badge-green-flow';
                $icon = $isBottleneck ? '<span class="perf-icon warning"><i class="fas fa-exclamation-triangle"></i></span>' : '<span class="perf-icon success"><i class="fas fa-check-circle"></i></span>';
                    // Uniform color for all bottlenecks (orange/yellow), green for normal
                    $bg = $isBottleneck ? 'linear-gradient(90deg, #fde68a 0%, #fbbf24 100%)' : 'linear-gradient(90deg, #bbf7d0 0%, #f0fdf4 100%)';
                    $color = $isBottleneck ? '#b45309' : '#166534';
                    $stageKey = str_replace(' ', '-', strtolower($stage));
            @endphp
                <button class="perf-card active-btn" style="width: 100%; margin-bottom: 0.5rem; background: {{ $bg }}; color: #1e293b; border: none; cursor: pointer; box-shadow: 0 1px 4px rgba(16,185,129,0.06); position: relative;" onclick="showPerformanceInfo('{{ $stageKey }}')" title="Click to view performance details for {{ str_replace('_', ' ', ucfirst($stage)) }}">
                <div class="perf-card-header">
                    {!! $icon !!}
                        <span class="perf-title" style="color: #1e293b;">{{ str_replace('_', ' ', ucfirst($stage)) }}</span>
                        <span class="badge {{ $utilBadgeClass }}" style="background: #fff; color: {{ $color }};">{{ $util }}%</span>
                    @if($isBottleneck)
                            <span class="badge badge-red" style="background: #f87171; color: #fff;">Bottleneck</span>
                    @endif
                </div>
                @if(!empty($perf['failures']))
                        <div class="perf-failures" style="color: #b91c1c;">
                        @foreach($perf['failures'] as $reason)
                            <span class="failure-reason">{{ $reason }}</span>
                        @endforeach
                    </div>
                @endif
                </button>
            @endforeach
            </div>
        </div>
    </div>
    <!-- Info display section -->
    <div id="activeInfoSection" class="active-info-section" style="display: none;"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Info content for System Components
const componentInfo = {
    manufacturers: `<h3>Manufacturers Overview</h3><p>Manufacturers connect to suppliers, vendors, and analysts. They are responsible for producing goods and managing production lines.</p>`,
    suppliers: `<h3>Suppliers Overview</h3><p>Suppliers provide raw materials and components to manufacturers. They are crucial for the supply chain.</p>`,
    vendors: `<h3>Vendors Overview</h3><p>Vendors distribute products to retailers and analysts. They play a key role in product availability.</p>`,
    retailers: `<h3>Retailers Overview</h3><p>Retailers sell products to end customers and connect with vendors and analysts.</p>`,
    analysts: `<h3>Analysts Overview</h3><p>Analysts monitor and analyze system performance, providing insights to all roles.</p>`
};
// Info content for Flow Performance
const performanceInfo = {
    'raw-materials': `<h3>Raw Materials Performance</h3><p>Raw materials are being supplied efficiently. No major issues detected.</p>`,
    'manufacturing': `<h3>Manufacturing Performance</h3><p>Manufacturing is running at {{ $flowPerformance['manufacturing']['utilization'] }}% utilization. @if(!empty($flowPerformance['manufacturing']['failures']))<br><strong>Issues:</strong> {{ implode(', ', $flowPerformance['manufacturing']['failures']) }}@else<br>No major issues detected.@endif</p>`,
    'quality-control': `<h3>Quality Control Performance</h3><p>Quality control is at {{ $flowPerformance['quality_control']['utilization'] }}% utilization. @if(!empty($flowPerformance['quality_control']['failures']))<br><strong>Issues:</strong> {{ implode(', ', $flowPerformance['quality_control']['failures']) }}@else<br>No major issues detected.@endif</p>`,
    'distribution': `<h3>Distribution Performance</h3><p>Distribution is at {{ $flowPerformance['distribution']['utilization'] }}% utilization. @if(!empty($flowPerformance['distribution']['failures']))<br><strong>Issues:</strong> {{ implode(', ', $flowPerformance['distribution']['failures']) }}@else<br>No major issues detected.@endif</p>`,
    'retail': `<h3>Retail Performance</h3><p>Retail is at {{ $flowPerformance['retail']['utilization'] }}% utilization. @if(!empty($flowPerformance['retail']['failures']))<br><strong>Issues:</strong> {{ implode(', ', $flowPerformance['retail']['failures']) }}@else<br>No major issues detected.@endif</p>`
};
function showComponentInfo(key) {
    const section = document.getElementById('activeInfoSection');
    section.innerHTML = componentInfo[key] || '<p>No details available.</p>';
    section.style.display = 'block';
    window.scrollTo({ top: section.offsetTop - 80, behavior: 'smooth' });
}
function showPerformanceInfo(key) {
    const section = document.getElementById('activeInfoSection');
    section.innerHTML = performanceInfo[key] || '<p>No details available.</p>';
    section.style.display = 'block';
    window.scrollTo({ top: section.offsetTop - 80, behavior: 'smooth' });
}
</script>
@endpush
