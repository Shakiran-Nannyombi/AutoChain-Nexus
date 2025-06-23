@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    @php
        $title = 'Vendor Dashboard';
    @endphp

    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-store"></i> Vendor Dashboard
        </h2>
        
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
                        <div style="font-size: 2rem; font-weight: bold;">{{ $totalCustomers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Total Customers</div>
                    </div>
                    <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.7;"></i>
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
                <a href="/vendor/products/create" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Add New Product</div>
                </a>
                
                <a href="/vendor/orders" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-shopping-cart" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">View Orders</div>
                </a>
                
                <a href="/vendor/customers" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-users" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Manage Customers</div>
                </a>
                
                <a href="/vendor/sales" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-chart-line" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Sales Analytics</div>
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
                        <div style="font-weight: 600;">Order #VEN-001</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Customer: John Smith - 5 items</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">Pending</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-check-circle" style="color: var(--blue);"></i>
                    <div>
                        <div style="font-weight: 600;">Order #VEN-002</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Customer: Jane Doe - 3 items</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">Completed</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0;">
                    <i class="fas fa-shipping-fast" style="color: var(--maroon);"></i>
                    <div>
                        <div style="font-weight: 600;">Order #VEN-003</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Customer: Bob Wilson - 8 items</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">Shipped</div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div>
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-star"></i> Top Selling Products
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--orange);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Product A</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">150 units sold</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Revenue: $1,500</div>
                        <div style="background: var(--orange); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Best Seller</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--blue);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Product B</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">120 units sold</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Revenue: $1,200</div>
                        <div style="background: var(--blue); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Popular</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid #28a745;">
                    <div style="font-weight: 600; color: var(--deep-purple);">Product C</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">95 units sold</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Revenue: $950</div>
                        <div style="background: #28a745; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Growing</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 