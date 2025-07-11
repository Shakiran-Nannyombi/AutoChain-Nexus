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
                // Manufacturer can chat with suppliers, retailers, and admin
                return $query->whereIn('role', ['supplier', 'retailer', 'admin'])->get();
            case 'retailer':
                // Retailer can chat with manufacturers, consumers, and admin
                return $query->whereIn('role', ['manufacturer', 'consumer', 'admin'])->get();
            case 'consumer':
                // Consumer can chat with retailers and admin
                return $query->whereIn('role', ['retailer', 'admin'])->get();
            case 'analyst':
                // Analyst can only chat with admin
                return $query->where('role', 'admin')->get();
            case 'vendor':
                // Vendor can chat with manufacturers, retailers, and admin
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
        return back()->with('success', 'Message sent!');
    }

    public function editMessage(\App\Models\ChatMessage $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        return view('chats.edit_message', compact('message'));
    }

    public function updateMessage(Request $request, \App\Models\ChatMessage $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        $message->update(['message' => $request->message]);
        return redirect()->route('chats.show', $message->chat_id)->with('success', 'Message updated!');
    }

    public function destroyMessage(\App\Models\ChatMessage $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        $chatId = $message->chat_id;
        $message->delete();
        return redirect()->route('chats.show', $chatId)->with('success', 'Message deleted!');
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
                $users = \App\Models\User::where('role', '!=', 'customer')->where('id', '!=', $userId)->get();
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
        }
        return view('chats.user-chat', compact('users', 'messages', 'selectedUser'));
    }
} 