@extends('layouts.dashboard')

@section('title', 'Analytics')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<div class="analytics-page">
    <h1 class='page-title' style='margin-bottom: 1.5rem;'>Analytics Dashboard</h1>

    <!-- Stats Cards -->
    <div class="stat-card-row">
        <div class="stat-card">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="info">
                <div class="title">Total Users</div>
                <div class="value">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <div class="info">
                <div class="title">Pending Approvals</div>
                <div class="value">{{ $pendingUsers }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <div class="info">
                <div class="title">Active Users</div>
                <div class="value">{{ $approvedUsers }}</div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-row">
        <div class="chart-container">
            <h3 style="margin-bottom: 1rem; font-size: 1.2rem;">User Roles</h3>
            <canvas id="userRolesChart"></canvas>
        </div>
        <div class="chart-container">
            <h3 style="margin-bottom: 1rem; font-size: 1.2rem;">New Users (Last 7 Days)</h3>
            <canvas id="userRegistrationChart"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="activity-feed">
        <h3 style="margin-bottom: 1rem; font-size: 1.2rem;">Recent Activity</h3>
        @forelse ($recentActivities as $activity)
            <div class="feed-item">
                <div class="icon"><i class="fas fa-user-plus"></i></div>
                <div class="details">
                    <strong>{{ $activity->name }}</strong> registered as a new {{ $activity->role }}.
                </div>
                <div class="timestamp">{{ $activity->created_at->diffForHumans() }}</div>
            </div>
        @empty
            <div class="text-center" style="padding: 2rem;">No recent activity.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // User Roles Chart (Pie)
        const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
        const userRolesData = @json($usersByRole);
        
        new Chart(userRolesCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(userRolesData).map(role => role.charAt(0).toUpperCase() + role.slice(1)),
                datasets: [{
                    label: 'User Roles',
                    data: Object.values(userRolesData),
                    backgroundColor: [
                        '#3490dc', '#f6993f', '#38c172', '#e3342f', '#6574cd'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // User Registration Chart (Line)
        const userRegistrationCtx = document.getElementById('userRegistrationChart').getContext('2d');
        const userRegistrationData = @json($userRegistrationData);

        const labels = userRegistrationData.map(d => new Date(d.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const data = userRegistrationData.map(d => d.registrations);

        new Chart(userRegistrationCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'New Registrations',
                    data: data,
                    borderColor: '#3490dc',
                    backgroundColor: 'rgba(52, 144, 220, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
