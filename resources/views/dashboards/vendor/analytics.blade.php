@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
@php
    if (!isset($allNotifications)) $allNotifications = collect();
@endphp
<style>
.stat-card {
    background: #222 !important;
    color: #fff !important;
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
}
.stat-card .stat-title {
    font-size: 1.1rem;
    color: #e0e0e0;
    font-weight: 600;
}
.stat-card .stat-value {
    font-size: 2.4rem;
    font-weight: bold;
    color: #fff;
}
.stat-card .stat-desc {
    font-size: 0.98rem;
    color: #bbb;
}
.chart-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 2rem;
    width: 100%;
    max-width: 100%;
    margin-bottom: 2.5rem;
    overflow-x: auto;
}
.chart-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.2rem;
}
.chart-container {
    width: 100%;
    min-width: 320px;
    height: 260px;
    max-width: 100%;
}
</style>
<div class="content-card">
    <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--text); letter-spacing: 0.01em;">
        Sales Analytics
    </h2>
    <!-- Stat Cards Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div style="background: #064217; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="font-size: 2.2rem; color: var(--primary); margin-bottom: 0.5rem;"><i class="fas fa-box"></i></div>
            <div style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin-bottom: 0.2rem;">TO BE PACKED</div>
            <div style="font-size: 2.1rem; font-weight: bold; color: var(--primary);">2,340</div>
        </div>
        <div style="background: #053660; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="font-size: 2.2rem; color: #2563eb; margin-bottom: 0.5rem;"><i class="fas fa-truck"></i></div>
            <div style="font-size: 1.1rem; color: #2563eb; font-weight: 600; margin-bottom: 0.2rem;">TO BE SHIPPED</div>
            <div style="font-size: 2.1rem; font-weight: bold; color: #2563eb;">1,120</div>
        </div>
        <div style="background: #553d12; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="font-size: 2.2rem; color: #f59e42; margin-bottom: 0.5rem;"><i class="fas fa-shipping-fast"></i></div>
            <div style="font-size: 1.1rem; color: #f59e42; font-weight: 600; margin-bottom: 0.2rem;">TO BE DELIVERED</div>
            <div style="font-size: 2.1rem; font-weight: bold; color: #f59e42;">980</div>
        </div>
        <div style="background: #4c0909; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
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
    <div>
        <!-- Sales Performance Chart Card -->
        <div class="chart-card">
            <div class="chart-title-row">
                <h3 style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin: 0;">Sales Performance</h3>
                <select id="salesPerformanceRange" style="padding: 0.3rem 1rem; border-radius: 8px; border: 1px solid #eee; font-size: 1rem;">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="last{{ $i }}">Last {{ $i }} Month{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div id="salesPerformanceChart" class="chart-container"></div>
        </div>
        <!-- Sales by City Donut Chart Card -->
        <div class="chart-card">
            <div class="chart-title-row">
                <h3 style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin: 0;">Sales by City</h3>
                <select id="salesByCityRange" style="padding: 0.3rem 1rem; border-radius: 8px; border: 1px solid #eee; font-size: 1rem;">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="last{{ $i }}">Last {{ $i }} Month{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div id="salesByCityChart" class="chart-container"></div>
        </div>
        <!-- Financial Performance Chart Card -->
        <div class="chart-card">
            <div class="chart-title-row">
                <h3 style="font-size: 1.1rem; color: var(--primary); font-weight: 600; margin: 0;">Financial Performance</h3>
                <select id="financialPerformanceRange" style="padding: 0.3rem 1rem; border-radius: 8px; border: 1px solid #eee; font-size: 1rem;">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="last{{ $i }}">Last {{ $i }} Month{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div id="financialPerformanceChart" class="chart-container"></div>
            <div style="display: flex; gap: 1.5rem; margin-top: 1.2rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#38c172;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Cash to Cash Cycle Time</span></div>
                <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#2563eb;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Account Rec Days</span></div>
                <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#f59e42;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Inventory Days</span></div>
                <div style="display: flex; align-items: center; gap: 0.4rem;"><span style="display:inline-block;width:14px;height:14px;background:#e3342f;border-radius:3px;"></span> <span style="font-size:0.98rem;color:var(--text-light);">Accounts Payable Days</span></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<!-- ECharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Helper to generate week labels
    function getWeekLabels(months) {
        const weeks = months * 4; // Approx 4 weeks per month
        let labels = [];
        for (let i = 1; i <= weeks; i++) {
            labels.push('Week ' + i);
        }
        return labels;
    }
    // Helper to generate month labels
    function getMonthLabels(months) {
        const now = new Date();
        let labels = [];
        for (let i = months - 1; i >= 0; i--) {
            const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            labels.push(d.toLocaleString('default', { month: 'short' }));
        }
        return labels;
    }
    // --- Financial Performance Chart ---
    const financialPerformanceDom = document.getElementById('financialPerformanceChart');
    const financialPerformanceRange = document.getElementById('financialPerformanceRange');
    let financialPerformanceChart;
    function getFinancialDemoData(range) {
        const months = parseInt(range.replace('last', ''));
        if (months <= 5) {
            // Weekly data
            const weeks = months * 4;
            return {
                xAxis: getWeekLabels(months),
                c2c: Array.from({length: weeks}, () => Math.floor(Math.random()*40+40)),
                rec: Array.from({length: weeks}, () => Math.floor(Math.random()*20+50)),
                inv: Array.from({length: weeks}, () => Math.floor(Math.random()*20+60)),
                pay: Array.from({length: weeks}, () => Math.floor(Math.random()*20+70)),
            };
        } else {
            // Monthly data
            return {
                xAxis: getMonthLabels(months),
                c2c: Array.from({length: months}, () => Math.floor(Math.random()*40+40)),
                rec: Array.from({length: months}, () => Math.floor(Math.random()*20+50)),
                inv: Array.from({length: months}, () => Math.floor(Math.random()*20+60)),
                pay: Array.from({length: months}, () => Math.floor(Math.random()*20+70)),
            };
        }
    }
    function updateFinancialPerformanceChart(range) {
        if (!financialPerformanceChart) return;
        const data = getFinancialDemoData(range);
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
        // Get initial data
        const initialFinancialData = getFinancialDemoData('last1');
        financialPerformanceChart.setOption({
            tooltip: { trigger: 'axis' },
            grid: { left: 40, right: 20, top: 30, bottom: 40 },
            legend: { show: false },
            xAxis: {
                type: 'category',
                data: initialFinancialData.xAxis,
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
                { name: 'Cash to Cash Cycle Time', type: 'bar', data: initialFinancialData.c2c, itemStyle: { color: '#38c172', borderRadius: [6, 6, 0, 0] }, barWidth: 18 },
                { name: 'Account Rec Days', type: 'bar', data: initialFinancialData.rec, itemStyle: { color: '#2563eb', borderRadius: [6, 6, 0, 0] }, barWidth: 18 },
                { name: 'Inventory Days', type: 'bar', data: initialFinancialData.inv, itemStyle: { color: '#f59e42', borderRadius: [6, 6, 0, 0] }, barWidth: 18 },
                { name: 'Accounts Payable Days', type: 'bar', data: initialFinancialData.pay, itemStyle: { color: '#e3342f', borderRadius: [6, 6, 0, 0] }, barWidth: 18 }
            ]
        });
        if (financialPerformanceRange) {
            financialPerformanceRange.addEventListener('change', function() {
                updateFinancialPerformanceChart(this.value);
            });
        }
    }
    // --- Sales Performance Chart ---
    const salesPerformanceDom = document.getElementById('salesPerformanceChart');
    const salesPerformanceRange = document.getElementById('salesPerformanceRange');
    let salesPerformanceChart;
    function getSalesDemoData(range) {
        const months = parseInt(range.replace('last', ''));
        if (months <= 5) {
            const weeks = months * 4;
            return {
                xAxis: getWeekLabels(months),
                sales: Array.from({length: weeks}, () => Math.floor(Math.random()*40+40)),
                revenue: Array.from({length: weeks}, () => Math.floor(Math.random()*30+30)),
            };
        } else {
            return {
                xAxis: getMonthLabels(months),
                sales: Array.from({length: months}, () => Math.floor(Math.random()*200+200)),
                revenue: Array.from({length: months}, () => Math.floor(Math.random()*150+150)),
            };
        }
    }
    function updateSalesPerformanceChart(range) {
        if (!salesPerformanceChart) return;
        const data = getSalesDemoData(range);
        salesPerformanceChart.setOption({
            xAxis: { data: data.xAxis },
            series: [
                { data: data.sales },
                { data: data.revenue }
            ]
        });
    }
    if (salesPerformanceDom) {
        salesPerformanceChart = echarts.init(salesPerformanceDom);
        const initialSalesData = getSalesDemoData('last1');
        salesPerformanceChart.setOption({
            tooltip: { trigger: 'axis' },
            grid: { left: 40, right: 20, top: 30, bottom: 40 },
            xAxis: {
                type: 'category',
                data: initialSalesData.xAxis,
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
                { name: 'Sales', type: 'bar', data: initialSalesData.sales, itemStyle: { color: '#2563eb', borderRadius: [6, 6, 0, 0] }, barWidth: 28 },
                { name: 'Revenue', type: 'line', data: initialSalesData.revenue, lineStyle: { color: '#38c172', width: 3 }, itemStyle: { color: '#38c172' }, smooth: true, symbol: 'circle', symbolSize: 8 }
            ]
        });
        if (salesPerformanceRange) {
            salesPerformanceRange.addEventListener('change', function() {
                updateSalesPerformanceChart(this.value);
            });
        }
    }
    // --- Sales by City Donut Chart ---
    const salesByCityDom = document.getElementById('salesByCityChart');
    const salesByCityRange = document.getElementById('salesByCityRange');
    let salesByCityChart;
    function getSalesByCityDemoData(range) {
        const months = parseInt(range.replace('last', ''));
        if (months <= 5) {
            // Weekly: more granular, randomize city data for each week
            const weeks = months * 4;
            let data = [];
            for (let i = 1; i <= weeks; i++) {
                data.push({ value: Math.floor(Math.random()*100+100), name: 'City ' + i, itemStyle: { color: ['#2563eb','#f59e42','#38c172','#e3342f'][i%4] } });
            }
            return data;
        } else {
            // Monthly: randomize city data for each month
            let data = [];
            const labels = getMonthLabels(months);
            for (let i = 0; i < months; i++) {
                data.push({ value: Math.floor(Math.random()*500+500), name: labels[i], itemStyle: { color: ['#2563eb','#f59e42','#38c172','#e3342f'][i%4] } });
            }
            return data;
        }
    }
    function updateSalesByCityChart(range) {
        if (!salesByCityChart) return;
        salesByCityChart.setOption({
            series: [{ data: getSalesByCityDemoData(range) }]
        });
    }
    if (salesByCityDom) {
        salesByCityChart = echarts.init(salesByCityDom);
        const initialCityData = getSalesByCityDemoData('last1');
        salesByCityChart.setOption({
            tooltip: { trigger: 'item' },
            legend: { orient: 'vertical', left: 'right', top: 'center', textStyle: { color: '#333' } },
            series: [
                { name: 'Sales', type: 'pie', radius: ['60%', '85%'], avoidLabelOverlap: false, label: { show: false }, data: initialCityData }
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