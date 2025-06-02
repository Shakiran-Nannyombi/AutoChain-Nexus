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

    @vite(['resources/css/app.css', 'resources/css/welcome.css', 'resources/js/app.js'])
</head>

<body class="text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <nav class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 mx-auto flex justify-between">
        <div class="flex items-center gap-2 logo ml-0">
            <img src="/images/logo.png" alt="AutoChain Nexus Logo" class="w-10 h-10">
            <span class="font-bold text-lg" style="color:#2196f3;">AutoChain Nexus</span>
        </div>

        <div class="flex items-center space-x-4">
            @if (Route::has('login'))
            <a href="{{ route('login') }}" class="font-semibold bg-transparent border border-blue-600 text-blue-600  transition rounded-full px-4 py-2">
                Log In
            </a>
            @endif
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="font-semibold bg-blue-600 text-white  border border-blue-600 transition rounded-full px-4 py-2">
                Sign Up
            </a>
            @endif
        </div>
    </nav>

    <main class="w-full">
        <!-- Hero Section -->
        <section class="hero w-full text-center py-24 px-8 rounded-3xl mb-6">
            <div class="relative flex flex-col items-center">
                <h2 class="text-5xl font-extrabold mb-6 text-blue-600" style="font-family: 'Inter', sans-serif;">Welcome to AutoChain Nexus</h2>
                <p class="text-lg mb-6 max-w-2xl mx-auto">
                    The intelligent, end-to-end automotive supply chain platform.<br>
                    Connect suppliers, manufacturers, distributors, and retailers in one seamless system.
                </p>
                <div class="flex flex-row sm:flex-row gap-6 justify-center mb-12">
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-full font-semibold bg-[#33b1e7] hover:bg-[#2196f3] shadow-lg text-white transition">Get Started</a>
                    <a href="#" class="border border-[#2196f3] px-8 py-4 rounded-full font-semibold hover:text-blue-600 hover:bg-transparent shadow-lg transition bg-white" style="color: #2196f3;">Learn More</a>
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
            <div class="swiper-pagination "></div>
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
        <section class="features py-20 ">
            <div class="container mx-auto text-center mb-12">
                <h2 class="text-5xl text-white font-bold mb-4">Key Features</h2>
                <p class="text-black max-w-xl mx-auto">Everything you need for a modern, connected automotive supply chain.</p>
            </div>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 px-6">
                <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Real-Time Inventory</h4>
                    <p>Track stock across suppliers, warehouses, and retailers.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Vendor Validation</h4>
                    <p>Automated document checks and compliance monitoring.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">ML-Powered Analytics</h4>
                    <p>Demand forecasting, customer segmentation, and smart recommendations.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Order Processing</h4>
                    <p>Seamless order flow from request to delivery with real-time updates.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Integrated Chat</h4>
                    <p>Secure, role-based communication across the supply chain.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-blue-600">Automated Reports</h4>
                    <p>Scheduled insights for every role, delivered via dashboard or email.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-blue-600 dark:bg-blue-700 py-16 text-white text-center mt-12 rounded-3xl shadow-lg">
            <h3 class="text-3xl font-bold mb-4">Ready to Transform Your Automotive Supply Chain?</h3>
            <p class="mb-6">Register now and experience intelligent, connected operations for every role.</p>
            <a href="{{ route('register') }}" class="bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition">Register Now</a>
        </section>
    </main>
</body>

</html>