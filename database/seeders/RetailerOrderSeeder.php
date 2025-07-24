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
        // Get all vendors (approved or not) and retailers
        $vendors = User::where('role', 'vendor')->get();
        $retailers = User::where('role', 'retailer')->get();
        
        // Make sure vendor with ID 4 is included
        $vendor4 = User::find(4);
        if ($vendor4 && !$vendors->contains($vendor4)) {
            $vendors->push($vendor4);
        }

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

        // Ensure every approved retailer gets at least 3 orders
        foreach ($retailers as $retailer) {
            for ($i = 0; $i < 3; $i++) {
                $vendor = $vendors->random();
                $status = $statuses[array_rand($statuses)];
                $carModel = $carModels[array_rand($carModels)];
                $customerName = $customerNames[array_rand($customerNames)];
                $quantity = rand(1, 5);
                $totalAmount = $quantity * rand(25000, 50000);

                $order = RetailerOrder::create([
                    'user_id' => $retailer->id,
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

        // Add a guaranteed demo pending order for testing
        $demoVendor = $vendors->first();
        $demoRetailer = $retailers->first();
        if ($demoVendor && $demoRetailer) {
            $order = RetailerOrder::create([
                'user_id' => $demoRetailer->id,
                'vendor_id' => $demoVendor->id,
                'customer_name' => 'Demo Customer',
                'car_model' => 'Demo Car Model 2024',
                'quantity' => 2,
                'status' => 'pending',
                'total_amount' => 123456,
                'ordered_at' => now(),
                'notes' => 'This is a guaranteed demo pending order for UI testing.',
            ]);
        }
        
        // Create orders specifically for vendor ID 4
        $vendor4 = User::find(4);
        if ($vendor4) {
            foreach ($retailers as $retailer) {
                // Create orders with different statuses
                foreach (['pending', 'confirmed', 'shipped', 'delivered'] as $status) {
                    $carModel = $carModels[array_rand($carModels)];
                    $customerName = $customerNames[array_rand($customerNames)];
                    $quantity = rand(1, 5);
                    $totalAmount = $quantity * rand(25000, 50000);
                    
                    $order = RetailerOrder::create([
                        'user_id' => $retailer->id,
                        'vendor_id' => $vendor4->id,
                        'customer_name' => $customerName . ' (Vendor 4)',
                        'car_model' => $carModel,
                        'quantity' => $quantity,
                        'status' => $status,
                        'total_amount' => $totalAmount,
                        'ordered_at' => Carbon::now()->subDays(rand(1, 30)),
                        'notes' => 'Order for Vendor ID 4 testing.',
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
            
            $this->command->info('Created orders specifically for vendor ID 4');
        }

        $this->command->info('Retailer orders seeded successfully!');
    }
}
