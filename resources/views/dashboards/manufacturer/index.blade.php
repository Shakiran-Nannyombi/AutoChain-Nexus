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
                <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 0.2rem;">
                    <i class="fas fa-industry"></i> Manufacturer Dashboard
                </h2>
                <div style="font-size: 1.5rem; color:rgb(6, 76, 6); opacity: 0.8;">Welcome back, {{ Auth::user()->name ?? 'Manufacturer' }}!</div>
            </div>
            <!-- Quick actions can be added here if needed -->
        </div>

        <!-- Stats Grid (reduced to 4 relevant cards) -->
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="stat-card" style="background: #110b7d; color: #fff;">
                <i class="fas fa-shopping-cart"></i>
                <div style="font-size: 2rem; font-weight: bold;">{{ $activeOrders ?? 0 }}</div>
                <div>Active Orders</div>
            </div>
            <div class="stat-card" style="background: #076a09; color: #fff;">
                <i class="fas fa-money-bill"></i>
                <div style="font-size: 2rem; font-weight: bold;">Shs {{ number_format($monthlyRevenue ?? 0, 2) }}</div>
                <div>Monthly Revenue</div>
            </div>
            <div class="stat-card" style="background: #948d06; color: #fff;">
                <i class="fas fa-boxes"></i>
                <div style="font-size: 2rem; font-weight: bold;">{{ $inventoryCount ?? 0 }}</div>
                <div>Inventory</div>
            </div>
            <div class="stat-card" style="background: #7b0581; color: #fff;">
                <i class="fas fa-users"></i>
                <div style="font-size: 2rem; font-weight: bold;">{{ $activeVendors ?? 0 }}</div>
                <div>Active Vendors</div>
            </div>
        </div>
        @if(isset($totalRevenue))
        <div style="margin-bottom: 2rem; font-size: 1.1rem; color: var(--primary); font-weight: 600;">
            Total Revenue from All Vendor Orders: Shs {{ number_format($totalRevenue, 2) }}
        </div>
        @endif

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
            <div class="chart-card layered-card">
                <h4 class="chart-title">Vendor Segmentation</h4>
                <div style="display: flex; flex-direction: column; gap: 0.7rem; width: 100%; max-width: 260px;">
                    <div class="vendor-segment-bar segment-color-0">At Risk (60%)</div>
                    <div class="vendor-segment-bar segment-color-1">High Value (25%)</div>
                    <div class="vendor-segment-bar segment-color-2">Occasional (15%)</div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="table-section">
            <h4 style="color: var(--text); font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem;">Recent Orders</h4>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th style="color: var(--text)">Order ID</th>
                        <th style="color: var(--text)">Vendor</th>
                        <th style="color: var(--text)">Status</th>
                        <th style="color: var(--text)">Date</th>
                        <th style="color: var(--text)">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($recentOrders) && count($recentOrders))
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->vendor_id }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d') }}</td>
                                <td><a href="{{ route('manufacturer.orders.show', $order->id) }}" class="btn btn-sm">View</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="5" style="text-align:center; color:#888;">No recent orders found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Low Stock Products Table -->
        <div class="table-section">
            <h4 style="color: var(--text); font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem;">Low Stock Products</h4>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th style="color: var(--text)">Product</th>
                        <th style="color: var(--text)">Current Stock</th>
                        <th style="color: var(--text)">Reorder</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($lowStockProducts) && count($lowStockProducts))
                        @foreach($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->stock }}</td>
                                <td><span style="color:#888;">Reorder</span></td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="3" style="text-align:center; color:#888;">No low stock products.</td></tr>
                    @endif
                </tbody>
            </table>
            @if(isset($lowStockProducts) && count($lowStockProducts))
                <div style="margin-top: 1.2rem;">
                    <strong>Quick List:</strong>
                    <ul style="margin: 0.5rem 0 0 1.2rem; color: #ef4444; font-weight: 600;">
                        @foreach($lowStockProducts as $product)
                            <li>{{ $product->name }} (Stock: {{ $product->stock }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Quick Links (commented out, add back if needed) -->
        <!--
        <div class="mb-3" style="display: flex; gap: 1rem;">
            <a href="{{ route('manufacturer.analystApplications') }}" class="btn btn-primary">Analyst Applications</a>
            <a href="#" class="btn btn-primary">Reports</a>
            <a href="#" class="btn btn-primary">Settings</a>
            <a href="#" class="btn btn-primary">Support</a>
        </div>
        -->
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sales Trend Chart
            const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
            new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Sales',
                        data: [12000, 15000, 11000, 18000, 17000, 21000],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.12)',
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: { color: '#e5e7eb' },
                            ticks: { color: '#374151', font: { size: 14 } }
                        },
                        x: {
                            grid: { color: '#f3f4f6' },
                            ticks: { color: '#374151', font: { size: 14 } }
                        }
                    }
                }
            });
            // Inventory Health Chart
            const inventoryHealthCtx = document.getElementById('inventoryHealthChart').getContext('2d');
            new Chart(inventoryHealthCtx, {
                type: 'pie',
                data: {
                    labels: ['In Stock', 'Low', 'Out of Stock'],
                    datasets: [{
                        data: [80, 12, 8],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom', labels: { color: '#374151', font: { size: 14 } } } }
                }
            });
            // Vendor Segmentation Bars (improved spacing and color)
            document.querySelectorAll('.vendor-segment-bar').forEach((bar, i) => {
                bar.style.marginBottom = '0.7rem';
                bar.style.display = 'block';
            });
        </script>
    @endpush
@endsection 