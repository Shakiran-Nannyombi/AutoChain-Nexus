@extends('layouts.dashboard')

@section('title', 'Vendor Chat')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-comments"></i> Chat
        </h2>
        
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 1rem; height: 600px;">
            <!-- Chat Contacts -->
            <div style="background: white; border-radius: 12px; padding: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.2rem;">
                    <i class="fas fa-users"></i> Contacts
                </h3>
                
                <!-- Search Contacts -->
                <div style="position: relative; margin-bottom: 1rem;">
                    <i class="fas fa-search" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    <input type="text" placeholder="Search contacts..." style="width: 100%; padding: 0.5rem 0.5rem 0.5rem 2rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                </div>
                
                <!-- Contact List -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 500px; overflow-y: auto;">
                    <!-- Active Contact -->
                    <div style="background: var(--light-cyan); border-radius: 8px; padding: 0.75rem; cursor: pointer;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: var(--deep-purple); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                TM
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--deep-purple);">Toyota Manufacturing</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Last message: 2 min ago</div>
                            </div>
                            <div style="background: #28a745; color: white; width: 8px; height: 8px; border-radius: 50%;"></div>
                        </div>
                    </div>
                    
                    <!-- Other Contacts -->
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 0.75rem; cursor: pointer;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: var(--orange); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                HM
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--deep-purple);">Honda Manufacturing</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Last message: 1 hour ago</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 0.75rem; cursor: pointer;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: var(--blue); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                ABC
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--deep-purple);">ABC Auto Dealership</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Last message: 3 hours ago</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 0.75rem; cursor: pointer;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: var(--maroon); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                CM
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--deep-purple);">City Motors</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Last message: 1 day ago</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 0.75rem; cursor: pointer;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                PC
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--deep-purple);">Premium Cars Inc.</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Last message: 2 days ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chat Messages -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column;">
                <!-- Chat Header -->
                <div style="padding: 1rem; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 40px; height: 40px; background: var(--deep-purple); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        TM
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--deep-purple);">Toyota Manufacturing</div>
                        <div style="font-size: 0.8rem; color: #6c757d;">Online</div>
                    </div>
                    <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                        <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-video"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Messages Area -->
                <div style="flex: 1; padding: 1rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem;">
                    <!-- Received Message -->
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; background: var(--deep-purple); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem;">
                            TM
                        </div>
                        <div style="background: var(--gray); padding: 0.75rem; border-radius: 12px; max-width: 70%;">
                            <div style="color: var(--deep-purple); font-weight: 600; margin-bottom: 0.25rem;">Toyota Manufacturing</div>
                            <div>Hi! We have a new batch of Camry models available. Would you like to place an order?</div>
                            <div style="font-size: 0.8rem; color: #6c757d; margin-top: 0.5rem;">10:30 AM</div>
                        </div>
                    </div>
                    
                    <!-- Sent Message -->
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start; justify-content: flex-end;">
                        <div style="background: var(--deep-purple); color: white; padding: 0.75rem; border-radius: 12px; max-width: 70%;">
                            <div>Yes, I'm interested! How many units are available and what's the delivery timeline?</div>
                            <div style="font-size: 0.8rem; color: rgba(255,255,255,0.8); margin-top: 0.5rem;">10:32 AM</div>
                        </div>
                        <div style="width: 32px; height: 32px; background: var(--orange); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem;">
                            V
                        </div>
                    </div>
                    
                    <!-- Received Message -->
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; background: var(--deep-purple); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem;">
                            TM
                        </div>
                        <div style="background: var(--gray); padding: 0.75rem; border-radius: 12px; max-width: 70%;">
                            <div style="color: var(--deep-purple); font-weight: 600; margin-bottom: 0.25rem;">Toyota Manufacturing</div>
                            <div>We have 50 units ready for immediate delivery. Standard delivery time is 3-5 business days. Would you like me to send you the detailed specifications?</div>
                            <div style="font-size: 0.8rem; color: #6c757d; margin-top: 0.5rem;">10:35 AM</div>
                        </div>
                    </div>
                    
                    <!-- Sent Message -->
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start; justify-content: flex-end;">
                        <div style="background: var(--deep-purple); color: white; padding: 0.75rem; border-radius: 12px; max-width: 70%;">
                            <div>Perfect! Please send the specifications and pricing details. I'll review and get back to you by end of day.</div>
                            <div style="font-size: 0.8rem; color: rgba(255,255,255,0.8); margin-top: 0.5rem;">10:37 AM</div>
                        </div>
                        <div style="width: 32px; height: 32px; background: var(--orange); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem;">
                            V
                        </div>
                    </div>
                </div>
                
                <!-- Message Input -->
                <div style="padding: 1rem; border-top: 1px solid #e0e0e0;">
                    <div style="display: flex; gap: 0.75rem; align-items: end;">
                        <div style="flex: 1; position: relative;">
                            <textarea placeholder="Type your message..." rows="2" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: none;"></textarea>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button style="background: var(--deep-purple); color: white; padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-paper-plane"></i>
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
                    <i class="fas fa-plus"></i> New Group Chat
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-file-alt"></i> Chat History
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-cog"></i> Chat Settings
                </button>
                <button style="background: var(--light-cyan); color: var(--deep-purple); padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center;">
                    <i class="fas fa-bell"></i> Notifications
                </button>
            </div>
        </div>
    </div>
@endsection 