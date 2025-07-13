<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@autochain.com'],
            [
                'name' => 'John Mayanja',
                'password' => Hash::make('password'),
                'phone' => '0123456789',
                'company' => 'Autochain Nexus',
                'address' => 'Kampala, Uganda',
                'role' => 'Admin',
                'admin_level' => 'super',
                'permissions' => json_encode(['all']),
                'department' => 'Administration',
                'is_active' => true,
                'profile_photo' => 'images/profile/admin.jpeg',
            ]
        );
        // Removed creation of User record for admin
    }
}
