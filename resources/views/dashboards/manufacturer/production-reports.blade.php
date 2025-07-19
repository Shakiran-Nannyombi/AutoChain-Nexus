@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem; font-weight:bold;"><i class="fas fa-file-alt"></i> Production Reports</h2>
        <!-- Filter, stats, and table content below -->
        <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: var(--primary); font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Filter Reports</h3>
            <form id="filterForm" style="display: flex; gap: 2rem; flex-wrap: wrap; align-items: flex-end;">
                <div>
                    <label for="startDate" style="font-size: 0.98rem; color: #333;">Start Date:</label>
                    <input type="date" id="startDate" name="start_date" style="margin-top: 0.3rem; border-radius: 6px; border: 1px solid #ccc; padding: 0.5rem;">
                </div>
                <div>
                    <label for="endDate" style="font-size: 0.98rem; color: #333;">End Date:</label>
                    <input type="date" id="endDate" name="end_date" style="margin-top: 0.3rem; border-radius: 6px; border: 1px solid #ccc; padding: 0.5rem;">
                </div>
                <div>
                    <button type="submit" style="padding: 0.6rem 1.5rem; background: var(--primary); color: #fff; border-radius: 6px; border: none; font-weight: 600;">Apply Filters</button>
                </div>
            </form>
        </div>
        <!-- Production Summary Statistics -->
        <div style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <div style="flex:1; min-width:200px; background: linear-gradient(135deg, var(--primary), #0d3a07); color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.1rem; opacity: 0.9;">Total Items Processed</div>
                <div style="font-size: 2.2rem; font-weight: bold; margin-top: 0.5rem;">{{ $totalItemsProcessed }}</div>
            </div>
            <div style="flex:1; min-width:200px; background: linear-gradient(135deg, #27ae60, #16610e); color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.1rem; opacity: 0.9;">Total Completed Items</div>
                <div style="font-size: 2.2rem; font-weight: bold; margin-top: 0.5rem;">{{ $totalCompletedItems }}</div>
            </div>
            <div style="flex:1; min-width:200px; background: linear-gradient(135deg, #b71c1c, #e57373); color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.1rem; opacity: 0.9;">Total Failed Items</div>
                <div style="font-size: 2.2rem; font-weight: bold; margin-top: 0.5rem;">{{ $totalFailedItems }}</div>
            </div>
        </div>
        <!-- Detailed Process Flow Table -->
        <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem;">
            <h3 style="color: var(--primary); font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Detailed Process Flow</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead style="background: #f8f8f8;">
                        <tr>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Item Name</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Current Stage</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Status</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Entered Stage At</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Completed Stage At</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Failure Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($processFlows as $flow)
                            <tr style="transition: background 0.2s;" onmouseover="this.style.background='#f3f7fa'" onmouseout="this.style.background='#fff'">
                                <td style="padding: 0.7rem; font-weight: 500; color: #222;">{{ $flow->item_name }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ ucfirst(str_replace('_', ' ', $flow->current_stage)) }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ ucfirst(str_replace('_', ' ', $flow->status)) }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ $flow->entered_stage_at ? \Carbon\Carbon::parse($flow->entered_stage_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ $flow->completed_stage_at ? \Carbon\Carbon::parse($flow->completed_stage_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ $flow->failure_reason ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 1.2rem; color: #888; text-align:center;">No process flow data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <button onclick="exportReport('csv')" style="padding: 0.6rem 1.5rem; background: #eee; color: #333; border-radius: 6px; border: none; font-weight: 600;">Export to CSV</button>
                <a href="{{ route('manufacturer.production-reports.pdf', request()->all()) }}" target="_blank" style="padding: 0.6rem 1.5rem; background: var(--primary); color: #fff; border-radius: 6px; border: none; font-weight: 600; text-decoration: none;">Export to PDF</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        // Reload the page with query parameters for filtering
        window.location.href = `{{ route('manufacturer.production-reports') }}?start_date=${startDate}&end_date=${endDate}`;
    });

    function exportReport(format) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        window.location.href = `{{ route('manufacturer.production-reports') }}?start_date=${startDate}&end_date=${endDate}&export=${format}`;
    }
</script>
@endpush