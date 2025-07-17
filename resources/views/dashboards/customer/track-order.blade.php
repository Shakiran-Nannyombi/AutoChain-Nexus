@extends('layouts.dashboard')

@section('title', 'Track Your Order')

@section('sidebar-content')
    @include('dashboards.customer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem;">
        <i class="fas fa-truck"></i> Track Your Order
    </h2>
    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); max-width: 600px; margin: 0 auto;">
        <form method="POST" action="{{ route('customer.track.order.post') }}" style="margin-bottom: 2rem;">
            @csrf
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Order ID</label>
                    <input type="text" name="order_id" value="{{ old('order_id') }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <button type="submit" style="background: var(--primary); color: white; padding: 0.6rem 1.2rem; border: none; border-radius: 4px; font-weight: 600;">Track</button>
                </div>
            </div>
        </form>
        @if($error)
            <div style="color: #dc3545; margin-bottom: 1rem; font-weight: 600;">
                <i class="fas fa-exclamation-circle"></i> {{ $error }}
            </div>
        @endif
        @if($order)
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                <h4 style="color: var(--success); margin-bottom: 1rem;">
                    <i class="fas fa-check-circle"></i> Order Found
                </h4>
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
            </div>
        @endif
    </div>
</div>
@endsection 