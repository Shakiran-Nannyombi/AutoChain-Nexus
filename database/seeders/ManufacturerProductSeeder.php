<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\User;

class ManufacturerProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first approved manufacturer
        $manufacturer = User::where('role', 'manufacturer')->where('status', 'approved')->first();
        if (!$manufacturer) {
            Log::warning('No approved manufacturer found for ManufacturerProductSeeder.');
            return;
        }
        $manufacturerId = $manufacturer->id;
        // Unique products from segmentation
        $products = [
            ['name' => 'Toyota Corolla', 'category' => 'Sedan', 'price' => 25000000, 'stock' => 100],
            ['name' => 'Honda Civic', 'category' => 'Sedan', 'price' => 34000000, 'stock' => 200],
            ['name' => 'Ford F-150', 'category' => 'Truck', 'price' => 350000000, 'stock' => 50],
            ['name' => 'BMW 3 Series', 'category' => 'Sedan', 'price' => 420000000, 'stock' => 190],
            ['name' => 'Mercedes-Benz C-Class', 'category' => 'Sedan', 'price' => 450000000, 'stock' => 175],
            ['name' => 'Audi A4', 'category' => 'Sedan', 'price' => 41000000, 'stock' => 19],
            ['name' => 'Volkswagen Golf', 'category' => 'Hatchback', 'price' => 230000000, 'stock' => 145],
            ['name' => 'Hyundai Sonata', 'category' => 'Sedan', 'price' => 320000000, 'stock' => 60],
            ['name' => 'Kia K5', 'category' => 'Sedan', 'price' => 410000000, 'stock' => 185],
            ['name' => 'Mazda 3', 'category' => 'Sedan', 'price' => 20000000, 'stock' => 161],
        ];
        foreach ($products as $product) {
            Product::updateOrCreate(
                [
                    'name' => $product['name'],
                    'manufacturer_id' => $manufacturerId,
                ],
                [
                    'category' => $product['category'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                ]
            );
        }
    }
} 