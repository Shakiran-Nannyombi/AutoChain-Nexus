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
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $manufacturerId = Auth::id();
        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'manufacturer_id' => $manufacturerId,
        ]);
        return redirect()->route('manufacturer.products')->with('success', 'Product created successfully!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('dashboards.manufacturer.products-edit', compact('product', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
        return redirect()->route('manufacturer.products')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
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