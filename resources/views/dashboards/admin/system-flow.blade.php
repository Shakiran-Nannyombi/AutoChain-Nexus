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
                <div class="stat-value">52</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">System Health</div>
                <div class="stat-value">98%</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Active Connections</div>
                <div class="stat-value">156</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-link"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Bottlenecks</div>
                <div class="stat-value" style="color: #F3950D;">1</div>
            </div>
            <div class="stat-icon yellow">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    <!-- Side by side row for System Components and Flow Performance -->
    <div style="display: flex; gap: 2rem;">
        <!-- System Components (Left) -->
        <div class="card-flow" style="flex: 1; min-width: 0;">
            <h2 class="card-title mb-4">System Components</h2>
            <div class="flow-list">
                <div class="flow-item">
                    <div>
                        <div class="font-semibold">Manufacturers</div>
                        <div class="text-sm" style="color: #6c757d;">Connected to: suppliers, vendors</div>
                    </div>
                    <span class="badge badge-green-flow">3 Active</span>
                </div>
                <div class="flow-item">
                    <div>
                        <div class="font-semibold">Suppliers</div>
                        <div class="text-sm" style="color: #6c757d;">Connected to: manufacturers</div>
                    </div>
                    <span class="badge badge-green-flow">12 Active</span>
                </div>
                <div class="flow-item">
                    <div>
                        <div class="font-semibold">Vendors</div>
                        <div class="text-sm" style="color: #6c757d;">Connected to: manufacturers, retailers</div>
                    </div>
                    <span class="badge badge-green-flow">8 Active</span>
                </div>
                <div class="flow-item">
                    <div>
                        <div class="font-semibold">Retailers</div>
                        <div class="text-sm" style="color: #6c757d;">Connected to: vendors, customers</div>
                    </div>
                    <span class="badge badge-green-flow">25 Active</span>
                </div>
                <div class="flow-item">
                    <div>
                        <div class="font-semibold">Analysts</div>
                        <div class="text-sm" style="color: #6c757d;">Connected to: all</div>
                    </div>
                    <span class="badge badge-green-flow">4 Active</span>
                </div>
            </div>
        </div>

        <!-- Flow Performance (Right) -->
        <div class="card-flow" style="flex: 1; min-width: 0;">
            <h2 class="card-title mb-4">Flow Performance</h2>
            <div class="flow-list">
                <div class="flow-item">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 0.75rem;"></i>
                        <span>Raw Materials</span>
                    </div>
                    <span class="badge badge-green-flow">95%</span>
                </div>
                <div class="flow-item">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle" style="color: #d97706; margin-right: 0.75rem;"></i>
                        <span>Manufacturing</span>
                    </div>
                    <div>
                        <span class="badge badge-yellow-flow" style="margin-right: 0.5rem;">78%</span>
                        <span class="badge badge-red-flow">Bottleneck</span>
                    </div>
                </div>
                <div class="flow-item">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 0.75rem;"></i>
                        <span>Quality Control</span>
                    </div>
                    <span class="badge badge-green-flow">92%</span>
                </div>
                <div class="flow-item">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 0.75rem;"></i>
                        <span>Distribution</span>
                    </div>
                    <span class="badge badge-green-flow">88%</span>
                </div>
                <div class="flow-item">
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
@endsection
