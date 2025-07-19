<!DOCTYPE html>
<html>
<head>
    <title>Production Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.6; }
        h1 { color: #1a7f37; font-size: 2rem; margin-bottom: 1.5rem; }
        .summary { display: flex; gap: 2rem; margin-bottom: 2rem; }
        .summary-card { flex:1; min-width:180px; background: #e8f5e9; color: #222; border-radius: 10px; padding: 1rem; text-align: center; }
        .summary-card .label { font-size: 1.05rem; opacity: 0.9; }
        .summary-card .value { font-size: 1.7rem; font-weight: bold; margin-top: 0.5rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th, td { padding: 0.6rem; border: 1px solid #bbb; font-size: 0.98rem; }
        th { background: #f1f8e9; color: #1a7f37; }
        td { color: #333; }
    </style>
</head>
<body>
    <h1>Production Report</h1>
    <div class="summary">
        <div class="summary-card">
            <div class="label">Total Items Processed</div>
            <div class="value">{{ $totalItemsProcessed }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Total Completed Items</div>
            <div class="value">{{ $totalCompletedItems }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Total Failed Items</div>
            <div class="value">{{ $totalFailedItems }}</div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Current Stage</th>
                <th>Status</th>
                <th>Entered Stage At</th>
                <th>Completed Stage At</th>
                <th>Failure Reason</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($processFlows as $flow)
                <tr>
                    <td>{{ $flow->item_name }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $flow->current_stage)) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $flow->status)) }}</td>
                    <td>{{ $flow->entered_stage_at ? \Carbon\Carbon::parse($flow->entered_stage_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                    <td>{{ $flow->completed_stage_at ? \Carbon\Carbon::parse($flow->completed_stage_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                    <td>{{ $flow->failure_reason ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#888;">No process flow data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
