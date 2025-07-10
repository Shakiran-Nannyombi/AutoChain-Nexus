<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/welcome.css'])
    @vite(['resources/js/slider.js'])
</head>
<body>
    <nav class="welcome-navbar">
        <div class="navbar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            <span class="navbar-brand">Autocahin Nexus</span>
        </div>
        <div class="navbar-links">
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        </div>
    </nav>
    <div class="hero-row">
        <div class="hero-content">
            <div class="hero-intro">AUTOCHAIN NEXUS PRESENTS</div>
            <h1 class="hero-title">THE VOICE OF THE MAKER</h1>
            <div class="hero-subtitle">You Don’t Just Purchase a Car, You Commission It.<br> A Motor Car That Is Yours, And Yours Alone.</div>
            <div class="hero-buttons">
                <a href="/login" class="hero-btn primary">LOGIN</a>
                <a href="/register" class="hero-btn secondary">SIGN UP</a>
            </div>
        </div>
        <div class="main-car-container">
            <img src="{{ asset('images/background.png') }}" alt="Main Car" class="main-car-img" id="mainCarImage">
        </div>
    </div>
    <div class="car-features car-features-below">
        <div class="feature">
            <div class="feature-title">SMART DASHBOARD</div>
            <div class="feature-desc">Real-time Analytics</div>
        </div>
        <div class="feature">
            <div class="feature-title">INTEGRATED LIGHTING</div>
            <div class="feature-desc">Ambient Experience</div>
        </div>
    </div>
    <div class="slider-outer">
        <div class="slider-container">
            <div class="slider-thumbnails" id="sliderThumbnails">
                <img src="{{ asset('images/car1.png') }}" class="slider-thumb active" data-img="{{ asset('images/car1.png') }}">
                <img src="{{ asset('images/car2.png') }}" class="slider-thumb" data-img="{{ asset('images/car2.png') }}">
                <img src="{{ asset('images/car3.png') }}" class="slider-thumb" data-img="{{ asset('images/car3.png') }}">
                <img src="{{ asset('images/car4.png') }}" class="slider-thumb" data-img="{{ asset('images/car4.png') }}">
                <img src="{{ asset('images/car5.png') }}" class="slider-thumb" data-img="{{ asset('images/car5.png') }}">
                <img src="{{ asset('images/car6.png') }}" class="slider-thumb" data-img="{{ asset('images/car6.png') }}">
                <img src="{{ asset('images/car7.png') }}" class="slider-thumb" data-img="{{ asset('images/car7.png') }}">
                <img src="{{ asset('images/car8.png') }}" class="slider-thumb" data-img="{{ asset('images/car8.png') }}">
                <img src="{{ asset('images/car9.png') }}" class="slider-thumb" data-img="{{ asset('images/car9.png') }}">
                <img src="{{ asset('images/car10.png') }}" class="slider-thumb" data-img="{{ asset('images/car10.png') }}">
            </div>
        </div>
    </div>
</body>
<footer class="welcome-footer">
    ©2025 Autochain nexus. All rights reserved.
</footer>
</html>
