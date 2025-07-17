@extends(layouts.dashboard')

@section('title', 'Browse Products')

@section('sidebar-content)    @include('dashboards.customer.sidebar')
@endsection

@section('content')
<div class="content-card>  <h2 style=color: var(--deep-purple); margin-bottom: 1>
        <i class=fas fa-search"></i> Browse Products
    </h2>

    <!-- Search and Filter Form -->
    <div style=background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4x rgba(0.1); margin-bottom:2m;">
        <form method="GETaction="{{ route('customer.browse.products') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200,1; gap: 1; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Search Products</label>
                <input type="text" name=search value="{{ request('search) }}" placeholder=Search by product name..." style="width: 100%; padding:00.5 border: 1px solid #ddd; border-radius: 4;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Category</label>
                <select name=category" style="width: 100%; padding:00.5 border: 1px solid #ddd; border-radius: 4px;">
                    <option value=">AllCategories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? selected' : >{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 60;">Vendor</label>
                <select name="vendor_id" style="width: 100%; padding:00.5 border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Vendors</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? selected' : }>{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" style="background: var(--primary); color: white; padding: 0.51rem; border: none; border-radius: 4px; cursor: pointer;>Search</button>
                <a href="{{ route('customer.browse.products') }}" style=background: #666or: white; padding: 0.51rem; border: none; border-radius: 4px; text-decoration: none; margin-left:00.5ar</a>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1); gap: 1.5rem;">
            @foreach($products as $product)
                <div style=background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4x rgba(0,0,0,0.1);">
                    <h3 style="color: var(--primary); margin-bottom: 0.5rem; font-size: 1.2em;">{{ $product->name }}</h3>
                    <p style="color: #666; margin-bottom: 0.5em;">{{ $product->category ??General                   <p style="color: var(--accent); font-weight: 60 font-size: 1.1rem; margin-bottom: 00.5>${{ number_format($product->price, 2) }}</p>
                    <p style="color: var(--success); font-weight: 600; margin-bottom: 05Stock: {{ $product->stock }}</p>
                    @if($product->vendor)
                        <p style=color: #888 font-size: 0.9rem; margin-bottom:1rem;">Vendor: {[object Object]$product->vendor->name }}</p>
                    @endif
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('customer.product.show', $product->id) }}" style="background: var(--primary); color: white; padding:0.5m; text-decoration: none; border-radius: 4lex: 1; text-align: center;">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style=margin-top:2text-align: center;">
        [object Object][object Object]$products->links() }}
        </div>
    @else
        <div style="text-align: center; padding:3rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 4x rgba(0,0,0,00.1);">
            <i class="fas fa-search style="font-size: 3olor: #ccc; margin-bottom: 1rem;></i>
            <h3 style="color: #666; margin-bottom: 0.5>No products found</h3>
            <p style=color: #888Try adjusting your search criteria or browse all products.</p>
        </div>
    @endif
</div>
@endsection 