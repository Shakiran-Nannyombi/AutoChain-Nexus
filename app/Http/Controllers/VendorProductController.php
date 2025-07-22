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
        $vendorId = Auth::id();
        // Products owned by the vendor
        $products = Product::where('vendor_id', $vendorId)->get();
        // Products from fulfilled vendor orders (grouped by product)
        $fulfilledOrders = \App\Models\VendorOrder::where('vendor_id', $vendorId)
            ->where('status', 'fulfilled')
            ->get();
        $orderedProducts = $fulfilledOrders->groupBy('product_name')->map(function($orders, $name) {
            $first = $orders->first();
            return [
                'name' => $name,
                'category' => $first->product_category,
                'price' => $first->unit_price,
                'stock' => $orders->sum('quantity'),
                'orders_count' => $orders->count(),
                'image_url' => $first->image_url ?? null,
            ];
        })->values();
        return view('dashboards.vendor.products', compact('products', 'orderedProducts'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data = array_merge($validated, [
            'vendor_id' => Auth::id(),
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'car_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image_url'] = 'images/' . $imageName;
        }
        $product = Product::create($data);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data = $validated;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'car_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image_url'] = 'images/' . $imageName;
        }
        $product->update($data);
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