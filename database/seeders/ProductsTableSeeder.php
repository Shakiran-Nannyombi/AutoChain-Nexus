<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $manufacturers = User::where('role', 'manufacturer')->get();
        $products = [
            ['name' => 'Toyota Corolla', 'price' => 250000.00, 'category' => 'Sedan', 'stock' => 3],
            ['name' => 'Honda Civic', 'price' => 240000.00, 'category' => 'Sedan', 'stock' => 2],
            ['name' => 'Ford F-150', 'price' => 350000.00, 'category' => 'Truck', 'stock' => 100],
            ['name' => 'BMW 3 Series', 'price' => 420000.00, 'category' => 'Sedan', 'stock' => 1],
            ['name' => 'Mercedes-Benz C-Class', 'price' => 450000.00, 'category' => 'Sedan', 'stock' => 0],
            ['name' => 'Audi A4', 'price' => 410000.00, 'category' => 'Sedan', 'stock' => 100],
            ['name' => 'Volkswagen Golf', 'price' => 230000.00, 'category' => 'Hatchback', 'stock' => 100],
            ['name' => 'Hyundai Sonata', 'price' => 220000.00, 'category' => 'Sedan', 'stock' => 100],
            ['name' => 'Kia K5', 'price' => 210000.00, 'category' => 'Sedan', 'stock' => 100],
            ['name' => 'Mazda 3', 'price' => 200000.00, 'category' => 'Sedan', 'stock' => 100],
        ];
        $insertData = [];
        foreach ($manufacturers as $manufacturer) {
            foreach ($products as $product) {
                $insertData[] = array_merge($product, ['manufacturer_id' => $manufacturer->id]);
            }
        }
        Product::insert($insertData);
    }
} 