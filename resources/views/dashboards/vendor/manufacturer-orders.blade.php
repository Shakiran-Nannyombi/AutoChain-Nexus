@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success" style="background: #d1fae5; color: #065f46; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger" style="background: #fee2e2; color: #991b1b; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
        <ul style="margin:0; padding-left:1.2rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="content-card manufacturer-orders">
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--text); letter-spacing: 0.01em;">Manufacturer Orders</h1>
    <div class="subtitle">Create new orders to manufacturers and review your order history.</div>
    <div class="tab-buttons">
        <button class="tab-link active" data-tab="new-order">New Order</button>
        <button class="tab-link" data-tab="my-orders">My Orders</button>
    </div>
    <div id="new-order" class="tab-content active">
        <h3 class="section-title">Create New Order to Manufacturer</h3>
        <form id="newOrderForm" method="POST" action="{{ route('vendor.orders.create') }}" class="order-form">
            @csrf
            <input type="hidden" name="order_type" value="manufacturer">
            <div class="form-group">
                <label for="partner_id">Select Manufacturer</label>
                <select id="partner_id" name="partner_id" required>
                    <option value="">Select Manufacturer</option>
                    @foreach($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}{{ $manufacturer->company ? ' - ' . $manufacturer->company : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="product_id">Select Product</label>
                <select id="product_id" name="product_id" required>
                    <option value="">Select Product</option>
                    @foreach($vendorProducts as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->category }}) - Shs {{ number_format($product->price) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" min="1" required>
            </div>
            <div class="form-group">
                <label for="delivery_date">Delivery Date</label>
                <input type="date" id="delivery_date" name="delivery_date" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label for="delivery_point">Delivery Point</label>
                <select id="delivery_point_select" name="delivery_point_select">
                    <option value="">Select Saved Address</option>
                    @foreach($vendorAddresses as $address)
                        <option value="{{ $address }}">{{ $address }}</option>
                    @endforeach
                    <option value="__new__">Add New Address...</option>
                </select>
                <input type="text" id="delivery_point" name="delivery_point" placeholder="Enter delivery address or location" style="display:none; margin-top:0.5rem;" />
            </div>
            <div class="form-group full-width">
                <label for="special_instructions">Special Instructions (Optional)</label>
                <textarea id="special_instructions" name="special_instructions" rows="3" placeholder="Any special requirements or notes..."></textarea>
            </div>
            <div class="form-actions full-width">
                <button type="button" id="cancelOrder" class="btn btn-secondary">Cancel</button>
                <button type="submit" id="submitOrder" class="btn btn-primary">Create Order</button>
            </div>
        </form>
    </div>
    <div id="my-orders" class="tab-content" style="display:none;">
        <h3 class="section-title">My Manufacturer Orders</h3>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Manufacturer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Ordered At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($manufacturerOrders as $order)
                    <tr>
                        <td>MO-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->manufacturer->name ?? 'Unknown' }}</td>
                        <td>{{ $order->product_name ?? $order->product }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->diffForHumans() : '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No manufacturer orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            tabContents.forEach(c => c.style.display = 'none');
            this.classList.add('active');
            const tab = document.getElementById(this.dataset.tab);
            tab.classList.add('active');
            tab.style.display = '';
        });
    });

    // Dynamic product dropdown based on manufacturer
    const manufacturerSelect = document.getElementById('partner_id');
    const productSelect = document.getElementById('product_id');
    if (manufacturerSelect && productSelect) {
        manufacturerSelect.addEventListener('change', function() {
            const manufacturerId = this.value;
            productSelect.innerHTML = '<option value="">Select Product</option>';
            if (!manufacturerId) return;
            fetch(`/vendor/products/by-manufacturer/${manufacturerId}`)
                .then(res => res.json())
                .then(products => {
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = `${product.name} (${product.category}) - Shs ${Number(product.price).toLocaleString()}`;
                        productSelect.appendChild(option);
                    });
                });
        });
    }

    // Delivery point dropdown logic
    const deliveryPointSelect = document.getElementById('delivery_point_select');
    const deliveryPointInput = document.getElementById('delivery_point');
    if (deliveryPointSelect && deliveryPointInput) {
        deliveryPointSelect.addEventListener('change', function() {
            if (this.value === '__new__') {
                deliveryPointInput.style.display = 'block';
                deliveryPointInput.required = true;
            } else if (this.value) {
                deliveryPointInput.value = this.value;
                deliveryPointInput.style.display = 'none';
                deliveryPointInput.required = false;
            } else {
                deliveryPointInput.value = '';
                deliveryPointInput.style.display = 'none';
                deliveryPointInput.required = false;
            }
        });
        // On submit, ensure delivery_point is set to the selected or entered value
        document.getElementById('newOrderForm').addEventListener('submit', function(e) {
            if (deliveryPointSelect.value && deliveryPointSelect.value !== '__new__') {
                deliveryPointInput.value = deliveryPointSelect.value;
            }
        });
    }
});
</script>
@endpush
@push('styles')
<style>
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #fff;
    display: inline-block;
}
.status-badge.status-pending { background: #f59e0b; }
.status-badge.status-declined { background: #ef4444; }
.status-badge.status-fulfilled, .status-badge.status-accepted { background: #10b981; }
.status-badge.status-cancelled { background: #ef4444; }
.status-badge.status-other { background: #888; }
</style>
@endpush
@endsection 