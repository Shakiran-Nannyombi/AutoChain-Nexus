@extends('layouts.dashboard')

@section('title', 'Vendor Products')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card vendor-products">
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.8rem; color: var(--text); letter-spacing: 0.01em;">Stock Management</h1>
    <div class="stat-cards-row">
        <div class="stat-card" style="background: #125bed;">
            <div class="stat-label" style="color: white">Total Models</div>
            <div class="stat-value" style="color: white">{{ ($products->count() + $orderedProducts->count()) }}</div>
            <div class="stat-sub" style="color: rgb(212, 210, 210)">+{{ $products->count() }} this month</div>
        </div>
        <div class="stat-card" style="background: #7e078b;">
            <div class="stat-label" style="color: white">Active Models</div>
            <div class="stat-value" style="color: white">{{ $products->where('status', 'active')->count() }}</div>
            <div class="stat-sub" style="color: rgb(211, 207, 207)">83% of total</div>
        </div>
        <div class="stat-card" style="background: #cf550e;">
            <div class="stat-label" style="color: white">Total Inventory</div>
            <div class="stat-value" style="color: white">{{ $products->sum('stock') + $orderedProducts->sum('stock') }}</div>
            <div class="stat-sub" style="color: rgb(223, 220, 220)">Units in stock</div>
        </div>
        <div class="stat-card" style="background: #b7c70d;">
            <div class="stat-label" style="color: white">Pending Orders</div>
            <div class="stat-value" style="color: white">42</div>
            <div class="stat-sub" style="color: rgb(217, 217, 217)">Across all models</div>
        </div>
    </div>
    <div class="view-toggle-row">
        <h2 class="section-title">Model Inventory</h2>
        <div class="view-toggle">
            <button id="tableViewBtn" class="toggle-btn active"><i class="fas fa-table"></i></button>
            <button id="gridViewBtn" class="toggle-btn"><i class="fas fa-th"></i></button>
        </div>
        <a href="{{ route('vendor.products.create') }}" class="button add-product-btn">+ Add New Model</a>
    </div>
    <div id="tableView" class="products-table-view">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Inventory</th>
                    <th>Orders</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="{{ asset($product->image_url ?? 'images/car1.png') }}" alt="{{ $product->name }}" class="product-img">
                                <div>
                                    <div>{{ $product->name }}</div>
                                    <div class="product-id">CM-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category }}</td>
                        <td><b>Shs {{ number_format($product->price) }}</b></td>
                        <td><span class="status-dot status-{{ $product->status ?? 'active' }}"></span> {{ ucfirst($product->status ?? 'active') }}</td>
                        <td><span class="inventory-badge {{ $product->stock < 15 ? 'low' : '' }}">{{ $product->stock }} units</span></td>
                        <td>0</td>
                    </tr>
                @endforeach
                @foreach($orderedProducts as $product)
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="{{ $product['image_url'] ?? asset('images/car1.png') }}" alt="{{ $product['name'] }}" class="product-img">
                                <div>
                                    <div>{{ $product['name'] }}</div>
                                    <div class="product-id">-</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product['category'] }}</td>
                        <td><b>Shs {{ number_format($product['price']) }}</b></td>
                        <td><span class="status-dot status-active"></span> Active</td>
                        <td><span class="inventory-badge {{ $product['stock'] < 15 ? 'low' : '' }}">{{ $product['stock'] }} units</span></td>
                        <td>{{ $product['orders_count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="gridView" class="products-grid-view" style="display:none;">
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card">
                    <img src="{{ asset($product->image_url ?? 'images/car1.png') }}" alt="{{ $product->name }}" class="product-img">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-id">CM-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="product-category">{{ $product->category }}</div>
                    <div class="product-price">Shs {{ number_format($product->price) }}</div>
                    <div class="product-status"><span class="status-dot status-{{ $product->status ?? 'active' }}"></span> {{ ucfirst($product->status ?? 'active') }}</div>
                    <div class="product-inventory {{ $product->stock < 15 ? 'low' : '' }}">{{ $product->stock }} units</div>
                </div>
            @endforeach
            @foreach($orderedProducts as $product)
                <div class="product-card">
                    <img src="{{ $product['image_url'] ?? asset('images/car1.png') }}" alt="{{ $product['name'] }}" class="product-img">
                    <div class="product-name">{{ $product['name'] }}</div>
                    <div class="product-id">-</div>
                    <div class="product-category">{{ $product['category'] }}</div>
                    <div class="product-price">Shs {{ number_format($product['price']) }}</div>
                    <div class="product-status"><span class="status-dot status-active"></span> Active</div>
                    <div class="product-inventory {{ $product['stock'] < 15 ? 'low' : '' }}">{{ $product['stock'] }} units</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@push('styles')
<style>
.vendor-products { background: var(--background); }
.page-title { font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--text); }
.stat-cards-row { display: flex; gap: 1.5rem; margin-bottom: 2.5rem; }
.stat-card { flex:1; min-width: 180px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start; }
.stat-label { color: #555; font-size: 1.01rem; margin-bottom: 0.2rem; }
.stat-value { font-size: 1.3rem; font-weight: 700; color: #222; }
.stat-sub { color: #888; font-size: 0.95rem; }
.section-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 1.2rem; color: #222; }
.view-toggle-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; }
.view-toggle { display: flex; gap: 0.5rem; }
.toggle-btn { background: #f3f4f6; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 1.1rem; cursor: pointer; }
.toggle-btn.active { background: var(--primary); color: #fff; }
.add-product-btn { background: var(--primary); color: #fff; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; }
.products-table { width: 100%; border-collapse: collapse; background: #fff; }
.products-table th, .products-table td { padding: 0.8rem 0.5rem; text-align: left; border-bottom: 1px solid #f0f0f0; }
.products-table th { background: #f8fafc; color: var(--primary); font-weight: 700; }
.products-table tr:last-child td { border-bottom: none; }
.product-info { display: flex; align-items: center; gap: 1rem; }
.product-img { width: 48px; height: 48px; object-fit: contain; border-radius: 8px; background: #f3f4f6; }
.product-id { color: #888; font-size: 0.95rem; }
.status-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 0.4rem; }
.status-active { background: #10b981; }
.status-discontinued { background: #ef4444; }
.inventory-badge { background: #f3f4f6; color: #222; border-radius: 12px; padding: 0.2rem 0.8rem; font-size: 0.98rem; font-weight: 600; }
.inventory-badge.low, .product-inventory.low { background: #ef4444; color: #fff; }
.products-grid-view { margin-top: 2rem; }
.products-grid { display: flex; flex-wrap: wrap; gap: 1.5rem; }
.product-card { background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.2rem 1rem; min-width: 220px; max-width: 260px; display: flex; flex-direction: column; align-items: flex-start; }
.product-name { font-weight: 700; font-size: 1.1rem; margin-bottom: 0.2rem; }
.product-category { color: #444; margin-bottom: 0.2rem; }
.product-price { font-size: 1.05rem; font-weight: 600; color: #2563eb; margin-bottom: 0.2rem; }
.product-status { margin-bottom: 0.2rem; }
.product-inventory { font-size: 0.98rem; color: #444; margin-bottom: 0.2rem; background: #f3f4f6; border-radius: 12px; padding: 0.2rem 0.8rem; font-weight: 600; }
.product-inventory.low { background: #ef4444; color: #fff; }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableViewBtn = document.getElementById('tableViewBtn');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const tableView = document.getElementById('tableView');
    const gridView = document.getElementById('gridView');
    tableViewBtn.addEventListener('click', function() {
        tableView.style.display = '';
        gridView.style.display = 'none';
        tableViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
    });
    gridViewBtn.addEventListener('click', function() {
        tableView.style.display = 'none';
        gridView.style.display = '';
        gridViewBtn.classList.add('active');
        tableViewBtn.classList.remove('active');
    });
});
</script>
@endpush
@endsection