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
@elseif($user && $user->role === 'manufacturer')
<div class="header manufacturer-header">
    <div class="header-left">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="dashboard-title" style="color: #0F2C67; font-size: 2.1rem; font-weight: 700;">
            @yield('title', 'Manufacturer Dashboard')
        </h1>
    </div>
    <div class="header-right">
        <form class="search-bar" method="GET" action="{{ url()->current() }}" id="manufacturerSearchForm" autocomplete="off" style="position:relative;">
            <i class="fas fa-search"></i>
            <input type="text" id="manufacturerSearchInput" name="q" placeholder="Search products, orders..." value="{{ request('q') }}" autocomplete="off">
            <div id="manufacturerSearchDropdown" class="search-results-dropdown" style="display:none;"></div>
        </form>
        <div class="header-icon notification-icon" id="manufacturerNotificationIcon">
            <i class="fas fa-bell"></i>
            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
            @endif
            <div class="dropdown notification-dropdown" id="manufacturerNotificationDropdown">
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
                        <!-- DEMO NOTIFICATIONS -->
                        <div class="notification-item unread">
                            <div class="notification-icon"><i class="fas fa-bell"></i></div>
                            <div class="notification-content">
                                <div class="notification-message">New order received from Retailer A</div>
                                <div class="notification-time">2 minutes ago</div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon"><i class="fas fa-cubes"></i></div>
                            <div class="notification-content">
                                <div class="notification-message">Inventory below threshold for Product X</div>
                                <div class="notification-time">10 minutes ago</div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon"><i class="fas fa-user-check"></i></div>
                            <div class="notification-content">
                                <div class="notification-message">Vendor B approved</div>
                                <div class="notification-time">1 hour ago</div>
                            </div>
                        </div>
                        <div class="dropdown-footer">
                            <a href="#" class="mark-all-read">Mark all as read</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="header-icon user-profile-icon" id="manufacturerUserProfileIcon">
            <i class="fas fa-user"></i>
            <div class="dropdown user-dropdown" id="manufacturerUserDropdown">
                <div class="dropdown-user-info">
                    <div class="user-avatar" style="background: #e0e0e0; color: #333">
                        @if($user && isset($user->profile_photo) && $user->profile_photo)
                            <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @else
                            <img src="{{ asset('images/profile.png') }}" alt="Default Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: 600;">{{ $user->name ?? 'Manufacturer' }}</div>
                        <div style="font-size: 0.8rem; opacity: 0.8;">Manufacturer</div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user-edit"></i> Profile
                </a>
                <a href="/manufacturer/settings" class="dropdown-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
@elseif($user && $user->role === 'supplier')
<div class="header supplier-header">
    <div class="header-left">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="dashboard-title" style="color: #166534; font-size: 2.1rem; font-weight: 700;">
            @yield('title', 'Supplier Dashboard')
        </h1>
    </div>
    <div class="header-right">
        <form class="search-bar" method="GET" action="{{ url()->current() }}" id="supplierSearchForm" autocomplete="off" style="position:relative;">
            <i class="fas fa-search"></i>
            <input type="text" id="supplierSearchInput" name="q" placeholder="Search supplies, orders..." value="{{ request('q') }}" autocomplete="off">
            <div id="supplierSearchDropdown" class="search-results-dropdown" style="display:none;"></div>
        </form>
        <div class="header-icon notification-icon" id="supplierNotificationIcon">
            <i class="fas fa-bell"></i>
            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
            @endif
            <div class="dropdown notification-dropdown" id="supplierNotificationDropdown">
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
        <div class="header-icon user-profile-icon" id="supplierUserProfileIcon">
            <i class="fas fa-user"></i>
            <div class="dropdown user-dropdown" id="supplierUserDropdown">
                <div class="dropdown-user-info">
                    <div class="user-avatar" style="background: #e0e0e0; color: #333">
                        @if($user && isset($user->profile_photo) && $user->profile_photo)
                            <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @else
                            <img src="{{ asset('images/profile.png') }}" alt="Default Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: 600;">{{ $user->name ?? 'Supplier' }}</div>
                        <div style="font-size: 0.8rem; opacity: 0.8;">Supplier</div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user-edit"></i> Profile
                </a>
                <a href="/supplier/settings" class="dropdown-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
