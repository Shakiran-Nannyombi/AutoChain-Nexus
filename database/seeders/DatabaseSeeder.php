<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AdminUserSeeder::class,
            ValidationRuleSeeder::class,
            FacilityVisitSeeder::class,
        ]);

        $roles = ['manufacturer', 'supplier', 'vendor', 'retailer', 'analyst'];

        foreach ($roles as $role) {
            User::firstOrCreate(
                ['email' => $role . '@example.com'],
                [
                    'name' => ucfirst($role) . ' User',
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'status' => 'pending',
                    'company' => ucfirst($role) . ' Company',
                    'phone' => '123-456-7890',
                    'address' => '123 ' . ucfirst($role) . ' Street',
                ]
            );
        }
    }
}
