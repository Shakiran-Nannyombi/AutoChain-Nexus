@extends('layouts.dashboard')

@section('title', 'Edit Product')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 class="page-title"><i class="fas fa-edit"></i> Edit Product #{{ $id }}</h2>
    <form method="POST" action="{{ route('manufacturer.products.update', $id) }}" class="products-form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $product->category) }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <button type="submit" class="button add-product-btn">Update Product</button>
    </form>
</div>
@endsection 