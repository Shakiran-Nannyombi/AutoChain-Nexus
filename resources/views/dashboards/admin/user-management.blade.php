@extends('layouts.dashboard')

@section('title', 'User Management')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    <h1 class="page-title" style="margin-bottom: 1.5rem;">User Management</h1>
    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div>
                <div class="stat-title">Total Users</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-icon" style="color: #2a6eea; background: #e9f0ff;">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Active Users</div>
                <div class="stat-value" style="color: #28a745;">{{ $stats['active'] }}</div>
            </div>
            <div class="stat-icon" style="color: #28a745; background: #e9f6ec;">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Inactive Users</div>
                <div class="stat-value" style="color: #dc3545;">{{ $stats['inactive'] }}</div>
            </div>
            <div class="stat-icon" style="color: #dc3545; background: #f8dfe1;">
                <i class="fas fa-user-times"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">New This Month</div>
                <div class="stat-value">{{ $stats['new_this_month'] }}</div>
            </div>
            <div class="stat-icon" style="color: #6f42c1; background: #f1edff;">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="filter-card">
        <h2 class="card-title">Search & Filter Users</h2>
        <div class="filter-controls">
            <div class="search-bar" style="flex-grow: 1;">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search by name or email...">
            </div>
            <div class="filter-role">
                <i class="fas fa-filter"></i>
                <select>
                    <option value="">All Roles</option>
                    <option value="manufacturer">Manufacturer</option>
                    <option value="supplier">Supplier</option>
                    <option value="vendor">Vendor</option>
                    <option value="retailer">Retailer</option>
                    <option value="analyst">Analyst</option>
                </select>
            </div>
        </div>
    </div>

    <!-- All Users List -->
    <div class="user-list-card">
        <h2 class="card-title">All Users ({{ $users->count() }})</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($users->count() > 0)
            @foreach($users as $user)
                <div class="user-item">
                    <div class="user-info-main">
                        <div class="user-details">
                            <span class="user-name">{{ $user->company_name ?? $user->name }}</span>
                            <span class="role-badge role-{{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
                            <span class="status-badge status-{{ $user->status == 'approved' ? 'active' : 'inactive' }}">
                                {{ $user->status == 'approved' ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="user-email">Email: {{ $user->email }}</div>
                    </div>
                    <div class="user-info-secondary">
                        <div>Location: {{ $user->address ?? 'N/A' }}</div>
                        <div>Joined: {{ $user->created_at->format('Y-m-d') }}</div>
                        <div>Last Login: {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('Y-m-d') : 'N/A' }}</div>
                    </div>
                    <div class="user-actions">
                        <button class="btn-action btn-view" data-url="{{ route('admin.user.show', $user) }}"><i class="fas fa-eye"></i> View</button>
                        <a href="{{ route('admin.user.edit', $user) }}" class="btn-action btn-edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                        <form action="{{ route('admin.user.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <p style="text-align: center; padding: 2rem;">No users found.</p>
        @endif
    </div>

    <!-- View User Modal -->
    <div id="viewUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">User Details</h2>
            <div id="modalBody">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('viewUserModal');
    const closeBtn = modal.querySelector('.close');

    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            fetch(url)
                .then(response => response.json())
                .then(user => {
                    const modalBody = document.getElementById('modalBody');
                    
                    let documentsHtml = '<h4>Documents</h4>';
                    if (user.documents && user.documents.length > 0) {
                        documentsHtml += '<ul class="documents-list">';
                        user.documents.forEach(doc => {
                            documentsHtml += `<li><a href="/storage/${doc.file_path}" target="_blank">${doc.file_path.split('/').pop()}</a> (${doc.document_type})</li>`;
                        });
                        documentsHtml += '</ul>';
                    } else {
                        documentsHtml += '<p>No documents submitted.</p>';
                    }

                    modalBody.innerHTML = `
                        <p><strong>Name:</strong> ${user.name}</p>
                        <p><strong>Email:</strong> ${user.email}</p>
                        <p><strong>Company:</strong> ${user.company || 'N/A'}</p>
                        <p><strong>Role:</strong> ${user.role}</p>
                        <p><strong>Status:</strong> ${user.status}</p>
                        <p><strong>Joined:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                        <hr>
                        ${documentsHtml}
                    `;
                    document.getElementById('modalTitle').innerText = `Details for ${user.name}`;
                    modal.style.display = 'block';
                });
        });
    });

    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
</script>
@endpush
