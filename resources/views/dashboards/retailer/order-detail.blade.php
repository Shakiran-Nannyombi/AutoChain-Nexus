@extends('layouts.dashboard')

@section('title', 'Order Details')

@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="color: var(--deep-purple); font-size: 1.8rem; margin: 0;">
                <i class="fas fa-shopping-bag"></i> Order #{{ $order->id }}
            </h2>
            <p style="color: var(--text-light); margin-top: 0.5rem;">Order Details</p>
        </div>
        <a href="{{ route('retailer.orders') }}" class="button button-gray">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="order-detail-container">
        <div class="order-info-grid">
            <!-- Order Status -->
            <div class="info-card">
                <h3>Order Status</h3>
                <div class="status-display">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="status-timeline">
                    @if($order->ordered_at)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <strong>Ordered:</strong> {{ \Carbon\Carbon::parse($order->ordered_at)->format('M d, Y H:i') }}
                            </div>
                        </div>
                    @endif
                    @if($order->confirmed_at)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <strong>Confirmed:</strong> {{ \Carbon\Carbon::parse($order->confirmed_at)->format('M d, Y H:i') }}
                            </div>
                        </div>
                    @endif
                    @if($order->shipped_at)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <strong>Shipped:</strong> {{ \Carbon\Carbon::parse($order->shipped_at)->format('M d, Y H:i') }}
                            </div>
                        </div>
                    @endif
                    @if($order->delivered_at)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <strong>Delivered:</strong> {{ \Carbon\Carbon::parse($order->delivered_at)->format('M d, Y H:i') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="info-card">
                <h3>Customer Information</h3>
                <div class="info-item">
                    <label>Customer Name:</label>
                    <span>{{ $order->customer_name }}</span>
                </div>
            </div>

            <!-- Product Information -->
            <div class="info-card">
                <h3>Product Details</h3>
                <div class="info-item">
                    <label>Car Model:</label>
                    <span>{{ $order->car_model }}</span>
                </div>
                <div class="info-item">
                    <label>Quantity:</label>
                    <span>{{ $order->quantity }}</span>
                </div>
                @if($order->total_amount)
                <div class="info-item">
                    <label>Total Amount:</label>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
                @endif
            </div>

            <!-- Vendor Information -->
            <div class="info-card">
                <h3>Vendor Information</h3>
                <div class="info-item">
                    <label>Vendor Name:</label>
                    <span>{{ $order->vendor->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Vendor Company:</label>
                    <span>{{ $order->vendor->company ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Vendor Email:</label>
                    <span>{{ $order->vendor->email ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($order->notes)
        <div class="notes-section">
            <h3>Order Notes</h3>
            <div class="notes-content">
                <div class="notes-text">{{ $order->notes }}</div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.order-detail-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 2rem;
    margin-top: 1rem;
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
}

.info-card h3 {
    color: var(--deep-purple);
    margin-bottom: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.status-display {
    margin-bottom: 1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1ecf1; color: #0c5460; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #d1e7dd; color: #0f5132; }
.status-rejected { background: #f8d7da; color: #721c24; }

.status-timeline {
    margin-top: 1rem;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.timeline-dot {
    width: 8px;
    height: 8px;
    background: var(--deep-purple);
    border-radius: 50%;
    margin-right: 0.75rem;
}

.timeline-content {
    font-size: 0.9rem;
    color: var(--text-dark);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-weight: 600;
    color: var(--text-dark);
}

.info-item span {
    color: var(--text-light);
}

.notes-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
}

.notes-section h3 {
    color: var(--deep-purple);
    margin-bottom: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.notes-content {
    margin-bottom: 1rem;
}

.notes-text {
    background: white;
    padding: 1rem;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    white-space: pre-wrap;
    line-height: 1.5;
}

.button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.button-gray { background: #6c757d; color: white; }
</style>
@endsection 