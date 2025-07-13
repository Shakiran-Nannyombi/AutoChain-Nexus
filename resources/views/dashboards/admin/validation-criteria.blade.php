@extends('layouts.dashboard')

@section('title', 'Validation Criteria Management')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-list-alt"></i> Validation Criteria</h2>    
<div class="content-card validation-criteria-page">
        <div class="validation-grid">
            <!-- Add New Validation Rule -->
            <div class="validation-card">
                <h2 class="card-title"><i class="fas fa-plus"></i> Add New Validation Rule</h2>
                <form action="{{ route('admin.validation.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Criteria Name</label>
                        <input type="text" id="name" name="name" placeholder="e.g., Financial Stability" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" placeholder="e.g., Financial, Quality, Logistics" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Detailed description of the validation rule" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="value">Threshold/Value</label>
                        <input type="text" id="value" name="value" placeholder="e.g., 70, 9001, 5" required>
                    </div>
                    <button type="submit" class="btn-primary-full"><i class="fas fa-plus-circle"></i> Add Validation Rule</button>
                </form>
            </div>

            <!-- Backend Integration Status -->
            <div class="validation-card">
                <h2 class="card-title">Backend Integration Status</h2>
                <div class="status-box status-connected">
                    <div class="status-icon"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <strong>Java Backend Connected</strong>
                        <p>Validation rules are automatically synced with the backend system.</p>
                    </div>
                </div>
                <div class="sync-info">
                    <div>Last Sync: <span>-</span></div>
                    <div>Rules in DB: <span>{{ $rules->count() }}</span></div>
                    <div>Active Rules: <span>{{ $rules->where('status', 'active')->count() }}</span></div>
                </div>
                <form action="{{ route('admin.validation.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-secondary-full">Force Sync with Backend</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Current Validation Rules -->
        <div class="validation-card" style="margin-top: 2rem;">
            <h2 class="card-title">Current Validation Rules</h2>
            <div class="rules-list">
                @forelse ($rules as $rule)
                    <div class="rule-item">
                        <div class="rule-details">
                            <strong class="rule-name">{{ $rule->name }}</strong>
                            <div class="rule-category">Category: {{ $rule->category }}</div>
                            <div class="rule-description">{{ $rule->description }}</div>
                        </div>
                        <div class="rule-actions">
                            <span class="status-badge-sm status-{{ strtolower($rule->status) }}">{{ ucfirst($rule->status) }}</span>
                            <button class="btn-action-sm btn-edit" data-rule="{{ $rule->toJson() }}"><i class="fas fa-pencil-alt"></i></button>
                            <form action="{{ route('admin.validation.destroy', $rule->id) }}" method="POST" class="delete-form" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-sm btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; padding: 2rem;">No validation rules found. Add one above to get started.</p>
                @endforelse
            </div>
        </div>

        <!-- Edit Rule Modal -->
        <div id="edit-modal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close-button">&times;</span>
                <h2>Edit Validation Rule</h2>
                <form id="edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit-name">Criteria Name</label>
                        <input type="text" id="edit-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-category">Category</label>
                        <input type="text" id="edit-category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <textarea id="edit-description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-value">Threshold/Value</label>
                        <input type="text" id="edit-value" name="value" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-status">Status</label>
                        <select id="edit-status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary-full">Update Rule</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('edit-modal');
        const editForm = document.getElementById('edit-form');
        const closeButton = document.querySelector('.close-button');

        // Open modal
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function () {
                const rule = JSON.parse(this.dataset.rule);
                
                // Set form action
                editForm.action = `{{ url('admin/validation-criteria') }}/${rule.id}`;

                // Populate form fields
                document.getElementById('edit-name').value = rule.name;
                document.getElementById('edit-category').value = rule.category;
                document.getElementById('edit-description').value = rule.description;
                document.getElementById('edit-value').value = rule.value;
                document.getElementById('edit-status').value = rule.status;
                
                modal.style.display = 'block';
            });
        });

        // Close modal
        closeButton.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (event) {
                if (!confirm('Are you sure you want to delete this rule? This action cannot be undone.')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>
@endpush 