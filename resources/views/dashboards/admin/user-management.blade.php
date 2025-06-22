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
        <form id="filterForm" action="{{ route('admin.user-management') }}" method="GET">
            <div class="filter-controls">
                <div class="search-bar" style="flex-grow: 1;">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search by name, email, or company..." value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="filter-role">
                    <i class="fas fa-filter"></i>
                    <select name="role">
                        <option value="">All Roles</option>
                        @php
                            $roles = ['manufacturer', 'supplier', 'vendor', 'retailer', 'analyst'];
                        @endphp
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ ($filters['role'] ?? '') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
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
                        <button class="btn-action btn-view" 
                                data-user-name="{{ $user->name }}"
                                data-user-email="{{ $user->email }}"
                                data-user-company="{{ $user->company ?? 'N/A' }}"
                                data-user-role="{{ ucfirst($user->role) }}"
                                data-user-status="{{ ucfirst($user->status) }}"
                                data-user-joined="{{ $user->created_at->format('n/j/Y') }}"
                                data-user-documents="{{ json_encode($user->documents) }}">
                            <i class="fas fa-eye"></i> View
                        </button>
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
    <div id="viewUserModal" class="modal user-details-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">User Details</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const searchInput = filterForm.querySelector('input[name="search"]');
    const roleSelect = filterForm.querySelector('select[name="role"]');
    let searchTimeout;

    // Auto-submit on search input with debounce
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500); // Wait 500ms after user stops typing
    });

    // Auto-submit on role change
    roleSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    const modal = document.getElementById('viewUserModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    const closeBtn = modal.querySelector('.close');

    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function () {
            const userName = this.dataset.userName;
            const documents = JSON.parse(this.dataset.userDocuments);

            let documentsHtml = '<div class="detail-section"><h4>Documents</h4>';
            if (documents && documents.length > 0) {
                documentsHtml += '<ul class="documents-list">';
                documents.forEach(doc => {
                    // Assuming a public 'storage' link is set up
                    const docUrl = `/storage/${doc.file_path}`;
                    const docName = doc.file_path.split('/').pop();
                    documentsHtml += `<li><a href="${docUrl}" target="_blank">${docName}</a></li>`;
                });
                documentsHtml += '</ul></div>';
            } else {
                documentsHtml += '<p>No documents submitted.</p></div>';
            }

            modalTitle.innerText = `Details for ${userName}`;
            modalBody.innerHTML = `
                <div class="detail-grid">
                    <div class="detail-item">
                        <span>Name:</span>
                        <p>${userName}</p>
                    </div>
                    <div class="detail-item">
                        <span>Email:</span>
                        <p>${this.dataset.userEmail}</p>
                    </div>
                    <div class="detail-item">
                        <span>Company:</span>
                        <p>${this.dataset.userCompany}</p>
                    </div>
                    <div class="detail-item">
                        <span>Role:</span>
                        <p>${this.dataset.userRole}</p>
                    </div>
                    <div class="detail-item">
                        <span>Status:</span>
                        <p>${this.dataset.userStatus}</p>
                    </div>
                    <div class="detail-item">
                        <span>Joined:</span>
                        <p>${this.dataset.userJoined}</p>
                    </div>
                </div>
                ${documentsHtml}
            `;
            
            modal.classList.add('show');
        });
    });

    const hideModal = () => {
        modal.classList.remove('show');
    };

    closeBtn.addEventListener('click', hideModal);
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            hideModal();
        }
    });
});
</script>
@endpush
