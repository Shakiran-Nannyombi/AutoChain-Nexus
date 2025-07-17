<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class ManufacturerProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create manufacturers
        $manufacturers = [
            [
                'name' => 'Toyota Manufacturing Co.',
                'email' => 'orders@toyota-mfg.com',
                'password' => Hash::make('password123'),
                'role' => 'manufacturer',
                'status' => 'active',
                'company' => 'Toyota Motor Corporation',
                'phone' => '+1-800-TOYOTA-1',
                'address' => '1 Toyota Way, Toyota City, Aichi, Japan',
            ],
            [
                'name' => 'Honda Production Ltd.',
                'email' => 'orders@honda-prod.com',
                'password' => Hash::make('password123'),
                'role' => 'manufacturer',
                'status' => 'active',
                'company' => 'Honda Motor Co., Ltd.',
                'phone' => '+1-800-HONDA-1',
                'address' => '2-1 Minami-Aoyama, Minato, Tokyo, Japan',
            ],
        ];

        $manufacturerIds = [];
        foreach ($manufacturers as $data) {
            $manufacturer = User::create($data);
            $manufacturerIds[] = $manufacturer->id;
        }

        // Create products for each manufacturer
        $products = [
            [
                'name' => 'Toyota Corolla 2024',
                'category' => 'Sedan',
                'price' => 25000,
                'stock' => 10,
                'manufacturer_id' => $manufacturerIds[0],
            ],
            [
                'name' => 'Toyota Camry 2024',
                'category' => 'Sedan',
                'price' => 28000,
                'stock' => 8,
                'manufacturer_id' => $manufacturerIds[0],
            ],
            [
                'name' => 'Honda Civic 2024',
                'category' => 'Sedan',
                'price' => 24000,
                'stock' => 12,
                'manufacturer_id' => $manufacturerIds[1],
            ],
            [
                'name' => 'Honda Accord 2024',
                'category' => 'Sedan',
                'price' => 27000,
                'stock' => 8,
                'manufacturer_id' => $manufacturerIds[1],
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
} 