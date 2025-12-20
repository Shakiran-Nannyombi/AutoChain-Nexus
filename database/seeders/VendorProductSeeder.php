<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;

class VendorProductSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create Amanda Foster as a vendor
        $vendor = User::firstOrCreate(
            [
                'email' => 'amanda.foster@example.com',
            ],
            [
                'name' => 'Amanda Foster',
                'role' => 'vendor',
                'status' => 'approved',
                'password' => bcrypt('password123'),
            ]
        );

        // Create products for Amanda Foster
        $products = [
            [
                'name' => 'Toyota Camry 2024',
                'category' => 'Sedan',
                'price' => 27000,
                'stock' => 15,
            ],
            [
                'name' => 'Honda Accord 2024',
                'category' => 'Sedan',
                'price' => 26500,
                'stock' => 12,
            ],
            [
                'name' => 'Ford Escape 2024',
                'category' => 'SUV',
                'price' => 32000,
                'stock' => 8,
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
                [
                    'name' => $data['name'],
                    'vendor_id' => $vendor->id,
                ],
                array_merge($data, ['vendor_id' => $vendor->id])
            );
        }

        $this->command->info('Products seeded for vendor Amanda Foster.');
    }
}
