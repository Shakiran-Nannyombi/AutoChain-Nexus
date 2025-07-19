@extends('layouts.dashboard')

@section('title', 'Vendor Segmentation')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem; font-weight: bold;">
        <i class="fas fa-project-diagram"></i> Vendor Segmentation Analytics
    </h2>
    <div style="margin-bottom: 1.2rem; color: var(--primary); font-size: 1.08rem;">
        This page provides a comprehensive overview of vendor segmentation within your supply chain. Analyze vendor performance, segment distribution, and top products to make informed decisions, optimize partnerships, and identify high-value or at-risk vendors.
    </div>
    <div style="margin-bottom: 1.5rem; background: #c6e2fe; border-left: 4px solid var(--primary); padding: 1rem 1.5rem; border-radius: 6px; color: #333;">
        <strong>Page Features & Instructions:</strong>
        <ul style="margin-top: 0.7rem; margin-bottom: 0.7rem;">
            <li><strong>Vendor Segmentation Table:</strong> View all vendors, their assigned segment (Platinum, Gold, Silver), total orders, most ordered product, fulfillment rate, and total value. Click <em>Show More</em> to see additional details for each vendor.</li>
            <li><strong>Vendor Segment Distribution (Pie Chart):</strong> Visualizes the proportion of vendors in each segment, helping you quickly identify the distribution of high-value, mid-tier, and at-risk vendors.</li>
            <li><strong>Top 5 Products (Bar Chart):</strong> Displays the most frequently ordered products across all vendors, allowing you to spot trends and popular items.</li>
        </ul>
        <strong>How to Use:</strong>
        <ul style="margin-top: 0.7rem;">
            <li>Use the <strong>table</strong> to compare vendor performance and segment assignment. Click <em>Show More</em> for order frequency and rates.</li>
            <li>Refer to the <strong>pie chart</strong> to assess the overall health of your vendor base and focus on segments needing attention.</li>
            <li>Analyze the <strong>bar chart</strong> to identify top-selling products and align procurement or marketing strategies accordingly.</li>
        </ul>
    </div>
    <!-- Segmentation Bar -->
    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
            <i class="fas fa-industry"></i> Vendor Segment Bar
        </h3>
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
                        <th>Total Value</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendorAnalytics as $i => $vendor)
                        @php $vendorId = isset($vendor['user_id']) ? $vendor['user_id'] : ($vendor['name'] ?? $i); @endphp
                    <tr>
                            <td>{{ $vendor['name'] }}</td>
                            <td>{{ $vendor['segment'] }}</td>
                            <td>{{ $vendor['total_orders'] }}</td>
                            <td>{{ $vendor['most_ordered_product'] }}</td>
                            <td>{{ $vendor['fulfillment_rate'] }}%</td>
                            <td>shs {{ is_numeric(preg_replace('/[^\d.]/', '', $vendor['total_value'])) ? number_format((float)preg_replace('/[^\d.]/', '', $vendor['total_value']), 2) : $vendor['total_value'] }}</td>
                            <td>
                                <button class="show-more-btn" data-vendor="{{ $vendorId }}" style="background: none; border: none; color: var(--primary); cursor: pointer;">Show More</button>
                            </td>
                        </tr>
                        <tr class="vendor-details" id="vendor-details-{{ $vendorId }}" style="display: none; background: #f6f6f6;">
                            <td colspan="8">
                                <div style="padding: 1rem 0;">
                                    <strong>Order Frequency:</strong> {{ $vendor['order_frequency'] > 0 ? $vendor['order_frequency'] . ' / month' : 'N/A' }}<br>
                                    <strong>Cancellation Rate:</strong> {{ $vendor['fulfillment_rate'] == 0 ? '100.00%' : '0.00%' }}<br>
                                    <strong>Fulfillment Rate:</strong> {{ $vendor['fulfillment_rate'] > 0 ? '100.00%' : '0.00%' }}<br>
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
            let csv = 'Vendor,Segment,Total Orders,Most Ordered Product,Fulfillment Rate,Total Value\n';
            @foreach($vendorAnalytics as $vendor)
                @php $vendorId = isset($vendor['user_id']) ? $vendor['user_id'] : ($vendor['name'] ?? 'Vendor #' . $loop->index); @endphp
                csv += `"{{ $vendor['name'] }}","{{ $vendor['segment'] }}",{{ $vendor['total_orders'] }},"{{ $vendor['most_ordered_product'] }}",{{ $vendor['fulfillment_rate'] }},{{ is_numeric(preg_replace('/[^\d.]/', '', $vendor['total_value'])) ? number_format((float)preg_replace('/[^\d.]/', '', $vendor['total_value']), 2) : '0.00' }}\n`;
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
        const runSegmentationBtn = document.getElementById('runSegmentationBtn');
        if (runSegmentationBtn) {
            runSegmentationBtn.addEventListener('click', function() {
                const btn = this;
                btn.disabled = true;
                btn.textContent = 'Running...';
                document.getElementById('segmentationStatus').textContent = '';
                fetch('http://127.0.0.1:8001/segment-vendors', { method: 'POST' })
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
        }
    </script>
@endsection 