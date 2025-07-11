@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Reports</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Summary</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->title }}</td>
                <td>{{ ucfirst($report->type) }}</td>
                <td>{{ Str::limit($report->summary, 50) }}</td>
                <td>
                    <a href="{{ asset('storage/' . $report->report_file) }}" class="btn btn-sm btn-primary" target="_blank">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
