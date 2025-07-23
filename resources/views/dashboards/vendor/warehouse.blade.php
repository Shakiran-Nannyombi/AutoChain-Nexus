@extends('layouts.dashboard')

@section('title', 'Warehouse Management')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card vendor-warehouse-dashboard">
    <h2  style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text); letter-spacing: 0.01em;">
        Warehouse Management
    </h2>
    
    <!-- Stat Cards Row -->
    <div class="warehouse-stat-cards">
        <div class="warehouse-stat-card" style="background: linear-gradient(125deg,  #43ab72, #1b0a44); color: #fff;">
            <div class="stat-label"><i class="fas fa-boxes"></i> Total Products</div>
            <div class="stat-value">{{ $products->count() }}</div>
            <div class="stat-desc">Active products in warehouse</div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(125deg,  #43ab72, #1b0a44); color: #fff;">
            <div class="stat-label"><i class="fas fa-warehouse"></i> Total Stock</div>
            <div class="stat-value">{{ $products->sum('stock') }}</div>
            <div class="stat-desc">Units in warehouse</div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(125deg,  #43ab72, #1b0a44); color: #fff;">
            <div class="stat-label"><i class="fas fa-exclamation-triangle"></i> Low Stock</div>
            <div class="stat-value">{{ $lowStockProducts }}</div>
            <div class="stat-desc">Products below 10 units</div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(125deg,  #43ab72, #1b0a44); color: #fff;">
            <div class="stat-label"><i class="fas fa-times-circle"></i> Out of Stock</div>
            <div class="stat-value">{{ $outOfStockProducts }}</div>
            <div class="stat-desc">Products with zero stock</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="warehouse-charts-row">
        <div class="warehouse-chart-card">
            <div class="chart-title">Stock Status Distribution</div>
            <div id="warehousePieChart" style="width: 100%; min-width: 220px; height: 220px;"></div>
        </div>
        <div class="warehouse-chart-card">
            <div class="chart-title">Stock Levels by Product</div>
            <div id="warehouseBarChart" style="width: 100%; min-width: 220px; height: 220px;"></div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="warehouse-table-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="chart-title" style="color: var(--text); font-size: 1.8rem; font-weight: 600;">Product Inventory</div>
        </div>
        <div class="table-responsive">
            <table class="dashboard-table" style="width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden;">
                <thead style="background:#43ab72; color:var(--text);">
                    <tr>
                        <th style="padding:0.9rem 0.7rem;">Product Name</th>
                        <th style="padding:0.9rem 0.7rem;">Category</th>
                        <th style="padding:0.9rem 0.7rem;">Current Stock</th>
                        <th style="padding:0.9rem 0.7rem;">Price</th>
                        <th style="padding:0.9rem 0.7rem;">Status</th>
                        <th style="padding:0.9rem 0.7rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr style="background: {{ $loop->even ? '#f8fafc' : '#fff' }}; border-bottom:1px solid #e5e7eb; transition:background 0.2s;" onmouseover="this.style.background='#e6f7f1'" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#fff' }}'">
                        <td style="padding:0.8rem 0.7rem;"><a href="#" style="color:var(--text); font-weight:600;">{{ $product->name }}</a></td>
                        <td style="padding:0.8rem 0.7rem;">{{ $product->category }}</td>
                        <td style="padding:0.8rem 0.7rem;">{{ $product->stock }} units</td>
                        <td style="padding:0.8rem 0.7rem;">${{ number_format($product->price, 2) }}</td>
                        <td style="padding:0.8rem 0.7rem; font-weight:600;">
                            @if($product->stock == 0)
                                <span class="status-badge danger">Out of Stock</span>
                            @elseif($product->stock < 10)
                                <span class="status-badge warning">Low Stock</span>
                            @else
                                <span class="status-badge success">In Stock</span>
                            @endif
                        </td>
                        <td style="padding:0.8rem 0.7rem;">
                            <!-- Update Stock button removed as requested -->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div class="modal fade" id="stockUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockUpdateForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="stock" class="form-label">New Stock Level</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ECharts CDN for Pie and Bar Charts -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Stock Status Pie Chart
    var pieDom = document.getElementById('warehousePieChart');
    if (pieDom) {
        var pieChart = echarts.init(pieDom);
        var pieOption = {
            tooltip: { trigger: 'item' },
            legend: { orient: 'vertical', right: 10, top: 30, textStyle: { color: '#888' } },
            series: [{
                name: 'Stock Status',
                type: 'pie',
                radius: ['55%', '80%'],
                center: ['40%', '50%'],
                label: { show: true, position: 'center', formatter: '{b}', fontSize: 16, fontWeight: 600, color: '#333' },
                data: [
                    { value: {{ $products->where('stock', '>', 10)->count() }}, name: 'In Stock', itemStyle: { color: '#43ab72' } },
                    { value: {{ $lowStockProducts }}, name: 'Low Stock', itemStyle: { color: '#f39c12' } },
                    { value: {{ $outOfStockProducts }}, name: 'Out of Stock', itemStyle: { color: '#1b0a44' } }
                ]
            }]
        };
        pieChart.setOption(pieOption);
        window.addEventListener('resize', function () { pieChart.resize(); });
    }

    // Stock Levels Bar Chart
    var barDom = document.getElementById('warehouseBarChart');
    if (barDom) {
        var barChart = echarts.init(barDom);
        var productNames = @json($products->pluck('name')->take(5));
        var stockLevels = @json($products->pluck('stock')->take(5));
        var barOption = {
            tooltip: { trigger: 'axis' },
            grid: { left: 40, right: 20, top: 40, bottom: 40 },
            xAxis: {
                type: 'category',
                data: productNames,
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#888', rotate: 45 }
            },
            yAxis: {
                type: 'value',
                axisLine: { lineStyle: { color: '#eee' } },
                axisLabel: { color: '#888' },
                splitLine: { lineStyle: { color: '#eee' } }
            },
            series: [{
                name: 'Stock Level',
                type: 'bar',
                data: stockLevels,
                itemStyle: { color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                    { offset: 0, color: '#43ab72' },
                    { offset: 1, color: '#1b0a44' }
                ]) },
                barWidth: 20
            }]
        };
        barChart.setOption(barOption);
        window.addEventListener('resize', function () { barChart.resize(); });
    }
});

function updateStock(productId, currentStock) {
    document.getElementById('stock').value = currentStock;
    document.getElementById('stockUpdateForm').action = '{{ route("vendor.warehouse.update-stock", ":id") }}'.replace(':id', productId);
    new bootstrap.Modal(document.getElementById('stockUpdateModal')).show();
}
</script>
@endpush