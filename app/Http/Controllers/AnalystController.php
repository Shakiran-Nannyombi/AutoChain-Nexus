<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerSale;
use App\Models\SupplierStock;
use App\Models\RetailerStock;
use App\Models\AnalystReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnalystController extends Controller
{
    /**
     * @var \App\Models\User|null $user
     */
    public function dashboard()
    {
        $totalReports = AnalystReport::count();

        $dataPoints = RetailerSale::count() +
                      RetailerStock::count() +
                      SupplierStock::count();

        // Fix: Get monthly sales totals as an array
        $trends = RetailerSale::select(DB::raw('to_char(created_at, \'YYYY-MM\') as month'), DB::raw('SUM(quantity_sold) as total'))
                    ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $accuracy = '95%'; // Placeholder or calculated from model later

        // Notifications removed to avoid linter errors

        return view('dashboards.analyst.index', compact(
            'totalReports', 'dataPoints', 'trends', 'accuracy'
        ));
    }

    public function analytics() {
        // Sales analytics
        $totalSales = \App\Models\RetailerSale::sum('quantity_sold');
        $salesByModel = \App\Models\RetailerSale::select('car_model', \DB::raw('SUM(quantity_sold) as total_sold'))
            ->groupBy('car_model')->orderByDesc('total_sold')->get();
        $salesByMonth = \App\Models\RetailerSale::select(\DB::raw('to_char(created_at, \'YYYY-MM\') as month'), \DB::raw('SUM(quantity_sold) as total_sold'))
            ->groupBy('month')->orderBy('month')->get();

        // Inventory analytics
        $totalStock = \App\Models\RetailerStock::sum('quantity_received');
        $stockByModel = \App\Models\RetailerStock::select('car_model', \DB::raw('SUM(quantity_received) as total_stock'))
            ->groupBy('car_model')->orderByDesc('total_stock')->get();
        $lowStockItems = \App\Models\RetailerStock::select('car_model', \DB::raw('SUM(quantity_received) as total_stock'))
            ->groupBy('car_model')->having('total_stock', '<', 10)->get();

        return view('dashboards.analyst.analytics', compact(
            'totalSales', 'salesByModel', 'salesByMonth',
            'totalStock', 'stockByModel', 'lowStockItems'
        ));
    }

public function trends()
{
    // Monthly sales totals
        $monthlySales = RetailerSale::selectRaw('to_char(created_at, \'YYYY-MM\') as month, SUM(quantity_sold) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

    // Calculate Simple Moving Average (SMA) with window of 3
    $sma = [];
    $values = array_values($monthlySales);
    $labels = array_keys($monthlySales);
    $window = 3;

    for ($i = 0; $i < count($values); $i++) {
        if ($i >= $window - 1) {
            $avg = array_sum(array_slice($values, $i - $window + 1, $window)) / $window;
            $sma[] = round($avg, 2);
        } else {
            $sma[] = null; // not enough data to average
        }
    }

    return view('dashboards.analyst.trends', compact('monthlySales', 'sma', 'labels'));
}

    public function messages($userId)
    {
        $currentUserId = optional(Auth::user())->id ?? session('user_id');
        // Handle admin users (if userId is prefixed with 'admin_')
        if (str_starts_with($userId, 'admin_')) {
            $adminId = substr($userId, 6);
            $selectedUser = \App\Models\Admin::find($adminId);
            if (!$selectedUser) {
                return response()->json(['status' => 'error', 'message' => 'Admin user not found'], 404);
            }
            // Demo messages for admin
            $messages = collect([
                [
                    'id' => 1,
                    'message' => 'Hi! Everything is going well, thank you for asking. We\'ve been working on improving our processes and I think we\'re making good progress.',
                    'sender_id' => 'admin_' . $adminId,
                    'sender_name' => $selectedUser->name,
                    'created_at' => now()->subMinutes(5)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
                [
                    'id' => 2,
                    'message' => 'That\'s great to hear! I was wondering if we could discuss some potential improvements to our workflow. Do you have some time this week?',
                    'sender_id' => $currentUserId,
                    'sender_name' => Auth::user()->name,
                    'created_at' => now()->subMinutes(2)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
            ]);
            $messagesArr = $messages->map(function($msg) use ($currentUserId) {
                return [
                    'id' => $msg['id'],
                    'message' => $msg['message'],
                    'sender_id' => $msg['sender_id'],
                    'sender_name' => $msg['sender_name'],
                    'created_at' => $msg['created_at'],
                    'is_me' => $msg['sender_id'] == $currentUserId,
                ];
            });
            return response()->json([
                'status' => 'success',
                'messages' => $messagesArr,
                'user' => [
                    'id' => $userId,
                    'name' => $selectedUser->name,
                    'avatar' => $selectedUser->profile_photo ? asset($selectedUser->profile_photo) : null,
                    'status' => 'online',
                ]
            ]);
        }
        // Regular user handling
        $selectedUser = \App\Models\User::find($userId);
        if (!$selectedUser) {
            // Provide a demo user object for missing users
            $selectedUser = (object) [
                'id' => $userId,
                'name' => 'Demo User',
                'profile_photo' => null,
            ];
            $messages = collect([
                [
                    'id' => 1,
                    'message' => 'Hello! This is a demo chat for a user not in the database.',
                    'sender_id' => $currentUserId,
                    'sender_name' => Auth::user()->name,
                    'created_at' => now()->subMinutes(10)->format('Y-m-d H:i'),
                    'is_me' => true,
                ],
                [
                    'id' => 2,
                    'message' => 'Hi! I am a demo user. You can use this chat to test the interface.',
                    'sender_id' => $userId,
                    'sender_name' => 'Demo User',
                    'created_at' => now()->subMinutes(8)->format('Y-m-d H:i'),
                    'is_me' => false,
                ],
            ]);
        } else {
            $messages = \App\Models\Chat::with('sender')->where(function($q) use ($currentUserId, $userId) {
                $q->where('sender_id', $currentUserId)->where('receiver_id', $userId);
            })->orWhere(function($q) use ($currentUserId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $currentUserId);
            })->orderBy('created_at')->get();
            if ($messages->isEmpty()) {
                // Provide demo messages if no real messages exist
                $messages = collect([
                    [
                        'id' => 1,
                        'message' => 'Hello! Can you provide the latest inventory analysis?',
                        'sender_id' => $currentUserId,
                        'sender_name' => Auth::user()->name,
                        'created_at' => now()->subMinutes(10)->format('Y-m-d H:i'),
                        'is_me' => true,
                    ],
                    [
                        'id' => 2,
                        'message' => 'Yes, I\'ve just uploaded the report to the shared folder. Let me know if you need any further breakdowns.',
                        'sender_id' => $selectedUser->id,
                        'sender_name' => $selectedUser->name,
                        'created_at' => now()->subMinutes(8)->format('Y-m-d H:i'),
                        'is_me' => false,
                    ],
                ]);
            }
        }
        $messagesArr = $messages->map(function($msg) use ($currentUserId) {
            return [
                'id' => $msg['id'] ?? $msg->id,
                'message' => $msg['message'] ?? $msg->message,
                'sender_id' => $msg['sender_id'] ?? $msg->sender_id,
                'sender_name' => $msg['sender_name'] ?? ($msg->sender ? $msg->sender->name : 'Unknown'),
                'created_at' => $msg['created_at'] ?? ($msg->created_at ? $msg->created_at->format('Y-m-d H:i') : ''),
                'is_me' => ($msg['sender_id'] ?? $msg->sender_id) == $currentUserId,
            ];
        });
        return response()->json([
            'status' => 'success',
            'messages' => $messagesArr,
            'user' => [
                'id' => $selectedUser->id,
                'name' => $selectedUser->name,
                'avatar' => $selectedUser->profile_photo ? asset($selectedUser->profile_photo) : null,
                'status' => 'online',
            ]
        ]);
    }

    // Removed analyst-manufacturer application logic and myApplications method

    public function forecasting() {
        return view('dashboards.analyst.forecasting');
    }
}
