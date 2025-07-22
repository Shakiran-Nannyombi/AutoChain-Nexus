<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ManufacturerOrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Demo Supplier Orders (ChecklistRequest)
        DB::table('checklist_requests')->insert([
            [
                'manufacturer_id' => 9, // Sarah Johnson
                'supplier_id' => 15, // Lisa Wang
                'materials_requested' => json_encode(['Steel' => 10, 'Rubber' => 5]),
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'manufacturer_id' => 10, // Michael Chen
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Plastic' => 20]),
                'status' => 'fulfilled',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'manufacturer_id' => 11, // Emily Rodriguez
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Copper' => 15]),
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'manufacturer_id' => 12, // Priya Patel
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Glass' => 8, 'Iron' => 12]),
                'status' => 'cancelled',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'manufacturer_id' => 13, // Liam O'Connor
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Nickel' => 7, 'Lead' => 11]),
                'status' => 'fulfilled',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ]);

        // Enhanced Vendor Orders for Demand Prediction (12 months: last 6 of 2024, first 6 of 2025)
        $manufacturerUserId = DB::table('users')->where('role', 'manufacturer')->where('status', 'approved')->value('id');
        $vendorUserIds = DB::table('users')->where('role', 'vendor')->where('status', 'approved')->pluck('id');
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

        // Add vendor orders with different statuses for testing
        \App\Models\VendorOrder::create([
            'manufacturer_id' => 1,
            'vendor_id' => 2,
            'product' => 'Toyota Corolla',
            'quantity' => 3,
            'status' => 'pending',
            'ordered_at' => now(),
        ]);
        \App\Models\VendorOrder::create([
            'manufacturer_id' => 1,
            'vendor_id' => 3,
            'product' => 'Kia K5',
            'quantity' => 2,
            'status' => 'accepted',
            'ordered_at' => now()->subDay(),
        ]);
        \App\Models\VendorOrder::create([
            'manufacturer_id' => 1,
            'vendor_id' => 4,
            'product' => 'Ford F-150',
            'quantity' => 1,
            'status' => 'rejected',
            'ordered_at' => now()->subDays(2),
        ]);

        // Demo Confirmed Deliveries (fulfilled orders)
        DB::table('deliveries')->insert([
            [
                'supplier_id' => 14, // David Thompson
                'manufacturer_id' => 9, // Sarah Johnson
                'materials_delivered' => json_encode(['Steel' => 10, 'Rubber' => 5]),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'supplier_id' => 15, // Lisa Wang
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Plastic' => 20, 'Glass' => 8]),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'supplier_id' => 16, // Robert Kim
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Copper' => 15, 'Aluminum' => 12]),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'supplier_id' => 17, // Olga Ivanova
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Iron' => 18, 'Nickel' => 7]),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'supplier_id' => 18, // Ahmed Hassan
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Zinc' => 9, 'Lead' => 11]),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ]);
    }
} 