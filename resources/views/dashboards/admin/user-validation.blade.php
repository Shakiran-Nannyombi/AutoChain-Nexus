@extends('layouts.dashboard')

@section('title', 'User Validation Center')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    <h1 class="page-title">User Validation Center</h1>
    <br>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ session('error') }}</div>
    @endif

    <!-- Stat Cards -->
    <div class="stat-cards-grid" style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #174ea6 0%, #2563eb 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon yellow" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-clock"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Pending Validations</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['pending'] }}</span>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #15803d 0%, #166534 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon green" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Approved This Week</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['approved_this_week'] }}</span>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%); color: #fff; border-radius: 14px; padding: 1.5rem; flex: 1; display: flex; align-items: center;">
            <div class="stat-icon red" style="color: #fff; background: rgba(255,255,255,0.12); border-radius: 50%; padding: 0.7rem; margin-right: 1rem;"><i class="fas fa-times-circle"></i></div>
            <div class="stat-info" style="color: #fff;">
                <p style="color: #fff; font-size: 1.1rem; opacity: 0.95;">Rejected This Week</p>
                <span style="color: #fff; font-size: 2rem; font-weight: bold;">{{ $stats['rejected_this_week'] }}</span>
            </div>
        </div>
    </div>

    <!-- Pending Applications List -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h2>Pending User Applications</h2>
        </div>
        <div class="card-body">
            @forelse ($pendingUsers as $user)
                <div class="application-card-new">
                    <div class="application-header-new">
                        <div class="company-info-new">
                            <h3>{{ $user->company ?? $user->name }}</h3>
                            <span class="role-badge-new role-{{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
                        </div>
                        @if($user->validation_score !== null)
                            <div class="score-badge-new">Score: {{ $user->validation_score }}%</div>
                        @endif
                    </div>
                    <div class="application-body-new">
                        <div class="contact-info-new">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone:</strong> {{ $user->phone }}</p>
                        </div>
                        <div class="submission-info-new">
                            <p><strong>Submitted:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
                            <p><strong>Address:</strong> {{ $user->address }}</p>
                        </div>
                    </div>
                    <div class="application-documents-new">
                        <p><strong>Submitted Documents:</strong></p>
                        <div class="documents-list-new">
                            @forelse($user->documents as $document)
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="document-tag-new">
                                    <i class="fas fa-file-alt"></i> {{ basename($document->file_path) }}
                                </a>
                            @empty
                                <span>No documents submitted.</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="application-footer-new">
                        <div class="validation-score-new">
                            @if($user->validation_score !== null)
                                <div class="score-breakdown">
                                    <div class="score-item">
                                        <span class="score-label">Overall:</span>
                                        <span class="score-value {{ $user->validation_score >= 70 ? 'score-pass' : 'score-fail' }}">
                                            {{ $user->validation_score }}%
                                        </span>
                                    </div>
                                    @if($user->financial_score !== null)
                                        <div class="score-item">
                                            <span class="score-label">Financial:</span>
                                            <span class="score-value">{{ $user->financial_score }}/30</span>
                                        </div>
                                    @endif
                                    @if($user->reputation_score !== null)
                                        <div class="score-item">
                                            <span class="score-label">Reputation:</span>
                                            <span class="score-value">{{ $user->reputation_score }}/25</span>
                                        </div>
                                    @endif
                                    @if($user->compliance_score !== null)
                                        <div class="score-item">
                                            <span class="score-label">Compliance:</span>
                                            <span class="score-value">{{ $user->compliance_score }}/25</span>
                                        </div>
                                    @endif
                                    @if($user->profile_score !== null)
                                        <div class="score-item">
                                            <span class="score-label">Profile:</span>
                                            <span class="score-value">{{ $user->profile_score }}/15</span>
                                        </div>
                                    @endif
                                </div>
                                @if($user->auto_visit_scheduled)
                                    <div class="visit-scheduled-badge">
                                        <i class="fas fa-calendar-check"></i> Visit Scheduled
                                    </div>
                                @endif
                            @else
                                <span>Validation Score: <strong>Not Run</strong></span>
                            @endif
                        </div>
                        <div class="action-buttons-new">
                            <a href="#" class="btn-action-new btn-view-details" data-url="{{ route('admin.user.show', $user) }}">View Details</a>
                            @if($user->validation_score !== null)
                                <a href="#" class="btn-action-new btn-view-validation" data-user-id="{{ $user->id }}">View Validation</a>
                            @endif
                            <form action="{{ route('admin.user.validate', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action-new btn-run-validation">Run Validation</button>
                            </form>
                            @if($user->role !== 'vendor')
                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action-new btn-approve">Approve & Send Email</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action-new btn-reject">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; padding: 2rem;">No pending user applications found.</p>
            @endforelse
        </div>
    </div>

    <!-- User Details Modal -->
    <div id="viewUserModal" class="modal">
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

    <!-- Validation Details Modal -->
    <div id="validationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="validationModalTitle">Validation Details</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="validationModalBody">
                <!-- Validation details will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const userModal = document.getElementById('viewUserModal');
    const validationModal = document.getElementById('validationModal');
    
    // User Details Modal
    if (userModal) {
        const closeBtn = userModal.querySelector('.close');

        document.querySelectorAll('.btn-view-details').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.dataset.url;
                if (!url) return;

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
                            <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                            <p><strong>Address:</strong> ${user.address || 'N/A'}</p>
                            <p><strong>Validation Score:</strong> ${user.validation_score || 'Not Run'}</p>
                            <p><strong>Joined:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                            <hr>
                            ${documentsHtml}
                        `;
                        document.getElementById('modalTitle').innerText = `Details for ${user.name}`;
                        userModal.classList.add('show');
                    })
                    .catch(error => {
                        console.error('Error fetching user details:', error);
                        alert('Error loading user details. Please try again.');
                    });
            });
        });

        if (closeBtn) {
            closeBtn.onclick = function() {
                userModal.classList.remove('show');
            }
        }
    }

    // Validation Details Modal
    if (validationModal) {
        const closeBtn = validationModal.querySelector('.close');

        document.querySelectorAll('.btn-view-validation').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.dataset.userId;
                if (!userId) return;

                // Fetch user data to show validation details
                fetch(`/admin/user-management/user/${userId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(user => {
                        const modalBody = document.getElementById('validationModalBody');
                        
                        let extractedDataHtml = '';
                        if (user.extracted_data) {
                            extractedDataHtml = `
                                <div class="extracted-data-section">
                                    <h4>Extracted Data from Documents</h4>
                                    ${renderExtractedData(user.extracted_data)}
                                </div>
                            `;
                        }

                        modalBody.innerHTML = `
                            <div class="validation-summary">
                                <h4>Validation Summary</h4>
                                <div class="score-grid">
                                    <div class="score-card ${user.validation_score >= 70 ? 'score-pass' : 'score-fail'}">
                                        <div class="score-number">${user.validation_score || 0}%</div>
                                        <div class="score-label">Overall Score</div>
                                    </div>
                                    <div class="score-card">
                                        <div class="score-number">${user.financial_score || 0}/30</div>
                                        <div class="score-label">Financial Stability</div>
                                    </div>
                                    <div class="score-card">
                                        <div class="score-number">${user.reputation_score || 0}/25</div>
                                        <div class="score-label">Reputation</div>
                                    </div>
                                    <div class="score-card">
                                        <div class="score-number">${user.compliance_score || 0}/25</div>
                                        <div class="score-label">Compliance</div>
                                    </div>
                                    <div class="score-card">
                                        <div class="score-number">${user.profile_score || 0}/15</div>
                                        <div class="score-label">Profile Completeness</div>
                                    </div>
                                </div>
                            </div>
                            <div class="validation-details">
                                <h4>Validation Criteria</h4>
                                <ul class="criteria-list">
                                    <li><strong>Financial Stability (30 points):</strong> Years in business, revenue, employee count</li>
                                    <li><strong>Reputation (25 points):</strong> Awards, customer reviews, industry membership</li>
                                    <li><strong>Regulatory Compliance (25 points):</strong> ISO certifications, licenses, regulatory compliance</li>
                                    <li><strong>Profile Completeness (15 points):</strong> Company name, address, phone number</li>
                                    <li><strong>Document Quality (5 points):</strong> Valid file formats and processing</li>
                                </ul>
                            </div>
                            ${extractedDataHtml}
                            <div class="validation-status">
                                <p><strong>Validation Date:</strong> ${user.validated_at ? new Date(user.validated_at).toLocaleString() : 'Not validated'}</p>
                                <p><strong>Auto Visit Scheduled:</strong> ${user.auto_visit_scheduled ? 'Yes' : 'No'}</p>
                            </div>
                        `;
                        document.getElementById('validationModalTitle').innerText = `Validation Details for ${user.name}`;
                        validationModal.classList.add('show');
                    })
                    .catch(error => {
                        console.error('Error fetching validation details:', error);
                        alert('Error loading validation details. Please try again.');
                    });
            });
        });

        if (closeBtn) {
            closeBtn.onclick = function() {
                validationModal.classList.remove('show');
            }
        }
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == userModal) {
            userModal.classList.remove('show');
        }
        if (event.target == validationModal) {
            validationModal.classList.remove('show');
        }
    }

    function renderExtractedData(data) {
        let html = '<div class="extracted-data-grid">';
        
        if (data.financial_data) {
            html += '<div class="data-section"><h5>Financial Data</h5><ul>';
            Object.entries(data.financial_data).forEach(([key, value]) => {
                html += `<li><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value}</li>`;
            });
            html += '</ul></div>';
        }
        
        if (data.reputation_data) {
            html += '<div class="data-section"><h5>Reputation Data</h5><ul>';
            Object.entries(data.reputation_data).forEach(([key, value]) => {
                html += `<li><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value}</li>`;
            });
            html += '</ul></div>';
        }
        
        if (data.compliance_data) {
            html += '<div class="data-section"><h5>Compliance Data</h5><ul>';
            Object.entries(data.compliance_data).forEach(([key, value]) => {
                html += `<li><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value}</li>`;
            });
            html += '</ul></div>';
        }
        
        html += '</div>';
        return html;
    }
});
</script>
@endpush
