@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@push('styles')
    @vite(['resources/css/vendor.css', 'resources/css/chat.css'])
    <style>
      .chat-message-row.me .message-actions { display: none; }
      .chat-message-row.me:hover .message-actions { display: block; }
    </style>
@endpush

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card chat-page" style="height: 100%; display: flex; flex-direction: column; padding: 0;">
  <div class="chat-flex-container" style="display: flex; flex: 1; height: 100%; overflow: hidden;">
    <!-- Sidebar: Users -->
    <div class="chat-sidebar" style="background: #f3f4f6; border-right: 1.5px solid #e5e7eb; min-width: 260px; max-width: 320px; padding: 0; display: flex; flex-direction: column;">
        <div class="chat-sidebar-header" style="font-size: 1.2rem; font-weight: 700; color: var(--primary); padding: 1.2rem 1.5rem 0.5rem 1.5rem; border-bottom: 1.5px solid #e5e7eb; background: #fff;">Users</div>
        <div class="chat-search-bar" style="padding: 1rem 1.5rem 0.5rem 1.5rem; background: #fff; border-bottom: 1.5px solid #e5e7eb;">
            <input type="text" id="userSearch" placeholder="Search users..." style="width: 100%; padding: 0.6rem 1rem; border-radius: 18px; border: 1px solid #e5e7eb; font-size: 1rem;">
        </div>
        <ul class="chat-users-list" id="userList" style="flex: 1; overflow-y: auto; padding: 0; margin: 0; background: #f8fafc;">
            @foreach($users as $user)
                <li data-role="{{ $user->role }}" style="list-style: none;">
                    <a href="#" class="user-chat-link" data-user-id="{{ $user->id }}" style="display: flex; align-items: center; gap: 12px; padding: 0.85rem 1.5rem; border-bottom: 1px solid #f3f4f6; text-decoration: none; color: #222; transition: background 0.2s; border-radius: 0;">
                        @php $profilePhoto = $user->profile_picture ?? ($user->documents->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                        @if($profilePhoto)
                            <img src="{{ asset($profilePhoto) }}" class="avatar" alt="avatar" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/profile/default.png') }}" class="avatar" alt="avatar" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                        @endif
                        <div style="flex: 1;">
                            <span class="user-name" style="font-weight: 600; font-size: 1.05rem;">{{ $user->name }}</span>
                            @if($user->role === 'admin')
                                <div style="font-size: 0.85rem; color: #fbbf24; font-weight: 600;">Administrator</div>
                            @endif
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Main Chat Area -->
    <div class="chat-main" style="flex: 1; display: flex; flex-direction: column; background: #f8fafc;">
      <div id="chat-header-area" style="display:none; align-items:center; gap:16px; padding: 1.2rem 2rem 0.5rem 2rem; border-bottom: 1.5px solid #e5e7eb; background: #fff; min-height: 70px;">
        <span id="chat-header-avatar"></span>
        <div>
          <div id="chat-header-name" style="font-size:1.15rem; color:var(--primary); font-weight:600;"></div>
          <div id="chat-header-status" style="font-size:0.95rem; color:#22c55e;"></div>
        </div>
      </div>
      <div id="chat-messages-area" class="chat-messages" style="flex: 1; overflow-y: auto; padding: 2rem 2rem 1rem 2rem; background: #f8fafc; border-radius: 0 0 18px 18px;">
        <div id="welcome-message" style="margin-bottom: 2.5rem; text-align: center;">
          <svg width="80" height="80" fill="none" viewBox="0 0 24 24"><path fill="#166534" d="M12 3C7.03 3 3 6.58 3 11c0 1.61.62 3.09 1.7 4.36-.23.81-.82 2.13-1.65 3.13-.18.22-.21.53-.07.78.13.25.41.37.68.29 1.52-.44 3.13-1.18 4.13-1.7C9.47 18.41 10.7 19 12 19c4.97 0 9-3.58 9-8s-4.03-8-9-8Z"/></svg>
          <h1 style="color: #166534; font-size: 2.5rem; font-weight: 700; margin-bottom: 1.2rem; text-align: center;">Welcome to Vendor Chat</h1>
          <div style="font-size: 1.25rem; color: #222; margin-bottom: 2.2rem; max-width: 520px; margin-left: auto; margin-right: auto; text-align: center;">
            Here you can communicate directly with admin, manufacturer, analyst, or retailer. Select a user from the list on the left to view your conversation or start a new chat.
          </div>
          <div style="text-align: left; display: inline-block; margin-bottom: 2.2rem;">
            <div style="color: #f97a00; font-size: 1.1rem; margin-bottom: 0.7rem;"><i class="fas fa-users"></i> Click a user to open your chat history.</div>
            <div style="color: #166534; font-size: 1.1rem; margin-bottom: 0.7rem;"><i class="fas fa-paper-plane"></i> Send messages, share updates, or ask for support in real time.</div>
            <div style="color: #fbbf24; font-size: 1.1rem; margin-bottom: 0.7rem;"><i class="fas fa-info-circle"></i> All conversations are private and secure.</div>
          </div>
          <div style="color: #f97a00; font-size: 1.15rem; margin-top: 2.2rem; text-align: center;">Need help? Use the chat to connect with your partners efficiently!</div>
        </div>
        <div id="demo-chats" style="display:none;">
          <!-- Demo chat for Manufacturer -->
          <div class="demo-chat" data-role="manufacturer" style="display:none;">
            <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
              <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">M</span></div>
              <div style="background: #e3f0ff; color: #166534; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Hi Vendor, can you confirm the ETA for the next batch of parts?
                <div style="font-size: 0.95rem; color: #6b7280; margin-top: 0.5rem; text-align: right;">Manufacturer, 10:15</div>
              </div>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
              <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Yes, the shipment will arrive by Thursday morning.
                <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, 10:16</div>
              </div>
              <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">V</span></div>
            </div>
            <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
              <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">M</span></div>
              <div style="background: #e3f0ff; color: #166534; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Perfect, thanks for the update!
                <div style="font-size: 0.95rem; color: #6b7280; margin-top: 0.5rem; text-align: right;">Manufacturer, 10:17</div>
              </div>
            </div>
          </div>
          <!-- Demo chat for Admin -->
          <div class="demo-chat" data-role="admin" style="display:none;">
            <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
              <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #fbbf24; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">J</span></div>
              <div style="background: #fff7e6; color: #b35400; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Hello! I need to discuss some compliance requirements for our upcoming audit.
                <div style="font-size: 0.95rem; color: #b35400; margin-top: 0.5rem; text-align: right;">John Mayanja, 09:00</div>
              </div>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
              <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Of course! I'm here to help. What specific compliance areas do you need assistance with?
                <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, 09:01</div>
              </div>
              <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">V</span></div>
            </div>
            <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
              <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #fbbf24; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">J</span></div>
              <div style="background: #fff7e6; color: #b35400; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                We need to ensure our documentation meets the new regulatory standards. Can you guide us through the requirements?
                <div style="font-size: 0.95rem; color: #b35400; margin-top: 0.5rem; text-align: right;">John Mayanja, 09:02</div>
              </div>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
              <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Absolutely! I'll send you the updated compliance checklist and schedule a review meeting for next week.
                <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, 09:03</div>
              </div>
              <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">V</span></div>
            </div>
          </div>
          <!-- Demo chat for Analyst -->
          <div class="demo-chat" data-role="analyst" style="display:none;">
            <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
              <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #bae6fd; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">AN</span></div>
              <div style="background: #e0f2fe; color: #0369a1; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Vendor, can you provide last month’s sales data for analysis?
                <div style="font-size: 0.95rem; color: #0369a1; margin-top: 0.5rem; text-align: right;">Analyst, 11:20</div>
              </div>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
              <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Sure, I’ll upload the report shortly.
                <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, 11:21</div>
              </div>
              <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">V</span></div>
            </div>
          </div>
          <!-- Demo chat for Retailer -->
          <div class="demo-chat" data-role="retailer" style="display:none;">
            <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
              <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #fca5a5; color: #991b1b; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">R</span></div>
              <div style="background: #fef2f2; color: #991b1b; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Hi Vendor, do you have the new product catalog?
                <div style="font-size: 0.95rem; color: #991b1b; margin-top: 0.5rem; text-align: right;">Retailer, 14:05</div>
              </div>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
              <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Yes, I’ll send it to you now.
                <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, 14:06</div>
              </div>
              <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">V</span></div>
            </div>
          </div>
        </div>
      </div>
      <form id="chat-input-form" class="chat-input-area" style="border-radius: 0 0 18px 18px; display: none; padding: 1rem; background: #fff; border-top: 1px solid #e5e7eb; position: sticky; bottom: 0; z-index: 10; box-shadow: 0 -2px 8px rgba(0,0,0,0.03);">
        <!-- Emoji Picker Button -->
        <button type="button" id="emoji-btn" style="background: none; border: none; color: #888; font-size: 1.3rem; margin-right: 8px; cursor: pointer; transition: color 0.2s;" title="Add emoji">
          <i class="far fa-smile"></i>
        </button>
        <!-- File Upload Button -->
        <button type="button" id="file-upload-btn" style="background: none; border: none; color: #888; font-size: 1.3rem; margin-right: 8px; cursor: pointer; transition: color 0.2s;" title="Attach file">
          <i class="fas fa-paperclip"></i>
        </button>
        <!-- Hidden file input -->
        <input type="file" id="file-input" style="display: none;" accept="image/*,.pdf,.doc,.docx,.txt,.xlsx,.xls">
        <!-- Message Input -->
        <input type="text" id="chat-input" placeholder="Write your message..." autocomplete="off" style="flex:1; border: none; outline: none; font-size: 1rem; padding: 8px 12px; background: #f8fafc; border-radius: 18px; margin-right: 8px;" />
        <!-- Send Button -->
        <button type="submit" id="send-btn" style="background: var(--primary); border: none; color: white; padding: 8px 16px; border-radius: 20px; cursor: pointer; transition: background 0.2s;" title="Send message">
          <i class="fas fa-paper-plane"></i>
        </button>
      </form>
      <!-- Emoji Picker (hidden by default) -->
      <div id="emoji-picker" style="display: none; position: absolute; bottom: 80px; left: 50%; transform: translateX(-50%); background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; max-width: 300px;">
        <div style="display: grid; grid-template-columns: repeat(8, 1fr); gap: 5px;">
          <button type="button" class="emoji-btn" data-emoji="😊">😊</button>
          <button type="button" class="emoji-btn" data-emoji="😂">😂</button>
          <button type="button" class="emoji-btn" data-emoji="❤️">❤️</button>
          <button type="button" class="emoji-btn" data-emoji="👍">👍</button>
          <button type="button" class="emoji-btn" data-emoji="👎">👎</button>
          <button type="button" class="emoji-btn" data-emoji="🎉">🎉</button>
          <button type="button" class="emoji-btn" data-emoji="🔥">🔥</button>
          <button type="button" class="emoji-btn" data-emoji="💯">💯</button>
          <button type="button" class="emoji-btn" data-emoji="✅">✅</button>
          <button type="button" class="emoji-btn" data-emoji="❌">❌</button>
          <button type="button" class="emoji-btn" data-emoji="⚠️">⚠️</button>
          <button type="button" class="emoji-btn" data-emoji="💡">💡</button>
          <button type="button" class="emoji-btn" data-emoji="📝">📝</button>
          <button type="button" class="emoji-btn" data-emoji="📎">📎</button>
          <button type="button" class="emoji-btn" data-emoji="📞">📞</button>
          <button type="button" class="emoji-btn" data-emoji="📧">📧</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let selectedUserId = null;
let selectedUserData = null;
let currentUserId = '{{ auth()->id() ?? session('user_id') }}';

function formatTimestamp(ts) {
  if (!ts) return '';
  // Try to parse ISO or fallback
  const d = new Date(ts);
  if (isNaN(d)) return ts;
  return d.toLocaleString(undefined, {
    year: 'numeric', month: 'short', day: 'numeric',
    hour: '2-digit', minute: '2-digit', hour12: true
  });
}

function renderMessages(messages, currentUserId) {
    let html = '';
    messages.forEach(msg => {
        const messageId = msg.id || Math.random().toString(36).substr(2, 9);
        html += `<div class="chat-message-row${msg.is_me ? ' me' : ''}" style="margin-bottom:10px; position: relative;" data-message-id="${messageId}" data-db-id="${msg.id}">` +
            (msg.is_me ? '' : `<div class="avatar">${msg.user_initial || msg.sender_name?.charAt(0) || ''}</div>`) +
            `<div class="chat-message${msg.is_me ? ' me' : ''}" style="${msg.is_me ? 'background:#2563eb;color:#fff;' : ''} position: relative;">` +
            `<div class="message-content">${msg.message.replace(/(@\w+)/g, '<span style=\'color:#22c55e;font-weight:600;\'>$1<\/span>')}</div>` +
            `<div class="meta" style="font-size:0.85rem;color:#94a3b8;margin-top:6px;text-align:right;">${msg.is_me ? 'You' : msg.sender_name}, ${formatTimestamp(msg.created_at)}</div>` +
            (msg.is_me ? `<div class="message-actions" style="position: absolute; top: 5px; right: 5px;">` +
                `<button type="button" class="edit-message-btn" data-db-id="${msg.id}" style="background: none; border: none; color: inherit; font-size: 0.8rem; margin-right: 5px; cursor: pointer;" title="Edit">✏️</button>` +
                `<button type="button" class="delete-message-btn" data-db-id="${msg.id}" style="background: none; border: none; color: inherit; font-size: 0.8rem; cursor: pointer;" title="Delete">🗑️</button>` +
              `</div>` : '') +
            `</div>` +
            `</div>`;
    });
    return html;
}

window.updateChatWindow = function(e) {
    if (selectedUserId && (e.message.sender_id == selectedUserId || e.message.receiver_id == selectedUserId)) {
        document.querySelector(`.user-chat-link[data-user-id='${selectedUserId}']`).click();
    }
};
window.showChatNotification = function(e) {
    if (e.message.receiver_id == currentUserId) {
        alert('New message from ' + e.sender.name + ': ' + e.message.message);
    }
};
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-message-btn')) {
        const messageRow = e.target.closest('.chat-message-row');
        const messageContent = messageRow.querySelector('.message-content');
        const currentText = messageContent.textContent;
        const dbId = e.target.getAttribute('data-db-id');
        const editInput = document.createElement('input');
        editInput.type = 'text';
        editInput.value = currentText;
        editInput.style.cssText = 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 8px;';
        const saveBtn = document.createElement('button');
        saveBtn.textContent = 'Save';
        saveBtn.style.cssText = 'background: var(--primary); color: white; border: none; padding: 4px 8px; border-radius: 4px; margin-right: 5px; cursor: pointer;';
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.style.cssText = 'background: #6b7280; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;';
        messageContent.innerHTML = '';
        messageContent.appendChild(editInput);
        messageContent.appendChild(saveBtn);
        messageContent.appendChild(cancelBtn);
        editInput.focus();
        saveBtn.addEventListener('click', function() {
            const newText = editInput.value.trim();
            if (newText && dbId) {
                fetch(`/chats/messages/${dbId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: newText })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        messageContent.innerHTML = newText;
                    } else {
                        alert(data.message || 'Failed to update message.');
                        messageContent.innerHTML = currentText;
                    }
                })
                .catch(() => {
                    alert('Error updating message.');
                    messageContent.innerHTML = currentText;
                });
            }
        });
        cancelBtn.addEventListener('click', function() {
            messageContent.innerHTML = currentText;
        });
    }
});
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-message-btn')) {
        if (confirm('Are you sure you want to delete this message?')) {
            const messageRow = e.target.closest('.chat-message-row');
            const dbId = e.target.getAttribute('data-db-id');
            if (dbId) {
                fetch(`/chats/messages/${dbId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        messageRow.remove();
                    } else {
                        alert(data.message || 'Failed to delete message.');
                    }
                })
                .catch(() => {
                    alert('Error deleting message.');
                });
            }
        }
    }
});
const userList = document.getElementById('userList');
const demoChats = document.getElementById('demo-chats');
const welcomeMessage = document.getElementById('welcome-message');
const chatHeaderArea = document.getElementById('chat-header-area');
const chatInputForm = document.getElementById('chat-input-form');
const emojiBtn = document.getElementById('emoji-btn');
const emojiPicker = document.getElementById('emoji-picker');
const chatInput = document.getElementById('chat-input');
const fileUploadBtn = document.getElementById('file-upload-btn');
const fileInput = document.getElementById('file-input');
const chatMessagesArea = document.getElementById('chat-messages-area');

