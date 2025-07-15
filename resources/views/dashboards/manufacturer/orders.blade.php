@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem;"> Orders Management</h2>
        <div class="tabs-container" style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
            <button class="tab-link active" data-tab="supplier-orders" style="padding: 0.7rem 2rem; border: none; border-radius: 8px; background: var(--primary); color: #fff; font-weight: 600; cursor: pointer;">Supplier Orders</button>
            <button class="tab-link" data-tab="vendor-orders" style="padding: 0.7rem 2rem; border: none; border-radius: 8px; background: #f5f5f5; color: var(--primary); font-weight: 600; cursor: pointer;">Vendor Orders</button>
        </div>
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        <div id="supplier-orders" class="tab-content active">
            <h3 style="color: var(--primary); font-size: 1.2rem; margin-bottom: 1rem;">Supplier Orders</h3>
            <!-- Summary Cards -->
            <div style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <div style="background: #f5f5f5; border-radius: 10px; padding: 1.2rem 2rem; min-width: 180px; text-align: center;">
                    <div style="font-size: 1.2rem; color: var(--primary); font-weight: 700;">{{ $supplierOrders->count() }}</div>
                    <div style="font-size: 0.98rem; color: #555;">Total Orders</div>
                </div>
                <div style="background: #f5f5f5; border-radius: 10px; padding: 1.2rem 2rem; min-width: 180px; text-align: center;">
                    <div style="font-size: 1.2rem; color: #e67e22; font-weight: 700;">{{ $supplierOrders->where('status','pending')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #555;">Pending</div>
                </div>
                <div style="background: #f5f5f5; border-radius: 10px; padding: 1.2rem 2rem; min-width: 180px; text-align: center;">
                    <div style="font-size: 1.2rem; color: #27ae60; font-weight: 700;">{{ $supplierOrders->where('status','fulfilled')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #555;">Fulfilled</div>
                </div>
                <div style="background: #f5f5f5; border-radius: 10px; padding: 1.2rem 2rem; min-width: 180px; text-align: center;">
                    <div style="font-size: 1.2rem; color: #c0392b; font-weight: 700;">{{ $supplierOrders->where('status','cancelled')->count() }}</div>
                    <div style="font-size: 0.98rem; color: #555;">Cancelled</div>
                </div>
            </div>
            <!-- Status Filter Tabs -->
            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                <a href="?" class="tab-link {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="?status=pending" class="tab-link {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
                <a href="?status=fulfilled" class="tab-link {{ request('status') === 'fulfilled' ? 'active' : '' }}">Fulfilled</a>
                <a href="?status=cancelled" class="tab-link {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled</a>
            </div>
            <!-- Search Bar -->
            <form method="GET" style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by supplier..." style="padding: 0.6rem 1rem; border-radius: 6px; border: 1px solid #ccc; min-width: 220px;">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <button type="submit" style="padding: 0.6rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 6px;">Search</button>
            </form>
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
                                @if($order->status === 'pending')
                                    <span style="background: #e67e22; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Pending</span>
                                @elseif($order->status === 'fulfilled')
                                    <span style="background: #27ae60; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Fulfilled</span>
                                @elseif($order->status === 'cancelled')
                                    <span style="background: #c0392b; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Cancelled</span>
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
            .tab-content { display: none; }
            .tab-content.active { display: block; }
            .status-badge { border-radius: 8px; font-size: 0.95rem; padding: 0.2rem 0.8rem; font-weight: 600; }
            .status-badge.status-pending { background: #fffbe6; color: #b45309; }
            .status-badge.status-fulfilled { background: #eafbe7; color: #16610e; }
            .tab-link { padding: 0.6rem 1.5rem; border: none; border-radius: 6px; background: #f5f5f5; color: var(--primary); font-weight: 600; text-decoration: none; margin-right: 0.5rem; transition: background 0.2s; }
            .tab-link.active, .tab-link:hover { background: var(--primary); color: #fff; }
            </style>
        </div>
        <div id="vendor-orders" class="tab-content" style="display: none;">
            <h3 style="color: var(--primary); font-size: 1.2rem; margin-bottom: 1rem;">Vendor Orders</h3>
            <table class="order-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f8f8; color: var(--primary);">
                        <th>ID</th>
                        <th>Vendor</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Ordered At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendorOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->vendor_id }}</td>
                            <td>{{ $order->product }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d') : '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No vendor orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
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
    });
    </script>
    <style>
    .tab-link.active { background: var(--primary); color: #fff !important; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .status-badge { border-radius: 8px; font-size: 0.95rem; padding: 0.2rem 0.8rem; font-weight: 600; }
    .status-badge.status-pending { background: #fffbe6; color: #b45309; }
    .status-badge.status-fulfilled { background: #eafbe7; color: #16610e; }
    </style>
@endsection
