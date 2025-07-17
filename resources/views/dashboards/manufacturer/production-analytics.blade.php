@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-chart-bar"></i> Production Analytics</h2>
        <!-- All existing content below this line should be inside this content-card div -->
    <!-- Summary Production Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-sm text-gray-600">Total Items Processed</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalItemsProcessed }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-sm text-gray-600">Total Completed Items</p>
            <p class="text-3xl font-bold text-green-600">{{ $totalCompletedItems }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-sm text-gray-600">Total Failed Items</p>
            <p class="text-3xl font-bold text-red-600">{{ $totalFailedItems }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-sm text-gray-600">Overall Yield</p>
            <p class="text-3xl font-bold text-purple-600">{{ $overallYield }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Items by Production Stage Pie Chart -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold">Items by Production Stage</h3>
                <div class="mt-4">
                    <canvas id="stageDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Stage Duration Analysis Bar Chart -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold">Average Stage Duration (Minutes)</h3>
                <div class="mt-4">
                    <canvas id="stageDurationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Production Rate Over Time Line Chart -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold">Production Rate Over Time</h3>
                <div class="mt-4">
                    <canvas id="productionRateChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Failure Trends Over Time Line Chart -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold">Failure Trends Over Time</h3>
                <div class="mt-4">
                    <canvas id="failureTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Items by Production Stage Pie Chart
    const stageDistributionCtx = document.getElementById('stageDistributionChart').getContext('2d');
    new Chart(stageDistributionCtx, {
        type: 'pie',
        data: {
            labels: JSON.parse('{!! json_encode($stageLabels) !!}'),
            datasets: [{
                data: JSON.parse('{!! json_encode($stageData) !!}'),
                backgroundColor: ['#A78BFA', '#6366F1', '#3B82F6', '#10B981', '#06B6D4', '#EF4444', '#6B7280'],
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
                    text: 'Distribution of Items Across Stages'
                }
            }
        }
    });

    // Average Stage Duration Bar Chart
    const stageDurationCtx = document.getElementById('stageDurationChart').getContext('2d');
    const averageStageDurations = JSON.parse('{!! json_encode($averageStageDurations) !!}');
    const stageDurationLabels = Object.keys(averageStageDurations).map(key => key.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase()));
    const stageDurationData = Object.values(averageStageDurations);

    new Chart(stageDurationCtx, {
        type: 'bar',
        data: {
            labels: stageDurationLabels,
            datasets: [{
                label: 'Average Duration (Minutes)',
                data: stageDurationData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Average Time Spent in Each Stage'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Minutes'
                    }
                }
            }
        }
    });

    // Production Rate Over Time Line Chart
    const productionRateCtx = document.getElementById('productionRateChart').getContext('2d');
    new Chart(productionRateCtx, {
        type: 'line',
        data: {
            labels: JSON.parse('{!! json_encode($productionRateLabels) !!}'),
            datasets: [{
                label: 'Completed Items',
                data: JSON.parse('{!! json_encode($productionRateData) !!}'),
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Daily Completed Items'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Items'
                    }
                }
            }
        }
    });

    // Failure Trends Over Time Line Chart
    const failureTrendCtx = document.getElementById('failureTrendChart').getContext('2d');
    new Chart(failureTrendCtx, {
        type: 'line',
        data: {
            labels: JSON.parse('{!! json_encode($failureTrendLabels) !!}'),
            datasets: [{
                label: 'Failed Items',
                data: JSON.parse('{!! json_encode($failureTrendData) !!}'),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Daily Failed Items'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Items'
                    }
                }
            }
        }
    });
</script>
@endpush