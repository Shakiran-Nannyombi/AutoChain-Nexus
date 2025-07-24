<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VendorOrderSeeder extends Seeder
{
    public function run()
    {
        // Car products and prices
        $carProducts = [
            ['name' => 'Toyota Corolla', 'price' => 250000000],
            ['name' => 'Honda Civic', 'price' => 240000000],
            ['name' => 'Ford F-150', 'price' => 350000000],
            ['name' => 'BMW 3 Series', 'price' => 420000000],
            ['name' => 'Mercedes-Benz C-Class', 'price' => 450000000],
            ['name' => 'Audi A4', 'price' => 410000000],
            ['name' => 'Volkswagen Golf', 'price' => 230000000],
            ['name' => 'Hyundai Sonata', 'price' => 220000000],
            ['name' => 'Kia K5', 'price' => 210000000],
            ['name' => 'Mazda 3', 'price' => 200000000],
        ];

        // Insert products if not already present
        foreach ($carProducts as $car) {
            DB::table('products')->updateOrInsert(
                ['name' => $car['name']],
                ['price' => $car['price'], 'category' => 'Car', 'stock' => 100, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $statuses = ['pending', 'fulfilled', 'cancelled'];
        $now = Carbon::now();
        $vendors = DB::table('vendors')->pluck('user_id'); // All vendor user_ids
        $manufacturers = DB::table('manufacturers')->pluck('user_id');
        $orders = [];

        foreach ($vendors as $vendor_user_id) {
            // Each vendor will order a random subset of products
            $productsForVendor = collect($carProducts)->shuffle()->take(rand(3, 7));
            foreach ($productsForVendor as $car) {
                // Some products are more popular: weighted random
                $popularity = [
                    'Toyota Corolla' => rand(8, 15),
                    'Honda Civic' => rand(5, 12),
                    'Ford F-150' => rand(3, 10),
                    'BMW 3 Series' => rand(1, 8),
                    'Mercedes-Benz C-Class' => rand(1, 8),
                    'Audi A4' => rand(1, 6),
                    'Volkswagen Golf' => rand(1, 6),
                    'Hyundai Sonata' => rand(1, 6),
                    'Kia K5' => rand(1, 6),
                    'Mazda 3' => rand(1, 6),
                ];
                $orderCount = $popularity[$car['name']] ?? rand(1, 10);
                for ($i = 0; $i < $orderCount; $i++) {
                    $quantity = rand(1, 50);
                    $status = (rand(1, 100) <= 30) ? 'accepted' : $statuses[array_rand($statuses)];
                    $acceptedAt = $status === 'accepted' ? $now->copy()->subDays(rand(1, 90))->addHours(rand(1, 12)) : null;
                    $orders[] = [
                        'manufacturer_id' => $manufacturers->random(),
                        'vendor_id' => $vendor_user_id,
                        'product' => $car['name'],
                        'product_name' => $car['name'],
                        'product_category' => 'Car',
                        'quantity' => $quantity,
                        'unit_price' => $car['price'],
                        'total_amount' => $car['price'] * $quantity,
                        'status' => $status,
                        'ordered_at' => $now->copy()->subDays(rand(1, 90)),
                        'accepted_at' => $acceptedAt,
                        'notes' => 'Seeded order for demo invoice. Delivery expected in 7-14 days.',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }
        DB::table('vendor_orders')->insert($orders);
    }
} 