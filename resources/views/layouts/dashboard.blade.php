<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Inventory Management System</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --deep-purple: #230444;
            --maroon: #90303D;
            --orange: #ED8240;
            --light-cyan: #D2EBE9;
            --white: #fff;
            --gray: #f5f7fa;
            --shadow: 0 4px 24px rgba(32,0,58,0.08);
            --blue: #2a6eea;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--gray);
            min-height: 100vh;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--deep-purple);
            color: var(--white);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--orange);
            margin-bottom: 0.5rem;
        }

        .sidebar-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.7;
            font-weight: 600;
        }

        .nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: var(--orange);
            color: var(--orange);
        }

        .nav-item i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: var(--white);
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--deep-purple);
            cursor: pointer;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--deep-purple);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--light-cyan);
            border-radius: 8px;
            color: var(--deep-purple);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--white);
        }

        .logout-btn {
            background: var(--maroon);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #7a2a35;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .content-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .header {
                padding: 1rem;
            }

            .content {
                padding: 1rem;
            }

            .content-card {
                padding: 1.5rem;
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.open {
            display: block;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-boxes"></i> IMS
                </div>
                <div class="sidebar-subtitle">Inventory Management</div>
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
                    <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ session('user_name', 'User') }}</div>
                            <div style="font-size: 0.8rem; opacity: 0.8;">{{ ucfirst(session('user_role', 'User')) }}</div>
                        </div>
                    </div>
                    
                    <a href="/logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
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
    </script>
</body>
</html> 