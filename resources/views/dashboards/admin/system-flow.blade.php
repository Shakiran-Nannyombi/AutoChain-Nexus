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
<div class="container mx-auto px-4 py-6">
    <h1 class="page-title" style="margin-bottom: 1.5rem;">System Flow Visualization</h1>

    <!-- Stats Cards -->
    <div class="stats-container mb-8">
        <div class="stat-card">
            <div>
                <div class="stat-title">Active Users</div>
                <div class="stat-value">{{ $stats['activeUsers'] }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card" style="cursor: pointer;" onclick="openSystemHealthModal()">
            <div>
                <div class="stat-title">System Health</div>
                <div class="stat-value">{{ $stats['systemHealth'] }}%</div>
            </div>
            <div class="stat-icon {{ $stats['systemHealth'] > 90 ? 'green' : 'yellow' }}">
                <i class="fas fa-heartbeat"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Active Connections</div>
                <div class="stat-value">{{ $stats['activeConnections'] }}</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-link"></i>
            </div>
        </div>
        <div class="stat-card" style="cursor: pointer;" onclick="openBottleneckModal()">
            <div>
                <div class="stat-value" style="color: #F3950D;">{{ $stats['bottlenecks'] }}</div>
                <div class="stat-title">Bottlenecks</div>
            </div>
            <div class="stat-icon yellow">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    <!-- Side by side row for System Components and Flow Performance -->
    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        <!-- System Components (Left) -->
        <div class="card-flow" style="flex: 1; min-width: 300px;">
            <h2 class="card-title mb-4">System Components</h2>
            <div class="flow-list">
                @foreach($componentData as $role => $data)
                <div class="flow-item clickable" onclick="openComponentModal('{{ $role }}')">
                    <div>
                        <div class="font-semibold">{{ ucfirst($role) }}</div>
                        <div class="text-sm" style="color: #6c757d;">Connected to: {{ $data['connections'] }}</div>
                    </div>
                    <span class="badge badge-green-flow">{{ $data['count'] }} Active</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Flow Performance (Right) -->
        <div class="card-flow" style="flex: 1; min-width: 300px;">
            <h2 class="card-title mb-4">Flow Performance <span class="badge-yellow-flow" style="font-size: 0.7rem; vertical-align: middle;">Demo Data</span></h2>
            <div class="flow-list">
                <div class="flow-item clickable" onclick="openPerformanceModal('raw-materials')">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 0.75rem;"></i>
                        <span>Raw Materials</span>
                    </div>
                    <span class="badge badge-green-flow">95%</span>
                </div>
                <div class="flow-item clickable" onclick="openPerformanceModal('manufacturing')">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle" style="color: #d97706; margin-right: 0.75rem;"></i>
                        <span>Manufacturing</span>
                    </div>
                    <div>
                        <span class="badge badge-yellow-flow" style="margin-right: 0.5rem;">78%</span>
                        <span class="badge badge-red-flow">Bottleneck</span>
                    </div>
                </div>
                <div class="flow-item clickable" onclick="openPerformanceModal('quality-control')">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 0.75rem;"></i>
                        <span>Quality Control</span>
                    </div>
                    <span class="badge badge-green-flow">92%</span>
                </div>
                <div class="flow-item clickable" onclick="openPerformanceModal('distribution')">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 0.75rem;"></i>
                        <span>Distribution</span>
                    </div>
                    <span class="badge badge-green-flow">88%</span>
                </div>
                <div class="flow-item clickable" onclick="openPerformanceModal('retail')">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 0.75rem;"></i>
                        <span>Retail</span>
                    </div>
                    <span class="badge badge-green-flow">91%</span>
                </div>
            </div>
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
    
    const componentData = {
        manufacturers: {
            title: 'Manufacturers Overview',
            content: `
                <div class="component-details">
                    <div class="component-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Manufacturers</span>
                            <span class="stat-value">3</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active</span>
                            <span class="stat-value">3</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending Approval</span>
                            <span class="stat-value">0</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Suppliers (12 active connections)</li>
                            <li>Vendors (8 active connections)</li>
                            <li>Analysts (4 active connections)</li>
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
                            <span class="stat-value">12</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active</span>
                            <span class="stat-value">12</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending Approval</span>
                            <span class="stat-value">0</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Manufacturers (3 active connections)</li>
                            <li>Analysts (4 active connections)</li>
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
                            <span class="stat-value">8</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active</span>
                            <span class="stat-value">8</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending Approval</span>
                            <span class="stat-value">0</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Manufacturers (3 active connections)</li>
                            <li>Retailers (25 active connections)</li>
                            <li>Analysts (4 active connections)</li>
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
                            <span class="stat-value">25</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active</span>
                            <span class="stat-value">25</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending Approval</span>
                            <span class="stat-value">0</span>
                        </div>
                    </div>
                    <div class="component-connections">
                        <h4>Connected Components</h4>
                        <ul>
                            <li>Vendors (8 active connections)</li>
                            <li>Analysts (4 active connections)</li>
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
                            <span class="stat-value">4</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active</span>
                            <span class="stat-value">4</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending Approval</span>
                            <span class="stat-value">0</span>
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
