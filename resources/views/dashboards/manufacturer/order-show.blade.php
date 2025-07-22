@extends('layouts.dashboard')

@section('title', 'Manufacturer Order Details')
@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manufacturer.css') }}">
@endpush

@section('content')
<div class="content-card" style="max-width: 600px; margin: 2rem auto;">
    <h2 style="color: var(--primary); font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem;">Order Details</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <tr><th style="text-align:left; padding: 8px;">Order ID</th><td style="padding: 8px;">{{ $order->id }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Vendor ID</th><td style="padding: 8px;">{{ $order->vendor_id }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Product</th><td style="padding: 8px;">{{ $order->product }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Quantity</th><td style="padding: 8px;">{{ $order->quantity }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Unit Price</th><td style="padding: 8px;">{{ $order->unit_price }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Total Amount</th><td style="padding: 8px;">{{ $order->total_amount }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Status</th><td style="padding: 8px;">{{ ucfirst($order->status) }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Ordered At</th><td style="padding: 8px;">{{ $order->ordered_at }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Created At</th><td style="padding: 8px;">{{ $order->created_at }}</td></tr>
        <tr><th style="text-align:left; padding: 8px;">Updated At</th><td style="padding: 8px;">{{ $order->updated_at }}</td></tr>
    </table>
    <div style="margin-top: 2rem;">
        <a href="{{ route('manufacturer.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>
@endsection 