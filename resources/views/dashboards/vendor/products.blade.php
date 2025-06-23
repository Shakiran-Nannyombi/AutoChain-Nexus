@extends('layouts.dashboard')

@section('title', 'Vendor Products')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-box"></i> Product Management
        </h2>
        
        <!-- Product Actions -->
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: var(--deep-purple); font-size: 1.3rem;">
                    <i class="fas fa-list"></i> All Products
                </h3>
                <button style="background: var(--deep-purple); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-plus"></i> Add New Product
                </button>
            </div>
            
            <!-- Search and Filter -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    <input type="text" placeholder="Search products..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>
                <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white;">
                    <option>All Categories</option>
                    <option>Electronics</option>
                    <option>Clothing</option>
                    <option>Home & Garden</option>
                </select>
                <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white;">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>Out of Stock</option>
                </select>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            <!-- Product Card 1 -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-purple); font-size: 1.2rem; margin: 0;">Product A</h4>
                    <span style="background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Active</span>
                </div>
                <div style="color: #6c757d; margin-bottom: 1rem;">Electronics Category</div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple);">$29.99</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Stock: 150 units</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: #28a745;">150 sold</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">This month</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="flex: 1; background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button style="flex: 1; background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-eye"></i> View
                    </button>
                </div>
            </div>
            
            <!-- Product Card 2 -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-purple); font-size: 1.2rem; margin: 0;">Product B</h4>
                    <span style="background: #dc3545; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Out of Stock</span>
                </div>
                <div style="color: #6c757d; margin-bottom: 1rem;">Clothing Category</div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple);">$49.99</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Stock: 0 units</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: #28a745;">89 sold</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">This month</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="flex: 1; background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button style="flex: 1; background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-eye"></i> View
                    </button>
                </div>
            </div>
            
            <!-- Product Card 3 -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-purple); font-size: 1.2rem; margin: 0;">Product C</h4>
                    <span style="background: #ffc107; color: #212529; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Low Stock</span>
                </div>
                <div style="color: #6c757d; margin-bottom: 1rem;">Home & Garden Category</div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple);">$19.99</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Stock: 5 units</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: #28a745;">234 sold</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">This month</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="flex: 1; background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button style="flex: 1; background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-eye"></i> View
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div style="display: flex; justify-content: center; margin-top: 2rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button style="padding: 0.5rem 1rem; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer;">Previous</button>
                <button style="padding: 0.5rem 1rem; border: 1px solid var(--deep-purple); background: var(--deep-purple); color: white; border-radius: 6px; cursor: pointer;">1</button>
                <button style="padding: 0.5rem 1rem; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer;">2</button>
                <button style="padding: 0.5rem 1rem; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer;">3</button>
                <button style="padding: 0.5rem 1rem; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer;">Next</button>
            </div>
        </div>
    </div>
@endsection 