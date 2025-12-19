@extends('layouts.dashboard')
@section('title', 'Retailer Reports')
@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection
@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-file-alt"></i> Retailer Reports
        </h2>

        <!-- Reports Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Total Sales Card -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 12px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">Total Sales</p>
                        <h3 style="margin: 0.5rem 0 0 0; font-size: 2rem;">{{ \App\Models\RetailerSale::where('retailer_id', Auth::id())->sum('quantity_sold') ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-chart-line" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Total Stock Card -->
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 1.5rem; border-radius: 12px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">Current Stock</p>
                        <h3 style="margin: 0.5rem 0 0 0; font-size: 2rem;">{{ \App\Models\RetailerStock::where('retailer_id', Auth::id())->where('status', 'accepted')->sum('quantity_received') ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-boxes" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Pending Orders Card -->
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 1.5rem; border-radius: 12px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">Pending Orders</p>
                        <h3 style="margin: 0.5rem 0 0 0; font-size: 2rem;">{{ \App\Models\RetailerOrder::where('user_id', Auth::id())->where('status', 'pending')->count() ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>

            <!-- Customer Orders Card -->
            <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 1.5rem; border-radius: 12px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">Customer Orders</p>
                        <h3 style="margin: 0.5rem 0 0 0; font-size: 2rem;">{{ \App\Models\CustomerOrder::where('retailer_id', Auth::id())->count() ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <!-- Sales Report Section -->
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-chart-bar"></i> Sales by Model
            </h3>
            @php
                $salesByModel = \App\Models\RetailerSale::where('retailer_id', Auth::id())
                    ->select('car_model', \DB::raw('SUM(quantity_sold) as total_sold'))
                    ->groupBy('car_model')
                    ->orderByDesc('total_sold')
                    ->get();
            @endphp
            @if($salesByModel->count() > 0)
                <div class="table-responsive">
                    <table class="table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 1rem; text-align: left; color: var(--deep-purple);">Car Model</th>
                                <th style="padding: 1rem; text-align: right; color: var(--deep-purple);">Total Sold</th>
                                <th style="padding: 1rem; text-align: right; color: var(--deep-purple);">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalSales = $salesByModel->sum('total_sold'); @endphp
                            @foreach($salesByModel as $sale)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 1rem;">{{ $sale->car_model }}</td>
                                    <td style="padding: 1rem; text-align: right; font-weight: 600;">{{ $sale->total_sold }}</td>
                                    <td style="padding: 1rem; text-align: right;">
                                        <span style="background: #667eea; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">
                                            {{ $totalSales > 0 ? round(($sale->total_sold / $totalSales) * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #6c757d; padding: 2rem;">No sales data available yet.</p>
            @endif
        </div>

        <!-- Stock Report Section -->
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-warehouse"></i> Current Stock by Model
            </h3>
            @php
                $stockByModel = \App\Models\RetailerStock::where('retailer_id', Auth::id())
                    ->where('status', 'accepted')
                    ->select('car_model', \DB::raw('SUM(quantity_received) as total_stock'))
                    ->groupBy('car_model')
                    ->orderByDesc('total_stock')
                    ->get();
            @endphp
            @if($stockByModel->count() > 0)
                <div class="table-responsive">
                    <table class="table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 1rem; text-align: left; color: var(--deep-purple);">Car Model</th>
                                <th style="padding: 1rem; text-align: right; color: var(--deep-purple);">Quantity</th>
                                <th style="padding: 1rem; text-align: center; color: var(--deep-purple);">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockByModel as $stock)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 1rem;">{{ $stock->car_model }}</td>
                                    <td style="padding: 1rem; text-align: right; font-weight: 600;">{{ $stock->total_stock }}</td>
                                    <td style="padding: 1rem; text-align: center;">
                                        @if($stock->total_stock < 5)
                                            <span style="background: #f5576c; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">
                                                <i class="fas fa-exclamation-triangle"></i> Low Stock
                                            </span>
                                        @elseif($stock->total_stock < 15)
                                            <span style="background: #ffa726; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">
                                                <i class="fas fa-info-circle"></i> Medium
                                            </span>
                                        @else
                                            <span style="background: #43e97b; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">
                                                <i class="fas fa-check-circle"></i> Good
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #6c757d; padding: 2rem;">No stock data available yet.</p>
            @endif
        </div>

        <!-- Recent Orders Section -->
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-shopping-cart"></i> Recent Orders
            </h3>
            @php
                $recentOrders = \App\Models\RetailerOrder::where('user_id', Auth::id())
                    ->with('vendor')
                    ->orderByDesc('created_at')
                    ->take(10)
                    ->get();
            @endphp
            @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 1rem; text-align: left; color: var(--deep-purple);">Order ID</th>
                                <th style="padding: 1rem; text-align: left; color: var(--deep-purple);">Vendor</th>
                                <th style="padding: 1rem; text-align: left; color: var(--deep-purple);">Model</th>
                                <th style="padding: 1rem; text-align: right; color: var(--deep-purple);">Quantity</th>
                                <th style="padding: 1rem; text-align: center; color: var(--deep-purple);">Status</th>
                                <th style="padding: 1rem; text-align: left; color: var(--deep-purple);">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 1rem; font-family: monospace;">#{{ $order->id }}</td>
                                    <td style="padding: 1rem;">{{ $order->vendor->name ?? 'N/A' }}</td>
                                    <td style="padding: 1rem;">{{ $order->car_model }}</td>
                                    <td style="padding: 1rem; text-align: right; font-weight: 600;">{{ $order->quantity }}</td>
                                    <td style="padding: 1rem; text-align: center;">
                                        @if($order->status === 'pending')
                                            <span style="background: #ffa726; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">Pending</span>
                                        @elseif($order->status === 'confirmed')
                                            <span style="background: #4facfe; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">Confirmed</span>
                                        @elseif($order->status === 'shipped')
                                            <span style="background: #667eea; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">Shipped</span>
                                        @elseif($order->status === 'delivered')
                                            <span style="background: #43e97b; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">Delivered</span>
                                        @else
                                            <span style="background: #f5576c; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem;">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #6c757d; padding: 2rem;">No orders placed yet.</p>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Custom scripts for the reports page
        console.log('Retailer Reports Page Loaded');
    </script>
@endsection
