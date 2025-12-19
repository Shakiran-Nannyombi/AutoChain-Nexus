<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index()
    {
        $chats = $this->chatService->getUserChats(Auth::id());
        return view('chats.index', compact('chats'));
    }

    public function create()
    {
        $user = Auth::user();
        $validContacts = $this->chatService->getValidContacts($user);
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

        if (!$this->chatService->canChatWith($user, $receiver)) {
            return redirect()->back()->with('error', 'You are not allowed to chat with this user.');
        }

        $this->chatService->createMessage([
            'receiver_id' => $receiver->id,
            'message' => $request->message
        ], $user);

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

        $message = $this->chatService->storeMessage($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent!',
            'data' => $message
        ]);
    }

    public function editMessage(\App\Models\Chat $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        return view('chats.edit_message', compact('message'));
    }

    public function updateMessage(Request $request, \App\Models\Chat $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $request->validate(['message' => 'required|string|max:1000']);
        $message->update(['message' => $request->message]);
        return response()->json(['status' => 'success', 'message' => 'Message updated!', 'data' => $message]);
    }

    public function destroyMessage(\App\Models\Chat $message)
    {
        $user = auth()->user();
        if ($message->sender_id !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $chatId = $message->id;
        $message->delete();
        return response()->json(['status' => 'success', 'message' => 'Message deleted!', 'chat_id' => $chatId]);
    }

    public function chat(Request $request)
    {
        $data = $this->chatService->getChatViewData($request, Auth::user());
        return view('chats.user-chat', $data);
    }

    public function getChatMessages($userId)
    {
        $currentUserId = auth()->id();
        $data = $this->chatService->getChatMessages($userId, $currentUserId);

        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $messagesArr = $data['messages']->map(function($msg) use ($currentUserId, $data) {
            return [
                'id' => $msg['id'] ?? $msg->id,
                'message' => $msg['message'] ?? $msg->message,
                'sender_id' => $msg['sender_id'] ?? $msg->sender_id,
                'sender_name' => $msg['sender_name'] ?? ($msg->sender ? $msg->sender->name : ($msg->guest_name ?? 'Guest')),
                'created_at' => $msg['created_at'] ?? ($msg->created_at ? $msg->created_at->format('Y-m-d H:i') : ''),
                'is_me' => $data['guestChatId'] ? ($msg->guest_chat_id === $data['guestChatId']) : (($msg['sender_id'] ?? $msg->sender_id) == $currentUserId),
            ];
        });

        return response()->json([
            'status' => 'success',
            'messages' => $messagesArr,
            'user' => [
                'id' => $data['selectedUser']->id,
                'name' => $data['selectedUser']->name,
                'avatar' => $data['selectedUser']->profile_photo ? asset($data['selectedUser']->profile_photo) : null,
                'status' => 'online',
            ]
        ]);
    }

    public function sendChatMessage(Request $request)
    {
        try {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string',
                'guest_name' => 'nullable|string|max:255',
                'guest_email' => 'nullable|email|max:255',
            ]);

            $chat = $this->chatService->sendAjaxMessage($request);

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => $chat
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Chat send error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function publicChat()
    {
        $users = User::whereIn('role', ['retailer', 'admin'])->get();
        return view('dashboards.customer.chat', compact('users'));
    }
}