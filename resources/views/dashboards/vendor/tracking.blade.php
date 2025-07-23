@extends('layouts.dashboard')

@section('title', 'Delivery Tracking')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--text); letter-spacing: 0.01em;">Delivery Tracking</h2>
    </div>
    <!-- Status Legend -->
    <div style="margin-bottom: 1rem;">
        <span class="status-badge to_be_packed">To Be Packed</span>
        <span class="status-badge to_be_shipped">To Be Shipped</span>
        <span class="status-badge to_be_delivered">In Transit</span>
        <span class="status-badge accepted">Delivered</span>
        <span class="status-badge rejected">Rejected</span>
    </div>

    <div class="delivery-tracking-grid" style="display: flex; min-height: 70vh;">
        <!-- Left: Delivery List -->
        <div class="tracking-list" style="width: 340px; background: #fafbfc; border-right: 1px solid #eee; padding: 1.5rem 0; overflow-y: auto;">
            <h3 style="margin-left: 2rem; color: var(--primary); font-size: 1.2rem; margin-bottom: 1.2rem;">Active Deliveries</h3>
            <div id="deliveryList">
                @if($activeDeliveries->count() === 0)
                    <!-- Demo Deliveries -->
                    <div class="delivery-card selected" data-id="101">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="font-weight: 700; color: var(--primary); font-size: 1.1rem;">#101</div>
                            <span class="status-badge to_be_delivered">In Transit</span>
                        </div>
                        <div style="margin: 0.7rem 0 0.5rem 0; font-size: 0.98rem; color: var(--text-dark);">
                            <i class="fas fa-car" style="color: var(--primary);"></i> Toyota Corolla 2024<br>
                            <i class="fas fa-boxes" style="color: var(--accent);"></i> 20 units
                        </div>
                        <div class="progress" style="height: 8px; background: #e0e7ef; border-radius: 4px; margin: 0.5rem 0;">
                            <div style="width: 60%; background: var(--primary); height: 100%; border-radius: 4px;"></div>
                        </div>
                        <div class="card-footer">
                            <div class="retailer-info">
                                <div class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; margin-right: 0.7rem; font-weight: bold;">O</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-dark);">Olivia Turner</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">Retailer</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">Jul 20, 2025</div>
                                </div>
                            </div>
                            <button class="icon-btn" onclick="updateDemoInfo(101)"><i class="fas fa-info-circle"></i></button>
                        </div>
                    </div>
                    <div class="delivery-card" data-id="102">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="font-weight: 700; color: var(--primary); font-size: 1.1rem;">#102</div>
                            <span class="status-badge to_be_packed">To Be Packed</span>
                        </div>
                        <div style="margin: 0.7rem 0 0.5rem 0; font-size: 0.98rem; color: var(--text-dark);">
                            <i class="fas fa-car" style="color: var(--primary);"></i> Honda Civic 2024<br>
                            <i class="fas fa-boxes" style="color: var(--accent);"></i> 12 units
                        </div>
                        <div class="progress" style="height: 8px; background: #e0e7ef; border-radius: 4px; margin: 0.5rem 0;">
                            <div style="width: 20%; background: #e67e22; height: 100%; border-radius: 4px;"></div>
                        </div>
                        <div class="card-footer">
                            <div class="retailer-info">
                                <div class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; margin-right: 0.7rem; font-weight: bold;">E</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-dark);">Ethan Brooks</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">Retailer</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">Jul 19, 2025</div>
                                </div>
                            </div>
                            <button class="icon-btn" onclick="updateDemoInfo(102)"><i class="fas fa-info-circle"></i></button>
                        </div>
                    </div>
                    <div class="delivery-card" data-id="103">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="font-weight: 700; color: var(--primary); font-size: 1.1rem;">#103</div>
                            <span class="status-badge accepted">Delivered</span>
                        </div>
                        <div style="margin: 0.7rem 0 0.5rem 0; font-size: 0.98rem; color: var(--text-dark);">
                            <i class="fas fa-car" style="color: var(--primary);"></i> Ford F-150 2024<br>
                            <i class="fas fa-boxes" style="color: var(--accent);"></i> 8 units
                        </div>
                        <div class="progress" style="height: 8px; background: #e0e7ef; border-radius: 4px; margin: 0.5rem 0;">
                            <div style="width: 100%; background: #155724; height: 100%; border-radius: 4px;"></div>
                        </div>
                        <div class="card-footer">
                            <div class="retailer-info">
                                <div class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; margin-right: 0.7rem; font-weight: bold;">S</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-dark);">Sophia Bennett</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">Retailer</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">Jul 18, 2025</div>
                                </div>
                            </div>
                            <button class="icon-btn" onclick="updateDemoInfo(103)"><i class="fas fa-info-circle"></i></button>
                        </div>
                    </div>
                @else
                    @forelse($activeDeliveries as $index => $delivery)
                    <div class="delivery-card{{ $index === 0 ? ' selected' : '' }}" data-id="{{ $delivery->id }}">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="font-weight: 700; color: var(--primary); font-size: 1.1rem;">#{{ $delivery->id }}</div>
                            <span class="status-badge {{ $delivery->status }}">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span>
                        </div>
                        <div style="margin: 0.7rem 0 0.5rem 0; font-size: 0.98rem; color: var(--text-dark);">
                            <i class="fas fa-car" style="color: var(--primary);"></i> {{ $delivery->car_model }}<br>
                            <i class="fas fa-boxes" style="color: var(--accent);"></i> {{ $delivery->quantity_received }} units
                        </div>
                        <div class="progress" style="height: 8px; background: #e0e7ef; border-radius: 4px; margin: 0.5rem 0;">
                            <div style="width: {{ $delivery->progress_percentage }}%; background: var(--primary); height: 100%; border-radius: 4px;"></div>
                        </div>
                        <div class="card-footer">
                            <div class="retailer-info">
                                <div class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; margin-right: 0.7rem; font-weight: bold;">
                                    {{ substr($delivery->retailer->name ?? 'R', 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-dark);">{{ $delivery->retailer->name ?? 'Unknown Retailer' }}</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">Retailer</div>
                                    <div style="font-size: 0.92rem; color: var(--text-light);">{{ $delivery->created_at ? \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                            <button class="icon-btn" onclick="getDeliveryDetails({{ $delivery->id }})">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 2rem; color: var(--text-light);">
                        <i class="fas fa-truck" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        No active deliveries
                    </div>
                    @endforelse
                @endif
            </div>
        </div>

        <!-- Center: Map -->
        <div class="tracking-map" style="flex: 1 1 0; min-width: 0; position: relative;">
            <div id="map" style="height: 100%; min-height: 500px;"></div>
        </div>

        <!-- Right: Info & Activity -->
        <div class="tracking-info" style="width: 340px; background: #fafbfc; border-left: 1px solid #eee; padding: 1.5rem 1.5rem 1.5rem 1rem; overflow-y: auto;">
            <div id="deliveryInfo">
                <div class="info-card">
                    <div class="info-title">Delivery Information</div>
                    <div style="color: var(--text-light);">Select a delivery to view details</div>
                </div>
            </div>
            <div id="activityFeed" style="margin-top: 2rem;">
                <div class="activity-feed">
                    <div class="info-title">Recent Activity</div>
                    <div style="color: var(--text-light);">Select a delivery to view activity</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delivery Details Modal -->
<div class="modal fade" id="deliveryDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delivery Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="deliveryDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize map
    if (document.getElementById('map')) {
        window._leafletMap = L.map('map').setView([40.7, -75.5], 8);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(window._leafletMap);
    }

    // Add click listeners to delivery cards
    document.querySelectorAll('.delivery-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            document.querySelectorAll('.delivery-card').forEach(c => c.classList.remove('selected'));
            // Add selected class to clicked card
            this.classList.add('selected');
            
            const deliveryId = this.getAttribute('data-id');
            updateDeliveryInfo(deliveryId);
        });
    });

    // Select first delivery by default
    const firstDelivery = document.querySelector('.delivery-card');
    if (firstDelivery) {
        firstDelivery.click();
    }
});

function updateDeliveryInfo(deliveryId) {
    // Update delivery information panel
    const deliveryCard = document.querySelector(`[data-id="${deliveryId}"]`);
    if (deliveryCard) {
        const carModel = deliveryCard.querySelector('i.fa-car').nextSibling.textContent.trim();
        const quantity = deliveryCard.querySelector('i.fa-boxes').nextSibling.textContent.trim();
        const retailer = deliveryCard.querySelector('.retailer-info div').textContent.trim();
        const status = deliveryCard.querySelector('.status-badge').textContent.trim();
        const date = deliveryCard.querySelector('.retailer-info div:last-child').textContent.trim();

        document.getElementById('deliveryInfo').innerHTML = `
            <div class="info-card">
                <div class="info-title">Delivery #${deliveryId}</div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-weight: 600; margin-bottom: 0.5rem;">Car Model:</div>
                    <div style="color: var(--text-dark);">${carModel}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-weight: 600; margin-bottom: 0.5rem;">Quantity:</div>
                    <div style="color: var(--text-dark);">${quantity}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-weight: 600; margin-bottom: 0.5rem;">Retailer:</div>
                    <div style="color: var(--text-dark);">${retailer}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-weight: 600; margin-bottom: 0.5rem;">Status:</div>
                    <div style="color: var(--text-dark);">${status}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-weight: 600; margin-bottom: 0.5rem;">Created:</div>
                    <div style="color: var(--text-dark);">${date}</div>
                </div>
            </div>
        `;

        // Update activity feed
        document.getElementById('activityFeed').innerHTML = `
            <div class="activity-feed">
                <div class="info-title">Delivery Timeline</div>
                <div class="activity-item">
                    <div class="activity-dot delivered"></div>
                    <div>
                        <div class="activity-content">Order created</div>
                        <div class="activity-time">${date}</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot transit"></div>
                    <div>
                        <div class="activity-content">Processing in warehouse</div>
                        <div class="activity-time">${date}</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot ${status.toLowerCase().includes('shipped') ? 'transit' : 'pending'}"></div>
                    <div>
                        <div class="activity-content">${status}</div>
                        <div class="activity-time">In progress</div>
                    </div>
                </div>
            </div>
        `;
    }
}

