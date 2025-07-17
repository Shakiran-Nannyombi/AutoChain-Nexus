@extends('layouts.dashboard')

@section('title', 'All Customers')

@section('sidebar-content')
    @include('dashboards.customer.sidebar')
@endsection

@section('content')
@php
    $segmentNames = [
        1 => 'At Risk',
        2 => 'High Value Customers',
        3 => 'At Risk Customers',
    ];
@endphp
<div class="content-card">
    <h2 style="color: var(--deep-purple); margin-bottom: 1rem;">
        <i class="fas fa-users"></i> All Customers
    </h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f0f0f0;">
                <th style="padding: 0.5rem; text-align: left;">Name</th>
                <th style="padding: 0.5rem; text-align: left;">Email</th>
                <th style="padding: 0.5rem; text-align: left;">Segment</th>
                <th style="padding: 0.5rem; text-align: left;">Total Purchases</th>
                <th style="padding: 0.5rem; text-align: left;">Total Spent</th>
                <th style="padding: 0.5rem; text-align: left;">Recency (days)</th>
                <th style="padding: 0.5rem; text-align: left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td style="padding: 0.5rem;">{{ $customer->name }}</td>
                <td style="padding: 0.5rem;">{{ $customer->email }}</td>
                <td style="padding: 0.5rem;">{{ $segmentNames[$customer->segment] ?? 'Unsegmented' }}</td>
                <td style="padding: 0.5rem;">{{ $customer->purchases_count }}</td>
                <td style="padding: 0.5rem;">{{ $customer->total_spent ?? 0 }}</td>
                <td style="padding: 0.5rem;">{{ $customer->recency ?? 'N/A' }}</td>
                <td style="padding: 0.5rem;"><a href="{{ route('customer.show', $customer) }}">View</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 