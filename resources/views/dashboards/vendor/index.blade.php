@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@php
    $segmentNames = [
        1 => 'At Risk',
        2 => 'High Value Customers',
        3 => 'occasional Customers',
    ];
@endphp

@section('content')
<div class="content-card vendor-dashboard-grid">
    <div class="stat-boxes">
        <div class="stat-box">
            <div class="stat-content">
                    <div>
                    <div class="stat-value">{{ $activeProducts ?? 5 }}</div>
                    <div class="stat-label">Active Car Models</div>
                </div>
                <i class="fas fa-car stat-icon"></i>
            </div>
        </div>
        <div class="stat-box secondary">
            <div class="stat-content">
                    <div>
                    <div class="stat-value">{{ $pendingOrders ?? 3 }}</div>
                    <div class="stat-label">Pending Orders from Retailers</div>
                </div>
                <i class="fas fa-shopping-cart stat-icon"></i>
            </div>
        </div>
        <div class="stat-box primary-light">
            <div class="stat-content">
                    <div>
                    <div class="stat-value">{{ $totalCustomers ?? 7 }}</div>
                    <div class="stat-label">Retailer Customers</div>
                </div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </div>
        <div class="stat-box accent">
            <div class="stat-content">
                    <div>
                    <div class="stat-value">${{ $monthlyRevenue ?? '120,000' }}</div>
                    <div class="stat-label">Monthly Revenue</div>
                </div>
                <i class="fas fa-dollar-sign stat-icon"></i>
            </div>
        </div>
            </div>
    <div class="dashboard-main-row">
        <div class="dashboard-chart-card">
            <h3 class="dashboard-section-title"><i class="fas fa-chart-line"></i> Revenue Report</h3>
            <div id="vendorSalesChart" style="width: 100%; min-width: 320px; height: 260px;"></div>
        </div>
        <div class="dashboard-activity-card">
            <h3 class="dashboard-section-title"><i class="fas fa-bell"></i> Recent Activity</h3>
            <ul class="activity-list">
                @forelse($recentActivities as $activity)
                    <li>
                        <span class="activity-dot success"></span>
                        {{ $activity->activity }}
                        @if($activity->details)
                            <span class="activity-details">{{ $activity->details }}</span>
                        @endif
                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li>No recent activity.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <!-- Customer Segment Analytics Section -->
    <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow); margin-top: 2rem;">
        <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
            <i class="fas fa-users"></i> Customer Segment Distribution
        </h3>
        <div>
            @if(isset($customerSegmentCounts) && count($customerSegmentCounts) > 0)
                <ul style="list-style: none; padding: 0;">
                    @foreach($customerSegmentCounts as $seg)
                        <li style="margin-bottom: 0.5rem;">
                            <strong>{{ $segmentNames[$seg->segment] ?? 'Unsegmented' }}:</strong> {{ $seg->count }} customers
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No segmentation data available.</p>
            @endif
                    </div>
        @if(isset($segmentSummaries) && count($segmentSummaries) > 0)
        <div style="margin-top: 2rem;">
            <h4 style="color: var(--deep-purple);">Segment Summary Table</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="padding: 0.5rem; text-align: left;">Segment</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Total Spent</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Purchases</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Recency (days)</th>
                        <th style="padding: 0.5rem; text-align: left;"># Customers</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($segmentSummaries as $summary)
                    <tr>
                        <td style="padding: 0.5rem;">{{ $segmentNames[$summary->segment] ?? 'Unsegmented' }}</td>
                        <td style="padding: 0.5rem;">${{ number_format($summary->avg_total_spent, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ number_format($summary->avg_purchases, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->avg_recency !== null ? number_format($summary->avg_recency, 1) : 'N/A' }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                </div>
        @endif
                    </div>
    <div class="dashboard-table-card">
        <h3 class="dashboard-section-title"><i class="fas fa-star"></i> Best Selling Cars</h3>
        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car Model</th>
                        <th>Manufacturer</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Units Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bestSellingCars as $car)
                        <tr>
                            <td>#{{ $car['id'] }}</td>
                            <td>{{ $car['car_model'] }}</td>
                            <td>{{ $car['manufacturer'] }}</td>
                            <td>${{ number_format($car['price']) }}</td>
                            <td>{{ $car['stock'] }}</td>
                            <td>{{ $car['units_sold'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No sales data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
@endsection 

@push('scripts')
<!-- ECharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var chartDom = document.getElementById('vendorSalesChart');
    if (chartDom) {
        var myChart = echarts.init(chartDom);
        var option = {
            tooltip: { trigger: 'axis' },
            legend: {
                data: ['Toyota Corolla', 'Honda Civic', 'Ford Focus'],
                top: 10,
                textStyle: { color: 'var(--primary)' }
            },
            grid: { left: 40, right: 20, top: 40, bottom: 40 },
            xAxis: {
                type: 'category',
                data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                axisLine: { lineStyle: { color: 'var(--primary)' } },
                axisLabel: { color: 'var(--primary)' }
            },
            yAxis: {
                type: 'value',
                axisLine: { lineStyle: { color: 'var(--primary)' } },
                axisLabel: { color: 'var(--primary)' },
                splitLine: { lineStyle: { color: '#eee' } }
            },
            series: [
                {
                    name: 'Toyota Corolla',
                    type: 'line',
                    data: [5, 8, 6, 10, 7, 9],
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: { color: '#16610E', width: 3 },
                    itemStyle: { color: '#16610E' },
                    areaStyle: { color: '#82d48a', opacity: 0.15 }
                },
                {
                    name: 'Honda Civic',
                    type: 'line',
                    data: [3, 6, 5, 7, 6, 8],
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: { color: '#F97A00', width: 3 },
                    itemStyle: { color: '#F97A00' },
                    areaStyle: { color: '#FED16A', opacity: 0.15 }
                },
                {
                    name: 'Ford Focus',
                    type: 'line',
                    data: [2, 4, 3, 5, 4, 6],
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: { color: '#388e3c', width: 3 },
                    itemStyle: { color: '#388e3c' },
                    areaStyle: { color: '#82d48a', opacity: 0.10 }
                }
            ]
        };
        myChart.setOption(option);
        window.addEventListener('resize', function () { myChart.resize(); });
    }
});
</script>
@endpush 