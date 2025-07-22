@extends('layouts.dashboard')

@section('title', 'Invoice Preview')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); font-size: 2rem; font-weight: bold; margin-bottom: 1.2rem;">Invoice Preview</h2>
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 2rem; margin-bottom: 2rem;">
        @include('emails.reports.invoice', [
            'order' => $order,
            'product' => $product,
            'manufacturer' => $manufacturer,
            'vendor' => $vendor,
            // Optionally pass delivery details if needed
        ])
    </div>
    <div style="display: flex; gap: 1.2rem;">
        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Save as PDF</button>
        <button class="btn btn-secondary" id="editInvoiceBtn"><i class="fas fa-edit"></i> Edit Invoice</button>
        <button class="btn resend-btn" id="resendEmailBtn"><i class="fas fa-envelope"></i> Resend Email</button>
    </div>
    <!-- Edit Invoice Modal -->
    <div id="editInvoiceModal" class="custom-modal-overlay" style="display:none;">
        <div class="custom-modal-card">
            <button type="button" class="custom-modal-close" id="closeEditInvoiceModal">&times;</button>
            <h3 class="custom-modal-title">Edit Invoice</h3>
            <form id="editInvoiceForm">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" class="form-control" value="{{ $order->product_name ?? $order->product }}" required />
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ $order->quantity }}" min="1" required />
                </div>
                <div class="form-group">
                    <label>Unit Price</label>
                    <input type="number" name="unit_price" class="form-control" value="{{ $order->unit_price }}" min="0" step="0.01" required />
                </div>
                <div class="form-group">
                    <label>Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" class="form-control" value="{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('Y-m-d') : '' }}" />
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                </div>
                <div style="margin-top:1.2rem; display:flex; gap:1rem; justify-content:flex-end;">
                    <button type="button" id="cancelEditInvoice" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Notification Popup -->
    <div id="customNotification" class="custom-modal-overlay" style="display:none;">
        <div class="custom-modal-card" style="max-width:350px; text-align:center;">
            <button type="button" class="custom-modal-close" id="closeNotification">&times;</button>
            <div id="notificationContent" style="margin-top:1.2rem; font-size:1.1rem;"></div>
        </div>
    </div>
    @push('styles')
    <style>
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.35);
        display: flex; align-items: center; justify-content: center;
        z-index: 10000;
        transition: background 0.2s;
    }
    .custom-modal-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        padding: 2.2rem 2rem 1.5rem 2rem;
        position: relative;
        min-width: 320px;
        max-width: 98vw;
        animation: modalPopIn 0.22s cubic-bezier(.4,2,.6,1) both;
    }
    @keyframes modalPopIn {
        0% { transform: scale(0.92) translateY(30px); opacity: 0; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }
    .custom-modal-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--primary, #2563eb);
        margin-bottom: 1.2rem;
        text-align: left;
    }
    .custom-modal-close {
        position: absolute;
        top: 1.1rem; right: 1.1rem;
        background: none; border: none;
        font-size: 1.5rem; color: #888;
        cursor: pointer;
        transition: color 0.18s;
    }
    .custom-modal-close:hover { color: #222; }
    #notificationContent .spinner {
        display: inline-block;
        width: 32px; height: 32px;
        border: 3px solid #e5e7eb;
        border-top: 3px solid #2563eb;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-bottom: 1rem;
    }
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .resend-btn {
        background: linear-gradient(90deg, #10b981 0%, #22d3ee 100%);
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(16,185,129,0.08);
        transition: background 0.18s, box-shadow 0.18s;
    }
    .resend-btn:hover, .resend-btn:focus {
        background: linear-gradient(90deg, #059669 0%, #0ea5e9 100%);
        box-shadow: 0 4px 16px rgba(16,185,129,0.16);
        color: #fff;
    }
    </style>
    @endpush
    @push('scripts')
    <script>
    function showNotification(message, opts = {}) {
        const notif = document.getElementById('customNotification');
        const content = document.getElementById('notificationContent');
        content.innerHTML = message;
        notif.style.display = 'flex';
        if (opts.autoClose) {
            setTimeout(() => notif.style.display = 'none', opts.autoClose);
        }
    }
    function showSpinnerNotification(text) {
        showNotification(`<div class='spinner'></div><div style='margin-top:0.7rem;'>${text}</div>`);
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Modal logic
        const modal = document.getElementById('editInvoiceModal');
        document.getElementById('editInvoiceBtn').onclick = function() {
            modal.style.display = 'flex';
        };
        document.getElementById('closeEditInvoiceModal').onclick = function() {
            modal.style.display = 'none';
        };
        document.getElementById('cancelEditInvoice').onclick = function() {
            modal.style.display = 'none';
        };
        // Save changes
        document.getElementById('editInvoiceForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            showSpinnerNotification('Saving changes...');
            fetch(`/manufacturer/vendor-orders/{{ $order->id }}/update-invoice`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification('Invoice updated successfully!', {autoClose: 1800});
                    setTimeout(() => location.reload(), 1800);
                } else {
                    showNotification(data.message || 'Failed to update invoice.');
                }
            })
            .catch(() => showNotification('Error updating invoice.'));
        };
        // Resend Email logic
        document.getElementById('resendEmailBtn').onclick = function() {
            showSpinnerNotification('Resending invoice email...');
            fetch(`/manufacturer/vendor-orders/{{ $order->id }}/resend-invoice`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification('Invoice resent to vendor!', {autoClose: 1800});
                } else {
                    showNotification(data.message || 'Failed to resend invoice.');
                }
            })
            .catch(() => showNotification('Error resending invoice.'));
        };
        // Notification close
        document.getElementById('closeNotification').onclick = function() {
            document.getElementById('customNotification').style.display = 'none';
        };
    });
    </script>
    @endpush
    <div style="margin-top: 2rem;">
        <a href="{{ route('manufacturer.orders') }}" class="btn btn-outline-primary">Back to Orders</a>
    </div>
</div>
@endsection 