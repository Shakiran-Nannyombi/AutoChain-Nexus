@extends('layouts.dashboard')

@section('title', 'Chat')
@push('styles')
    @vite(['resources/css/admin.css', 'resources/css/chat.css'])
@endpush

@push('scripts')
<script>
window.updateChatWindow = function(event) {
    // If the selected user is the sender, append the new message
    const selectedUserId = '{{ $selectedUser->id ?? '' }}';
    if (event.sender_id == selectedUserId) {
        const chatArea = document.querySelector('.overflow-y-auto.p-4');
        if (chatArea) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'flex justify-start';
            msgDiv.innerHTML = `<div class="max-w-xs px-4 py-2 rounded-lg bg-gray-200 text-black">${event.message}<div class="text-xs text-gray-400 mt-1 text-right">now</div></div>`;
            chatArea.appendChild(msgDiv);
            chatArea.scrollTop = chatArea.scrollHeight;
        }
    }
};
window.showChatNotification = function(event) {
    // Simple notification popup
    const notif = document.createElement('div');
    notif.className = 'fixed top-4 right-4 bg-blue-900 text-white px-4 py-2 rounded shadow-lg z-50';
    notif.innerText = `New message from ${event.sender_name}`;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 4000);
};
</script>
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <div class="content-card">
    <h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-comments"></i> Admin Chat</h2>
<div class="chat-outer-card">
    <!-- Sidebar: Users -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">Users</div>
        <div class="chat-search-bar">
            <input type="text" id="userSearch" placeholder="Search users...">
        </div>
        <ul class="chat-users-list" id="userList">
            @foreach($users as $user)
                <li>
                    <a href="{{ route('admin.chat', ['user' => $user->id]) }}" class="{{ isset($selectedUser) && $selectedUser && $selectedUser->id == $user->id ? 'active' : '' }}">
                        @php $profilePhoto = $user->profile_photo ?? ($user->documents->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                        @if($profilePhoto)
                            <img src="{{ asset('storage/' . $profilePhoto) }}" class="avatar" alt="avatar">
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
        @if($selectedUser)
            <div class="chat-header">
                @php $profilePhoto = $selectedUser->profile_photo ?? ($selectedUser->documents->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                @if($profilePhoto)
                    <img src="{{ asset('storage/' . $profilePhoto) }}" class="avatar" alt="avatar">
                @else
                    <span class="avatar">{{ strtoupper(substr($selectedUser->name,0,1)) }}</span>
                @endif
                <span class="user-name">{{ $selectedUser->name }}</span>
            </div>
            <div class="chat-messages" id="chatMessages">
                @forelse($messages as $msg)
                    <div class="chat-message-row {{ $msg->sender_id == (auth()->id() ?? session('user_id')) ? 'me' : '' }}">
                        @php $msgPhoto = $msg->sender->profile_photo ?? ($msg->sender->documents->where('document_type', 'profile_picture')->first()->file_path ?? null); @endphp
                        @if($msgPhoto)
                            <img src="{{ asset('storage/' . $msgPhoto) }}" class="avatar" alt="avatar">
                        @else
                            <div class="avatar">{{ strtoupper(substr($msg->sender->name ?? 'U',0,1)) }}</div>
                        @endif
                        <div class="chat-message {{ $msg->sender_id == (auth()->id() ?? session('user_id')) ? 'me' : '' }}">
                            {{ $msg->message }}
                            <div class="meta">{{ $msg->created_at->format('H:i') }}</div>
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
            <form action="{{ route('chats.storeMessage', ['chat' => $selectedUser->id]) }}" method="POST" class="chat-input-area">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                <input type="text" name="message" placeholder="Type a message..." required autocomplete="off">
                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path fill="currentColor" d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg></button>
            </form>
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
</script>
@endpush
@endsection
