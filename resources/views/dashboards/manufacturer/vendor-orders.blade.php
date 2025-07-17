@extends('layouts.dashboard')

@section('title', 'Vendor Orders Management')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin: 0;">
            <i class="fas fa-shopping-cart"></i> Vendor Orders Management
        </h2>
    </div>

    <div class="orders-container">
        <div class="orders-header">
            <div class="orders-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $vendorOrders->where('status', 'pending')->count() }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $vendorOrders->where('status', 'accepted')->count() }}</div>
                    <div class="stat-label">Accepted</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $vendorOrders->where('status', 'rejected')->count() }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $vendorOrders->count() }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
        </div>

        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Vendor</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Ordered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendorOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                <div>
                                    <strong>{{ $order->vendor->name ?? 'N/A' }}</strong><br>
                                    <small>{{ $order->vendor->email ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->product_name ?? 'N/A' }}</strong><br>
                                    <small>{{ $order->product_category ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>{{ $order->quantity }}</td>
                            <td>${{ number_format($order->total_amount ?? 0, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('manufacturer.vendor-orders.show', $order->id) }}" 
                                       class="button button-blue">View</a>
                                    
                                    @if($order->status === 'pending')
                                        <button class="button button-green accept-order" 
                                                data-order-id="{{ $order->id }}">Accept</button>
                                        <button class="button button-red reject-order" 
                                                data-order-id="{{ $order->id }}">Reject</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No vendor orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Accept Order Modal -->
<div id="acceptOrderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Accept Order</h2>
            <span class="close">&times;</span>
        </div>
        <form id="acceptOrderForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="unit_price">Unit Price ($)</label>
                    <input type="number" id="unit_price" name="unit_price" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="expected_delivery_date">Expected Delivery Date</label>
                    <input type="date" id="expected_delivery_date" name="expected_delivery_date" 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                <div class="form-group">
                    <label for="notes">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Add any notes about this order..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-gray" onclick="closeModal('acceptOrderModal')">Cancel</button>
                <button type="submit" class="button button-green">Accept Order</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Order Modal -->
<div id="rejectOrderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Reject Order</h2>
            <span class="close">&times;</span>
        </div>
        <form id="rejectOrderForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason *</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" 
                              placeholder="Please provide a reason for rejecting this order..." required></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Additional Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" 
                              placeholder="Add any additional notes..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-gray" onclick="closeModal('rejectOrderModal')">Cancel</button>
                <button type="submit" class="button button-red">Reject Order</button>
            </div>
        </form>
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
    color: var(--primary);
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
    color: var(--primary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-accepted { background: #d1ecf1; color: #0c5460; }
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
.button-green { background: #28a745; color: white; }
.button-red { background: #dc3545; color: white; }
.button-gray { background: #6c757d; color: white; }

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: var(--primary);
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-dark);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}
</style>

@push('scripts')
<script>
let currentOrderId = null;

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.getElementById(modalId).querySelector('form').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Close modal when clicking X
document.querySelectorAll('.close').forEach(function(closeBtn) {
    closeBtn.onclick = function() {
        closeBtn.closest('.modal').style.display = 'none';
    }
});

// Accept order
document.querySelectorAll('.accept-order').forEach(function(btn) {
    btn.onclick = function() {
        currentOrderId = this.dataset.orderId;
        openModal('acceptOrderModal');
    }
});

document.getElementById('acceptOrderForm').onsubmit = function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/manufacturer/vendor-orders/${currentOrderId}/accept`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while accepting the order.');
    });
    
    closeModal('acceptOrderModal');
};

// Reject order
document.querySelectorAll('.reject-order').forEach(function(btn) {
    btn.onclick = function() {
        currentOrderId = this.dataset.orderId;
        openModal('rejectOrderModal');
    }
});

document.getElementById('rejectOrderForm').onsubmit = function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/manufacturer/vendor-orders/${currentOrderId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rejecting the order.');
    });
    
    closeModal('rejectOrderModal');
};
</script>
@endpush
@endsection 