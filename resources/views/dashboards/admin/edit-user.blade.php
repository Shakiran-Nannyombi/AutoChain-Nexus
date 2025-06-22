@extends('layouts.dashboard')

@section('title', 'Edit User')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    <div class="edit-user-container-new">
        <h1 class="edit-user-title-new">Edit User Details: <span>{{ $user->name }}</span></h1>

        <form class="edit-user-form-new" action="{{ route('admin.user.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
            </div>

            <div class="form-group">
                <label for="company">Company</label>
                <input type="text" id="company" name="company" class="form-control" value="{{ old('company', $user->company) }}">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-control" required>
                    @foreach(['manufacturer', 'supplier', 'vendor', 'retailer', 'analyst'] as $role)
                        <option value="{{ $role }}" @if(old('role', $user->role) == $role) selected @endif>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    @foreach(['pending', 'approved', 'rejected'] as $status)
                        <option value="{{ $status }}" @if(old('status', $user->status) == $status) selected @endif>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="4">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save-new">Save Changes</button>
                <a href="{{ route('admin.user-management') }}" class="link-cancel-new">Cancel</a>
            </div>
        </form>
    </div>
@endsection 