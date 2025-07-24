<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderItem;
use Carbon\Carbon;

class SupplierOrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get all approved suppliers
        $suppliers = User::where('role', 'supplier')->where('status', 'approved')->get();
        if ($suppliers->isEmpty()) {
            $this->command->info('No approved suppliers found.');
            return;
        }
        $statuses = ['pending', 'fulfilled', 'declined'];
        $products = ['Steel Sheets', 'Aluminum Rods', 'Plastic Components', 'Electronic Chips', 'Rubber Gaskets'];
        foreach ($suppliers as $supplier) {
            for ($i = 0; $i < 3; $i++) {
                $order = SupplierOrder::create([
                    'supplier_id' => $supplier->id,
                    'order_date' => Carbon::now()->subDays(rand(0, 30)),
                    'status' => $statuses[$i % count($statuses)],
                ]);
                // Add 2-4 items per order
                $itemCount = rand(2, 4);
                for ($j = 0; $j < $itemCount; $j++) {
                    SupplierOrderItem::create([
                        'supplier_order_id' => $order->id,
                        'product_name' => $products[array_rand($products)],
                        'quantity' => rand(5, 50),
                        'unit_price' => rand(100, 1000),
                    ]);
                }
            }
        }
    }
} 