<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RetailerStock;
use App\Models\User;
use App\Models\Product;

class RetailerStockTestSeeder extends Seeder
{
    public function run(): void
    {
        // Find a retailer user
        $retailer = User::where('role', 'retailer')->first();
        // Find a vendor user
        $vendor = User::where('role', 'vendor')->first();
        // Get all products
        $products = Product::all();
        if (!$retailer || !$vendor || $products->isEmpty()) {
            $this->command->info('Missing retailer, vendor, or products.');
            return;
        }
        foreach ($products as $product) {
            RetailerStock::updateOrCreate(
                [
                    'retailer_id' => $retailer->id,
                    'car_model' => $product->name,
                ],
                [
                    'status' => 'accepted',
                    'quantity_received' => 20,
                    'vendor_id' => $vendor->id,
                    'vendor_name' => $vendor->name,
                ]
            );
        }
        $this->command->info('Test retailer stock added for all products.');
    }
} 