@elseif($user && $user->role === 'analyst')
<div class="header analyst-header">
    <div class="header-left">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="dashboard-title" style="color: #1e3a8a; font-size: 2.1rem; font-weight: 700;">
            Analyst Dashboard
        </h1>
    </div>
    <div class="header-right">
        <form class="search-bar" method="GET" action="{{ url()->current() }}" id="analystSearchForm" autocomplete="off" style="position:relative;">
            <i class="fas fa-search"></i>
            <input type="text" id="analystSearchInput" name="q" placeholder="Search reports, users..." value="{{ request('q') }}" autocomplete="off">
            <div id="analystSearchDropdown" class="search-results-dropdown" style="display:none; position:absolute; left:0; right:0; z-index:1000;"></div>
        </form>
        <div class="header-icon notification-icon" id="analystNotificationIcon" style="cursor:pointer;">
            <i class="fas fa-bell"></i>
            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
            @endif
            <div class="dropdown notification-dropdown" id="analystNotificationDropdown">
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
        <div class="header-icon user-profile-icon" id="analystUserProfileIcon" style="cursor:pointer;">
            <i class="fas fa-user"></i>
            <div class="dropdown user-dropdown" id="analystUserDropdown">
                <div class="dropdown-user-info">
                    <div class="user-avatar" style="background: #e0e0e0; color: #333">
                        @if($user && isset($user->profile_photo) && $user->profile_photo)
                            <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @else
                            <img src="{{ asset('images/profile.png') }}" alt="Default Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: 600;">{{ $user->name ?? 'Analyst' }}</div>
                        <div style="font-size: 0.8rem; opacity: 0.8;">Analyst</div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user-edit"></i> Profile
                </a>
                <a href="/analyst/settings" class="dropdown-item">
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

// Manufacturer header dropdown toggle
const manufacturerNotificationIcon = document.getElementById('manufacturerNotificationIcon');
const manufacturerUserProfileIcon = document.getElementById('manufacturerUserProfileIcon');
const manufacturerNotificationDropdown = document.getElementById('manufacturerNotificationDropdown');
const manufacturerUserDropdown = document.getElementById('manufacturerUserDropdown');

if (manufacturerNotificationIcon && manufacturerNotificationDropdown) {
    manufacturerNotificationIcon.addEventListener('click', (event) => {
        event.stopPropagation();
        manufacturerNotificationDropdown.classList.toggle('open');
        if (manufacturerUserDropdown) manufacturerUserDropdown.classList.remove('open');
    });
}
if (manufacturerUserProfileIcon && manufacturerUserDropdown) {
    manufacturerUserProfileIcon.addEventListener('click', (event) => {
        event.stopPropagation();
        manufacturerUserDropdown.classList.toggle('open');
        if (manufacturerNotificationDropdown) manufacturerNotificationDropdown.classList.remove('open');
    });
}
window.addEventListener('click', (event) => {
    if (manufacturerNotificationDropdown && !manufacturerNotificationIcon.contains(event.target)) {
        manufacturerNotificationDropdown.classList.remove('open');
    }
    if (manufacturerUserDropdown && !manufacturerUserProfileIcon.contains(event.target)) {
        manufacturerUserDropdown.classList.remove('open');
    }
});

// Supplier header dropdown toggle
const supplierNotificationIcon = document.getElementById('supplierNotificationIcon');
const supplierUserProfileIcon = document.getElementById('supplierUserProfileIcon');
const supplierNotificationDropdown = document.getElementById('supplierNotificationDropdown');
const supplierUserDropdown = document.getElementById('supplierUserDropdown');

if (supplierNotificationIcon && supplierNotificationDropdown) {
    supplierNotificationIcon.addEventListener('click', (event) => {
        event.stopPropagation();
        supplierNotificationDropdown.classList.toggle('open');
        if (supplierUserDropdown) supplierUserDropdown.classList.remove('open');
    });
}
if (supplierUserProfileIcon && supplierUserDropdown) {
    supplierUserProfileIcon.addEventListener('click', (event) => {
        event.stopPropagation();
        supplierUserDropdown.classList.toggle('open');
        if (supplierNotificationDropdown) supplierNotificationDropdown.classList.remove('open');
    });
}
window.addEventListener('click', (event) => {
    if (supplierNotificationDropdown && !supplierNotificationIcon.contains(event.target)) {
        supplierNotificationDropdown.classList.remove('open');
    }
    if (supplierUserDropdown && !supplierUserProfileIcon.contains(event.target)) {
        supplierUserDropdown.classList.remove('open');
    }
});

