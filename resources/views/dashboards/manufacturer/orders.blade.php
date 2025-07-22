@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 1rem;"> Orders Management</h2>
        @php $vendorTabActive = request('vendor_status') !== null && request('vendor_status') !== ''; @endphp
        <div class="tabs-container" style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
            <button class="tab-link{{ !$vendorTabActive ? ' active' : '' }}" data-tab="supplier-orders" style="padding: 0.7rem 2rem; border: none; border-radius: 8px; background: var(--primary); color: #fff; font-weight: 600; cursor: pointer;">Supplier Orders</button>
            <button class="tab-link{{ $vendorTabActive ? ' active' : '' }}" data-tab="vendor-orders" style="padding: 0.7rem 2rem; border: none; border-radius: 8px; background: #f5f5f5; color: var(--primary); font-weight: 600; cursor: pointer;">Vendor Orders</button>
            <button class="tab-link" data-tab="invoices" style="padding: 0.7rem 2rem; border: none; border-radius: 8px; background: #f5f5f5; color: var(--primary); font-weight: 600; cursor: pointer;">Invoices</button>
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
                                @foreach($order->materials_requested as $mat => $qty)
                                    <span>{{ $mat }}: <strong>{{ $qty }}</strong></span>@if(!$loop->last), @endif
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
                            <td style="padding: 0.7rem;">{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '' }}</td>
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
                            <td style="padding: 0.7rem;">{{ count($order->materials_requested) }}</td>
                            <td style="padding: 0.7rem;">
                                <form method="POST" action="{{ route('manufacturer.remake.order', $order->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background: var(--primary); color: white; border: none; border-radius: 6px; padding: 0.4rem 1.2rem; font-size: 0.95rem;">
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
            <style>
            .tab-link.active { background: var(--primary); color: #fff !important; }
            .tab-link.active[data-tab='vendor-orders'] { background: var(--accent) !important; color: #111 !important; }
            .tab-content { display: none; }
            .tab-content.active { display: block; }
            .status-badge { border-radius: 8px; font-size: 0.95rem; padding: 0.2rem 0.8rem; font-weight: 600; }
            .status-badge.status-pending { background: #fffbe6; color: #b45309; }
            .status-badge.status-fulfilled { background: #eafbe7; color: #16610e; }
            .tab-link { padding: 0.6rem 1.5rem; border: none; border-radius: 6px; background: #f5f5f5; color: var(--primary); font-weight: 600; text-decoration: none; margin-right: 0.5rem; transition: background 0.2s; }
            .tab-link.active, .tab-link:hover { background: var(--primary); color: #fff; }
            </style>
        </div>
        <div id="vendor-orders" class="tab-content{{ $vendorTabActive ? ' active' : '' }}" style="display: none;">
            <h3 style="color: var(--text); font-size: 1.5rem; margin-bottom: 1rem; font-weight:bold;"><i class="fas fa-truck"></i> Vendor Orders Management</h3>
            <!-- Vendor Orders Summary Cards -->
            <div style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <div class="orders-summary-card fulfilled">
                    <div style="font-size: 1.2rem; color: white; font-weight: 700;">{{ $vendorOrders->where('status','fulfilled')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #d5cfcf;">Fulfilled</div>
                </div>
                <div class="orders-summary-card cancelled">
                    <div style="font-size: 1.2rem; color: white; font-weight: 700;">{{ $vendorOrders->where('status','cancelled')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #d5cfcf;">Cancelled</div>
                </div>
                <div class="orders-summary-card total">
                    <div style="font-size: 1.2rem; color: white; font-weight: 700;">{{ $vendorOrders->count() }}</div>
                    <div style="font-size: 0.98rem; color: #d5cfcf;">Total</div>
                </div>
            </div>
            <!-- Vendor Orders Filter Form -->
            <!-- Remove the Vendor Orders Filter Form -->
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f8f8; color: var(--primary);">
                            <th style="padding: 0.7rem;">Order ID</th>
                            <th style="padding: 0.7rem;">Vendor</th>
                            <th style="padding: 0.7rem;">Product</th>
                            <th style="padding: 0.7rem;">Quantity</th>
                            <th style="padding: 0.7rem;">Total Amount</th>
                            <th style="padding: 0.7rem;">Status</th>
                            <th style="padding: 0.7rem;">Ordered At</th>
                            <th style="padding: 0.7rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendorOrders as $order)
                            <tr>
                                <td style="padding: 0.7rem;">#{{ $order->id }}</td>
                                <td style="padding: 0.7rem;">
                                    @if($order->vendor)
                                        {{ $order->vendor->name }}<br>
                                        <span style="color:#888; font-size:0.95em;">{{ $order->vendor->email }}</span>
                                    @else
                                        Vendor #{{ $order->vendor_id }}
                                    @endif
                                </td>
                                <td style="padding: 0.7rem;">{{ $order->product }}</td>
                                <td style="padding: 0.7rem;">{{ $order->quantity }}</td>
                                <td style="padding: 0.7rem;">shs {{ number_format(isset($productPrices[$order->product]) ? ($productPrices[$order->product] * $order->quantity) : 0, 2) }}</td>
                                <td style="padding: 0.7rem;">
                                    @php $status = $order->status ?: 'pending'; @endphp
                                    @if($status === 'pending')
                                        <span style="background: #e67e22; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Pending</span>
                                    @elseif($status === 'accepted')
                                        <span style="background: #27ae60; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Fulfilled</span>
                                    @elseif($status === 'rejected')
                                        <span style="background: #b71c1c; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Declined</span>
                                    @else
                                        <span style="background: #888; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                                <td style="padding: 0.7rem;">{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : '' }}</td>
                                @php
                                    $vendorImage = ($order->vendor && $order->vendor->profilePhotoPath)
                                        ? asset('storage/' . $order->vendor->profilePhotoPath)
                                        : asset('images/profile/vendor.jpeg');
                                    $orderData = [
                                        'id' => $order->id,
                                        'vendor_name' => $order->vendor->name ?? ('Vendor #' . $order->vendor_id),
                                        'vendor_email' => $order->vendor->email ?? null,
                                        'vendor_image' => $vendorImage,
                                        'product' => $order->product,
                                        'quantity' => $order->quantity,
                                        'total_amount' => isset($productPrices[$order->product]) ? ($productPrices[$order->product] * $order->quantity) : 0,
                                        'status' => $order->status,
                                        'ordered_at' => $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : ''
                                    ];
                                @endphp
                                <td style="padding: 0.7rem;"><button type="button" class="btn btn-primary view-order-btn" data-order='@json($orderData)'>View</button></td>
                                @if($order->status === 'pending')
                                    <td style="padding: 0.7rem;">
                                        <button type="button" class="btn btn-success open-confirm-modal" data-order-id="{{ $order->id }}" style="margin-left: 0.5rem;">Confirm</button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No vendor orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div id="invoices" class="tab-content" style="display:none;">
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
                            <td style="padding: 0.7rem;">shs {{ number_format(isset($productPrices[$order->product]) ? ($productPrices[$order->product] * $order->quantity) : 0, 2) }}</td>
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
    <style>
    .tab-link.active { background: var(--primary); color: #fff !important; }
    .tab-link.active[data-tab='vendor-orders'] { background: var(--accent) !important; color: #111 !important; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .status-badge { border-radius: 8px; font-size: 0.95rem; padding: 0.2rem 0.8rem; font-weight: 600; }
    .status-badge.status-pending { background: #fffbe6; color: #b45309; }
    .status-badge.status-fulfilled { background: #eafbe7; color: #16610e; }
    </style>
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
        <div style="background:#fff; border-radius:10px; max-width:400px; width:90vw; padding:2rem; position:relative; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
            <button id="closeConfirmOrderModal" style="position:absolute; top:10px; right:15px; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <form id="confirmOrderForm" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="confirmOrderId">
                <div style="margin-bottom:1rem;">
                    <label for="delivery_date" style="font-weight:600;">Delivery Date</label>
                    <input type="date" name="delivery_date" id="delivery_date" class="form-control" required style="width:100%;padding:0.5rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="delivery_address" style="font-weight:600;">Delivery Address</label>
                    <input type="text" name="delivery_address" id="delivery_address" class="form-control" required style="width:100%;padding:0.5rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="driver_name" style="font-weight:600;">Driver Name</label>
                    <input type="text" name="driver_name" id="driver_name" class="form-control" required style="width:100%;padding:0.5rem;">
                </div>
                <button type="submit" class="btn btn-success" style="width:100%;margin-top:1rem;">Confirm and Send Email</button>
            </form>
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
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                tabContents.forEach(c => c.style.display = 'none');
                this.classList.add('active');
                // Custom: force accent background and black text for Vendor Orders tab
                if(this.dataset.tab === 'vendor-orders') {
                    this.style.background = 'var(--accent)';
                    this.style.color = '#111';
                } else {
                    this.style.background = 'var(--primary)';
                    this.style.color = '#fff';
                }
                // Reset other tab styles
                tabLinks.forEach(l => {
                    if(l !== this) {
                        l.style.background = (l.dataset.tab === 'vendor-orders') ? '#f5f5f5' : 'var(--primary)';
                        l.style.color = (l.dataset.tab === 'vendor-orders') ? 'var(--primary)' : '#fff';
                    }
                });
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
        const confirmOrderForm = document.getElementById('confirmOrderForm');
        let currentOrderId = null;
        document.querySelectorAll('.open-confirm-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                document.getElementById('confirmOrderId').value = orderId;
                // Set the form action to the correct confirm route
                document.getElementById('confirmOrderForm').action = '/manufacturer/vendor-orders/' + orderId + '/confirm';
                document.getElementById('confirmOrderModal').style.display = 'flex';
            });
        });
        closeConfirmModal.addEventListener('click', function() {
            confirmModal.style.display = 'none';
        });
        confirmModal.addEventListener('click', function(e) {
            if (e.target === confirmModal) confirmModal.style.display = 'none';
        });
    });
    </script>
@endsection
