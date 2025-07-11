@extends('layouts.dashboard')

@section('title', 'Reports')
@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <div class="content-card reports-page">
    <h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-file-alt"></i> Reports</h2>

    <div class="reports-grid">
        <!-- Schedule a New Report -->
        <div class="report-card">
            <h3 class="card-title">Schedule a New Report</h3>
            <form action="{{ route('admin.reports.schedule') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="report_type">Report Type</label>
                    <select id="report_type" name="report_type" required>
                        <option value="" disabled selected>Select a report...</option>
                        <option value="user_activity">User Activity Report</option>
                        <option value="inventory_summary">Inventory Summary</option>
                        <option value="validation_outcomes">Validation Outcomes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="recipients">Stakeholder Emails</label>
                    <input type="text" id="recipients" name="recipients" placeholder="e.g., manager@example.com, ceo@example.com" required>
                    <small>Enter multiple emails separated by commas.</small>
                </div>

                <div class="form-group">
                    <label for="frequency">Frequency</label>
                    <select id="frequency" name="frequency" required>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>

                <button type="submit" class="btn-schedule">Schedule Report</button>
            </form>
        </div>

        <!-- Currently Scheduled Reports -->
        <div class="report-card">
            <h3 class="card-title">Currently Scheduled Reports</h3>
            <div class="scheduled-reports-list">
                @forelse ($scheduledReports as $report)
                    <div class="scheduled-item">
                        <div class="report-info">
                            <div class="report-name">{{ ucwords(str_replace('_', ' ', $report->report_type)) }}</div>
                            <div class="report-details">
                                Sending {{ $report->frequency }} to <strong>{{ Str::limit($report->recipients, 30) }}</strong>
                            </div>
                        </div>
                        <div class="report-actions">
                            <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete-report">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="no-reports">No reports have been scheduled yet.</p>
                @endforelse
            </div>
        </div>
    </div>
  </div>
@endsection