if (userList) {
  userList.addEventListener('click', function(e) {
    const link = e.target.closest('.user-chat-link');
    if (!link) return;
    e.preventDefault();
    const userId = link.getAttribute('data-user-id');
    const role = link.closest('li').getAttribute('data-role');
    const name = link.querySelector('.user-name')?.textContent || '';
    const avatarEl = link.querySelector('.avatar');
    selectedUserId = userId;
    // Show header and input
    chatHeaderArea.style.display = 'flex';
    chatInputForm.style.display = 'flex';
    // Update header avatar/name/status
    document.getElementById('chat-header-name').textContent = name;
    const headerAvatar = document.getElementById('chat-header-avatar');
    headerAvatar.innerHTML = '';
    if (avatarEl) headerAvatar.appendChild(avatarEl.cloneNode(true));
    document.getElementById('chat-header-status').textContent = 'Online';
    // Fetch real messages
    fetch(`/chats/messages/${userId}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success' && data.messages && data.messages.length > 0) {
          // Hide demo chats, show real messages
          demoChats.style.display = 'none';
          welcomeMessage.style.display = 'none';
          chatMessagesArea.innerHTML = `<div id='real-messages'>${renderMessages(data.messages, currentUserId)}</div>`;
        } else {
          // Show demo chat for this role
          chatMessagesArea.innerHTML = '';
          demoChats.querySelectorAll('.demo-chat').forEach(dc => dc.style.display = 'none');
          if (role && demoChats.querySelector('.demo-chat[data-role="' + role + '"]')) {
            demoChats.style.display = '';
            welcomeMessage.style.display = 'none';
            demoChats.querySelector('.demo-chat[data-role="' + role + '"]').style.display = '';
          } else {
            demoChats.style.display = 'none';
            welcomeMessage.style.display = '';
          }
        }
      })
      .catch((error) => {
        console.error('Error loading messages:', error);
        chatMessagesArea.innerHTML = '<div style="color:red;">Failed to load messages.</div>';
      });
  });
}
// Hide chat header and input by default
if (chatHeaderArea) chatHeaderArea.style.display = 'none';
if (chatInputForm) chatInputForm.style.display = 'none';

// Send message AJAX
if (chatInputForm) {
  chatInputForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!selectedUserId || !chatInput.value.trim()) return;
    const message = chatInput.value.trim();
    fetch('/chats/send', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ receiver_id: selectedUserId, message })
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success' && data.data) {
        // After sending, reload messages for this user
        fetch(`/chats/messages/${selectedUserId}`)
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success' && data.messages) {
              demoChats.style.display = 'none';
              welcomeMessage.style.display = 'none';
              chatMessagesArea.innerHTML = `<div id='real-messages'>${renderMessages(data.messages, currentUserId)}</div>`;
            }
          });
        chatInput.value = '';
      } else {
        alert(data.message || 'Failed to send message.');
      }
    })
    .catch(() => {
      alert('Error sending message.');
    });
  });
}
// Emoji picker functionality
if (emojiBtn && emojiPicker) {
  emojiBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'block' : 'none';
  });
}
document.addEventListener('click', function(e) {
  if (emojiPicker && !emojiPicker.contains(e.target) && emojiBtn && !emojiBtn.contains(e.target)) {
    emojiPicker.style.display = 'none';
  }
});
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('emoji-btn')) {
    const emoji = e.target.getAttribute('data-emoji');
    if (chatInput) {
      const cursorPos = chatInput.selectionStart;
      const textBefore = chatInput.value.substring(0, cursorPos);
      const textAfter = chatInput.value.substring(cursorPos);
      chatInput.value = textBefore + emoji + textAfter;
      chatInput.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
      chatInput.focus();
    }
    if (emojiPicker) emojiPicker.style.display = 'none';
  }
});
// File upload functionality
if (fileUploadBtn && fileInput) {
  fileUploadBtn.addEventListener('click', function() {
    fileInput.click();
  });
  fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file && chatInput) {
      const fileName = file.name;
      const fileSize = (file.size / 1024).toFixed(1); // KB
      const fileType = file.type.split('/')[0];
      let fileIcon = '📎';
      if (fileType === 'image') fileIcon = '🖼️';
      else if (fileType === 'application') fileIcon = '📄';
      const fileMessage = `${fileIcon} ${fileName} (${fileSize} KB)`;
      chatInput.value = fileMessage;
    }
  });
}
// Prevent form submit from reloading page (demo only)
if (chatInputForm) {
  chatInputForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (chatInput) chatInput.value = '';
  });
}
</script>
@endpush