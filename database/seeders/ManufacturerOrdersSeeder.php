<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ManufacturerOrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Demo Supplier Orders (ChecklistRequest)
        DB::table('checklist_requests')->insert([
            [
                'manufacturer_id' => 9, // Sarah Johnson
                'supplier_id' => 15, // Lisa Wang
                'materials_requested' => json_encode(['Steel' => 10, 'Rubber' => 5]),
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'manufacturer_id' => 10, // Michael Chen
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Plastic' => 20]),
                'status' => 'fulfilled',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'manufacturer_id' => 11, // Emily Rodriguez
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Copper' => 15]),
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'manufacturer_id' => 12, // Priya Patel
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Glass' => 8, 'Iron' => 12]),
                'status' => 'cancelled',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'manufacturer_id' => 13, // Liam O'Connor
                'supplier_id' => 15,
                'materials_requested' => json_encode(['Nickel' => 7, 'Lead' => 11]),
                'status' => 'fulfilled',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ]);

        // Demo Vendor Orders (vendor_orders table)
        $manufacturerUserId = DB::table('users')->where('role', 'manufacturer')->where('status', 'approved')->value('id');
        $vendorUserIds = DB::table('users')->where('role', 'vendor')->where('status', 'approved')->pluck('id');
        if ($manufacturerUserId && $vendorUserIds->count() >= 2) {
        DB::table('vendor_orders')->insert([
            [
                    'manufacturer_id' => $manufacturerUserId,
                    'vendor_id' => $vendorUserIds[0],
                'product' => 'Packaging Boxes',
                'quantity' => 100,
                'status' => 'fulfilled',
                'ordered_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                    'manufacturer_id' => $manufacturerUserId,
                    'vendor_id' => $vendorUserIds[1],
                'product' => 'Labels',
                'quantity' => 500,
                'status' => 'pending',
                'ordered_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ]);
        }

        // Demo Confirmed Deliveries (fulfilled orders)
        DB::table('deliveries')->insert([
            [
                'supplier_id' => 14, // David Thompson
                'manufacturer_id' => 9, // Sarah Johnson
                'materials_delivered' => json_encode(['Steel' => 10, 'Rubber' => 5]),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'supplier_id' => 15, // Lisa Wang
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Plastic' => 20, 'Glass' => 8]),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'supplier_id' => 16, // Robert Kim
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Copper' => 15, 'Aluminum' => 12]),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'supplier_id' => 17, // Olga Ivanova
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Iron' => 18, 'Nickel' => 7]),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'supplier_id' => 18, // Ahmed Hassan
                'manufacturer_id' => 9,
                'materials_delivered' => json_encode(['Zinc' => 9, 'Lead' => 11]),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ]);
    }
} 