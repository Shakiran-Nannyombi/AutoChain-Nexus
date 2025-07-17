@extends('layouts.dashboard')

@section('title', $product->name)

@section('sidebar-content')
    @include('dashboards.customer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div class="row" style="gap: 2rem; margin-bottom:2rem;">
        <!-- Product Details -->
        <div class="col-md-7">
            <h2 style="color: var(--deep-purple); margin-bottom: 1em;">{{ $product->name }}</h2>
            <div class="bg-white p-4 rounded shadow-sm mb-4">
                <div class="row mb-3">
                    <div class="col-6">
                        <strong style="color: var(--primary);">Category:</strong>
                        <p>{{ $product->category ?? 'General' }}</p>
                    </div>
                    <div class="col-6">
                        <strong style="color: var(--primary);">Price:</strong>
                        <p style="color: var(--accent); font-weight:600;">${{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="col-6">
                        <strong style="color: var(--primary);">Stock:</strong>
                        <p style="color: var(--success); font-weight:600;">{{ $product->stock }} units</p>
                    </div>
                    @if($product->vendor)
                    <div class="col-6">
                        <strong style="color: var(--primary);">Vendor:</strong>
                        <p>{{ $product->vendor->name }}</p>
                    </div>
                    @endif
                </div>

                <!-- Order Form -->
                @if($product->stock > 0)
                <form method="POST" action="{{ route('customer.place.order') }}" style="border-top: 1px solid #eee; padding-top:2rem;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <h3 style="color: var(--maroon); margin-bottom:1rem;">Place Your Order</h3>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Email *</label>
                            <input type="email" name="customer_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Phone *</label>
                            <input type="tel" name="customer_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Quantity *</label>
                            <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Address *</label>
                        <textarea name="customer_address" rows="3" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Retailer *</label>
                        <select name="retailer_id" class="form-control" required>
                            <option value="">Choose a retailer...</option>
                            @foreach(\App\Models\User::where('role', 'retailer')->where('status', 'approved')->get() as $retailer)
                                <option value="{{ $retailer->id }}">{{ $retailer->name }}{{ $retailer->company ? ' (' . $retailer->company . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-shopping-cart"></i> Place Order - ${{ number_format($product->price, 2) }}
                    </button>
                </form>
                @else
                <div class="alert alert-danger text-center mt-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4 class="mt-2">Out of Stock</h4>
                    <p>This product is currently out of stock. Please check back later.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="col-md-4">
            <h3 style="color: var(--blue); margin-bottom: 1rem;">Related Products</h3>
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white p-3 rounded shadow-sm mb-3">
                <h4 style="color: var(--primary); margin-bottom: 0.5rem;">{{ $relatedProduct->name }}</h4>
                <div style="color: #666; margin-bottom: 0.5rem;">{{ $relatedProduct->category ?? 'General' }}</div>
                <div style="color: var(--accent); font-weight: 600;">${{ number_format($relatedProduct->price, 2) }}</div>
                <div style="color: var(--success); font-weight: 600;">Stock: {{ $relatedProduct->stock }}</div>
                <a href="{{ route('customer.product.show', $relatedProduct->id) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection