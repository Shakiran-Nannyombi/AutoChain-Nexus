@extends('layouts.dashboard')

@section('title', 'Analyst Chat')
@push('styles')
    @vite(['resources/css/analyst.css', 'resources/css/chat.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card chat-page analyst-content" style="height: 100%; display: flex; flex-direction: column; padding: 0;">
  <div class="chat-flex-container" style="display: flex; flex: 1; height: 100%; overflow: hidden;">
    <!-- Sidebar: Users -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">Users</div>
        <div class="chat-search-bar">
            <input type="text" id="userSearch" placeholder="Search users...">
        </div>
        <ul class="chat-users-list" id="userList">
            @foreach($users as $user)
                <li>
                    <a href="#" class="user-chat-link" data-user-id="{{ $user->id }}">
                        @php $profilePhoto = $user->profile_photo ?? ($user->documents->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                        @if($profilePhoto)
                            <img src="{{ asset($profilePhoto) }}" class="avatar" alt="avatar">
                        @else
                            <span class="avatar">{{ strtoupper(substr($user->name,0,1)) }}</span>
                        @endif
                        <span class="user-name">{{ $user->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Main Chat Area -->
    <div class="chat-main">
      <div id="chat-header-area" style="display:none; align-items:center; gap:16px; padding: 1.2rem 2rem 0.5rem 2rem; border-bottom: 1.5px solid var(--accent); background: var(--background); min-height: 70px;">
        <span id="chat-header-avatar"></span>
        <div>
          <div id="chat-header-name" style="font-size:1.15rem; color:var(--primary); font-weight:600;"></div>
          <div id="chat-header-status" style="font-size:0.95rem; color:#22c55e;"></div>
        </div>
      </div>
      <div id="chat-messages-area" class="chat-messages" style="height: 70vh; overflow-y: auto; padding: 2rem 2rem 1rem 2rem; background: #f8fafc; border-radius: 0 0 18px 18px;">
        <div style="margin-bottom: 2.5rem; text-align: center;">
          <svg width="80" height="80" fill="none" viewBox="0 0 24 24"><path fill="var(--primary)" d="M12 3C7.03 3 3 6.58 3 11c0 1.61.62 3.09 1.7 4.36-.23.81-.82 2.13-1.65 3.13-.18.22-.21.53-.07.78.13.25.41.37.68.29 1.52-.44 3.13-1.18 4.13-1.7C9.47 18.41 10.7 19 12 19c4.97 0 9-3.58 9-8s-4.03-8-9-8Z"/></svg>
        </div>
        <h1 style="color: var(--primary); font-size: 2.5rem; font-weight: 700; margin-bottom: 1.2rem; text-align: center;">Welcome to Analyst Chat</h1>
        <div style="font-size: 1.25rem; color: var(--text-dark); margin-bottom: 2.2rem; max-width: 520px; margin-left: auto; margin-right: auto; text-align: center;">
          Here you can communicate directly with admin or manufacturer. Select a user from the list on the left to view your conversation or start a new chat.
        </div>
        <div style="text-align: left; display: inline-block; margin-bottom: 2.2rem;">
          <div style="color: var(--secondary); font-size: 1.1rem; margin-bottom: 0.7rem;"><i class="fas fa-users"></i> Click a user to open your chat history.</div>
          <div style="color: var(--primary); font-size: 1.1rem; margin-bottom: 0.7rem;"><i class="fas fa-paper-plane"></i> Send messages, share updates, or ask for support in real time.</div>
          <div style="color: var(--accent); font-size: 1.1rem; margin-bottom: 0.7rem;"><i class="fas fa-info-circle"></i> All conversations are private and secure.</div>
        </div>
        <div style="color: var(--secondary); font-size: 1.15rem; margin-top: 2.2rem; text-align: center;">Need help? Use the chat to connect with your partners efficiently!</div>
      </div>
      <form id="chat-input-form" class="chat-input-area" style="border-radius: 0 0 18px 18px; display: none; padding: 1rem; background: #fff; border-top: 1px solid #e5e7eb;" autocomplete="off">
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
        <input type="text" id="chat-input" placeholder="Write your message..." autocomplete="off" style="flex:1; border: none; outline: none; font-size: 1rem; padding: 8px 12px;" />
        
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
let currentUserId = '{{ auth()->id() ?? session("user_id") }}';

function renderMessages(messages, currentUserId) {
    let html = '';
    messages.forEach(msg => {
        const messageId = msg.id || Math.random().toString(36).substr(2, 9);
        html += `<div class="chat-message-row${msg.is_me ? ' me' : ''}" style="margin-bottom:10px; position: relative;" data-message-id="${messageId}" data-db-id="${msg.id}">` +
            (msg.is_me ? '' : `<div class="avatar">${msg.user_initial || msg.sender_name.charAt(0)}</div>`) +
            `<div class="chat-message${msg.is_me ? ' me' : ''}" style="${msg.is_me ? 'background:#2563eb;color:#fff;' : ''} position: relative;">` +
            `<div class="message-content">${msg.message.replace(/(@\w+)/g, '<span style=\'color:#22c55e;font-weight:600;\'>$1<\/span>')}</div>` +
            `<div class="meta" style="font-size:0.85rem;color:#94a3b8;margin-top:6px;text-align:right;">${msg.is_me ? 'You' : msg.sender_name}, ${msg.created_at}</div>` +
            // Message actions (only for own messages)
            (msg.is_me ? `<div class="message-actions" style="position: absolute; top: 5px; right: 5px; display: none;">
                <button type="button" class="edit-message-btn" data-db-id="${msg.id}" style="background: none; border: none; color: inherit; font-size: 0.8rem; margin-right: 5px; cursor: pointer;" title="Edit">✏️</button>
                <button type="button" class="delete-message-btn" data-db-id="${msg.id}" style="background: none; border: none; color: inherit; font-size: 0.8rem; cursor: pointer;" title="Delete">🗑️</button>
              </div>` : '') +
            `</div>` +
            `</div>`;
    });
    return html;
}

// Real-time update handler
window.updateChatWindow = function(e) {
    if (selectedUserId && (e.message.sender_id == selectedUserId || e.message.receiver_id == selectedUserId)) {
        // Reload messages for the selected user
        document.querySelector(`.user-chat-link[data-user-id='${selectedUserId}']`).click();
    }
};

// Notification handler
window.showChatNotification = function(e) {
    if (e.message.receiver_id == currentUserId) {
        // Show a notification badge or popup
        alert('New message from ' + e.sender.name + ': ' + e.message.message);
    }
};

function showChatHeader(user) {
    const header = document.getElementById('chat-header-area');
    const avatar = document.getElementById('chat-header-avatar');
    const name = document.getElementById('chat-header-name');
    const status = document.getElementById('chat-header-status');
    if (user) {
        header.style.display = 'flex';
        if (user.avatar) {
            avatar.innerHTML = `<img src="${user.avatar}" class="avatar" style="width:44px;height:44px;" alt="avatar">`;
        } else {
            avatar.innerHTML = `<div class="avatar" style="width:44px;height:44px;">${user.name.charAt(0)}</div>`;
        }
        name.textContent = user.name;
        status.textContent = user.status || 'online';
    } else {
        header.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // User search filter
    const userSearch = document.getElementById('userSearch');
    const userList = document.getElementById('userList');
    userSearch?.addEventListener('input', function() {
        const val = this.value.toLowerCase();
        userList.querySelectorAll('li').forEach(li => {
            const name = li.querySelector('.user-name').textContent.toLowerCase();
            li.style.display = name.includes(val) ? '' : 'none';
        });
    });

    // Emoji picker functionality
    const emojiBtn = document.getElementById('emoji-btn');
    const emojiPicker = document.getElementById('emoji-picker');
    const chatInput = document.getElementById('chat-input');
    const fileUploadBtn = document.getElementById('file-upload-btn');
    const sendBtn = document.getElementById('send-btn');

    emojiBtn?.addEventListener('click', function() {
        emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'block' : 'none';
    });
    
    // Close emoji picker when clicking outside
    document.addEventListener('click', function(e) {
        if (!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
            emojiPicker.style.display = 'none';
        }
    });
    
    // Emoji button clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('emoji-btn')) {
            const emoji = e.target.getAttribute('data-emoji');
            const cursorPos = chatInput.selectionStart;
            const textBefore = chatInput.value.substring(0, cursorPos);
            const textAfter = chatInput.value.substring(cursorPos);
            chatInput.value = textBefore + emoji + textAfter;
            chatInput.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
            chatInput.focus();
            emojiPicker.style.display = 'none';
        }
    });

    // File upload functionality
    if (fileUploadBtn && fileInput) {
        fileUploadBtn.addEventListener('click', function() {
            fileInput.click();
        });
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // For now, we'll just add the filename to the message
                // In a real implementation, you'd upload the file to the server
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(1); // KB
                const fileType = file.type.split('/')[0];
                let fileIcon = '📎';
                if (fileType === 'image') fileIcon = '🖼️';
                else if (fileType === 'application') fileIcon = '📄';
                const fileMessage = `${fileIcon} ${fileName} (${fileSize} KB)`;
                if (chatInput) chatInput.value = fileMessage;
                // Show preview for images
                if (fileType === 'image') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You could show a preview here
                        console.log('Image preview:', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }

    // Message hover effects and actions
    document.addEventListener('mouseover', function(e) {
        if (e.target.closest('.chat-message-row')) {
            const messageRow = e.target.closest('.chat-message-row');
            const actions = messageRow.querySelector('.message-actions');
            if (actions) {
                actions.style.display = 'block';
            }
        }
    });
    
    document.addEventListener('mouseout', function(e) {
        if (e.target.closest('.chat-message-row')) {
            const messageRow = e.target.closest('.chat-message-row');
            const actions = messageRow.querySelector('.message-actions');
            if (actions) {
                actions.style.display = 'none';
            }
        }
    });

    // Message editing functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-message-btn')) {
            const messageRow = e.target.closest('.chat-message-row');
            const messageContent = messageRow.querySelector('.message-content');
            const currentText = messageContent.textContent;
            const dbId = e.target.getAttribute('data-db-id');
            // Create edit input
            const editInput = document.createElement('input');
            editInput.type = 'text';
            editInput.value = currentText;
            editInput.style.cssText = 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 8px;';
            // Create save/cancel buttons
            const saveBtn = document.createElement('button');
            saveBtn.textContent = 'Save';
            saveBtn.style.cssText = 'background: var(--primary); color: white; border: none; padding: 4px 8px; border-radius: 4px; margin-right: 5px; cursor: pointer;';
            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Cancel';
            cancelBtn.style.cssText = 'background: #6b7280; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;';
            // Replace content with edit form
            messageContent.innerHTML = '';
            messageContent.appendChild(editInput);
            messageContent.appendChild(saveBtn);
            messageContent.appendChild(cancelBtn);
            editInput.focus();
            // Save functionality
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
            // Cancel functionality
            cancelBtn.addEventListener('click', function() {
                messageContent.innerHTML = currentText;
            });
        }
    });

    // Message deletion functionality
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

    // Chat user selection
    document.querySelectorAll('.user-chat-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            selectedUserId = this.getAttribute('data-user-id');
            const chatArea = document.getElementById('chat-messages-area');
            chatArea.innerHTML = '<div style="padding:2rem; text-align:center;">Loading chat...</div>';
            fetch(`/analyst/chat/messages/${selectedUserId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        selectedUserData = data.user;
                        showChatHeader(selectedUserData);
                        chatArea.innerHTML = renderMessages(data.messages, data.current_user_id);
                        document.getElementById('chat-input-form').style.display = 'flex';
                    } else {
                        showChatHeader(null);
                        chatArea.innerHTML = `<div style='color:red;padding:2rem;'>${data.message || 'Failed to load chat.'}</div>`;
                        document.getElementById('chat-input-form').style.display = 'none';
                    }
                })
                .catch(() => {
                    showChatHeader(null);
                    chatArea.innerHTML = '<div style="color:red;padding:2rem;">Error loading chat.</div>';
                    document.getElementById('chat-input-form').style.display = 'none';
                });
        });
    });

    // Send message
    const chatInputForm = document.getElementById('chat-input-form');
    if (chatInputForm) {
        chatInputForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            if (!message || !selectedUserId) return;
            // Disable send button while sending
            const sendBtn = document.getElementById('send-btn');
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            fetch('/analyst/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ receiver_id: selectedUserId, message })
            })
            .then(res => res.json())
            .then((data) => {
                if (data.status === 'success') {
                    // Reload messages
                    document.querySelector(`.user-chat-link[data-user-id='${selectedUserId}']`).click();
                    input.value = '';
                } else {
                    alert(data.message || 'Failed to send message.');
                }
            })
            .catch(() => {
                alert('Error sending message.');
            })
            .finally(() => {
                // Re-enable send button
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            });
        });
    }

    // Button hover effects
    if (emojiBtn) {
        emojiBtn.addEventListener('mouseenter', function() {
            this.style.color = '#666';
        });
        emojiBtn.addEventListener('mouseleave', function() {
            this.style.color = '#888';
        });
    }
    if (fileUploadBtn) {
        fileUploadBtn.addEventListener('mouseenter', function() {
            this.style.color = '#666';
        });
        fileUploadBtn.addEventListener('mouseleave', function() {
            this.style.color = '#888';
        });
    }
    if (sendBtn) {
        sendBtn.addEventListener('mouseenter', function() {
            this.style.color = '#1e40af';
        });
        sendBtn.addEventListener('mouseleave', function() {
            this.style.color = 'white';
        });
    }
});
</script>
@endpush