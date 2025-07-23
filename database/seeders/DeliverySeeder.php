<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Delivery;
use App\Models\RetailerOrder;
use Carbon\Carbon;

class DeliverySeeder extends Seeder
{
    public function run(): void
    {
        $drivers = ['John Smith', 'Sarah Johnson', 'Mike Davis', 'Lisa Wilson'];
        $destinations = ['New York, NY', 'Los Angeles, CA', 'Chicago, IL', 'Houston, TX'];
        $statuses = ['pending', 'shipped', 'delivered'];

        $supplier = \App\Models\User::where('role', 'supplier')->first();
        $manufacturer = \App\Models\User::where('role', 'manufacturer')->first();
        if (!$supplier || !$manufacturer) return;
        $orders = RetailerOrder::take(10)->get();
        foreach ($orders as $i => $order) {
            $status = $statuses[array_rand($statuses)];
            $progress = $status === 'pending' ? 0 : ($status === 'shipped' ? rand(10, 90) : 100);
            $eta = Carbon::now()->addDays(rand(1, 5))->format('Y-m-d H:i');
            $tracking = [
                ['status' => 'Package loaded', 'location' => 'Warehouse', 'timestamp' => Carbon::now()->subDays(2)->format('Y-m-d H:i')],
                ['status' => 'Departed', 'location' => 'Warehouse', 'timestamp' => Carbon::now()->subDay()->format('Y-m-d H:i')],
                ['status' => $status === 'delivered' ? 'Delivered' : 'In transit', 'location' => $destinations[$i % count($destinations)], 'timestamp' => Carbon::now()->format('Y-m-d H:i')],
            ];
            Delivery::create([
                'supplier_id' => $supplier->id,
                'manufacturer_id' => $manufacturer->id,
                'retailer_order_id' => $order->id,
                'status' => $status,
                'driver' => $drivers[$i % count($drivers)],
                'destination' => $destinations[$i % count($destinations)],
                'progress' => $progress,
                'eta' => $eta,
                'tracking_history' => $tracking,
                'materials_delivered' => [
                    ['item' => $order->car_model, 'quantity' => $order->quantity]
                ],
            ]);
        }
    }
} 