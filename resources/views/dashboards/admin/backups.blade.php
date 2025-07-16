@extends('layouts.dashboard')

@section('title', 'Data Backup & Recovery')

@push('styles')
    @vite(['resources/css/admin.css'])
    <style>
    /* Remove background from backup item container */
    .backup-item {
        background: transparent !important;
        /* If there's a box-shadow or border-radius you want to keep, add them here */
    }
    </style>
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <div class="content-card">
    <h2 style="color: var(--primary) !important; font-size: 2rem; font-weight: bold; margin-bottom: 1.5rem;"><i class="fas fa-database"></i> Backups</h2>
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ session('error') }}</div>
    @endif

    <!-- Backup Overview Cards -->
    <div class="stats-container mb-8">
        <div class="stat-card">
            <div>
                <div class="stat-title">Total Backups</div>
                <div class="stat-value">{{ count($backups) }}</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-database"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Latest Backup</div>
                <div class="stat-value">{{ count($backups) > 0 ? $backups[0]['date']->format('M d') : 'None' }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Storage Used</div>
                <div class="stat-value">{{ app('App\Http\Controllers\Admin\BackupController')->calculateTotalSize($backups) }}</div>
            </div>
            <div class="stat-icon yellow">
                <i class="fas fa-hdd"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-title">Auto Backup</div>
                <div class="stat-value">Daily</div>
            </div>
            <div class="stat-icon light-green">
                <i class="fas fa-robot"></i>
            </div>
        </div>
    </div>

    <!-- What is being backed up -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <h2 style="font-size: 1.5rem; color: var(--secondary);">What Gets Backed Up?</h2>
        </div>
        <div class="card-body">
            <ul style="margin-bottom:1rem; font-size: 1.1rem;">
                <li><strong>Database:</strong> All tables and data in your main database.</li>
                <li><strong>Application Files:</strong> All code, configuration, and resources (excluding <code>vendor/</code> and <code>node_modules/</code>).</li>
                <li><strong>Storage Files:</strong> Uploaded files and user-generated content.</li>
            </ul>
            <form action="{{ route('admin.backups.create') }}" method="POST" style="margin-bottom:0;">
                @csrf
                <div style="display:flex; flex-wrap:wrap; gap:2rem; align-items:center; margin-bottom:1rem;">
                    <label><input type="checkbox" name="backup_database" value="1" checked> Database</label>
                    <label><input type="checkbox" name="backup_files" value="1" checked> Application Files</label>
                    <label><input type="checkbox" name="backup_storage" value="1" checked> Storage Files</label>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-plus-circle"></i> Create Backup Now
                </button>
            </form>
            <div style="margin-top:0.7rem; color:#64748b; font-size:0.98rem;">
                <i class="fas fa-info-circle"></i> Unchecked items will be excluded from this backup only.
            </div>
        </div>
    </div>

    <!-- Storage Usage Progress Bar -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <h2 style="font-size: 1.5rem;">Backup Storage Usage</h2>
        </div>
        <div class="card-body">
            <div style="margin-bottom:0.7rem; color:#64748b;">Total: 1.5G &nbsp; Used: 160K &nbsp; Available: 1.5G</div>
            <div style="background:#e5e7eb; border-radius:8px; height:22px; width:100%; overflow:hidden;">
                <div style="background:linear-gradient(90deg,#2563eb,#22d3ee); width:1%; height:100%; border-radius:8px;"></div>
            </div>
            <div style="margin-top:0.3rem; color:#2563eb; font-weight:600;">1% used</div>
        </div>
    </div>

    <!-- Backup List -->
    <div class="card">
        <div class="card-header">
            <h2 style="font-size: 1.5rem;">Available Backups</h2>
        </div>
        <div class="card-body">
            @if(count($backups) > 0)
                <div class="backup-list">
                    @foreach($backups as $backup)
                        <div class="backup-item">
                            <div class="backup-info">
                                <div class="backup-header">
                                    <h3 class="backup-name">{{ $backup['filename'] }}</h3>
                                    <span class="backup-date">{{ $backup['date']->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                                <div class="backup-details">
                                    <span class="backup-size"><i class="fas fa-file-archive"></i> {{ $backup['size'] }}</span>
                                    <span class="backup-age">
                                        <i class="fas fa-clock"></i> 
                                        {{ $backup['date']->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="backup-actions">
                                <a href="{{ route('admin.backups.download', $backup['filename']) }}" 
                                   class="btn-action btn-download" title="Download Backup">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" 
                                        class="btn-action btn-restore" 
                                        onclick="openRestoreModal('{{ $backup['filename'] }}')"
                                        title="Restore from Backup">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <form action="{{ route('admin.backups.delete', $backup['filename']) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Are you sure you want to delete this backup? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete Backup">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-database" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <h3>No Backups Found</h3>
                    <p>No backup files have been created yet. Create your first backup to get started.</p>
                </div>
            @endif
        </div>
    </div>
  </div>

<!-- Restore Confirmation Modal -->
<div id="restoreModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Restore from Backup</h3>
            <span class="close" onclick="closeRestoreModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="restore-warning">
                <i class="fas fa-exclamation-triangle" style="color: #d97706; font-size: 2rem; margin-bottom: 1rem;"></i>
                <h4>Warning: This action will overwrite your current data!</h4>
                <p>Restoring from a backup will replace all current data with the data from the selected backup. This action cannot be undone.</p>
                <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                    <li>All current data will be lost</li>
                    <li>The system will be temporarily unavailable during restore</li>
                    <li>Make sure you have a recent backup before proceeding</li>
                </ul>
            </div>
            <form id="restoreForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="confirmation">Type "yes" to confirm the restore operation:</label>
                    <input type="text" id="confirmation" name="confirmation" required 
                           placeholder="Type 'yes' to confirm" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-danger">Restore from Backup</button>
                    <button type="button" class="btn-secondary" onclick="closeRestoreModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRestoreModal(filename) {
    const modal = document.getElementById('restoreModal');
    const form = document.getElementById('restoreForm');
    form.action = `{{ url('admin/backups') }}/${filename}/restore`;
    modal.classList.add('show');
}

function closeRestoreModal() {
    document.getElementById('restoreModal').classList.remove('show');
    document.getElementById('confirmation').value = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('restoreModal');
    if (event.target === modal) {
        closeRestoreModal();
    }
}
</script>
@endpush 