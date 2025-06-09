<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white">
        <div class="flex">
            @include('layouts.partials.sidebar')
            
            <div class="flex-1 flex flex-col overflow-y-auto">
                @include('layouts.partials.header')
                
                <main class="flex-1 p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Notifications -->
        <div id="notifications-container"></div>

        <!-- Scripts -->
        <script>
            // Handle search
            document.getElementById('search-form')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const query = document.getElementById('search-input').value;
                if (query.trim()) {
                    // Add your search logic here
                    console.log('Searching for:', query);
                }
            });

            // Handle logout
            document.getElementById('logout-button')?.addEventListener('click', function() {
                document.getElementById('logout-form').submit();
            });
        </script>

        @stack('scripts')
    </body>
</html>
