<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\VendorActivity;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    // List all products for the current vendor
    public function index()
    {
        $products = Product::where('vendor_id', Auth::id())->get();
        return view('dashboards.vendor.products', compact('products'));
    }

    // Show create form
    public function create()
    {
        return view('dashboards.vendor.product-create');
    }

    // Store new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $product = Product::create(array_merge($validated, [
            'vendor_id' => Auth::id(),
        ]));
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Created product',
            'details' => 'Product: ' . $product->name,
        ]);
        return redirect()->route('vendor.products')->with('success', 'Product created.');
    }

    // Show edit form
    public function edit($id)
    {
        $product = Product::where('vendor_id', Auth::id())->findOrFail($id);
        return view('dashboards.vendor.product-edit', compact('product'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::where('vendor_id', Auth::id())->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $product->update($validated);
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Updated product',
            'details' => 'Product: ' . $product->name,
        ]);
        return redirect()->route('vendor.products')->with('success', 'Product updated.');
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::where('vendor_id', Auth::id())->findOrFail($id);
        $product->delete();
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Deleted product',
            'details' => 'Product: ' . $product->name,
        ]);
        return redirect()->route('vendor.products')->with('success', 'Product deleted.');
    }
} 