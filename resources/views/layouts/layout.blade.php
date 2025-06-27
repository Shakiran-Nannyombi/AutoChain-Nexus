<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nexus Autochain')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
</head>
<body>
    <div class="header">
        <div class="logo">    <h1>Nexus Autochain</h1> </div>
    <nav class="nav">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('contact') }}">Contact</a>
        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </nav>
    </div>
    <main class="content">
        @yield('content')
    </main>
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Autochain Nexus. All rights reserved.</p>
        <p>
            <a href="{{ route('privacy') }}">Privacy Policy</a> |
            <a href="{{ route('terms') }}">Terms of Service</a> |
            <a href="{{ route('contact') }}">Contact Us</a>
        </p>
    </footer>
</body>
</html>