<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Welcome | Autochain Nexus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])
    </head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="logo-img">
            <span class="brand">Autochain Nexus</span>
        </div>
        <div class="nav-actions">
            <a href="{{ route('login') }}"><button class="btn btn-login">Login</button></a>
            <a href="{{ route('register') }}"><button class="btn btn-primary">Get Started</button></a>
        </div>
                </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="badge">🚗 Revolutionizing Automotive Supply Chains</div>
        <div class="main-title">
            Welcome to the Future of <span class="highlight">Automotive Manufacturing</span>
        </div>
         <!-- Image Slider -->
        <div class="slider-container">
            <div class="slider">
                <img src="{{ asset('images/car1.jpeg') }}" class="slide active" alt="Car 1">
                <img src="{{ asset('images/car2.jpeg') }}" class="slide" alt="Car 2">
                <img src="{{ asset('images/car3.jpeg') }}" class="slide" alt="Car 3">
                <img src="{{ asset('images/car4.jpeg') }}" class="slide" alt="Car 4">
                <img src="{{ asset('images/car5.jpeg') }}" class="slide" alt="Car 5">
                <img src="{{ asset('images/car6.jpeg') }}" class="slide" alt="Car 6">
                <img src="{{ asset('images/car7.jpeg') }}" class="slide" alt="Car 7">
                <img src="{{ asset('images/car8.jpeg') }}" class="slide" alt="Car 8">
                <img src="{{ asset('images/car9.jpeg') }}" class="slide" alt="Car 9">
            </div>
        </div>
        <div class="main-desc">
            Experience seamless collaboration across your entire supply chain ecosystem. From manufacturers to customers, our platform connects every stakeholder with real-time data, intelligent workflows, and predictive analytics.
        </div>

        <div class="features-row">
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Secure &amp; Compliant</h3>
                <p>Enterprise-grade security with role-based access control and comprehensive audit trails.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>AI-Powered Insights</h3>
                <p>Machine learning algorithms provide demand forecasting and optimize your supply chain.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Real-time Collaboration</h3>
                <p>Connect all stakeholders with live updates, chat systems, and workflow automation.</p>
            </div>
        </div>

    </div>

    <div class="footer">
        &copy; 2024 Autochain Nexus. Powering the future of automotive manufacturing.
    </div>
    </body>
</html>
