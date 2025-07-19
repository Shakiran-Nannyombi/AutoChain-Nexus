<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\User;

class SupplierOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $manufacturer = User::where('role', 'manufacturer')->where('status', 'approved')->first();
        $suppliers = User::where('role', 'supplier')->where('status', 'approved')->get();
        if (!$manufacturer || $suppliers->isEmpty()) {
            Log::warning('No approved manufacturer or suppliers found for SupplierOrdersSeeder.');
            return;
        }
        $materialsList = [
            ['Steel', 'Rubber', 'Glass'],
            ['Plastic', 'Aluminum', 'Paint'],
            ['Copper', 'Nickel', 'Electronics'],
            ['Iron', 'Lead', 'Upholstery'],
            ['Zinc', 'Foam', 'Tires'],
            ['Leather', 'Adhesives', 'Wiring'],
            ['Batteries', 'Sensors', 'Lighting'],
            ['Transmission Fluid', 'Coolant', 'Brake Pads'],
            ['Windshields', 'Mirrors', 'Exhaust'],
            ['Catalytic Converter', 'Radiator', 'Springs'],
        ];
        $statuses = ['pending', 'fulfilled', 'declined'];
        $now = Carbon::now();
        $orders = [];
        foreach ($suppliers as $i => $supplier) {
            $allMaterials = $materialsList[$i % count($materialsList)];
            $numMaterials = rand(2, min(5, count($allMaterials)));
            $selected = array_rand(array_flip($allMaterials), $numMaterials);
            $materials = [];
            foreach ((array)$selected as $mat) {
                $materials[$mat] = rand(5, 20);
            }
            // Randomize status for variety
            $status = $statuses[array_rand($statuses)];
            $orders[] = [
                'manufacturer_id' => $manufacturer->id,
                'supplier_id' => $supplier->id,
                'materials_requested' => json_encode($materials),
                'status' => $status,
                'created_at' => $now->copy()->subDays(rand(1, 10)),
                'updated_at' => $now->copy()->subDays(rand(1, 10)),
            ];
        }
        DB::table('checklist_requests')->insert($orders);
    }
} 