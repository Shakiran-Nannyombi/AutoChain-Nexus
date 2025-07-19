@extends('layouts.dashboard')

@section('title', 'Select Sales Analysis File')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card" style="max-width: 700px; margin: 2rem auto;">
    <h2 style="color: var(--primary); font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem;">
        <i class="fas fa-database"></i> Select Sales Analysis File
    </h2>
    @if($files->isEmpty())
        <div style="color: #b71c1c; background: #ffeaea; padding: 1rem; border-radius: 6px;">No sales analysis files uploaded by your assigned analysts yet.</div>
    @else
    <form id="selectSalesFileForm" onsubmit="return false;">
        @csrf
        <div style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Available Files:</label>
            @foreach($files as $file)
                <div style="margin-bottom: 0.7rem; padding: 0.7rem 1rem; background: #f7f7f7; border-radius: 6px; display: flex; align-items: center; gap: 1rem;">
                    <input type="radio" name="selected_file" value="{{ $file->id }}" id="file_{{ $file->id }}" data-file-path="{{ asset('storage/' . $file->report_file) }}" {{ $loop->first ? 'checked' : '' }}>
                    <label for="file_{{ $file->id }}" style="flex:1; cursor:pointer;">
                        <strong>{{ $file->title }}</strong> <span style="color: #888; font-size: 0.98em;">({{ $file->type }}, {{ $file->created_at->format('Y-m-d H:i') }})</span><br>
                        <span style="font-size: 0.97em; color: #555;">{{ $file->summary }}</span>
                    </label>
                    <a href="{{ asset('storage/' . $file->report_file) }}" target="_blank" style="color: var(--primary); font-size: 0.98em; text-decoration: underline;">Download</a>
                </div>
            @endforeach
        </div>
    </form>
    <div id="forecast-result" style="margin-top:2rem;"></div>
    <div style="margin-top: 2.5rem;">
        <h3 style="color: var(--primary); font-size: 1.1rem; font-weight: 600; margin-bottom: 0.7rem;">Preview of Most Recent File</h3>
        @if($preview)
            <div style="background: #f9f9f9; border-radius: 6px; padding: 1rem; font-family: monospace; font-size: 0.98em; max-height: 300px; overflow: auto;">
                @if(is_array($preview) && isset($preview[0]) && is_string($preview[0]))
                    <pre style="margin:0;">{!! implode("\n", array_map('e', $preview)) !!}</pre>
                @else
                    <pre style="margin:0;">{!! json_encode($preview, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}</pre>
                @endif
            </div>
        @else
            <div style="color: #888;">No preview available.</div>
        @endif
    </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function fetchForecastFromFile(fileId, filePath) {
    // Example: send AJAX to FastAPI with fileId or filePath
    // Here, you would POST to your FastAPI endpoint, passing the file path or ID
    // For demo, just show a loading message and fake result
    const resultDiv = document.getElementById('forecast-result');
    resultDiv.innerHTML = '<div style="color:var(--primary);font-weight:600;">Generating forecast from selected file...</div>';
    // Example AJAX (replace URL and payload as needed)
    fetch('http://localhost:8001/forecast', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ file_id: fileId, file_path: filePath })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            resultDiv.innerHTML = '<div style="color:#b71c1c;">'+data.error+'</div>';
            return;
        }
        // Render chart and table
        let table = '<table class="demand-prediction-table" style="margin-top:1rem;"><thead><tr><th>Month</th><th>Predicted Sales</th></tr></thead><tbody>';
        let labels = [], values = [];
        data.forEach(row => {
            table += `<tr><td>${row.Month}</td><td>${row.Predicted}</td></tr>`;
            labels.push(row.Month);
            values.push(row.Predicted);
        });
        table += '</tbody></table>';
        resultDiv.innerHTML = '<canvas id="forecastChart" style="max-width:600px;"></canvas>' + table;
        const ctx = document.getElementById('forecastChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: { labels, datasets: [{ label: 'Predicted Sales', data: values, borderColor: 'rgba(33,150,243,0.8)', backgroundColor: 'rgba(33,150,243,0.2)', fill: true }] },
            options: { responsive: true, plugins: { legend: { display: false }, title: { display: true, text: 'Forecast from Selected File' } } }
        });
    })
    .catch(err => {
        resultDiv.innerHTML = '<div style="color:#b71c1c;">Error: '+err+'</div>';
    });
}
// Auto-trigger forecast on file selection
const radios = document.querySelectorAll('input[name="selected_file"]');
radios.forEach(radio => {
    radio.addEventListener('change', function() {
        fetchForecastFromFile(this.value, this.getAttribute('data-file-path'));
    });
});
// Trigger forecast for the initially checked file on page load
window.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="selected_file"]:checked');
    if (checked) fetchForecastFromFile(checked.value, checked.getAttribute('data-file-path'));
});
</script>
@endsection 