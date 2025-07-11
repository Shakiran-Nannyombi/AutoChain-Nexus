@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manufacturer.css') }}">
@endpush

@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection
@section('content')
    @php
        $title = 'Manufacturer Control Panel';
        // Debug: Show segmentation data (remove after confirming it works)
        // dd($customerSegmentCounts, $segmentSummaries, $segmentNames, $segmentRecommendations);
    @endphp

    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-industry"></i> Manufacturing Dashboard
        </h2>
        
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
            @if(isset($segmentRecommendations) && count($segmentRecommendations) > 0)
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
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Quick Stats -->
            <div style="background: linear-gradient(135deg, var(--deep-purple), var(--orange)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeProducts ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Products</div>
                    </div>
                    <i class="fas fa-box" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--maroon), var(--orange)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $pendingOrders ?? 0 }}</div>
                        <div style="opacity: 0.9;">Pending Orders</div>
                    </div>
                    <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--blue), var(--light-cyan)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $productionLines ?? 0 }}</div>
                        <div style="opacity: 0.9;">Production Lines</div>
                    </div>
                    <i class="fas fa-industry" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $monthlyRevenue ?? '$0' }}</div>
                        <div style="opacity: 0.9;">Monthly Revenue</div>
                    </div>
                    <i class="fas fa-dollar-sign" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="/manufacturer/products/create" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Add New Product</div>
                </a>
                
                <a href="/manufacturer/orders" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-shopping-cart" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">View Orders</div>
                </a>
                
                <a href="/manufacturer/inventory" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-warehouse" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Check Inventory</div>
                </a>
                
                <a href="/manufacturer/production" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-industry" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Production Status</div>
                </a>
            </div>
        </div>

        <!-- Recent Orders -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-shopping-cart"></i> Recent Orders
            </h3>
            <div style="background: var(--gray); padding: 1rem; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-clock" style="color: var(--orange);"></i>
                    <div>
                        <div style="font-weight: 600;">Order #12345</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">50 units - Widget A</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">Pending</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-check-circle" style="color: var(--blue);"></i>
                    <div>
                        <div style="font-weight: 600;">Order #12344</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">100 units - Widget B</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">Completed</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0;">
                    <i class="fas fa-spinner" style="color: var(--maroon);"></i>
                    <div>
                        <div style="font-weight: 600;">Order #12343</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">75 units - Widget C</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">In Production</div>
                </div>
            </div>
        </div>

        <!-- Production Status -->
        <div>
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-industry"></i> Production Status
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--orange);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Production Line 1</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">Widget A</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Progress: 75%</div>
                        <div style="background: var(--orange); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Active</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--blue);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Production Line 2</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">Widget B</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Progress: 100%</div>
                        <div style="background: var(--blue); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Complete</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--gray);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Production Line 3</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">Idle</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Status: Available</div>
                        <div style="background: var(--gray); color: var(--deep-purple); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Idle</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 