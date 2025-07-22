@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 1rem;"><i class="fas fa-chart-bar"></i> Production Analytics</h2>
        
        <!-- Summary Production Statistics -->
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
            <div style="flex:1; min-width:200px; background: linear-gradient(135deg, var(--accent), #b35400); color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.1rem; opacity: 0.9;">Overall Yield</div>
                <div style="font-size: 2.2rem; font-weight: bold; margin-top: 0.5rem;">{{ $overallYield }}%</div>
            </div>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 2rem;">
            <!-- Items by Production Stage Pie Chart -->
            <div style="flex:1; min-width:320px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem;">
                <h3 style="color: var(--primary); font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Items by Production Stage</h3>
                <div style="margin-top: 1rem;">
                    <canvas id="stageDistributionChart"></canvas>
                </div>
            </div>
            <!-- Stage Duration Analysis Bar Chart -->
            <div style="flex:1; min-width:320px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem;">
                <h3 style="color: var(--accent); font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Average Stage Duration (Minutes)</h3>
                <div style="margin-top: 1rem;">
                    <canvas id="stageDurationChart"></canvas>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
            <!-- Production Rate Over Time Line Chart -->
            <div style="flex:1; min-width:320px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem;">
                <h3 style="color: var(--primary); font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Production Rate Over Time</h3>
                <div style="margin-top: 1rem;">
                    <canvas id="productionRateChart"></canvas>
                </div>
            </div>
            <!-- Failure Trends Over Time Line Chart -->
            <div style="flex:1; min-width:320px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem;">
                <h3 style="color: #b71c1c; font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Failure Trends Over Time</h3>
                <div style="margin-top: 1rem;">
                    <canvas id="failureTrendChart"></canvas>
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