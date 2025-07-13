@extends('layouts.dashboard')

@section('title', 'Settings')
@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-cogs"></i> Settings</h2>
  <div class="content-card settings-page">
    <!-- Tabs Navigation -->
    <div class="tabs-container">
        <div class="tab-link active" data-tab="validation">Validation</div>
        <div class="tab-link" data-tab="integrations">Integrations</div>
        <div class="tab-link" data-tab="reporting">Reporting</div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Validation Settings Tab -->
        <div id="validation" class="tab-content active">
            <div class="settings-card">
                <h3>Validation Process Settings</h3>
                <div class="form-group">
                    <label for="auto_approval_score">Auto-Approval Score</label>
                    <input type="number" name="auto_approval_score" id="auto_approval_score" value="{{ $settings['auto_approval_score'] ?? '90' }}">
                    <small>Users with a validation score at or above this value will be automatically approved.</small>
                </div>
                <div class="form-group">
                    <label for="manual_review_score">Manual Review Score</label>
                    <input type="number" name="manual_review_score" id="manual_review_score" value="{{ $settings['manual_review_score'] ?? '50' }}">
                    <small>Users with a score below this value will be flagged for manual review.</small>
                </div>
            </div>
        </div>

        <!-- Integrations Settings Tab -->
        <div id="integrations" class="tab-content">
            <div class="settings-card">
                <h3>API & Integration Settings</h3>
                <div class="form-group">
                    <label for="validation_api_url">Validation API URL</label>
                    <input type="text" name="validation_api_url" id="validation_api_url" value="{{ $settings['validation_api_url'] ?? '' }}">
                    <small>The base URL for the external user validation service.</small>
                </div>
                 <div class="form-group">
                    <label for="email_service_api_url">Email Service API URL</label>
                    <input type="text" name="email_service_api_url" id="email_service_api_url" value="{{ $settings['email_service_api_url'] ?? '' }}">
                    <small>The base URL for the external email service.</small>
                </div>
            </div>
        </div>

        <!-- Reporting Settings Tab -->
        <div id="reporting" class="tab-content">
            <div class="settings-card">
                <h3>Reporting & Notifications</h3>
                 <div class="form-group">
                    <label for="default_report_recipients">Default Report Recipients</label>
                    <input type="text" name="default_report_recipients" id="default_report_recipients" value="{{ $settings['default_report_recipients'] ?? '' }}">
                    <small>A comma-separated list of emails to be pre-filled for new reports.</small>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn-primary-full" style="margin-top: 2rem;">Save Settings</button>
    </form>
  </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Deactivate all tabs and content
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Activate clicked tab and content
                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });
    });
</script>
@endpush
