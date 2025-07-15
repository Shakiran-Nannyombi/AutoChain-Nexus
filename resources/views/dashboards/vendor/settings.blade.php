@extends('layouts.dashboard')

@section('title', 'Vendor Settings')

@push('styles')
    @vite(['resources/css/vendor.css'])
    <style>
        .settings-container { max-width: 900px; margin: 0 auto; }
        .settings-section { 
            background: #fff; 
            border: 1px solid #e5e7eb; 
            border-radius: 12px; 
            padding: 2rem; 
            margin-bottom: 2rem; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s ease;
        }
        .settings-section:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .settings-section h2 { 
            color: var(--primary); 
            font-size: 1.4rem; 
            font-weight: 700; 
            margin-bottom: 1.5rem; 
            display: flex; 
            align-items: center; 
            gap: 0.5rem;
        }
        .settings-section h2::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--primary);
            border-radius: 2px;
        }
        .settings-form label { 
            font-weight: 600; 
            color: #374151; 
            margin-bottom: 0.5rem; 
            display: block; 
            font-size: 0.95rem;
        }
        .settings-form input, .settings-form select, .settings-form textarea {
            width: 100%; 
            padding: 0.8rem 1rem; 
            border-radius: 8px; 
            border: 1px solid #d1d5db; 
            margin-bottom: 1.5rem; 
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #f9fafb;
        }
        .settings-form input:focus, .settings-form select:focus, .settings-form textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: #fff;
        }
        .settings-form input[type="file"] { 
            padding: 0.5rem 0; 
            background: transparent;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }
        .settings-form input[type="file"]:hover {
            border-color: var(--primary);
        }
        .settings-form .form-actions { 
            display: flex; 
            gap: 1rem; 
            flex-wrap: wrap;
        }
        .settings-form button { 
            background: var(--primary); 
            color: #fff; 
            border: none; 
            border-radius: 8px; 
            padding: 0.8rem 2rem; 
            font-size: 1rem; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }
        .settings-form button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }
        .settings-form button.delete { 
            background: #ef4444; 
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }
        .settings-form button.delete:hover {
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }
        .settings-form .toggle-switch { 
            display: flex; 
            align-items: center; 
            gap: 0.8rem; 
            margin-bottom: 1.2rem; 
            padding: 0.8rem;
            background: #f9fafb;
            border-radius: 8px;
            transition: background 0.2s ease;
        }
        .settings-form .toggle-switch:hover {
            background: #f3f4f6;
        }
        .settings-form .toggle-switch input[type="checkbox"] { 
            width: 44px;
            height: 24px;
            appearance: none;
            background: #d1d5db;
            border-radius: 12px;
            position: relative;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .settings-form .toggle-switch input[type="checkbox"]:checked {
            background: var(--primary);
        }
        .settings-form .toggle-switch input[type="checkbox"]::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fff;
            top: 2px;
            left: 2px;
            transition: transform 0.2s ease;
        }
        .settings-form .toggle-switch input[type="checkbox"]:checked::before {
            transform: translateX(20px);
        }
        .settings-form .toggle-switch label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }
        .placeholder-text {
            color: #6b7280; 
            font-size: 0.95rem; 
            font-style: italic;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px dashed #d1d5db;
        }
        @media (max-width: 768px) {
            .settings-container { padding: 0 1rem; }
            .settings-section { padding: 1.5rem; }
            .settings-form .form-actions { flex-direction: column; }
            .settings-form button { width: 100%; }
        }
    </style>
@endpush

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card settings-container" style="padding: 2rem;">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Vendor Settings
    </h2>
    <form class="settings-form" method="POST" action="#" enctype="multipart/form-data">
        @csrf
        <!-- Notification Preferences -->
        <div class="settings-section">
            <h2>Notification Preferences</h2>
            <div class="toggle-switch">
                <input type="checkbox" id="notify_messages" name="notify_messages" {{ old('notify_messages', true) ? 'checked' : '' }}>
                <label for="notify_messages">Enable chat message notifications</label>
            </div>
            <div class="toggle-switch">
                <input type="checkbox" id="notify_orders" name="notify_orders" {{ old('notify_orders', true) ? 'checked' : '' }}>
                <label for="notify_orders">Enable order status notifications</label>
            </div>
        </div>

        <!-- Company Branding -->
        <div class="settings-section">
            <h2>Company Branding</h2>
            <label for="company_logo">Company Logo</label>
            <input type="file" id="company_logo" name="company_logo" accept="image/*">

            <label for="business_hours">Business Hours</label>
            <input type="text" id="business_hours" name="business_hours" value="{{ old('business_hours', $user->business_hours ?? '') }}" placeholder="e.g. Mon-Fri: 9AM-6PM, Sat: 10AM-4PM">

            <label for="company_description">Company Description</label>
            <textarea id="company_description" name="company_description" rows="4" placeholder="Tell us about your company...">{{ old('company_description', $user->company_description ?? '') }}</textarea>
        </div>

        <!-- Compliance Documents -->
        <div class="settings-section">
            <h2>Compliance Documents</h2>
            <label for="compliance_docs">Upload Compliance Documents</label>
            <input type="file" id="compliance_docs" name="compliance_docs[]" multiple>
        </div>

        <!-- Theme/Appearance -->
        <div class="settings-section">
            <h2>Theme & Appearance</h2>
            <div class="toggle-switch">
                <input type="checkbox" id="dark_mode" name="dark_mode" {{ old('dark_mode') ? 'checked' : '' }}>
                <label for="dark_mode">Enable Dark Mode</label>
            </div>
        </div>

        <!-- Integrations & Security (Placeholder) -->
        <div class="settings-section">
            <h2>Integrations & Security</h2>
            <div class="placeholder-text">
                Coming soon: Email/SMS integrations, Two-Factor Authentication, Session Management, and more security features.
            </div>
        </div>

        <!-- Account Actions -->
        <div class="settings-section">
            <h2>Account Actions</h2>
            <div class="form-actions">
                <button type="submit">Save Changes</button>
                <button type="button" class="delete" onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')){ document.getElementById('delete-account-form').submit(); }">Delete Account</button>
            </div>
        </div>
    </form>
    <form id="delete-account-form" method="POST" action="#" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection