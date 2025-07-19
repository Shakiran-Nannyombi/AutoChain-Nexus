@extends('layouts.dashboard')

@section('title', 'Generate Report')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card" style="padding: 2.5rem 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; margin-bottom: 2.2rem;">
        <h2 style="color: var(--primary); font-size: 2rem; font-weight: 800; margin-bottom: 0; display: flex; align-items: center; gap: 0.7rem; letter-spacing: 0.5px;">
            <i class="fas fa-cogs" style="color: var(--primary);"></i> Generate New Report
        </h2>
        <a href="{{ route('analyst.reports') }}" style="background: var(--primary); color: #fff; border-radius: 8px; padding: 0.8rem 2rem; font-weight: 700; font-size: 1.05rem; text-decoration: none; box-shadow: 0 2px 8px rgba(22,97,14,0.08); display: flex; align-items: center; gap: 0.7rem; transition: background 0.18s, box-shadow 0.18s;">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>
    <h4 style="color:#F97A00; font-size: 1.1rem; font-weight: 700; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
        <i class="fas fa-lightbulb"></i> Tips
    </h4>
    <p style="color:black; font-size: 1.2rem; margin-bottom: 1.2rem;">
        Select a report type and date range <br>
        Use filters for more specific results 
        <br> Preview the report before downloading <br>
        Download as PDF or CSV
    </p>
        <!-- Main Form & Preview -->
        <section style="flex: 3 1 500px; min-width: 320px;">
            <form method="GET" action="{{ route('analyst.reports.generate') }}" style="display: flex; flex-direction: column; gap: 1.5rem; background: var(--background); border-radius: 10px; box-shadow: 0 1px 8px rgba(22,97,14,0.04); padding: 2rem 1.5rem;">
                <div>
                    <label for="type" style="font-weight: 700; color: var(--primary); margin-bottom: 0.3rem; display: block;">Report Type</label>
                    <select name="type" id="type" required style="width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--primary-light); border-radius: 8px; font-size: 1.08rem; background: #fff; color: #222;">
                        <option value="">Select type...</option>
                        <option value="sales" {{ request('type') == 'sales' ? 'selected' : '' }}>Sales</option>
                        <option value="inventory" {{ request('type') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                        <option value="performance" {{ request('type') == 'performance' ? 'selected' : '' }}>Performance</option>
                    </select>
                </div>
                <div>
                    <label for="order_report_type" style="font-weight: 700; color: var(--primary); margin-bottom: 0.3rem; display: block;">Order Report Source</label>
                    <select name="order_report_type" id="order_report_type" style="width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--primary-light); border-radius: 8px; font-size: 1.08rem; background: #fff; color: #222;">
                        <option value="">Select source...</option>
                        <option value="supplier_orders" {{ request('order_report_type') == 'supplier_orders' ? 'selected' : '' }}>Supplier Orders by Manufacturer</option>
                        <option value="vendor_orders" {{ request('order_report_type') == 'vendor_orders' ? 'selected' : '' }}>Vendor Orders to Manufacturer</option>
        </select>
                </div>
                <div style="display: flex; gap: 1.2rem;">
                    <div style="flex:1;">
                        <label for="start_date" style="font-weight: 700; color: var(--primary); margin-bottom: 0.3rem; display: block;">Start Date</label>
                        <input type="date" name="start_date" id="start_date" required style="width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--primary-light); border-radius: 8px; font-size: 1.08rem; background: #f9fafb; color: #222;" value="{{ request('start_date') }}">
                    </div>
                    <div style="flex:1;">
                        <label for="end_date" style="font-weight: 700; color: var(--primary); margin-bottom: 0.3rem; display: block;">End Date</label>
                        <input type="date" name="end_date" id="end_date" required style="width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--primary-light); border-radius: 8px; font-size: 1.08rem; background: #f9fafb; color: #222;" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div>
                    <label for="filter" style="font-weight: 700; color: var(--primary); margin-bottom: 0.3rem; display: block;">Filter (optional)</label>
                    <input type="text" name="filter" id="filter" placeholder="e.g. Car Model, Region, Analyst..." style="width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--primary-light); border-radius: 8px; font-size: 1.08rem; background: #f9fafb; color: #222;" value="{{ request('filter') }}">
                </div>
                <button type="submit" style="background: var(--primary); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2rem; font-weight: 700; font-size: 1.1rem; margin-top: 0.5rem; box-shadow: 0 2px 8px rgba(22,97,14,0.08); cursor: pointer; transition: background 0.18s, box-shadow 0.18s;">
                    <i class="fas fa-play"></i> Generate Report
                </button>
    </form>
            @if(isset($reportData))
            <div style="margin-top: 2.5rem; background:#fff; border-radius: 10px; padding: 2rem 1.5rem; box-shadow: 0 1px 8px rgba(22,97,14,0.04);">
                <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-eye"></i> Report Preview
                </h3>
                <!-- Example: Table preview -->
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse: collapse; background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(22,97,14,0.04);">
                        <thead>
                            <tr>
                                @foreach($reportData['headers'] as $header)
                                    <th style="padding: 0.7rem 1rem; background: var(--primary-light); color: var(--primary); font-weight: 700;">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['rows'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td style="padding: 0.7rem 1rem; color: #222; border-bottom: 1px solid #eee;">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                    <a href="{{ $reportData['pdfUrl'] ?? '#' }}" style="background: var(--primary); color: #fff; border-radius: 6px; padding: 0.7rem 1.5rem; font-weight: 600; text-decoration: none;">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                    <a href="{{ $reportData['csvUrl'] ?? '#' }}" style="background: var(--secondary); color: #fff; border-radius: 6px; padding: 0.7rem 1.5rem; font-weight: 600; text-decoration: none;">
                        <i class="fas fa-file-csv"></i> Download CSV
                    </a>
                </div>
            </div>
            @endif
        </section>
</div>
@endsection
