@extends('layouts.dashboard')

@section('title', 'Customer Dashboard')

@section('sidebar-content')
    @include('dashboards.customer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem;">
        <i class="fas fa-shopping-cart"></i> Welcome to Our Auto Parts Marketplace
    </h2>

    <!-- Recommended Products (based on segmentation) -->
    @if(isset($recommendations) && $recommendations->count() > 0)
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--blue); font-size: 1.2rem;">
                <i class="fas fa-thumbs-up"></i> Recommended For You
            </h3>
            <div class="row">
                @foreach($recommendations as $product)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="bg-white p-3 rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <h4 style="color: var(--primary); font-size: 1.1rem;">{{ $product->name }}</h4>
                            <div style="color: #666;">{{ $product->category ?? 'General' }}</div>
                            <div style="color: var(--accent); font-weight: 600;">${{ number_format($product->price, 2) }}</div>
                            <a href="{{ route('customer.product.show', $product->id) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Top Selling Products in Your Segment -->
    @if(isset($topSegmentProducts) && $topSegmentProducts->count() > 0)
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--purple); font-size: 1.2rem;">
                <i class="fas fa-chart-bar"></i> Top Selling Products in Your Segment
            </h3>
            <div class="row">
                @foreach($topSegmentProducts as $product)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="bg-white p-3 rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <h4 style="color: var(--primary); font-size: 1.1rem;">{{ $product->name }}</h4>
                            <div style="color: #666;">{{ $product->category ?? 'General' }}</div>
                            <div style="color: var(--accent); font-weight: 600;">${{ number_format($product->price, 2) }}</div>
                            <div style="color: #888; font-size: 0.95em;">Purchased {{ $product->segment_purchases_count ?? 0 }} times in your segment</div>
                            <a href="{{ route('customer.product.show', $product->id) }}" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Available Products -->
    @if($availableProducts->count() > 0)
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--green); font-size: 1.4rem;">
                <i class="fas fa-shopping-cart"></i> Available Products
            </h3>
            <div class="row">
                @foreach($availableProducts as $product)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="bg-white p-3 rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <h4 style="color: var(--primary); font-size: 1.1rem;">{{ $product->name }}</h4>
                            <div style="color: #666;">{{ $product->category ?? 'General' }}</div>
                            <div style="color: var(--accent); font-weight: 600;">${{ number_format($product->price, 2) }}</div>
                            <a href="{{ route('customer.product.show', $product->id) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p>No products available at the moment.</p>
    @endif
</div>
@endsection 