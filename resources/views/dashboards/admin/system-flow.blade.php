@extends('layouts.dashboard')

@section('title', 'System Flow Visualization')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin.css'])
    <style>
    .system-components-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .component-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .component-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .component-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #22223b;
    }
    .component-card-desc {
        color: #555;
        font-size: 1rem;
    }
    .badge-green-flow {
        background: #d1fae5;
        color: #059669;
        font-weight: 500;
        border-radius: 16px;
        padding: 0.25em 1em;
        font-size: 0.95em;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .flow-performance-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .perf-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .perf-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.15rem;
        font-weight: 600;
    }
    .perf-title {
        flex: 1;
        color: #22223b;
    }
    .perf-icon.success {
        color: #059669;
        font-size: 1.3em;
    }
    .perf-icon.warning {
        color: #f59e42;
        font-size: 1.3em;
    }
    .badge {
        font-weight: 500;
        border-radius: 16px;
        padding: 0.25em 1em;
        font-size: 0.95em;
        margin-left: 0.5em;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .badge-yellow {
        background: #fef3c7;
        color: #b45309;
    }
    .badge-red {
        background: #fecaca;
        color: #b91c1c;
        margin-left: 0.5em;
    }
    .perf-failures {
        margin-top: 0.5em;
        color: #b91c1c;
        font-size: 0.98em;
    }
    .failure-reason {
        display: inline-block;
        margin-right: 1em;
    }
    </style>
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <div class="content-card">
    <h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-project-diagram"></i> System Flow</h2>

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
                <div class="stat-title" style="font-size: 1.1rem; opacity: 0.95; color: #fff;">Bottlenecks</div>
                <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #fff;">{{ $stats['bottlenecks'] }}</div>
            </div>
            <div class="stat-icon yellow" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-left: 1rem;"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <!-- System Components (Full Width, Card Style) -->
    <div class="card-flow mb-8" style="width: 100%;">
        <h2 class="card-title mb-4">System Components</h2>
        <div class="system-components-list">
            @foreach($componentData as $role => $data)
            <div class="component-card">
                <div class="component-card-header">
                    <span class="component-title">{{ ucfirst($role) }}</span>
                    <span class="badge badge-green-flow">{{ $data['count'] }} Active</span>
                </div>
                <div class="component-card-desc">Connected to: {{ $data['connections'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Flow Performance (Full Width, Card Style) -->
    <div class="card-flow mb-8" style="width: 100%;">
        <h2 class="card-title mb-4">Flow Performance</h2>
        <div class="flow-performance-list">
            @foreach($flowPerformance as $stage => $perf)
            @php
                $util = $perf['utilization'];
                $isBottleneck = ($util < 80) || (!empty($perf['failures']));
                $utilBadgeClass = $isBottleneck ? 'badge-yellow' : 'badge-green-flow';
                $icon = $isBottleneck ? '<span class="perf-icon warning"><i class="fas fa-exclamation-triangle"></i></span>' : '<span class="perf-icon success"><i class="fas fa-check-circle"></i></span>';
            @endphp
            <div class="perf-card">
                <div class="perf-card-header">
                    {!! $icon !!}
                    <span class="perf-title">{{ str_replace('_', ' ', ucfirst($stage)) }}</span>
                    <span class="badge {{ $utilBadgeClass }}">{{ $util }}%</span>
                    @if($isBottleneck)
                        <span class="badge badge-red">Bottleneck</span>
                    @endif
                </div>
                @if(!empty($perf['failures']))
                    <div class="perf-failures">
                        @foreach($perf['failures'] as $reason)
                            <span class="failure-reason">{{ $reason }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
  </div>

<!-- Component Details Modal -->
<div id="componentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="componentModalTitle">Component Details</h3>
            <span class="close" onclick="closeComponentModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="componentModalContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Performance Details Modal -->
<div id="performanceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="performanceModalTitle">Performance Analysis</h3>
            <span class="close" onclick="closePerformanceModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="performanceModalContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- System Health Modal -->
<div id="systemHealthModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>System Health Overview</h3>
            <span class="close" onclick="closeSystemHealthModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="health-overview">
                <div class="health-metric">
                    <h4>Overall System Health: 98%</h4>
                    <div class="health-bar">
                        <div class="health-fill" style="width: 98%; background-color: #28a745;"></div>
                    </div>
                </div>
                <div class="health-breakdown">
                    <h4>Component Health Breakdown</h4>
                    <div class="health-item">
                        <span>Database Performance</span>
                        <span class="health-score good">99%</span>
                    </div>
                    <div class="health-item">
                        <span>API Response Time</span>
                        <span class="health-score good">97%</span>
                    </div>
                    <div class="health-item">
                        <span>Memory Usage</span>
                        <span class="health-score good">95%</span>
                    </div>
                    <div class="health-item">
                        <span>CPU Utilization</span>
                        <span class="health-score good">96%</span>
                    </div>
                </div>
                <div class="health-recommendations">
                    <h4>Recommendations</h4>
                    <ul>
                        <li>System is performing optimally</li>
                        <li>No immediate action required</li>
                        <li>Continue monitoring for trends</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottleneck Analysis Modal -->
<div id="bottleneckModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Bottleneck Analysis</h3>
            <span class="close" onclick="closeBottleneckModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="bottleneck-overview">
                <div class="bottleneck-alert">
                    <i class="fas fa-exclamation-triangle" style="color: #d97706; font-size: 2rem;"></i>
                    <h4>1 Active Bottleneck Detected</h4>
                </div>
                <div class="bottleneck-details">
                    <h4>Manufacturing Process</h4>
                    <p><strong>Issue:</strong> Production line efficiency has dropped to 78%</p>
                    <p><strong>Impact:</strong> Delays in order fulfillment by 2-3 days</p>
                    <p><strong>Root Cause:</strong> Equipment maintenance overdue</p>
                </div>
                <div class="bottleneck-actions">
                    <h4>Recommended Actions</h4>
                    <ul>
                        <li>Schedule immediate equipment maintenance</li>
                        <li>Review production line workflow</li>
                        <li>Consider temporary capacity increase</li>
                        <li>Monitor closely for 48 hours</li>
                    </ul>
                </div>
                <div class="bottleneck-timeline">
                    <h4>Timeline</h4>
                    <div class="timeline-item">
                        <span class="timeline-date">Today</span>
                        <span class="timeline-action">Issue identified</span>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-date">Tomorrow</span>
                        <span class="timeline-action">Maintenance scheduled</span>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-date">Day 3</span>
                        <span class="timeline-action">Expected resolution</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Component Modal Functions
function openComponentModal(component) {
    const modal = document.getElementById('componentModal');
    const title = document.getElementById('componentModalTitle');
    const content = document.getElementById('componentModalContent');
    
    // Use Blade-passed data for active connections
    const activeConnectionsPerRole = @json($activeConnectionsPerRole);
    const componentData = {
        manufacturers: {
            title: 'Manufacturers Overview',
            content: `
                <div class="component-details">
                    <div class="component-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Manufacturers</span>
                            <span class="stat-value">{{ $componentData['manufacturers']['count'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active Connections</span>
                            <span class="stat-value">${activeConnectionsPerRole.manufacturers ?? 0}</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Suppliers, Vendors, Analysts</li>
                        </ul>
                    </div>
                    <div class="component-actions">
                        <a href="{{ route('admin.user-management') }}?role=manufacturer" class="btn-primary">View All Manufacturers</a>
                        <a href="{{ route('admin.user-validation') }}?role=manufacturer" class="btn-secondary">Review Applications</a>
                    </div>
                </div>
            `
        },
        suppliers: {
            title: 'Suppliers Overview',
            content: `
                <div class="component-details">
                    <div class="component-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Suppliers</span>
                            <span class="stat-value">{{ $componentData['suppliers']['count'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active Connections</span>
                            <span class="stat-value">${activeConnectionsPerRole.suppliers ?? 0}</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Manufacturers</li>
                        </ul>
                    </div>
                    <div class="component-actions">
                        <a href="{{ route('admin.user-management') }}?role=supplier" class="btn-primary">View All Suppliers</a>
                        <a href="{{ route('admin.user-validation') }}?role=supplier" class="btn-secondary">Review Applications</a>
                    </div>
                </div>
            `
        },
        vendors: {
            title: 'Vendors Overview',
            content: `
                <div class="component-details">
                    <div class="component-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Vendors</span>
                            <span class="stat-value">{{ $componentData['vendors']['count'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active Connections</span>
                            <span class="stat-value">${activeConnectionsPerRole.vendors ?? 0}</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Manufacturers, Retailers, Analysts</li>
                        </ul>
                    </div>
                    <div class="component-actions">
                        <a href="{{ route('admin.user-management') }}?role=vendor" class="btn-primary">View All Vendors</a>
                        <a href="{{ route('admin.user-validation') }}?role=vendor" class="btn-secondary">Review Applications</a>
                    </div>
                </div>
            `
        },
        retailers: {
            title: 'Retailers Overview',
            content: `
                <div class="component-details">
                    <div class="component-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Retailers</span>
                            <span class="stat-value">{{ $componentData['retailers']['count'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active Connections</span>
                            <span class="stat-value">${activeConnectionsPerRole.retailers ?? 0}</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Vendors, Analysts</li>
                        </ul>
                    </div>
                    <div class="component-actions">
                        <a href="{{ route('admin.user-management') }}?role=retailer" class="btn-primary">View All Retailers</a>
                        <a href="{{ route('admin.user-validation') }}?role=retailer" class="btn-secondary">Review Applications</a>
                    </div>
                </div>
            `
        },
        analysts: {
            title: 'Analysts Overview',
            content: `
                <div class="component-details">
                    <div class="component-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Analysts</span>
                            <span class="stat-value">{{ $componentData['analysts']['count'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active Connections</span>
                            <span class="stat-value">${activeConnectionsPerRole.analysts ?? 0}</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>All Components (Cross-functional access)</li>
                        </ul>
                    </div>
                    <div class="component-actions">
                        <a href="{{ route('admin.user-management') }}?role=analyst" class="btn-primary">View All Analysts</a>
                        <a href="{{ route('admin.user-validation') }}?role=analyst" class="btn-secondary">Review Applications</a>
                    </div>
                </div>
            `
        }
    };
    
    title.textContent = componentData[component].title;
    content.innerHTML = componentData[component].content;
    modal.classList.add('show');
}

function closeComponentModal() {
    document.getElementById('componentModal').classList.remove('show');
}

// Performance Modal Functions
function openPerformanceModal(stage) {
    const modal = document.getElementById('performanceModal');
    const title = document.getElementById('performanceModalTitle');
    const content = document.getElementById('performanceModalContent');
    
    const performanceData = {
        'raw-materials': {
            title: 'Raw Materials Performance',
            content: `
                <div class="performance-details">
                    <div class="performance-chart">
                        <h4>Performance Trend (Last 30 Days)</h4>
                        <div class="chart-placeholder">
                            <div class="chart-bar" style="height: 95%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 92%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 97%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 94%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 96%; background-color: #28a745;"></div>
                        </div>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Current Performance</span>
                            <span class="metric-value good">95%</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Average Response Time</span>
                            <span class="metric-value">2.3s</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Success Rate</span>
                            <span class="metric-value good">98.5%</span>
                        </div>
                    </div>
                </div>
            `
        },
        'manufacturing': {
            title: 'Manufacturing Performance',
            content: `
                <div class="performance-details">
                    <div class="performance-alert">
                        <i class="fas fa-exclamation-triangle" style="color: #d97706; font-size: 1.5rem;"></i>
                        <h4>Performance Issue Detected</h4>
                    </div>
                    <div class="performance-chart">
                        <h4>Performance Trend (Last 30 Days)</h4>
                        <div class="chart-placeholder">
                            <div class="chart-bar" style="height: 85%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 82%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 78%; background-color: #d97706;"></div>
                            <div class="chart-bar" style="height: 75%; background-color: #d97706;"></div>
                            <div class="chart-bar" style="height: 78%; background-color: #d97706;"></div>
                        </div>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Current Performance</span>
                            <span class="metric-value warning">78%</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Average Response Time</span>
                            <span class="metric-value">5.7s</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Success Rate</span>
                            <span class="metric-value warning">85.2%</span>
                        </div>
                    </div>
                    <div class="performance-issues">
                        <h4>Identified Issues</h4>
                        <ul>
                            <li>Equipment maintenance overdue</li>
                            <li>Production line bottlenecks</li>
                            <li>Staff shortage in key areas</li>
                        </ul>
                    </div>
                </div>
            `
        },
        'quality-control': {
            title: 'Quality Control Performance',
            content: `
                <div class="performance-details">
                    <div class="performance-chart">
                        <h4>Performance Trend (Last 30 Days)</h4>
                        <div class="chart-placeholder">
                            <div class="chart-bar" style="height: 92%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 94%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 91%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 93%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 92%; background-color: #28a745;"></div>
                        </div>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Current Performance</span>
                            <span class="metric-value good">92%</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Average Response Time</span>
                            <span class="metric-value">3.1s</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Success Rate</span>
                            <span class="metric-value good">96.8%</span>
                        </div>
                    </div>
                </div>
            `
        },
        'distribution': {
            title: 'Distribution Performance',
            content: `
                <div class="performance-details">
                    <div class="performance-chart">
                        <h4>Performance Trend (Last 30 Days)</h4>
                        <div class="chart-placeholder">
                            <div class="chart-bar" style="height: 88%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 85%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 90%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 87%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 88%; background-color: #28a745;"></div>
                        </div>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Current Performance</span>
                            <span class="metric-value good">88%</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Average Response Time</span>
                            <span class="metric-value">4.2s</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Success Rate</span>
                            <span class="metric-value good">94.1%</span>
                        </div>
                    </div>
                </div>
            `
        },
        'retail': {
            title: 'Retail Performance',
            content: `
                <div class="performance-details">
                    <div class="performance-chart">
                        <h4>Performance Trend (Last 30 Days)</h4>
                        <div class="chart-placeholder">
                            <div class="chart-bar" style="height: 91%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 89%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 93%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 90%; background-color: #28a745;"></div>
                            <div class="chart-bar" style="height: 91%; background-color: #28a745;"></div>
                        </div>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Current Performance</span>
                            <span class="metric-value good">91%</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Average Response Time</span>
                            <span class="metric-value">2.8s</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Success Rate</span>
                            <span class="metric-value good">97.3%</span>
                        </div>
                    </div>
                </div>
            `
        }
    };
    
    title.textContent = performanceData[stage].title;
    content.innerHTML = performanceData[stage].content;
    modal.classList.add('show');
}

function closePerformanceModal() {
    document.getElementById('performanceModal').classList.remove('show');
}

// System Health Modal Functions
function openSystemHealthModal() {
    document.getElementById('systemHealthModal').classList.add('show');
}

function closeSystemHealthModal() {
    document.getElementById('systemHealthModal').classList.remove('show');
}

// Bottleneck Modal Functions
function openBottleneckModal() {
    document.getElementById('bottleneckModal').classList.add('show');
}

function closeBottleneckModal() {
    document.getElementById('bottleneckModal').classList.remove('show');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = [
        'componentModal',
        'performanceModal', 
        'systemHealthModal',
        'bottleneckModal'
    ];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.remove('show');
        }
    });
}
</script>
@endpush
