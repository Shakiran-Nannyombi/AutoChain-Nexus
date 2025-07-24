<a href="{{ route('supplier.dashboard') }}" class="nav-item {{ request()->is('supplier/dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Control Panel
</a>
<a href="{{ route('supplier.stock-management') }}" class="nav-item {{ request()->is('supplier/stock-management*') ? 'active' : '' }}">
    <i class="fas fa-warehouse"></i> Check Inventory
</a>
<a href="{{ route('supplier.supplies.create') }}" class="nav-item {{ request()->is('supplier/supplies/create*') ? 'active' : '' }}">
    <i class="fas fa-plus"></i> Add New Supply
</a>
<a href="{{ route('supplier.checklist-receipt') }}" class="nav-item {{ request()->is('supplier/checklist-receipt*') ? 'active' : '' }}">
    <i class="fas fa-clipboard-check"></i> Checklist Receipt
</a>
<a href="{{ route('supplier.orders') }}" class="nav-item {{ request()->is('supplier/orders*') ? 'active' : '' }}">
    <i class="fas fa-shopping-cart"></i> View Orders
</a>
<a href="{{ route('supplier.shipments') }}" class="nav-item {{ request()->is('supplier/shipments*') ? 'active' : '' }}">
    <i class="fas fa-shipping-fast"></i> Track Shipments
</a>
<a href="{{ route('supplier.live-tracking') }}" class="nav-item {{ request()->is('supplier/live-tracking*') ? 'active' : '' }}">
    <i class="fas fa-map-marked-alt"></i> Live Tracking
</a>
<a href="{{ route('supplier.delivery-details') }}" class="nav-item {{ request()->is('supplier/delivery-details*') ? 'active' : '' }}">
    <i class="fas fa-truck"></i> Delivery History
</a>
<a href="{{ route('supplier.chat') }}" class="nav-item {{ request()->routeIs('supplier.chat') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>