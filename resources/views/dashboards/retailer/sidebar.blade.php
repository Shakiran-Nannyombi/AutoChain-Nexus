<a href="{{ route('retailer.dashboard') }}" class="nav-item{{ request()->is('retailer/dashboard*') ? ' active' : '' }}">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="{{ route('retailer.stock-overview') }}" class="nav-item{{ request()->is('retailer/stock-overview') ? ' active' : '' }}">
    <i class="fas fa-warehouse"></i> Stock Overview
</a>
<a href="{{ route('retailer.sales-update') }}" class="nav-item{{ request()->is('retailer/sales-update') ? ' active' : '' }}">
    <i class="fas fa-edit"></i> Sales Update
</a>
<a href="{{ route('retailer.order-placement') }}" class="nav-item{{ request()->is('retailer/order-placement') ? ' active' : '' }}">
    <i class="fas fa-shopping-cart"></i> Order Placement
</a>
<a href="{{ route('user.reports') }}" class="nav-item{{ request()->is('user/reports') ? ' active' : '' }}">
    <i class="fas fa-file-alt"></i> Reports
</a>
<a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
<a href="{{ route('retailer.notifications') }}" class="nav-item{{ request()->is('retailer/notifications') ? ' active' : '' }}">
    <i class="fas fa-bell"></i> Notifications
</a>