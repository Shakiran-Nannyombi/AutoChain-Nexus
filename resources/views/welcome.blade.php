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

<body class=" bg-[#171d3f] flex p-6 lg:p-8 lg:justify-center">
    <main class="main w-full">
        <!-- Logo Section -->
        <div class="logo flex justify-center mb-28 mx-auto">
            <img src="/images/logo1.png" alt="AutoChain Nexus Logo"
            class="w-32 h-32 sm:w-50 sm:h-50 md:w-48 md:h-48 rounded-full border-4 border-transparent object-contain mx-auto" />
        </div>
        <!-- Hero Section -->
        <section class="hero text-center bg-transparent py-12">
            <h2 class="text-5xl font-extrabold mb-6 text-[#38b5ea] text-center" style="font-family: 'Inter', sans-serif;">
                Welcome to AutoChain Nexus
            </h2>
            <p class="text-xl text-white mb-8 max-w-2xl mx-auto text-center">
                The advanced automotive supply chain management platform for your car business needs.
            </p>

            <!-- User Role Slider Section -->
            <div class="container mx-auto text-center mb-8 space-x-4">
                <h3 class="text-3xl mb-8 font-semibold text-white">Who Uses AutoChain Nexus?</h3>
            </div>
            <!-- Swiper Slider -->
            <div class="swiper mb-8">
                <div class="swiper-wrapper mb-8">
                    <div class="swiper-slide">
                        <img src="/images/supplier.jpg" alt="Supplier" class="slider-img">
                        <h4 class="text-xl text-white font-semibold mb-1">Supplier</h4>
                        <p class="text-white">Update stock, validate vendors, and manage raw material flow.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/manufacturer.jpg" alt="Manufacturer" class="slider-img">
                        <h4 class="text-xl text-white font-semibold mb-1">Manufacturer</h4>
                        <p class="text-white">Track production, forecast demand, and optimize operations.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/vendor.jpg" alt="Vendor" class="slider-img">
                        <h4 class="text-xl text-white font-semibold mb-1">Vendor</h4>
                        <p class="text-white">Assign transport, monitor shipments, and coordinate delivery.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/retailer.jpg" alt="Retailer" class="slider-img">
                        <h4 class="text-xl text-white font-semibold mb-1">Retailer</h4>
                        <p class="text-white">Receive inventory, record sales, and access personalized insights.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/analyst.jpg" alt="Analyst" class="slider-img">
                        <h4 class="text-xl text-white font-semibold mb-1">Analyst</h4>
                        <p class="text-white">Analyze trends, segment customers, and generate actionable reports.</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="/images/admin.jpg" alt="Admin" class="slider-img">
                        <h4 class="text-xl text-white font-semibold mb-1">Admin</h4>
                        <p class="text-white">Approve users, monitor system health, and oversee compliance.</p>
                    </div>
                </div>

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

            <div class="flex justify-center px-4 sm:px-6 lg:px-8">
                <div class="bg-[#38b5ea]/10 rounded-2xl shadow-lg px-6 py-8 sm:px-10 flex flex-col items-center w-full max-w-4xl">
                    <h3 class="text-2xl font-bold mb-6 text-white">Get Started</h3>
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-12 w-full justify-center items-center">
                        <a href="{{ route('register') }}" class="bg-[#2c8ac9] text-[#f0f2f5] font-bold py-3 px-8 rounded-full hover:bg-[#2c7ad5] transition duration-300 text-lg w-full sm:w-auto text-center">Register to Create Account</a>
                        <a href="{{ route('login') }}" class="bg-white text-[#171d2f] font-bold py-3 px-8 rounded-full hover:bg-[#e4e4e5] transition duration-300 text-lg w-full sm:w-auto text-center">Sign Into Your Account</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features py-6 sm:py-20 px-4 sm:px-6 lg:px-8">
            <div class="container mx-auto text-center mb-2 sm:mb-12">
                <h2 class="text-3xl sm:text-5xl font-bold mb-4 text-white">Key Features</h2>
                <p class="text-base sm:text-xl text-white max-w-xl mx-auto">Everything you need for a modern, connected automotive supply chain.</p>
            </div>
            <div class="grid gap-6 sm:gap-8 sm:grid-cols-2 lg:grid-cols-3 max-w-6xl mx-auto">
                <div class="bg-[#2c7ad5] rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-[#171d3f]">Real-Time Inventory</h4>
                    <p class="text-white">Track stock across suppliers, warehouses, and retailers.</p>
                </div>
                <div class="bg-[#2c7ad5] rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-[#171d3f]">Vendor Validation</h4>
                    <p class="text-white">Automated document checks and compliance monitoring.</p>
                </div>
                <div class="bg-[#2c7ad5] rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-[#171d3f]">ML-Powered Analytics</h4>
                    <p class="text-white">Demand forecasting, customer segmentation, and smart recommendations.</p>
                </div>
                <div class="bg-[#2c7ad5] rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-[#171d3f]">Order Processing</h4>
                    <p class="text-white">Seamless order flow from request to delivery with real-time updates.</p>
                </div>
                <div class="bg-[#2c7ad5] rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-[#171d3f]">Integrated Chat</h4>
                    <p class="text-white">Secure, role-based communication across the supply chain.</p>
                </div>
                <div class="bg-[#2c7ad5] rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <h4 class="text-xl font-semibold mb-2 text-[#171d3f]">Automated Reports</h4>
                    <p class="text-white">Scheduled insights for every role, delivered via dashboard or email.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-[#38b5ea] py-12 sm:py-16 text-[#171d3f] text-center mt-8 sm:mt-12 rounded-3xl shadow-lg mx-4 sm:mx-auto max-w-4xl px-6 sm:px-8">
            <h3 class="text-2xl sm:text-3xl font-bold mb-4">Ready to Transform Your Automotive Supply Chain?</h3>
            <p class="mb-6 text-[#171d3f]">Register now and experience intelligent, connected operations for every role.</p>
            <a href="{{ route('register') }}" class="bg-white text-[#171d3f] font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition duration-300 text-sm sm:text-base">Register Now</a>
        </section>
    </main>
</body>

</html>