@extends('layouts.dashboard')

@section('title', 'Customer Segmentation')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card" style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow); margin-top: 2rem; max-width: 900px; margin-left: auto; margin-right: auto;">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 2rem; font-weight: 800;">
        <i class="fas fa-users"></i> Customer Segment Distribution
    </h2>
    <div>
        @if(isset($customerSegmentCounts) && count($customerSegmentCounts) > 0)
            <ul style="list-style: none; padding: 0;">
                @foreach($customerSegmentCounts as $seg)
                    <li style="margin-bottom: 0.5rem;">
                        <strong>{{ $segmentNames[$seg->segment] ?? 'Unsegmented' }}:</strong> {{ $seg->count }} customers
                    </li>
                @endforeach
            </ul>
        @else
            <p>No segmentation data available.</p>
        @endif
    </div>
    @if(isset($segmentSummaries) && count($segmentSummaries) > 0)
    <div style="margin-top: 2rem;">
        <h4 style="color: var(--primary);">Segment Summary Table</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f0f0f0;">
                    <th style="padding: 0.5rem; text-align: left;">Segment</th>
                    <th style="padding: 0.5rem; text-align: left;">Avg. Total Spent</th>
                    <th style="padding: 0.5rem; text-align: left;">Avg. Purchases</th>
                    <th style="padding: 0.5rem; text-align: left;">Avg. Recency (days)</th>
                    <th style="padding: 0.5rem; text-align: left;"># Customers</th>
                </tr>
            </thead>
            <tbody>
                @foreach($segmentSummaries as $summary)
                <tr>
                    <td style="padding: 0.5rem;">{{ $segmentNames[$summary->segment] ?? 'Unsegmented' }}</td>
                    <td style="padding: 0.5rem;">${{ number_format($summary->avg_total_spent, 2) }}</td>
                    <td style="padding: 0.5rem;">{{ number_format($summary->avg_purchases, 2) }}</td>
                    <td style="padding: 0.5rem;">{{ $summary->avg_recency !== null ? number_format($summary->avg_recency, 1) : 'N/A' }}</td>
                    <td style="padding: 0.5rem;">{{ $summary->count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection 