@extends('layouts.dashboard')

@section('title', 'Validation Criteria Management')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<div class="content-card validation-criteria-page">
    <h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem; font-weight: bold;">
        <i class="fas fa-list-alt"></i> Validation Criteria</h2>
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
            <form id="edit-form" method="POST" style="padding: 1.1rem 1.2rem; border-radius: 14px; box-shadow: 0 8px 32px rgba(0,0,0,0.18); background: #fff; max-width: 700px; margin: 2rem auto; position: relative; display: flex; flex-direction: column; gap: 0.4rem;">
                <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.1rem;">
                    <h3 style="margin: 0; font-size: 1.1rem; color: var(--primary, #16610E); font-weight: 700;">Edit Validation Rule</h3>
                    <span class="close-button" style="font-size: 1.5rem; cursor: pointer; color: #888; transition: color 0.2s;">&times;</span>
                </div>
                    @csrf
                    @method('PUT')
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <label for="edit-name" style="font-weight: 600; color: #222;">Criteria Name</label>
                        <input type="text" id="edit-name" name="name" required style="padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.97rem;">
                    </div>
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <label for="edit-category" style="font-weight: 600; color: #222;">Category</label>
                        <input type="text" id="edit-category" name="category" required style="padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.97rem;">
                    </div>
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <label for="edit-description" style="font-weight: 600; color: #222;">Description</label>
                        <textarea id="edit-description" name="description" rows="2" required style="padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 1.05rem; resize: vertical; min-height: 38px;"></textarea>
                    </div>
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <label for="edit-value" style="font-weight: 600; color: #222;">Threshold/Value</label>
                        <input type="text" id="edit-value" name="value" required style="padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.97rem;">
                    </div>
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <label for="edit-status" style="font-weight: 600; color: #222;">Status</label>
                        <select id="edit-status" name="status" required style="padding: 0.5rem; border-radius: 6px; border: 1px solid #aec7e4; font-size: 0.97rem; margin-bottom: 0.3rem;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary-full" style="width: 100%; background: var(--primary, #16610E); color: #fff; border: none; border-radius: 8px; padding: 0.9rem; font-size: 1.08rem; font-weight: 600; margin-bottom: 0; cursor: pointer;">Update Rule</button>
                </form>
        </div>
        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="modal" style="display:none;">
            <div class="modal-content" style="padding: 1.5rem 2rem; border-radius: 14px; box-shadow: 0 8px 32px rgba(0,0,0,0.18); background: #fff; max-width: 400px; margin: 6rem auto; position: relative; text-align: center;">
                <h3 style="color: var(--primary, #16610E); font-size: 1.2rem; font-weight: 700; margin-bottom: 1.2rem;">Delete Validation Rule?</h3>
                <p style="margin-bottom: 2rem; color: #333;">Are you sure you want to delete this rule? This action cannot be undone.</p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button id="cancel-delete" type="button" style="padding: 0.6rem 1.5rem; border-radius: 6px; border: none; background: #eee; color: #222; font-weight: 600; cursor: pointer;">Cancel</button>
                    <button id="confirm-delete" type="button" style="padding: 0.6rem 1.5rem; border-radius: 6px; border: none; background: var(--primary, #16610E); color: #fff; font-weight: 600; cursor: pointer;">Delete</button>
                </div>
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
        const deleteModal = document.getElementById('delete-modal');
        let formToDelete = null;

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
                    event.preventDefault();
                formToDelete = form;
                deleteModal.style.display = 'block';
            });
        });

        document.getElementById('cancel-delete').onclick = function() {
            deleteModal.style.display = 'none';
            formToDelete = null;
        };
        document.getElementById('confirm-delete').onclick = function() {
            if (formToDelete) {
                formToDelete.submit();
                deleteModal.style.display = 'none';
            }
        };
        window.addEventListener('click', (event) => {
            if (event.target == deleteModal) {
                deleteModal.style.display = 'none';
                formToDelete = null;
            }
        });
    });
</script>
@endpush 