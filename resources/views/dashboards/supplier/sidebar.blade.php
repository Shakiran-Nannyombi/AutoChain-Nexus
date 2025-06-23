<a href="{{ route('supplier.dashboard') }}" class="nav-item {{ request()->is('supplier/dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Dashboard
</a>
<a href="{{ route('supplier.stock-management') }}" class="nav-item {{ request()->is('supplier/stock-management*') ? 'active' : '' }}">
    <i class="fas fa-boxes"></i> Stock Management
</a>
<a href="{{ route('supplier.checklist-receipt') }}" class="nav-item {{ request()->is('supplier/checklist-receipt*') ? 'active' : '' }}">
    <i class="fas fa-clipboard-check"></i> Checklist Receipt
</a>
<a href="{{ route('supplier.delivery-history') }}" class="nav-item {{ request()->is('supplier/delivery-history*') ? 'active' : '' }}">
    <i class="fas fa-truck"></i> Delivery History
</a>
<a href="{{ route('supplier.chat') }}" class="nav-item {{ request()->is('supplier/chat*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
<a href="{{ route('supplier.notifications') }}" class="nav-item {{ request()->is('supplier/notifications*') ? 'active' : '' }}">
    <i class="fas fa-bell"></i> Notifications
</a>
<a href="{{ route('supplier.settings') }}" class="nav-item {{ request()->is('supplier/settings*') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a> 