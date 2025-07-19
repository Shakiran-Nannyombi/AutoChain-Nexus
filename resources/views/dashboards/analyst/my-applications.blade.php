@extends('layouts.dashboard')

@section('title', 'Analyst Dashboard')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2>My Applications to Manufacturers</h2>
    @if($applications->isEmpty())
        <p>You have not applied to any manufacturers yet.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Manufacturer</th>
                <th>Company</th>
                <th>Status</th>
                <th>Applied At</th>
            </tr>
        </thead>
        <tbody>
        @foreach($applications as $app)
            <tr>
                <td>{{ $app->manufacturer_name }}</td>
                <td>{{ $app->manufacturer_company }}</td>
                <td>
                    @if($app->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($app->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($app->created_at)->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection 