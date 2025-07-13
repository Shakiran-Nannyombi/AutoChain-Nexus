@extends('layouts.app')

@section('title', 'Chat')

@push('styles')
    @vite(['resources/css/chat.css'])
@endpush

@section('content')
<div class="chat-outer-card" style="height: 100vh; margin: 0 auto; box-shadow: none; border-radius: 0;">
    <!-- Sidebar: Users -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">Users</div>
        <div class="chat-search-bar">
            <input type="text" id="userSearch" placeholder="Search users...">
        </div>
        <ul class="chat-users-list" id="userList">
            @foreach($users as $user)
                <li>
                    <a href="{{ route('user.chat', ['user' => $user->id]) }}" class="{{ isset($selectedUser) && $selectedUser && $selectedUser->id == $user->id ? 'active' : '' }}">
                        @php $profilePhoto = $user->profile_photo ?? (optional($user->documents)->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                        @if($profilePhoto)
                            <img src="{{ asset($profilePhoto) }}" class="avatar" alt="avatar">
                        @elseif($user->role === 'vendor')
                            <img src="{{ asset('images/profile/vendor.jpeg') }}" class="avatar" alt="avatar">
                        @else
                            <span class="avatar">{{ strtoupper(substr($user->name ?? 'U',0,1)) }}</span>
                        @endif
                        <span class="user-name">{{ $user->name ?? 'Unknown' }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Main Chat Area -->
    <div class="chat-main">
        @if($selectedUser)
            <div class="chat-header">
                @php $profilePhoto = $selectedUser->profile_photo ?? ($selectedUser->documents->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                @if($profilePhoto)
                    <img src="{{ asset($profilePhoto) }}" class="avatar" alt="avatar">
                @elseif($selectedUser && $selectedUser->role === 'vendor')
                    <img src="{{ asset('images/profile/vendor.jpeg') }}" class="avatar" alt="avatar">
                @else
                    <span class="avatar">{{ strtoupper(substr($selectedUser->name,0,1)) }}</span>
                @endif
                <span class="user-name">{{ $selectedUser->name }}</span>
            </div>
            <div class="chat-messages" id="chatMessages">
                @forelse($messages as $msg)
                    <div class="chat-message-row {{ $msg->sender_id == (auth()->id() ?? session('user_id')) ? 'me' : '' }}" data-message-id="{{ $msg->id }}">
                        @php $msgPhoto = optional($msg->sender)->profile_photo ?? (optional(optional($msg->sender)->documents)->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                        @if($msgPhoto)
                            <img src="{{ asset($msgPhoto) }}" class="avatar" alt="avatar">
                        @elseif(optional($msg->sender)->role === 'vendor')
                            <img src="{{ asset('images/profile/vendor.jpeg') }}" class="avatar" alt="avatar">
                        @else
                            <div class="avatar">{{ strtoupper(substr(optional($msg->sender)->name ?? 'U',0,1)) }}</div>
                        @endif
                        <div class="chat-message {{ $msg->sender_id == (auth()->id() ?? session('user_id')) ? 'me' : '' }}">
                            <span class="chat-message-text">{{ $msg->message }}</span>
                            <div class="meta">{{ optional($msg->created_at)->format('H:i') }}</div>
                            @if($msg->sender_id == (auth()->id() ?? session('user_id')))
                                <button class="edit-message-btn" style="margin-left:8px;">Edit</button>
                                <button class="delete-message-btn" style="margin-left:4px; color:red;">Delete</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Demo chats if no messages -->
                    <div class="chat-message-row">
                        <div class="avatar">A</div>
                        <div class="chat-message">Hi there! How can I help you today?<div class="meta">09:00</div></div>
                    </div>
                    <div class="chat-message-row me">
                        <div class="avatar">Y</div>
                        <div class="chat-message me">I have a question about my order.<div class="meta">09:01</div></div>
                    </div>
                    <div class="chat-message-row">
                        <div class="avatar">A</div>
                        <div class="chat-message">Sure! Please provide your order number.<div class="meta">09:02</div></div>
                    </div>
                @endforelse
            </div>
            <form id="chatMessageForm" action="{{ route('chats.storeMessage', ['chat' => $selectedUser->id]) }}" method="POST" class="chat-input-area">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                <input type="text" name="message" placeholder="Type a message..." required autocomplete="off">
                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24"><path fill="currentColor" d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg></button>
            </form>
            <div id="chatMessageStatus" style="margin-top: 8px; color: red;"></div>
        @else
            <div class="flex-1 flex items-center justify-center text-gray-400 text-xl">
                Select a user to start chatting
            </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
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

// AJAX chat message send
const chatForm = document.getElementById('chatMessageForm');
const chatMessages = document.getElementById('chatMessages');
const chatStatus = document.getElementById('chatMessageStatus');
if (chatForm) {
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        chatStatus.textContent = '';
        const formData = new FormData(chatForm);
        const url = chatForm.action;
        const csrfToken = chatForm.querySelector('input[name="_token"]').value;
        const messageInput = chatForm.querySelector('input[name="message"]');
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });
            const data = await response.json();
            if (data.status === 'success') {
                // Append the new message to the chat area
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const msgRow = document.createElement('div');
                msgRow.className = 'chat-message-row me';
                msgRow.innerHTML = `
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}</div>
                    <div class="chat-message me">${data.data.message}<div class="meta">${hours}:${minutes}</div></div>
                `;
                chatMessages.appendChild(msgRow);
                messageInput.value = '';
                chatStatus.style.color = 'green';
                chatStatus.textContent = 'Message sent!';
                setTimeout(() => { chatStatus.textContent = ''; }, 2000);
            } else {
                chatStatus.style.color = 'red';
                chatStatus.textContent = data.message || 'Failed to send message.';
            }
        } catch (err) {
            chatStatus.style.color = 'red';
            chatStatus.textContent = 'Error sending message.';
        }
    });
}

// Edit and Delete message AJAX
chatMessages?.addEventListener('click', async function(e) {
    const target = e.target;
    const row = target.closest('.chat-message-row');
    if (!row) return;
    const messageId = row.getAttribute('data-message-id');
    // Edit
    if (target.classList.contains('edit-message-btn')) {
        const textSpan = row.querySelector('.chat-message-text');
        const oldText = textSpan.textContent;
        // Replace with input
        const input = document.createElement('input');
        input.type = 'text';
        input.value = oldText;
        input.style.width = '70%';
        textSpan.replaceWith(input);
        input.focus();
        // Save/cancel buttons
        const saveBtn = document.createElement('button');
        saveBtn.textContent = 'Save';
        saveBtn.style.marginLeft = '4px';
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.style.marginLeft = '4px';
        target.after(saveBtn, cancelBtn);
        target.style.display = 'none';
        // Save handler
        saveBtn.onclick = async function() {
            const url = `/chats/messages/${messageId}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content || document.querySelector('input[name="_token"]').value;
            try {
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: input.value })
                });
                const data = await response.json();
                if (data.status === 'success') {
                    const newText = document.createElement('span');
                    newText.className = 'chat-message-text';
                    newText.textContent = input.value;
                    input.replaceWith(newText);
                    saveBtn.remove();
                    cancelBtn.remove();
                    target.style.display = '';
                } else {
                    alert(data.message || 'Failed to update message.');
                }
            } catch (err) {
                alert('Error updating message.');
            }
        };
        // Cancel handler
        cancelBtn.onclick = function() {
            const newText = document.createElement('span');
            newText.className = 'chat-message-text';
            newText.textContent = oldText;
            input.replaceWith(newText);
            saveBtn.remove();
            cancelBtn.remove();
            target.style.display = '';
        };
    }
    // Delete
    if (target.classList.contains('delete-message-btn')) {
        if (!confirm('Delete this message?')) return;
        const url = `/chats/messages/${messageId}`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content || document.querySelector('input[name="_token"]').value;
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });
            const data = await response.json();
            if (data.status === 'success') {
                row.remove();
            } else {
                alert(data.message || 'Failed to delete message.');
            }
        } catch (err) {
            alert('Error deleting message.');
        }
    }
});
</script>
@endpush
@endsection 