<?php

namespace App\Http\Controllers\Manufacturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create()
    {
        // Return a view for creating a new product (to be created)
        return view('dashboards.manufacturer.products-create');
    }

    public function store(Request $request)
    {
        // Handle storing a new product (to be implemented)
        // ...
        return redirect()->route('manufacturer.products')->with('success', 'Product created successfully!');
    }

    public function edit($id)
    {
        // Return a view for editing a product (to be created)
        // $product = ... fetch product by $id
        return view('dashboards.manufacturer.products-edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Handle updating a product (to be implemented)
        // ...
        return redirect()->route('manufacturer.products')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        // Handle deleting a product (to be implemented)
        // ...
        return redirect()->route('manufacturer.products')->with('success', 'Product deleted successfully!');
    }

    public function index()
    {
        $manufacturerId = Auth::id();
        $products = Product::where('manufacturer_id', $manufacturerId)->get();
        $lowStockCount = $products->where('stock', '<', 5)->count();
        $categoryCount = $products->pluck('category')->unique()->count();
        $categories = $products->pluck('category')->unique();
        return view('dashboards.manufacturer.products', compact('products', 'lowStockCount', 'categoryCount', 'categories'));
    }
} 