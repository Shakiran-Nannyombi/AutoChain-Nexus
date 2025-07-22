@extends('layouts.dashboard')

@section('title', 'Vendor Orders')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card" style="background: #fafbfc; box-shadow: none;">
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.2rem; color: var(--primary); letter-spacing: 0.01em;">Order Management</h1>
    <div style="font-size: 1.1rem; color: #555; margin-bottom: 2.2rem;">Manage manufacturer orders and confirm retailer requests</div>
    <!-- Stat Cards -->
    <div style="display: flex; gap: 1.5rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
        <div style="flex:1; min-width: 180px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #2563eb;">
            <div style="font-size: 1.3rem; font-weight: 700; color: #222;">{{ $totalModels ?? 0 }}</div>
            <div style="color: #555; font-size: 1.01rem; margin-top: 0.2rem;">Total Models</div>
        </div>
        <div style="flex:1; min-width: 180px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #10b981;">
            <div style="font-size: 1.3rem; font-weight: 700; color: #222;">{{ $activeModels ?? 0 }}</div>
            <div style="color: #555; font-size: 1.01rem; margin-top: 0.2rem;">Active Models</div>
        </div>
        <div style="flex:1; min-width: 180px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #f59e0b;">
            <div style="font-size: 1.3rem; font-weight: 700; color: #222;">{{ $totalInventory ?? 0 }}</div>
            <div style="color: #555; font-size: 1.01rem; margin-top: 0.2rem;">Total Inventory</div>
        </div>
        <div style="flex:1; min-width: 180px; background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start; border-left: 6px solid #a21caf;">
            <div style="font-size: 1.3rem; font-weight: 700; color: #222;">{{ $pendingOrders ?? 0 }}</div>
            <div style="color: #555; font-size: 1.01rem; margin-top: 0.2rem;">Pending Orders</div>
        </div>
    </div>
    <!-- Real-time Stock Levels -->
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.5rem; margin-bottom: 2.5rem;">
        <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.2rem; color: #222; display: flex; align-items: center;"><i class="fas fa-cube" style="margin-right: 0.7rem;"></i> Real-time Stock Levels</h3>
        <div style="display: flex; gap: 1.2rem; flex-wrap: wrap;">
            @foreach($products as $product)
                <div style="flex:1; min-width: 180px; background: #fafbfc; border-radius: 10px; border: 1px solid #e5e7eb; padding: 1.1rem 1rem; margin-bottom: 1rem; position: relative;">
                    <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.3rem; display: flex; align-items: center;">
                        {{ $product->name }}
                        <span style="margin-left: 0.5rem; width: 10px; height: 10px; border-radius: 50%; display: inline-block; background:
                            @if($product->stock > 20) #10b981
                            @elseif($product->stock > 10) #f59e0b
                            @else #ef4444 @endif;"></span>
                    </div>
                    <div style="font-size: 0.98rem; color: #444;">Available: <b>{{ $product->stock }}</b></div>
                    <div style="font-size: 0.98rem; color: #444;">Reserved: <b>{{ $product->reserved ?? 0 }}</b></div>
                    <div style="font-size: 0.98rem; color: #444;">Total: <b>{{ $product->stock + ($product->reserved ?? 0) }}</b></div>
                    @if($product->stock < 10)
                        <div style="color: #ef4444; font-size: 0.95rem; margin-top: 0.4rem;"><i class="fas fa-exclamation-triangle"></i> Reorder needed</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <!-- Tabs for Retailer/Manufacturer Orders -->
    <div style="margin-bottom: 2.5rem;">
        <div style="display: flex; gap: 1.5rem; margin-bottom: 1.2rem;">
            <button class="tab-link active" data-tab="retailer-orders" style="padding: 0.7rem 2rem; border: none; border-radius: 8px; background: var(--primary); color: #fff; font-weight: 600; cursor: pointer;">Retailer Orders</button>
            <button class="tab-link" data-tab="manufacturer-orders" style="padding: 0.7rem 2rem; border: none; border-radius: 8px; background: #f5f5f5; color: var(--primary); font-weight: 600; cursor: pointer;">Manufacturer Orders</button>
        </div>
        <div id="retailer-orders" class="tab-content active">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1.2rem; color: #222;">Pending Retailer Orders</h3>
            <table style="width: 100%; border-collapse: collapse; background: #fff;">
                <thead>
                    <tr style="background: #f8fafc; color: #222; font-size: 1.05rem;">
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Order ID</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Retailer</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Model</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Quantity</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Value</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Status</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Ordered At</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($retailerOrders as $order)
                        <tr>
                            <td>RO-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->retailer->name ?? 'N/A' }}</td>
                            <td>{{ $order->car_model }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Shs {{ number_format($order->total_amount) }}</td>
                            <td>
                                <span style="font-weight: 700; color:
                                    @if($order->status === 'pending') #ef4444
                                    @elseif($order->status === 'confirmed') #f59e0b
                                    @elseif($order->status === 'shipped') #3b82f6
                                    @elseif($order->status === 'delivered') #10b981
                                    @else #222 @endif;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->diffForHumans() : 'N/A' }}</td>
                            <td>
                                <button style="background: #10b981; color: #fff; border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 1.1rem; margin-right: 0.3rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button style="background: #ef4444; color: #fff; border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 1.1rem; margin-right: 0.3rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                <button style="background: #f3f4f6; color: #222; border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 1.1rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No retailer orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="manufacturer-orders" class="tab-content" style="display:none;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1.2rem; color: #222;">Manufacturer Orders</h3>
            <table style="width: 100%; border-collapse: collapse; background: #fff;">
                <thead>
                    <tr style="background: #f8fafc; color: #222; font-size: 1.05rem;">
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Order ID</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Manufacturer</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Product</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Quantity</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Status</th>
                        <th style="padding: 0.8rem 0.5rem; text-align: left;">Ordered At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($manufacturerOrders as $order)
                        <tr>
                            <td>MO-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->manufacturer->name ?? 'Unknown' }}</td>
                            <td>{{ $order->product_name ?? $order->product }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->diffForHumans() : '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No manufacturer orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Product Grid -->
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 1.5rem; margin-bottom: 2.5rem;">
        <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.2rem; color: #222; display: flex; align-items: center;"><i class="fas fa-th-large" style="margin-right: 0.7rem;"></i> Model Inventory</h3>
        <div style="display: flex; gap: 1.2rem; flex-wrap: wrap;">
            @foreach($products as $product)
                <div style="flex:1; min-width: 220px; max-width: 260px; background: #fafbfc; border-radius: 10px; border: 1px solid #e5e7eb; padding: 1.1rem 1rem; margin-bottom: 1rem; position: relative; display: flex; flex-direction: column; align-items: flex-start;">
                    <div style="width: 100%; height: 90px; background: #e5e7eb; border-radius: 8px; margin-bottom: 0.7rem; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ $product->image_url ?? asset('images/car1.png') }}" alt="{{ $product->name }}" style="max-width: 80px; max-height: 70px; object-fit: contain;">
                    </div>
                    <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.2rem;">{{ $product->name }}</div>
                    <div style="font-size: 0.98rem; color: #444; margin-bottom: 0.2rem;">{{ $product->category }}</div>
                    <div style="font-size: 1.05rem; font-weight: 600; color: #2563eb; margin-bottom: 0.2rem;">Shs {{ number_format($product->price) }}</div>
                    <div style="font-size: 0.98rem; color: #444; margin-bottom: 0.2rem;">Inventory: <b>{{ $product->stock }}</b></div>
                    <div style="font-size: 0.98rem; color: #444; margin-bottom: 0.2rem;">Orders: <b>{{ $retailerOrders->where('car_model', $product->name)->count() }}</b></div>
                    <div style="font-size: 0.98rem; color: #444; margin-bottom: 0.2rem;">Status: <span style="color: {{ $product->stock < 10 ? '#ef4444' : '#10b981' }}; font-weight: 700;">{{ $product->stock < 10 ? 'Low' : 'Active' }}</span></div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            tabContents.forEach(c => c.style.display = 'none');
            this.classList.add('active');
            const tab = document.getElementById(this.dataset.tab);
            tab.classList.add('active');
            tab.style.display = '';
        });
    });
});
</script>
@endpush
@endsection