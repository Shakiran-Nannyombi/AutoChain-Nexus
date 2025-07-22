@extends('layouts.dashboard')

@section('title', 'Manufacturer Products')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div class="products-header-row">
        <h2 class="page-title" style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 0.2rem;"><i class="fas fa-box"></i> Products Overview</h2>
        <a href="{{ route('manufacturer.products.create') }}" class="button add-product-btn">+ Add Product</a>
    </div>
    <!-- Summary Stats Row -->
    <div class="products-stats-row">
        <div class="stat-card mini">
            <div class="stat-label" style="color: black;">Total Products</div>
            <div class="stat-value" style="color: black;">{{ $products->count() ?? 0 }}</div>
        </div>
        <div class="stat-card mini">
            <div class="stat-label" style="color: black;">Out of Stock</div>
            <div class="stat-value" style="color: black;">{{ $products->where('stock', '<', 100)->count() }}</div>
        </div>
        <div class="stat-card mini">
            <div class="stat-label" style="color: black;">Categories</div>
            <div class="stat-value" style="color: black;">{{ $categoryCount ?? 0 }}</div>
        </div>
    </div>
    <!-- Search/Filter Bar -->
    <form method="GET" action="" class="products-search-bar" id="products-search-form" onsubmit="return false;">
        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search products..." class="search-input">
        <select name="category" id="category-select" class="search-select">
            <option value="">All Products</option>
            @foreach($products as $product)
                <option value="product:{{ $product->name }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="button search-btn" id="search-btn"><i class="fas fa-search"></i> Search</button>
    </form>
    <!-- Products Table -->
    <table class="dashboard-table products-table" id="products-table"> 
        <thead>
            <tr>
                <th style="color: var(--text);">Name</th>
                <th style="color: var(--text);">Category</th>
                <th style="color: var(--text);">Price</th>
                <th style="color: var(--text);">Stock</th>
                <th style="color: var(--text);">Status</th>
                <th style="color: var(--text);">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="product-name">{{ $product->name }}</td>
                    <td class="product-category">{{ $product->category }}</td>
                    <td>shs {{ number_format($product->price, 0, '.', ',') }}</td>
                    <td class="product-stock">{{ $product->stock }}</td>
                    <td>
                        @if($product->stock < 100)
                            <span class="badge badge-danger">Out of Stock</span>
                        @else
                            <span class="badge badge-success">In Stock</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('manufacturer.products.edit', $product->id) }}" class="button action-btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('manufacturer.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button action-btn delete-btn" onclick="return confirm('Delete this product?')"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const categorySelect = document.getElementById('category-select');
        const table = document.getElementById('products-table');
        const rows = table.querySelectorAll('tbody tr');
        const searchBtn = document.getElementById('search-btn');

        function filterTable() {
            const search = searchInput.value.toLowerCase();
            const category = categorySelect.value;
            let anyVisible = false;
            rows.forEach(row => {
                const name = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
                let show = true;
                if (category && category.startsWith('product:')) {
                    const prodName = category.replace('product:', '');
                    if (row.querySelector('.product-name')?.textContent !== prodName) show = false;
                } else {
                    if (search && !name.includes(search)) show = false;
                }
                row.style.display = show ? '' : 'none';
                if (show) anyVisible = true;
            });
            // Show/hide 'No products found.' row
            const emptyRow = table.querySelector('tbody .text-center');
            if (emptyRow) emptyRow.style.display = anyVisible ? 'none' : '';
        }

        searchInput.addEventListener('input', filterTable);
        categorySelect.addEventListener('change', filterTable);
        searchBtn.addEventListener('click', filterTable);
    });
</script>
@endpush
@endsection
