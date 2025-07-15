@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
@php
    if (!isset($allNotifications)) $allNotifications = collect();
@endphp
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Sales Analytics
    </h2>
    <!-- Stat Cards Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div style="background: #e6f4ea; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="font-size: 2.2rem; color: var(--primary); margin-bottom: 0.5rem;"><i class="fas fa-box"></i></div>
            <div style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin-bottom: 0.2rem;">TO BE PACKED</div>
            <div style="font-size: 2.1rem; font-weight: bold; color: var(--primary);">2,340</div>
        </div>
        <div style="background: #eaf3fb; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="font-size: 2.2rem; color: #2563eb; margin-bottom: 0.5rem;"><i class="fas fa-truck"></i></div>
            <div style="font-size: 1.1rem; color: #2563eb; font-weight: 600; margin-bottom: 0.2rem;">TO BE SHIPPED</div>
            <div style="font-size: 2.1rem; font-weight: bold; color: #2563eb;">1,120</div>
        </div>
        <div style="background: #fff6e5; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="font-size: 2.2rem; color: #f59e42; margin-bottom: 0.5rem;"><i class="fas fa-shipping-fast"></i></div>
            <div style="font-size: 1.1rem; color: #f59e42; font-weight: 600; margin-bottom: 0.2rem;">TO BE DELIVERED</div>
            <div style="font-size: 2.1rem; font-weight: bold; color: #f59e42;">980</div>
        </div>
        <div style="background: #fbeaea; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="font-size: 2.2rem; color: #e3342f; margin-bottom: 0.5rem;"><i class="fas fa-file-invoice"></i></div>
            <div style="font-size: 1.1rem; color: #e3342f; font-weight: 600; margin-bottom: 0.2rem;">TO BE INVOICED</div>
            <div style="font-size: 2.1rem; font-weight: bold; color: #e3342f;">430</div>
        </div>
    </div>
    <!-- Top Selling Items Section -->
    <div style="margin-bottom: 2.5rem;">
        <div style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem;">Top Selling Items</div>
        <div style="display: flex; gap: 1.2rem; overflow-x: auto; padding-bottom: 0.5rem;">
            @foreach($topSellingItems as $item)
                <div style="min-width: 180px; background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1rem; display: flex; flex-direction: column; align-items: center;">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px; margin-bottom: 0.7rem;">
                    <div style="font-weight: 700; color: var(--primary); font-size: 1.05rem; margin-bottom: 0.2rem; text-align: center;">{{ $item['name'] }}</div>
                    <div style="color: var(--text-light); font-size: 0.98rem;">{{ $item['quantity'] }} pcs</div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Modern Chart Cards Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
        <!-- Sales Performance Chart Card -->
        <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
                <h3 style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin: 0;">Sales Performance</h3>
                <select id="salesPerformanceRange" style="padding: 0.3rem 1rem; border-radius: 8px; border: 1px solid #eee; font-size: 1rem;">
                    <option value="this">This Month</option>
                    <option value="last6">Last 6 Months</option>
                </select>
            </div>
            <div id="salesPerformanceChart" style="width: 100%; min-width: 320px; height: 260px;"></div>
        </div>
        <!-- Sales by City Donut Chart Card -->
        <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
                <h3 style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin: 0;">Sales by City</h3>
                <select id="salesByCityRange" style="padding: 0.3rem 1rem; border-radius: 8px; border: 1px solid #eee; font-size: 1rem;">
                    <option value="this">This Month</option>
                    <option value="last6">Last 6 Months</option>
                </select>
            </div>
            <div id="salesByCityChart" style="width: 100%; min-width: 220px; height: 260px;"></div>
        </div>
    </div>
</div>
    <!-- Financial Performance Chart Card -->
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; width: 100%; margin-bottom: 2.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
            <h3 style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin: 0;">Financial Performance</h3>
            <select id="financialPerformanceRange" style="padding: 0.3rem 1rem; border-radius: 8px; border: 1px solid #eee; font-size: 1rem;">
                <option value="this">This Month</option>
                <option value="last6">Last 6 Months</option>
            </select>
        </div>
        <div id="financialPerformanceChart" style="width: 100%; min-width: 320px; height: 260px;"></div>
        <div style="display: flex; gap: 1.5rem; margin-top: 1.2rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#38c172;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Cash to Cash Cycle Time</span></div>
            <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#2563eb;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Account Rec Days</span></div>
            <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#f59e42;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Inventory Days</span></div>
            <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#e3342f;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Accounts Payable Days</span></div>
        </div>
    </div>
@endsection
@push('scripts')
<!-- ECharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Financial Performance Grouped Bar Chart (Interactive)
    const financialPerformanceDom = document.getElementById('financialPerformanceChart');
    const financialPerformanceRange = document.getElementById('financialPerformanceRange');
    let financialPerformanceChart;
    const financialPerformanceData = {
        'this': {
            xAxis: ['Jul'],
            c2c: [45],
            rec: [60],
            inv: [70],
            pay: [75]
        },
        'last6': {
            xAxis: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            c2c: [38, 42, 35, 48, 44, 45],
            rec: [62, 59, 57, 61, 60, 60],
            inv: [68, 70, 66, 69, 71, 70],
            pay: [72, 74, 73, 75, 76, 75]
        }
    };
    function updateFinancialPerformanceChart(range) {
        if (!financialPerformanceChart) return;
        const data = financialPerformanceData[range] || financialPerformanceData['this'];
        financialPerformanceChart.setOption({
            xAxis: { data: data.xAxis },
            series: [
                { data: data.c2c },
                { data: data.rec },
                { data: data.inv },
                { data: data.pay }
            ]
        });
    }
    if (financialPerformanceDom) {
        financialPerformanceChart = echarts.init(financialPerformanceDom);
        financialPerformanceChart.setOption({
            tooltip: { trigger: 'axis' },
            grid: { left: 40, right: 20, top: 30, bottom: 40 },
            legend: {
                show: false
            },
            xAxis: {
                type: 'category',
                data: financialPerformanceData['this'].xAxis,
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#333' }
            },
            yAxis: {
                type: 'value',
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#333' },
                splitLine: { lineStyle: { color: '#f3f4f6' } }
            },
            series: [
                {
                    name: 'Cash to Cash Cycle Time',
                    type: 'bar',
                    data: financialPerformanceData['this'].c2c,
                    itemStyle: { color: '#38c172', borderRadius: [6, 6, 0, 0] },
                    barWidth: 18
                },
                {
                    name: 'Account Rec Days',
                    type: 'bar',
                    data: financialPerformanceData['this'].rec,
                    itemStyle: { color: '#2563eb', borderRadius: [6, 6, 0, 0] },
                    barWidth: 18
                },
                {
                    name: 'Inventory Days',
                    type: 'bar',
                    data: financialPerformanceData['this'].inv,
                    itemStyle: { color: '#f59e42', borderRadius: [6, 6, 0, 0] },
                    barWidth: 18
                },
                {
                    name: 'Accounts Payable Days',
                    type: 'bar',
                    data: financialPerformanceData['this'].pay,
                    itemStyle: { color: '#e3342f', borderRadius: [6, 6, 0, 0] },
                    barWidth: 18
                }
            ]
        });
        if (financialPerformanceRange) {
            financialPerformanceRange.addEventListener('change', function() {
                updateFinancialPerformanceChart(this.value);
            });
        }
    }
    // Sales Performance Bar/Line Chart (Interactive)
    const salesPerformanceDom = document.getElementById('salesPerformanceChart');
    const salesPerformanceRange = document.getElementById('salesPerformanceRange');
    let salesPerformanceChart;
    const salesPerformanceData = {
        'this': {
            xAxis: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            sales: [40, 60, 55, 70, 65, 80],
            revenue: [30, 50, 45, 60, 55, 70]
        },
        'last6': {
            xAxis: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            sales: [220, 180, 210, 190, 230, 250],
            revenue: [180, 160, 170, 175, 200, 220]
        }
    };
    function updateSalesPerformanceChart(range) {
        if (!salesPerformanceChart) return;
        const data = salesPerformanceData[range] || salesPerformanceData['this'];
        salesPerformanceChart.setOption({
            xAxis: {
                data: data.xAxis
            },
            series: [
                { data: data.sales },
                { data: data.revenue }
            ]
        });
    }
    if (salesPerformanceDom) {
        salesPerformanceChart = echarts.init(salesPerformanceDom);
        salesPerformanceChart.setOption({
            tooltip: { trigger: 'axis' },
            grid: { left: 40, right: 20, top: 30, bottom: 40 },
            xAxis: {
                type: 'category',
                data: salesPerformanceData['this'].xAxis,
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#333' }
            },
            yAxis: {
                type: 'value',
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#333' },
                splitLine: { lineStyle: { color: '#f3f4f6' } }
            },
            series: [
                {
                    name: 'Sales',
                    type: 'bar',
                    data: salesPerformanceData['this'].sales,
                    itemStyle: { color: '#2563eb', borderRadius: [6, 6, 0, 0] },
                    barWidth: 28
                },
                {
                    name: 'Revenue',
                    type: 'line',
                    data: salesPerformanceData['this'].revenue,
                    lineStyle: { color: '#38c172', width: 3 },
                    itemStyle: { color: '#38c172' },
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8
                }
            ]
        });
        if (salesPerformanceRange) {
            salesPerformanceRange.addEventListener('change', function() {
                updateSalesPerformanceChart(this.value);
            });
        }
    }

    // Sales by City Donut Chart (Interactive)
    const salesByCityDom = document.getElementById('salesByCityChart');
    const salesByCityRange = document.getElementById('salesByCityRange');
    let salesByCityChart;
    const salesByCityData = {
        'this': [
            { value: 1048, name: 'Amsterdam', itemStyle: { color: '#2563eb' } },
            { value: 735, name: 'New York', itemStyle: { color: '#f59e42' } },
            { value: 580, name: 'Hudson', itemStyle: { color: '#38c172' } },
            { value: 484, name: 'Canandaigua', itemStyle: { color: '#e3342f' } }
        ],
        'last6': [
            { value: 9000, name: 'Amsterdam', itemStyle: { color: '#2563eb' } },
            { value: 1200, name: 'New York', itemStyle: { color: '#f59e42' } },
            { value: 400, name: 'Hudson', itemStyle: { color: '#38c172' } },
            { value: 200, name: 'Canandaigua', itemStyle: { color: '#e3342f' } }
        ]
    };
    function updateSalesByCityChart(range) {
        if (!salesByCityChart) return;
        salesByCityChart.setOption({
            series: [{ data: salesByCityData[range] || salesByCityData['this'] }]
        });
    }
    if (salesByCityDom) {
        salesByCityChart = echarts.init(salesByCityDom);
        salesByCityChart.setOption({
            tooltip: { trigger: 'item' },
            legend: { orient: 'vertical', left: 'right', top: 'center', textStyle: { color: '#333' } },
            series: [
                {
                    name: 'Sales',
                    type: 'pie',
                    radius: ['60%', '85%'],
                    avoidLabelOverlap: false,
                    label: { show: false },
                    data: salesByCityData['this']
                }
            ]
        });
        if (salesByCityRange) {
            salesByCityRange.addEventListener('change', function() {
                updateSalesByCityChart(this.value);
            });
        }
    }
});
</script>
@endpush