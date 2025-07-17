<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RetailerOrder;
use App\Models\User;
use Carbon\Carbon;

class RetailerOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some vendors and retailers
        $vendors = User::where('role', 'vendor')->take(3)->get();
        $retailers = User::where('role', 'retailer')->take(5)->get();

        if ($vendors->isEmpty() || $retailers->isEmpty()) {
            $this->command->info('No vendors or retailers found. Please create some users first.');
            return;
        }

        $carModels = [
            'Toyota Corolla 2024',
            'Honda Civic 2024',
            'Ford F-150 2024',
            'BMW 3 Series 2024',
            'Mercedes-Benz C-Class 2024',
            'Audi A4 2024',
            'Volkswagen Golf 2024',
            'Hyundai Sonata 2024',
            'Kia K5 2024',
            'Mazda 3 2024'
        ];

        $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'rejected'];
        $customerNames = [
            'John Smith',
            'Sarah Johnson',
            'Michael Brown',
            'Emily Davis',
            'David Wilson',
            'Lisa Anderson',
            'Robert Taylor',
            'Jennifer Martinez',
            'Christopher Garcia',
            'Amanda Rodriguez'
        ];

        foreach ($vendors as $vendor) {
            // Create 3-5 orders for each vendor
            for ($i = 0; $i < rand(3, 5); $i++) {
                $status = $statuses[array_rand($statuses)];
                $retailer = $retailers->random();
                $carModel = $carModels[array_rand($carModels)];
                $customerName = $customerNames[array_rand($customerNames)];
                $quantity = rand(1, 5);
                $totalAmount = $quantity * rand(25000, 50000);
                
                $order = RetailerOrder::create([
                    'retailer_id' => $retailer->id,
                    'vendor_id' => $vendor->id,
                    'customer_name' => $customerName,
                    'car_model' => $carModel,
                    'quantity' => $quantity,
                    'status' => $status,
                    'total_amount' => $totalAmount,
                    'ordered_at' => Carbon::now()->subDays(rand(1, 30)),
                    'notes' => rand(0, 1) ? 'Sample order notes for testing purposes.' : null,
                ]);

                // Add timestamps based on status
                if ($status !== 'pending') {
                    $order->confirmed_at = Carbon::now()->subDays(rand(1, 25));
                }
                
                if (in_array($status, ['shipped', 'delivered'])) {
                    $order->shipped_at = Carbon::now()->subDays(rand(1, 20));
                }
                
                if ($status === 'delivered') {
                    $order->delivered_at = Carbon::now()->subDays(rand(1, 15));
                }

                $order->save();
            }
        }

        $this->command->info('Retailer orders seeded successfully!');
    }
}
