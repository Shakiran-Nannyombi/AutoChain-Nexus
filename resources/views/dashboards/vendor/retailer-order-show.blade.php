@extends('layouts.dashboard')

@section('title', 'Order Details')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--text); letter-spacing: 0.01em;">Order Details</h1>
                <p style="font-size: 1.5rem; color: var(--primary); margin-bottom: 2.2rem;">Viewing details for Order #{{ $selectedOrder->id }}</p>
            </div>
            <a href="{{ route('vendor.retailer-orders.index') }}" class="button button-gray">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
    
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
        
        <!-- Action Buttons -->
        <div class="action-buttons-container">
            <h3><i class="fas fa-cogs"></i> Actions</h3>
            <div class="action-buttons">
                @if($selectedOrder->status === 'pending')
                    <form method="POST" action="{{ route('vendor.retailer-orders.confirm', $selectedOrder->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="button button-green">
                            <i class="fas fa-check"></i> Confirm Order
                        </button>
                    </form>
                    <form method="POST" action="{{ route('vendor.retailer-orders.reject', $selectedOrder->id) }}" style="display:inline;" id="rejectForm{{ $selectedOrder->id }}">
                        @csrf
                        <input type="hidden" name="rejection_reason" id="rejectionReason{{ $selectedOrder->id }}">
                        <button type="button" class="button button-red" onclick="rejectOrder({{ $selectedOrder->id }})">
                            <i class="fas fa-times"></i> Reject Order
                        </button>
                    </form>
                @elseif($selectedOrder->status === 'confirmed')
                    <form method="POST" action="{{ route('vendor.retailer-orders.ship', $selectedOrder->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="button button-blue">
                            <i class="fas fa-shipping-fast"></i> Ship Order
                        </button>
                    </form>
                @elseif($selectedOrder->status === 'shipped')
                    <form method="POST" action="{{ route('vendor.retailer-orders.deliver', $selectedOrder->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="button button-green">
                            <i class="fas fa-check-double"></i> Mark as Delivered
                        </button>
                    </form>
                @endif
            </div>
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
        <form id="updateNotesForm" method="POST" action="{{ route('vendor.retailer-orders.notes', $selectedOrder->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="5" placeholder="Add or update notes for this order...">{{ $selectedOrder->notes }}</textarea>
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
/* Order Detail Styles */
.order-detail-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 20px;
    margin-top: 20px;
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

.notes-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
    margin-bottom: 20px;
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

.action-buttons-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
}

.action-buttons-container h3 {
    color: var(--primary);
    margin-bottom: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 10px;
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
    display: flex;
    flex-direction: column;
}

.modal-header,
.modal-body,
.modal-footer {
    width: 100%;
    flex: none;
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
    flex-direction: row;
    gap: 1rem;
    justify-content: flex-end;
    align-items: center;
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
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle order rejection
    window.rejectOrder = function(orderId) {
        const reason = prompt('Please provide a reason for rejecting this order:');
        if (reason) {
            document.getElementById(`rejectionReason${orderId}`).value = reason;
            document.getElementById(`rejectForm${orderId}`).submit();
        }
    };

    // Modal functions
    window.openNotesModal = function(orderId) {
        document.getElementById('notesModal').style.display = 'block';
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

    // Close modal when clicking the X
    document.querySelectorAll('.close').forEach(function(closeBtn) {
        closeBtn.onclick = function() {
            closeBtn.closest('.modal').style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection