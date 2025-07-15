@extends('layouts.dashboard')

@section('title', 'Delivery Tracking')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Delivery Tracking
    </h2>
    <div class="delivery-tracking-grid" style="display: flex; min-height: 100vh;">
        <!-- Left: Delivery List -->
        <div class="tracking-list" style="width: 340px; background: #fafbfc; border-right: 1px solid #eee; padding: 1.5rem 0; overflow-y: auto;">
            <h3 style="margin-left: 2rem; color: var(--primary); font-size: 1.2rem; margin-bottom: 1.2rem;">Tracking Delivery</h3>
            <div id="deliveryList">
                <!-- Demo deliveries -->
            </div>
        </div>
        <!-- Center: Map -->
        <div class="tracking-map" style="flex: 1 1 0; min-width: 0; position: relative;">
            <div id="map" style="height: 100%; min-height: 500px;"></div>
        </div>
        <!-- Right: Info & Activity -->
        <div class="tracking-info" style="width: 340px; background: #fafbfc; border-left: 1px solid #eee; padding: 1.5rem 1.5rem 1.5rem 1rem; overflow-y: auto;">
            <div id="driverInfo"></div>
            <div id="activityFeed" style="margin-top: 2rem;"></div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
.status-badge.Delivery { background: #eafbe7; color: var(--primary); }
.status-badge.Transit { background: #e7f0fb; color: #2563eb; }
.status-badge.Pending { background: #fff6e5; color: #e67e22; }
.status-badge.Delayed { background: #fdeaea; color: var(--danger); }
.delivery-card .avatar {
    width: 38px; height: 38px; border-radius: 50%; object-fit: cover; margin-right: 0.7rem;
}
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
.tracking-info .info-card .avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 0.7rem;
}
.activity-feed {
    background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.2rem 1rem;
}
.activity-feed .activity-item {
    display: flex; align-items: flex-start; gap: 0.7rem; margin-bottom: 1rem;
}
.activity-feed .activity-dot {
    width: 10px; height: 10px; border-radius: 50%; margin-top: 0.3rem;
    background: var(--primary);
}
.activity-feed .activity-dot.delivered { background: var(--primary); }
.activity-feed .activity-dot.transit { background: #2563eb; }
.activity-feed .activity-dot.pending { background: #e67e22; }
.activity-feed .activity-dot.delayed { background: var(--danger); }
.activity-feed .activity-content { font-size: 0.98rem; color: var(--text-dark); }
.activity-feed .activity-time { font-size: 0.92rem; color: var(--text-light); margin-left: 0.5rem; }
</style>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@push('scripts')
<script>
// Demo data
const deliveries = [
  {
    id: '#3565432', status: 'Delivery',
    pickup: '4140 Parker Rd. Allentown, Mexico 31134',
    dropoff: '3517 W. Gray St. Utica, Pennsylvania 57867',
    retailer: { name: 'James Wilson', company: 'Auto Sales Network', avatar: '/images/profile/retailer.jpeg' },
    driver: { name: 'Philip Osborne', email: 'philiposborne@gmail.com', phone: '(208) 555-0112', avatar: '/images/profile/driver.jpeg' },
    activity: [
      { type: 'delivered', text: '4140 Parker Rd. Allentown, New Mexico 31134', time: '18 Jun 2024 at 08:04' },
      { type: 'delivered', text: '1901 Thornridge Cir. Shiloh, Hawaii 81063', time: '19 Jun 2024 at 09:20' },
      { type: 'delivered', text: '3517 W. Gray St. Utica, Pennsylvania 57867', time: '27 Jun 2024 at 20:15' },
    ],
    route: [ [40.608, -75.490], [40.712, -65.600], [50.800, -75.800] ]
  },
  {
    id: '#483920', status: 'Transit',
    pickup: '6391 Elgin St. Celina, Delaware 10299',
    dropoff: '8502 Preston Rd. Inglewood, Maine 98380',
    retailer: { name: 'Maria Garcia', company: 'Car Dealers United', avatar: '/images/profile/retailer.jpeg' },
    driver: { name: 'Philip Osborne', email: 'philiposborne@gmail.com', phone: '(208) 555-0112', avatar: '/images/profile/driver.jpeg' },
    activity: [
      { type: 'transit', text: '6391 Elgin St. Celina, Delaware 10299', time: '20 Jun 2024 at 10:00' },
      { type: 'transit', text: '8502 Preston Rd. Inglewood, Maine 98380', time: '21 Jun 2024 at 12:30' },
    ],
    route: [ [40.700, -75.500], [40.800, -75.600], [40.900, -75.700] ]
  },
  {
    id: '#1442654', status: 'Pending',
    pickup: '2572 Westheimer Rd. Santa Ana, Illinois 85486',
    dropoff: '6391 Elgin St. Celina, Delaware 10299',
    retailer: { name: 'Thomas Brown', company: 'Auto Showroom Elite', avatar: '/images/profile/retailer.jpeg' },
    driver: { name: 'Philip Osborne', email: 'philiposborne@gmail.com', phone: '(208) 555-0112', avatar: '/images/profile/driver.jpeg' },
    activity: [
      { type: 'pending', text: '2572 Westheimer Rd. Santa Ana, Illinois 85486', time: '22 Jun 2024 at 14:00' },
    ],
    route: [ [30.800, -75.700], [90.900, -75.800], [41.000, -75.900] ]
  },
  {
    id: '#9437291', status: 'Delivery',
    pickup: '1901 Thornridge Cir. Shiloh, Hawaii 81063',
    dropoff: '2715 Ash Dr. San Jose, South Dakota 83475',
    retailer: { name: 'Hiro Tanaka', company: 'Car Hub Japan', avatar: '/images/profile/retailer.jpeg' },
    driver: { name: 'Philip Osborne', email: 'philiposborne@gmail.com', phone: '(208) 555-0112', avatar: '/images/profile/driver.jpeg' },
    activity: [
      { type: 'delivered', text: '1901 Thornridge Cir. Shiloh, Hawaii 81063', time: '23 Jun 2024 at 16:00' },
      { type: 'delivered', text: '2715 Ash Dr. San Jose, South Dakota 83475', time: '24 Jun 2024 at 18:30' },
    ],
    route: [ [50.900, -75.800], [41.000, -95.900], [41.100, -86.000] ]
  },
];

function renderDeliveryList(selectedIdx = 0) {
  const list = deliveries.map((d, i) => `
    <div class="delivery-card${i === selectedIdx ? ' selected' : ''}" data-idx="${i}">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="font-weight: 700; color: var(--primary); font-size: 1.1rem;">${d.id}</div>
        <span class="status-badge ${d.status}">${d.status}</span>
      </div>
      <div style="margin: 0.7rem 0 0.5rem 0; font-size: 0.98rem; color: var(--text-dark);">
        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> ${d.pickup}<br>
        <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i> ${d.dropoff}
      </div>
      <div class="card-footer">
        <div class="retailer-info">
          <img src="${d.retailer.avatar}" class="avatar" alt="avatar" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name='+encodeURIComponent('${d.retailer.name}');">
          <div>
            <div style="font-weight: 600; color: var(--text-dark);">${d.retailer.name}</div>
            <div style="font-size: 0.92rem; color: var(--text-light);">Retailer</div>
            <div style="font-size: 0.92rem; color: var(--text-light);">${d.retailer.company}</div>
          </div>
        </div>
        <button class="icon-btn"><i class="fas fa-ellipsis-h"></i></button>
      </div>
    </div>
  `).join('');
  document.getElementById('deliveryList').innerHTML = list;
  // Add click listeners
  document.querySelectorAll('.delivery-card').forEach(card => {
    card.addEventListener('click', function() {
      const idx = parseInt(this.getAttribute('data-idx'));
      renderDeliveryList(idx);
      renderMap(idx);
      renderInfo(idx);
      renderActivity(idx);
    });
  });
}

function renderMap(idx = 0) {
  if (!window._leafletMap) {
    window._leafletMap = L.map('map').setView([40.7, -75.5], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap'
    }).addTo(window._leafletMap);
  }
  const map = window._leafletMap;
  map.eachLayer(function(layer) {
    if (layer instanceof L.Polyline || layer instanceof L.Marker) map.removeLayer(layer);
  });
  const d = deliveries[idx];
  // Route polyline
  const route = L.polyline(d.route, { color: '#2563eb', weight: 5, opacity: 0.8 }).addTo(map);
  // Pickup marker
  const pickup = L.marker(d.route[0], { title: 'Pickup' }).addTo(map);
  // Dropoff marker
  const dropoff = L.marker(d.route[d.route.length-1], { title: 'Dropoff' }).addTo(map);
  // Vehicle marker (at midpoint)
  const midIdx = Math.floor(d.route.length/2);
  const vehicle = L.circleMarker(d.route[midIdx], { radius: 14, color: 'var(--primary)', fillColor: 'var(--primary)', fillOpacity: 0.7 }).addTo(map);
  vehicle.bindTooltip('<i class="fas fa-truck"></i>', { permanent: true, direction: 'center', className: 'vehicle-tooltip' });
  map.fitBounds(route.getBounds(), { padding: [40, 40] });
}

function renderInfo(idx = 0) {
  const d = deliveries[idx];
  document.getElementById('driverInfo').innerHTML = `
    <div class="info-card">
      <div class="info-title">${d.driver.name} <span style="font-size:0.95rem; color:var(--text-light);">Driver</span></div>
      <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.7rem;">
        <img src="${d.driver.avatar}" class="avatar" alt="driver" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name='+encodeURIComponent('${d.driver.name}');">
        <div>
          <div style="font-size:0.98rem; color:var(--text-dark);">${d.driver.email}</div>
          <div style="font-size:0.98rem; color:var(--text-dark);">${d.driver.phone}</div>
        </div>
      </div>
    </div>
  `;
}

function renderActivity(idx = 0) {
  const d = deliveries[idx];
  document.getElementById('activityFeed').innerHTML = `
    <div class="activity-feed">
      <div style="font-weight:700; color:var(--primary); margin-bottom:0.7rem;">Activity</div>
      ${d.activity.map(a => `
        <div class="activity-item">
          <div class="activity-dot ${a.type}"></div>
          <div>
            <div class="activity-content">${a.text}</div>
            <div class="activity-time">${a.time}</div>
          </div>
        </div>
      `).join('')}
    </div>
  `;
}

document.addEventListener('DOMContentLoaded', function() {
  renderDeliveryList(0);
  renderMap(0);
  renderInfo(0);
  renderActivity(0);
});
</script>
@endpush
@endsection 