<a href="{{ route('vendor.dashboard') }}" class="nav-item {{ request()->is('vendor/dashboard*') ? 'active' : '' }}">
    <i class="fas fa-tachometer-alt"></i> Dashboard
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

<a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>

<a href="{{ route('vendor.notifications') }}" class="nav-item {{ request()->is('vendor/notifications*') ? 'active' : '' }}">
    <i class="fas fa-bell"></i> Notifications
</a>

<a href="{{ route('vendor.settings') }}" class="nav-item {{ request()->is('vendor/settings*') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>