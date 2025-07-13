<div class="delete-account-card">
    <div class="delete-account-header" style="color: red;">
        <span class="delete-account-title"><i class="fas fa-exclamation-triangle warning-icon"></i> Warning </span>
    </div>
    <div class="delete-account-warning">
      Once your account is deleted, <span class="danger-text">all of its resources and data will be permanently deleted.</span> This action cannot be undone. Please download any data or information you wish to retain before proceeding.
    </div>
    <button id="showDeleteModalBtn" class="delete-account-btn" style="color: :red;">
        <i class="fas fa-trash-alt"></i> Delete Account
    </button>
</div>

<!-- Modal Overlay -->
<div id="deleteModalOverlay" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-exclamation-circle modal-warning-icon"></i>
            <span>Are you sure you want to delete your account?</span>
        </div>
        <div class="modal-body">
            This action is <span class="danger-text">permanent</span> and cannot be undone.<br>
            Please enter your password to confirm.
        </div>
        <div class="modal-actions">
            <button type="button" id="cancelDeleteModalBtn" class="modal-cancel-btn">Cancel</button>
            <form method="post" action="{{ route('profile.destroy') }}" style="display: inline;">
                @csrf
                @method('delete')
                <input type="password" name="password" placeholder="Enter your password" required class="modal-password-input" autocomplete="current-password" />
                <button type="submit" class="modal-delete-btn" style="background: red;">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const showBtn = document.getElementById('showDeleteModalBtn');
    const modal = document.getElementById('deleteModalOverlay');
    const cancelBtn = document.getElementById('cancelDeleteModalBtn');

    showBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
    });

    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Optional: Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.style.display = 'none';
        }
    });
});
</script>
