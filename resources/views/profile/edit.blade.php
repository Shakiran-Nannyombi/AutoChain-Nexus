@extends('layouts.dashboard')

@section('title', 'Profile')

@section('sidebar-content')
    @if(auth()->user()->role === 'manufacturer')
        @include('dashboards.manufacturer.sidebar')
    @elseif(auth()->user()->role === 'supplier')
        @include('dashboards.supplier.sidebar')
    @elseif(auth()->user()->role === 'vendor')
        @include('dashboards.vendor.sidebar')
    @elseif(auth()->user()->role === 'retailer')
        @include('dashboards.retailer.sidebar')
    @elseif(auth()->user()->role === 'analyst')
        @include('dashboards.analyst.sidebar')
    @endif
@endsection

@section('content')
<h1 class='page-title' style='margin-bottom: 1.5rem;'>Edit Profile</h1>

<div class="admin-profile-grid">
    <!-- Left Column: User Info Card -->
    <div class="profile-user-card">
        @php
            $userProfilePhoto = $user->profile_photo_path;
            $nameParts = explode(' ', $user->name);
            $initials = count($nameParts) > 1 
                ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
                : strtoupper(substr($user->name, 0, 2));
        @endphp
        @if($userProfilePhoto)
            <img src="{{ asset('storage/' . $userProfilePhoto) }}" alt="Profile Photo" class="profile-avatar" style="object-fit:cover; width:100px; height:100px; border-radius:50%; border:2px solid #e0e0e0; margin-bottom:1rem;">
        @else
            <div class="profile-avatar">{{ $initials }}</div>
        @endif
        <h2 class="user-name">{{ $user->name }}</h2>
        <div class="user-role">{{ ucfirst($user->role) }}</div>
        <hr style="margin: 1.5rem 0;">
        <ul class="profile-user-details">
            <li><i class="fas fa-envelope"></i>{{ $user->email }}</li>
            <li><i class="fas fa-phone"></i>{{ $user->phone ?? 'N/A' }}</li>
            <li><i class="fas fa-building"></i>{{ $user->company ?? 'N/A' }}</li>
            <li><i class="fas fa-map-marker-alt"></i>{{ $user->address ?? 'N/A' }}</li>
            <li><i class="fas fa-calendar-alt"></i>Joined {{ $user->created_at->format('F Y') }}</li>
            <li><i class="fas fa-check-circle"></i>Status: {{ ucfirst($user->status) }}</li>
        </ul>
    </div>

    <!-- Right Column: Settings Forms -->
    <div class="profile-settings-forms">
        <div class="profile-settings-card">
            <h3><i class="fas fa-user-edit"></i>Profile Information</h3>
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="password-settings-card" style="margin-top: 2rem;">
            <h3><i class="fas fa-lock"></i>Update Password</h3>
            @include('profile.partials.update-password-form')
        </div>
        <div class="password-settings-card" style="margin-top: 2rem;">
            <h3><i class="fas fa-trash-alt"></i>Delete Account</h3>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .admin-profile-grid {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        align-items: flex-start;
    }

    .profile-user-card, .profile-settings-card, .password-settings-card {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .profile-user-card {
        text-align: center;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 600;
        margin: 0 auto 1rem;
    }

    .profile-user-card .user-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .profile-user-card .user-role {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    .profile-user-details {
        text-align: left;
        list-style: none;
        padding: 0;
    }

    .profile-user-details li {
        display: flex;
        align-items: center;
        color: #4b5563;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    .profile-user-details li i {
        width: 24px;
        text-align: center;
        margin-right: 0.75rem;
        color: #9ca3af;
    }

    .profile-settings-card h3, .password-settings-card h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .profile-settings-card h3 i, .password-settings-card h3 i {
        margin-right: 0.75rem;
        color: #9ca3af;
    }

    .settings-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .settings-form-grid .form-group {
        margin-bottom: 0;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-actions-alt .link-cancel {
        font-size: 14px;
        color: #6c757d;
        text-decoration: none;
        margin-left: 15px;
    }
</style>
@endpush
