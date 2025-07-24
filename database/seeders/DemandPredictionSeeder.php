<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DemandPredictionSeeder extends Seeder
{
    public function run(): void
    {
        // Car products and prices
        $carProducts = [
            ['name' => 'Toyota Corolla', 'category' => 'Sedan', 'price' => 250000],
            ['name' => 'Honda Civic', 'category' => 'Sedan', 'price' => 240000],
            ['name' => 'Ford F-150', 'category' => 'Truck', 'price' => 350000],
            ['name' => 'BMW 3 Series', 'category' => 'Sedan', 'price' => 420000],
            ['name' => 'Mercedes-Benz C-Class', 'category' => 'Sedan', 'price' => 450000],
            ['name' => 'Audi A4', 'category' => 'Sedan', 'price' => 410000],
            ['name' => 'Volkswagen Golf', 'category' => 'Hatchback', 'price' => 230000],
            ['name' => 'Hyundai Sonata', 'category' => 'Sedan', 'price' => 220000],
            ['name' => 'Kia K5', 'category' => 'Sedan', 'price' => 210000],
            ['name' => 'Mazda 3', 'category' => 'Sedan', 'price' => 200000],
        ];
        $manufacturerUserId = DB::table('users')->where('role', 'manufacturer')->where('status', 'approved')->value('id');
        $vendorUserIds = DB::table('users')->where('role', 'vendor')->where('status', 'approved')->pluck('id');
        if ($manufacturerUserId && $vendorUserIds->count() > 0) {
            $orders = [];
            // Last 6 months of 2024
            for ($m = 7; $m <= 12; $m++) {
                foreach ($carProducts as $car) {
                    $vendorId = $vendorUserIds[rand(0, $vendorUserIds->count() - 1)];
                    $quantity = rand(2, 15);
                    $status = 'fulfilled';
                    $orderDate = Carbon::create(2024, $m, 15, 12, 0, 0);
                    $orders[] = [
                        'manufacturer_id' => $manufacturerUserId,
                        'vendor_id' => $vendorId,
                        'product' => $car['name'],
                        'product_name' => $car['name'],
                        'product_category' => $car['category'],
                        'quantity' => $quantity,
                        'unit_price' => $car['price'],
                        'total_amount' => $car['price'] * $quantity,
                        'status' => $status,
                        'ordered_at' => $orderDate,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ];
                }
            }
            // First 6 months of 2025
            for ($m = 1; $m <= 6; $m++) {
                foreach ($carProducts as $car) {
                    $vendorId = $vendorUserIds[rand(0, $vendorUserIds->count() - 1)];
                    $quantity = rand(2, 15);
                    $status = 'fulfilled';
                    $orderDate = Carbon::create(2025, $m, 15, 12, 0, 0);
                    $orders[] = [
                        'manufacturer_id' => $manufacturerUserId,
                        'vendor_id' => $vendorId,
                        'product' => $car['name'],
                        'product_name' => $car['name'],
                        'product_category' => $car['category'],
                        'quantity' => $quantity,
                        'unit_price' => $car['price'],
                        'total_amount' => $car['price'] * $quantity,
                        'status' => $status,
                        'ordered_at' => $orderDate,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ];
                }
            }
            DB::table('vendor_orders')->insert($orders);
        }
    }
} 