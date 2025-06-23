@extends('layouts.dashboard')

@section('title', 'Vendor Notifications')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-bell"></i> Notifications
        </h2>
        
        <!-- Notification Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">5</div>
                <div style="font-size: 0.9rem;">Unread Notifications</div>
            </div>
            <div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">12</div>
                <div style="font-size: 0.9rem;">New Requests</div>
            </div>
            <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">8</div>
                <div style="font-size: 0.9rem;">Chat Messages</div>
            </div>
            <div style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; padding: 1rem; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">3</div>
                <div style="font-size: 0.9rem;">System Alerts</div>
            </div>
        </div>
        
        <!-- Notification Filters -->
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <button style="background: var(--deep-purple); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                All Notifications
            </button>
            <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                New Requests
            </button>
            <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                Chat Messages
            </button>
            <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                System Alerts
            </button>
            <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                Mark All Read
            </button>
        </div>
        
        <!-- Notifications List -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <!-- Unread Notification -->
            <div style="padding: 1rem; border-bottom: 1px solid #f0f0f0; background: #f8f9fa;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="width: 40px; height: 40px; background: #dc3545; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600; color: var(--deep-purple);">New Delivery Request</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">2 minutes ago</div>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">
                            ABC Auto Dealership has requested delivery of 3 Toyota Camry vehicles. Please review and approve the request.
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button style="background: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Unread Notification -->
            <div style="padding: 1rem; border-bottom: 1px solid #f0f0f0; background: #f8f9fa;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="width: 40px; height: 40px; background: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600; color: var(--deep-purple);">New Chat Message</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">15 minutes ago</div>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">
                            Toyota Manufacturing sent you a message: "New batch of Camry models available for immediate delivery."
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: var(--deep-purple); color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-reply"></i> Reply
                            </button>
                            <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-eye"></i> View Chat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Read Notification -->
            <div style="padding: 1rem; border-bottom: 1px solid #f0f0f0;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="width: 40px; height: 40px; background: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600; color: var(--deep-purple);">Delivery Completed</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">1 hour ago</div>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">
                            Delivery #DEL-001 to City Motors has been completed successfully. All 2 Toyota Camry vehicles delivered.
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-file-alt"></i> View Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Read Notification -->
            <div style="padding: 1rem; border-bottom: 1px solid #f0f0f0;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="width: 40px; height: 40px; background: #ffc107; color: #212529; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600; color: var(--deep-purple);">Low Stock Alert</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">2 hours ago</div>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">
                            Honda Civic Black model is running low on stock. Only 8 units remaining. Consider requesting more from manufacturer.
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: var(--orange); color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-plus"></i> Request Stock
                            </button>
                            <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-eye"></i> View Inventory
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Read Notification -->
            <div style="padding: 1rem; border-bottom: 1px solid #f0f0f0;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="width: 40px; height: 40px; background: #6c757d; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600; color: var(--deep-purple);">System Update</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">1 day ago</div>
                        </div>
                        <div style="color: #6c757d; margin-bottom: 0.5rem;">
                            New features have been added to the delivery tracking system. You can now track deliveries in real-time.
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-info"></i> Learn More
                            </button>
                        </div>
                    </div>
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
        
        <!-- Quick Actions -->
        <div style="margin-top: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-cog"></i> Notification Settings
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-bell-slash"></i> Mute Notifications
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-trash"></i> Clear All
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-download"></i> Export Notifications
                </button>
            </div>
        </div>
    </div>
@endsection 