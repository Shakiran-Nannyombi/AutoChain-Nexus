@extends('layouts.dashboard')

@section('title', 'Vendor Warehouse Access')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-warehouse"></i> Warehouse Access
        </h2>
        
        <!-- Warehouse Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">1,250</div>
                <div style="font-size: 0.9rem;">Total Cars Available</div>
            </div>
            <div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">45</div>
                <div style="font-size: 0.9rem;">Car Models</div>
            </div>
            <div style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">12</div>
                <div style="font-size: 0.9rem;">Color Options</div>
            </div>
            <div style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">8</div>
                <div style="font-size: 0.9rem;">Low Stock Items</div>
            </div>
        </div>
        
        <!-- Search and Filter -->
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                <input type="text" placeholder="Search cars by model..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
            </div>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Models</option>
                <option>Toyota Camry</option>
                <option>Honda Civic</option>
                <option>Ford Mustang</option>
                <option>BMW 3 Series</option>
                <option>Mercedes C-Class</option>
            </select>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Colors</option>
                <option>White</option>
                <option>Black</option>
                <option>Red</option>
                <option>Blue</option>
                <option>Silver</option>
            </select>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Stock Levels</option>
                <option>In Stock</option>
                <option>Low Stock</option>
                <option>Out of Stock</option>
            </select>
        </div>
        
        <!-- Cars Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            <!-- Car Card 1 -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-purple); font-size: 1.3rem; margin: 0;">Toyota Camry</h4>
                    <span style="background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">In Stock</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Color: White</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Quantity: 45 units</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Year: 2024</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Price: $25,000</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="flex: 1; background: var(--light-cyan); color: var(--deep-purple); padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                    <button style="flex: 1; background: var(--orange); color: white; padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-truck"></i> Request Delivery
                    </button>
                </div>
            </div>
            
            <!-- Car Card 2 -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-purple); font-size: 1.3rem; margin: 0;">Honda Civic</h4>
                    <span style="background: #ffc107; color: #212529; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Low Stock</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Color: Black</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Quantity: 8 units</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Year: 2024</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Price: $22,500</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="flex: 1; background: var(--light-cyan); color: var(--deep-purple); padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                    <button style="flex: 1; background: var(--orange); color: white; padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-truck"></i> Request Delivery
                    </button>
                </div>
            </div>
            
            <!-- Car Card 3 -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-purple); font-size: 1.3rem; margin: 0;">Ford Mustang</h4>
                    <span style="background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">In Stock</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Color: Red</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Quantity: 32 units</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Year: 2024</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Price: $35,000</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="flex: 1; background: var(--light-cyan); color: var(--deep-purple); padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                    <button style="flex: 1; background: var(--orange); color: white; padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-truck"></i> Request Delivery
                    </button>
                </div>
            </div>
            
            <!-- Car Card 4 -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-purple); font-size: 1.3rem; margin: 0;">BMW 3 Series</h4>
                    <span style="background: #dc3545; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Out of Stock</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Color: Blue</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Quantity: 0 units</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple); margin-bottom: 0.5rem;">Year: 2024</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Price: $45,000</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="flex: 1; background: var(--light-cyan); color: var(--deep-purple); padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                    <button style="flex: 1; background: var(--gray); color: var(--deep-purple); padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;" disabled>
                        <i class="fas fa-clock"></i> Coming Soon
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