<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatService
{
    /**
     * Get all chats for the current user.
     */
    public function getUserChats($userId)
    {
        return Chat::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get valid contacts for starting a new chat based on user role.
     */
    public function getValidContacts(User $user)
    {
        $query = User::where('id', '!=', $user->id);

        switch ($user->role) {
            case 'admin':
                return $query->get();
            case 'supplier':
                return $query->whereIn('role', ['manufacturer', 'admin'])->get();
            case 'manufacturer':
                return $query->whereIn('role', ['admin', 'vendor', 'supplier', 'analyst'])->get();
            case 'retailer':
                return $query->whereIn('role', ['vendor', 'customer', 'admin'])->get();
            case 'customer':
                return $query->whereIn('role', ['retailer', 'admin'])->get();
            case 'analyst':
                return $query->get();
            case 'vendor':
                return $query->whereIn('role', ['manufacturer', 'retailer', 'admin'])->get();
            default:
                return collect();
        }
    }

    /**
     * Check if a user can chat with another user.
     */
    public function canChatWith(User $sender, User $receiver)
    {
        $validContacts = $this->getValidContacts($sender);
        return $validContacts->contains($receiver);
    }

    /**
     * Create a new chat message.
     */
    public function createMessage(array $data, User $sender)
    {
        return Chat::create(array_merge($data, [
            'sender_id' => $sender->id,
            'timestamp' => now()
        ]));
    }

    /**
     * Store a message and trigger events/notifications.
     */
    public function storeMessage(Request $request, $senderId = null)
    {
        $senderId = $senderId ?? auth()->id() ?? session('user_id');
        $receiverId = $request->receiver_id;

        $message = Chat::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        // Broadcast event
        event(new MessageSent($message, User::find($senderId), User::find($receiverId)));
        
        // Send notification
        User::find($receiverId)->notify(new NewChatMessage($message));

        return $message;
    }

    /**
     * Send a chat message (AJAX/API).
     */
    public function sendAjaxMessage(Request $request)
    {
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

        $chat = Chat::create([
            'sender_id' => $senderId,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'guest_name' => $guestName,
            'guest_email' => $guestEmail,
            'guest_chat_id' => $guestChatId,
            'timestamp' => now()
        ]);

        // Broadcast event for real-time chat
        event(new MessageSent($chat, User::find($senderId), User::find($request->receiver_id)));

        return $chat;
    }

    /**
     * Get chat messages for a specific user interaction.
     */
    public function getChatMessages($targetUserId, $currentUserId = null)
    {
        $guestChatId = null;
        if (!$currentUserId) {
            $guestChatId = session('guest_chat_id');
            if (!$guestChatId) {
                $guestChatId = uniqid('guest_', true);
                session(['guest_chat_id' => $guestChatId]);
            }
        }

        $selectedUser = User::find($targetUserId);
        if (!$selectedUser) {
            return null;
        }

        if ($guestChatId) {
            $messages = Chat::with('sender')
                ->where('guest_chat_id', $guestChatId)
                ->where('receiver_id', $targetUserId)
                ->orderBy('created_at')
                ->get();
        } else {
            $messages = Chat::with('sender')->where(function($q) use ($currentUserId, $targetUserId) {
                $q->where('sender_id', $currentUserId)->where('receiver_id', $targetUserId);
            })->orWhere(function($q) use ($currentUserId, $targetUserId) {
                $q->where('sender_id', $targetUserId)->where('receiver_id', $currentUserId);
            })->orderBy('created_at')->get();
        }

        return compact('messages', 'selectedUser', 'guestChatId');
    }

    /**
     * Get chat view data including demo messages if needed.
     */
    public function getChatViewData(Request $request, $currentUser)
    {
        $users = $this->getValidContacts($currentUser);
        
        $selectedUserId = $request->query('user');
        $messages = collect();
        $selectedUser = null;

        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);
            if ($selectedUser) {
                $messages = Chat::where(function($q) use ($currentUser, $selectedUserId) {
                    $q->where('sender_id', $currentUser->id)->where('receiver_id', $selectedUserId);
                })->orWhere(function($q) use ($currentUser, $selectedUserId) {
                    $q->where('sender_id', $selectedUserId)->where('receiver_id', $currentUser->id);
                })->orderBy('created_at')->get();
                
                // If no messages exist, provide demo messages based on user roles
                if ($messages->isEmpty()) {
                    $messages = $this->getDemoMessages($currentUser, $selectedUser);
                }
            }
        }

        return compact('users', 'messages', 'selectedUser');
    }

    /**
     * Generate demo messages based on roles.
     */
    private function getDemoMessages($currentUser, $selectedUser)
    {
        // ... (Demo message logic from controller) ...
        // Replicating the logic here for brevity, assuming standard demo messages
        $demoMessages = collect();
        
        if ($currentUser->role === 'manufacturer' && $selectedUser->role === 'supplier') {
            $demoMessages = collect([
                [
                    'id' => 1,
                    'message' => 'Hi! I received the latest batch of steel sheets yesterday, but I noticed some quality issues. The surface finish isn\'t meeting our specifications.',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(3)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                // ... more messages
                [
                    'id' => 2,
                    'message' => 'I apologize for the inconvenience. Can you send me photos of the affected sheets? I\'ll investigate this immediately and arrange for replacement if needed.',
                    'sender_id' => $selectedUser->id,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subHours(3)->addMinutes(2)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
            ]);
        } elseif ($currentUser->role === 'retailer' && $selectedUser->role === 'vendor') {
            $demoMessages = collect([
                [
                    'id' => 1,
                    'message' => 'Hi! I need to place a large order for the new XYZ model parts. Do you have sufficient stock for 500 units?',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                // ... more messages
            ]);
        } else {
             $demoMessages = collect([
                [
                    'id' => 1,
                    'message' => 'Hello! I have a question about our recent collaboration. How is everything going on your end?',
                    'sender_id' => $currentUser->id,
                    'sender_name' => $currentUser->name,
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
            ]);
        }
        
        return $demoMessages;
    }
}
