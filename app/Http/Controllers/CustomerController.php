<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retailer;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Models\RetailerStock;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function dashboard()
    {
        // For public access, we'll show general market data
        // Get all retailers with their stock information
        $retailers = User::where('role', 'retailer')
            ->where('status', 'approved')
            ->get();
        
        // Get available products from all vendors
        $availableProducts = Product::with('vendor')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->take(10)
            ->get();
        
        // Get recent stock movements (accepted deliveries to retailers)
        $recentStockMovements = RetailerStock::where('status', 'accepted')
            ->with(['retailer', 'vendor'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        // Get market statistics
        $marketStats = [
            'total_products' => Product::where('stock', '>', 0)->count(),
            'total_retailers' => User::where('role', 'retailer')->where('status', 'approved')->count(),
            'total_vendors' => User::where('role', 'vendor')->where('status', 'approved')->count(),
            'recent_deliveries' => RetailerStock::where('status', 'accepted')->where('updated_at', '>=', now()->subDays(7))->count()
        ];
        
        // Get featured products (products with highest stock)
        $featuredProducts = Product::with('vendor')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboards.customer.index', compact(
            'retailers', 
            'availableProducts',        'recentStockMovements',
            'marketStats',
            'featuredProducts'
        ));
    }

    public function browseProducts(Request $request)
    {
        $query = Product::with('vendor');
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        // Filter by vendor
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }
        
        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->orderBy('name')->paginate(12);
        $vendors = User::where('role', 'vendor')->where('status', 'approved')->get();
        $categories = Product::distinct()->pluck('category')->filter();
        
        return view('dashboards.customer.browse-products', compact('products', 'vendors', 'categories'));
    }

    public function showProduct($id)
    {
        $product = Product::with('vendor')->findOrFail($id);
        $relatedProducts = Product::with('vendor')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        // Get all retailer stocks for this product
        $retailerStocks = \App\Models\RetailerStock::where('car_model', $product->name)
            ->where('status', 'accepted')
            ->with('retailer')
            ->get();

        // Only show retailers who have accepted stock for this product
        $retailers = $retailerStocks->pluck('retailer')->unique('id')->values();
        $retailerStocksGrouped = $retailerStocks->groupBy('retailer_id');

        return view('dashboards.customer.product-detail', compact('product', 'relatedProducts', 'retailerStocksGrouped', 'retailers'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:50',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'retailer_id' => 'required|exists:users,id'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if retailer has enough stock for this product
        $retailerStock = \App\Models\RetailerStock::where('retailer_id', $request->retailer_id)
            ->where('car_model', $product->name)
            ->where('status', 'accepted')
            ->sum('quantity_received');

        $orderedQty = CustomerOrder::where('retailer_id', $request->retailer_id)
            ->where('product_id', $product->id)
            ->sum('quantity');

        if ($retailerStock - $orderedQty < $request->quantity) {
            return back()->with('error', 'Insufficient stock at this retailer.');
        }

        // Create or find customer
        $customer = Customer::firstOrCreate(
            ['email' => $request->customer_email],
            [
                'name' => $request->customer_name,
                'phone' => $request->customer_phone,
                'address' => $request->customer_address,
                'segment' => 3 // Default to At Risk Customers for new
            ]
        );

        // Create customer order
        $order = CustomerOrder::create([
            'customer_id' => $customer->id,
            'retailer_id' => $request->retailer_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_amount' => $product->price * $request->quantity,
            'status' => 'pending',
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'order_date' => now()
        ]);

        // Dynamic segmentation logic
        $totalOrders = CustomerOrder::where('customer_id', $customer->id)->count();
        $totalSpent = CustomerOrder::where('customer_id', $customer->id)->sum('total_amount');
        if ($totalOrders >= 10 || $totalSpent > 100000) {
            $customer->segment = 2; // High Value Customers
        } elseif ($totalOrders >= 3) {
            $customer->segment = 1; // Occasional Buyers
        } else {
            $customer->segment = 3; // At Risk Customers
        }
        $customer->save();

        // (Optional) If you want to also decrement global product stock, uncomment:
        // $product->decrement('stock', $request->quantity);

        return redirect()->route('customer.order.confirmation', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function orderConfirmation($orderId)
    {
        $order = CustomerOrder::with(['customer', 'retailer', 'product'])->findOrFail($orderId);
        return view('dashboards.customer.order-confirmation', compact('order'));
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        $error = null;

        if ($request->has('order_id') && $request->has('customer_email')) {
            $order = CustomerOrder::where('id', $request->order_id)
                ->where('customer_email', $request->customer_email)
                ->with(['customer', 'retailer', 'product'])
                ->first();

            if (!$order) {
                $error = 'Order not found. Please check your order ID and email.';
            }
        }

        return view('dashboards.customer.track-order', compact('order', 'error'));
    }

    public function getRecommendations(Customer $customer)
    {
        $recommendations = $customer->recommendProducts();

        return response()->json([
            'customer_id' => $customer->id,
            'segment' => $customer->segment,
            'recommendations' => $recommendations
        ]);
    }

    public function list()
    {
        $customers = \App\Models\Customer::withCount('purchases')
            ->withSum('purchases as total_spent', 'amount')
            ->get();

        // Calculate recency (days since last purchase)
        foreach ($customers as $customer) {
            $lastPurchase = $customer->purchases()->orderByDesc('purchase_date')->first();
            $customer->recency = $lastPurchase
                ? now()->diffInDays($lastPurchase->purchase_date)
                : null;
        }

        return view('dashboards.customer.list', compact('customers'));
    }

    public function show(Customer $customer)
    {
        return view('dashboards.customer.show', compact('customer'));
    }
    
    private function getSegmentName($segment)
    {
        $segmentNames = [
            1 => 'Occasional Buyers',
            2 => 'High Value Customers', 
            3 => 'At Risk Customers',
        ];
        
        return $segmentNames[$segment] ?? 'Unsegmented';
    }
}