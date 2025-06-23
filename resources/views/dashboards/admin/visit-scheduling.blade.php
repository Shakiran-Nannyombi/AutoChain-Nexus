@extends('layouts.dashboard')

@section('title', 'Visit Scheduling & Management')

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo2.png') }}">
@endpush

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ session('error') }}</div>
    @endif
    <h1 class="page-title">Visit Scheduling & Management</h1>
    <br>

    <!-- Stat Cards -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <p>Pending Requests</p>
                <span>{{ $stats['pending'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <p>Approved Visits</p>
                <span>{{ $stats['approved'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="stat-info">
                <p>Rejected Visits</p>
                <span>{{ $stats['rejected'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-robot"></i></div>
            <div class="stat-info">
                <p>Auto-Scheduled</p>
                <span>{{ $stats['auto_scheduled'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon light-green"><i class="fas fa-clipboard-check"></i></div>
            <div class="stat-info">
                <p>Completed This Week</p>
                <span>{{ $stats['this_week'] }}</span>
            </div>
        </div>
    </div>

    <!-- Facility Visit Requests -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h2>Facility Visit Requests</h2>
        </div>
        <div class="card-body">
            @forelse ($visits as $visit)
                <div class="visit-card">
                    <div class="visit-main-info">
                        <div class="visit-header">
                            <h3 class="company-name">{{ $visit->company_name }}</h3>
                            <span class="status-badge-visit status-{{ $visit->status }}">{{ ucfirst($visit->status) }}</span>
                            <span class="visit-type-badge">{{ $visit->visit_type }}</span>
                        </div>
                        <div class="contact-info">
                            <p><i class="fas fa-user"></i> {{ $visit->contact_person }} ({{ $visit->contact_email }})</p>
                            <p><i class="fas fa-calendar-alt"></i> <strong>Scheduled for:</strong> {{ $visit->visit_date->format('Y-m-d \a\t h:i A') }}</p>
                        </div>
                        <div class="purpose">
                            <p><strong>Purpose:</strong> {{ $visit->purpose }}</p>
                        </div>
                        <div class="location">
                            <p><i class="fas fa-map-marker-alt"></i> {{ $visit->location }}</p>
                        </div>
                    </div>
                    <div class="visit-meta-info">
                        <div class="request-date">
                            <p><i class="fas fa-clock"></i> Requested: {{ $visit->requested_date->format('Y-m-d') }}</p>
                        </div>
                        <div class="visit-actions">
                             @if ($visit->status == 'pending')
                                <button type="button" class="btn-action-visit btn-reschedule" onclick="openRescheduleModal({{ $visit->id }}, '{{ $visit->visit_date->format('Y-m-d') }}', '{{ $visit->visit_date->format('H:i') }}')">Reschedule</button>
                                <form action="{{ route('admin.visits.approve', $visit) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-visit btn-approve-visit">Approve</button>
                                </form>
                                <form action="{{ route('admin.visits.reject', $visit) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-visit btn-reject-visit">Reject</button>
                                </form>
                            @elseif ($visit->status == 'approved')
                                <button type="button" class="btn-action-visit btn-view-calendar" onclick="openCalendarModal()">View Calendar</button>
                                <form action="{{ route('admin.visits.confirm', $visit) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-visit btn-send-email">Confirm & Send Details</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; padding: 2rem;">No visit requests found.</p>
            @endforelse
        </div>
    </div>
@endsection

<!-- Calendar Modal -->
<div id="calendarModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Visit Calendar</h3>
            <span class="close" onclick="closeCalendarModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="calendar-view">
                @foreach($visits->groupBy(function($visit) { return $visit->visit_date->format('Y-m'); }) as $yearMonth => $monthVisits)
                    <div class="month-section">
                        <h4>{{ \Carbon\Carbon::parse($yearMonth . '-01')->format('F Y') }}</h4>
                        @foreach($monthVisits->groupBy(function($visit) { return $visit->visit_date->format('Y-m-d'); }) as $date => $dayVisits)
                            <div class="day-section">
                                <h5>{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h5>
                                @foreach($dayVisits as $visit)
                                    <div class="calendar-visit-item">
                                        <div class="visit-time">{{ $visit->visit_date->format('h:i A') }}</div>
                                        <div class="visit-details">
                                            <strong>{{ $visit->company_name }}</strong>
                                            <span class="status-badge-visit status-{{ $visit->status }}">{{ ucfirst($visit->status) }}</span>
                                        </div>
                                        <div class="visit-location">{{ $visit->location }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reschedule Visit</h3>
            <span class="close" onclick="closeRescheduleModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="rescheduleForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="visit_date">Visit Date:</label>
                    <input type="date" id="visit_date" name="visit_date" required>
                </div>
                <div class="form-group">
                    <label for="visit_time">Visit Time:</label>
                    <input type="time" id="visit_time" name="visit_time" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Reschedule Visit</button>
                    <button type="button" class="btn-secondary" onclick="closeRescheduleModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCalendarModal() {
    document.getElementById('calendarModal').classList.add('show');
}

function closeCalendarModal() {
    document.getElementById('calendarModal').classList.remove('show');
}

function openRescheduleModal(visitId, currentDate, currentTime) {
    const form = document.getElementById('rescheduleForm');
    form.action = `/admin/visits/${visitId}/reschedule`;
    document.getElementById('visit_date').value = currentDate;
    document.getElementById('visit_time').value = currentTime;
    document.getElementById('rescheduleModal').classList.add('show');
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').classList.remove('show');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const calendarModal = document.getElementById('calendarModal');
    const rescheduleModal = document.getElementById('rescheduleModal');
    
    if (event.target === calendarModal) {
        closeCalendarModal();
    }
    if (event.target === rescheduleModal) {
        closeRescheduleModal();
    }
}
</script>
