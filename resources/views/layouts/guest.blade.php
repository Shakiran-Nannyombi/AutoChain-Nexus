<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Autochain Nexus') }}</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/auth.css', 'resources/js/app.js'])
    </head>
    <body bgcolor="#0F2C67">
        <a href="/" style="display:inline-block;margin:1rem 0 0 1rem;color:#6c63ff;text-decoration:none;font-weight:600;">&#8592; Back to Home</a>
        <div>
            {{ $slot }}
        </div>
    </body>
</html>
