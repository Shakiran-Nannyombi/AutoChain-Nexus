@extends('layouts.dashboard')

@section('title', 'My Orders')

@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: var(--deep-purple); font-size: 1.8rem; margin: 0;">
            <i class="fas fa-shopping-bag"></i> My Orders
        </h2>
        <a href="{{ route('retailer.order-placement') }}" class="button" style="background: #007bff; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
            <i class="fas fa-plus"></i> Place New Order
        </a>
    </div>

    <div class="orders-container">
        <div class="orders-header">
            <div class="orders-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $orders->where('status', 'pending')->count() }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $orders->where('status', 'confirmed')->count() }}</div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $orders->where('status', 'shipped')->count() }}</div>
                    <div class="stat-label">Shipped</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $orders->where('status', 'delivered')->count() }}</div>
                    <div class="stat-label">Delivered</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $orders->where('status', 'rejected')->count() }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
            </div>
        </div>

        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Vendor</th>
                        <th>Ordered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->car_model }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->vendor->name ?? 'N/A' }}</td>
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('retailer.order-detail', $order->id) }}" 
                                       class="button button-blue">View Details</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.orders-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    overflow: hidden;
}

.orders-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.orders-stats {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.stat-card {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    min-width: 100px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--deep-purple);
}

.stat-label {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-top: 0.25rem;
}

.orders-table-container {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th,
.orders-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
}

.orders-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--deep-purple);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1ecf1; color: #0c5460; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #d1e7dd; color: #0f5132; }
.status-rejected { background: #f8d7da; color: #721c24; }

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.button-blue { background: #007bff; color: white; }
</style>
@endsection 