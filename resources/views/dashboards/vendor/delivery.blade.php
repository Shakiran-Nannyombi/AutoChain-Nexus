@extends('layouts.dashboard')

@section('title', 'Vendor Car Delivery')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-truck"></i> Car Delivery
        </h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Delivery Form -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.4rem;">
                    <i class="fas fa-plus"></i> New Delivery Request
                </h3>
                
                <form style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- Retailer Selection -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Select Retailer</label>
                        <select style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: white;">
                            <option>Choose a retailer...</option>
                            <option>ABC Auto Dealership</option>
                            <option>City Motors</option>
                            <option>Premium Cars Inc.</option>
                            <option>Metro Auto Group</option>
                            <option>Elite Motors</option>
                        </select>
                    </div>
                    
                    <!-- Car Selection -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Select Car Model</label>
                        <select style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: white;">
                            <option>Choose a car model...</option>
                            <option>Toyota Camry - White (45 available)</option>
                            <option>Honda Civic - Black (8 available)</option>
                            <option>Ford Mustang - Red (32 available)</option>
                            <option>BMW 3 Series - Blue (0 available)</option>
                            <option>Mercedes C-Class - Silver (15 available)</option>
                        </select>
                    </div>
                    
                    <!-- Quantity -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Quantity</label>
                        <input type="number" min="1" max="50" value="1" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                    
                    <!-- Delivery Date -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Preferred Delivery Date</label>
                        <input type="date" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                    
                    <!-- Delivery Address -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Delivery Address</label>
                        <textarea rows="3" placeholder="Enter delivery address..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                    </div>
                    
                    <!-- Special Instructions -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Special Instructions</label>
                        <textarea rows="2" placeholder="Any special delivery instructions..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" style="background: var(--deep-purple); color: white; padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem;">
                        <i class="fas fa-truck"></i> Submit Delivery Request
                    </button>
                </form>
            </div>
            
            <!-- Pending Requests -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.4rem;">
                    <i class="fas fa-clock"></i> Pending Delivery Requests
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <!-- Request 1 -->
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h4 style="color: var(--deep-purple); font-size: 1.1rem; margin: 0;">ABC Auto Dealership</h4>
                            <span style="background: #ffc107; color: #212529; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Pending</span>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">Toyota Camry - White (2 units)</div>
                        <div style="color: #6c757d; margin-bottom: 1rem;">Delivery Date: 2024-01-20</div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button style="background: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                    
                    <!-- Request 2 -->
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h4 style="color: var(--deep-purple); font-size: 1.1rem; margin: 0;">City Motors</h4>
                            <span style="background: #ffc107; color: #212529; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Pending</span>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">Ford Mustang - Red (1 unit)</div>
                        <div style="color: #6c757d; margin-bottom: 1rem;">Delivery Date: 2024-01-22</div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button style="background: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                    
                    <!-- Request 3 -->
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h4 style="color: var(--deep-purple); font-size: 1.1rem; margin: 0;">Premium Cars Inc.</h4>
                            <span style="background: #007bff; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Approved</span>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">Mercedes C-Class - Silver (3 units)</div>
                        <div style="color: #6c757d; margin-bottom: 1rem;">Delivery Date: 2024-01-25</div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: var(--orange); color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-truck"></i> Schedule Delivery
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div style="margin-top: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-warehouse"></i> View Warehouse Stock
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-route"></i> View Delivery Routes
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-file-alt"></i> Generate Delivery Report
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-cog"></i> Delivery Settings
                </button>
            </div>
        </div>
    </div>
@endsection 