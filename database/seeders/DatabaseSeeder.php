<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order\Order;
use App\Models\Chat;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create an Admin User in the admins table
        Admin::create([
            'name' => 'Shakiran Nannyombi',
            'email' => 'preezynats@gmail.com',
            'password' => bcrypt('19830101'),
            'phone' => '123-456-7890',
            'company_name' => 'Admin Corp',
            'company_address' => '123 Admin St',
            'is_admin' => true,
        ]);

        // Create some dummy orders
        $users = User::all();

        Order::factory(5)->create(['user_id' => $users->random()->id])->each(function ($order) use ($users) {
            // Create chat messages for each order
            Chat::factory(rand(1, 5))->create([
                'sender_id' => $users->random()->id,
                'receiver_id' => $users->except($order->user_id)->random()->id,
                'order_id' => $order->id,
            ]);
        });
    }
}
