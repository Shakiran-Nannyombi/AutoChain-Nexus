@extends('layouts.dashboard')

@section('title', 'Order Details')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>Order #{{ $order->id }}</h1>
                <p>Retailer Order Details</p>
            </div>
            <a href="{{ route('vendor.retailer-orders.index') }}" class="button button-gray">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
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
                <div class="info-item">
                    <label>Retailer:</label>
                    <span>{{ $order->retailer->name ?? 'N/A' }}</span>
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

            <!-- Actions -->
            <div class="info-card">
                <h3>Actions</h3>
                <div class="action-buttons">
                    @if($order->status === 'pending')
                        <button class="button button-green confirm-order" data-order-id="{{ $order->id }}">
                            <i class="fas fa-check"></i> Confirm Order
                        </button>
                        <button class="button button-red reject-order" data-order-id="{{ $order->id }}">
                            <i class="fas fa-times"></i> Reject Order
                        </button>
                    @elseif($order->status === 'confirmed')
                        <button class="button button-blue ship-order" data-order-id="{{ $order->id }}">
                            <i class="fas fa-shipping-fast"></i> Ship Order
                        </button>
                    @elseif($order->status === 'shipped')
                        <button class="button button-green deliver-order" data-order-id="{{ $order->id }}">
                            <i class="fas fa-check-double"></i> Mark Delivered
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="notes-section">
            <h3>Order Notes</h3>
            <div class="notes-content">
                @if($order->notes)
                    <div class="notes-text">{{ $order->notes }}</div>
                @else
                    <div class="no-notes">No notes available for this order.</div>
                @endif
            </div>
            <button class="button button-blue" onclick="openNotesModal()">
                <i class="fas fa-edit"></i> Update Notes
            </button>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Order Notes</h2>
            <span class="close">&times;</span>
        </div>
        <form id="updateNotesForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="5" placeholder="Add or update notes for this order...">{{ $order->notes }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-gray" onclick="closeModal('notesModal')">Cancel</button>
                <button type="submit" class="button button-blue">Update Notes</button>
            </div>
        </form>
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
    color: var(--primary);
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
    background: var(--primary);
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

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.button {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
}

.button-green { background: #28a745; color: white; }
.button-red { background: #dc3545; color: white; }
.button-blue { background: #007bff; color: white; }
.button-gray { background: #6c757d; color: white; }

.notes-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
}

.notes-section h3 {
    color: var(--primary);
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

.no-notes {
    color: var(--text-light);
    font-style: italic;
    text-align: center;
    padding: 1rem;
}

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

.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    resize: vertical;
    min-height: 120px;
}
</style>

@push('scripts')
<script>
// Modal functions
function openNotesModal() {
    document.getElementById('notesModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
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

// Update notes
document.getElementById('updateNotesForm').onsubmit = function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/vendor/retailer-orders/{{ $order->id }}/notes`, {
        method: 'PUT',
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
        alert('An error occurred while updating notes.');
    });
    
    closeModal('notesModal');
};

// Action buttons (same as in retailer-orders.blade.php)
let currentOrderId = {{ $order->id }};

// Confirm order
document.querySelectorAll('.confirm-order').forEach(function(btn) {
    btn.onclick = function() {
        if (confirm('Confirm this order?')) {
            fetch(`/vendor/retailer-orders/${currentOrderId}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
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
                alert('An error occurred while confirming the order.');
            });
        }
    }
});

// Reject order
document.querySelectorAll('.reject-order').forEach(function(btn) {
    btn.onclick = function() {
        const reason = prompt('Please provide a reason for rejecting this order:');
        if (reason) {
            fetch(`/vendor/retailer-orders/${currentOrderId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ rejection_reason: reason })
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
        }
    }
});

// Ship order
document.querySelectorAll('.ship-order').forEach(function(btn) {
    btn.onclick = function() {
        if (confirm('Mark this order as shipped?')) {
            fetch(`/vendor/retailer-orders/${currentOrderId}/ship`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
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
                alert('An error occurred while shipping the order.');
            });
        }
    }
});

// Deliver order
document.querySelectorAll('.deliver-order').forEach(function(btn) {
    btn.onclick = function() {
        if (confirm('Mark this order as delivered?')) {
            fetch(`/vendor/retailer-orders/${currentOrderId}/deliver`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
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
                alert('An error occurred while marking the order as delivered.');
            });
        }
    }
});
</script>
@endpush
@endsection 