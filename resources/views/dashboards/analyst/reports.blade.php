@extends('layouts.dashboard')

@section('title', 'Reports')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card" style="padding: 2.5rem 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; margin-bottom: 2.2rem;">
        <h2 style="color: var(--primary); font-size: 2rem; font-weight: 800; margin-bottom: 0; display: flex; align-items: center; gap: 0.7rem; letter-spacing: 0.5px;">
            <i class="fas fa-file-alt" style="color: var(--primary);"></i> Analyst Reports
        </h2>
        @php
        $availableDates = isset($reports) ? $reports->pluck('created_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->unique()->values() : collect();
        $selectedDate = request('filter_date');
    @endphp
    <form method="GET" style="margin-bottom: 1.5rem; display: flex; gap: 1.2rem; align-items: center;">
        <label for="filter_date" style="font-weight: 600; color: var(--primary);">Filter by Date:</label>
        <select name="filter_date" id="filter_date" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 6px; border: 1.5px solid var(--primary-light);">
            <option value="">All Dates</option>
            @foreach($availableDates as $date)
                <option value="{{ $date }}" {{ $selectedDate == $date ? 'selected' : '' }}>{{ $date }}</option>
            @endforeach
        </select>
    </form>

        <a href="{{ route('analyst.reports.create') }}" style="background:var(--text2); color:black; border-radius: 8px; padding: 0.9rem 2rem; font-weight: 700; font-size: 1.1rem; text-decoration: none; box-shadow: 0 2px 12px rgba(22,97,14,0.08); display: flex; align-items: center; gap: 0.7rem; transition: background 0.18s, box-shadow 0.18s;">
            <i class="fas fa-upload"></i> Upload New Report
        </a>
    </div>
        <!-- Main Content -->
        <section style="flex: 3 1 500px; min-width: 320px;">
            <div style="margin-bottom: 2.2rem;">
                <h3 style="color: var(--secondary); font-size: 1.3rem; font-weight: 700; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-clock"></i> Recent Reports
                </h3>
                {{-- When displaying reports, filter by selected date if set --}}
                @php
                    $filteredReports = $reports;
                    if ($selectedDate) {
                        $filteredReports = $reports->filter(fn($r) => \Carbon\Carbon::parse($r->created_at)->format('Y-m-d') === $selectedDate);
                    }
                @endphp
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
                    @foreach($filteredReports as $report)
                        <div style="background: var(--background); border-radius: 10px; box-shadow: 0 1px 8px rgba(22,97,14,0.04); padding: 1.2rem 1rem; display: flex; flex-direction: column; gap: 0.7rem;">
                            <div style="display: flex; align-items: center; gap: 0.7rem;">
                                <i class="fas fa-chart-bar" style="color: var(--primary); font-size: 1.5rem;"></i>
                                <span style="font-weight: 700; color: var(--primary);">{{ $report->title }}</span>
                            </div>
                            <div style="color: #555; font-size: 0.98rem;">{{ $report->description }}</div>
                            <div style="font-size: 0.93em; color: #888;">{{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }}</div>
                            <div style="display: flex; gap: 0.7rem; margin-top: 0.5rem;">
                                <a href="#" style="background: var(--primary); color: #fff; border-radius: 6px; padding: 0.4rem 1.1rem; font-size: 0.98rem; font-weight: 600; text-decoration: none;">View</a>
                                <a href="#" style="background: var(--secondary); color: #fff; border-radius: 6px; padding: 0.4rem 1.1rem; font-size: 0.98rem; font-weight: 600; text-decoration: none;">Download</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @php
                $typeCounts = isset($reports) ? $reports->groupBy('type')->map->count() : collect();
                $totalReports = isset($reports) ? $reports->count() : 0;
                $mostActiveDay = isset($reports) ? $reports->groupBy(fn($r) => \Carbon\Carbon::parse($r->created_at)->format('Y-m-d'))->sortDesc()->keys()->first() : null;
            @endphp
            <div style="margin-top: 3.5rem;">
                <h3 style="color: var(--primary); font-size: 1.3rem; font-weight: 700; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-chart-pie"></i> Reports Overview
                </h3>
                <div style=" border-radius: 14px; padding: 2.5rem 1.5rem; margin-bottom: 2.2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 180px;">
                    <canvas id="reportTypeChart" width="400" height="120"></canvas>
                    <div style="margin-top: 1.5rem; color: #222; font-size: 1.08rem;">
                        <strong>Total Reports:</strong> {{ $totalReports }}<br>
                        <strong>Most Active Day:</strong> {{ $mostActiveDay ?? 'N/A' }}
                    </div>
                </div>
            </div>
            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('reportTypeChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($typeCounts->keys()) !!},
                            datasets: [{
                                label: 'Report Count',
                                data: {!! json_encode($typeCounts->values()) !!},
                                backgroundColor: ['#16610E', '#F97A00', '#FED16A'],
                            }]
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                </script>
            @endpush
        </section>
        </div>
</div>
@endsection 