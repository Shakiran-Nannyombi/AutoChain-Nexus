<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorOrder;
use App\Models\User;
use Carbon\Carbon;

class VendorOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some vendors and manufacturers
        $vendors = User::where('role', 'vendor')->where('status', 'approved')->take(3)->get();
        $manufacturers = User::where('role', 'manufacturer')->where('status', 'approved')->take(2)->get();

        if ($vendors->isEmpty() || $manufacturers->isEmpty()) {
            $this->command->info('No vendors or manufacturers found. Please create some users first.');
            return;
        }

        $products = [
            [
                'id' => 1,
                'name' => 'Toyota Corolla 2024',
                'category' => 'Sedan',
                'base_price' => 25000
            ],
            [
                'id' => 2,
                'name' => 'Honda Civic 2024',
                'category' => 'Sedan',
                'base_price' => 24000
            ],
            [
                'id' => 3,
                'name' => 'Ford F-150 2024',
                'category' => 'Truck',
                'base_price' => 35000
            ],
            [
                'id' => 4,
                'name' => 'BMW 3 Series 2024',
                'category' => 'Luxury',
                'base_price' => 45000
            ],
            [
                'id' => 5,
                'name' => 'Mercedes-Benz C-Class 2024',
                'category' => 'Luxury',
                'base_price' => 48000
            ]
        ];

        $statuses = ['pending', 'accepted', 'rejected'];
        $deliveryAddresses = [
            '123 Main St, Anytown, CA 90210',
            '456 Oak Ave, Somewhere, NY 10001',
            '789 Pine Rd, Elsewhere, TX 75001',
            '321 Elm St, Nowhere, FL 33101',
            '654 Maple Dr, Anywhere, WA 98101'
        ];

        foreach ($vendors as $vendor) {
            // Create 2-4 orders for each vendor
            for ($i = 0; $i < rand(2, 4); $i++) {
                $status = $statuses[array_rand($statuses)];
                $manufacturer = $manufacturers->random();
                $product = $products[array_rand($products)];
                $quantity = rand(1, 5);
                $unitPrice = $product['base_price'];
                $totalAmount = $unitPrice * $quantity;
                
                $order = VendorOrder::create([
                    'manufacturer_id' => $manufacturer->id,
                    'vendor_id' => $vendor->id,
                    'product' => $product['id'],
                    'product_name' => $product['name'],
                    'product_category' => $product['category'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_amount' => $totalAmount,
                    'status' => $status,
                    'ordered_at' => Carbon::now()->subDays(rand(1, 30)),
                    'delivery_address' => $deliveryAddresses[array_rand($deliveryAddresses)],
                    'expected_delivery_date' => Carbon::now()->addDays(rand(7, 30)),
                    'notes' => rand(0, 1) ? 'Sample order notes for testing purposes.' : null,
                ]);

                // Add timestamps based on status
                if ($status === 'accepted') {
                    $order->accepted_at = Carbon::now()->subDays(rand(1, 25));
                } elseif ($status === 'rejected') {
                    $order->rejected_at = Carbon::now()->subDays(rand(1, 25));
                    $order->rejection_reason = 'Sample rejection reason for testing.';
                }

                $order->save();
            }
        }

        $this->command->info('Vendor orders seeded successfully!');
    }
}
