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

    <!-- Market Statistics -->
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 text-center bg-white rounded shadow-sm">
                <div style="color: var(--primary); font-weight: 600;">Available Products</div>
                <div style="font-size: 1.5rem; font-weight: bold;">{{ $marketStats['total_products'] }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 text-center bg-white rounded shadow-sm">
                <div style="color: var(--accent); font-weight: 600;">Active Retailers</div>
                <div style="font-size: 1.5rem; font-weight: bold;">{{ $marketStats['total_retailers'] }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 text-center bg-white rounded shadow-sm">
                <div style="color: var(--warning); font-weight: 600;">Trusted Vendors</div>
                <div style="font-size: 1.5rem; font-weight: bold;">{{ $marketStats['total_vendors'] }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 text-center bg-white rounded shadow-sm">
                <div style="color: var(--success); font-weight: 600;">Recent Deliveries</div>
                <div style="font-size: 1.2rem; font-weight: bold;">{{ $marketStats['recent_deliveries'] }}</div>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--blue); font-size: 1.2rem;">
                <i class="fas fa-star"></i> Featured Products
            </h3>
            <div class="row">
                @foreach($featuredProducts as $product)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="bg-white p-3 rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <h4 style="color: var(--primary); font-size: 1.1rem;">{{ $product->name }}</h4>
                            <div style="color: #666;">{{ $product->category ?? 'General' }}</div>
                            <div style="color: var(--accent); font-weight: 600;">${{ number_format($product->price, 2) }}</div>
                            <div style="color: var(--success); font-weight: 600;">Stock: {{ $product->stock }}</div>
                            @if($product->vendor)
                                <div style="color: #888; font-size: 0.95rem;">Vendor: {{ $product->vendor->name }}</div>
                            @endif
                            <a href="{{ route('customer.product.show', $product->id) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
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
                            <div style="color: var(--success); font-weight: 600;">Stock: {{ $product->stock }}</div>
                            @if($product->vendor)
                                <div style="color: #888; font-size: 0.95rem;">Vendor: {{ $product->vendor->name }}</div>
                            @endif
                            <a href="{{ route('customer.product.show', $product->id) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Stock Movements -->
    @if($recentStockMovements->count() > 0)
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--orange); font-size: 1.4rem;">
                <i class="fas fa-truck"></i> Recent Stock Movements
            </h3>
            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Car Model</th>
                            <th>Retailer</th>
                            <th>Vendor</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentStockMovements as $stock)
                            <tr>
                                <td>{{ $stock->car_model }}</td>
                                <td>{{ $stock->retailer->name ?? 'N/A' }}</td>
                                <td>{{ $stock->vendor->name ?? 'N/A' }}</td>
                                <td>{{ $stock->quantity_received }}</td>
                                <td><span class="badge bg-success">{{ ucfirst($stock->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-md-6 mb-3">
            <a href="{{ route('customer.browse.products') }}" class="btn btn-lg btn-block btn-primary w-100">
                <i class="fas fa-search"></i> Browse All Products
            </a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="{{ route('customer.track.order') }}" class="btn btn-lg btn-block btn-success w-100">
                <i class="fas fa-truck"></i> Track Your Order
            </a>
        </div>
    </div>

    <!-- Retailer Stock Overview -->
    <div style="margin-top: 2rem;">
        <h3 style="color: var(--maroon); font-size: 1.4rem;">
            <i class="fas fa-store"></i> Retailer Stock Overview
        </h3>
        @forelse ($retailers as $retailer)
            <div class="bg-white rounded shadow-sm p-3 mb-4">
                <h4 style="color: var(--deep-purple); font-weight: bold;">
                    <i class="fas fa-user-tag"></i> {{ $retailer->name }}
                </h4>
                <p style="color: #666; margin-bottom: 1rem;">{{ $retailer->company ?? 'No company info' }}</p>
                @php
                    $retailerStock = \App\Models\RetailerStock::where('retailer_id', $retailer->id)
                        ->where('status', 'accepted')
                        ->get();
                @endphp
                @if ($retailerStock->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Car Model</th>
                                    <th>Vendor</th>
                                    <th>In Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retailerStock as $stock)
                                    <tr>
                                        <td>{{ $stock->car_model }}</td>
                                        <td>{{ $stock->vendor_name ?? 'N/A' }}</td>
                                        <td>{{ $stock->quantity_received }}</td>
                                        <td><span class="badge bg-success">{{ ucfirst($stock->status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="color: gray;">No stock available at the moment.</p>
                @endif
            </div>
        @empty
            <p>No retailers found.</p>
        @endforelse
    </div>
</div>
@endsection 