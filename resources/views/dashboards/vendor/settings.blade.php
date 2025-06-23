@extends('layouts.dashboard')

@section('title', 'Vendor Settings')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-cog"></i> Vendor Settings
        </h2>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            <!-- Settings Navigation -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content;">
                <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.2rem;">
                    <i class="fas fa-list"></i> Settings Menu
                </h3>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <button style="background: var(--deep-purple); color: white; padding: 0.75rem 1rem; border: none; border-radius: 8px; text-align: left; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-user"></i> Profile Settings
                    </button>
                    <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1rem; border: none; border-radius: 8px; text-align: left; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-store"></i> Store Settings
                    </button>
                    <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1rem; border: none; border-radius: 8px; text-align: left; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-bell"></i> Notifications
                    </button>
                    <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1rem; border: none; border-radius: 8px; text-align: left; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-shield-alt"></i> Security
                    </button>
                    <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1rem; border: none; border-radius: 8px; text-align: left; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-credit-card"></i> Payment Methods
                    </button>
                    <button style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 1rem; border: none; border-radius: 8px; text-align: left; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-shipping-fast"></i> Shipping Settings
                    </button>
                </div>
            </div>
            
            <!-- Settings Content -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.4rem;">
                    <i class="fas fa-user"></i> Profile Settings
                </h3>
                
                <form style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- Personal Information -->
                    <div>
                        <h4 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.1rem;">Personal Information</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">First Name</label>
                                <input type="text" value="John" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Last Name</label>
                                <input type="text" value="Vendor" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                            </div>
                        </div>
                        <div style="margin-top: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Email Address</label>
                            <input type="email" value="john.vendor@example.com" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                        </div>
                        <div style="margin-top: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Phone Number</label>
                            <input type="tel" value="+1 (555) 123-4567" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                        </div>
                    </div>
                    
                    <!-- Store Information -->
                    <div>
                        <h4 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.1rem;">Store Information</h4>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Store Name</label>
                            <input type="text" value="Vendor Store" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                        </div>
                        <div style="margin-top: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Store Description</label>
                            <textarea rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: vertical;">We are a leading vendor providing high-quality products to our customers.</textarea>
                        </div>
                        <div style="margin-top: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-purple);">Business Address</label>
                            <textarea rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: vertical;">123 Business Street, Suite 100, City, State 12345</textarea>
                        </div>
                    </div>
                    
                    <!-- Preferences -->
                    <div>
                        <h4 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.1rem;">Preferences</h4>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="email-notifications" checked style="width: 18px; height: 18px;">
                                <label for="email-notifications" style="font-weight: 600; color: var(--deep-purple);">Email Notifications</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="sms-notifications" style="width: 18px; height: 18px;">
                                <label for="sms-notifications" style="font-weight: 600; color: var(--deep-purple);">SMS Notifications</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="order-updates" checked style="width: 18px; height: 18px;">
                                <label for="order-updates" style="font-weight: 600; color: var(--deep-purple);">Order Status Updates</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="marketing-emails" style="width: 18px; height: 18px;">
                                <label for="marketing-emails" style="font-weight: 600; color: var(--deep-purple);">Marketing Emails</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Save Button -->
                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        <button type="submit" style="background: var(--deep-purple); color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem;">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <button type="button" style="background: var(--gray); color: var(--deep-purple); padding: 0.75rem 2rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem;">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 