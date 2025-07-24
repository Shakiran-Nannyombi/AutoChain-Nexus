@extends('layouts.dashboard')

@section('title', 'Order Management')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--text); letter-spacing: 0.01em;">Order Management</h1>
                <p style="font-size: 1.5rem; color: var(--primary); margin-bottom: 2.2rem;">View and manage all retailer orders</p>
            </div>
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
                        <th style="font-size: 1.2rem; font-weight: 600; color: var(--text); letter-spacing: 0.01em;">Order ID</th>
                        <th style="font-size: 1.2rem; font-weight: 600; color: var(--text); letter-spacing: 0.01em;">Retailer</th>
                        <th style="font-size: 1.2rem; font-weight: 600; color: var(--text); letter-spacing: 0.01em;">Product</th>
                        <th style="font-size: 1.2rem; font-weight: 600; color: var(--text); letter-spacing: 0.01em;">Status</th>
                        <th style="font-size: 1.2rem; font-weight: 600; color: var(--text); letter-spacing: 0.01em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($retailerOrders as $order)
                        <tr data-order-id="{{ $order->id }}" class="{{ $selectedOrder && $selectedOrder->id == $order->id ? 'active' : '' }}">
                            <td>{{ $order->id }}</td>
                            <td>{{ optional($order->retailer)->name ?? 'N/A' }}</td>
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
                                <a href="{{ route('vendor.retailer-orders.show', $order->id) }}" class="button button-sm button-gray view-details" data-order-id="{{ $order->id }}">
                                    <i class="fas fa-eye"></i> Details
                                </a>
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
                                <span>{{ optional($selectedOrder->retailer)->name ?? 'N/A' }}</span>
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

<!-- Confirm Order Modal -->
<div id="confirmOrderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirm Order</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to confirm this order?</p>
            <div class="form-group">
                <label for="confirmNotes">Additional Notes (Optional)</label>
                <textarea id="confirmNotes" rows="3" placeholder="Add any notes about this confirmation..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="button button-gray" onclick="closeModal('confirmOrderModal')">Cancel</button>
            <button type="button" class="button button-green" id="confirmOrderBtn">Confirm Order</button>
        </div>
    </div>
</div>

<!-- Reject Order Modal -->
<div id="rejectOrderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Reject Order</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="rejectionReason">Reason for Rejection <span class="required">*</span></label>
                <textarea id="rejectionReason" rows="3" placeholder="Please provide a reason for rejecting this order..." required></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="button button-gray" onclick="closeModal('rejectOrderModal')">Cancel</button>
            <button type="button" class="button button-red" id="rejectOrderBtn">Reject Order</button>
        </div>
    </div>
</div>

<!-- Ship Order Modal -->
<div id="shipOrderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ship Order</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you ready to mark this order as shipped?</p>
            <div class="form-group">
                <label for="trackingNumber">Tracking Number (Optional)</label>
                <input type="text" id="trackingNumber" placeholder="Enter tracking number...">
            </div>
            <div class="form-group">
                <label for="shippingNotes">Shipping Notes (Optional)</label>
                <textarea id="shippingNotes" rows="3" placeholder="Add any notes about this shipment..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="button button-gray" onclick="closeModal('shipOrderModal')">Cancel</button>
            <button type="button" class="button button-blue" id="shipOrderBtn">Ship Order</button>
        </div>
    </div>
</div>

<!-- Deliver Order Modal -->
<div id="deliverOrderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Mark as Delivered</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure this order has been delivered?</p>
            <div class="form-group">
                <label for="deliveryNotes">Delivery Notes (Optional)</label>
                <textarea id="deliveryNotes" rows="3" placeholder="Add any notes about this delivery..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="button button-gray" onclick="closeModal('deliverOrderModal')">Cancel</button>
            <button type="button" class="button button-green" id="deliverOrderBtn">Mark as Delivered</button>
        </div>
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

.button-green { background: #28a745; color: white; }
.button-red { background: #dc3545; color: white; }
.button-blue { background: #007bff; color: white; }
.button-gray { background: #6c757d; color: white; }

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

/* Status Badges */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1ecf1; color: #0c5460; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #d1e7dd; color: #0f5132; }
.status-rejected { background: #f8d7da; color: #721c24; }

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    overflow: auto;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    animation: modalFadeIn 0.3s;
    display: flex;
    flex-direction: column;
}

.modal-header,
.modal-body,
.modal-footer {
    width: 100%;
    flex: none;
}

@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.modal-header h2 {
    margin: 0;
    color: #333;
    font-size: 1.5rem;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s;
}

.close:hover {
    color: #333;
}

.modal-body {
    padding: 1.5rem;
}

/* Modal Footer Buttons Inline Fix */
.modal-footer {
    display: flex !important;
    flex-direction: row !important;
    gap: 1rem;
    justify-content: flex-end;
    align-items: center;
    padding: 1.5rem;
    border-top: 1px solid #f0f0f0;
    background-color: #f8f9fa;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}

.modal-footer button,
.modal-footer .button {
    display: inline-flex !important;
    width: auto !important;
    min-width: 120px;
    justify-content: center;
    align-items: center;
    margin: 0;
}

/* Remove block/clear for buttons in modal-footer */
.modal-footer .form-group,
.modal-footer .form-group label,
.modal-footer .form-group input,
.modal-footer .form-group textarea {
    display: initial !important;
    clear: none !important;
}

.form-group {
    margin-bottom: 1.5rem;
    display: block;
    clear: both;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
    clear: both;
}

.form-group input,
.form-group textarea {
    display: block;
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.2s;
    box-sizing: border-box;
    clear: both;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.required {
    color: #dc3545;
}

/* Toast Notifications */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 300px;
    max-width: 450px;
    z-index: 1100;
    transform: translateX(110%);
    transition: transform 0.3s ease;
}

.toast.show {
    transform: translateX(0);
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toast-content i {
    font-size: 20px;
}

.toast-success {
    border-left: 4px solid #28a745;
}

.toast-success i {
    color: #28a745;
}

.toast-error {
    border-left: 4px solid #dc3545;
}

.toast-error i {
    color: #dc3545;
}

.toast-info {
    border-left: 4px solid #17a2b8;
}

.toast-info i {
    color: #17a2b8;
}

.toast-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #6c757d;
}

.toast-close:hover {
    color: #343a40;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentOrderId = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        showToast('Security token not found. Please refresh the page.', 'error');
        return;
    }
    
    console.log('CSRF token found:', csrfToken);
    
    // Handle all order actions
    document.addEventListener('click', function(e) {
        const getOrderId = (button) => button.getAttribute('data-order-id');
        
        // Confirm order
        if (e.target.closest('.confirm-order')) {
            const button = e.target.closest('.confirm-order');
            currentOrderId = getOrderId(button);
            document.getElementById('confirmOrderModal').style.display = 'block';
        }
        
        // Reject order
        if (e.target.closest('.reject-order')) {
            const button = e.target.closest('.reject-order');
            currentOrderId = getOrderId(button);
            document.getElementById('rejectOrderModal').style.display = 'block';
        }
        
        // Ship order
        if (e.target.closest('.ship-order')) {
            const button = e.target.closest('.ship-order');
            currentOrderId = getOrderId(button);
            document.getElementById('shipOrderModal').style.display = 'block';
        }
        
        // Deliver order
        if (e.target.closest('.deliver-order')) {
            const button = e.target.closest('.deliver-order');
            currentOrderId = getOrderId(button);
            document.getElementById('deliverOrderModal').style.display = 'block';
        }
        
        // View details
        if (e.target.closest('.view-details')) {
            const button = e.target.closest('.view-details');
            const orderId = getOrderId(button);
            window.location.href = `/vendor/retailer-orders/${orderId}`;
        }
    });

    // Confirm Order Button
    document.getElementById('confirmOrderBtn').addEventListener('click', function() {
        const notes = document.getElementById('confirmNotes').value;
        updateOrderStatus(currentOrderId, 'confirm', {
            notes: notes
        }, 'Order confirmed successfully!');
    });

    // Reject Order Button
    document.getElementById('rejectOrderBtn').addEventListener('click', function() {
        const reason = document.getElementById('rejectionReason').value;
        if (!reason.trim()) {
            alert('Please provide a reason for rejection');
            return;
        }
        updateOrderStatus(currentOrderId, 'reject', {
            rejection_reason: reason
        }, 'Order rejected successfully!');
    });

    // Ship Order Button
    document.getElementById('shipOrderBtn').addEventListener('click', function() {
        const trackingNumber = document.getElementById('trackingNumber').value;
        const shippingNotes = document.getElementById('shippingNotes').value;
        updateOrderStatus(currentOrderId, 'ship', {
            tracking_number: trackingNumber,
            shipping_notes: shippingNotes
        }, 'Order shipped successfully!');
    });

    // Deliver Order Button
    document.getElementById('deliverOrderBtn').addEventListener('click', function() {
        const deliveryNotes = document.getElementById('deliveryNotes').value;
        updateOrderStatus(currentOrderId, 'deliver', {
            delivery_notes: deliveryNotes
        }, 'Order marked as delivered successfully!');
    });

    // Helper functions
    function updateOrderStatus(orderId, action, data, successMsg) {
        console.log('Updating order status:', { orderId, action, data });
        
        fetch(`/vendor/retailer-orders/${orderId}/${action}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Close any open modals
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.style.display = 'none';
                });
                
                // Show success message
                showToast(successMsg, 'success');
                
                // Update the table row
                updateTableRow(orderId, data.order);
            } else {
                showToast('Error: ' + (data.message || 'Action failed'), 'error');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            showToast('An error occurred. Please try again.', 'error');
        });
    }
    
    // Update table row without reloading
    function updateTableRow(orderId, order) {
        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
        if (row) {
            // Update status cell
            const statusCell = row.querySelector('td:nth-child(4)');
            if (statusCell) {
                statusCell.innerHTML = `<span class="status-badge status-${order.status}">${capitalizeFirstLetter(order.status)}</span>`;
            }
            
            // Update action buttons
            const actionsCell = row.querySelector('td:nth-child(5)');
            if (actionsCell) {
                let actionButtons = '';
                
                if (order.status === 'pending') {
                    actionButtons = `
                        <button class="button button-sm button-green confirm-order" data-order-id="${order.id}">
                            <i class="fas fa-check"></i> Confirm
                        </button>
                        <button class="button button-sm button-red reject-order" data-order-id="${order.id}">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    `;
                } else if (order.status === 'confirmed') {
                    actionButtons = `
                        <button class="button button-sm button-blue ship-order" data-order-id="${order.id}">
                            <i class="fas fa-shipping-fast"></i> Ship
                        </button>
                    `;
                } else if (order.status === 'shipped') {
                    actionButtons = `
                        <button class="button button-sm button-green deliver-order" data-order-id="${order.id}">
                            <i class="fas fa-check-double"></i> Deliver
                        </button>
                    `;
                }
                
                actionButtons += `
                    <a href="/vendor/retailer-orders/${order.id}" class="button button-sm button-gray view-details" data-order-id="${order.id}">
                        <i class="fas fa-eye"></i> Details
                    </a>
                `;
                
                actionsCell.innerHTML = `<div class="action-buttons">${actionButtons}</div>`;
            }
            
            // If this is the selected order, also update the details panel
            const selectedOrderId = document.querySelector('.order-detail-container')?.dataset?.orderId;
            if (selectedOrderId && selectedOrderId == orderId) {
                updateOrderDetailsPanel(order);
            }
        }
    }
    
    // Update order details panel
    function updateOrderDetailsPanel(order) {
        // Update status badge
        const statusBadge = document.querySelector('.status-display .status-badge');
        if (statusBadge) {
            statusBadge.className = `status-badge status-${order.status}`;
            statusBadge.textContent = capitalizeFirstLetter(order.status);
        }
        
        // Update timeline
        const timeline = document.querySelector('.status-timeline');
        if (timeline) {
            let timelineHTML = '';
            
            if (order.ordered_at) {
                timelineHTML += `
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong>Ordered:</strong> ${formatDate(order.ordered_at)}
                        </div>
                    </div>
                `;
            }
            
            if (order.confirmed_at) {
                timelineHTML += `
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong>Confirmed:</strong> ${formatDate(order.confirmed_at)}
                        </div>
                    </div>
                `;
            }
            
            if (order.shipped_at) {
                timelineHTML += `
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong>Shipped:</strong> ${formatDate(order.shipped_at)}
                        </div>
                    </div>
                `;
            }
            
            if (order.delivered_at) {
                timelineHTML += `
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong>Delivered:</strong> ${formatDate(order.delivered_at)}
                        </div>
                    </div>
                `;
            }
            
            timeline.innerHTML = timelineHTML;
        }
        
        // Update notes
        const notesText = document.querySelector('.notes-text');
        const noNotes = document.querySelector('.no-notes');
        if (notesText && noNotes) {
            if (order.notes) {
                notesText.textContent = order.notes;
                notesText.style.display = 'block';
                noNotes.style.display = 'none';
            } else {
                notesText.style.display = 'none';
                noNotes.style.display = 'block';
            }
        }
    }
    
    // Toast notification
    function showToast(message, type = 'info') {
        // Remove any existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());
        
        // Create new toast
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close">&times;</button>
        `;
        
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Auto hide after 5 seconds
        const hideTimeout = setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
        
        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            clearTimeout(hideTimeout);
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        });
    }
    
    // Helper functions
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        });
    }

    // Modal functions
    window.openNotesModal = function(orderId) {
        currentOrderId = orderId;
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
    
    // Close modal when clicking X
    document.querySelectorAll('.modal .close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    // Form submission for notes
    document.getElementById('updateNotesForm').onsubmit = function(e) {
        e.preventDefault();
        const orderId = this.dataset.orderId;
        const notes = document.getElementById('notes').value;
        
        fetch(`/vendor/retailer-orders/${orderId}/notes`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                document.getElementById('notesModal').style.display = 'none';
                
                // Show success message
                showToast('Notes updated successfully!', 'success');
                
                // Update notes in the UI
                const notesText = document.querySelector('.notes-text');
                const noNotes = document.querySelector('.no-notes');
                if (notesText && noNotes) {
                    if (notes) {
                        notesText.textContent = notes;
                        notesText.style.display = 'block';
                        noNotes.style.display = 'none';
                    } else {
                        notesText.style.display = 'none';
                        noNotes.style.display = 'block';
                    }
                }
            } else {
                showToast('Error: ' + (data.message || 'Failed to update notes'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
        });
    };
});
</script>
@endpush
@endsection