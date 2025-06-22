<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Autochain Nexus</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js', 'resources/css/auth.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
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
                <div class="user-avatar" style="margin-left: 0.9rem;">
                    {{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}{{ strtoupper(substr(session('user_name', 'er'), strpos(session('user_name', 'er'), ' ')+1, 1)) }}
                </div>
                <div class="user-info" style="display: flex; flex-direction: column; align-items: flex-start;">
                    <div style="font-weight: 600; color: #fff; font-size: 1.05rem; line-height: 1.1;">
                        {{ session('user_name', 'User') }}
                    </div>
                    <div style="font-size: 0.85rem; color: #2a6eea">
                        {{ strtolower(session('user_role', 'user')) }}
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
                        @else
                            {{ $title ?? 'Dashboard' }}
                        @endif
                    </h1>
                </div>
                
                <div class="header-right">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="header-icon notification-icon" id="notificationIcon">
                        <i class="fas fa-bell"></i>
                        <div class="dropdown notification-dropdown" id="notificationDropdown">
                            <div class="dropdown-header">Notifications</div>
                            <div style="padding: 1rem; text-align: center; color: #6c757d;">
                                No new notifications.
                            </div>
                        </div>
                    </div>
                    
                    <div class="header-icon user-profile-icon" id="userProfileIcon">
                        <i class="fas fa-user"></i>
                        <div class="dropdown user-dropdown" id="userDropdown">
                            <div class="dropdown-user-info">
                                 <div class="user-avatar" style="background: #e0e0e0; color: #333">
                                    {{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ session('user_name', 'User') }}</div>
                                    <div style="font-size: 0.8rem; opacity: 0.8;">{{ ucfirst(session('user_role', 'User')) }}</div>
                                </div>
                            </div>
                            <a href="/admin/settings" class="dropdown-item">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <a href="/logout" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
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

    </script>

    @stack('scripts')
</body>
</html> 