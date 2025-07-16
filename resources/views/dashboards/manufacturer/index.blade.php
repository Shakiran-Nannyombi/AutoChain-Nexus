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
        <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem; font-weight: bold; display: flex; align-items: center;">
            <i class="fas fa-industry"></i> Control Panel
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
    
    <!-- Vendor Segmentation Analytics Section -->
    <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
        <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
            <i class="fas fa-industry"></i> Vendor Segment Distribution
        </h3>
        <div>
            @if(isset($vendorSegmentCounts) && count($vendorSegmentCounts) > 0)
                <ul style="list-style: none; padding: 0;">
                    @foreach($vendorSegmentCounts as $seg)
                        <li style="margin-bottom: 0.5rem;">
                            <strong>Segment {{ $seg->segment ?? 'Unsegmented' }}:</strong> {{ $seg->count }} vendors
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No vendor segmentation data available.</p>
            @endif
        </div>
        @if(isset($vendorSegmentSummaries) && count($vendorSegmentSummaries) > 0)
        <div style="margin-top: 2rem;">
            <h4 style="color: var(--primary);">Vendor Segment Summary Table</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="padding: 0.5rem; text-align: left;">Segment</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Total Value</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Orders</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Recency (days)</th>
                        <th style="padding: 0.5rem; text-align: left;"># Vendors</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendorSegmentSummaries as $summary)
                    <tr>
                        <td style="padding: 0.5rem;">{{ $summary->segment ?? 'Unsegmented' }}</td>
                        <td style="padding: 0.5rem;">${{ number_format($summary->avg_total_value, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ number_format($summary->avg_orders, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->avg_recency !== null ? number_format($summary->avg_recency, 1) : 'N/A' }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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