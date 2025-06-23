@extends('layouts.dashboard')

@section('title', 'Vendor Orders')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-shopping-cart"></i> Order Management
        </h2>
        
        <!-- Order Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">25</div>
                <div style="font-size: 0.9rem;">Pending Orders</div>
            </div>
            <div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">156</div>
                <div style="font-size: 0.9rem;">Completed Orders</div>
            </div>
            <div style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">12</div>
                <div style="font-size: 0.9rem;">Processing</div>
            </div>
            <div style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">3</div>
                <div style="font-size: 0.9rem;">Cancelled</div>
            </div>
        </div>
        
        <!-- Order Filters -->
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                <input type="text" placeholder="Search orders..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
            </div>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Status</option>
                <option>Pending</option>
                <option>Processing</option>
                <option>Shipped</option>
                <option>Delivered</option>
                <option>Cancelled</option>
            </select>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Time</option>
                <option>Today</option>
                <option>This Week</option>
                <option>This Month</option>
                <option>Last 30 Days</option>
            </select>
        </div>
        
        <!-- Orders Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: var(--gray);">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Order ID</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Customer</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Products</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Total</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Date</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">#VEN-001</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">John Smith</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">john@example.com</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>Product A (2x)</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Product B (1x)</div>
                        </td>
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">$109.97</td>
                        <td style="padding: 1rem;">
                            <span style="background: #ffc107; color: #212529; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Pending</span>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">2024-01-15</td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button style="background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">#VEN-002</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">Jane Doe</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">jane@example.com</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>Product C (3x)</div>
                        </td>
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">$59.97</td>
                        <td style="padding: 1rem;">
                            <span style="background: #007bff; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Processing</span>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">2024-01-14</td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button style="background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">#VEN-003</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">Bob Wilson</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">bob@example.com</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>Product A (1x)</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Product C (2x)</div>
                        </td>
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">$69.97</td>
                        <td style="padding: 1rem;">
                            <span style="background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Delivered</span>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">2024-01-13</td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button style="background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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