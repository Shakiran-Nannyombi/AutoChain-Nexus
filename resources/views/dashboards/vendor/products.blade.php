@extends('layouts.dashboard')

@section('title', 'Vendor Products')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Products Overview
    </h2>
    <a href="{{ route('vendor.products.create') }}" class="button" style="margin-bottom:1.2rem; background:var(--primary); color:#fff; padding:0.6rem 1.2rem; border-radius:8px;">+ Add Product</a>
    <table class="order-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a href="{{ route('vendor.products.edit', $product->id) }}" class="button" style="background:var(--accent); color:#fff; padding:0.4rem 1rem; border-radius:6px;">Edit</a>
                        <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button" style="background:var(--danger); color:#fff; padding:0.4rem 1rem; border-radius:6px;" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No products found.</td></tr>
            @endforelse
                </tbody>
            </table>
        </div>
@endsection