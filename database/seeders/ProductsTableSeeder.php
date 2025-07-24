<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $carImages = [
            'images/car1.png',
            'images/car2.png',
            'images/car3.png',
            'images/car4.png',
            'images/car5.png',
            'images/car6.png',
            'images/car7.png',
            'images/car8.png',
            'images/car9.png',
            'images/car10.png',
        ];
        $productNames = [
            'Toyota Corolla', 'Honda Civic', 'Ford F-150', 'BMW 3 Series', 'Mercedes-Benz C-Class',
            'Audi A4', 'Volkswagen Golf', 'Hyundai Sonata', 'Kia K5', 'Mazda 3'
        ];
        $categories = ['Sedan', 'Truck', 'Hatchback', 'SUV', 'Coupe', 'Convertible', 'Wagon', 'Van', 'Crossover', 'Sports Car'];
        $prices = [250000000, 240000000, 350000000, 420000000, 450000000, 410000000, 230000000, 220000000, 210000000, 200000000];
        $stocks = [15, 12, 36, 15, 31, 7, 87, 9, 20, 40];
        $vendors = \App\Models\User::where('role', 'vendor')->get();
        $manufacturers = \App\Models\User::where('role', 'manufacturer')->get();
        $insertData = [];
        $productIndex = 0;
        // Assign products to vendors
        foreach ($vendors as $vendor) {
            for ($i = 0; $i < count($carImages); $i++) {
                $insertData[] = [
                    'name' => $productNames[$i],
                    'price' => $prices[$i],
                    'category' => $categories[$i],
                    'stock' => rand(100, 200),
                    'image_url' => $carImages[$i],
                    'vendor_id' => $vendor->id,
                    'manufacturer_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        // Assign products to manufacturers
        foreach ($manufacturers as $manufacturer) {
            for ($i = 0; $i < count($carImages); $i++) {
                $insertData[] = [
                    'name' => $productNames[$i],
                    'price' => $prices[$i],
                    'category' => $categories[$i],
                    'stock' => rand(100, 200),
                    'image_url' => $carImages[$i],
                    'vendor_id' => null,
                    'manufacturer_id' => $manufacturer->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('products')->insert($insertData);
    }
} 