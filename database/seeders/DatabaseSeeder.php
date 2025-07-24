<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    
        // User::factory(10)->create();

        $this->call([
            AdminUserSeeder::class,
            ApprovedAndPendingUsersSeeder::class,
            //VendorSeeder::class,
            ManufacturerSeeder::class,
            ValidationRuleSeeder::class,
            FacilityVisitSeeder::class,
            AnalystSampleDataSeeder::class,
            DemoNotificationsSeeder::class,
            ProductsTableSeeder::class,
            CustomersTableSeeder::class,
            PurchasesTableSeeder::class,
            ManufacturerOrdersSeeder::class,
            CustomerSegmentDemoSeeder::class, // Add this line
            VarietyCustomerPurchaseSeeder::class, // Add this line
            //VendorOrderSeeder::class,
            ProcessFlowSeeder::class, // <-- Ensure this is included
            VendorOrderSeeder::class, // <-- Add this to seed vendor sales data
            RetailerOrderSeeder::class, // <-- Add this to seed retailer orders
            DemandPredictionSeeder::class,
            SupplierOrderSeeder::class, // Connect supplier order seeder
            SupplierOrdersSeeder::class, // Connect checklist request seeder
        ]);

        // Seed SupplierStock and Delivery for all approved suppliers
        $suppliers = \App\Models\User::where('role', 'supplier')->where('status', 'approved')->get();
        foreach ($suppliers as $supplier) {
            // Seed 3-5 stocks per supplier
            \App\Models\SupplierStock::factory()->count(rand(3,5))->create([
                'supplier_id' => $supplier->id
            ]);
            // Seed 2-3 deliveries per supplier
            \App\Models\Delivery::factory()->count(rand(2,3))->create([
                'supplier_id' => $supplier->id
            ]);
        }

        // Seed demo communications for active connections
        $manufacturer = \App\Models\User::where('role', 'manufacturer')->first();
        $supplier = \App\Models\User::where('role', 'supplier')->first();
        $vendor = \App\Models\User::where('role', 'vendor')->first();
        $retailer = \App\Models\User::where('role', 'retailer')->first();
        $analyst = \App\Models\User::where('role', 'analyst')->first();

        if ($manufacturer && $supplier) {
            \App\Models\Communication::create([
                'sender_id' => $manufacturer->id,
                'receiver_id' => $supplier->id,
                'type' => 'message',
                'content' => 'Initial partnership discussion',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed demo process flows for flow performance metrics
        $now = now();
        $stages = ['raw_materials', 'manufacturing', 'quality_control', 'distribution', 'retail'];
        $items = [
            ['item_name' => 'Order A', 'current_stage' => 'manufacturing', 'status' => 'in_progress', 'entered_stage_at' => $now->copy()->subDays(4), 'completed_stage_at' => null, 'failure_reason' => null], // bottleneck
            ['item_name' => 'Order B', 'current_stage' => 'quality_control', 'status' => 'completed', 'entered_stage_at' => $now->copy()->subDays(2), 'completed_stage_at' => $now->copy()->subDay(), 'failure_reason' => null],
            ['item_name' => 'Order C', 'current_stage' => 'distribution', 'status' => 'in_progress', 'entered_stage_at' => $now->copy()->subDay(), 'completed_stage_at' => null, 'failure_reason' => null],
            ['item_name' => 'Order D', 'current_stage' => 'manufacturing', 'status' => 'failed', 'entered_stage_at' => $now->copy()->subDays(3), 'completed_stage_at' => null, 'failure_reason' => 'Equipment failure'],
            ['item_name' => 'Order E', 'current_stage' => 'retail', 'status' => 'completed', 'entered_stage_at' => $now->copy()->subDays(1), 'completed_stage_at' => $now, 'failure_reason' => null],
            ['item_name' => 'Order F', 'current_stage' => 'raw_materials', 'status' => 'in_progress', 'entered_stage_at' => $now->copy()->subHours(12), 'completed_stage_at' => null, 'failure_reason' => null],
            ['item_name' => 'Order G', 'current_stage' => 'quality_control', 'status' => 'failed', 'entered_stage_at' => $now->copy()->subDays(2), 'completed_stage_at' => null, 'failure_reason' => 'Failed quality check'],
        ];
        foreach ($items as $item) {
            \App\Models\ProcessFlow::create($item);
        }

    }
}
