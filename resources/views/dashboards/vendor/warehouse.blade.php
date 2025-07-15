@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card vendor-warehouse-dashboard">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Warehouse Mnagement
    </h2>
    <!-- Stat Cards Row -->
    <div class="warehouse-stat-cards">
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--primary), #0d3a07); color: #fff;">
            <div class="stat-label"><i class="fas fa-wallet"></i> Current Balance</div>
            <div class="stat-value">$134,000</div>
            <div class="stat-desc">Total profit <span class="stat-badge up">+30.00</span></div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--primary-light), #0a440d); color: #fff;">
            <div class="stat-label"><i class="fas fa-coins"></i> Total Income</div>
            <div class="stat-value">$134,000</div>
            <div class="stat-desc">Total income <span class="stat-badge up">+10.02</span></div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--accent), #4e2501); color: #fff;">
            <div class="stat-label"><i class="fas fa-money-bill-wave"></i> Expense</div>
            <div class="stat-value">$90,000</div>
            <div class="stat-desc">Today expense <span class="stat-badge down">+30.00</span></div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--secondary), #b35400); color: #fff;">
            <div class="stat-label"><i class="fas fa-truck"></i> Near by Delivery</div>
            <div class="stat-value">14.05.90</div>
            <div class="stat-desc">Today expected <span class="stat-badge down">+30.00</span></div>
        </div>
    </div>
    <!-- Charts Row -->
    <div class="warehouse-charts-row">
        <div class="warehouse-chart-card">
            <div class="chart-title">Warehouse workload</div>
            <div id="warehousePieChart" style="width: 100%; min-width: 220px; height: 220px;"></div>
        </div>
        <div class="warehouse-chart-card">
            <div class="chart-title">Warehouse indicators</div>
            <div id="warehouseBarChart" style="width: 100%; min-width: 220px; height: 220px;"></div>
        </div>
    </div>
    <!-- Recent Deliveries Table -->
    <div class="warehouse-table-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="chart-title">Recent deliveries</div>
            <a href="#" style="color: var(--primary); font-weight: 600; font-size: 1rem;">See All</a>
        </div>
        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Delivery number</th>
                        <th>Weight</th>
                        <th>Date & Time</th>
                        <th>Driver Info</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="#" style="color: #2563eb; font-weight: 600;">N23389394-12</a></td>
                        <td>5039 KG</td>
                        <td>17 Jul 2022 | 10:00pm</td>
                        <td><img src="/images/profile/driver.jpeg" alt="driver" class="driver-avatar"> Resul Ustaitoglu</td>
                        <td><span class="status-badge completed">Completed</span></td>
                    </tr>
                    <tr>
                        <td><a href="#" style="color: #2563eb; font-weight: 600;">S234293911-15</a></td>
                        <td>4800 KG</td>
                        <td>20 Jul 2022 | 6:15pm</td>
                        <td><img src="/images/profile/driver.jpeg" alt="driver" class="driver-avatar"> Szekeres Dalma</td>
                        <td><span class="status-badge progress">In Progress</span></td>
                    </tr>
                    <tr>
                        <td><a href="#" style="color: #2563eb; font-weight: 600;">M23389345-18</a></td>
                        <td>3590 KG</td>
                        <td>25 Jul 2022 | 12:34pm</td>
                        <td><img src="/images/profile/driver.jpeg" alt="driver" class="driver-avatar"> Varga Boglárka</td>
                        <td><span class="status-badge completed">Completed</span></td>
                    </tr>
                    <tr>
                        <td><a href="#" style="color: #2563eb; font-weight: 600;">S23389890-23</a></td>
                        <td>7800 KG</td>
                        <td>28 Jul 2022 | 3:00am</td>
                        <td><img src="/images/profile/driver.jpeg" alt="driver" class="driver-avatar"> J. Bamber</td>
                        <td><span class="status-badge progress">In Progress</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Capacity Guide & Dead Stocks + Recent Activity Row -->
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 2rem; align-items: flex-start;">
        <!-- Capacity Guide & Dead Stocks (stacked) -->
        <div style="flex: 1 1 320px; min-width: 320px; max-width: 370px; display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Capacity Guide Card -->
            <div style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem 2rem;">
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Capacity Guide</div>
                <div style="font-size: 0.98rem; color: var(--text-light); margin-bottom: 1rem;">Monitor Warehouse Space Efficiency</div>
                <div id="capacityDonut" style="width: 180px; height: 180px; margin: 0 auto 0.5rem auto;"></div>
                <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 0.2rem; margin-top: 0.5rem;">
                    <span style="color: #f7b500; font-weight: 600; font-size: 0.98rem;">10% <span style="color: var(--text-dark); font-weight: 400;">Products are In Warning Zone</span></span>
                    <span style="color: #e3342f; font-weight: 600; font-size: 0.98rem;">8% <span style="color: var(--text-dark); font-weight: 400;">Products are In Risk Zone</span></span>
                </div>
            </div>
            <!-- Dead Stocks Card -->
            <div style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem 2rem; display: flex; flex-direction: column; justify-content: center; align-items: flex-start;">
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Dead Stocks</div>
                <div style="font-size: 2.1rem; font-weight: bold; color: var(--text-dark); margin: 0.5rem 0 0.2rem 0;">25 Items</div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="background: #ffeaea; color: #e3342f; font-weight: 600; border-radius: 8px; padding: 0.2rem 0.7rem; font-size: 1rem;">-10% <i class="fas fa-arrow-down"></i></span>
                    <span style="color: var(--text-light); font-size: 0.98rem;">Since Last month</span>
                </div>
            </div>
        </div>
        <!-- Recent Warehouse Activity -->
        <div class="dashboard-activity-card" style="flex: 2 1 400px; min-width: 320px;">
            <h3 class="dashboard-section-title"><i class="fas fa-history"></i> Recent Warehouse Activity</h3>
            <ul class="activity-list">
                <li><span class="activity-dot success"></span> 5 Nissan Altima received from Nissan Motors <span class="activity-time">1d ago</span></li>
                <li><span class="activity-dot warning"></span> Low stock alert for Honda Civic 2023 <span class="activity-time">2d ago</span></li>
                <li><span class="activity-dot info"></span> Ford Focus 2021 moved to Warehouse A <span class="activity-time">3d ago</span></li>
                <li><span class="activity-dot danger"></span> Hyundai Elantra 2022 critical stock <span class="activity-time">4d ago</span></li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ECharts CDN for Pie and Bar Charts -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pie Chart
    var pieDom = document.getElementById('warehousePieChart');
    if (pieDom) {
        var pieChart = echarts.init(pieDom);
        var pieOption = {
            tooltip: { trigger: 'item' },
            legend: { orient: 'vertical', right: 10, top: 30, textStyle: { color: '#888' } },
            series: [{
                name: 'Workload',
                type: 'pie',
                radius: ['55%', '80%'],
                center: ['40%', '50%'],
                label: { show: true, position: 'center', formatter: '{b}', fontSize: 16, fontWeight: 600, color: '#333' },
                data: [
                    { value: 40, name: 'Central', itemStyle: { color: '#8e44ad' } },
                    { value: 30, name: 'West', itemStyle: { color: '#3498db' } },
                    { value: 20, name: 'Reverse', itemStyle: { color: '#f39c12' } },
                    { value: 10, name: 'Other', itemStyle: { color: '#e67e22' } }
                ]
            }]
        };
        pieChart.setOption(pieOption);
        window.addEventListener('resize', function () { pieChart.resize(); });
    }
    // Bar Chart
    var barDom = document.getElementById('warehouseBarChart');
    if (barDom) {
        var barChart = echarts.init(barDom);
        var barOption = {
            tooltip: { trigger: 'axis' },
            legend: { data: ['Toyota Corolla', 'Honda Civic', 'Ford Focus'], top: 10, textStyle: { color: '#888' } },
            grid: { left: 40, right: 20, top: 40, bottom: 40 },
            xAxis: {
                type: 'category',
                data: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#888' }
            },
            yAxis: {
                type: 'value',
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#888' },
                splitLine: { lineStyle: { color: '#eee' } }
            },
            series: [
                {
                    name: 'Toyota Corolla',
                    type: 'bar',
                    stack: 'total',
                    data: [30, 40, 35, 50, 45, 55, 60, 65, 60, 55, 50, 45],
                    itemStyle: { color: '#16610E' },
                    barWidth: 16
                },
                {
                    name: 'Honda Civic',
                    type: 'bar',
                    stack: 'total',
                    data: [20, 30, 25, 35, 30, 40, 45, 50, 45, 40, 35, 30],
                    itemStyle: { color: '#F97A00' },
                    barWidth: 16
                },
                {
                    name: 'Ford Focus',
                    type: 'bar',
                    stack: 'total',
                    data: [10, 20, 15, 25, 20, 30, 35, 40, 35, 30, 25, 20],
                    itemStyle: { color: '#388e3c' },
                    barWidth: 16
                }
            ]
        };
        barChart.setOption(barOption);
        window.addEventListener('resize', function () { barChart.resize(); });
    }
    // Capacity Guide Donut Chart
    var donutDom = document.getElementById('capacityDonut');
    if (donutDom) {
        var donutChart = echarts.init(donutDom);
        var option = {
            series: [{
                type: 'pie',
                radius: ['70%', '90%'],
                avoidLabelOverlap: false,
                label: { show: false },
                data: [
                    { value: 82, name: 'Safe', itemStyle: { color: '#82d48a' } },
                    { value: 10, name: 'Warning', itemStyle: { color: '#f7b500' } },
                    { value: 8, name: 'Risk', itemStyle: { color: '#e3342f' } }
                ]
            }],
            graphic: [{
                type: 'text',
                left: 'center',
                top: 'center',
                style: {
                    text: '82%\nProduct Are\nSafe',
                    textAlign: 'center',
                    fontSize: 22,
                    fontWeight: 700,
                    fill: '#16610E',
                    lineHeight: 28
                }
            }]
        };
        donutChart.setOption(option);
        window.addEventListener('resize', function () { donutChart.resize(); });
    }
});
</script>
@endpush