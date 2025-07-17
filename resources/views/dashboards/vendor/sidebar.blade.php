<a href="{{ route('vendor.dashboard') }}" class="nav-item {{ request()->is('vendor/dashboard*') ? 'active' : '' }}">
    <i class="fas fa-store"></i> Control Panel
</a>

<a href="{{ route('vendor.warehouse') }}" class="nav-item {{ request()->is('vendor/warehouse*') ? 'active' : '' }}">
    <i class="fas fa-warehouse"></i> Warehouse Access
</a>

<a href="{{ route('vendor.delivery') }}" class="nav-item {{ request()->is('vendor/delivery*') ? 'active' : '' }}">
    <i class="fas fa-truck"></i> Car Delivery
</a>

<a href="{{ route('vendor.tracking') }}" class="nav-item {{ request()->is('vendor/tracking*') ? 'active' : '' }}">
    <i class="fas fa-route"></i> Delivery Tracking
</a>

<a href="{{ route('vendor.products') }}" class="nav-item {{ request()->is('vendor/products*') ? 'active' : '' }}">
    <i class="fas fa-box"></i> Products
</a>

<a href="{{ route('vendor.orders') }}" class="nav-item {{ request()->is('vendor/orders*') ? 'active' : '' }}">
    <i class="fas fa-shopping-cart"></i> Orders to Manufacturers
</a>

<a href="{{ route('vendor.retailer-orders.index') }}" class="nav-item {{ request()->is('vendor/retailer-orders*') ? 'active' : '' }}">
    <i class="fas fa-list-check"></i> Retailer Orders
</a>

<a href="{{ route('vendor.analytics') }}" class="nav-item {{ request()->is('vendor/analytics*') ? 'active' : '' }}">
    <i class="fas fa-chart-bar"></i> Analytics
</a>

<a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>

<a href="{{ route('vendor.settings') }}" class="nav-item {{ request()->is('vendor/settings*') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>
