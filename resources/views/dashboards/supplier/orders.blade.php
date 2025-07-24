@extends('layouts.dashboard')

@section('title', 'View Orders')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); margin-bottom: 1rem; font-weight: 800; font-size: 2rem;">
            Orders Management
        </h2>
        
        <!-- Orders Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Order ID</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Manufacturer</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Materials</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Date</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem; font-weight: 600;">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 1rem;">{{ $order->manufacturer->name ?? 'N/A' }}</td>
                        <td style="padding: 1rem;">
                            @if($order->materials_requested)
                                @php
                                    $materials = is_array($order->materials_requested)
                                        ? $order->materials_requested
                                        : (json_decode($order->materials_requested, true) ?? []);
                                @endphp
                                @foreach($materials as $material => $quantity)
                                    <span style="background: #e3f2fd; color: #1976d2; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.85rem; margin-right: 0.5rem;">
                                        {{ $material }}: {{ $quantity }}
                                    </span>
                                @endforeach
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($order->status === 'pending')
                                <span style="background: #fff3cd; color: #856404; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Pending</span>
                            @elseif($order->status === 'fulfilled')
                                <span style="background: #d4edda; color: #155724; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Fulfilled</span>
                            @elseif($order->status === 'cancelled')
                                <span style="background: #f8d7da; color: #721c24; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Cancelled</span>
                            @else
                                <span style="background: #d1ecf1; color: #0c5460; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #666;">{{ $order->created_at->format('M d, Y') }}</td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button onclick="viewOrder({{ $order->id }})" style="padding: 0.3rem 0.8rem; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                @if($order->status === 'pending')
                                <button onclick="editOrder({{ $order->id }})" style="padding: 0.3rem 0.8rem; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #666;">
                            <i class="fas fa-shopping-cart" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                            <div>No orders found</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<!-- View Order Modal -->
<div id="viewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Order Details</h3>
        <div id="orderDetails"></div>
        <button onclick="closeModal()" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px; margin-top: 1rem;">Close</button>
    </div>
</div>

<!-- Edit Order Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Edit Order</h3>
        <form id="editForm">
            <div id="editFields"></div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="button" onclick="closeModal()" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px;">Cancel</button>
                <button type="button" onclick="saveOrder()" style="padding: 0.5rem 1rem; background: #28a745; color: white; border: none; border-radius: 6px;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentOrderId = null;
const orders = {!! json_encode($orders->toArray()) !!};

function viewOrder(orderId) {
    const order = orders.find(o => o.id === orderId);
    if (order) {
        let materials = 'No materials specified';
        if (order.materials_requested) {
            try {
                const materialsObj = typeof order.materials_requested === 'string' ? 
                    JSON.parse(order.materials_requested) : order.materials_requested;
                materials = Object.entries(materialsObj).map(([material, qty]) => 
                    `<span style="display: inline-block; background: #e3f2fd; color: #1976d2; padding: 0.2rem 0.5rem; border-radius: 4px; margin: 0.2rem;">${material}: ${qty}</span>`
                ).join('');
            } catch (e) {
                materials = 'Invalid materials data';
            }
        }
        
        document.getElementById('orderDetails').innerHTML = `
            <div style="margin-bottom: 1rem;">
                <p style="margin-bottom: 0.5rem;"><strong>Order ID:</strong> #ORD-${String(order.id).padStart(3, '0')}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Manufacturer:</strong> ${order.manufacturer ? order.manufacturer.name : 'N/A'}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Status:</strong> <span style="text-transform: capitalize;">${order.status}</span></p>
                <p style="margin-bottom: 0.5rem;"><strong>Materials:</strong></p>
                <div style="margin-left: 1rem;">${materials}</div>
                <p style="margin-bottom: 0.5rem; margin-top: 1rem;"><strong>Date:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
            </div>
        `;
        document.getElementById('viewModal').style.display = 'flex';
    }
}

function editOrder(orderId) {
    const order = orders.find(o => o.id === orderId);
    if (order) {
        currentOrderId = orderId;
        let fieldsHtml = '';
        
        if (order.materials_requested) {
            try {
                const materialsObj = typeof order.materials_requested === 'string' ? 
                    JSON.parse(order.materials_requested) : order.materials_requested;
                Object.entries(materialsObj).forEach(([material, qty]) => {
                    fieldsHtml += `
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">${material}:</label>
                            <input type="number" name="${material}" value="${qty}" min="1" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    `;
                });
            } catch (e) {
                fieldsHtml = '<p style="color: red;">Error loading materials data</p>';
            }
        } else {
            fieldsHtml = '<p>No materials to edit</p>';
        }
        
        document.getElementById('editFields').innerHTML = fieldsHtml;
        document.getElementById('editModal').style.display = 'flex';
    }
}

function closeModal() {
    document.getElementById('viewModal').style.display = 'none';
    document.getElementById('editModal').style.display = 'none';
    currentOrderId = null;
}

function saveOrder() {
    if (currentOrderId) {
        const inputs = document.querySelectorAll('#editFields input[type="number"]');
        const materials = {};
        
        inputs.forEach(input => {
            if (input.name && input.value) {
                materials[input.name] = parseInt(input.value) || 0;
            }
        });
        
        if (Object.keys(materials).length === 0) {
            alert('Please enter valid quantities for materials.');
            return;
        }
        
        const hiddenForm = document.createElement('form');
        hiddenForm.method = 'POST';
        hiddenForm.action = `/supplier/orders/${currentOrderId}/update`;
        hiddenForm.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="materials" value='${JSON.stringify(materials)}'>
        `;
        document.body.appendChild(hiddenForm);
        hiddenForm.submit();
    }
}
</script>

@endsection