function getDeliveryDetails(deliveryId) {
    fetch(`{{ route('vendor.tracking.details', ':id') }}`.replace(':id', deliveryId))
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Delivery Information</h6>
                        <p><strong>ID:</strong> ${data.delivery.id}</p>
                        <p><strong>Car Model:</strong> ${data.delivery.car_model}</p>
                        <p><strong>Quantity:</strong> ${data.delivery.quantity_received}</p>
                        <p><strong>Status:</strong> ${data.delivery.status}</p>
                        <p><strong>Retailer:</strong> ${data.delivery.retailer?.name || 'Unknown'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Timeline</h6>
                        ${data.timeline.map(event => `
                            <div style="margin-bottom: 0.5rem;">
                                <strong>${event.event}:</strong> ${event.timestamp ? new Date(event.timestamp).toLocaleString() : 'Pending'}
                            </div>
                        `).join('')}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Progress</h6>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: ${data.progress_percentage}%" 
                                 aria-valuenow="${data.progress_percentage}" aria-valuemin="0" aria-valuemax="100">
                                ${data.progress_percentage}%
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('deliveryDetailsContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('deliveryDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error fetching delivery details:', error);
            alert('Error loading delivery details');
        });
}
</script>

<style>
.tracking-list .delivery-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    margin: 0 1.5rem 1.2rem 1.5rem;
    padding: 1.2rem 1rem;
    border-left: 4px solid var(--primary-light);
    transition: border 0.2s, box-shadow 0.2s;
    cursor: pointer;
    position: relative;
}

.delivery-card.selected {
    border-left: 4px solid var(--accent);
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.delivery-card .status-badge {
    border-radius: 8px;
    font-size: 0.95rem;
    padding: 0.2rem 0.8rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.status-badge.to_be_packed { background: #fff6e5; color: #e67e22; }
.status-badge.to_be_shipped { background: #e7f0fb; color: #2563eb; }
.status-badge.to_be_delivered { background: #eafbe7; color: var(--primary); }
.status-badge.accepted { background: #d4edda; color: #155724; }
.status-badge.rejected { background: #f8d7da; color: #721c24; }

.delivery-card .card-footer {
    display: flex; align-items: center; margin-top: 1rem;
}

.delivery-card .card-footer .retailer-info {
    display: flex; align-items: center; flex: 1;
}

.delivery-card .card-footer .icon-btn {
    background: #f5f5f5; border: none; border-radius: 8px; padding: 0.4rem 0.7rem; margin-left: 0.5rem; cursor: pointer;
}

.tracking-info .info-card {
    background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.2rem 1rem; margin-bottom: 1.2rem;
}

.tracking-info .info-title {
    font-weight: 700; color: var(--primary); font-size: 1.1rem; margin-bottom: 0.7rem;
}

.activity-feed {
    background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.2rem 1rem;
}

.activity-feed .activity-item {
    display: flex; align-items: flex-start; gap: 0.7rem; margin-bottom: 1rem;
}

.activity-feed .activity-dot {
    width: 10px; height: 10px; border-radius: 50%; margin-top: 0.3rem;
}

.activity-feed .activity-dot.delivered { background: var(--primary); }
.activity-feed .activity-dot.transit { background: #2563eb; }
.activity-feed .activity-dot.pending { background: #e67e22; }
.activity-feed .activity-dot.delayed { background: var(--danger); }

.activity-feed .activity-content { font-size: 0.98rem; color: var(--text-dark); }
.activity-feed .activity-time { font-size: 0.92rem; color: var(--text-light); margin-left: 0.5rem; }
</style>
@endpush