@extends('layouts.dashboard')

@section('title', 'Checklist Receipt')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--text); margin-bottom: 1rem; font-weight: 800; font-size: 2rem;">
        Checklist Receipt
    </h2>

    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
        <input type="text" id="supplier-search" placeholder="Search by manufacturer..." style="padding: 0.6rem 1rem; border: 1px solid #b3b3b3; border-radius: 7px; font-size: 1rem;">
        <select id="status-filter" style="padding: 0.6rem 2rem; border: 1px solid #b3b3b3; border-radius: 7px; font-size: 1rem;">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="fulfilled">Fulfilled</option>
            <option value="in_transit">In Transit</option>
            <option value="declined">Declined</option>
        </select>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;" id="checklist-grid">
        @forelse($checklists as $checklist)
            <div class="checklist-card" data-status="{{ $checklist->status }}" data-manufacturer="{{ $checklist->manufacturer_id }}" style="background: white; border-left: 5px solid var(--orange); padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div class="manufacturer-label" style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-industry"></i> From Manufacturer #{{ $checklist->manufacturer_id }}
                </div>
                <div style="font-size: 0.9rem; color: #555;">Requested Materials:</div>
                <ul style="margin-left: 1rem; margin-top: 0.5rem;">
                    @php
                        $materials = is_array($checklist->materials_requested)
                            ? $checklist->materials_requested
                            : (json_decode($checklist->materials_requested, true) ?? []);
                    @endphp
                    @foreach($materials as $material => $qty)
                        <li>{{ $material }}: <strong>{{ $qty }}</strong></li>
                    @endforeach
                </ul>
                <div style="margin-top: 1rem;">
                    @if($checklist->status === 'pending')
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="confirmOrder({{ $checklist->id }})" style="padding: 0.5rem 1rem; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-check"></i> Confirm
                            </button>
                            <button onclick="rejectOrder({{ $checklist->id }})" style="padding: 0.5rem 1rem; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    @elseif($checklist->status === 'fulfilled')
                        @php
                            $delivery = \App\Models\Delivery::where('supplier_id', session('user_id'))
                                ->where('checklist_request_id', $checklist->id)
                                ->first();
                        @endphp
                        @if(!$delivery)
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <span style="display: inline-block; padding: 0.3rem 0.6rem; background: #28a745; color: white; font-size: 0.85rem; border-radius: 5px; margin-bottom: 0.5rem;">
                                    <i class="fas fa-check"></i> Confirmed
                                </span>
                                <button onclick="shipOrder({{ $checklist->id }})" style="padding: 0.4rem 0.8rem; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem;">
                                    <i class="fas fa-shipping-fast"></i> Ship
                                </button>
                            </div>
                        @elseif($delivery->status === 'in_transit')
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <span style="display: inline-block; padding: 0.3rem 0.6rem; background: #007bff; color: white; font-size: 0.85rem; border-radius: 5px; margin-bottom: 0.5rem;">
                                    <i class="fas fa-truck"></i> In Transit
                                </span>
                                <button onclick="deliverOrder({{ $checklist->id }})" style="padding: 0.4rem 0.8rem; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem;">
                                    <i class="fas fa-check-circle"></i> Deliver
                                </button>
                            </div>
                        @elseif($delivery->status === 'delivered')
                            <span style="display: inline-block; padding: 0.3rem 0.6rem; background: #17a2b8; color: white; font-size: 0.85rem; border-radius: 5px;">
                                <i class="fas fa-truck"></i> Awaiting Confirmation
                            </span>
                        @elseif($delivery->status === 'completed')
                            <span style="display: inline-block; padding: 0.3rem 0.6rem; background: #6c757d; color: white; font-size: 0.85rem; border-radius: 5px;">
                                <i class="fas fa-check-double"></i> Order Complete
                            </span>
                        @endif
                    @elseif($checklist->status === 'declined')
                        <span style="display: inline-block; padding: 0.3rem 0.6rem; background: #dc3545; color: white; font-size: 0.85rem; border-radius: 5px;">
                            <i class="fas fa-times"></i> Rejected
                        </span>
                    @else
                        <span style="display: inline-block; padding: 0.3rem 0.6rem; background: #6c757d; color: white; font-size: 0.85rem; border-radius: 5px;">
                            <i class="fas fa-question"></i> Unknown Status
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div style="background: var(--light-cyan); padding: 2rem; text-align: center; border-radius: 10px; color: #555;">
                <i class="fas fa-inbox" style="font-size: 2rem; color: var(--deep-purple);"></i>
                <p style="margin-top: 0.5rem;">No checklist requests yet. They will appear here when sent by a manufacturer.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 400px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Confirm Order</h3>
        <p>Are you sure you want to confirm this order?</p>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button onclick="closeModal()" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px;">Cancel</button>
            <button onclick="submitConfirm()" style="padding: 0.5rem 1rem; background: #28a745; color: white; border: none; border-radius: 6px;">Confirm</button>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 400px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Reject Order</h3>
        <p>Please provide a reason for rejection:</p>
        <textarea id="rejectReason" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; margin: 1rem 0; min-height: 80px;" placeholder="Enter rejection reason..."></textarea>
        <div style="display: flex; gap: 1rem;">
            <button onclick="closeModal()" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px;">Cancel</button>
            <button onclick="submitReject()" style="padding: 0.5rem 1rem; background: #dc3545; color: white; border: none; border-radius: 6px;">Reject</button>
        </div>
    </div>
