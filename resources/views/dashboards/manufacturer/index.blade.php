@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manufacturer.css') }}">
@endpush

@php
    $segmentNames = [
        1 => 'At Risk',
        2 => 'High Value Customers',
        3 => 'Occasional Customers',
    ];
@endphp

@section('content')
    <div class="content-card">
        <!-- Header & Quick Actions -->
        <div class="dashboard-header">
            <div>
                <h2 style="color: var(--primary); font-size: 2rem; font-weight: bold; margin-bottom: 0.2rem;">
                    <i class="fas fa-industry"></i> Manufacturer Dashboard
                </h2>
                <div style="font-size: 1.1rem; color: var(--primary); opacity: 0.8;">Welcome back, {{ Auth::user()->name ?? 'Manufacturer' }}!</div>
            </div>
            <div class="quick-actions">
                <a href="#" class="btn">Create Order</a>
                <a href="#" class="btn">Add Product</a>
                <a href="#" class="btn">Contact Analyst</a>
            </div>
        </div>

        <!-- Notifications/Alerts -->
        <div class="notification-banner">
            <i class="fas fa-exclamation-triangle"></i>
            <span>3 products are out of stock! <a href="#" style="color: #fff; text-decoration: underline;">View details</a></span>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div style="font-size: 2rem; font-weight: bold;">{{ $activeOrders ?? 0 }}</div>
                <div>Active Orders</div>
            </div>
            <div class="stat-card accent">
                <div style="font-size: 2rem; font-weight: bold;">{{ $monthlyRevenue ?? '$0' }}</div>
                <div>Monthly Revenue</div>
            </div>
            <div class="stat-card secondary">
                <div style="font-size: 2rem; font-weight: bold;">{{ $inventoryCount ?? 0 }}</div>
                <div>Inventory</div>
            </div>
            <div class="stat-card">
                <div style="font-size: 2rem; font-weight: bold;">{{ $activeVendors ?? 0 }}</div>
                <div>Active Vendors</div>
            </div>
            <div class="stat-card accent">
                <div style="font-size: 2rem; font-weight: bold;">{{ $pendingShipments ?? 0 }}</div>
                <div>Pending Shipments</div>
            </div>
            <div class="stat-card secondary">
                <div style="font-size: 2rem; font-weight: bold;">{{ $lowStockAlerts ?? 0 }}</div>
                <div>Low Stock Alerts</div>
            </div>
            <div class="stat-card">
                <div style="font-size: 2rem; font-weight: bold;">{{ $returnsCount ?? 0 }}</div>
                <div>Returns/Complaints</div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
            <div class="chart-card">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Sales Trend (6 months)</h4>
                <canvas id="salesTrendChart" height="120"></canvas>
            </div>
            <div class="chart-card">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Inventory Health</h4>
                <canvas id="inventoryHealthChart" height="120"></canvas>
            </div>
            <div class="chart-card">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Vendor Segmentation</h4>
                <!-- Example bar, replace with dynamic data -->
                <div class="vendor-segment-bar segment-color-0" style="width: 60%;">At Risk (60%)</div>
                <div class="vendor-segment-bar segment-color-1" style="width: 25%;">High Value (25%)</div>
                <div class="vendor-segment-bar segment-color-2" style="width: 15%;">Occasional (15%)</div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="table-section">
            <h4 style="color: var(--primary); margin-bottom: 1rem;">Recent Orders</h4>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example rows, replace with dynamic data -->
                    <tr>
                        <td>#12345</td>
                        <td>Vendor A</td>
                        <td>Shipped</td>
                        <td>2024-07-17</td>
                        <td><a href="#" class="btn btn-sm">View</a></td>
                    </tr>
                    <tr>
                        <td>#12346</td>
                        <td>Vendor B</td>
                        <td>Pending</td>
                        <td>2024-07-16</td>
                        <td><a href="#" class="btn btn-sm">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Low Stock Products Table -->
        <div class="table-section">
            <h4 style="color: var(--primary); margin-bottom: 1rem;">Low Stock Products</h4>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Current Stock</th>
                        <th>Reorder</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example rows, replace with dynamic data -->
                    <tr>
                        <td>Product X</td>
                        <td>3</td>
                        <td><a href="#" class="btn btn-sm">Reorder</a></td>
                    </tr>
                    <tr>
                        <td>Product Y</td>
                        <td>5</td>
                        <td><a href="#" class="btn btn-sm">Reorder</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Quick Links -->
        <div class="mb-3" style="display: flex; gap: 1rem;">
            <a href="{{ route('manufacturer.analystApplications') }}" class="btn btn-primary">Analyst Applications</a>
            <a href="#" class="btn btn-primary">Reports</a>
            <a href="#" class="btn btn-primary">Settings</a>
            <a href="#" class="btn btn-primary">Support</a>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Placeholder chart data
            const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
            new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Sales',
                        data: [12000, 15000, 11000, 18000, 17000, 21000],
                        borderColor: 'var(--primary)',
                        backgroundColor: 'rgba(34,197,94,0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { plugins: { legend: { display: false } } }
            });
            const inventoryHealthCtx = document.getElementById('inventoryHealthChart').getContext('2d');
            new Chart(inventoryHealthCtx, {
                type: 'pie',
                data: {
                    labels: ['In Stock', 'Low', 'Out of Stock'],
                    datasets: [{
                        data: [80, 12, 8],
                        backgroundColor: ['var(--primary)', 'var(--accent)', 'var(--secondary)']
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });
        </script>
    @endpush
@endsection 