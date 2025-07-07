@extends('layouts.dashboard')
@section('title', 'Sales Analysis')
@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
    <h1 class="page-header">Sales Analysis</h1>

    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-chart-pie"></i> Top Selling Car Models
        </h2>

        <div style="margin-bottom: 2rem;">
            <table class="table table-striped">
                <thead style="background: var(--light-cyan);">
                    <tr>
                        <th>Car Model</th>
                        <th>Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesByModel as $sale)
                        <tr>
                            <td>{{ $sale->car_model }}</td>
                            <td>{{ $sale->total_sold }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-chart-line"></i> Monthly Sales Trend
        </h2>

        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>
@endsection

@php
    $months = json_encode(array_keys($monthlySales));
    $sales = json_encode(array_values($monthlySales));
@endphp


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const salesLabels = JSON.parse('{!! addslashes($months) !!}');
        const salesData = JSON.parse('{!! addslashes($sales) !!}');        

        const ctx = document.getElementById('salesChart').getContext('2d');

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Monthly Sales',
                    data: salesData,
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#333',
                            font: { weight: 'bold' }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#555',
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Units Sold',
                            color: '#777'
                        }
                    },
                    x: {
                        ticks: { color: '#555' },
                        title: {
                            display: true,
                            text: 'Month',
                            color: '#777'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush