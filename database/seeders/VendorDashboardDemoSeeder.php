<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\RetailerStock;
use App\Models\Purchase;
use Carbon\Carbon;

class VendorDashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Get all approved vendors
        $vendors = User::where('role', 'vendor')->where('status', 'approved')->get();
        if ($vendors->isEmpty()) {
            $this->command->info('No approved vendors found.');
            return;
        }
        // Ensure there are demo customers
        $customerCount = \App\Models\Customer::count();
        if ($customerCount < 10) {
            \App\Models\Customer::factory()->count(10 - $customerCount)->create();
        }
        $customers = \App\Models\Customer::pluck('id')->toArray();
        // Ensure there are demo retailers
        $retailerCount = \App\Models\Retailer::count();
        if ($retailerCount < 5) {
            \App\Models\Retailer::factory()->count(5 - $retailerCount)->create();
        }
        $retailers = \App\Models\Retailer::pluck('id')->toArray();
        foreach ($vendors as $vendor) {
            // Add demo products
            $products = [
                ['name' => 'Toyota Corolla 2024', 'stock' => 10],
                ['name' => 'Honda Civic 2024', 'stock' => 8],
                ['name' => 'Ford F-150 2024', 'stock' => 5],
            ];
            $productIds = [];
            foreach ($products as $prod) {
                $product = Product::updateOrCreate(
                    ['vendor_id' => $vendor->id, 'name' => $prod['name']],
                    [
                        'category' => 'Car',
                        'price' => rand(20000, 40000),
                        'stock' => $prod['stock'],
                        'status' => 'active',
                    ]
                );
                $productIds[] = $product->id;
            }
            // Add demo retailer stocks with varied statuses and random retailers
            $statuses = ['pending', 'accepted', 'rejected'];
            for ($i = 0; $i < 3; $i++) {
                RetailerStock::create([
                    'retailer_id' => $retailers[array_rand($retailers)],
                    'vendor_id' => $vendor->id,
                    'vendor_name' => $vendor->name,
                    'car_model' => $products[$i]['name'],
                    'quantity_received' => rand(1, 5),
                    'status' => $statuses[$i % count($statuses)],
                ]);
            }
            // Add demo purchases with valid product_id and random customers
            for ($i = 0; $i < 5; $i++) {
                Purchase::create([
                    'vendor_id' => $vendor->id,
                    'customer_id' => $customers[array_rand($customers)],
                    'product_id' => $productIds[array_rand($productIds)],
                    'amount' => rand(10000, 50000),
                    'purchase_date' => Carbon::now()->subDays(rand(0, 30)),
                ]);
            }
        }
        $this->command->info('Demo data for all vendors seeded!');
    }
} 