<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class BulkAssignProductImagesSeeder extends Seeder
{
    public function run()
    {
        // List of car images in public/images/
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
        $products = Product::all();
        $i = 0;
        foreach ($products as $product) {
            $product->image_url = $carImages[$i % count($carImages)];
            $product->save();
            $i++;
        }
    }
} 