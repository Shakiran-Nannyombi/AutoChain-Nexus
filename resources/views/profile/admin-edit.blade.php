@extends('layouts.dashboard')

@section('title', 'Admin Profile')

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<h1 class='page-title' style='margin-bottom: 1.5rem;'>Edit Profile</h1>

<div class="admin-profile-grid">
    <!-- Left Column: User Info Card -->
    <div class="profile-user-card">
        @php
            $adminProfilePhoto = $user->profile_photo;
            $nameParts = explode(' ', $user->name);
            $initials = count($nameParts) > 1 
                ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
                : strtoupper(substr($user->name, 0, 2));
        @endphp
        @if($adminProfilePhoto)
            <img src="{{ asset($adminProfilePhoto) }}" alt="Profile Photo" class="profile-avatar" style="object-fit:cover; width:100px; height:100px; border-radius:50%; border:2px solid #e0e0e0; margin-bottom:1rem;">
        @else
            <div class="profile-avatar">{{ $initials }}</div>
        @endif
        <h2 class="user-name">{{ $user->name }}</h2>
        <div class="user-role">{{ $user->department ?? 'Admin' }}</div>
        <hr style="margin: 1.5rem 0;">
        <ul class="profile-user-details">
            <li><i class="fas fa-briefcase"></i>{{ $user->department ?? 'N/A' }}</li>
            <li><i class="fas fa-map-marker-alt"></i>{{ $user->address ?? 'N/A' }}</li>
            <li><i class="fas fa-calendar-alt"></i>Joined {{ $user->created_at->format('F Y') }}</li>
        </ul>
    </div>

    <!-- Right Column: Settings Forms -->
    <div class="profile-settings-forms">
        <div class="profile-settings-card">
            @include('profile.partials.admin-update-profile-information-form')
        </div>
        <div class="password-settings-card" style="margin-top: 2rem;">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-page {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        max-width: 800px;
    }
    .profile-card {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
</style>
@endpush 