<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'App')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="user-id" content="{{ auth()->id() ?? session('user_id') }}"> 
    @stack('styles')
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html> 