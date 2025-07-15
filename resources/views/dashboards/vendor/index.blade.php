@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

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
                <li><span class="activity-dot success"></span> Order #CAR-1023 placed by Retailer "AutoMart" <span class="activity-time">1h ago</span></li>
                <li><span class="activity-dot warning"></span> Low stock: Toyota Corolla 2022 <span class="activity-time">2h ago</span></li>
                <li><span class="activity-dot info"></span> New retailer "City Cars" registered <span class="activity-time">3h ago</span></li>
                <li><span class="activity-dot danger"></span> Payment failed for Order #CAR-1019 <span class="activity-time">5h ago</span></li>
                <li><span class="activity-dot success"></span> Purchased 5 Honda Civics from Manufacturer "Honda Motors" <span class="activity-time">6h ago</span></li>
            </ul>
        </div>
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
                    <tr>
                        <td>#1</td>
                        <td>Toyota Corolla 2022</td>
                        <td>Toyota</td>
                        <td>$22,000</td>
                        <td>4</td>
                        <td>18</td>
                    </tr>
                    <tr>
                        <td>#2</td>
                        <td>Honda Civic 2023</td>
                        <td>Honda</td>
                        <td>$24,500</td>
                        <td>2</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>#3</td>
                        <td>Ford Focus 2021</td>
                        <td>Ford</td>
                        <td>$19,800</td>
                        <td>3</td>
                        <td>12</td>
                    </tr>
                    <tr>
                        <td>#4</td>
                        <td>Hyundai Elantra 2022</td>
                        <td>Hyundai</td>
                        <td>$21,200</td>
                        <td>1</td>
                        <td>9</td>
                    </tr>
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