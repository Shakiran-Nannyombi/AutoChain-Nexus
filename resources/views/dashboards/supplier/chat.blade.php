@extends('layouts.dashboard')

@section('title', 'Supplier Chat')

@push('styles')
    @vite(['resources/css/supplier.css', 'resources/css/chat.css'])
    <style>
      .chat-message-row.me .message-actions { display: none; }
      .chat-message-row.me:hover .message-actions { display: block; }
    </style>
@endpush

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
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
                        <span class="user-name" style="font-weight: 600; font-size: 1.05rem;">{{ $user->name }}</span>
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
          <h1 style="color: #166534; font-size: 2.5rem; font-weight: 700; margin-bottom: 1.2rem; text-align: center;">Welcome to Supplier Chat</h1>
          <div style="font-size: 1.25rem; color: #222; margin-bottom: 2.2rem; max-width: 520px; margin-left: auto; margin-right: auto; text-align: center;">
            Here you can communicate directly with manufacturer or admin. Select a user from the list on the left to view your conversation or start a new chat.
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
                Hello Supplier, can you confirm the delivery schedule for next week?
                <div style="font-size: 0.95rem; color: #6b7280; margin-top: 0.5rem; text-align: right;">Manufacturer, 10:15</div>
              </div>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
              <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Yes, the shipment will be ready by Monday.
                <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, 10:16</div>
              </div>
              <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">S</span></div>
            </div>
          </div>
          <!-- Demo chat for Admin -->
          <div class="demo-chat" data-role="admin" style="display:none;">
            <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
              <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #fbbf24; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">A</span></div>
              <div style="background: #fff7e6; color: #b35400; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Please update your compliance documents by Friday.
                <div style="font-size: 0.95rem; color: #b35400; margin-top: 0.5rem; text-align: right;">Admin, 09:00</div>
              </div>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
              <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                Will do, thanks for the reminder!
                <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, 09:01</div>
              </div>
              <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">S</span></div>
            </div>
          </div>
        </div>
      </div>
      <form id="chat-input-form" class="chat-input-area" style="border-radius: 0 0 18px 18px; display: none; padding: 1rem; background: #fff; border-top: 1px solid #e5e7eb; position: sticky; bottom: 0; z-index: 10; box-shadow: 0 -2px 8px rgba(0,0,0,0.03);">
        <!-- Message Input -->
        <input type="text" id="chat-input" placeholder="Write your message..." autocomplete="off" style="flex:1; border: none; outline: none; font-size: 1rem; padding: 8px 12px; background: #f8fafc; border-radius: 18px; margin-right: 8px;" />
        <!-- Send Button -->
        <button type="submit" id="send-btn" style="background: var(--primary); border: none; color: white; padding: 8px 16px; border-radius: 20px; cursor: pointer; transition: background 0.2s;" title="Send message">
          <i class="fas fa-paper-plane"></i>
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let selectedUserId = null;
let currentUserId = '{{ auth()->id() ?? session("user_id") }}';

const userList = document.getElementById('userList');
const demoChats = document.getElementById('demo-chats');
const welcomeMessage = document.getElementById('welcome-message');
const chatHeaderArea = document.getElementById('chat-header-area');
const chatInputForm = document.getElementById('chat-input-form');
const chatInput = document.getElementById('chat-input');
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
    if (chatHeaderArea) chatHeaderArea.style.display = 'flex';
    if (chatInputForm) chatInputForm.style.display = 'flex';
    
    // Update header
    if (document.getElementById('chat-header-name')) document.getElementById('chat-header-name').textContent = name;
    const headerAvatar = document.getElementById('chat-header-avatar');
    if (headerAvatar) {
    headerAvatar.innerHTML = '';
    if (avatarEl) headerAvatar.appendChild(avatarEl.cloneNode(true));
    }
    if (document.getElementById('chat-header-status')) document.getElementById('chat-header-status').textContent = 'Online';
    
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
  });
}

// Send message (demo functionality)
if (chatInputForm) {
  chatInputForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!selectedUserId) return;
    const message = chatInput.value.trim();
    if (!message) return;
    
    // Add message to chat area
    const currentTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    const messageHtml = `
      <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 1.2rem;">
        <div style="background: #2563eb; color: #fff; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
          ${message}
          <div style="font-size: 0.95rem; color: #dbeafe; margin-top: 0.5rem; text-align: right;">You, ${currentTime}</div>
        </div>
        <div style="margin-left: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">S</span></div>
      </div>
    `;
    
    chatMessagesArea.innerHTML += messageHtml;
    chatInput.value = '';
    chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;
    
    // Simulate response based on user role
    setTimeout(() => {
      const userName = document.getElementById('chat-header-name').textContent;
      const userRole = document.querySelector(`[data-user-id="${selectedUserId}"]`).closest('li').getAttribute('data-role');
      
      // Different responses based on role
      const responses = {
        manufacturer: [
          "Thanks for the update! I'll review the production schedule.",
          "Got it. Let me check with the production team.",
          "Perfect! I'll coordinate with the logistics department.",
          "Understood. I'll get back to you with the details shortly.",
          "Great! Please send me the specifications when ready."
        ],
        admin: [
          "Thank you for reaching out. I'll review this immediately.",
          "Noted. I'll update the system accordingly.",
          "Thanks for the information. I'll process this request.",
          "Received. I'll coordinate with the relevant departments.",
          "Acknowledged. I'll follow up on this matter."
        ],
        vendor: [
          "Thanks! I'll check our inventory and get back to you.",
          "Received. Let me verify the availability.",
          "Got it! I'll prepare the quotation for you.",
          "Thanks for the inquiry. I'll send you the details.",
          "Perfect! I'll coordinate the delivery schedule."
        ],
        retailer: [
          "Thanks for the message! I'll check our stock levels.",
          "Received. Let me verify the order status.",
          "Got it! I'll update you on the delivery timeline.",
          "Thanks! I'll coordinate with our warehouse team.",
          "Perfect! I'll send you the tracking information."
        ]
      };
      
      const roleResponses = responses[userRole] || [
        "Thanks for your message! I'll get back to you soon.",
        "Received. I'll look into this and respond shortly.",
        "Got it! Let me check and get back to you."
      ];
      
      const randomResponse = roleResponses[Math.floor(Math.random() * roleResponses.length)];
      
      const responseHtml = `
        <div style="display: flex; align-items: flex-end; margin-bottom: 1.2rem;">
          <div style="margin-right: 10px;"><span class="avatar" style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">${userName.charAt(0)}</span></div>
          <div style="background: #e3f0ff; color: #166534; border-radius: 18px; padding: 1rem 1.3rem; font-size: 1.08rem; max-width: 70vw; min-width: 120px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            ${randomResponse}
            <div style="font-size: 0.95rem; color: #6b7280; margin-top: 0.5rem; text-align: right;">${userName}, ${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
          </div>
        </div>
      `;
      chatMessagesArea.innerHTML += responseHtml;
      chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;
    }, Math.random() * 2000 + 1000); // Random delay between 1-3 seconds
  });
}
</script>
@endpush
