<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'App')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/darkmode.js'])
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-icon-180.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="user-id" content="{{ auth()->id() ?? session('user_id') }}"> 
    @stack('styles')
    <script>
    // On page load, restore preference
    (function() {
        const theme = localStorage.getItem('theme');
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    })();
    </script>
</head>
<body>
    <button id="darkModeToggle" aria-label="Toggle dark mode" style="position: fixed; top: 1rem; right: 1rem; z-index: 2000; background: var(--background); color: var(--primary); border: 1px solid var(--primary); border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; box-shadow: var(--shadow); cursor: pointer; transition: background 0.2s, color 0.2s;">
        <span id="darkModeIcon">🌙</span>
    </button>
    @yield('content')

    @stack('scripts')
</body>
</html> 