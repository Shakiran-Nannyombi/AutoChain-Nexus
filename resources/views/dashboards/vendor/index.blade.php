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
    $newOrdersCount = isset($realTimeOrders) ? $realTimeOrders->where('status', 'new')->count() : 0;
@endphp

@section('content')
<div class="content-card vendor-dashboard-grid" style="background: #fafbfc; box-shadow: none;">
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--primary); letter-spacing: 0.01em;">Welcome back, {{ Auth::user()->name ?? 'Vendor' }}.</h1>
    <div style="font-size: 1.1rem; color: #555; margin-bottom: 2.2rem;">Here's what's happening with your business today.</div>
    <!-- Stat Cards -->
    <div style="display: flex; gap: 1.5rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
        <div style="flex:1; min-width: 220px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.5rem 1.2rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #2563eb;">
            <div style="font-size: 1.7rem; font-weight: 700; color: #222;">{{ $activeProducts ?? 0 }}</div>
            <div style="color: #555; font-size: 1.05rem; margin-top: 0.2rem;">Active Car Models</div>
        </div>
        <div style="flex:1; min-width: 220px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.5rem 1.2rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #f59e0b;">
            <div style="font-size: 1.7rem; font-weight: 700; color: #222;">{{ $pendingOrders ?? 0 }}</div>
            <div style="color: #555; font-size: 1.05rem; margin-top: 0.2rem;">Pending Orders</div>
        </div>
        <div style="flex:1; min-width: 220px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.5rem 1.2rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #10b981;">
            <div style="font-size: 1.7rem; font-weight: 700; color: #222;">{{ $totalCustomers ?? 0 }}</div>
            <div style="color: #555; font-size: 1.05rem; margin-top: 0.2rem;">Retailer Customers</div>
        </div>
        <div style="flex:1; min-width: 220px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.5rem 1.2rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #a21caf;">
            <div style="font-size: 1.7rem; font-weight: 700; color: #222;">Shs {{ number_format($monthlyRevenue ?? 0, 2) }}</div>
            <div style="color: #555; font-size: 1.05rem; margin-top: 0.2rem;">Monthly Revenue</div>
        </div>
    </div>
    <!-- Real-time Order Processing -->
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 2rem 1.5rem; margin-bottom: 2.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
            <div style="font-size: 1.35rem; font-weight: 700; color: #222; display: flex; align-items: center;">
             Order Processing
                @if($newOrdersCount > 0)
                    <span style="background: #ef4444; color: #fff; font-size: 0.95rem; font-weight: 600; border-radius: 12px; padding: 0.2rem 0.8rem; margin-left: 1rem;">{{ $newOrdersCount }} New</span>
                @endif
            </div>
            <div style="display: flex; align-items: center; gap: 0.7rem;">
                <input type="text" placeholder="Search orders..." style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.5rem 1rem; font-size: 1rem; min-width: 220px;">
                <button style="background: #f3f4f6; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 1.1rem; cursor: pointer;"><i class="fas fa-filter"></i></button>
            </div>
        </div>
        <table style="width: 100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background: #f8fafc; color: #222; font-size: 1.05rem;">
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Order ID</th>
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Retailer</th>
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Model</th>
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Qty</th>
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Value</th>
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Status</th>
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Time</th>
                    <th style="padding: 0.8rem 0.5rem; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($realTimeOrders as $order)
                    <tr @if($order->status === 'new') style="background: #fef2f2;" @endif>
                        <td>ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->retailer->name ?? 'N/A' }}</td>
                        <td>{{ $order->car_model }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>Shs {{ number_format($order->total_amount) }}</td>
                        <td>
                            @if($order->status === 'new')
                                <span style="color: #ef4444; font-weight: 700;">New</span>
                            @elseif($order->status === 'pending')
                                <span style="color: #f59e0b; font-weight: 700;">Pending</span>
                            @elseif($order->status === 'processing')
                                <span style="color: #3b82f6; font-weight: 700;">Processing</span>
                            @else
                                <span>{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->diffForHumans() }}</td>
                        <td>
                            <button style="background: #10b981; color: #fff; border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 1.1rem; margin-right: 0.3rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button style="background: #ef4444; color: #fff; border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 1.1rem; margin-right: 0.3rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            <button style="background: #f3f4f6; color: #222; border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 1.1rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-clock"></i> Delay
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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