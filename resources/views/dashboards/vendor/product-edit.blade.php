@extends('layouts.dashboard')

@section('title', 'Edit Product')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">Edit Product</h2>
    <form method="POST" action="{{ route('vendor.products.update', $product->id) }}" style="max-width: 500px;">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $product->category) }}" required>
            @error('category')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
            @error('price')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
            @error('stock')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div style="margin-top:1.2rem;">
            <button type="submit" class="button" style="background:var(--primary); color:#fff; padding:0.6rem 1.2rem; border-radius:8px;">Update</button>
            <a href="{{ route('vendor.products') }}" class="button" style="background:#eee; color:#333; padding:0.6rem 1.2rem; border-radius:8px;">Back</a>
        </div>
    </form>
</div>
@endsection 