<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/auth.css', 'resources/js/app.js'])
    </head>
    <body bgcolor=#0F2C67>
        <a href="/" style="display:inline-block;margin:1rem 0 0 1rem;color:#6c63ff;text-decoration:none;font-weight:600;">&#8592; Back to Home</a>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
        @stack('scripts')
    </body>
</html> 