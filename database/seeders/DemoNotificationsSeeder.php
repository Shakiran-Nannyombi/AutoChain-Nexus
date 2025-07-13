<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Admin;

class DemoNotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        $now = now();
        $notifications = [
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\NewUserNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode(['message' => 'Welcome to the system!']),
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\UserResetPasswordNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode(['message' => 'A password reset was requested for your account.']),
                'read_at' => null,
                'created_at' => $now->copy()->subMinutes(10),
                'updated_at' => $now->copy()->subMinutes(10),
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\NewChatMessage',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode(['message' => 'You have a new chat message!', 'sender_id' => 2, 'sender_name' => 'Demo User', 'created_at' => $now->copy()->subMinutes(20)->toDateTimeString()]),
                'read_at' => null,
                'created_at' => $now->copy()->subMinutes(20),
                'updated_at' => $now->copy()->subMinutes(20),
            ],
        ];

        DB::table('notifications')->insert($notifications);

        $admin = Admin::first();
        if ($admin) {
            $adminNotifications = [
                [
                    'id' => (string) Str::uuid(),
                    'type' => 'App\\Notifications\\NewUserNotification',
                    'notifiable_type' => get_class($admin),
                    'notifiable_id' => $admin->id,
                    'data' => json_encode(['message' => 'Welcome, admin!']),
                    'read_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => (string) Str::uuid(),
                    'type' => 'App\\Notifications\\UserResetPasswordNotification',
                    'notifiable_type' => get_class($admin),
                    'notifiable_id' => $admin->id,
                    'data' => json_encode(['message' => 'A password reset was requested for your admin account.']),
                    'read_at' => null,
                    'created_at' => $now->copy()->subMinutes(10),
                    'updated_at' => $now->copy()->subMinutes(10),
                ],
                [
                    'id' => (string) Str::uuid(),
                    'type' => 'App\\Notifications\\NewChatMessage',
                    'notifiable_type' => get_class($admin),
                    'notifiable_id' => $admin->id,
                    'data' => json_encode(['message' => 'You have a new chat message as admin!', 'sender_id' => 2, 'sender_name' => 'Demo User', 'created_at' => $now->copy()->subMinutes(20)->toDateTimeString()]),
                    'read_at' => null,
                    'created_at' => $now->copy()->subMinutes(20),
                    'updated_at' => $now->copy()->subMinutes(20),
                ],
            ];
            DB::table('notifications')->insert($adminNotifications);
        }
    }
} 