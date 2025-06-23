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

    <!-- User Sessions Bar Graphs -->
    <div class="chart-row">
        <div class="chart-container" style="width: 100%; max-width: 700px;">
            <h3 style="margin-bottom: 1rem; font-size: 1.2rem;">User Sessions Per Month (Last 12 Months)</h3>
            <canvas id="monthlySessionsChart"></canvas>
        </div>
        <div class="chart-container" style="width: 100%; max-width: 400px;">
            <h3 style="margin-bottom: 1rem; font-size: 1.2rem;">User Sessions Per Year (Last 5 Years)</h3>
            <canvas id="annualSessionsChart"></canvas>
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
                    label: 'User Count',
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
                    },
                    title: {
                        display: true,
                        text: 'User Distribution by Role'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' users';
                            }
                        }
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
                plugins: {
                    title: {
                        display: true,
                        text: 'New Users Registered Per Day'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date (Day)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Users'
                        }
                    }
                }
            }
        });

        // User Sessions Per Month (Bar)
        const monthlySessionsCtx = document.getElementById('monthlySessionsChart').getContext('2d');
        const monthlySessionsData = @json($monthlySessions);
        const monthlyLabels = monthlySessionsData.map(d => d.month);
        const monthlyCounts = monthlySessionsData.map(d => d.sessions);
        new Chart(monthlySessionsCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Sessions',
                    data: monthlyCounts,
                    backgroundColor: '#38c172',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'User Sessions Per Month'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month (YYYY-MM)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Sessions'
                        }
                    }
                }
            }
        });

        // User Sessions Per Year (Bar)
        const annualSessionsCtx = document.getElementById('annualSessionsChart').getContext('2d');
        const annualSessionsData = @json($annualSessions);
        const annualLabels = annualSessionsData.map(d => d.year);
        const annualCounts = annualSessionsData.map(d => d.sessions);
        new Chart(annualSessionsCtx, {
            type: 'bar',
            data: {
                labels: annualLabels,
                datasets: [{
                    label: 'Sessions',
                    data: annualCounts,
                    backgroundColor: '#3490dc',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'User Sessions Per Year'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Year (YYYY)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Sessions'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
