<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use Carbon\Carbon;

class PurchasesTableSeeder extends Seeder
{
    public function run()
    {
        Purchase::insert([
            // High spender, recent
            ['customer_id' => 1, 'product_id' => 1, 'amount' => 42000.00, 'purchase_date' => Carbon::now()->subDays(3), 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 1, 'product_id' => 2, 'amount' => 20000.00, 'purchase_date' => Carbon::now()->subDays(10), 'created_at' => now(), 'updated_at' => now()],
            // Frequent, moderate spender
            ['customer_id' => 2, 'product_id' => 3, 'amount' => 35000.00, 'purchase_date' => Carbon::now()->subDays(15), 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 2, 'product_id' => 4, 'amount' => 42000.00, 'purchase_date' => Carbon::now()->subDays(20), 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 2, 'product_id' => 5, 'amount' => 120.00, 'purchase_date' => Carbon::now()->subDays(25), 'created_at' => now(), 'updated_at' => now()],
            // Infrequent, low spender
            ['customer_id' => 3, 'product_id' => 6, 'amount' => 40.00, 'purchase_date' => Carbon::now()->subDays(100), 'created_at' => now(), 'updated_at' => now()],
            // New customer, recent purchase
            ['customer_id' => 4, 'product_id' => 7, 'amount' => 90.00, 'purchase_date' => Carbon::now()->subDays(1), 'created_at' => now(), 'updated_at' => now()],
            // Budget buyer, old purchase
            ['customer_id' => 5, 'product_id' => 8, 'amount' => 150.00, 'purchase_date' => Carbon::now()->subDays(200), 'created_at' => now(), 'updated_at' => now()],
            // Fleet, many purchases
            ['customer_id' => 6, 'product_id' => 1, 'amount' => 18000.00, 'purchase_date' => Carbon::now()->subDays(5), 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 6, 'product_id' => 2, 'amount' => 20000.00, 'purchase_date' => Carbon::now()->subDays(7), 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 6, 'product_id' => 3, 'amount' => 35000.00, 'purchase_date' => Carbon::now()->subDays(9), 'created_at' => now(), 'updated_at' => now()],
            // Quick Fix, small parts
            ['customer_id' => 7, 'product_id' => 5, 'amount' => 120.00, 'purchase_date' => Carbon::now()->subDays(2), 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 7, 'product_id' => 6, 'amount' => 40.00, 'purchase_date' => Carbon::now()->subDays(3), 'created_at' => now(), 'updated_at' => now()],
            // Elite, high value
            ['customer_id' => 8, 'product_id' => 4, 'amount' => 42000.00, 'purchase_date' => Carbon::now()->subDays(4), 'created_at' => now(), 'updated_at' => now()],
            // Urban, moderate
            ['customer_id' => 9, 'product_id' => 2, 'amount' => 20000.00, 'purchase_date' => Carbon::now()->subDays(30), 'created_at' => now(), 'updated_at' => now()],
            // Classic, old purchase
            ['customer_id' => 10, 'product_id' => 3, 'amount' => 35000.00, 'purchase_date' => Carbon::now()->subDays(365), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
} 