@php $user = auth()->user(); @endphp
<div class="dashboard-header">
    <h1 class="dashboard-title">@yield('title', 'Dashboard')</h1>
    <div class="dashboard-header-right">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search users, pages..." />
        </div>
        @if($user && $user->role === 'admin')
            <i class="fas fa-bell"></i>
            <i class="fas fa-user"></i>
        @elseif($user && $user->role === 'manufacturer')
            <i class="fas fa-industry"></i>
            <i class="fas fa-user"></i>
        @elseif($user && $user->role === 'supplier')
            <i class="fas fa-truck"></i>
            <i class="fas fa-user"></i>
        @elseif($user && $user->role === 'retailer')
            <i class="fas fa-store"></i>
            <i class="fas fa-user"></i>
        @elseif($user && $user->role === 'analyst')
            <i class="fas fa-chart-line"></i>
            <i class="fas fa-user"></i>
        @else
            <i class="fas fa-user"></i>
        @endif
    </div>
</div> 