<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Autochain Nexus')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/css/manufacturer.css','resources/css/analyst.css','resources/css/supplier.css','resources/css/retailer.css', 'resources/css/vendor.css','resources/js/app.js', 'resources/css/auth.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    @stack('head')
    <style>
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: #fff;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: none;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
        }
        .search-bar:focus-within .search-results-dropdown {
            display: block;
        }
        .search-results-dropdown .result-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }
        .search-results-dropdown .result-item:last-child {
            border-bottom: none;
        }
        .search-results-dropdown .result-item:hover {
            background-color: #f8f9fa;
        }
        .search-results-dropdown .result-item .icon {
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            color: #6c757d;
        }
        .search-results-dropdown .result-item .details .name {
            font-weight: 600;
        }
        .search-results-dropdown .result-item .details .info {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .search-results-dropdown .no-results {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
        }
        
        /* Notification Styles */
        .notification-icon {
            position: relative;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .notification-dropdown {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .dropdown-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
        }
        
        .unread-count {
            background: #3b82f6;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
            cursor: pointer;
        }
        
        .notification-item:hover {
            background-color: #f9fafb;
        }
        
        .notification-item.unread {
            background-color: #eff6ff;
            border-left: 3px solid #3b82f6;
        }
        
        .notification-icon {
            margin-right: 0.75rem;
            margin-top: 0.125rem;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-message {
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        .no-notifications {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
            color: #6b7280;
        }
        
        .no-notifications i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }
        
        .dropdown-footer {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-top: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
        
        .dropdown-footer a {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .dropdown-footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .notification-dropdown {
                width: 300px;
                right: -50px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div>
                    <div class="sidebar-logo">AUTOCHAIN NEXUS</div>
                    <div class="sidebar-subtitle">Car Inventory Management System</div>
                </div>
            </div>

            <div class="sidebar-user">
                @php $user = auth('admin')->user() ?? auth()->user(); @endphp
                @if($user && isset($user->profile_photo) && $user->profile_photo)
                    <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" class="user-avatar" style="margin-left: 0.9rem; object-fit:cover; width:48px; height:48px; border-radius:50%; border:2px solid #e0e0e0;">
                @else
                    <div class="user-avatar" style="margin-left: 0.9rem;">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->name ?? 'er', strpos($user->name ?? 'er', ' ')+1, 1)) }}
                    </div>
                @endif
                <div class="user-info" style="display: flex; flex-direction: column; align-items: flex-start;">
                    <div style="font-weight: 600; color: #fff; font-size: 1.05rem; line-height: 1.1;">
                        {{ $user->name ?? 'User' }}
                    </div>
                    <div style="font-size: 0.85rem; color: #2a6eea">
                        @if($user && get_class($user) === 'App\\Models\\Admin')
                            admin
                        @else
                            {{ strtolower($user->role ?? 'user') }}
                        @endif
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                @yield('sidebar-content')
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">
                        @if (request()->is('admin/*'))
                            Admin Dashboard
                        @elseif (request()->is('supplier/*'))
                            Supplier Dashboard
                        @elseif (request()->is('manufacturer/*'))
                            Manufacturer Dashboard
                        @elseif (request()->is('vendor/*'))
                            Vendor Dashboard
                        @elseif (request()->is('retailer/*'))
                            Retailer Dashboard
                        @elseif (request()->is('analyst/*'))
                            Analyst Dashboard
                        @else
                            {{ $title ?? 'Dashboard' }}
                        @endif
                    </h1>
                </div>
                
                <div class="header-right">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="globalSearchInput" placeholder="Search users, pages...">
                        <div class="search-results-dropdown" id="searchResultsDropdown"></div>
                    </div>
                    <div class="header-icon notification-icon" id="notificationIcon">
                        <i class="fas fa-bell"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                            <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
                        @endif
                        <div class="dropdown notification-dropdown" id="notificationDropdown">
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
                                                @if($notification->type === 'App\\Notifications\\NewUserNotification')
                                                    <i class="fas fa-user-plus text-blue-500"></i>
                                                @elseif($notification->type === 'App\\Notifications\\UserApproved')
                                                    <i class="fas fa-check-circle text-green-500"></i>
                                                @elseif($notification->type === 'App\\Notifications\\UserRejected')
                                                    <i class="fas fa-times-circle text-red-500"></i>
                                                @else
                                                    <i class="fas fa-bell text-gray-500"></i>
                                                @endif
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
                                @else
                                    <div class="no-notifications">
                                        <i class="fas fa-bell-slash"></i>
                                        <span>No notifications</span>
                                    </div>
                                @endif
                            </div>
                            @if(isset($allNotifications) && $allNotifications->count() > 0)
                                <div class="dropdown-footer">
                                    <a href="#" class="mark-all-read">Mark all as read</a>
                                    <a href="#" class="view-all">View all</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="header-icon user-profile-icon" id="userProfileIcon">
                        <i class="fas fa-user"></i>
                        <div class="dropdown user-dropdown" id="userDropdown">
                            <div class="dropdown-user-info">
                                 <div class="user-avatar" style="background: #e0e0e0; color: #333">
                                    @if($user && isset($user->profile_photo) && $user->profile_photo)
                                        <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" style="object-fit:cover; width:40px; height:40px; border-radius:50%; border:2px solid #e0e0e0;">
                                    @else
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $user->name ?? 'User' }}</div>
                                    <div style="font-size: 0.8rem; opacity: 0.8;">
                                        @if($user && get_class($user) === 'App\\Models\\Admin')
                                            Admin
                                        @else
                                            {{ ucfirst($user->role ?? 'User') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user-edit"></i> Profile
                            </a>
                            @if (request()->is('admin/*'))
                                <a href="/admin/settings" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            @elseif (request()->is('supplier/*'))
                                <a href="{{ route('supplier.settings') }}" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            @elseif (request()->is('manufacturer/*'))
                                <a href="{{ route('manufacturer.settings') }}" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            @else
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            @endif
                            @if (request()->is('admin/*'))
                                <a href="{{ route('admin.logout') }}" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            @else
                                <a href="{{ route('logout') }}" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Prevent browser back button after logout
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
        
        // Mobile sidebar toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('open');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
        });

        // Close sidebar when clicking on nav items on mobile
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('open');
                }
            });
        });

        // Dropdown toggle
        const notificationIcon = document.getElementById('notificationIcon');
        const userProfileIcon = document.getElementById('userProfileIcon');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const userDropdown = document.getElementById('userDropdown');

        notificationIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            notificationDropdown.classList.toggle('open');
            userDropdown.classList.remove('open');
        });

        userProfileIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            userDropdown.classList.toggle('open');
            notificationDropdown.classList.remove('open');
        });

        window.addEventListener('click', (event) => {
            if (!notificationIcon.contains(event.target)) {
                notificationDropdown.classList.remove('open');
            }
            if (!userProfileIcon.contains(event.target)) {
                userDropdown.classList.remove('open');
            }
        });

        // Global search
        const searchInput = document.getElementById('globalSearchInput');
        const searchResults = document.getElementById('searchResultsDropdown');

        searchInput.addEventListener('input', async function() {
            const query = this.value;

            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`/admin/search?q=${query}`);
                const data = await response.json();
                
                let html = '';
                if (data.users.length === 0 && data.pages.length === 0) {
                    html = '<div class="no-results">No results found.</div>';
                } else {
                    data.users.forEach(user => {
                        html += `
                            <a href="/admin/user-management" class="result-item">
                                <div class="icon"><i class="fas fa-user"></i></div>
                                <div class="details">
                                    <div class="name">${user.name}</div>
                                    <div class="info">${user.role} • ${user.status}</div>
                                </div>
                            </a>
                        `;
                    });
                    data.pages.forEach(page => {
                        html += `
                            <a href="${page.url}" class="result-item">
                                <div class="icon"><i class="fas fa-file-alt"></i></div>
                                <div class="details">
                                    <div class="name">${page.title}</div>
                                    <div class="info">${page.description}</div>
                                </div>
                            </a>
                        `;
                    });
                }
                searchResults.innerHTML = html;
                searchResults.style.display = 'block';
            } catch (error) {
                console.error('Search error:', error);
            }
        });

        // Notification functionality
        const notificationItems = document.querySelectorAll('.notification-item');
        const markAllReadBtn = document.querySelector('.mark-all-read');
        
        // Mark individual notification as read
        notificationItems.forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.dataset.notificationId;
                if (notificationId) {
                    // Mark as read by removing unread class
                    this.classList.remove('unread');
                    
                    // Update badge count
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        const currentCount = parseInt(badge.textContent);
                        if (currentCount > 1) {
                            badge.textContent = currentCount - 1;
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                    
                    // Update unread count in header
                    const unreadCount = document.querySelector('.unread-count');
                    if (unreadCount) {
                        const currentUnread = parseInt(unreadCount.textContent.split(' ')[0]);
                        if (currentUnread > 1) {
                            unreadCount.textContent = `${currentUnread - 1} new`;
                        } else {
                            unreadCount.style.display = 'none';
                        }
                    }
                }
            });
        });
        
        // Mark all notifications as read
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove unread class from all notifications
                notificationItems.forEach(item => {
                    item.classList.remove('unread');
                });
                
                // Hide badge and unread count
                const badge = document.querySelector('.notification-badge');
                if (badge) badge.style.display = 'none';
                
                const unreadCount = document.querySelector('.unread-count');
                if (unreadCount) unreadCount.style.display = 'none';
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html> 