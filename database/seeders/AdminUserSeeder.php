<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

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
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'company' => 'Autochain Nexus',
                'address' => 'Admin Address',
                'admin_level' => 'super',
                'permissions' => json_encode(['all']),
                'department' => 'Administration',
                'is_active' => true,
            ]
        );
    }
}
