@extends('layouts.dashboard')

@section('title', 'Delivery Management')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card vendor-delivery-dashboard">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Delivery Management
    </h2>
    
    <!-- Stat Cards Row -->
    <div class="delivery-stat-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, var(--primary), #0d3a07); color: #fff; padding: 1.5rem; border-radius: 14px;">
            <div class="stat-label"><i class="fas fa-clock"></i> Pending Deliveries</div>
            <div class="stat-value" style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0;">{{ $totalPending }}</div>
            <div class="stat-desc">Awaiting processing</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--accent), #4e2501); color: #fff; padding: 1.5rem; border-radius: 14px;">
            <div class="stat-label"><i class="fas fa-truck"></i> In Transit</div>
            <div class="stat-value" style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0;">{{ $totalInTransit }}</div>
            <div class="stat-desc">Currently being delivered</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--success), #0d4a07); color: #fff; padding: 1.5rem; border-radius: 14px;">
            <div class="stat-label"><i class="fas fa-check-circle"></i> Completed</div>
            <div class="stat-value" style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0;">{{ $totalCompleted }}</div>
            <div class="stat-desc">Successfully delivered</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--secondary), #b35400); color: #fff; padding: 1.5rem; border-radius: 14px;">
            <div class="stat-label"><i class="fas fa-shopping-cart"></i> Total Orders</div>
            <div class="stat-value" style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0;">{{ $totalOrders }}</div>
            <div class="stat-desc">From manufacturers</div>
        </div>
    </div>

    <!-- Delivery Tabs -->
    <div class="delivery-tabs" style="margin-bottom: 2rem;">
        <div class="tab-buttons" style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
            <button class="tab-btn active" data-tab="pending">Pending ({{ $pendingDeliveries->count() }})</button>
            <button class="tab-btn" data-tab="in-transit">In Transit ({{ $inTransitDeliveries->count() }})</button>
            <button class="tab-btn" data-tab="completed">Completed ({{ $completedDeliveries->count() }})</button>
            <button class="tab-btn" data-tab="orders">Orders ({{ $vendorOrders->count() }})</button>
        </div>

        <!-- Pending Deliveries Tab -->
        <div class="tab-content active" data-tab="pending">
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Car Model</th>
                            <th>Retailer</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingDeliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->id }}</td>
                            <td>{{ $delivery->car_model }}</td>
                            <td>{{ $delivery->retailer->name ?? 'Unknown' }}</td>
                            <td>{{ $delivery->quantity_received }}</td>
                            <td>
                                <span class="status-badge warning">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span>
                            </td>
                                                    <td>{{ $delivery->created_at ? \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="updateStatus({{ $delivery->id }}, 'to_be_shipped')">
                                Mark as Shipped
                            </button>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No pending deliveries</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- In Transit Deliveries Tab -->
        <div class="tab-content" data-tab="in-transit">
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Car Model</th>
                            <th>Retailer</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inTransitDeliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->id }}</td>
                            <td>{{ $delivery->car_model }}</td>
                            <td>{{ $delivery->retailer->name ?? 'Unknown' }}</td>
                            <td>{{ $delivery->quantity_received }}</td>
                            <td>
                                <span class="status-badge info">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span>
                            </td>
                                                    <td>{{ $delivery->created_at ? \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <button class="btn btn-sm btn-success" onclick="updateStatus({{ $delivery->id }}, 'delivered')">
                                Mark as Delivered
                            </button>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No in-transit deliveries</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Completed Deliveries Tab -->
        <div class="tab-content" data-tab="completed">
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Car Model</th>
                            <th>Retailer</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Completed</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedDeliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->id }}</td>
                            <td>{{ $delivery->car_model }}</td>
                            <td>{{ $delivery->retailer->name ?? 'Unknown' }}</td>
                            <td>{{ $delivery->quantity_received }}</td>
                            <td>
                                @if($delivery->status === 'accepted')
                                    <span class="status-badge success">Accepted</span>
                                @else
                                    <span class="status-badge danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $delivery->updated_at ? \Carbon\Carbon::parse($delivery->updated_at)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $delivery->created_at && $delivery->updated_at ? \Carbon\Carbon::parse($delivery->created_at)->diffForHumans(\Carbon\Carbon::parse($delivery->updated_at), true) : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No completed deliveries</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vendor Orders Tab -->
        <div class="tab-content" data-tab="orders">
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Manufacturer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Ordered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendorOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->manufacturer->name ?? 'Unknown' }}</td>
                            <td>{{ $order->product }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>
                                <span class="status-badge {{ $order->status === 'pending' ? 'warning' : 'success' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Upcoming Deliveries -->
    @if($upcomingDeliveries->count() > 0)
    <div class="upcoming-deliveries" style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="color: var(--primary); margin-bottom: 1rem;">Upcoming Deliveries (Next 7 Days)</h3>
        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Car Model</th>
                        <th>Retailer</th>
                        <th>Quantity</th>
                        <th>Expected Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingDeliveries as $delivery)
                    <tr>
                        <td>{{ $delivery->car_model }}</td>
                        <td>{{ $delivery->retailer->name ?? 'Unknown' }}</td>
                        <td>{{ $delivery->quantity_received }}</td>
                        <td>{{ $delivery->created_at ? \Carbon\Carbon::parse($delivery->created_at)->addDays(7)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="status-badge warning">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Delivery Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusUpdateForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="to_be_shipped">To Be Shipped</option>
                            <option value="to_be_delivered">To Be Delivered</option>
                            <option value="shipped">Shipped</option>
                        </select>
            </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tab functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabName = btn.dataset.tab;
            
            // Remove active class from all buttons and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked button and corresponding content
            btn.classList.add('active');
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
        });
    });
});

function updateStatus(stockId, status) {
    document.getElementById('status').value = status;
    document.getElementById('statusUpdateForm').action = '{{ route("vendor.delivery.update-status", ":id") }}'.replace(':id', stockId);
    new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
}
</script>

<style>
.tab-btn {
    background: #fff;
    border: 1px solid #ddd;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.tab-btn.active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-badge.success { background: #d4edda; color: #155724; }
.status-badge.warning { background: #fff3cd; color: #856404; }
.status-badge.danger { background: #f8d7da; color: #721c24; }
.status-badge.info { background: #d1ecf1; color: #0c5460; }
</style>
@endpush