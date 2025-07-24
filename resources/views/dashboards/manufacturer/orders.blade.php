@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 1rem;"> Orders Management</h2>
        @php $vendorTabActive = request('vendor_status') !== null && request('vendor_status') !== ''; @endphp
        <div class="tabs-container">
            <button class="tab-link{{ !$vendorTabActive ? ' active' : '' }}" data-tab="supplier-orders">Supplier Orders</button>
            <button class="tab-link" data-tab="supplier-deliveries">Deliveries</button>
            <button class="tab-link" data-tab="vendor-orders">Vendor Orders</button>
            <button class="tab-link" data-tab="invoices">Invoices</button>
        </div>
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        <div id="supplier-orders" class="tab-content{{ !$vendorTabActive ? ' active' : '' }}">
            <h3 style="color: var(--text); font-size: 1.5rem; margin-bottom: 1rem; font-weight:bold;"><i class="fas fa-truck"></i> Supplier Orders</h3>
            <!-- Summary Cards -->
            <div style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <div class="orders-summary-card total">
                    <div style="font-size: 1.2rem; color: white; font-weight: 700;">{{ $supplierOrders->count() }}</div>
                    <div style="font-size: 0.98rem; color: #d5cfcf;">Total Orders</div>
                </div>
                <div class="orders-summary-card pending">
                    <div style="font-size: 1.2rem; color: white; font-weight: 700;">{{ $supplierOrders->where('status','pending')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #d5cfcf;">Pending</div>
                </div>
                <div class="orders-summary-card fulfilled">
                    <div style="font-size: 1.2rem; color: white; font-weight: 700;">{{ $supplierOrders->where('status','fulfilled')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #d5cfcf;">Fulfilled</div>
                </div>
                <div class="orders-summary-card declined">
                    <div style="font-size: 1.2rem; color: white; font-weight: 700;">{{ $supplierOrders->where('status','declined')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #d5cfcf;">Declined</div>
                </div>
            </div>
            <!-- Orders Table -->
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f8f8; color: var(--primary);">
                            <th style="padding: 0.7rem;">Order</th>
                            <th style="padding: 0.7rem;">Supplier</th>
                            <th style="padding: 0.7rem;">Date</th>
                            <th style="padding: 0.7rem;">Status</th>
                            <th style="padding: 0.7rem;">Items</th>
                            <th style="padding: 0.7rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplierOrders as $order)
                        <tr>
                            <td style="padding: 0.7rem;">
                                @foreach($order->items as $item)
                                    <span>{{ $item->product_name }}: <strong>{{ $item->quantity }}</strong></span>@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td style="padding: 0.7rem;">
                                @if($order->supplier)
                                    {{ $order->supplier->name }}<br>
                                    <span style="color:#888; font-size:0.95em;">{{ $order->supplier->email }}</span>
                                @else
                                    Supplier #{{ $order->supplier_id }}
                                @endif
                            </td>
                            <td style="padding: 0.7rem;">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : '' }}</td>
                            <td style="padding: 0.7rem;">
                                @php $status = $order->status ?: 'pending'; @endphp
                                @if($status === 'pending')
                                    <span style="background: #e67e22; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Pending</span>
                                @elseif($status === 'fulfilled')
                                    <span style="background: #27ae60; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Fulfilled</span>
                                @elseif($status === 'declined')
                                    <span style="background: #b71c1c; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Declined</span>
                                @endif
                            </td>
                            <td style="padding: 0.7rem;">{{ $order->items->count() }}</td>
                            <td style="padding: 0.7rem;">
                                <form method="POST" action="{{ route('manufacturer.remake.order', $order->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background: var(--primary); color: white; border: none; border-radius: 6px; padding: 0.4rem 1.2rem; font-size: 0.95rem; cursor: pointer;">
                                        <i class="fas fa-redo"></i> Remake Order
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:#888; padding:2rem;">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div id="supplier-deliveries" class="tab-content">
            <h3 style="color: var(--text); font-size: 1.5rem; margin-bottom: 1rem; font-weight:bold;"><i class="fas fa-truck"></i> Supplier Deliveries</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f8f8; color: var(--primary);">
                            <th style="padding: 0.7rem;">Delivery ID</th>
                            <th style="padding: 0.7rem;">Supplier</th>
                            <th style="padding: 0.7rem;">Driver</th>
                            <th style="padding: 0.7rem;">Materials</th>
                            <th style="padding: 0.7rem;">Status</th>
                            <th style="padding: 0.7rem;">Progress</th>
                            <th style="padding: 0.7rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)
                        <tr>
                            <td style="padding: 0.7rem; font-weight: 600;">#DEL-{{ str_pad($delivery->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 0.7rem;">{{ $delivery->supplier->name ?? 'N/A' }}</td>
                            <td style="padding: 0.7rem;">{{ $delivery->driver }}</td>
                            <td style="padding: 0.7rem;">
                                @if($delivery->materials_delivered)
                                    @foreach($delivery->materials_delivered as $material => $quantity)
                                        <span style="background: #e3f2fd; color: #1976d2; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.85rem; margin-right: 0.5rem;">{{ $material }}: {{ $quantity }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td style="padding: 0.7rem;">
                                @if($delivery->status === 'delivered')
                                    <span style="background: #17a2b8; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Awaiting Confirmation</span>
                                @elseif($delivery->status === 'completed')
                                    <span style="background: #28a745; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Complete</span>
                                @else
                                    <span style="background: #007bff; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">{{ ucfirst($delivery->status) }}</span>
                                @endif
                            </td>
                            <td style="padding: 0.7rem;">{{ $delivery->progress }}%</td>
                            <td style="padding: 0.7rem;">
                                @if(in_array($delivery->status, ['delivered', 'pending', 'in_transit']))
                                    <button onclick="confirmDelivery({{ $delivery->id }})" style="padding: 0.4rem 0.8rem; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem;">
                                        <i class="fas fa-check"></i> Confirm Delivery
                                    </button>
                                @elseif($delivery->status === 'completed')
                                    <span style="color: #28a745; font-weight: 600;">✓ Confirmed</span>
                                @else
                                    <span style="color: #6c757d;">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:#888; padding:2rem;">No deliveries found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div id="vendor-orders" class="tab-content">
            <h3 style="color: var(--text); font-size: 1.5rem; margin-bottom: 1rem; font-weight:bold;"><i class="fas fa-truck"></i> Vendor Orders Management</h3>
            <div style="overflow-x:auto;">
                <table id="vendorOrdersTable" style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f8f8; color: var(--primary);">
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
                        @foreach($vendorOrders->sortByDesc('created_at') as $order)
                        <tr id="order-row-{{ $order->id }}">
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->vendor->name ?? ('Vendor #' . $order->vendor_id) }}<br><span style="color:#888; font-size:0.95em;">{{ $order->vendor->email ?? '' }}</span></td>
                            <td>{{ $order->product_name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>shs {{ number_format($order->total_amount, 2) }}</td>
                            <td class="order-status">@if($order->status === 'pending')<span class="badge bg-warning">Pending</span>@elseif($order->status === 'accepted')<span class="badge bg-success">Fulfilled</span>@elseif($order->status === 'rejected')<span class="badge bg-danger">Declined</span>@else<span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>@endif</td>
                            <td>{{ $order->ordered_at ? $order->ordered_at->format('Y-m-d H:i') : '' }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap; white-space: nowrap;">
                                    @if($order->status === 'pending')
                                        <button type="button" class="action-btn confirm-btn open-confirm-modal" style="min-width: 90px;" data-order-id="{{ $order->id }}">Confirm</button>
                                        <button type="button" class="action-btn reject-btn open-reject-modal" style="min-width: 90px; background:#ef4444; color:#fff; font-weight:600;" data-order-id="{{ $order->id }}">Reject</button>
                                    @endif
                                    <a href="{{ route('manufacturer.vendor-orders.show', $order->id) }}" class="action-btn view-btn" style="min-width: 70px;">View</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="invoices" class="tab-content">
            <h3 style="color: var(--text); font-size: 1.5rem; margin-bottom: 1rem; font-weight:bold;"><i class="fas fa-file-invoice"></i> Invoices</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f8f8; color: var(--primary);">
                            <th style="padding: 0.7rem;">Invoice #</th>
                            <th style="padding: 0.7rem;">Vendor</th>
                            <th style="padding: 0.7rem;">Product</th>
                            <th style="padding: 0.7rem;">Quantity</th>
                            <th style="padding: 0.7rem;">Total Amount</th>
                            <th style="padding: 0.7rem;">Confirmed At</th>
                            <th style="padding: 0.7rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $invoices = $vendorOrders->where('status', 'accepted'); @endphp
                        @forelse($invoices as $order)
                        <tr>
                            <td style="padding: 0.7rem;">#{{ $order->id }}</td>
                            <td style="padding: 0.7rem;">{{ $order->vendor->name ?? ('Vendor #' . $order->vendor_id) }}</td>
                            <td style="padding: 0.7rem;">{{ $order->product }}</td>
                            <td style="padding: 0.7rem;">{{ $order->quantity }}</td>
                            <td style="padding: 0.7rem;">shs {{ number_format($order->total_amount, 2) }}</td>
                            <td style="padding: 0.7rem;">{{ $order->accepted_at ? \Carbon\Carbon::parse($order->accepted_at)->format('Y-m-d H:i') : '' }}</td>
                            <td style="padding: 0.7rem;"><a href="{{ route('manufacturer.vendor-orders.invoice-preview', $order->id) }}" class="btn btn-primary btn-sm">View Invoice</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center; color:#888; padding:2rem;">No invoices found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal for Order Details -->
    <div id="orderDetailsModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:10px; max-width:400px; width:90vw; padding:2rem; position:relative; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
            <button id="closeOrderModal" style="position:absolute; top:10px; right:15px; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <div style="text-align:center; margin-bottom:1rem;">
                <div id="orderVendorName" style="font-weight:700; font-size:1.2rem;"></div>
                <div id="orderVendorEmail" style="color:#888; font-size:0.98rem;"></div>
            </div>
            <div style="margin-bottom:0.7rem;"><strong>Product:</strong> <span id="orderProduct"></span></div>
            <div style="margin-bottom:0.7rem;"><strong>Quantity:</strong> <span id="orderQuantity"></span></div>
            <div style="margin-bottom:0.7rem;"><strong>Total Amount:</strong> shs <span id="orderTotalAmount"></span></div>
            <div style="margin-bottom:0.7rem;"><strong>Status:</strong> <span id="orderStatus"></span></div>
            <div style="margin-bottom:0.7rem;"><strong>Ordered At:</strong> <span id="orderOrderedAt"></span></div>
        </div>
    </div>
    <!-- Modal for Confirming Vendor Order -->
    <div id="confirmOrderModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:14px; max-width:420px; width:95vw; padding:2.2rem 2rem 1.5rem 2rem; position:relative; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
            <button id="closeConfirmOrderModal" style="position:absolute; top:12px; right:18px; background:none; border:none; font-size:1.7rem; color:#888; cursor:pointer;">&times;</button>
            <div id="confirmOrderSummary" style="margin-bottom:1.5rem; background:#f8fafc; border-radius:8px; padding:1rem 1.2rem;">
                <div style="font-weight:700; font-size:1.1rem; color:var(--primary); margin-bottom:0.3rem;">Confirm Vendor Order</div>
                <div style="font-size:1rem; color:#333;"><span id="confirmOrderVendor"></span></div>
                <div style="font-size:0.98rem; color:#666; margin-bottom:0.2rem;"><span id="confirmOrderVendorEmail"></span></div>
                <div style="font-size:0.98rem; color:#444;"><b>Product:</b> <span id="confirmOrderProduct"></span></div>
                <div style="font-size:0.98rem; color:#444;"><b>Quantity:</b> <span id="confirmOrderQuantity"></span></div>
                <div style="font-size:0.98rem; color:#444;"><b>Total:</b> shs <span id="confirmOrderTotal"></span></div>
            </div>
            <form id="confirmOrderForm" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="confirmOrderId">
                <div style="margin-bottom:1.1rem;">
                    <label for="delivery_date" style="font-weight:600;">Delivery Date</label>
                    <input type="date" name="delivery_date" id="delivery_date" class="form-control" required style="width:100%;padding:0.5rem;">
                </div>
                <div style="margin-bottom:1.1rem;">
                    <label for="delivery_address" style="font-weight:600;">Delivery Address</label>
                    <input type="text" name="delivery_address" id="delivery_address" class="form-control" required style="width:100%;padding:0.5rem;">
                </div>
                <div style="margin-bottom:1.1rem;">
                    <label for="driver_name" style="font-weight:600;">Driver Name</label>
                    <input type="text" name="driver_name" id="driver_name" class="form-control" required style="width:100%;padding:0.5rem;">
                </div>
                <div style="display:flex; gap:0.7rem; margin-top:1.5rem;">
                    <button type="button" id="cancelConfirmOrder" class="btn btn-secondary" style="flex:1; background:#eee; color:#333; border-radius:7px; padding:0.7rem 0; font-weight:600; border:none;">Cancel</button>
                    <button type="submit" class="btn btn-success" style="flex:2; background:var(--primary); color:#fff; border-radius:7px; padding:0.7rem 0; font-weight:700; border:none;">Confirm &amp; Send Email</button>
                </div>
            </form>
            <div id="confirmOrderSpinner" style="display:none; text-align:center; margin-top:1rem;"><span>Processing...</span></div>
        </div>
    </div>
    <!-- Reject Modal -->
    <div id="rejectOrderModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:14px; max-width:420px; width:95vw; padding:2.2rem 2rem 1.5rem 2rem; position:relative; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
            <button id="closeRejectOrderModal" style="position:absolute; top:12px; right:18px; background:none; border:none; font-size:1.7rem; color:#888; cursor:pointer;">&times;</button>
            <div style="font-weight:700; font-size:1.1rem; color:#ef4444; margin-bottom:0.7rem;">Reject Vendor Order</div>
            <form id="rejectOrderForm" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="rejectOrderId">
                <div style="margin-bottom:1.1rem;">
                    <label for="rejection_reason" style="font-weight:600;">Reason for Rejection</label>
                    <textarea name="rejection_reason" id="rejection_reason" class="form-control" required style="width:100%;padding:0.5rem; min-height:80px;"></textarea>
                </div>
                <div style="display:flex; gap:0.7rem; margin-top:1.5rem;">
                    <button type="button" id="cancelRejectOrder" class="btn btn-secondary" style="flex:1; background:#eee; color:#333; border-radius:7px; padding:0.7rem 0; font-weight:600; border:none;">Cancel</button>
                    <button type="submit" class="btn btn-danger" style="flex:2; background:#ef4444; color:#fff; border-radius:7px; padding:0.7rem 0; font-weight:700; border:none;">Reject &amp; Send Email</button>
                </div>
            </form>
            <div id="rejectOrderSpinner" style="display:none; text-align:center; margin-top:1rem;"><span>Processing...</span></div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');
        // On page load, show the correct tab if vendor_status is present
        const vendorTabActive = {{ $vendorTabActive ? 'true' : 'false' }};
        if (vendorTabActive) {
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            tabContents.forEach(c => c.style.display = 'none');
            document.querySelector('.tab-link[data-tab="vendor-orders"]').classList.add('active');
            document.getElementById('vendor-orders').classList.add('active');
            document.getElementById('vendor-orders').style.display = '';
        } else {
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            tabContents.forEach(c => c.style.display = 'none');
            document.querySelector('.tab-link[data-tab="supplier-orders"]').classList.add('active');
            document.getElementById('supplier-orders').classList.add('active');
            document.getElementById('supplier-orders').style.display = '';
        }
        tabLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remove active class and reset styles
                tabLinks.forEach(l => {
                    l.classList.remove('active');
                    l.style.background = '#f5f5f5';
                    l.style.color = 'var(--primary)';
                });
                
                // Hide all tab contents
                tabContents.forEach(c => {
                    c.classList.remove('active');
                    c.style.display = 'none';
                });
                
                // Activate clicked tab
                this.classList.add('active');
                this.style.background = 'var(--primary)';
                this.style.color = '#fff';
                
                // Show corresponding content
                const tab = document.getElementById(this.dataset.tab);
                tab.classList.add('active');
                tab.style.display = '';
            });
        });
        // Modal logic for order details
        const modal = document.getElementById('orderDetailsModal');
        const closeModal = document.getElementById('closeOrderModal');
        document.querySelectorAll('.view-order-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const order = JSON.parse(this.getAttribute('data-order'));
                document.getElementById('orderVendorName').textContent = order.vendor_name;
                document.getElementById('orderVendorEmail').textContent = order.vendor_email || '';
                document.getElementById('orderProduct').textContent = order.product;
                document.getElementById('orderQuantity').textContent = order.quantity;
                document.getElementById('orderTotalAmount').textContent = Number(order.total_amount).toLocaleString(undefined, {minimumFractionDigits:2});
                document.getElementById('orderStatus').textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);
                document.getElementById('orderOrderedAt').textContent = order.ordered_at;
                modal.style.display = 'flex';
            });
        });
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });
        // Confirm Order Modal logic
        const confirmModal = document.getElementById('confirmOrderModal');
        const closeConfirmModal = document.getElementById('closeConfirmOrderModal');
        const cancelConfirmOrder = document.getElementById('cancelConfirmOrder');
        const confirmOrderForm = document.getElementById('confirmOrderForm');
        const confirmOrderSpinner = document.getElementById('confirmOrderSpinner');
        let currentOrderId = null;
        document.querySelectorAll('.open-confirm-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                document.getElementById('confirmOrderId').value = orderId;
                confirmOrderForm.action = '/manufacturer/vendor-orders/' + orderId + '/confirm';
                // Fill summary info
                const row = document.getElementById('order-row-' + orderId);
                if (row) {
                    document.getElementById('confirmOrderVendor').textContent = row.children[1].innerText.split('\n')[0];
                    document.getElementById('confirmOrderVendorEmail').textContent = row.children[1].innerText.split('\n')[1] || '';
                    document.getElementById('confirmOrderProduct').textContent = row.children[2].innerText;
                    document.getElementById('confirmOrderQuantity').textContent = row.children[3].innerText;
                    document.getElementById('confirmOrderTotal').textContent = row.children[4].innerText.replace('shs ', '');
                }
                confirmModal.style.display = 'flex';
            });
        });
        closeConfirmModal.addEventListener('click', function() {
            confirmModal.style.display = 'none';
        });
        cancelConfirmOrder.addEventListener('click', function() {
            confirmModal.style.display = 'none';
        });
        confirmModal.addEventListener('click', function(e) {
            if (e.target === confirmModal) confirmModal.style.display = 'none';
        });
        // AJAX submit for confirm order form
        confirmOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            confirmOrderSpinner.style.display = 'block';
            const form = this;
            const url = form.action;
            const formData = new FormData(form);
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                confirmOrderSpinner.style.display = 'none';
                if (data && data.success) {
                    confirmModal.style.display = 'none';
                    // Update the row in-place
                    const row = document.getElementById('order-row-' + form.order_id.value);
                    if (row) {
                        row.querySelector('.order-status').innerHTML = '<span class="badge bg-success">Fulfilled</span>';
                        // Disable the confirm button
                        const confirmBtn = row.querySelector('.confirm-btn');
                        if (confirmBtn) {
                            confirmBtn.disabled = true;
                            confirmBtn.style.opacity = 0.5;
                            confirmBtn.style.pointerEvents = 'none';
                        }
                    }
                    // Show beautiful toast
                    const toast = document.getElementById('toastSuccess');
                    document.getElementById('toastSuccessMsg').textContent = data.message || 'Order confirmed successfully!';
                    toast.style.display = 'flex';
                    setTimeout(() => { toast.style.display = 'none'; }, 3500);
                } else if (data && data.message) {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                confirmOrderSpinner.style.display = 'none';
                alert('An error occurred while confirming the order.');
            });
        });
        // Reject Order Modal logic
        const rejectModal = document.getElementById('rejectOrderModal');
        const closeRejectModal = document.getElementById('closeRejectOrderModal');
        const cancelRejectOrder = document.getElementById('cancelRejectOrder');
        const rejectOrderForm = document.getElementById('rejectOrderForm');
        const rejectOrderSpinner = document.getElementById('rejectOrderSpinner');
        document.querySelectorAll('.open-reject-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                document.getElementById('rejectOrderId').value = orderId;
                rejectOrderForm.action = '/manufacturer/vendor-orders/' + orderId + '/reject';
                rejectModal.style.display = 'flex';
            });
        });
        closeRejectModal.addEventListener('click', function() {
            rejectModal.style.display = 'none';
        });
        cancelRejectOrder.addEventListener('click', function() {
            rejectModal.style.display = 'none';
        });
        rejectModal.addEventListener('click', function(e) {
            if (e.target === rejectModal) rejectModal.style.display = 'none';
        });
        // AJAX submit for reject order form
        rejectOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            rejectOrderSpinner.style.display = 'block';
            const form = this;
            const url = form.action;
            const formData = new FormData(form);
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                rejectOrderSpinner.style.display = 'none';
                if (data && data.success) {
                    rejectModal.style.display = 'none';
                    // Update the row in-place
                    const row = document.getElementById('order-row-' + form.order_id.value);
                    if (row) {
                        row.querySelector('.order-status').innerHTML = '<span class="badge bg-danger">Declined</span>';
                        // Disable the reject and confirm buttons
                        const rejectBtn = row.querySelector('.reject-btn');
                        if (rejectBtn) {
                            rejectBtn.disabled = true;
                            rejectBtn.style.opacity = 0.5;
                            rejectBtn.style.pointerEvents = 'none';
                        }
                        const confirmBtn = row.querySelector('.confirm-btn');
                        if (confirmBtn) {
                            confirmBtn.disabled = true;
                            confirmBtn.style.opacity = 0.5;
                            confirmBtn.style.pointerEvents = 'none';
                        }
                    }
                    // Show beautiful toast
                    const toast = document.getElementById('toastSuccess');
                    document.getElementById('toastSuccessMsg').textContent = data.message || 'Order rejected and vendor notified.';
                    toast.style.display = 'flex';
                    setTimeout(() => { toast.style.display = 'none'; }, 3500);
                } else if (data && data.message) {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                rejectOrderSpinner.style.display = 'none';
                alert('An error occurred while rejecting the order.');
            });
        });
    });
    
    function confirmDelivery(deliveryId) {
        if (confirm('Are you sure you want to confirm this delivery as received?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/manufacturer/deliveries/${deliveryId}/confirm`;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
@endsection
