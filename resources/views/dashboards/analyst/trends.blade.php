@extends('layouts.dashboard')

@section('title', 'Trends')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
    <div class="content-card">
    <h2 class="page-header"><i class="fas fa-trending-up"></i> Sales Trend Analysis</h2>

    <p>Trend based on 3-month Simple Moving Average (SMA)</p>

    <canvas id="trendChart" height="100"></canvas>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trendChart').getContext('2d');
const trendChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: JSON.parse('{!! addslashes(json_encode($labels)) !!}'),
        datasets: [
            {
                label: 'Monthly Sales',
                data: JSON.parse('{!! addslashes(json_encode(array_values($monthlySales))) !!}'),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: '3-Month SMA',
                data: JSON.parse('{!! addslashes(json_encode($sma)) !!}'),
                borderColor: '#28a745',
                borderDash: [5, 5],
                fill: false,
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush