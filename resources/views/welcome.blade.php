@extends('layouts.layout')

@section('title', 'Home - Autochain Nexus')

@section('content')
    <section class="intro">
        <div class="media-masthead__media media-masthead__media-overlay">
        <div class="media-masthead__media-overlay" style="opacity: 0.56;"></div>
            <div class="picture-container">
                <div class="picture">
                <picture>
                    <img src="/images/t1.jpg" alt="Car" style="width: 100%; height: 100%; object-fit: cover;" />
                </picture> <!-- Responsive image would go here -->
                </div>
            </div>
        </div>
        <div class="media-masthead_content container"> <!-- Flex parent -->
            <div class="media-masthead_content-box"> <!-- Flex child -->
                <h1 class="media-masthead__heading">Welcome to Autochain Nexus</h1>
                <p class="media-masthead__description">Modernize your automotive supply chain with a centralized, role-based platform for seamless management from vendor onboarding to delivery.</p>
                <div class="media-masthead__buttons"></div>
            </div>
        </div>

    </div>

    <div class="footer">
        &copy; 2024 Autochain Nexus. Powering the future of automotive manufacturing.
    </div>
    </body>
</html>

        </div>
        <div class="hero">
            
           
        </div>
    </section>
    <section class="features-wrapper">
    <div class="features">
        <div class="features">
    <section class="feature-card">
        <h3>Vendor Onboarding</h3>
        <p>Register and validate vendors with automated financial and compliance checks, ensuring secure and efficient onboarding.</p>
    </section>
    <section class="feature-card">
        <h3>Manufacturing Workflows</h3>
        <p>Track production stages with real-time updates and demand-driven forecasts powered by machine learning.</p>
    </section>
    <section class="feature-card">
        <h3>Inventory Tracking</h3>
        <p>Monitor warehouse inventory, prioritize high-demand models, and manage stock replenishment seamlessly.</p>
    </section>
    <section class="feature-card">
        <h3>Order Fulfillment</h3>
        <p>Streamline order processing with real-time alerts and integrated communication for retailers and manufacturers.</p>
    </section>
    <section class="feature-card">
        <h3>Intelligent Analytics</h3>
        <p>Gain insights with custom reports and demand forecasts to optimize supply chain performance.</p>
    </section>
</div>

    </div>
    </section>
@endsection