// Analyst header dropdown toggle (match manufacturer/supplier logic)
const analystNotificationIcon = document.getElementById('analystNotificationIcon');
const analystUserProfileIcon = document.getElementById('analystUserProfileIcon');
const analystNotificationDropdown = document.getElementById('analystNotificationDropdown');
const analystUserDropdown = document.getElementById('analystUserDropdown');

if (analystNotificationIcon && analystNotificationDropdown) {
    analystNotificationIcon.addEventListener('click', (event) => {
        event.stopPropagation();
        analystNotificationDropdown.classList.toggle('open');
        if (analystUserDropdown) analystUserDropdown.classList.remove('open');
    });
}
if (analystUserProfileIcon && analystUserDropdown) {
    analystUserProfileIcon.addEventListener('click', (event) => {
        event.stopPropagation();
        analystUserDropdown.classList.toggle('open');
        if (analystNotificationDropdown) analystNotificationDropdown.classList.remove('open');
    });
}
window.addEventListener('click', (event) => {
    if (analystNotificationDropdown && !analystNotificationIcon.contains(event.target)) {
        analystNotificationDropdown.classList.remove('open');
    }
    if (analystUserDropdown && !analystUserProfileIcon.contains(event.target)) {
        analystUserDropdown.classList.remove('open');
    }
});

// Analyst live search (search all visible content except header/sidebar)
const analystSearchInput = document.getElementById('analystSearchInput');
const analystSearchDropdown = document.getElementById('analystSearchDropdown');
const analystSearchForm = document.getElementById('analystSearchForm');
if (analystSearchInput && analystSearchDropdown) {
    analystSearchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        // Define the main content area (adjust selector as needed)
        const mainContent = document.querySelector('.content-card, .main-content, #main');
        if (!mainContent) return;
        // Get all direct children (sections, divs, etc.)
        const items = Array.from(mainContent.children).filter(el => el.offsetParent !== null);
        let matches = [];
        if (query.length < 2) {
            analystSearchDropdown.style.display = 'none';
            analystSearchDropdown.innerHTML = '';
            // Show all items again
            items.forEach(item => {
                item.style.display = '';
                item.classList.remove('search-highlight');
            });
            return;
        }
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
            analystSearchDropdown.innerHTML = matches.slice(0, 10).map(item => {
                let label = item.textContent.trim();
                if (label.length > 60) label = label.slice(0, 57) + '...';
                return `<div class="result-item" style="cursor:pointer;">${label}</div>`;
            }).join('');
            analystSearchDropdown.style.display = 'block';
        } else {
            analystSearchDropdown.innerHTML = '<div class="no-results">No results found.</div>';
            analystSearchDropdown.style.display = 'block';
        }
        // Scroll to/highlight on click
        Array.from(analystSearchDropdown.querySelectorAll('.result-item')).forEach((el, idx) => {
            el.addEventListener('click', function() {
                const target = matches[idx];
                if (target) {
                    target.scrollIntoView({behavior: 'smooth', block: 'center'});
                    target.classList.add('search-highlight');
                    setTimeout(() => target.classList.remove('search-highlight'), 2000);
                }
                analystSearchDropdown.style.display = 'none';
            });
        });
    });
    // Hide dropdown on outside click
    document.addEventListener('click', function(e) {
        if (!analystSearchForm.contains(e.target)) {
            analystSearchDropdown.style.display = 'none';
        }
    });
}
// Optional: highlight style
const analystStyle = document.createElement('style');
analystStyle.innerHTML = `.search-highlight { background: #e0ffe0 !important; transition: background 0.5s; }`;
document.head.appendChild(analystStyle);
</script> 
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Analyst notification dropdown
    const notifIcon = document.getElementById('analystNotificationIcon');
    const notifDropdown = document.getElementById('analystNotificationDropdown');
    notifIcon && notifIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        notifDropdown.style.display = notifDropdown.style.display === 'block' ? 'none' : 'block';
        // Hide user dropdown if open
        const userDropdown = document.getElementById('analystUserDropdown');
        if(userDropdown) userDropdown.style.display = 'none';
    });
    // Analyst user dropdown
    const userIcon = document.getElementById('analystUserProfileIcon');
    const userDropdown = document.getElementById('analystUserDropdown');
    userIcon && userIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
        // Hide notif dropdown if open
        if(notifDropdown) notifDropdown.style.display = 'none';
    });
    // Hide dropdowns when clicking outside
    document.addEventListener('click', function() {
        if(notifDropdown) notifDropdown.style.display = 'none';
        if(userDropdown) userDropdown.style.display = 'none';
    });
});
</script>
@endpush 