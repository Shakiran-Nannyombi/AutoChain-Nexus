<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            ['name' => 'Toyota Corolla', 'price' => 18000.00],
            ['name' => 'Honda Civic', 'price' => 20000.00],
            ['name' => 'Ford F-150', 'price' => 35000.00],
            ['name' => 'BMW 3 Series', 'price' => 42000.00],
            ['name' => 'Car Battery', 'price' => 120.00],
            ['name' => 'Engine Oil', 'price' => 40.00],
            ['name' => 'Brake Pads', 'price' => 90.00],
            ['name' => 'Tire', 'price' => 150.00],
        ]);
    }
} 