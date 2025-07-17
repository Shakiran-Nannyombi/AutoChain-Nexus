@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card" style="max-width:700px;">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">Edit Profile</h2>
    @if(session('success'))
        <div style="background:#e6f4ea; color:#14532d; padding:1rem; border-radius:8px; margin-bottom:1rem;">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('vendor.profile.update') }}" style="margin-bottom:2rem;">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
            @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Company</label>
            <input type="text" name="company" class="form-control" value="{{ old('company', $user->company) }}" required>
            @error('company')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" required>
            @error('address')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="button" style="background:var(--primary); color:#fff; padding:0.6rem 1.2rem; border-radius:8px;">Update Profile</button>
    </form>
    <h3 style="color:var(--primary); margin-bottom:1rem;">Change Password</h3>
    <form method="POST" action="{{ route('vendor.profile.password') }}" style="margin-bottom:2rem;">
        @csrf
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
            @error('current_password')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="button" style="background:var(--primary); color:#fff; padding:0.6rem 1.2rem; border-radius:8px;">Change Password</button>
    </form>
    <h3 style="color:var(--primary); margin-bottom:1rem;">Upload Documents</h3>
    <form method="POST" action="{{ route('vendor.profile.documents') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
            @error('profile_picture')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Supporting Documents</label>
            <input type="file" name="supporting_documents[]" class="form-control" multiple>
            @error('supporting_documents.*')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="button" style="background:var(--primary); color:#fff; padding:0.6rem 1.2rem; border-radius:8px;">Upload</button>
    </form>
</div>
@endsection 