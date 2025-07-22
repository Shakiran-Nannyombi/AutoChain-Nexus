<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewChatMessage;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $chats = Chat::where('sender_id', $user->id)
                     ->orWhere('receiver_id', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->get();
        return view('chats.index', compact('chats'));
    }

    public function create()
    {
        $user = Auth::user();
        $validContacts = $this->getValidContacts($user);
        return view('chats.create', compact('validContacts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        if (!$this->canChatWith($user, $receiver)) {
            return redirect()->back()->with('error', 'You are not allowed to chat with this user.');
        }

        Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message
        ]);

        return redirect()->route('chats.index')->with('success', 'Message sent successfully.');
    }

    public function show(Chat $chat)
    {
        $user = Auth::user();
        if ($chat->sender_id !== $user->id && $chat->receiver_id !== $user->id) {
            return redirect()->route('chats.index')->with('error', 'You do not have permission to view this chat.');
        }
        $chat->load('messages');
        return view('chats.show', compact('chat'));
    }

    public function edit(Chat $chat)
    {
        $user = auth()->user();
        if ($chat->sender_id !== $user->id) {
            return redirect()->route('chats.index')->with('error', 'You can only edit your own messages.');
        }
        return view('chats.edit', compact('chat'));
    }

    public function update(Request $request, Chat $chat)
    {
        $user = auth()->user();
        if ($chat->sender_id !== $user->id) {
            return redirect()->route('chats.index')->with('error', 'You can only update your own messages.');
        }
        $chat->update(['message' => $request->message]);
        return redirect()->route('chats.index')->with('success', 'Message updated successfully.');
    }

    public function destroy(Chat $chat)
    {
        $user = auth()->user();
        if ($chat->sender_id !== $user->id) {
            return redirect()->route('chats.index')->with('error', 'You can only delete your own messages.');
        }
        $chat->delete();
        return redirect()->route('chats.index')->with('success', 'Message deleted successfully.');
    }

    private function getValidContacts(User $user)
    {
        $query = User::where('id', '!=', $user->id);

        switch ($user->role) {
            case 'admin':
                // Admin can chat with everyone
                return $query->get();
            case 'supplier':
                // Supplier can chat with manufacturers and admin
                return $query->whereIn('role', ['manufacturer', 'admin'])->get();
            case 'manufacturer':
                // Manufacturer can chat with admin, vendor, supplier, analyst
                return $query->whereIn('role', ['admin', 'vendor', 'supplier', 'analyst'])->get();
            case 'retailer':
                // Retailer can chat with vendor, customer, admin
                return $query->whereIn('role', ['vendor', 'customer', 'admin'])->get();
            case 'customer':
                // Customer can chat with retailer and admin
                return $query->whereIn('role', ['retailer', 'admin'])->get();
            case 'analyst':
                // Analyst can chat with all users except themselves
                return $query->get();
            case 'vendor':
                // Vendor can chat with manufacturer, retailer, admin
                return $query->whereIn('role', ['manufacturer', 'retailer', 'admin'])->get();
            default:
                return collect();
        }
    }

    private function canChatWith(User $sender, User $receiver)
    {
        $validContacts = $this->getValidContacts($sender);
        return $validContacts->contains($receiver);
    }

    // ... existing code for sendMessage, getOrderChats, getUnreadMessages, markAsRead, storeMessage, editMessage, updateMessage, destroyMessage ...

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'message' => 'required|string'
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'order_id' => $request->order_id,
            'message' => $request->message,
            'timestamp' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $chat
        ]);
    }

    public function getOrderChats($orderId)
    {
        $chats = Chat::with(['sender', 'receiver'])
            ->where('order_id', $orderId)
            ->orderBy('timestamp', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $chats
        ]);
    }

    public function getUnreadMessages()
    {
        $unreadMessages = Chat::with(['sender', 'order'])
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $unreadMessages
        ]);
    }

    public function markAsRead($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        
        if ($chat->receiver_id === Auth::id()) {
            $chat->update(['read_at' => now()]);
            return response()->json([
                'status' => 'success',
                'message' => 'Message marked as read'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 403);
    }

    public function storeMessage(Request $request, $chatId = null)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
        $senderId = auth()->id() ?? session('user_id');
        $receiverId = $request->receiver_id;
        $message = \App\Models\Chat::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);
        // Broadcast event
        event(new MessageSent($message, \App\Models\User::find($senderId), \App\Models\User::find($receiverId)));
        // Send notification
        \App\Models\User::find($receiverId)->notify(new NewChatMessage($message));
        return response()->json([
            'status' => 'success',
            'message' => 'Message sent!',
            'data' => $message
        ]);
    }

    public function editMessage(\App\Models\ChatMessage $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        return view('chats.edit_message', compact('message'));
    }

    // Update message (now using Chat model)
    public function updateMessage(Request $request, \App\Models\Chat $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        $message->update(['message' => $request->message]);
        return response()->json([
            'status' => 'success',
            'message' => 'Message updated!',
            'data' => $message
        ]);
    }

    // Delete message (now using Chat model)
    public function destroyMessage(\App\Models\Chat $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }
        $chatId = $message->id;
        $message->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Message deleted!',
            'chat_id' => $chatId
        ]);
    }

    public function chat(Request $request)
    {
        $userId = auth()->id() ?? session('user_id');
        $currentUser = \App\Models\User::find($userId);
        $role = $currentUser->role;
        $users = collect();
        switch ($role) {
            case 'admin':
                $users = \App\Models\User::where('id', '!=', $userId)->get();
                break;
            case 'manufacturer':
                $users = \App\Models\User::whereIn('role', ['supplier', 'vendor'])->get();
                break;
            case 'supplier':
                $users = \App\Models\User::where('role', 'manufacturer')->get();
                break;
            case 'vendor':
                $users = \App\Models\User::whereIn('role', ['retailer', 'manufacturer'])->get();
                break;
            case 'retailer':
                $users = \App\Models\User::whereIn('role', ['customer', 'vendor'])->get();
                break;
            case 'customer':
                $users = \App\Models\User::where('role', 'retailer')->get();
                break;
            case 'analyst':
                $users = \App\Models\User::where('id', '!=', $userId)->get();
                break;
            default:
                $users = collect();
        }
        $selectedUserId = $request->query('user');
        $messages = collect();
        $selectedUser = null;
        if ($selectedUserId) {
            $selectedUser = \App\Models\User::find($selectedUserId);
            $messages = \App\Models\Chat::where(function($q) use ($userId, $selectedUserId) {
                $q->where('sender_id', $userId)->where('receiver_id', $selectedUserId);
            })->orWhere(function($q) use ($userId, $selectedUserId) {
                $q->where('sender_id', $selectedUserId)->where('receiver_id', $userId);
            })->orderBy('created_at')->get();
            
            // If no messages exist, provide demo messages based on user roles
            if ($messages->isEmpty()) {
                $messages = $this->getDemoMessages($currentUser, $selectedUser);
            }
        }
        return view('chats.user-chat', compact('users', 'messages', 'selectedUser'));
    }

    private function getDemoMessages($currentUser, $selectedUser)
    {
        $demoMessages = collect();
        
        // Generate demo messages based on the roles involved
        if ($currentUser->role === 'manufacturer' && $selectedUser->role === 'supplier') {
            // Manufacturer asking supplier about material quality
            $demoMessages = collect([
                [
                    'id' => 1,
                    'message' => 'Hi! I received the latest batch of steel sheets yesterday, but I noticed some quality issues. The surface finish isn\'t meeting our specifications.',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(3)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 2,
                    'message' => 'I apologize for the inconvenience. Can you send me photos of the affected sheets? I\'ll investigate this immediately and arrange for replacement if needed.',
                    'sender_id' => $selectedUser->id,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(3)->addMinutes(2)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 3,
                    'message' => 'I\'ve already documented the issues and sent photos to your quality team. The batch number is STL-2024-045. When can I expect the replacement?',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 4,
                    'message' => 'I\'ve expedited the replacement order. You\'ll receive the new batch by tomorrow morning. I\'ve also added a 10% discount to compensate for the delay.',
                    'sender_id' => $selectedUser->id,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(2)->addMinutes(5)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 5,
                    'message' => 'That\'s great! Thank you for the quick response. I appreciate the discount too. This will help us stay on schedule with our production.',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(1)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
            ]);
        } elseif ($currentUser->role === 'retailer' && $selectedUser->role === 'vendor') {
            // Retailer asking vendor about product availability
            $demoMessages = collect([
                [
                    'id' => 1,
                    'message' => 'Hi! I need to place a large order for the new XYZ model parts. Do you have sufficient stock for 500 units?',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 2,
                    'message' => 'Let me check our current inventory. I can confirm we have 450 units in stock, and we\'re expecting a shipment of 200 more units by Friday.',
                    'sender_id' => $selectedUser->id,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(2)->addMinutes(3)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 3,
                    'message' => 'Perfect! I can take the 450 units now and the remaining 50 when the new shipment arrives. What\'s the best price you can offer for this bulk order?',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(1)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 4,
                    'message' => 'For a bulk order of 500 units, I can offer you a 15% discount off our standard price. This brings the total to $12,750. Would you like me to prepare the order?',
                    'sender_id' => $selectedUser->id,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(1)->addMinutes(2)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 5,
                    'message' => 'Excellent! That works perfectly for our budget. Please proceed with the order. I\'ll send the purchase order details right away.',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subMinutes(30)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
            ]);
        } else {
            // Generic demo conversation
            $demoMessages = collect([
                [
                    'id' => 1,
                    'message' => 'Hello! I have a question about our recent collaboration. How is everything going on your end?',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 2,
                    'message' => 'Hi! Everything is going well, thank you for asking. We\'ve been working on improving our processes and I think we\'re making good progress.',
                    'sender_id' => $selectedUser->id,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(2)->addMinutes(5)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 3,
                    'message' => 'That\'s great to hear! I was wondering if we could discuss some potential improvements to our workflow. Do you have some time this week?',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(1)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 4,
                    'message' => 'Absolutely! I\'m available on Wednesday afternoon or Thursday morning. Which works better for you?',
                    'sender_id' => $selectedUser->id,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(1)->addMinutes(3)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 5,
                    'message' => 'Wednesday afternoon works perfectly for me. Let\'s say 2 PM? I\'ll send you a calendar invite with the meeting details.',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subMinutes(30)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
            ]);
        }
        
        return $demoMessages;
    }

    private function getDemoMessagesForAdmin($currentUser, $adminUser)
    {
        // Generate demo messages for admin conversations
        return collect([
            [
                'id' => 1,
                'message' => 'Hello! I need to discuss some compliance requirements for our upcoming audit.',
                'sender_id' => $currentUser->id,
                'sender_name' => $currentUser->name,
                'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                'is_me' => true,
            ],
            [
                'id' => 2,
                'message' => 'Of course! I\'m here to help. What specific compliance areas do you need assistance with?',
                'sender_id' => 'admin_' . $adminUser->id,
                'sender_name' => $adminUser->name,
                'created_at' => now()->subHours(2)->addMinutes(1)->format('Y-m-d H:i'),
                'is_me' => false,
            ],
            [
                'id' => 3,
                'message' => 'We need to ensure our documentation meets the new regulatory standards. Can you guide us through the requirements?',
                'sender_id' => $currentUser->id,
                'sender_name' => $currentUser->name,
                'created_at' => now()->subHours(1)->format('Y-m-d H:i'),
                'is_me' => true,
            ],
            [
                'id' => 4,
                'message' => 'Absolutely! I\'ll send you the updated compliance checklist and schedule a review meeting for next week.',
                'sender_id' => 'admin_' . $adminUser->id,
                'sender_name' => $adminUser->name,
                'created_at' => now()->subMinutes(30)->format('Y-m-d H:i'),
                'is_me' => false,
            ],
        ]);
    }

    // Add this method for AJAX chat message fetching
    public function getChatMessages($userId)
    {
        $currentUserId = auth()->id();
        $guestChatId = null;
        if (!$currentUserId) {
            // For guests, use a session-based identifier
            $guestChatId = session('guest_chat_id');
            if (!$guestChatId) {
                $guestChatId = uniqid('guest_', true);
                session(['guest_chat_id' => $guestChatId]);
            }
        }

        $selectedUser = \App\Models\User::find($userId);
        if (!$selectedUser) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        // For guests, fetch messages by guest_chat_id
        if ($guestChatId) {
            $messages = \App\Models\Chat::with('sender')
                ->where('guest_chat_id', $guestChatId)
                ->where('receiver_id', $userId)
                ->orderBy('created_at')
                ->get();
        } else {
            $messages = \App\Models\Chat::with('sender')->where(function($q) use ($currentUserId, $userId) {
                $q->where('sender_id', $currentUserId)->where('receiver_id', $userId);
            })->orWhere(function($q) use ($currentUserId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $currentUserId);
            })->orderBy('created_at')->get();
        }

        $messagesArr = $messages->map(function($msg) use ($currentUserId, $guestChatId) {
            return [
                'id' => $msg['id'] ?? $msg->id,
                'message' => $msg['message'] ?? $msg->message,
                'sender_id' => $msg['sender_id'] ?? $msg->sender_id,
                'sender_name' => $msg['sender_name'] ?? ($msg->sender ? $msg->sender->name : ($msg->guest_name ?? 'Guest')),
                'created_at' => $msg['created_at'] ?? ($msg->created_at ? $msg->created_at->format('Y-m-d H:i') : ''),
                'is_me' => $guestChatId ? ($msg->guest_chat_id === $guestChatId) : (($msg['sender_id'] ?? $msg->sender_id) == $currentUserId),
            ];
        });

        return response()->json([
            'status' => 'success',
            'messages' => $messagesArr,
            'user' => [
                'id' => $selectedUser->id,
                'name' => $selectedUser->name,
                'avatar' => $selectedUser->profile_photo 
                    ? asset($selectedUser->profile_photo) 
                    : null,
                'status' => 'online',
            ]
        ]);
    }

    // Add this method for AJAX chat message sending
    public function sendChatMessage(Request $request)
    {
        try {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string',
                'guest_name' => 'nullable|string|max:255',
                'guest_email' => 'nullable|email|max:255',
            ]);

            $senderId = auth()->check() ? auth()->id() : null;
            $guestName = $request->guest_name;
            $guestEmail = $request->guest_email;
            $guestChatId = null;
            if (!$senderId) {
                $guestChatId = session('guest_chat_id');
                if (!$guestChatId) {
                    $guestChatId = uniqid('guest_', true);
                    session(['guest_chat_id' => $guestChatId]);
                }
            }

            $chat = \App\Models\Chat::create([
                'sender_id' => $senderId,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
                'guest_chat_id' => $guestChatId,
                'timestamp' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => $chat
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Chat send error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the public customer chat page (no authentication required).
     */
    public function publicChat()
    {
        // Show all retailers and admins as chat contacts
        $users = \App\Models\User::whereIn('role', ['retailer', 'admin'])->get();
        return view('dashboards.customer.chat', compact('users'));
    }
} 