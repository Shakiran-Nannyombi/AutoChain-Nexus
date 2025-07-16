<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VendorOrderSeeder extends Seeder
{
    public function run()
    {
        // Demo vendor and manufacturer user IDs (adjust as needed)
        $vendors = DB::table('vendors')->pluck('user_id', 'id'); // [vendor_id => user_id]
        $manufacturers = DB::table('manufacturers')->pluck('user_id', 'id'); // [manufacturer_id => user_id]
        if ($vendors->isEmpty() || $manufacturers->isEmpty()) {
            // Skip seeding if no vendors or manufacturers
            return;
        }
        $products = ['Steel Rods', 'Rubber Sheets', 'Plastic Moulds', 'Aluminum Sheets', 'Copper Wire'];
        $statuses = ['pending', 'fulfilled', 'cancelled'];
        $orders = [];
        $now = Carbon::now();
        foreach ($vendors as $vendor_id => $vendor_user_id) {
            foreach (array_rand($products, 3) as $i) {
                $orders[] = [
                    'manufacturer_id' => $manufacturers->random(),
                    'vendor_id' => $vendor_user_id,
                    'product' => $products[$i],
                    'quantity' => rand(10, 100),
                    'status' => $statuses[array_rand($statuses)],
                    'ordered_at' => $now->copy()->subDays(rand(1, 90)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        DB::table('vendor_orders')->insert($orders);
    }
} 