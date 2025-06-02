<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AutoChain Nexus - Automotive Supply Chain Platform</title>

    <!-- Fonts -->
    <link rel="icon" href="/images/autochain-logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Swiper.js for slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-[#1b1b18] flex p-6 lg:p-8 lg:justify-center flex-col bg-white">
    <nav class="fixed top-0 left-0 right-0 w-full z-50 bg-blue-600 border-b border-gray-100 flex items-center justify-between px-4 py-2 shadow-lg">
        <div class="flex items-center gap-2 logo ml-0">
            <img src="/images/logo1.png" alt="AutoChain Nexus Logo" class="h-12 w-12 rounded-full object-cover">
        </div>

        <!-- Login/Register Links -->
        <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
            @if (Route::has('login'))
            <a href="{{ route('login') }}" class="font-semibold bg-transparent border border-white text-white rounded-full px-2 py-0.5">
                Log In
            </a>
            @endif
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="font-semibold bg-white text-blue-600 rounded-full px-2 py-0.5">
                Sign Up
            </a>
            @endif
        </div>
    </nav>

    <main class="w-full mt-20">
        <!-- Hero Section -->
        <section class="hero text-center py-16 px-8 rounded-3xl mb-6 mt-6 bg-white" style="margin-top: 80px;">
            <div class="relative flex flex-col items-center">
                <h2 class="text-5xl font-extrabold mb-6 text-blue-600" style="font-family: 'Inter', sans-serif;">Welcome to AutoChain Nexus</h2>
                <p class="text-lg mb-6 max-w-2xl mx-auto">
                    The intelligent, end-to-end automotive supply chain platform.<br>
                    Connect suppliers, manufacturers, distributors, and retailers in one seamless system.
                </p>
                <div class="flex flex-row sm:flex-row gap-6 justify-center mb-12">
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-full font-semibold bg-blue-600 text-white shadow-lg">Get Started</a>
                </div>
            </div>

            <!-- User Role Slider Section -->
            <div class="container mx-auto text-center mb-8 space-x-4">
                <h3 class="text-3xl font-bold mb-2" style="font-family:'Times New Roman', Times, serif;">Who Uses AutoChain Nexus?</h3>
            </div>
            <!-- Swiper Slider -->
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="/images/supplier.jpg" alt="Supplier" class="slider-img">
                        <h4 class="text-xl font-semibold mb-1">Supplier</h4>
                        <p>Update stock, validate vendors, and manage raw material flow.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/manufacturer.jpg" alt="Manufacturer" class="slider-img">
                        <h4 class="text-xl font-semibold mb-1">Manufacturer</h4>
                        <p>Track production, forecast demand, and optimize operations.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/vendor.jpg" alt="Vendor" class="slider-img">
                        <h4 class="text-xl font-semibold mb-1">Vendor</h4>
                        <p>Assign transport, monitor shipments, and coordinate delivery.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/retailer.jpg" alt="Retailer" class="slider-img">
                        <h4 class="text-xl font-semibold mb-1">Retailer</h4>
                        <p>Receive inventory, record sales, and access personalized insights.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/analyst.jpg" alt="Analyst" class="slider-img">
                        <h4 class="text-xl font-semibold mb-1">Analyst</h4>
                        <p>Analyze trends, segment customers, and generate actionable reports.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/admin.jpg" alt="Admin" class="slider-img">
                        <h4 class="text-xl font-semibold mb-1">Admin</h4>
                        <p>Approve users, monitor system health, and oversee compliance.</p>
                    </div>
                </div>

                <br><br>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new Swiper('.swiper', {
                        loop: true,
                        autoplay: {
                            delay: 3500
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true
                        },
                    });
                });
            </script>
        </section>
       
        <!-- Features Section -->
        <section class="features py-20 bg-white">
            <div class="container mx-auto text-center mb-12">
                <h2 class="text-5xl font-bold mb-4 text-blue-600">Key Features</h2>
                <p class="text-gray-700 max-w-xl mx-auto">Everything you need for a modern, connected automotive supply chain.</p>
            </div>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 px-6">
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Real-Time Inventory</h4>
                    <p>Track stock across suppliers, warehouses, and retailers.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Vendor Validation</h4>
                    <p>Automated document checks and compliance monitoring.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">ML-Powered Analytics</h4>
                    <p>Demand forecasting, customer segmentation, and smart recommendations.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Order Processing</h4>
                    <p>Seamless order flow from request to delivery with real-time updates.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Integrated Chat</h4>
                    <p>Secure, role-based communication across the supply chain.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Automated Reports</h4>
                    <p>Scheduled insights for every role, delivered via dashboard or email.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-blue-600 py-16 text-white text-center mt-12 rounded-3xl shadow-lg">
            <h3 class="text-3xl font-bold mb-4">Ready to Transform Your Automotive Supply Chain?</h3>
            <p class="mb-6">Register now and experience intelligent, connected operations for every role.</p>
            <a href="{{ route('register') }}" class="bg-white text-blue-600 font-bold py-3 px-8 rounded-full">Register Now</a>
        </section>
    </main>
</body>

</html>