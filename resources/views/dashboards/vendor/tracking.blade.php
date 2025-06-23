@extends('layouts.dashboard')

@section('title', 'Vendor Delivery Tracking')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-route"></i> Delivery Tracking
        </h2>
        
        <!-- Delivery Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">156</div>
                <div style="font-size: 0.9rem;">Completed Deliveries</div>
            </div>
            <div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">12</div>
                <div style="font-size: 0.9rem;">In Transit</div>
            </div>
            <div style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">8</div>
                <div style="font-size: 0.9rem;">Scheduled</div>
            </div>
            <div style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">3</div>
                <div style="font-size: 0.9rem;">Delayed</div>
            </div>
        </div>
        
        <!-- Search and Filter -->
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                <input type="text" placeholder="Search deliveries..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
            </div>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Status</option>
                <option>Completed</option>
                <option>In Transit</option>
                <option>Scheduled</option>
                <option>Delayed</option>
                <option>Cancelled</option>
            </select>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Retailers</option>
                <option>ABC Auto Dealership</option>
                <option>City Motors</option>
                <option>Premium Cars Inc.</option>
                <option>Metro Auto Group</option>
            </select>
            <select style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; background: white; min-width: 150px;">
                <option>All Time</option>
                <option>Today</option>
                <option>This Week</option>
                <option>This Month</option>
                <option>Last 30 Days</option>
            </select>
        </div>
        
        <!-- Deliveries Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: var(--gray);">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Delivery ID</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Retailer</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Cars Delivered</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Delivery Date</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Driver</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--deep-purple);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">#DEL-001</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">ABC Auto Dealership</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">123 Main St, City</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>Toyota Camry - White (2x)</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Total: $50,000</div>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">2024-01-15</td>
                        <td style="padding: 1rem;">
                            <span style="background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Completed</span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">John Driver</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Truck #V001</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button style="background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">#DEL-002</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">City Motors</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">456 Oak Ave, Town</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>Ford Mustang - Red (1x)</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Total: $35,000</div>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">2024-01-16</td>
                        <td style="padding: 1rem;">
                            <span style="background: #007bff; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">In Transit</span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">Sarah Smith</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Truck #V002</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button style="background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">#DEL-003</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">Premium Cars Inc.</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">789 Luxury Blvd, City</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>Mercedes C-Class - Silver (3x)</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Total: $135,000</div>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">2024-01-18</td>
                        <td style="padding: 1rem;">
                            <span style="background: #ffc107; color: #212529; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Scheduled</span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">Mike Johnson</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Truck #V003</div>
                        </td>
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
                        <td style="padding: 1rem; font-weight: 600; color: var(--deep-purple);">#DEL-004</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">Metro Auto Group</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">321 Metro St, Downtown</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>Honda Civic - Black (5x)</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Total: $112,500</div>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">2024-01-17</td>
                        <td style="padding: 1rem;">
                            <span style="background: #dc3545; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Delayed</span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">Lisa Brown</div>
                            <div style="font-size: 0.9rem; color: #6c757d;">Truck #V004</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button style="background: var(--orange); color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-exclamation-triangle"></i>
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
        
        <!-- Quick Actions -->
        <div style="margin-top: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-file-alt"></i> Export Report
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-chart-line"></i> Delivery Analytics
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-route"></i> Route Optimization
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-bell"></i> Set Alerts
                </button>
            </div>
        </div>
    </div>
@endsection 