</div>

<!-- Ship Modal -->
<div id="shipModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 400px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Ship Order</h3>
        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Driver Name:</label>
            <input type="text" id="driverName" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" placeholder="Enter driver name">
        </div>
        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Destination:</label>
            <input type="text" id="destination" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" placeholder="Enter destination">
        </div>
        <div style="display: flex; gap: 1rem;">
            <button onclick="closeModal()" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px;">Cancel</button>
            <button onclick="submitShip()" style="padding: 0.5rem 1rem; background: #007bff; color: white; border: none; border-radius: 6px;">Ship</button>
        </div>
    </div>
</div>

<!-- Deliver Modal -->
<div id="deliverModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 400px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Confirm Delivery</h3>
        <p>Are you sure you want to mark this order as delivered?</p>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button onclick="closeModal()" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px;">Cancel</button>
            <button onclick="submitDeliver()" style="padding: 0.5rem 1rem; background: #28a745; color: white; border: none; border-radius: 6px;">Confirm Delivery</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('supplier-search');
        const statusFilter = document.getElementById('status-filter');
        const cards = document.querySelectorAll('.checklist-card');
        function filterCards() {
            const search = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            cards.forEach(card => {
                const manufacturer = card.querySelector('.manufacturer-label').textContent.toLowerCase();
                const cardStatus = card.getAttribute('data-status');
                
                // Determine if card is shipped (has In Transit or Awaiting Confirmation status)
                const hasShippedStatus = card.querySelector('span')?.textContent.includes('In Transit') || 
                                       card.querySelector('span')?.textContent.includes('Awaiting Confirmation');
                
                let show = true;
                if (search && !manufacturer.includes(search)) show = false;
                
                if (status) {
                    if (status === 'in_transit') {
                        show = card.innerHTML.includes('In Transit');
                    } else {
                        show = cardStatus === status;
                    }
                }
                
                card.style.display = show ? '' : 'none';
            });
        }
        searchInput.addEventListener('input', filterCards);
        statusFilter.addEventListener('change', filterCards);
    });
    
    let currentOrderId = null;
    
    function confirmOrder(orderId) {
        currentOrderId = orderId;
        document.getElementById('confirmModal').style.display = 'flex';
    }
    
    function rejectOrder(orderId) {
        currentOrderId = orderId;
        document.getElementById('rejectModal').style.display = 'flex';
    }
    
    function closeModal() {
        document.getElementById('confirmModal').style.display = 'none';
        document.getElementById('rejectModal').style.display = 'none';
        document.getElementById('shipModal').style.display = 'none';
        document.getElementById('deliverModal').style.display = 'none';
        currentOrderId = null;
    }
    
    function shipOrder(orderId) {
        currentOrderId = orderId;
        document.getElementById('shipModal').style.display = 'flex';
    }
    
    function deliverOrder(orderId) {
        currentOrderId = orderId;
        document.getElementById('deliverModal').style.display = 'flex';
    }
    
    function submitConfirm() {
        if (currentOrderId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/supplier/checklist-receipt/confirm/${currentOrderId}`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function submitReject() {
        const reason = document.getElementById('rejectReason').value;
        if (currentOrderId && reason.trim()) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/supplier/checklist-receipt/reject/${currentOrderId}`;
            form.innerHTML = '@csrf <input type="hidden" name="reason" value="' + reason + '">';
            document.body.appendChild(form);
            form.submit();
        } else {
            alert('Please provide a reason for rejection.');
        }
    }
    
    function submitShip() {
        const driver = document.getElementById('driverName').value;
        const destination = document.getElementById('destination').value;
        if (currentOrderId && driver.trim() && destination.trim()) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/supplier/checklist-receipt/ship/${currentOrderId}`;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="driver" value="' + driver + '"><input type="hidden" name="destination" value="' + destination + '">';
            document.body.appendChild(form);
            form.submit();
        } else {
            alert('Please provide driver name and destination.');
        }
    }
    
    function submitDeliver() {
        if (currentOrderId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/supplier/checklist-receipt/deliver/${currentOrderId}`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
