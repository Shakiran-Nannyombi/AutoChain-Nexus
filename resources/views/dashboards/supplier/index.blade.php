@extends('layouts.dashboard')

@section('title', 'Supplier Dashboard')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    @php
        $title = 'Supplier Dashboard';
    @endphp

    <div class="content-card">
        <h2 style="color: var(--text); margin-bottom: 1rem; font-weight:bold; font-size: 2rem;">
          Control Panel
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Quick Stats -->
            <div style="background: linear-gradient(135deg, #0d3a07, #26741c); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeSupplies ?? '0' }}</div>
                        <div style="opacity: 0.9;">Active Supplies</div>
                    </div>
                    <i class="fas fa-boxes" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg,  #10073a, #231c74); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $pendingOrders ?? 0 }}</div>
                        <div style="opacity: 0.9;">Pending Orders</div>
                    </div>
                    <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg,  #2f073a, #581c74); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeShipments ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Shipments</div>
                    </div>
                    <i class="fas fa-shipping-fast" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg,  #073a39, #1c6d74); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $monthlyRevenue ?? 'shs 0' }}</div>
                        <div style="opacity: 0.9;">Monthly Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('supplier.supplies.create') }}" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Add New Supply</div>
                </a>
                
                <a href="{{ route('supplier.orders') }}" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-shopping-cart" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">View Orders</div>
                </a>
                
                <a href="{{ route('supplier.shipments') }}" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-shipping-fast" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Track Shipments</div>
                </a>
                
                <a href="{{ route('supplier.inventory') }}" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-warehouse" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Check Inventory</div>
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
                        <div style="font-weight: 600;">Order #SUP-001</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Raw Materials - Steel</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">Pending</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-check-circle" style="color: var(--blue);"></i>
                    <div>
                        <div style="font-weight: 600;">Order #SUP-002</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Electronics - Chips</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">Delivered</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0;">
                    <i class="fas fa-shipping-fast" style="color: var(--maroon);"></i>
                    <div>
                        <div style="font-weight: 600;">Order #SUP-003</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Plastics - Resin</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">In Transit</div>
                </div>
            </div>
        </div>

        <!-- Shipment Status -->
        <div>
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-shipping-fast"></i> Active Shipments
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--orange);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Shipment #SHP-001</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">Raw Materials to Factory A</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">ETA: 2 days</div>
                        <div style="background: var(--orange); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">In Transit</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--blue);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Shipment #SHP-002</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">Electronics to Factory B</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">ETA: Today</div>
                        <div style="background: var(--blue); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Out for Delivery</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid #28a745;">
                    <div style="font-weight: 600; color: var(--deep-purple);">Shipment #SHP-003</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">Plastics to Factory C</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Delivered</div>
                        <div style="background: #28a745; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 