<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplyChain\Shipment;
use App\Models\SupplyChain\Suppliers;

class SupplyChainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Uncomment and use the following code if you need to seed demo data in the future:
        /*
        Shipment::create([
            'shipment_id' => 'SH-001',
            'origin' => 'Steel Corp',
            'destination' => 'Factory A',
            'progress' => 75,
            'eta' => '2 hours',
            'status' => 'in-transit',
            'expected_delivery_date' => now()->addHours(2),
        ]);

        Shipment::create([
            'shipment_id' => 'SH-002',
            'origin' => 'ElectroTech',
            'destination' => 'Factory B',
            'progress' => 100,
            'eta' => 'Delivered',
            'status' => 'delivered',
            'expected_delivery_date' => now()->subDays(1),
        ]);

        Shipment::create([
            'shipment_id' => 'SH-003',
            'origin' => 'Rubber Industries',
            'destination' => 'Warehouse C',
            'progress' => 0,
            'eta' => '6 hours',
            'status' => 'pending',
            'expected_delivery_date' => now()->addHours(6),
        ]);

        Shipment::create([
            'shipment_id' => 'SH-004',
            'origin' => 'Paint Solutions',
            'destination' => 'Paint Shop',
            'progress' => 45,
            'eta' => '4 hours',
            'status' => 'in-transit',
            'expected_delivery_date' => now()->addHours(4),
        ]);

        Suppliers::create([
            'name' => 'Steel Corp',
            'category' => 'Raw Materials',
            'rating' => 4.8,
            'orders' => 156,
            'on_time_delivery' => 95.0,
        ]);

        Suppliers::create([
            'name' => 'ElectroTech',
            'category' => 'Electronics',
            'rating' => 4.6,
            'orders' => 89,
            'on_time_delivery' => 92.0,
        ]);

        Suppliers::create([
            'name' => 'Rubber Industries',
            'category' => 'Materials',
            'rating' => 4.9,
            'orders' => 203,
            'on_time_delivery' => 98.0,
        ]);

        Suppliers::create([
            'name' => 'Paint Solutions',
            'category' => 'Chemicals',
            'rating' => 4.5,
            'orders' => 67,
            'on_time_delivery' => 88.0,
        ]);
        */
    }
}
