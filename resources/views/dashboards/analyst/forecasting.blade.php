@extends('layouts.dashboard')

@section('title', 'Forecasting')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); font-size: 1.5rem; font-weight: bold; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.7rem;">
        <i class="fas fa-magic"></i> Forecasting
    </h2>
    <div style="margin-bottom: 2rem; color: #555; font-size: 1.08rem;">
        Run demand and sales forecasts using advanced analytics tools. Select a car model, region, and forecast period to get started.
    </div>
    <form id="forecastForm" style="display: flex; gap: 1.2rem; flex-wrap: wrap; align-items: flex-end; margin-bottom: 2.2rem;">
        <div style="flex: 1 1 180px; min-width: 160px;">
            <label for="carModel" style="font-weight: 600; color: var(--primary);">Car Model</label>
            <input type="text" id="carModel" name="carModel" class="form-control" placeholder="e.g. Model X" style="width: 100%; padding: 0.7rem; border-radius: 6px; border: 1px solid #ccc;">
        </div>
        <div style="flex: 1 1 180px; min-width: 160px;">
            <label for="region" style="font-weight: 600; color: var(--primary);">Region</label>
            <input type="text" id="region" name="region" class="form-control" placeholder="e.g. North" style="width: 100%; padding: 0.7rem; border-radius: 6px; border: 1px solid #ccc;">
        </div>
        <div style="flex: 1 1 120px; min-width: 120px;">
            <label for="months" style="font-weight: 600; color: var(--primary);">Months Ahead</label>
            <input type="number" id="months" name="months" class="form-control" min="1" max="12" value="3" style="width: 100%; padding: 0.7rem; border-radius: 6px; border: 1px solid #ccc;">
        </div>
        <button type="submit" style="background: var(--primary); color: #fff; border: none; border-radius: 6px; padding: 0.8rem 1.5rem; font-weight: 600; font-size: 1rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-play"></i> Run Forecast
        </button>
    </form>
    <div id="forecastLoading" style="display: none; text-align: center; margin: 2rem 0;">
        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i>
        <div style="margin-top: 1rem; color: #888;">Running forecast...</div>
    </div>
    <div id="forecastError" style="display: none; color: #b71c1c; background: #ffeaea; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; text-align: center;"></div>
    <div id="forecastResult" style="display: none;">
        <div style="margin-bottom: 2.2rem;">
            <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-chart-line"></i> Forecast Chart
            </h3>
            <div style="background: #f5faff; border-radius: 10px; padding: 1.5rem;">
                <canvas id="forecastChart" style="width: 100%; max-width: 100%; height: 220px;"></canvas>
            </div>
        </div>
        <div>
            <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-table"></i> Forecast Table
            </h3>
            <div style="overflow-x:auto;">
                <table id="forecastTable" style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden;">
                    <thead style="background: var(--primary-light); color: #fff;">
                        <tr>
                            <th style="padding: 0.7rem; text-align: left;">Month</th>
                            <th style="padding: 0.7rem; text-align: right;">Forecast</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const form = document.getElementById('forecastForm');
const loading = document.getElementById('forecastLoading');
const errorDiv = document.getElementById('forecastError');
const resultDiv = document.getElementById('forecastResult');
const tableBody = document.querySelector('#forecastTable tbody');
let forecastChart = null;

form.addEventListener('submit', function(e) {
    e.preventDefault();
    errorDiv.style.display = 'none';
    resultDiv.style.display = 'none';
    loading.style.display = 'block';
    const carModel = document.getElementById('carModel').value.trim();
    const region = document.getElementById('region').value.trim();
    // months is not used by backend, but keep for UI
    fetch('/predict-demand', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            model: carModel,
            region: region
        })
    })
    .then(res => res.json())
    .then(data => {
        loading.style.display = 'none';
        if (!data.success && !data.forecast) {
            errorDiv.textContent = data.message || 'Forecast failed.';
            errorDiv.style.display = 'block';
            return;
        }
        // Assume data.forecast is an array of { month, value }
        const forecastArr = data.forecast || [];
        if (forecastArr.length === 0) {
            errorDiv.textContent = 'No forecast data returned.';
            errorDiv.style.display = 'block';
            return;
        }
        const labels = forecastArr.map(item => item.month);
        const values = forecastArr.map(item => item.value);
        if (forecastChart) forecastChart.destroy();
        const ctx = document.getElementById('forecastChart').getContext('2d');
        forecastChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Forecast',
                    data: values,
                    borderColor: '#87193a',
                    backgroundColor: 'rgba(135, 25, 58, 0.08)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#87193a',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        tableBody.innerHTML = '';
        forecastArr.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `<td style='padding:0.7rem; border-bottom:1px solid #f0f0f0;'>${item.month}</td><td style='padding:0.7rem; border-bottom:1px solid #f0f0f0; text-align:right;'>${item.value}</td>`;
            tableBody.appendChild(row);
        });
        resultDiv.style.display = 'block';
    })
    .catch(() => {
        loading.style.display = 'none';
        errorDiv.textContent = 'Error connecting to forecast service.';
        errorDiv.style.display = 'block';
    });
});
</script>
@endpush
@endsection 