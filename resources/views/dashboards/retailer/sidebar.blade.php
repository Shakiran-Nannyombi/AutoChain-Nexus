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
    <i class="fas fa-shopping-cart"></i> Place Order
</a>
<a href="{{ route('retailer.orders') }}" class="nav-item{{ request()->is('retailer/my-orders*') ? ' active' : '' }}">
    <i class="fas fa-list"></i> My Orders
</a>
<a href="{{ route('retailer.reports') }}" class="nav-item{{ request()->is('retailer/reports') ? ' active' : '' }}">
    <i class="fas fa-file-alt"></i> Reports
</a>
<a href="{{ route('retailer.chat') }}" class="nav-item{{ request()->is('retailer/chat') ? ' active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
