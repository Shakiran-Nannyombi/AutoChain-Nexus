@extends('layouts.dashboard')

@section('title', 'Customer Details')

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
        <i class="fas fa-user"></i> {{ $customer->name }}
    </h2>
    <p><strong>Email:</strong> {{ $customer->email }}</p>
    <p><strong>Phone:</strong> {{ $customer->phone }}</p>
    <p><strong>Address:</strong> {{ $customer->address }}</p>
    <p><strong>Segment:</strong> {{ $segmentNames[$customer->segment] ?? 'Unsegmented' }}</p>
    <a href="{{ route('customer.list') }}">&larr; Back to all customers</a>
</div>
@endsection 