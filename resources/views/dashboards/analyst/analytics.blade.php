@extends('layouts.dashboard')

@section('title', 'Analytics')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); font-size: 1.5rem; font-weight: bold; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.7rem;">
        <i class="fas fa-chart-line"></i> Analytics
    </h2>
    <div style="display: flex; flex-wrap: wrap; gap: 2.5rem; margin-bottom: 2.2rem;">
        <div style="flex: 1 1 320px; background: #2d85dc; border-radius: 10px; padding: 1.5rem; min-width: 260px;">
            <h3 style="color: #fff; font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem;">
                <i class="fas fa-chart-bar"></i> Total Sales
            </h3>
            <div style="font-size: 2.2rem; font-weight: bold; color: #fff;">{{ number_format($totalSales) }}</div>
            <div style="color: black; font-size: 1.08rem; margin-top: 0.7rem;">Total units sold across all retailers</div>
        </div>
        <div style="flex: 1 1 320px; background: #50c198; border-radius: 10px; padding: 1.5rem; min-width: 260px;">
            <h3 style="color:#fff; font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem;">
                <i class="fas fa-warehouse"></i> Total Inventory
            </h3>
            <div style="font-size: 2.2rem; font-weight: bold; color: #fff;">{{ number_format($totalStock) }}</div>
            <div style="color: black; font-size: 1.08rem; margin-top: 0.7rem;">Total stock received by retailers</div>
        </div>
    </div>
    <div style="margin-bottom: 2.2rem;">
        <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-chart-bar"></i> Sales by Car Model
        </h3>
        <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
            <div style="flex: 2 1 340px; min-width: 260px;">
                <canvas id="salesByModelChart" style="width: 100%; max-width: 100%; height: 220px;"></canvas>
            </div>
            <div style="flex: 1 1 220px; min-width: 200px;">
                <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden;">
                    <thead style="background: var(--primary-light); color: #fff;">
                        <tr>
                            <th style="padding: 0.7rem; text-align: left;">Car Model</th>
                            <th style="padding: 0.7rem; text-align: right;">Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesByModel as $row)
                        <tr>
                            <td style="padding: 0.7rem; border-bottom: 1px solid #f0f0f0;">{{ $row->car_model }}</td>
                            <td style="padding: 0.7rem; border-bottom: 1px solid #f0f0f0; text-align: right;">{{ number_format($row->total_sold) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-bottom: 2.2rem;">
        <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-calendar-alt"></i> Sales by Month
        </h3>
        <div style="background: #f5faff; border-radius: 10px; padding: 1.5rem;">
            <canvas id="salesByMonthChart" style="width: 100%; max-width: 100%; height: 220px;"></canvas>
        </div>
    </div>
    <div style="margin-bottom: 2.2rem;">
        <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-warehouse"></i> Inventory by Car Model
        </h3>
        <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
            <div style="flex: 2 1 340px; min-width: 260px;">
                <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden;">
                    <thead style="background: var(--primary-light); color: #fff;">
                        <tr>
                            <th style="padding: 0.7rem; text-align: left;">Car Model</th>
                            <th style="padding: 0.7rem; text-align: right;">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockByModel as $row)
                        <tr>
                            <td style="padding: 0.7rem; border-bottom: 1px solid #f0f0f0;">{{ $row->car_model }}</td>
                            <td style="padding: 0.7rem; border-bottom: 1px solid #f0f0f0; text-align: right;">{{ number_format($row->total_stock) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
        <h3 style="color: #b71c1c; font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-exclamation-triangle"></i> Low Stock Items
        </h3>
        <div style="background: #fff3e0; border-radius: 10px; padding: 1.5rem;">
            @if($lowStockItems->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #ff9800; color: #fff;">
                    <tr>
                        <th style="padding: 0.7rem; text-align: left;">Car Model</th>
                        <th style="padding: 0.7rem; text-align: right;">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockItems as $row)
                    <tr>
                        <td style="padding: 0.7rem; border-bottom: 1px solid #ffe0b2;">{{ $row->car_model }}</td>
                        <td style="padding: 0.7rem; border-bottom: 1px solid #ffe0b2; text-align: right;">{{ number_format($row->total_stock) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="color: #b71c1c; font-size: 1.08rem;">No low stock items found.</div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales by Car Model Chart
const salesByModelCtx = document.getElementById('salesByModelChart').getContext('2d');
const salesByModelChart = new Chart(salesByModelCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($salesByModel->pluck('car_model')) !!},
        datasets: [{
            label: 'Units Sold',
            data: {!! json_encode($salesByModel->pluck('total_sold')) !!},
            backgroundColor: 'rgba(40, 167, 69, 0.7)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
// Sales by Month Chart
const salesByMonthCtx = document.getElementById('salesByMonthChart').getContext('2d');
const salesByMonthChart = new Chart(salesByMonthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesByMonth->pluck('month')) !!},
        datasets: [{
            label: 'Units Sold',
            data: {!! json_encode($salesByMonth->pluck('total_sold')) !!},
            borderColor: '#102a9b',
            backgroundColor: 'rgba(16, 42, 155, 0.08)',
            fill: true,
            tension: 0.3,
            pointRadius: 4,
            pointBackgroundColor: '#102a9b',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush
@endsection 