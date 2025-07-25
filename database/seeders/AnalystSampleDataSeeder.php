<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RetailerOrder;
use App\Models\RetailerStock;
use App\Models\Retailer;
use App\Models\RetailerSale;
use App\Models\SupplierStock;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnalystSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $supplier = User::where('role', 'supplier')->first();
            $retailerUser = User::where('role', 'retailer')->first();
            $manufacturer = User::where('role', 'manufacturer')->first();

            // Make sure required users exist
            if (!$supplier || !$retailerUser || !$manufacturer) {
                throw new \Exception("Required users (supplier, retailer, manufacturer) not found.");
            }

            // Link a retailer account to the retailer user (if not already done)
            $retailer = Retailer::firstOrCreate(
                ['user_id' => $retailerUser->id],
                ['name' => $retailerUser->name,
                'email' => $retailerUser->email,
                'phone' => '123-456-7890',
                'address' => '123 Retailer Street',
                'password' => Hash::make('autochainnexus'),
                'company' => $retailerUser->company,
            ],

            );

            // Supplier Stock
            SupplierStock::factory()->count(5)->create([
                'supplier_id' => $supplier->id,
            ]);

            // Retailer Stock
            RetailerStock::factory()->count(5)->create([
                'retailer_id' => $retailer->id,
                'vendor_id' => $supplier->id,
            ]);

            // Retailer Sales
            RetailerSale::factory()->count(10)->create([
                'retailer_id' => $retailer->id,
            ]);

            // Retailer Orders
            RetailerOrder::factory()->count(6)->create([
                'user_id' => $retailer->id,
            ]);

            // Deliveries from supplier to manufacturer
            Delivery::factory()->count(4)->create([
                'supplier_id' => $supplier->id,
                'manufacturer_id' => $manufacturer->id,
                'materials_delivered' => [
                    'engine parts' => rand(10, 30),
                    'glass' => rand(5, 20)
                ]
            ]);
        });
    }
    
    }

