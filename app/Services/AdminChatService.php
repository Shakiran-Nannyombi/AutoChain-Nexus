<?php

namespace App\Services;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatService
{
    /**
     * Get chat data for the chat view.
     */
    public function getChatViewData(Request $request)
    {
        $adminId = Auth::check() ? Auth::id() : session('user_id');
        
        if (!$adminId) {
            abort(403, 'Unauthorized');
        }

        $users = User::where('id', '!=', $adminId)->get();
        $selectedUserId = $request->query('user');
        $messages = collect();
        $selectedUser = null;

        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);
            $messages = Chat::where(function($q) use ($adminId, $selectedUserId) {
                $q->where('sender_id', $adminId)->where('receiver_id', $selectedUserId);
            })->orWhere(function($q) use ($adminId, $selectedUserId) {
                $q->where('sender_id', $selectedUserId)->where('receiver_id', $adminId);
            })->orderBy('created_at')->get();
        }

        return compact('users', 'messages', 'selectedUser');
    }

    /**
     * Get messages for a specific user.
     */
    public function getMessages($userId)
    {
        $adminId = Auth::guard('admin')->id() ?? Auth::id() ?? session('user_id');
        
        if (!$adminId) {
            return ['status' => 'error', 'message' => 'Unauthorized', 'code' => 403];
        }

        $selectedUser = User::find($userId);
        
        if (!$selectedUser) {
            return ['status' => 'error', 'message' => 'User not found', 'code' => 404];
        }

        $messages = Chat::with('sender')->where(function($q) use ($adminId, $userId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($adminId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $adminId);
        })->orderBy('created_at')->get();

        if ($messages->isEmpty()) {
            $messagesArr = $this->getDemoMessages($userId, $selectedUser, $adminId);
        } else {
            $messagesArr = $messages->map(function($msg) use ($adminId) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender ? $msg->sender->name : 'Unknown',
                    'created_at' => $msg->created_at ? $msg->created_at->format('Y-m-d H:i') : '',
                    'is_me' => $msg->sender_id == $adminId,
                ];
            });
        }

        return [
            'status' => 'success',
            'messages' => $messagesArr,
            'user' => [
                'id' => $selectedUser->id,
                'name' => $selectedUser->name,
                'avatar' => $selectedUser->profile_photo ? asset($selectedUser->profile_photo) : null,
                'status' => 'online',
            ]
        ];
    }

    /**
     * Send a chat message.
     */
    public function sendMessage(Request $request)
    {
        $adminId = Auth::guard('admin')->id() ?? Auth::id() ?? session('user_id');
        
        if (!$adminId) {
            return ['status' => 'error', 'message' => 'Unauthorized', 'code' => 403];
        }

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'sender_id' => $adminId,
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return ['status' => 'success', 'message' => 'Message sent!', 'data' => $chat];
    }

    /**
     * Get demo messages for testing.
     */
    private function getDemoMessages($userId, $selectedUser, $adminId)
    {
        return collect([
            [
                'id' => 1,
                'message' => 'Hello! I have an urgent issue with my recent order #ORD-2024-001. The delivery was supposed to arrive yesterday but it\'s still showing "in transit" on the tracking page.',
                'sender_id' => $userId,
                'sender_name' => $selectedUser->name,
                'created_at' => now()->subHours(2)->format('Y-m-d H:i'),
                'is_me' => false,
            ],
            [
                'id' => 2,
                'message' => 'Hi there! I understand your concern. Let me check the status of your order #ORD-2024-001 right away.',
                'sender_id' => $adminId,
                'sender_name' => 'Admin',
                'created_at' => now()->subHours(2)->addMinutes(1)->format('Y-m-d H:i'),
                'is_me' => true,
            ],
            [
                'id' => 3,
                'message' => 'I can see that your order was processed and shipped on time, but there was a delay at the local distribution center due to weather conditions. The package is currently at the final sorting facility.',
                'sender_id' => $adminId,
                'sender_name' => 'Admin',
                'created_at' => now()->subHours(2)->addMinutes(3)->format('Y-m-d H:i'),
                'is_me' => true,
            ],
            [
                'id' => 4,
                'message' => 'That\'s frustrating. I really needed those parts for a client project that\'s due tomorrow. Is there anything you can do to expedite the delivery?',
                'sender_id' => $userId,
                'sender_name' => $selectedUser->name,
                'created_at' => now()->subHours(2)->addMinutes(5)->format('Y-m-d H:i'),
                'is_me' => false,
            ],
            [
                'id' => 5,
                'message' => 'Absolutely! I\'ve just contacted our logistics partner and arranged for priority delivery. Your package will be delivered by 2 PM today. I\'ve also added a 15% discount to your next order as compensation for the inconvenience.',
                'sender_id' => $adminId,
                'sender_name' => 'Admin',
                'created_at' => now()->subHours(2)->addMinutes(8)->format('Y-m-d H:i'),
                'is_me' => true,
            ],
            [
                'id' => 6,
                'message' => 'That\'s amazing! Thank you so much for your quick response and going above and beyond. I really appreciate the discount too. You\'ve saved my project!',
                'sender_id' => $userId,
                'sender_name' => $selectedUser->name,
                'created_at' => now()->subHours(2)->addMinutes(10)->format('Y-m-d H:i'),
                'is_me' => false,
            ],
            [
                'id' => 7,
                'message' => 'You\'re very welcome! Customer satisfaction is our top priority. I\'ve also updated your tracking information - you should receive a notification when the package is out for delivery. Is there anything else I can help you with today?',
                'sender_id' => $adminId,
                'sender_name' => 'Admin',
                'created_at' => now()->subHours(2)->addMinutes(12)->format('Y-m-d H:i'),
                'is_me' => true,
            ],
            [
                'id' => 8,
                'message' => 'Actually, yes! I was thinking about placing another order for some additional parts. Do you have any recommendations for the new XYZ model components?',
                'sender_id' => $userId,
                'sender_name' => $selectedUser->name,
                'created_at' => now()->subHours(1)->format('Y-m-d H:i'),
                'is_me' => false,
            ],
            [
                'id' => 9,
                'message' => 'Great question! For the XYZ model, I\'d recommend our premium line - they have better durability and come with extended warranty. I can send you our latest catalog with detailed specifications. Would you like me to email it to you?',
                'sender_id' => $adminId,
                'sender_name' => 'Admin',
                'created_at' => now()->subHours(1)->addMinutes(2)->format('Y-m-d H:i'),
                'is_me' => true,
            ],
            [
                'id' => 10,
                'message' => 'Perfect! That would be very helpful. Thanks again for all your help today. You\'ve been incredibly professional and responsive.',
                'sender_id' => $userId,
                'sender_name' => $selectedUser->name,
                'created_at' => now()->subHours(1)->addMinutes(4)->format('Y-m-d H:i'),
                'is_me' => false,
            ],
        ]);
    }
}
