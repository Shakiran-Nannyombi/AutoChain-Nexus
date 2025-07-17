@extends('layouts.dashboard')

@section('title', 'Order Confirmation')

@section('sidebar-content')
    @include('dashboards.customer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--success); margin-bottom: 1.5rem;">
        <i class="fas fa-check-circle"></i> Order Placed Successfully!
    </h2>
    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); max-width: 600px; margin: 0 auto;">
        <h3 style="color: var(--primary); margin-bottom: 1rem;">Order Details</h3>
        <ul style="list-style: none; padding: 0;">
            <li><strong>Order ID:</strong> {{ $order->id }}</li>
            <li><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</li>
            <li><strong>Quantity:</strong> {{ $order->quantity }}</li>
            <li><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</li>
            <li><strong>Retailer:</strong> {{ $order->retailer->name ?? 'N/A' }}</li>
            <li><strong>Status:</strong> <span style="color: var(--success); font-weight: 600;">{{ ucfirst($order->status) }}</span></li>
            <li><strong>Order Date:</strong> {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M d, Y H:i') : 'N/A' }}</li>
        </ul>
        <hr style="margin: 1.5rem 0;">
        <h4 style="color: var(--deep-purple);">Customer Info</h4>
        <ul style="list-style: none; padding: 0;">
            <li><strong>Name:</strong> {{ $order->customer_name }}</li>
            <li><strong>Email:</strong> {{ $order->customer_email }}</li>
            <li><strong>Phone:</strong> {{ $order->customer_phone }}</li>
            <li><strong>Address:</strong> {{ $order->customer_address }}</li>
        </ul>
        <div style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('customer.track.order') }}" class="btn btn-success" style="background: var(--success); color: white; padding: 0.7rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">Track Your Order</a>
            <a href="{{ route('customer.browse.products') }}" class="btn btn-primary" style="background: var(--primary); color: white; padding: 0.7rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600; margin-left: 1rem;">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection 