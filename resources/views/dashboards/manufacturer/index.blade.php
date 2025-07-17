@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manufacturer.css') }}">
    <style>
        .vendor-segment-bar {
            height: 24px;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        .segment-color-0 { background: var(--primary); color: #fff; }
        .segment-color-1 { background: var(--accent); color: #fff; }
        .segment-color-2 { background: var(--secondary); color: #fff; }
        .segment-label {
            font-weight: bold;
            cursor: pointer;
            position: relative;
        }
        .segment-label[data-tooltip]:hover:after {
            content: attr(data-tooltip);
            position: absolute;
            left: 0;
            top: 120%;
            background: #222;
            color: #fff;
            padding: 4px 10px;
            border-radius: 4px;
            white-space: nowrap;
            font-size: 0.9em;
            z-index: 10;
        }
    </style>
@endpush

@php
    $segmentNames = [
        1 => 'At Risk',
        2 => 'High Value Customers',
        3 => 'occasional Customers',
    ];
@endphp

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem; font-weight: bold; display: flex; align-items: center;">
            <i class="fas fa-industry"></i> Control Panel
        </h2>
        <!-- Main Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, var(--primary), #0d3a07); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeOrders ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Orders</div>
                    </div>
                    <i class="fas fa-shopping-cart" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, var(--accent), #b35400); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $monthlyRevenue ?? '$0' }}</div>
                        <div style="opacity: 0.9;">Monthly Revenue</div>
                    </div>
                    <i class="fas fa-dollar-sign" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, var(--primary-light), #388e3c); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $inventoryCount ?? 0 }}</div>
                        <div style="opacity: 0.9;">Inventory</div>
                    </div>
                    <i class="fas fa-cubes" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
                    </div>
            <div style="background: linear-gradient(135deg, var(--secondary), #b35400); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeVendors ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Vendors</div>
                    </div>
                    <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    <div class="mb-3">
        <a href="{{ route('manufacturer.analystApplications') }}" class="btn btn-primary">Analyst Applications</a>
    </div>
     <!-- Customer Segmentation Analytics Section -->
     <!-- Removed: Customer segmentation section for manufacturer dashboard -->
    
    <!-- Vendor Segmentation Analytics Section -->
    <div class="content-card" style="margin-bottom: 2rem;">
        <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
            <i class="fas fa-industry"></i> Vendor Segment Distribution
        </h3>
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="font-weight: 600;">Segment Bar:</span>
                <div style="flex: 1; height: 18px; background: #e0e0e0; border-radius: 9px; overflow: hidden; display: flex;">
                    @php
                        $totalVendors = array_sum(array_map(function($d) { return isset($d['count']) ? $d['count'] : 0; }, (array)$segmentAnalytics));
                    @endphp
                    @foreach($segmentAnalytics as $segment => $data)
                        @php $count = isset($data['count']) ? $data['count'] : 0; @endphp
                        <div style="width: {{ isset($data['proportion']) ? $data['proportion'] : ($totalVendors > 0 ? ($count / $totalVendors * 100) : 0) }}%; background: {{ $segmentColors[$segment] ?? '#aaa' }}; height: 100%;" title="{{ $segmentLabels[$segment] ?? 'Unsegmented' }} ({{ $count }} vendors)"></div>
                    @endforeach
                </div>
                <span style="font-size: 0.95em; color: #888;">Proportion of vendors in each segment</span>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="table table-compact" style="min-width: 900px; width: 100%; border-collapse: collapse;">
                <thead style="position: sticky; top: 0; background: #f9f9f9;">
                    <tr>
                        <th>Vendor</th>
                        <th>Segment</th>
                        <th>Total Orders</th>
                        <th>Most Ordered Product</th>
                        <th>Fulfillment Rate</th>
                        <th>Recency (days)</th>
                        <th>Total Value</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendorAnalytics as $i => $vendor)
                        @php $vendorId = isset($vendor['user_id']) ? $vendor['user_id'] : ($vendor['name'] ?? $i); @endphp
                    <tr>
                            <td>{{ $vendor['name'] ?? 'Vendor #' . $vendorId }}</td>
                            <td>{{ $segmentLabels[isset($vendor['segment']) ? $vendor['segment'] : null] ?? 'Unsegmented' }}</td>
                            <td>{{ $vendor['total_orders'] }}</td>
                            <td>{{ $vendor['most_ordered_product'] }}</td>
                            <td>{{ $vendor['fulfillment_rate'] ?? 'N/A' }}%</td>
                            <td>{{ $vendor['recency'] ?? 'N/A' }}</td>
                            <td>₦{{ number_format($vendor['total_value'], 2) }}</td>
                            <td>
                                <button class="show-more-btn" data-vendor="{{ $vendorId }}" style="background: none; border: none; color: var(--primary); cursor: pointer;">Show More</button>
                            </td>
                        </tr>
                        <tr class="vendor-details" id="vendor-details-{{ $vendorId }}" style="display: none; background: #f6f6f6;">
                            <td colspan="8">
                                <div style="padding: 1rem 0;">
                                    <strong>Top 3 Products:</strong> {{ isset($vendor['top_3_products']) ? implode(', ', $vendor['top_3_products']) : 'N/A' }}<br>
                                    <strong>Average Order Value:</strong> ₦{{ isset($vendor['average_order_value']) ? number_format($vendor['average_order_value'], 2) : 'N/A' }}<br>
                                    <strong>Order Frequency:</strong> {{ $vendor['order_frequency'] ?? 'N/A' }} / month<br>
                                    <strong>Cancellation Rate:</strong> {{ $vendor['cancellation_rate'] ?? 'N/A' }}%<br>
                                    <strong>First Order:</strong> {{ $vendor['first_order_date'] ?? 'N/A' }}<br>
                                    <strong>Last Order:</strong> {{ $vendor['last_order_date'] ?? 'N/A' }}
                                </div>
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Vendor Analytics Charts Section -->
    <div class="content-card" style="margin-bottom: 2rem;">
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start;">
            <div style="flex: 1 1 300px; min-width: 300px;">
                <h4 style="color: var(--primary);">Vendor Segment Distribution</h4>
                <canvas id="segmentPieChart" height="180"></canvas>
            </div>
            <div style="flex: 2 1 400px; min-width: 350px;">
                <h4 style="color: var(--primary);">Top 5 Products (All Vendors)</h4>
                <canvas id="topProductsBarChart" height="180"></canvas>
                </div>
        </div>
    </div>
    <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-bottom: 0.5rem;">
        <button id="runSegmentationBtn" style="background: var(--accent); color: #fff; border: none; border-radius: 5px; padding: 0.5em 1.2em; font-weight: 600; cursor: pointer;">Run Segmentation</button>
        <button id="exportCsvBtn" style="background: var(--primary); color: #fff; border: none; border-radius: 5px; padding: 0.5em 1.2em; font-weight: 600; cursor: pointer;">Export CSV</button>
        <a href="{{ route('manufacturer.analytics.pdf') }}" target="_blank" style="background: var(--primary); color: #fff; border: none; border-radius: 5px; padding: 0.5em 1.2em; font-weight: 600; text-decoration: none; display: inline-block;">Export PDF</a>
    </div>
    <div id="segmentationStatus" style="margin-bottom: 1rem; color: var(--primary); font-weight: 500;"></div>
    <script>
        document.querySelectorAll('.show-more-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-vendor');
                const details = document.getElementById('vendor-details-' + id);
                if (details.style.display === 'none') {
                    details.style.display = '';
                    this.textContent = 'Show Less';
                } else {
                    details.style.display = 'none';
                    this.textContent = 'Show More';
                }
            });
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.custom-tab-btn');
            const tabContents = document.querySelectorAll('.custom-tab-content');
            tabButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    tabButtons.forEach(function(b) { b.classList.remove('active'); b.style.borderBottom = 'none'; });
                    btn.classList.add('active');
                    btn.style.borderBottom = '3px solid var(--primary)';
                    tabContents.forEach(function(content) { content.style.display = 'none'; });
                    const tabId = btn.getAttribute('data-tab');
                    document.getElementById('tab-' + tabId).style.display = 'block';
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart for Segment Distribution
        const segmentPieCtx = document.getElementById('segmentPieChart').getContext('2d');
        const segmentLabels = @json(array_values($segmentLabels));
        const segmentCounts = @json(array_values(array_map(function($d) { return isset($d['count']) ? $d['count'] : 0; }, $segmentAnalytics)));
        const segmentColors = @json(array_values($segmentColors));
        new Chart(segmentPieCtx, {
            type: 'pie',
            data: {
                labels: segmentLabels,
                datasets: [{
                    data: segmentCounts,
                    backgroundColor: segmentColors,
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom' } },
                responsive: true,
            }
        });
        // Bar Chart for Top 5 Products
        const topProducts = @json(array_slice($topProducts, 0, 5));
        const topProductNames = Object.keys(topProducts);
        const topProductCounts = Object.values(topProducts);
        const topProductColors = ['#4CAF50', '#2196F3', '#FFC107', '#FF5722', '#9C27B0'];
        const topProductsBarCtx = document.getElementById('topProductsBarChart').getContext('2d');
        new Chart(topProductsBarCtx, {
            type: 'bar',
            data: {
                labels: topProductNames,
                datasets: [{
                    label: 'Orders',
                    data: topProductCounts,
                    backgroundColor: topProductColors,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
        // CSV Export Button
        document.getElementById('exportCsvBtn').addEventListener('click', function() {
            let csv = 'Vendor,Segment,Total Orders,Most Ordered Product,Fulfillment Rate,Recency (days),Total Value\n';
            @foreach($vendorAnalytics as $vendor)
                @php $vendorId = isset($vendor['user_id']) ? $vendor['user_id'] : ($vendor['name'] ?? 'Vendor #' . $loop->index); @endphp
                csv += `"{{ $vendor['name'] ?? 'Vendor #' . $vendorId }}","{{ $segmentLabels[isset($vendor['segment']) ? $vendor['segment'] : null] ?? 'Unsegmented' }}",{{ $vendor['total_orders'] }},"{{ $vendor['most_ordered_product'] }}",{{ $vendor['fulfillment_rate'] ?? 'N/A' }},{{ $vendor['recency'] ?? 'N/A' }},{{ number_format($vendor['total_value'], 2) }}\n`;
            @endforeach
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'vendor_analytics.csv';
            a.click();
            URL.revokeObjectURL(url);
        });
    </script>
    <script>
        document.getElementById('runSegmentationBtn').addEventListener('click', function() {
            const btn = this;
            btn.disabled = true;
            btn.textContent = 'Running...';
            document.getElementById('segmentationStatus').textContent = '';
            fetch('/api/segment-vendors', { method: 'POST' })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('segmentationStatus').textContent = 'Segmentation complete! Reloading...';
                        setTimeout(() => window.location.reload(), 1200);
                    } else {
                        document.getElementById('segmentationStatus').textContent = 'Segmentation failed: ' + (data.error || 'Unknown error');
                        btn.disabled = false;
                        btn.textContent = 'Run Segmentation';
                    }
                })
                .catch(err => {
                    document.getElementById('segmentationStatus').textContent = 'Segmentation failed: ' + err;
                    btn.disabled = false;
                    btn.textContent = 'Run Segmentation';
            });
        });
    </script>
@endsection 