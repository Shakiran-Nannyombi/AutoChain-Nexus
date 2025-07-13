@extends('layouts.dashboard')

@section('title', 'Profile')

@section('sidebar-content')
    @if(($user instanceof \App\Models\Admin) || ($user->role === 'admin'))
        @include('dashboards.admin.sidebar')
    @elseif($user->role === 'manufacturer')
        @include('dashboards.manufacturer.sidebar')
    @elseif($user->role === 'supplier')
        @include('dashboards.supplier.sidebar')
    @elseif($user->role === 'vendor')
        @include('dashboards.vendor.sidebar')
    @elseif($user->role === 'retailer')
        @include('dashboards.retailer.sidebar')
    @elseif($user->role === 'analyst')
        @include('dashboards.analyst.sidebar')
    @endif
@endsection

@push('styles')
    @vite(['resources/css/profile.css'])
@endpush

@section('content')
<div class="content-card profile-content-card">
    <h2 class="profile-page-heading" style="color: var(--primary); font-size: 2rem; font-weight: 700; display: flex; align-items: center; gap: 0.7rem; margin-bottom: 2.2rem;"><i class="fas fa-user-circle"></i> Profile</h2>
    <div class="profile-main-row">
        <!-- Left: Profile Summary Card -->
        <div class="profile-summary-col">
            <div class="profile-summary-card">
                <div class="profile-summary-avatar">
                    @php $userProfilePhoto = $user->profile_photo; @endphp
        @if($userProfilePhoto)
                        <img src="{{ asset($userProfilePhoto) }}" alt="Profile Photo" class="profile-avatar-lg">
        @else
                        <img src="{{ asset('images/profile.png') }}" alt="Default Profile Photo" class="profile-avatar-lg">
        @endif
                </div>
                <div class="profile-summary-info">
                    <div class="profile-summary-name">{{ $user->name }}</div>
                    <div class="profile-summary-role">{{ ucfirst($user->role) }}</div>
                    <div class="profile-summary-location">{{ $user->address ?? 'Location not set' }}</div>
                </div>
            </div>
        </div>
        <!-- Right: Info Cards stacked -->
        <div class="profile-info-col">
            <div class="profile-section-card">
                <div class="profile-section-header">
                    <span>Personal Information</span>
                    <button class="profile-edit-btn" onclick="document.getElementById('personal-info-form').scrollIntoView({behavior: 'smooth'});"><i class="fas fa-pen"></i> Edit</button>
                </div>
                <div class="profile-section-content">
                    <div class="profile-info-row">
                        <div><span class="profile-info-label">Full Name</span><span>{{ $user->name }}</span></div>
                        <div><span class="profile-info-label">Email Address</span><span>{{ $user->email }}</span></div>
                        <div><span class="profile-info-label">Phone Number</span><span>{{ $user->phone ?? 'N/A' }}</span></div>
                        <div><span class="profile-info-label">User Role</span><span>{{ ucfirst($user->role) }}</span></div>
                    </div>
                </div>
            </div>
            <div class="profile-section-card">
                <div class="profile-section-header">
                    <span>Address</span>
                    <button class="profile-edit-btn" onclick="document.getElementById('personal-info-form').scrollIntoView({behavior: 'smooth'});"><i class="fas fa-pen"></i> Edit</button>
                </div>
                <div class="profile-section-content">
                    <div class="profile-info-row">
                        <div><span class="profile-info-label">Address</span><span>{{ $user->address ?? 'N/A' }}</span></div>
                        <div><span class="profile-info-label">Company</span><span>{{ $user->company ?? 'N/A' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Rest of the content (edit forms etc) -->
    <div class="profile-edit-row">
        <div class="profile-edit-col">
            <div class="profile-section-card" id="personal-info-form">
                <div class="profile-section-header">
                    <span>Edit Profile Information</span>
                </div>
                <div class="profile-section-content">
            @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
        <div class="profile-edit-col">
            <div class="profile-section-card">
                <div class="profile-section-header">
                    <span>Change Password</span>
                </div>
                <div class="profile-section-content">
            @include('profile.partials.update-password-form')
        </div>
            </div>
            <div class="profile-section-card">
                <div class="profile-section-header">
                    <span>Delete Account</span>
                </div>
                <div class="profile-section-content">
            @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
