@extends('layouts.dashboard')

@section('title', 'Edit User')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    <div class="edit-user-container">
        <h1 class="edit-user-title">Edit User Details: {{ $user->name }}</h1> <br>

        <form class="edit-user-form" action="{{ route('admin.user.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-row">
                <label for="company">Company</label>
                <input type="text" id="company" name="company" value="{{ old('company', $user->company) }}">
            </div>

            <div class="form-row">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="form-row">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    @foreach(['manufacturer', 'supplier', 'vendor', 'retailer', 'analyst'] as $role)
                        <option value="{{ $role }}" @if(old('role', $user->role) == $role) selected @endif>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    @foreach(['pending', 'approved', 'rejected'] as $status)
                        <option value="{{ $status }}" @if(old('status', $user->status) == $status) selected @endif>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="form-actions-alt">
                <button type="submit" class="btn-save">Save Changes</button>
                <a href="{{ route('admin.user.index') }}" class="link-cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection 