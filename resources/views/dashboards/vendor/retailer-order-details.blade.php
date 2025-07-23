@extends('layouts.dashboard')

@section('title', 'Order Management')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>Order Management</h1>
                <p>View and manage all retailer orders</p>
            </div>
            <a href="{{ route('vendor.retailer-orders.index') }}" class="button button-gray">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
        
    @if ($retailerOrders->isEmpty())
        <p>No retailer orders found.</p>
    @else
        <!-- Orders Table -->
        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Retailer</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($retailerOrders as $order)
                        <tr data-order-id="{{ $order->id }}" class="{{ $selectedOrder && $selectedOrder->id == $order->id ? 'active' : '' }}">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->retailer->name ?? 'N/A' }}</td>
                            <td>{{ $order->car_model }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                @if($order->status === 'pending')
                                    <button class="button button-sm button-green confirm-order" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-check"></i> Confirm
                                    </button>
                                    <button class="button button-sm button-red reject-order" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @elseif($order->status === 'confirmed')
                                    <button class="button button-sm button-blue ship-order" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-shipping-fast"></i> Ship
                                    </button>
                                @elseif($order->status === 'shipped')
                                    <button class="button button-sm button-green deliver-order" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-check-double"></i> Deliver
                                    </button>
                                @endif
                                <button class="button button-sm button-gray view-details" data-order-id="{{ $order->id }}">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Details Panel -->
        <div class="order-details-panel">
            @if($selectedOrder)
                <div class="order-detail-container">
                    <div class="order-info-grid">
                        <!-- Order Status Timeline -->
                        <div class="info-card">
                            <h3><i class="fas fa-history"></i> Order Status</h3>
                            <div class="status-display">
                                <span class="status-badge status-{{ $selectedOrder->status }}">
                                    {{ ucfirst($selectedOrder->status) }}
                                </span>
                            </div>
                            <div class="status-timeline">
                                @if($selectedOrder->ordered_at)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <strong>Ordered:</strong> {{ \Carbon\Carbon::parse($selectedOrder->ordered_at)->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                                @if($selectedOrder->confirmed_at)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <strong>Confirmed:</strong> {{ \Carbon\Carbon::parse($selectedOrder->confirmed_at)->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                                @if($selectedOrder->shipped_at)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <strong>Shipped:</strong> {{ \Carbon\Carbon::parse($selectedOrder->shipped_at)->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                                @if($selectedOrder->delivered_at)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <strong>Delivered:</strong> {{ \Carbon\Carbon::parse($selectedOrder->delivered_at)->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="info-card">
                            <h3><i class="fas fa-user"></i> Customer Information</h3>
                            <div class="info-item">
                                <label>Customer Name:</label>
                                <span>{{ $selectedOrder->customer_name }}</span>
                            </div>
                            <div class="info-item">
                                <label>Retailer:</label>
                                <span>{{ $selectedOrder->retailer->name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="info-card">
                            <h3><i class="fas fa-box"></i> Product Details</h3>
                            <div class="info-item">
                                <label>Car Model:</label>
                                <span>{{ $selectedOrder->car_model }}</span>
                            </div>
                            <div class="info-item">
                                <label>Quantity:</label>
                                <span>{{ $selectedOrder->quantity }}</span>
                            </div>
                            @if($selectedOrder->total_amount)
                            <div class="info-item">
                                <label>Total Amount:</label>
                                <span>${{ number_format($selectedOrder->total_amount, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="notes-section">
                        <h3><i class="fas fa-sticky-note"></i> Order Notes</h3>
                        <div class="notes-content">
                            @if($selectedOrder->notes)
                                <div class="notes-text">{{ $selectedOrder->notes }}</div>
                            @else
                                <div class="no-notes">No notes available for this order.</div>
                            @endif
                        </div>
                        <button class="button button-blue" onclick="openNotesModal('{{ $selectedOrder->id }}')">
                            <i class="fas fa-edit"></i> Update Notes
                        </button>
                    </div>
                </div>
            @else
                <div class="no-order-selected">
                    <i class="fas fa-info-circle"></i>
                    <p>Select an order to view details</p>
                </div>
            @endif
        </div>
    @endif
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
                    <textarea id="notes" name="notes" rows="5" placeholder="Add or update notes for this order..."></textarea>
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
/* Container Layout */
.orders-table-container {
    margin-bottom: 20px;
}

.order-details-panel {
    margin-top: 30px;
}

/* Table Styles */
.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th, .orders-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.orders-table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.orders-table tr:hover {
    background-color: #f8f9fa;
}

.orders-table tr.active {
    background-color: #e9f7fe;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.button {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
}

.button-sm {
    padding: 6px 10px;
    font-size: 0.8rem;
}

/* Order Detail Styles */
.order-detail-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 20px;
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
}

/* Keep all your other existing styles */
/* ... */
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle all order actions
    document.addEventListener('click', function(e) {
        const getOrderId = (button) => button.getAttribute('data-order-id');
        
        // Confirm order
        if (e.target.closest('.confirm-order')) {
            const button = e.target.closest('.confirm-order');
            if (confirm('Confirm this order?')) {
                updateOrderStatus(getOrderId(button), 'confirm', 'Order confirmed!');
            }
        }
        
        // Reject order
        if (e.target.closest('.reject-order')) {
            const button = e.target.closest('.reject-order');
            const reason = prompt('Reason for rejection:');
            if (reason) {
                fetch(`/vendor/retailer-orders/${getOrderId(button)}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ rejection_reason: reason })
                }).then(handleResponse).catch(handleError);
            }
        }
        
        // Ship order
        if (e.target.closest('.ship-order')) {
            const button = e.target.closest('.ship-order');
            if (confirm('Mark as shipped?')) {
                updateOrderStatus(getOrderId(button), 'ship', 'Order shipped!');
            }
        }
        
        // Deliver order
        if (e.target.closest('.deliver-order')) {
            const button = e.target.closest('.deliver-order');
            if (confirm('Mark as delivered?')) {
                updateOrderStatus(getOrderId(button), 'deliver', 'Order delivered!');
            }
        }
        
        // View details
        if (e.target.closest('.view-details')) {
            const button = e.target.closest('.view-details');
            window.location.href = `/vendor/retailer-orders?selected=${getOrderId(button)}`;
        }
    });

    // Helper functions
    function updateOrderStatus(orderId, action, successMsg) {
        fetch(`/vendor/retailer-orders/${orderId}/${action}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        }).then(handleResponse).catch(handleError);
        
        function handleResponse(response) {
            response.json().then(data => {
                if (data.success) {
                    alert(successMsg);
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Action failed'));
                }
            });
        }
        
        function handleError(error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    }

    // Modal functions
    window.openNotesModal = function(orderId) {
        fetch(`/vendor/retailer-orders/${orderId}/notes`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('notes').value = data.notes || '';
                document.getElementById('updateNotesForm').dataset.orderId = orderId;
                document.getElementById('notesModal').style.display = 'block';
            });
    };

    window.closeModal = function(modalId) {
        document.getElementById(modalId).style.display = 'none';
    };

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };

    // Form submission
    document.getElementById('updateNotesForm').onsubmit = function(e) {
        e.preventDefault();
        const orderId = this.dataset.orderId;
        const notes = document.getElementById('notes').value;
        
        fetch(`/vendor/retailer-orders/${orderId}/notes`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ notes: notes })
        }).then(res => {
            if (res.ok) {
                alert('Notes updated!');
                window.location.reload();
            }
        });
    };
});
</script>
@endpush
@endsection