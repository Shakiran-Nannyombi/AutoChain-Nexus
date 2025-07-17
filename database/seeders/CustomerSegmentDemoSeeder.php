<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Product;

class CustomerSegmentDemoSeeder extends Seeder
{
    public function run()
    {
        // Clear existing demo data (optional)
        // Customer::truncate();
        // Purchase::truncate();

        // Segment 1: Occasional Buyers
        $occasional = Customer::factory()->count(5)->create(['segment' => 1]);
        foreach ($occasional as $customer) {
            Purchase::factory()->count(rand(1, 2))->create([
                'customer_id' => $customer->id,
                'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
                'amount' => rand(20, 100),
                'purchase_date' => now()->subDays(rand(30, 365)),
            ]);
        }

        // Segment 2: High Value Customers
        $highValue = Customer::factory()->count(5)->create(['segment' => 2]);
        foreach ($highValue as $customer) {
            Purchase::factory()->count(rand(5, 10))->create([
                'customer_id' => $customer->id,
                'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
                'amount' => rand(500, 2000),
                'purchase_date' => now()->subDays(rand(1, 60)),
            ]);
        }

        // Segment 3: At Risk Customers
        $atRisk = Customer::factory()->count(5)->create(['segment' => 3]);
        foreach ($atRisk as $customer) {
            // Last purchase was a long time ago, few purchases
            Purchase::factory()->count(rand(1, 2))->create([
                'customer_id' => $customer->id,
                'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
                'amount' => rand(20, 200),
                'purchase_date' => now()->subDays(rand(180, 730)),
            ]);
        }
    }
} 