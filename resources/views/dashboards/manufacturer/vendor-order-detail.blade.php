@extends('layouts.dashboard')

@section('title', 'Vendor Order Detail')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 class="page-title" style="color: var(--primary); font-size: 2rem; font-weight: bold; margin-bottom: 1rem;"><i class="fas fa-file-alt"></i> Vendor Order Detail</h2>
    <table class="table table-bordered" style="width:100%; max-width:600px;">
        <tr><th>Order ID</th><td>#{{ $order->id }}</td></tr>
        <tr><th>Vendor</th><td>{{ $order->vendor->name ?? 'N/A' }}<br><span style="color:#888;">{{ $order->vendor->email ?? '' }}</span></td></tr>
        <tr><th>Product</th><td>{{ $order->product_name ?? $order->product }}</td></tr>
        <tr><th>Quantity</th><td>{{ $order->quantity }}</td></tr>
        <tr><th>Total Amount</th><td>shs {{ number_format($order->total_amount, 2) }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($order->status) }}</td></tr>
        <tr><th>Ordered At</th><td>{{ $order->ordered_at ? $order->ordered_at->format('Y-m-d H:i') : '' }}</td></tr>
    </table>
    <a href="{{ route('manufacturer.orders') }}" class="button" style="margin-top:1.5rem;">Back to Orders</a>
</div>
@endsection 