<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Product;

class VarietyCustomerPurchaseSeeder extends Seeder
{
    public function run()
    {
        // High Value: 10–20 purchases, $1000–$5000, last 60 days
        $highValue = Customer::factory()->count(10)->create(['segment' => 2]);
        foreach ($highValue as $customer) {
            for ($i = 0; $i < rand(10, 20); $i++) {
                Purchase::factory()->create([
                    'customer_id' => $customer->id,
                    'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
                    'amount' => rand(1000, 5000),
                    'purchase_date' => now()->subDays(rand(1, 60)),
                ]);
            }
        }

        // Occasional: 1–2 purchases, $20–$80, 6–24 months ago
        $occasional = Customer::factory()->count(10)->create(['segment' => 1]);
        foreach ($occasional as $customer) {
            for ($i = 0; $i < rand(1, 2); $i++) {
                Purchase::factory()->create([
                    'customer_id' => $customer->id,
                    'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
                    'amount' => rand(20, 80),
                    'purchase_date' => now()->subDays(rand(180, 730)),
                ]);
            }
        }

        // At Risk: 1 purchase, $20–$100, 12–36 months ago
        $atRisk = Customer::factory()->count(10)->create(['segment' => 3]);
        foreach ($atRisk as $customer) {
            Purchase::factory()->create([
                'customer_id' => $customer->id,
                'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
                'amount' => rand(20, 100),
                'purchase_date' => now()->subDays(rand(365, 1095)),
            ]);
        }
    }
} 