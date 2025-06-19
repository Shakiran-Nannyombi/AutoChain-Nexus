<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $headerTitle = 'Communication Center';

        // Unread Messages
        $unreadMessages = Chat::where('receiver_id', Auth::id())
                              ->whereNull('read_at')
                              ->count();

        // Placeholders for other overview cards
        $pendingNotifications = 0; // To be connected to a Notification model later
        $activeAlerts = 0; // To be connected to an Alert/ActivityLog model later
        $responseRate = '0%'; // To be calculated based on message/response data

        // Fetch chat messages for the authenticated user
        $chats = Chat::with(['sender', 'receiver'])
                    ->where('sender_id', Auth::id())
                    ->orWhere('receiver_id', Auth::id())
                    ->orderBy('timestamp', 'desc')
                    ->get();

        return view('pages.communications', compact(
            'headerTitle',
            'unreadMessages',
            'pendingNotifications',
            'activeAlerts',
            'responseRate',
            'chats'
        ));
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
} 