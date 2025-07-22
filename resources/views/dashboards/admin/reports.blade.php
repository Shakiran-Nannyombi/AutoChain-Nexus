@extends('layouts.dashboard')

@section('title', 'Reports')
@push('styles')
    @vite(['resources/css/admin.css'])
    <style>
        .reports-grid {
            display: flex !important;
            flex-direction: column !important;
            gap: 2rem !important;
            width: 100% !important;
        }
        .report-card {
            width: 100% !important;
            background: var(--background) !important;
            border-radius: 14px !important;
            box-shadow: var(--shadow) !important;
            padding: 2rem !important;
            margin-bottom: 0 !important;
        }
        @media (max-width: 600px) {
            .reports-grid {
                gap: 1rem !important;
            }
            .report-card {
                padding: 1rem !important;
            }
        }
        @media (max-width: 900px) {
            .content-card.reports-page > div[style*='display: flex'] {
                flex-direction: column !important;
            }
            .content-card.reports-page section {
                margin-bottom: 2rem !important;
            }
        }
    </style>
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <div class="content-card ">
    @if(session('warning') || isset($warning))
      <div style="background: #fffbe6; color: #b45309; border: 1px solid #fde68a; border-radius: 8px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; font-size: 1.08rem; display: flex; align-items: center;">
        <i class="fas fa-exclamation-triangle" style="margin-right: 0.7rem;"></i>
        {{ session('warning') ?? $warning }}
      </div>
    @endif
    <h2 style="color: var(--text); font-size: 2rem; margin-bottom: 2.5rem; font-weight: 700; display: flex; align-items: center;"><i class="fas fa-file-alt" style="margin-right: 0.7rem;"></i> Reports</h2>
    <div style="display: flex; flex-direction: row; gap: 2.5rem; flex-wrap: wrap; width: 100%;">
        <!-- Schedule a New Report -->
      <section style="flex: 1 1 380px; min-width: 340px; background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.07); padding: 2.5rem 2rem; margin-bottom: 0;">
        <h3 style="color: black; font-size: 1.6rem; font-weight: 600; margin-bottom: 1.5rem;">Schedule a New Report</h3>
        <form action="{{ route('admin.reports.schedule') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.2rem;">
                @csrf
          <div>
            <label for="report_type" style="font-weight: 600; color: var(--primary); margin-bottom: 0.3rem; display: block;">Report Type</label>
            <select id="report_type" name="report_type" required style="width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid #e0e0e0;">
                        <option value="" disabled selected>Select a report...</option>
                        <option value="user_activity">User Activity Report</option>
                        <option value="inventory_summary">Inventory Summary</option>
                        <option value="validation_outcomes">Validation Outcomes</option>
                    </select>
                </div>
          <div>
            <label for="recipients" style="font-weight: 600; color: var(--primary); margin-bottom: 0.3rem; display: block;">Stakeholder Emails</label>
            <div style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 0.5rem;">
              <div>
                <label for="roleSelect" style="font-size: 0.95rem; color: #444;">Filter by Role</label>
                <select id="roleSelect" style="width: 160px; padding: 0.4rem; border-radius: 6px; border: 1px solid #e0e0e0;">
                  <option value="all">All Users</option>
                  @foreach($userRoles as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label for="userSelect" style="font-size: 0.95rem; color: #444;">Select Users</label>
                <select id="userSelect" multiple size="5" style="width: 100%; max-width: 100%; min-width: 0; font-size: 1.08rem; padding: 0.7rem; border-radius: 6px; border: 1px solid #e0e0e0; background: #f8fafc; box-sizing: border-box;">
                  @foreach($users as $user)
                    <option value="{{ $user->email }}" data-role="{{ $user->role }}">{{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}</option>
                  @endforeach
                </select>
              </div>
              <button type="button" id="addEmailsBtn" style="background: #82d48a; color: #fff; border: none; border-radius: 6px; padding: 0.5rem 1.2rem; font-size: 1rem; font-weight: 600; cursor: pointer;">Add</button>
            </div>
            <input type="text" id="recipients" name="recipients" placeholder="e.g., manager@example.com, ceo@example.com" required style="width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid #e0e0e0; background: #f8fafc;">
            <small style="color: #666;">Enter multiple emails separated by commas, or use the selector above.</small>
                </div>
          <div>
            <label for="frequency" style="font-weight: 600; color: var(--primary); margin-bottom: 0.3rem; display: block;">Frequency</label>
            <select id="frequency" name="frequency" required style="width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid #e0e0e0;">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
          <button type="submit" style="background: var(--primary); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 0; font-size: 1.1rem; font-weight: 600; margin-top: 0.5rem; cursor: pointer; transition: background 0.2s;">Schedule Report</button>
            </form>
      </section>

        <!-- Currently Scheduled Reports -->
      <section style="flex: 1 1 380px; min-width: 340px; background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.07); padding: 2.5rem 2rem; margin-bottom: 0;">
        <h3 style="color: black; font-size: 1.3rem; font-weight: 600; margin-bottom: 1.5rem;">Currently Scheduled Reports</h3>
        <div style="display: flex; flex-direction: column; gap: 1.2rem;">
                @forelse ($scheduledReports as $report)
            <div style="display: flex; justify-content: space-between; align-items: flex-start; background: #f8fafc; border-radius: 10px; padding: 1.2rem 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.03); max-width: 100%; box-sizing: border-box; flex-wrap: wrap; margin-bottom: 1.2rem;">
                <div style="min-width: 0; flex: 1 1 220px;">
                    <div style="font-weight: 700; color: var(--primary); font-size: 1.08rem; word-break: break-word;">{{ ucwords(str_replace('_', ' ', $report->report_type)) }}</div>
                    <div style="color: #444; font-size: 1rem; margin-top: 0.2rem; word-break: break-word;">Sending <span style="font-weight: 600; color: var(--secondary);">{{ $report->frequency }}</span> to <span style="font-weight: 600; color: #222;">{{ Str::limit($report->recipients, 80) }}</span></div>
                            </div>
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; min-width: 0;">
                    <a href="{{ route('admin.reports.view', $report->id) }}" target="_blank" style="background: #2563eb; color: #fff; border: none; border-radius: 6px; padding: 0.4rem 1.1rem; font-size: 1rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; transition: background 0.2s; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="View Report"><i class="fas fa-eye"></i> View</a>
                    <a href="{{ route('admin.reports.download', $report->id) }}" style="background: #22c55e; color: #fff; border: none; border-radius: 6px; padding: 0.4rem 1.1rem; font-size: 1rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; transition: background 0.2s; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Download Report"><i class="fas fa-download"></i> Download</a>
                    <button type="button" class="delete-report-btn" data-report-id="{{ $report->id }}" style="background: none; border: none; color: var(--danger); font-size: 1.3rem; cursor: pointer; padding: 0.3rem 0.7rem; border-radius: 6px; transition: background 0.2s;" title="Delete Report">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                </div>
                    </div>
                @empty
            <p style="color: #888; text-align: center; margin: 2rem 0;">No reports have been scheduled yet.</p>
                @endforelse
        </div>
      </section>
    </div>
  </div>
<!-- Delete Confirmation Modal -->
<div id="deleteReportModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.18); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem 2.5rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); max-width:400px; margin:auto; text-align:center;">
        <h3 style="color:#b45309; font-size:1.2rem; font-weight:700; margin-bottom:1.2rem;">Are you sure you want to delete this report?</h3>
        <div style="margin-bottom:2rem; color:#333;">This action cannot be undone.</div>
        <div style="display:flex; gap:1rem; justify-content:center;">
            <button id="cancelDeleteReport" type="button" style="padding:0.6rem 1.5rem; border-radius:6px; border:none; background:#eee; color:#222; font-weight:600; cursor:pointer;">Cancel</button>
            <form id="deleteReportForm" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="delete_report_id" id="delete_report_id" value="">
                <button type="submit" style="padding:0.6rem 1.5rem; border-radius:6px; border:none; background:var(--danger,#b91c1c); color:#fff; font-weight:600; cursor:pointer;">Delete</button>
            </form>
        </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Filter users by role
    const roleSelect = document.getElementById('roleSelect');
    const userSelect = document.getElementById('userSelect');
    const addEmailsBtn = document.getElementById('addEmailsBtn');
    const recipientsInput = document.getElementById('recipients');
    roleSelect.addEventListener('change', function () {
      const selectedRole = this.value;
      for (const option of userSelect.options) {
        option.style.display = (selectedRole === 'all' || option.getAttribute('data-role') === selectedRole) ? '' : 'none';
      }
    });
    // Add selected emails to recipients input
    addEmailsBtn.addEventListener('click', function () {
      const selected = Array.from(userSelect.selectedOptions).map(opt => opt.value);
      let current = recipientsInput.value.trim();
      if (current && !current.endsWith(',')) current += ', ';
      const toAdd = selected.filter(email => !current.includes(email));
      recipientsInput.value = current + toAdd.join(', ');
    });
  });
</script>
<script>
document.querySelectorAll('.delete-report-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('delete_report_id').value = this.getAttribute('data-report-id');
        document.getElementById('deleteReportModal').style.display = 'flex';
    });
});
document.getElementById('cancelDeleteReport').onclick = function() {
    document.getElementById('deleteReportModal').style.display = 'none';
};
window.onclick = function(event) {
    const modal = document.getElementById('deleteReportModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};
</script>
@endpush
