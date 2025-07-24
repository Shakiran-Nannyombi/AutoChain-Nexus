<a href="{{ route('vendor.dashboard') }}" class="nav-item {{ request()->is('vendor/dashboard*') ? 'active' : '' }}">
    <i class="fas fa-store"></i> Control Panel
</a>

<a href="{{ route('vendor.products') }}" class="nav-item {{ request()->is('vendor/products*') ? 'active' : '' }}">
    <i class="fas fa-box"></i> Products
</a>

<a href="{{ route('vendor.manufacturer-orders') }}" class="nav-item {{ request()->is('vendor/manufacturer-orders*') ? 'active' : '' }}">
    <i class="fas fa-list-check"></i> Orders
</a>

<a href="{{ route('vendor.retailer-orders.index') }}" class="nav-item {{ request()->is('vendor/orders*') ? 'active' : '' }}">
    <i class="fas fa-shopping-cart"></i> Orders Management
</a>

<a href="{{ route('vendor.warehouse') }}" class="nav-item {{ request()->is('vendor/warehouse*') ? 'active' : '' }}">
    <i class="fas fa-warehouse"></i> Warehouse Access
</a>

<a href="{{ route('vendor.tracking') }}" class="nav-item {{ request()->is('vendor/de*') ? 'active' : '' }}">
    <i class="fas fa-route"></i> Delivery Tracking
</a>

<a href="{{ route('vendor.customer-segmentation') }}" class="nav-item {{ request()->is('vendor/customer-segmentation*') ? 'active' : '' }}">
    <i class="fas fa-users"></i> Segmentation
</a>

<a href="{{ route('vendor.analytics') }}" class="nav-item {{ request()->is('vendor/analytics*') ? 'active' : '' }}">
    <i class="fas fa-chart-bar"></i> Analytics
</a>

<a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>


