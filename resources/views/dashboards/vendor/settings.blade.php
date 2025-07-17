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
        .document-list {
            margin-top: 1rem;
        }
        .document-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        .document-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .document-icon {
            color: var(--primary);
            font-size: 1.2rem;
        }
        .activity-list {
            list-style: none;
            padding: 0;
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
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

    @if(session('success'))
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <ul style="margin: 0; padding-left: 1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Information -->
    <div class="settings-section">
        <h2>Profile Information</h2>
        <form method="POST" action="{{ route('vendor.settings.update-profile') }}" class="settings-form">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div>
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
                <div>
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit">Update Profile</button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="settings-section">
        <h2>Change Password</h2>
        <form method="POST" action="{{ route('vendor.settings.change-password') }}" class="settings-form">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div>
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
            </div>
            <div>
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>
            <div class="form-actions">
                <button type="submit">Change Password</button>
            </div>
        </form>
    </div>

    <!-- Company Information -->
    <div class="settings-section">
        <h2>Company Information</h2>
        <form method="POST" action="{{ route('vendor.settings.update-company') }}" class="settings-form" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="company_description">Company Description</label>
                <textarea id="company_description" name="company_description" rows="4" placeholder="Tell us about your company...">{{ old('company_description', $user->company_description) }}</textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="business_hours">Business Hours</label>
                    <input type="text" id="business_hours" name="business_hours" value="{{ old('business_hours', $user->business_hours) }}" placeholder="e.g. Mon-Fri: 9AM-6PM, Sat: 10AM-4PM">
                </div>
                <div>
                    <label for="website">Website</label>
                    <input type="url" id="website" name="website" value="{{ old('website', $user->website) }}" placeholder="https://yourcompany.com">
                </div>
            </div>
            <div>
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3" placeholder="Enter your business address">{{ old('address', $user->address) }}</textarea>
            </div>
            <div class="form-actions">
                <button type="submit">Update Company Info</button>
            </div>
        </form>
    </div>

    <!-- Documents -->
    <div class="settings-section">
        <h2>Documents</h2>
        <form method="POST" action="{{ route('vendor.settings.upload-document') }}" class="settings-form" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="document_type">Document Type</label>
                    <select id="document_type" name="document_type" required>
                        <option value="">Select document type</option>
                        <option value="business_license">Business License</option>
                        <option value="tax_certificate">Tax Certificate</option>
                        <option value="insurance">Insurance Certificate</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="document_file">Upload Document</label>
                    <input type="file" id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit">Upload Document</button>
            </div>
        </form>

        @if($documents->count() > 0)
        <div class="document-list">
            <h3 style="color: var(--primary); margin: 1.5rem 0 1rem 0;">Uploaded Documents</h3>
            @foreach($documents as $document)
            <div class="document-item">
                <div class="document-info">
                    <i class="fas fa-file-alt document-icon"></i>
                    <div>
                        <div style="font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</div>
                        <div style="font-size: 0.9rem; color: #6b7280;">Uploaded {{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('M d, Y') : 'N/A' }}</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('vendor.settings.download-document', $document->id) }}" class="btn btn-sm btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Download</a>
                    <form method="POST" action="{{ route('vendor.settings.delete-document', $document->id) }}" style="display: inline;">
        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

        <!-- Notification Preferences -->
        <div class="settings-section">
            <h2>Notification Preferences</h2>
        <form method="POST" action="{{ route('vendor.settings.update-notifications') }}" class="settings-form">
            @csrf
            <div class="toggle-switch">
                <input type="checkbox" id="notify_messages" name="notify_messages" {{ old('notify_messages', $user->notify_messages ?? true) ? 'checked' : '' }}>
                <label for="notify_messages">Enable chat message notifications</label>
            </div>
            <div class="toggle-switch">
                <input type="checkbox" id="notify_orders" name="notify_orders" {{ old('notify_orders', $user->notify_orders ?? true) ? 'checked' : '' }}>
                <label for="notify_orders">Enable order status notifications</label>
            </div>
            <div class="toggle-switch">
                <input type="checkbox" id="notify_deliveries" name="notify_deliveries" {{ old('notify_deliveries', $user->notify_deliveries ?? true) ? 'checked' : '' }}>
                <label for="notify_deliveries">Enable delivery updates</label>
            </div>
            <div class="form-actions">
                <button type="submit">Update Notifications</button>
            </div>
        </form>
        </div>

    <!-- Recent Activity -->
    @if($recentActivities->count() > 0)
        <div class="settings-section">
        <h2>Recent Activity</h2>
        <ul class="activity-list">
            @foreach($recentActivities as $activity)
            <li class="activity-item">
                <div class="activity-dot"></div>
                <div>
                    <div style="font-weight: 500;">{{ $activity->description }}</div>
                    <div style="font-size: 0.9rem; color: #6b7280;">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Account Deletion -->
    <div class="settings-section" style="border-color: #ef4444;">
        <h2 style="color: #ef4444;">Danger Zone</h2>
        <p style="color: #6b7280; margin-bottom: 1rem;">Once you delete your account, there is no going back. Please be certain.</p>
        <form method="POST" action="{{ route('vendor.settings.delete-account') }}" class="settings-form" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <div class="form-actions">
                <button type="submit" class="delete">Delete Account</button>
            </div>
        </form>
        </div>
</div>
@endsection