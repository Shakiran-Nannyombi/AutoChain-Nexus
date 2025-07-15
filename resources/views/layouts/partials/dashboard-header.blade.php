@php $user = auth()->user(); @endphp
@php
    if (!isset($allNotifications)) $allNotifications = collect();
@endphp
@if($user && $user->role === 'vendor')
<div class="header vendor-header">
    <div class="header-left">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="dashboard-title" style="color: #0F2C67;">
             @yield('title', 'Vendor Dashboard')
        </h1>
    </div>
    <div class="header-right">
        <form class="search-bar" method="GET" action="{{ url()->current() }}" id="vendorSearchForm" autocomplete="off" style="position:relative;">
            <i class="fas fa-search"></i>
            <input type="text" id="vendorSearchInput" name="q" placeholder="Search products, orders..." value="{{ request('q') }}" autocomplete="off">
            <div id="vendorSearchDropdown" class="search-results-dropdown" style="display:none;"></div>
        </form>
        <div class="header-icon notification-icon" id="vendorNotificationIcon">
            <i class="fas fa-bell"></i>
            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
            @endif
            <div class="dropdown notification-dropdown" id="vendorNotificationDropdown">
                <div class="dropdown-header">
                    <span>Notifications</span>
                    @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                        <span class="unread-count">{{ $unreadNotifications->count() }} new</span>
                    @endif
                </div>
                <div class="notification-list">
                    @if(isset($allNotifications) && $allNotifications->count() > 0)
                        @foreach($allNotifications as $notification)
                            <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" data-notification-id="{{ $notification->id }}">
                                <div class="notification-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-message">
                                        @php
                                            $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                                            $message = $data['message'] ?? 'New notification';
                                        @endphp
                                        {{ $message }}
                                    </div>
                                    <div class="notification-time">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="dropdown-footer">
                            <a href="#" class="mark-all-read">Mark all as read</a>
                        </div>
                    @else
                        <div class="no-notifications">
                            <i class="fas fa-bell-slash"></i>
                            <span>No notifications</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="header-icon user-profile-icon" id="vendorUserProfileIcon">
            <i class="fas fa-user"></i>
            <div class="dropdown user-dropdown" id="vendorUserDropdown">
                <div class="dropdown-user-info">
                    <div class="user-avatar" style="background: #e0e0e0; color: #333">
                        @if($user && isset($user->profile_photo) && $user->profile_photo)
                            <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @else
                            <img src="{{ asset('images/profile.png') }}" alt="Default Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: 600;">{{ $user->name ?? 'Vendor' }}</div>
                        <div style="font-size: 0.8rem; opacity: 0.8;">Vendor</div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user-edit"></i> Profile
                </a>
                <a href="/vendor/settings" class="dropdown-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
@else
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
@endif 
<script>
        // Vendor search: filter page content or redirect (placeholder for real search logic)
        const vendorSearchForm = document.getElementById('vendorSearchForm');
        const vendorSearchInput = document.getElementById('vendorSearchInput');
        if (vendorSearchForm && vendorSearchInput) {
            vendorSearchForm.addEventListener('submit', function(e) {
                // Optionally, implement AJAX or client-side filtering here
                // For now, allow default GET submit to reload page with ?q=...
                // e.preventDefault();
                // Example: window.find(vendorSearchInput.value);
            });
        }
        // Vendor live search with dropdown
        const vendorSearchDropdown = document.getElementById('vendorSearchDropdown');
        if (vendorSearchInput && vendorSearchDropdown) {
            vendorSearchInput.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();
                if (query.length < 2) {
                    vendorSearchDropdown.style.display = 'none';
                    vendorSearchDropdown.innerHTML = '';
                    // Optionally, show all items again
                    document.querySelectorAll('.product-item, .order-item, .customer-item').forEach(item => {
                        item.style.display = '';
                        item.classList.remove('search-highlight');
                    });
                    return;
                }
                // Collect all items
                const items = Array.from(document.querySelectorAll('.product-item, .order-item, .customer-item'));
                let matches = [];
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(query)) {
                        matches.push(item);
                        item.style.display = '';
                        item.classList.add('search-highlight');
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('search-highlight');
                    }
                });
                // Build dropdown
                if (matches.length > 0) {
                    vendorSearchDropdown.innerHTML = matches.slice(0, 10).map(item => {
                        let label = item.textContent.trim();
                        if (label.length > 60) label = label.slice(0, 57) + '...';
                        return `<div class="result-item" style="cursor:pointer;">${label}</div>`;
                    }).join('');
                    vendorSearchDropdown.style.display = 'block';
                } else {
                    vendorSearchDropdown.innerHTML = '<div class="no-results">No results found.</div>';
                    vendorSearchDropdown.style.display = 'block';
                }
                // Scroll to/highlight on click
                Array.from(vendorSearchDropdown.querySelectorAll('.result-item')).forEach((el, idx) => {
                    el.addEventListener('click', function() {
                        const target = matches[idx];
                        if (target) {
                            target.scrollIntoView({behavior: 'smooth', block: 'center'});
                            target.classList.add('search-highlight');
                            setTimeout(() => target.classList.remove('search-highlight'), 2000);
                        }
                        vendorSearchDropdown.style.display = 'none';
                    });
                });
            });
            // Hide dropdown on outside click
            document.addEventListener('click', function(e) {
                if (!vendorSearchForm.contains(e.target)) {
                    vendorSearchDropdown.style.display = 'none';
                }
            });
        }
        // Optional: highlight style
        const style = document.createElement('style');
        style.innerHTML = `.search-highlight { background: #e0ffe0 !important; transition: background 0.5s; }`;
        document.head.appendChild(style);
</script> 