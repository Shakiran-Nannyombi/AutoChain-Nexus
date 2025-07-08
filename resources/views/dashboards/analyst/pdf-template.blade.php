<!DOCTYPE html>
<html>
<head>
    <title>{{ $report->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h1 { color: #4e73df; }
        .section { margin-bottom: 20px; }
        .chart-data { margin: 10px 0; }
        .chart-row { display: flex; justify-content: space-between; margin: 5px 0; }
        .chart-label { font-weight: bold; }
        .chart-value { color: #4e73df; }
    </style>
</head>
<body>
    <h1>{{ $report->title }}</h1>
    <p><strong>Type:</strong> {{ ucfirst($report->type) }}</p>
    <p><strong>Target Role:</strong> {{ ucfirst($report->target_role) }}</p>
    <div class="section">
        <strong>Summary:</strong>
        <p>{{ $report->summary }}</p>
    </div>

    @if(!empty($chartBase64))
        <div class="section">
            <strong>Sales Chart:</strong><br>
            <img src="data:image/png;base64,{{ $chartBase64 }}" style="width: 100%; max-width: 500px;">
        </div>
    @else
        <p>No chart available.</p>
    @endif

    @if(!empty($salesByModel) && count($salesByModel) > 0)
    <div class="section">
        <strong>Sales by Car Model:</strong>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
    <thead style="background-color: #f0f0f0;">
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px;">Car Model</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Total Sold</th>
        </tr>
    </thead>
    <tbody>
        @foreach($salesByModel as $sale)
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;">{{ $sale->car_model }}</td>
            <td style="border: 1px solid #ddd; padding: 8px;">{{ $sale->total_sold }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
    @endif

    <p><small>Generated on: {{ now()->format('M d, Y H:i') }}</small></p>
</body>
</html>
