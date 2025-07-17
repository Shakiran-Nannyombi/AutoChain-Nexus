<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\RetailerStock;
use App\Models\VendorActivity;

class VendorWarehouseController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        
        // Get vendor's products with stock information
        $products = Product::where('vendor_id', $vendorId)
            ->orderBy('name')
            ->get();
        
        // Get stock sent to retailers
        $retailerStocks = RetailerStock::where('vendor_id', $vendorId)
            ->with('retailer')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate warehouse statistics
        $totalProducts = $products->count();
        $totalStock = $products->sum('stock');
        $lowStockProducts = $products->where('stock', '<', 10)->count();
        $outOfStockProducts = $products->where('stock', 0)->count();
        
        // Get recent stock movements
        $recentStockMovements = RetailerStock::where('vendor_id', $vendorId)
            ->with('retailer')
            ->latest()
            ->take(10)
            ->get();
        
        // Get stock by status
        $stockByStatus = RetailerStock::where('vendor_id', $vendorId)
            ->selectRaw('status, COUNT(*) as count, SUM(quantity_received) as total_quantity')
            ->groupBy('status')
            ->get();
        
        return view('dashboards.vendor.warehouse', compact(
            'products',
            'retailerStocks',
            'totalProducts',
            'totalStock',
            'lowStockProducts',
            'outOfStockProducts',
            'recentStockMovements',
            'stockByStatus'
        ));
    }
    
    public function updateStock(Request $request, $productId)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);
        
        $product = Product::where('vendor_id', Auth::id())
            ->findOrFail($productId);
        
        $oldStock = $product->stock;
        $product->update(['stock' => $request->stock]);
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Updated stock',
            'details' => "Product: {$product->name}, Old: {$oldStock}, New: {$request->stock}",
        ]);
        
        return back()->with('success', 'Stock updated successfully.');
    }
} 