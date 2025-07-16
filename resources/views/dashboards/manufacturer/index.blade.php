@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manufacturer.css') }}">
@endpush

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-industry"></i> Manufacturing Dashboard
        </h2>
        <!-- Main Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, var(--primary), #0d3a07); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeOrders ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Orders</div>
                    </div>
                    <i class="fas fa-shopping-cart" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, var(--accent), #b35400); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $monthlyRevenue ?? '$0' }}</div>
                        <div style="opacity: 0.9;">Monthly Revenue</div>
                    </div>
                    <i class="fas fa-dollar-sign" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, var(--primary-light), #388e3c); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $inventoryCount ?? 0 }}</div>
                        <div style="opacity: 0.9;">Inventory</div>
                    </div>
                    <i class="fas fa-cubes" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, var(--secondary), #b35400); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeVendors ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Vendors</div>
                    </div>
                    <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
        <!-- Tabs Section -->
        <div style="margin-bottom: 2rem;">
            <div id="customTabs" style="display: flex; gap: 1rem; border-bottom: 2px solid #e5e7eb; margin-bottom: 1.5rem;">
                <button class="custom-tab-btn active" data-tab="products" style="background: none; border: none; color: var(--primary); font-weight: 600; font-size: 1.1rem; padding: 0.5rem 1.2rem; border-bottom: 3px solid var(--primary); cursor:pointer;">Products</button>
                <button class="custom-tab-btn" data-tab="orders" style="background: none; border: none; color: var(--primary); font-weight: 600; font-size: 1.1rem; padding: 0.5rem 1.2rem; cursor:pointer;">Orders</button>
                <button class="custom-tab-btn" data-tab="inventory" style="background: none; border: none; color: var(--primary); font-weight: 600; font-size: 1.1rem; padding: 0.5rem 1.2rem; cursor:pointer;">Inventory</button>
                <button class="custom-tab-btn" data-tab="vendors" style="background: none; border: none; color: var(--primary); font-weight: 600; font-size: 1.1rem; padding: 0.5rem 1.2rem; cursor:pointer;">Vendors</button>
            </div>
            <div id="customTabContent">
                <div class="custom-tab-content" id="tab-products" style="display:block;">
                    <div style="max-width: 500px; margin: 2rem auto; background: #f8fafc; padding: 2rem; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); text-align:center;">
                        <i class="fas fa-box" style="font-size:2.5rem; color:var(--primary); margin-bottom:1rem;"></i>
                        <h3 style="color: var(--primary); font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem;">Product Management</h3>
                        <p style="color: #444; margin-bottom: 1.5rem;">Easily add, edit, and view all your products in one place. Keep your catalog up to date and organized.</p>
                        <a href="/manufacturer/products" style="display:inline-block; background: var(--primary); color: #fff; font-weight:600; padding:0.7rem 1.5rem; border:none; border-radius:5px; text-decoration:none; margin-right: 0.5rem;">View Products</a>
                        <a href="/manufacturer/products/create" style="display:inline-block; background: var(--accent); color: #fff; font-weight:600; padding:0.7rem 1.5rem; border:none; border-radius:5px; text-decoration:none;">Add Product</a>
                    </div>
                </div>
                <div class="custom-tab-content" id="tab-orders" style="display:none;">
                    <div style="max-width: 500px; margin: 2rem auto; background: #f8fafc; padding: 2rem; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); text-align:center;">
                        <i class="fas fa-shopping-cart" style="font-size:2.5rem; color:var(--primary); margin-bottom:1rem;"></i>
                        <h3 style="color: var(--primary); font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem;">Order Management</h3>
                        <p style="color: #444; margin-bottom: 1.5rem;">Track, manage, and fulfill orders efficiently. Stay on top of your sales and order status.</p>
                        <a href="/manufacturer/orders" style="display:inline-block; background: var(--primary); color: #fff; font-weight:600; padding:0.7rem 1.5rem; border:none; border-radius:5px; text-decoration:none;">View Orders</a>
                    </div>
                </div>
                <div class="custom-tab-content" id="tab-inventory" style="display:none;">
                    <div style="max-width: 500px; margin: 2rem auto; background: #f8fafc; padding: 2rem; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); text-align:center;">
                        <i class="fas fa-cubes" style="font-size:2.5rem; color:var(--primary); margin-bottom:1rem;"></i>
                        <h3 style="color: var(--primary); font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem;">Inventory Overview</h3>
                        <p style="color: #444; margin-bottom: 1.5rem;">Monitor your stock levels, raw materials, and finished goods. Ensure you never run out of critical inventory.</p>
                        <a href="/manufacturer/inventory-status" style="display:inline-block; background: var(--primary); color: #fff; font-weight:600; padding:0.7rem 1.5rem; border:none; border-radius:5px; text-decoration:none;">View Inventory</a>
                    </div>
                </div>
                <div class="custom-tab-content" id="tab-vendors" style="display:none;">
                    <div style="max-width: 500px; margin: 2rem auto; background: #f8fafc; padding: 2rem; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); text-align:center;">
                        <i class="fas fa-users" style="font-size:2.5rem; color:var(--primary); margin-bottom:1rem;"></i>
                        <h3 style="color: var(--primary); font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem;">Vendors</h3>
                        <p style="color: #444; margin-bottom: 1.5rem;">View and manage your active vendors. Build strong supplier relationships for a smooth supply chain.</p>
                        <a href="/manufacturer/vendors" style="display:inline-block; background: var(--primary); color: #fff; font-weight:600; padding:0.7rem 1.5rem; border:none; border-radius:5px; text-decoration:none;">View Vendors</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <a href="{{ route('manufacturer.analystApplications') }}" class="btn btn-primary">Analyst Applications</a>
    </div>
     <!-- Customer Segmentation Analytics Section -->
     <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
        <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
            <i class="fas fa-users"></i> Customer Segment Distribution
        </h3>
        <div>
            @if(isset($customerSegmentCounts) && count($customerSegmentCounts) > 0)
                <ul style="list-style: none; padding: 0;">
                    @foreach($customerSegmentCounts as $seg)
                        <li style="margin-bottom: 0.5rem;">
                            <strong>{{ $segmentNames[$seg->segment] ?? 'Unsegmented' }}:</strong> {{ $seg->count }} customers
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No segmentation data available.</p>
            @endif
        </div>
        @if(isset($segmentSummaries) && count($segmentSummaries) > 0)
        <div style="margin-top: 2rem;">
            <h4 style="color: var(--deep-purple);">Segment Summary Table</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="padding: 0.5rem; text-align: left;">Segment</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Total Spent</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Purchases</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Recency (days)</th>
                        <th style="padding: 0.5rem; text-align: left;"># Customers</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($segmentSummaries as $summary)
                    <tr>
                        <td style="padding: 0.5rem;">{{ $segmentNames[$summary->segment] ?? 'Unsegmented' }}</td>
                        <td style="padding: 0.5rem;">${{ number_format($summary->avg_total_spent, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ number_format($summary->avg_purchases, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->avg_recency !== null ? number_format($summary->avg_recency, 1) : 'N/A' }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @if(isset($segmentRecommendations) && count($segmentRecommendations) > 0 && isset($segmentNames))
        <div style="margin-top: 2rem;">
            <h4 style="color: var(--deep-purple);">Top Recommended Products by Segment</h4>
            @foreach($segmentNames as $segId => $segName)
                <div style="margin-bottom: 1.5rem;">
                    <strong>{{ $segName }}</strong>
                    @if(isset($segmentRecommendations[$segId]) && $segmentRecommendations[$segId]->count())
                        <ul style="list-style: none; padding: 0;">
                            @foreach($segmentRecommendations[$segId] as $product)
                                <li style="margin-bottom: 0.5rem;">
                                    <strong>{{ $product->name }}</strong>
                                    @if(isset($product->description))<br><span style="color: #555;">{{ $product->description }}</span>@endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span style="color: #888;">No recommendations available.</span>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.custom-tab-btn');
            const tabContents = document.querySelectorAll('.custom-tab-content');
            tabButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    tabButtons.forEach(function(b) { b.classList.remove('active'); b.style.borderBottom = 'none'; });
                    btn.classList.add('active');
                    btn.style.borderBottom = '3px solid var(--primary)';
                    tabContents.forEach(function(content) { content.style.display = 'none'; });
                    const tabId = btn.getAttribute('data-tab');
                    document.getElementById('tab-' + tabId).style.display = 'block';
                });
            });
        });
    </script>
@endsection 