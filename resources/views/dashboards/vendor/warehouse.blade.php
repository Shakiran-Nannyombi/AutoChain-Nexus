@extends('layouts.dashboard')

@section('title', 'Warehouse Management')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card vendor-warehouse-dashboard">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Warehouse Management
    </h2>
    
    <!-- Stat Cards Row -->
    <div class="warehouse-stat-cards">
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--primary), #0d3a07); color: #fff;">
            <div class="stat-label"><i class="fas fa-boxes"></i> Total Products</div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-desc">Active products in warehouse</div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--primary-light), #0a440d); color: #fff;">
            <div class="stat-label"><i class="fas fa-warehouse"></i> Total Stock</div>
            <div class="stat-value">{{ $totalStock }}</div>
            <div class="stat-desc">Units in warehouse</div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--accent), #4e2501); color: #fff;">
            <div class="stat-label"><i class="fas fa-exclamation-triangle"></i> Low Stock</div>
            <div class="stat-value">{{ $lowStockProducts }}</div>
            <div class="stat-desc">Products below 10 units</div>
        </div>
        <div class="warehouse-stat-card" style="background: linear-gradient(135deg, var(--secondary), #b35400); color: #fff;">
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
            <div class="chart-title">Product Inventory</div>
            <a href="{{ route('vendor.products') }}" style="color: var(--primary); font-weight: 600; font-size: 1rem;">Manage Products</a>
        </div>
        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><a href="#" style="color: #2563eb; font-weight: 600;">{{ $product->name }}</a></td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->stock }} units</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            @if($product->stock == 0)
                                <span class="status-badge danger">Out of Stock</span>
                            @elseif($product->stock < 10)
                                <span class="status-badge warning">Low Stock</span>
                            @else
                                <span class="status-badge success">In Stock</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="updateStock({{ $product->id }}, {{ $product->stock }})">
                                Update Stock
                            </button>
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

    <!-- Recent Stock Movements & Recent Activity Row -->
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 2rem; align-items: flex-start;">
        <!-- Recent Stock Movements -->
        <div style="flex: 1 1 320px; min-width: 320px; max-width: 370px; display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem 2rem;">
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Recent Stock Movements</div>
                <div style="font-size: 0.98rem; color: var(--text-light); margin-bottom: 1rem;">Latest stock transfers to retailers</div>
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($recentStockMovements as $movement)
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                        <div style="font-weight: 600; color: var(--text-dark);">{{ $movement->car_model }}</div>
                        <div style="font-size: 0.9rem; color: var(--text-light);">
                            {{ $movement->quantity_received }} units to {{ $movement->retailer->name ?? 'Unknown Retailer' }}
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-light);">
                            {{ $movement->created_at->diffForHumans() }}
                </div>
            </div>
                    @empty
                    <div style="text-align: center; color: var(--text-light); padding: 1rem;">
                        No recent stock movements
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Warehouse Activity -->
        <div class="dashboard-activity-card" style="flex: 2 1 400px; min-width: 320px;">
            <h3 class="dashboard-section-title"><i class="fas fa-history"></i> Recent Warehouse Activity</h3>
            <ul class="activity-list">
                @forelse($recentStockMovements->take(5) as $movement)
                <li>
                    <span class="activity-dot info"></span> 
                    {{ $movement->quantity_received }} {{ $movement->car_model }} sent to {{ $movement->retailer->name ?? 'retailer' }}
                    <span class="activity-time">{{ $movement->created_at->diffForHumans() }}</span>
                </li>
                @empty
                <li><span class="activity-dot info"></span> No recent activity</li>
                @endforelse
            </ul>
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
                    { value: {{ $products->where('stock', '>', 10)->count() }}, name: 'In Stock', itemStyle: { color: '#27ae60' } },
                    { value: {{ $lowStockProducts }}, name: 'Low Stock', itemStyle: { color: '#f39c12' } },
                    { value: {{ $outOfStockProducts }}, name: 'Out of Stock', itemStyle: { color: '#e74c3c' } }
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
                    itemStyle: { color: '#16610E' },
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