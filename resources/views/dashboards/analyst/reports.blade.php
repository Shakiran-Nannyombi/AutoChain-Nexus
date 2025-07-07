@extends('layouts.dashboard')

@section('title', 'Reports')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
    <h1 class="page-header">Reports Management</h1>
    <div class="content-card">
    <h2 class="page-header"><i class="fas fa-file-alt"></i> Generated Reports</h2>

    @foreach($reports as $report)
        <div class="report-box">
            <h4>{{ $report->title }} <span class="badge">{{ ucfirst($report->type) }}</span></h4>
            <p>{{ $report->summary }}</p>
            <p><strong>For:</strong> {{ ucfirst($report->target_role) }}</p>
            @if($report->report_file)
                <a href="{{ asset('storage/' . $report->report_file) }}" target="_blank">Download</a>
            @endif
            <small>Generated on: {{ $report->created_at->format('M d, Y') }}</small>
            <hr>
        </div>
    @endforeach

    {{ $reports->links() }}
</div>
@endsection 