<a href="{{ route('customer.dashboard') }}" class="nav-item {{ request()->is('customer/dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Dashboard
</a>
<a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
<a href="{{ route('customer.settings') }}" class="nav-item {{ request()->is('customer/settings*') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>