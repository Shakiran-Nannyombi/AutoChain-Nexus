@extends('layouts.dashboard')
@section('content')
<div class="content-card">
    <h2>Analyst Applications</h2>
    @if($applications->isEmpty())
        <p>No analyst applications at this time.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Analyst</th>
                <th>Company</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($applications as $app)
            <tr>
                <td>
                    <img src="{{ asset($app->analyst_photo ?? 'images/profile/analyst.jpeg') }}" alt="photo" width="32" class="rounded-circle me-2">
                    {{ $app->analyst_name }}
                </td>
                <td>{{ $app->analyst_company }}</td>
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
                <td>
                    @if($app->status == 'pending')
                        <a href="{{ route('manufacturer.approveAnalyst', $app->id) }}" class="btn btn-success btn-sm">Approve</a>
                        <a href="{{ route('manufacturer.rejectAnalyst', $app->id) }}" class="btn btn-danger btn-sm">Reject</a>
                    @endif
                    <a href="{{ route('manufacturer.viewAnalystPortfolio', $app->analyst_id) }}" class="btn btn-info btn-sm">View Portfolio</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection 