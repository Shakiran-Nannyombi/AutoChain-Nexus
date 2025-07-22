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
  <div class="content-card">
    <h2 class="page-title" style="color: var(--text) !important; font-size: 1.8rem; margin-bottom: 1.5rem;">
        <i class="fas fa-calendar-check"></i> Visit Scheduling & Management
    </h2>
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ session('error') }}</div>
    @endif
    <br>

    <!-- Stat Cards -->
    <div class="stat-cards-grid" style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #174ea6 0%, #2563eb 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon yellow" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-clock"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Pending Requests</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['pending'] }}</span>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #15803d 0%, #166534 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon green" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Approved Visits</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['approved'] }}</span>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon red" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-times-circle"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Rejected Visits</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['rejected'] }}</span>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #eab308 0%, #a16207 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon blue" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-robot"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Auto-Scheduled</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['auto_scheduled'] }}</span>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #6d28d9 0%, #4c1d95 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon light-green" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-clipboard-check"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Completed This Week</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['this_week'] }}</span>
            </div>
        </div>
    </div>

    <!-- Visit Status Tabs -->
    <div class="visit-tabs" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <button type="button" class="tab-btn active" onclick="showVisits('all')">All</button>
        <button type="button" class="tab-btn" onclick="showVisits('pending')">Pending</button>
        <button type="button" class="tab-btn" onclick="showVisits('approved')">Approved</button>
        <button type="button" class="tab-btn" onclick="showVisits('completed')">Completed</button>
    </div>
    <style>
        .tab-btn { padding: 0.5rem 1.5rem; border: none; background: #eee; cursor: pointer; border-radius: 5px; font-weight: 600; color: #16610E; transition: background 0.2s, color 0.2s; }
        .tab-btn.active { background: var(--primary, #16610E); color: #fff; }
    </style>

    <!-- Facility Visit Requests -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h2>Facility Visit Requests</h2>
        </div>
        <div class="card-body">
            @forelse ($visits as $visit)
                <div class="visit-card" data-status="{{ $visit->status }}">
                    <div class="visit-main-info">
                        <div class="visit-header">
                            <h3 class="company-name" style="color: black">{{ $visit->company_name }}</h3>
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
                                <button type="button" class="btn-action-visit btn-edit-visit" onclick="openEditVisitModal({{ $visit->id }})">Edit</button>
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
                                @if ($visit->user && $visit->user->role === 'vendor')
                                <form action="{{ route('admin.visits.complete', $visit) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-visit btn-complete-visit" onclick="return confirm('Mark this visit as completed? This will approve the vendor.')">Mark as Completed</button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; padding: 2rem;">No visit requests found.</p>
            @endforelse
        </div>
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

function showVisits(status) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    if (status === 'all') {
        document.querySelector('.tab-btn:nth-child(1)').classList.add('active');
    } else if (status === 'pending') {
        document.querySelector('.tab-btn:nth-child(2)').classList.add('active');
    } else if (status === 'approved') {
        document.querySelector('.tab-btn:nth-child(3)').classList.add('active');
    } else if (status === 'completed') {
        document.querySelector('.tab-btn:nth-child(4)').classList.add('active');
    }
    document.querySelectorAll('.visit-card').forEach(card => {
        if (status === 'all') {
            card.style.display = '';
        } else if (status === 'approved') {
            card.style.display = (card.dataset.status === 'approved') ? '' : 'none';
        } else {
            card.style.display = (card.dataset.status === status) ? '' : 'none';
        }
    });
}
document.addEventListener('DOMContentLoaded', function () {
    showVisits('all');
});

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
