@extends('layouts.dashboard')

@section('title', 'Inventory-reports')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
    <h1 class="page-header">Inventory Reports</h1>
    <!DOCTYPE html>
<html>
<head>
    <title>{{ $report->title }}</title>
</head>
<body>
    <h1>{{ $report->title }}</h1>
    <p><strong>Type:</strong> {{ ucfirst($report->type) }}</p>
    <p><strong>Target:</strong> {{ ucfirst($report->target_role) }}</p>
    <p>{{ $report->summary }}</p>
    <small>Generated on: {{ $report->created_at->format('M d, Y') }}</small>
</body>
</html>

@endsection 